<?php
require "DB_Conns/dbconn.php";
class DrinkRequests{

    public function getFullRecipeByID(int $id){
        //echo "ID: ".$id;
        $recipe =array();
        $ingredients = $this->getTotalIngredientsForRecipe($id);
        $instructions =$this->getInstructionsByID($id);
        $headers = $this->getRecipeHeadersByID($id);
        $history = $this->get_History_By_ID($id);
        $glassName = $this->getGlassType($headers["glassTypeID"]);

        $recipe["ingredients"] = $ingredients;
        $recipe["instructions"] = $instructions;
        $recipe["name"] = $headers["name"];
        $recipe["website"] = $headers["website"];
        $recipe["notes"] = $headers["notes"];
        $recipe["drinkType"] = $headers["drinkType"];
        $recipe["isClassics"] = $headers["isClassics"];

        $recipe["glassType"] = $glassName;
        $recipe["history"] = $history;

        return $recipe;
    }



    public function getRecipeHeadersByID(int $id){
        global $conn;
        $sql = "SELECT * FROM drink_headers WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row;
    }


    /**
     * @param int $id
     * @return array 2D, each array has the ingred/amount/measure/comment as a stringin it
     */
    public function getTotalIngredientsForRecipe(int $id){
        global $conn;
        $sql = "SELECT * FROM ingredient_Mapper WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        //$row=mysqli_fetch_assoc($result);
        $recipe = array();
        $garnishes = array();
        while ($row=mysqli_fetch_assoc($result)){
            //print_r($row);

            $ingred = $this->select_Ingredient_By_ID($row["ingredID"]);
            $amount = $this->select_Amount_By_ID($row["amountID"]);
            $measure = $this->select_Measure_By_ID($row["measureID"]);
            $comment = $this->select_Comment_By_ID($row["commentID"]);

            if($ingred["ingredType"] == "garnish"){
                $tmpStr = $amount." ".$measure." ".$ingred["ingredient"]." ".$comment." (".$ingred["ingredType"].")";
                array_push($garnishes,$tmpStr);
            }else{
                $tmpStr = $amount." ".$measure." ".$ingred["ingredient"]." ".$comment;
                array_push($recipe,$tmpStr);
            }

        }

        $retArray = array();
        //array_push($retArrary,$recipe);
        //array_push($retArrary,$garnishes);
        $retArray["ingredsMain"] = $recipe;
        $retArray["garnishes"] = $garnishes;
        return $retArray;


    }



    /**
     * @param int $id
     * @return string of history
     */
    public function get_History_By_ID(int $id){
        global $conn;
        $sql = "SELECT history FROM history WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row["history"];
    }

    /**
     * @param int $id
     * @return string of instructions, seperated by @@@
     */
    public function getInstructionsByID(int $id){
        global $conn;
        $sql = "SELECT instruct FROM instructions WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row["instruct"];
    }


    /**
     * @param int $glassID
     * @return string name of glass
     */
    public function getGlassType(int $glassID){
        global $conn;
        $sql = "SELECT glassType FROM glassType WHERE glassTypeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$glassID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return "DNE";
        }
        $row=mysqli_fetch_assoc($result);
        return $row["glassType"];
    }

    public function getGlassTypeByName(string $glassName){
        global $conn;
        $sql = "SELECT glassTypeID FROM glassType WHERE glassType=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$glassName);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["glassTypeID"];
    }



    /**
     * @param int $id
     * @return array of ingredient and ingredType
     */
    public function select_Ingredient_By_ID(int $id){
        global $conn;
        $sql = "SELECT ingredient,ingredType FROM ingredients WHERE ingredID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row;

    }


    /**
     * @param string $name
     * @return int the ingredientID
     */
    public function select_IngredientID_By_Name(string $name){
        global $conn;
        $sql = "SELECT ingredID FROM ingredients WHERE ingredient=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$name);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["ingredID"];
    }

    public function select_IngredientID_AND_Type_By_Name(string $name){
        global $conn;
        $sql = "SELECT ingredID FROM ingredients WHERE ingredient=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$name);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row;
    }

    public function select_Amount_By_ID(int $id){
        global $conn;
        $sql = "SELECT amount FROM amount WHERE amountID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row["amount"];
    }

    public function select_Measure_By_ID(int $id){
        global $conn;
        $sql = "SELECT measure FROM measure WHERE measureID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row["measure"];
    }

    public function select_Comment_By_ID(int $id){
        global $conn;
        $sql = "SELECT comment FROM comment WHERE commentID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        return $row["comment"];
    }

    /**
     * @param int $id
     * @return array of id and name
     */
    public function select_Name_By_ID(int $id){
        global $conn;
        $sql = "SELECT name FROM drink_headers WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);
        //$resVal = ;

        return [$id,$row["name"]];
    }


    public function searchRecipesIncludingStrict($ingredientList,$useDefaultList){
        global $conn;
        if($useDefaultList){

        }
        $id_list = array();

        $garnishString = "garnish";

        $sql = "SELECT ingredID FROM ingredients WHERE upper(ingredient) LIKE ?";
        //$sql = "SELECT ingredID FROM ingredients WHERE upper(ingredient) LIKE ? AND NOT ingredType=? ";
        foreach ($ingredientList as $ingred){

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$ingred);
            $stmt->execute();
            $result = $stmt->get_result();
            //$row=mysqli_fetch_assoc($result);
            while ($row=mysqli_fetch_assoc($result)){
                array_push($id_list,$row["ingredID"]);
            }


        }
        $garnishArray = $this->getGarnishIDs();

        $id_list = array_merge($id_list,$garnishArray);


        $sql = "SELECT DISTINCT recipeID ".
            "FROM ingredient_Mapper ".
            "WHERE recipeID NOT IN (".
            "SELECT recipeID ".
            "FROM ingredient_Mapper ".
            "WHERE ingredID NOT IN (";



// Add placeholders for parameters
        //$placeholders = rtrim(str_repeat("?,", count($id_list)), ",");
        $placeholders = implode(",",$id_list);
        $sql .= $placeholders . "))";
        /*
                echo $sql;
                if (!mysqli_query($conn,$sql))
                {
                    echo("Error description: " . mysqli_error($conn));
                }
                */
        $stmt = $conn->prepare($sql);
        //$stmt->bind_param("s",$ingred);
        $stmt->execute();
        $result = $stmt->get_result();
        //$stmt = $conn.createStatement();

        $resAssocArray = array();
        $idx = 0;
        //print_r($ingredientList);
        $drinkIds = array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($drinkIds,$row["recipeID"]);
        }
        /*

        while ($row=mysqli_fetch_assoc($result)){
            $dict_idx = "recipe_".$idx;
            $resAssocArray[$dict_idx] = $this->select_Name_By_ID($row["recipeID"]);
            $idx ++;
        }
        */
        //$resArray = array();
        /*
        $idx=0;
        while ($row=mysqli_fetch_assoc($result)){
            //array_push($resArray,$this->)
            $dict_idx = "recipe_".$idx;
            $idx++;
        }
        */
        $resArray = $this->getAllDrinkHeadersFromIDs($drinkIds);
        return $resArray;



    }


    /**
     * @param string $amountName
     * @return int
     */
    public function getAmountIDByName(string $amountName){
        global $conn;
        $sql = "SELECT amountID FROM amount WHERE amount=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$amountName);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["amountID"];
    }

    /**
     * @param string $measureName
     * @return int
     */
    public function getMeasureByName(string $measureName){
        global $conn;
        $sql = "SELECT measureID FROM measure WHERE measure=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$measureName);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["measureID"];
    }

    /**
     * @param string $commentName
     * @return int
     */
    public function getCommentByName(string $commentName){
        global $conn;
        $sql = "SELECT commentID FROM comment WHERE comment=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$commentName);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["commentID"];
    }


    /**
     * @param int $recipeID
     * @return string
     */
    public function getHistoryWaiting(int $recipeID){
        global $conn;
        $sql = "SELECT history FROM history_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["history"];
    }

    public function getInstructionsWaiting(int $recipeID){
        global $conn;
        $sql = "SELECT instruct FROM instructions_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["instruct"];
    }


    /**
     * @param int $recipeID
     * @return array 2d array containing rows from mapper_waiting
     */
    public function getIngredientMapperWaiting(int $recipeID){
        global $conn;
        $sql = "SELECT * FROM ingredient_Mapper_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        //$row=mysqli_fetch_assoc($result);
        $mappedRowsWaiting = array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($mappedRowsWaiting,$row);
        }

        return $mappedRowsWaiting;
    }


    public function getHeaderWaiting($recipeID){
        global $conn;
        $sql = "SELECT * FROM drink_headers_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row;
    }


    /**
     * @param $recipeID
     * @return array pulls ids from the ingredient_mapper only returns IDs
     * Also grabs garnishes
     */
    public function getIngredInfoByRecipeID($recipeID){
        global $conn;
        $sql = "SELECT * FROM ingredient_Mapper WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$recipeID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }

        $ingredientMappingIDs = array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($ingredientMappingIDs,$row);
        }
        return $ingredientMappingIDs;
    }

    public function getIngredNameByID($ingredientID){
        global $conn;
        $sql = "SELECT ingredient FROM ingredients WHERE ingredID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$ingredientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["ingredient"];

    }

    public function getIngredTypeByID($ingredientID){
        global $conn;
        $sql = "SELECT ingredType FROM ingredients WHERE ingredID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$ingredientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return $row["ingredType"];

    }

    public function getIngredNameANDTypeByID($ingredientID){
        global $conn;
        $sql = "SELECT * FROM ingredients WHERE ingredID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$ingredientID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }
        $row=mysqli_fetch_assoc($result);
        return array("ingredType"=>$row["ingredType"],"ingredName"=>$row["ingredient"]);
    }



    /**
     * @param string $name
     * @return array 2d array of the headers
     */
    public function getDrinksByName(string $name){
        global $conn;
        $sql = "SELECT * FROM drink_headers WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $name_modified = "%".$name."%";
        $stmt->bind_param("s",$name_modified);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }


        $drinksArray = array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($drinksArray,$row);
        }
        return $drinksArray;


    }


    /**
     * @param array $drinkIds
     * @return array 2d array of headers
     */
    public function getAllDrinkHeadersFromIDs(array $drinkIds){
        global $conn;
        $parameters = str_repeat('?,', count($drinkIds) - 1) . '?';
        $sql = "SELECT * FROM drink_headers WHERE recipeID in ($parameters)";
        //$stmt = $conn->prepare($sql);
        //$stmt->bind_param(,$drinkIds);
        //$stmt->execute();
        //$result = $stmt->get_result();
        $result = $conn->execute_query($sql, $drinkIds);
        if (!$result->num_rows){
            return -1;
        }

        $headers = [];
        while ($header=mysqli_fetch_assoc($result)){
            //$id = $ingredientArray["ingredID"];
            array_push($headers,$header);
        }

        return $headers;

    }


    /**
     * @param array $ingredients
     * @return array of drinkIDs
     */
    public function getDrinksIDsByIncludes(array $ingredients){
        global $conn;
        $parameters = str_repeat('?,', count($ingredients) - 1) . '?';
        $sql = "SELECT * FROM ingredients WHERE ingredient in ($parameters)";
        //$stmt = $conn->prepare($sql);
        //$stmt->bind_param($ingredients);
        //$stmt->execute();
        //$result = $stmt->get_result();
        $result = $conn->execute_query($sql, $ingredients);
        if (!$result->num_rows){
            return -1;
        }

        $ingredientIds = [];
        while ($ingredientArray=mysqli_fetch_assoc($result)){
            //$id = $ingredientArray["ingredID"];
            array_push($ingredientIds,$ingredientArray["ingredID"]);
        }

        //now search ingredient mapper table to find all drinks that include any of the ingredient IDs

        $parameters = str_repeat('?,', count($ingredientIds) - 1) . '?';
        $sql = "SELECT recipeID FROM ingredient_Mapper WHERE ingredID in ($parameters)";
        //$stmt = $conn->prepare($sql);
        //$stmt->bind_param($ingredientIds);
        //$stmt->execute();
        //$result = $stmt->get_result();
        $result = $conn->execute_query($sql, $ingredientIds);
        if (!$result->num_rows){
            return -1;
        }
        $drinkIDs = array();
        while ($drinkID=mysqli_fetch_assoc($result)){
            array_push($drinkIDs,$drinkID["recipeID"]);
        }

        return $drinkIDs;


    }

    public function getIngredientIDsOfDrinkIDs(array $drinkIdArray){
        global $conn;
        //$drinkIdParamString = implode("")
        $parameters = str_repeat('?,', count($drinkIdArray) - 1) . '?';
        $sql = "SELECT DISTINCT ingredID FROM ingredient_Mapper WHERE recipeID IN ($parameters);";
        $result = $conn->execute_query($sql, $drinkIdArray);
        if (!$result->num_rows){
            return -1;
        }

        $ingredIDList = array();
        while($row=mysqli_fetch_assoc($result)){
            array_push($ingredIDList,$row["ingredID"]);
        }
        return $ingredIDList;



    }


    public function getGarnishIDs(){
        global $conn;
        $sql = "SELECT ingredID FROM ingredients WHERE ingredType=?";
        $garnishString = "garnish";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$garnishString);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result->num_rows){
            return -1;
        }

        $idArray = array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($idArray,$row["ingredID"]);
        }

        return $idArray;

    }





}