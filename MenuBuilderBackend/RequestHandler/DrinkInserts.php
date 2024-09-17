<?php
require "DB_Conns/dbconn.php";

/**
 *
 */
class DrinkInserts{





    /**
     * @param string $headers split by (,) ; use @ to denote nothing in spot
     * @return int
     */
    public function insertHeaders(string $headers){
        global $conn;
        if($conn->errno){
            exit();
        }

        $headers_split = explode(",",$headers);


        $sql = "INSERT INTO drink_headers (website,name,drinkType,isClassics,notes,imageName,glassTypeID) VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('sssssss',$headers_split[0],$headers_split[1],$headers_split[2],$headers_split[3],$headers_split[4],$headers_split[5],$headers_split[6]);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;

    }

    /**
     * @param string $headers split by (,) ; use @ to denote nothing in spot
     * @return int recipeID
     */
    public function insertHeadersWaiting(string $headers){
        global $conn;
        if($conn->errno){
            exit();
        }

        $headers_split = explode(",",$headers);


        $sql = "INSERT INTO drink_headers_waiting (website,name,drinkType,isClassics,notes,imageName,glassTypeID) VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('sssssss',$headers_split[0],$headers_split[1],$headers_split[2],$headers_split[3],$headers_split[4],$headers_split[5],$headers_split[6]);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;

    }

    /**
     * NOT SAFE DONT USE
     * @param string $ingredients split by (,)
     * @param string $ingredType MUST HAVE SAME NUMBER AS INGREDIENTS
     * @return void
     */
    public function insertIngredients(string $ingredients, string $ingredTypes){
        global $conn;
        $ingredients_split = explode(",",$ingredients);
        $ingredType_split = explode(",",$ingredTypes);

        if(count($ingredients_split) != count($ingredType_split)){
            return "ingreds and type do not match";
        }

        for($i=0;$i<count($ingredType_split); $i++){
            $sql = "INSERT INTO ingredients (ingredient,ingredType) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt ->bind_param('ss',$ingredients_split[$i],$ingredType_split[$i]);
            $stmt->execute();
        }

    }


    /**
     * @param string $ingredient
     * @param string $ingredType
     * @return int the ingredients id
     */
    public function insertSingleIngredient(string $ingredient, string $ingredType){
        global $conn;
        $sql = "INSERT INTO ingredients (ingredient,ingredType) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('ss',$ingredient,$ingredType);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;
    }
    public function insertInstructionsWaiting(int $recipeWaitingID,string $instructions){
        global $conn;
        $sql = "INSERT INTO instructions_waiting (recipeID,instruct) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('ss',$recipeWaitingID,$instructions);
        $stmt->execute();
    }

    public function insertInstructions(int $recipeID,string $instructions){
        global $conn;
        $sql = "INSERT INTO instructions (recipeID,instruct) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('ss',$recipeID,$instructions);
        $stmt->execute();
    }

    public function insertHistoryWaiting(int $recipeWaitingID,string $history){
        global $conn;
        $sql = "INSERT INTO history_waiting (recipeID,history) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('ss',$recipeWaitingID,$history);
        $stmt->execute();
    }

    public function insertHistory(int $recipeID,string $history){
        global $conn;
        $sql = "INSERT INTO history (recipeID,history) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('ss',$recipeID,$history);
        $stmt->execute();
    }


    /**
     * @param string $glassType
     * @return int id of the glasstype when inserted
     */
    public function insertGlassType(string $glassType){
        global $conn;
        $sql = "INSERT INTO glassType (glassType) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$glassType);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;
    }

    public function insertAmount(string $amount){
        global $conn;
        $sql = "INSERT INTO amount (amount) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$amount);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;
    }

    public function insertMeasure(string $measure){
        global $conn;
        $sql = "INSERT INTO measure (measure) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$measure);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;
    }

    public function insertComment(string $comment){
        global $conn;
        $sql = "INSERT INTO comment (comment) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$comment);
        $stmt->execute();

        $insertedID = $stmt->insert_id;
        return $insertedID;
    }


    /**
     * @param int $recipeID
     * @param array $ingredIDs
     * @param array $amountIDs
     * @param array $measureIDs
     * @param array $commentIDs
     * @return int
     */
    public function insert_Mappings(int $recipeID, array $ingredIDs, array $amountIDs, array $measureIDs, array $commentIDs){
        global $conn;
        $lengthIngreds = count($ingredIDs);
        if(count($amountIDs)!=$lengthIngreds or count($measureIDs)!=$lengthIngreds or count($commentIDs)!=$lengthIngreds){
            //NOT THE SAME LENGTH, CANNOT ADD to mappings table
            return -1;
        }

        //echo $recipeID;
        //print_r($ingredIDs);

        //print_r($amountIDs);
        //print_r($measureIDs);
        //print_r($commentIDs);
        for($i=0;$i<$lengthIngreds;$i++){
            $sql = "INSERT INTO ingredient_Mapper (recipeID,ingredID,amountID,measureID,commentID) VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt ->bind_param('sssss',$recipeID,$ingredIDs[$i],$amountIDs[$i],$measureIDs[$i],$commentIDs[$i]);
            $stmt->execute();

        }

        return 1;


    }

    public function insert_Mapptings_Waiting(int $recipeID, array $ingredIDs, array $amountIDs, array $measureIDs, array $commentIDs){
        global $conn;
        $lengthIngreds = count($ingredIDs);
        if(count($amountIDs)!=$lengthIngreds or count($measureIDs)!=$lengthIngreds or count($commentIDs)!=$lengthIngreds){
            //NOT THE SAME LENGTH, CANNOT ADD to mappings table
            return -1;
        }

        for($i=0;$i<$lengthIngreds;$i++){
            $sql = "INSERT INTO ingredient_Mapper_Waiting (recipeID,ingredID,amountID,measureID,commentID) VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt ->bind_param('sssss',$recipeID,$ingredIDs[$i],$amountIDs[$i],$measureIDs[$i],$commentIDs[$i]);
            $stmt->execute();

        }

        return 1;
    }

    public function insertMappingsFromWaiting($recipeID,array $mappings){
        global $conn;
        foreach ($mappings as $map){
            $sql = "INSERT INTO ingredient_Mapper (recipeID,ingredID,amountID,measureID,commentID) VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt ->bind_param('sssss',$recipeID,$map["ingredID"],$map["amountID"],$map["measureID"],$map["commentID"]);
            $stmt->execute();
        }
        return 1;
    }






}