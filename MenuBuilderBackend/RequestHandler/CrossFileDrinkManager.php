<?php
require_once "RequestHandler/DrinkDeletions.php";
require_once "RequestHandler/DrinkInserts.php";
require_once "RequestHandler/DrinkRequests.php";
class CrossFileDrinkManager{
    private $DrinkDeletions;
    private $DrinkInserts;
    private $DrinkRequests;

    function __construct() {
        $this->DrinkDeletions = new DrinkDeletions();
        $this->DrinkInserts = new DrinkInserts();
        $this->DrinkRequests = new DrinkRequests();
    }


    /**
     * @param String $headers
     * @param String $ingreds
     * @param String $ingredTypes
     * @param String $measureString
     * @param String $amountString
     * @param String $commentString
     * @param String $instructions
     * @param String $history
     * @return int|string
     */
    public function insertWholeDrink(String $headers, String $ingreds, String $ingredTypes, String $measureString, String $amountString, String $commentString, String $instructions, String $history){
        $headers_split = explode(",",$headers);
        $headers_split = array_map([$this, 'trim_space'],$headers_split);
        //print_r($headers_split);
        if (count($headers_split) != 7){
            //need to error out in some way
        }

        //check and insert glass type
        $glassID = $this->DrinkRequests->getGlassTypeByName($headers_split[6]);
        if($glassID == -1){
            $glassID = $this->DrinkInserts->insertGlassType($headers_split[6]);
        }


        $headers_split[6] = $glassID;

        $headers = implode(",",$headers_split);


        //$this->DrinkInserts->insertHeaders($headers);

        //preparing ingreds, measures, etc
        $ingredients_split = explode(",",$ingreds);
        $ingredients_split = array_map([$this, 'trim_space'],$ingredients_split);

        $ingredientsType_split = explode(",",$ingredTypes);
        $ingredientsType_split = array_map([$this, 'trim_space'],$ingredientsType_split);

        $measures_split = explode(",",$measureString);
        $measures_split = array_map([$this, 'trim_space'],$measures_split);

        $amounts_split =explode(",",$amountString);
        $amounts_split = array_map([$this, 'trim_space'],$amounts_split);

        $comments_split = explode(",",$commentString);
        $comments_split = array_map([$this, 'trim_space'],$comments_split);

        if(count($ingredients_split) != count($ingredientsType_split)){
            return "MISMATCH of ingredients and types";
        }

        //handling ingreds and getting IDS
        $ingredientIDs_list= $this->handleIncomingIngredients($ingredients_split, $ingredientsType_split);
        $amountIDs_list = $this->handleIncomingAmounts($amounts_split);
        $measuresIDs_list = $this->handleIncomingMeasures($measures_split);
        $commentsIDs_list = $this->handleIncomingComment($comments_split);


        //inserting drink headers and getting ID
        $drinkID = $this->DrinkInserts->insertHeaders($headers);

        //inserting mappings
        $mappingsResult = $this->DrinkInserts->insert_Mappings($drinkID,$ingredientIDs_list,$amountIDs_list,$measuresIDs_list,$commentsIDs_list);

        if($mappingsResult != 1){
            //failed
        }

        //inserting history and instructions with the ID
        $this->DrinkInserts->insertHistory($drinkID,$history);

        $this->DrinkInserts->insertInstructions($drinkID,$instructions);


        return 1;

    }

    public function insertWholeDrinkWaiting(String $headers, String $ingreds, String $ingredTypes, String $measureString, String $amountString, String $commentString, String $instructions, String $history){
        $headers_split = explode(",",$headers);
        $headers_split = array_map([$this, 'trim_space'],$headers_split);
        //print_r($headers_split);
        if (count($headers_split) != 7){
            //need to error out in some way
        }

        //check and insert glass type
        $glassID = $this->DrinkRequests->getGlassTypeByName($headers_split[6]);
        if($glassID == -1){
            $glassID = $this->DrinkInserts->insertGlassType($headers_split[6]);
        }


        $headers_split[6] = $glassID;

        $headers = implode(",",$headers_split);


        //$this->DrinkInserts->insertHeaders($headers);

        //preparing ingreds, measures, etc
        $ingredients_split = explode(",",$ingreds);
        $ingredients_split = array_map([$this, 'trim_space'],$ingredients_split);

        $ingredientsType_split = explode(",",$ingredTypes);
        $ingredientsType_split = array_map([$this, 'trim_space'],$ingredientsType_split);

        $measures_split = explode(",",$measureString);
        $measures_split = array_map([$this, 'trim_space'],$measures_split);

        $amounts_split =explode(",",$amountString);
        $amounts_split = array_map([$this, 'trim_space'],$amounts_split);

        $comments_split = explode(",",$commentString);
        $comments_split = array_map([$this, 'trim_space'],$comments_split);

        if(count($ingredients_split) != count($ingredientsType_split)){
            return "MISMATCH of ingredients and types";
        }

        //handling ingreds and getting IDS
        $ingredientIDs_list= $this->handleIncomingIngredients($ingredients_split, $ingredientsType_split);
        $amountIDs_list = $this->handleIncomingAmounts($amounts_split);
        $measuresIDs_list = $this->handleIncomingMeasures($measures_split);
        $commentsIDs_list = $this->handleIncomingComment($comments_split);

        //inserting drink headers and getting ID into the waiting table
        $drinkID = $this->DrinkInserts->insertHeadersWaiting($headers);

        //inserting mappings
        $mappingsResult = $this->DrinkInserts->insert_Mapptings_Waiting($drinkID,$ingredientIDs_list,$amountIDs_list,$measuresIDs_list,$commentsIDs_list);

        if($mappingsResult != 1){
            //failed
        }

        //inserting history and instructions with the ID
        $this->DrinkInserts->insertHistoryWaiting($drinkID,$history);

        $this->DrinkInserts->insertInstructionsWaiting($drinkID,$instructions);


        return 1;
    }

    public function moveWaitingToRegular(int $recipeWaitingID){
        $headers = $this->DrinkRequests->getHeaderWaiting($recipeWaitingID);
        $history = $this->DrinkRequests->getHistoryWaiting($recipeWaitingID);
        $instructions = $this->DrinkRequests->getInstructionsWaiting($recipeWaitingID);
        $ingredientMappings = $this->DrinkRequests->getIngredientMapperWaiting($recipeWaitingID);

        $this->DrinkDeletions->deleteHeadersWaiting($recipeWaitingID);
        $this->DrinkDeletions->deleteHistoryWaiting($recipeWaitingID);
        $this->DrinkDeletions->deleteInstructionsWaiting($recipeWaitingID);
        $this->DrinkDeletions->deleteIngredientMapperWaiting($recipeWaitingID);

        print_r($headers);
        $headers_to_use = array();
        array_push($headers_to_use,$headers["website"],$headers["name"],$headers["drinkType"],$headers["isClassics"],$headers["notes"],$headers["imageName"],$headers["glassTypeID"]);
        $headersString = implode(",",$headers_to_use);
        $drinkID = $this->DrinkInserts->insertHeaders($headersString);

        $this->DrinkInserts->insertHistory($drinkID,$history);
        $this->DrinkInserts->insertInstructions($drinkID,$instructions);

        $this->DrinkInserts->insertMappingsFromWaiting($drinkID,$ingredientMappings);

        return 1;







    }


    public function handleIncomingIngredients($ingredients_split,$ingredientsType_split){
        $ingredientIDs_list = [];
        for($i=0;$i<count($ingredients_split);$i++){
            $ingredientID= $this->DrinkRequests->select_IngredientID_By_Name($ingredients_split[$i]);

            if($ingredientID == -1){
                //ingred does not exist, add
                $ingredientID=$this->DrinkInserts->insertSingleIngredient($ingredients_split[$i],$ingredientsType_split[$i]);
            }

            array_push($ingredientIDs_list,$ingredientID);

        }

        return $ingredientIDs_list;
    }

    public function handleIncomingAmounts($amounts_split){
        $amountIDs_list = [];
        for($i=0;$i<count($amounts_split);$i++){
            $amountID = $this->DrinkRequests->getAmountIDByName($amounts_split[$i]);

            if($amountID == -1){
                //ingred does not exist, add
                $amountID=$this->DrinkInserts->insertAmount($amounts_split[$i]);
            }

            array_push($amountIDs_list,$amountID);
        }

        return $amountIDs_list;

    }

    public function handleIncomingMeasures($measures_split){
        $mesureIDs_list = [];
        for($i=0;$i<count($measures_split);$i++){
            $measureID = $this->DrinkRequests->getMeasureByName($measures_split[$i]);

            if($measureID == -1){

                $measureID=$this->DrinkInserts->insertMeasure($measures_split[$i]);
            }

            array_push($mesureIDs_list,$measureID);
        }

        return $mesureIDs_list;

    }

    public function handleIncomingComment($comments_split){
        $commentIDs_list = [];
        for ($i=0;$i<count($comments_split);$i++){
            $commentID = $this->DrinkRequests->getCommentByName($comments_split[$i]);
            if($commentID == -1){

                $commentID=$this->DrinkInserts->insertComment($comments_split[$i]);
            }

            array_push($commentIDs_list,$commentID);
        }
        return $commentIDs_list;
    }

    public function trim_space($str)
    {
        return trim($str," ");
    }

}