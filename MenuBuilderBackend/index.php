<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'ErrorHandler.php';
require './RequestHandler/Auth.php';
require_once './RequestHandler/DrinkRequests.php';
require_once './RequestHandler/CrossFileDrinkManager.php';
require_once './RequestHandler/MenuHandler.php';
require_once './PDFbuilder/PDFmanager.php';

set_exception_handler("\\ErrorHandler::handleException");
//echo "2";
header("Content-type: application/json; charset=UTF-8");
//echo "3";
$url_parts = explode("/",$_SERVER["REQUEST_URI"]);
#print_r($url_parts);
//echo "4";
$url_request = $url_parts[1];

$authController = new Auth();
$requestController = new DrinkRequests();
$CrossFileDrinkManager = new CrossFileDrinkManager();


$menuHandler = new MenuHandler();
$pdfManager = new PDFmanager();

if( $url_request== "login"){
    //print_r("login called");
    $token = $authController->handle_login($_POST["username"],$_POST["password"]);
    print_r($token);
    //print_r($token.getBearerToken());

    //$verifiedToken = $authController->verify_jwt($token);
    //print_r((array)$verifiedToken);
    //$headers = getAuthorizationHeader();

    return;

}elseif($url_request== "signup"){
    //print_r("signup called");
    $token = $authController->handle_signup($_POST["username"],$_POST["password"]);
    print_r($token);
    return;

}elseif($url_request== "searchByListStrict"){
    //maybe dont require a login?

    $ingredientString  = $_POST["ingredients"];

    $ingredientArray = explode(",",$ingredientString);
    //print_r($ingredientArray);
    $ingredientArray = array_filter($ingredientArray);
    //print_r($ingredientArray);



    $recipeArray = $requestController->searchRecipesIncludingStrict($ingredientArray,$_POST["useDefaultList"]);
    $retVal = convertDrinksToAssoc($recipeArray);
    print_r(json_encode($retVal));
    //$drinkIDs = $requestController->getDrinksIDsByIncludes($ingredientArray);
    //$drinkHeaders = $requestController->getAllDrinkHeadersFromIDs($drinkIDs);

    //$resVal = convertDrinksToAssoc($drinkHeaders);
    //print_r(json_encode($resVal));

    /*
    $headers = getAuthorizationHeader();

    if(isset($headers)){
        $token = getBearerToken();

        $val = $authController->verify_jwt($token);
        //echo "---";
        $val = (array) $val;
        //print_r($val);

        if(isset($val["code"])){
            //echo "CODE SET";

        }else{
            //echo "NOT SET";
            $ingredList = explode(",",$_POST["ingredList"]);
            $recipeArray = $requestController->searchRecipesIncludingStrict($ingredList,$_POST["useDefaultList"]);
            //print_r($recipeArray);
            print_r(json_encode($recipeArray));
        }

    }else{
        echo "HEADERS NOT SET";
        print_r($headers);
    }

    */

}elseif($url_request== "homepage"){

}elseif($url_request== "download"){
    $filename = "BarPro-v1_0";
    $file_url = 'contentDir/'.$filename.'.pkg';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
    readfile($file_url);
}elseif($url_request== "insertDrink"){
    $headers = $_POST["headers"];
    $ingreds = $_POST["ingreds"];
    $ingredTypes = $_POST["ingredTypes"];
    $measures = $_POST["measures"];
    $amounts = $_POST["amounts"];
    $comments = $_POST["comments"];
    $instructions = $_POST["instructions"];
    $history = $_POST["history"];

    $insertionResult = $CrossFileDrinkManager->insertWholeDrink($headers,$ingreds,$ingredTypes,$measures,$amounts,$comments,$instructions,$history);
    //echo $insertionResult;
    //$retVal=array();
    $retVal["insertionResult"] = $insertionResult;
    print_r(json_encode($retVal));

}elseif($url_request== "runInserter"){
    $json = file_get_contents('./DO-NOT-INCLUDE/personalDrinkAdder.json');

    $json_data = json_decode($json,true);
    print_r($json_data);

    foreach($json_data as $key => $value)
    {
        $headersString = "";
        $headersString = $headersString.$value["website"].",".$value["name"].",".$value["drinkType"].",".$value["isClassics"].",".$value["note"].",".$value["imageName"].",".$value["glassType"];
        $ingredString = implode(",",$value["ingredients"]);
        $ingredTypeString = implode(",",$value["ingredTypes"]);

        $measureString = implode(",",$value["measures"]);
        $amountString = implode(",",$value["amounts"]);
        $commentString = implode(",",$value["comment"]);

        $ingredCount = count($value["ingredients"]);
        if(count($value["ingredTypes"])!=$ingredCount or count($value["measures"])!=$ingredCount or count($value["measures"])!=$ingredCount or count($value["comment"])!=$ingredCount ){

        }else{
            $CrossFileDrinkManager->insertWholeDrink($headersString,$ingredString,$ingredTypeString,$measureString,$amountString,$commentString,$value["instructions"],$value["history"]);
        }



    }


}elseif($url_request== "insertDrinkWaiting"){
    $headers = $_POST["headers"];
    $ingreds = $_POST["ingreds"];
    $ingredTypes = $_POST["ingredTypes"];
    $measures = $_POST["measures"];
    $amounts = $_POST["amounts"];
    $comments = $_POST["comments"];
    $instructions = $_POST["instructions"];
    $history = $_POST["history"];

    $insertionResult = $CrossFileDrinkManager->insertWholeDrinkWaiting($headers,$ingreds,$ingredTypes,$measures,$amounts,$comments,$instructions,$history);

    $retVal["insertionResult"] = $insertionResult;
    print_r(json_encode($retVal));
}elseif($url_request== "acceptWaitingDrink"){
    $waitingDrinkID = $_POST["waitingDrinkID"];

    $moveResult = $CrossFileDrinkManager->moveWaitingToRegular($waitingDrinkID);

    $retVal["moveResult"] = $moveResult;
    print_r(json_encode($retVal));

}elseif($url_request== "nameSearch"){
    $name = $_POST["drinkName"];

    $searchResult = $requestController->getDrinksByName($name);

    if ($searchResult == -1){
        $failureArray = array();
        $failureArray["error"] = "error";
        print_r(json_encode($failureArray));
    }else{
        $retVal = array();
        $idx = 0;
        foreach ($searchResult as $searchRes){
            $dict_idx = "recipe_".$idx;
            $retVal[$dict_idx] = $searchRes;
            $idx ++;
        }
        print_r(json_encode($retVal));
    }
}elseif($url_request== "getGlassType"){
    $glassID = $_POST["glassTypeID"];

    $glassType = $requestController->getGlassType((int)$glassID);
    $result = array();
    $result["glassType"] = $glassType;
    print_r(json_encode($result));
}elseif($url_request== "searchByIncludes"){
    $ingredientString  = $_POST["ingredients"];

    $ingredientArray = explode(",",$ingredientString);

    $drinkIDs = $requestController->getDrinksIDsByIncludes($ingredientArray);
    //print_r($drinkIDs);
    if($drinkIDs == -1){
        $resVal["code"] ="DNE";
        print_r(json_encode($resVal));
    }else{
        $drinkHeaders = $requestController->getAllDrinkHeadersFromIDs($drinkIDs);

        $resVal = convertDrinksToAssoc($drinkHeaders);
        print_r(json_encode($resVal));
    }


}elseif ($url_request == "getDrinkByID"){
    $drinkID = $_POST["drinkID"];
    $retVal = $requestController->getFullRecipeByID($drinkID);
    print_r(json_encode($retVal));
}elseif ($url_request == "getUserMenus"){
    $value = preformAuthorizationCheck($authController);

    if($value == false){
        echo "VALUE FALSE";
        $errorReturn["ERR"] = "SERVERERROR";
        //print_r($errorReturn);
    }else{
        $uID = $value["uid"];

        $menus = $menuHandler->getUserMenus($uID);
        //print_r($menus);

        $retVal = array();
        $idx = 0;
        foreach ($menus as $searchRes){
            $dict_idx = "recipe_".$idx;
            $retVal[$dict_idx] = $searchRes;
            $idx ++;
        }
        print_r(json_encode($retVal));


    }
}elseif ($url_request == "checkDrinkInMainMenu"){
    $headers = getAuthorizationHeader();
    if(isset($headers)){
        $token = getBearerToken();

        $val = (array) $authController->verify_jwt($token);
        //print_r($val);

        if(isset($val["code"])){
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        }else{
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            $drinkID = $_POST["drinkID"];

            $drinkExists = $menuHandler->checkIfDrinkExistsInMainMenu($uID,$drinkID);

            $retVal["drinkExists"] = $drinkExists;
            print_r(json_encode($retVal));

        }

    }else{
        echo "HEADERS NOT SET";
        print_r($headers);
    }
}elseif ($url_request == "addDrinkToMainMenu"){
    $value = preformAuthorizationCheck($authController);
    if ($value == false){
        $errorReturn["ERR"] = "SERVERERROR";
        print_r($errorReturn);
    }else{
        $uID = $value["uid"];
        //print_r($uID);
        $drinkID = $_POST["drinkID"];
        $returnVal = $menuHandler->updateMenu($uID,"main",$drinkID);
        //print_r($returnVal);
        if ($returnVal == -2){
            //$retError["Error"] = "Error"
            return -2;
        }
        $val["msg"] = "success";
        print_r(json_encode($val));

    }
}elseif ($url_request=="deleteDrinkFromMainMenu"){
    $value = preformAuthorizationCheck($authController);
    if ($value == false){
        $errorReturn["ERR"] = "SERVERERROR";
        print_r($errorReturn);
    }else{
        $uID = $value["uid"];
        //print_r($uID);
        $drinkID = $_POST["drinkID"];
        $returnVal = $menuHandler->deleteDrinkIDFromMenu($uID,"main",$drinkID);
        //print_r($returnVal);
        if ($returnVal == -2){
            //$retError["Error"] = "Error"
            return -2;
        }
        $val["msg"] = "success";
        //return $val;
        print_r(json_encode($val));

    }
}elseif($url_request=="checkIfDrinkInMenu"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        //print_r($val);

        if (isset($val["code"])) {
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            $drinkID = $_POST["drinkID"];

            $drinkExists = $menuHandler->checkIfDrinkExistsInMenu($uID, $drinkID, $menuName);

            $retVal["drinkExists"] = $drinkExists;
            print_r(json_encode($retVal));

        }

    }
}elseif ($url_request=="getMenuByNameAndUserID"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        //print_r($val);

        if (isset($val["code"])) {
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            //$drinkID = $_POST["drinkID"];

            $drinkString = $menuHandler->getDrinksFromMenu($uID,$menuName);

            //$retVal["drinkExists"] = $drinkExists;
            $retVal["drinkIDString"] = $drinkString;
            print_r(json_encode($retVal));

        }

    }
}elseif ($url_request=="checkIfMenuExists"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        //print_r($val);

        if (isset($val["code"])) {
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            //$drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);

            //$retVal["drinkExists"] = $drinkExists;
            $retVal["menuExists"] = $menuExistBool;
            print_r(json_encode($retVal));

        }

    }
}elseif($url_request=="addUserMenu"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        //print_r($val);

        if (isset($val["code"])) {
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            //$drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);
            if ($menuExistBool){
                $retVal["code"] = "MENUEXISTS";
                print_r(json_encode($retVal));
                //return;
            }else{
                //ADD THE MENU

                $menuHandler->createEmptyMenu($uID,$menuName);
                $retVal["code"] = "success";
                print_r(json_encode($retVal));
            }

            //$retVal["drinkExists"] = $drinkExists;
            //$retVal["menuExists"] = $menuExistBool;
            //print_r(json_encode($retVal));

        }

    }

}elseif($url_request=="addDrinkToMenu"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        //print_r($val);

        if (isset($val["code"])) {
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            $drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);
            if ($menuExistBool){
                $menuHandler->updateMenu($uID,$menuName,$drinkID);
                $retVal["code"] = "success";
                print_r(json_encode($retVal));
                //return;
            }else{
                //MENU DOES NOT EXIST, DO NOT ALLOW AND ADDITION

                //$menuHandler->createEmptyMenu($uID,$menuName);
                $retVal["code"] = "error";
                print_r(json_encode($retVal));
            }

            //$retVal["drinkExists"] = $drinkExists;
            //$retVal["menuExists"] = $menuExistBool;
            //print_r(json_encode($retVal));

        }

    }

}elseif ($url_request=="deleteDrinkFromMenu"){

    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        //print_r($val);

        if (isset($val["code"])) {
            //means an error happened
            echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            $drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);
            if ($menuExistBool){
                $menuHandler->deleteDrinkIDFromMenu($uID,$menuName,$drinkID);
                $retVal["code"] = "success";
                print_r(json_encode($retVal));
                //return;
            }else{
                //MENU DOES NOT EXIST, DO NOT ALLOW AND ADDITION

                //$menuHandler->createEmptyMenu($uID,$menuName);
                $retVal["code"] = "error";
                print_r(json_encode($retVal));
            }

            //$retVal["drinkExists"] = $drinkExists;
            //$retVal["menuExists"] = $menuExistBool;
            //print_r(json_encode($retVal));

        }

    }

}elseif($url_request=="getMenuIngredients"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        if (isset($val["code"])) {
            echo "CODE SET";
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            //$drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);
            if ($menuExistBool){
                $drinkString = $menuHandler->getDrinksFromMenu($uID,$menuName);
                $drinkIdArray = explode("/",$drinkString);
                //echo "DRINKIDARRAY: ";
                //print_r($drinkIdArray);
                if (empty($drinkIdArray) || (count($drinkIdArray) ==1 && empty($drinkIdArray[0]) )){
                    $retVal["code"] = "empty";
                    print_r(json_encode($retVal));
                    exit();
                }
                $ingredIDs = $requestController->getIngredientIDsOfDrinkIDs($drinkIdArray);

                $ingredNames = array();
                /*
                foreach ($ingredIDs as $ingredID){
                    array_push($ingredNames,$requestController->getIngredNameByID($ingredID));
                }
                */
                $ingredData = array();
                foreach ($ingredIDs as $ingredID){
                    array_push($ingredData,$requestController->getIngredNameANDTypeByID($ingredID));
                }
                //$retVal["ingredNames"] = $ingredNames;
                //print_r(json_encode($retVal));
                //print_r(json_encode($ingredData));
                $ingredDataAssoc = convertDrinksToAssoc($ingredData);
                print_r(json_encode($ingredDataAssoc));

                //$menuHandler->deleteDrinkIDFromMenu($uID,$menuName,$drinkID);
                //$retVal["code"] = "success";
                //print_r(json_encode($retVal));

            }else{
                //MENU DOES NOT EXIST, DO NOT ALLOW AND ADDITION

                //$menuHandler->createEmptyMenu($uID,$menuName);
                $retVal["code"] = "error";
                print_r(json_encode($retVal));
            }

        }

    }

}elseif($url_request=="buildMenuPDF"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        if (isset($val["code"])) {
            echo "CODE SET";
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            //$drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);
            if ($menuExistBool){

                $menuName = $_POST["menuName"];
                $name = $pdfManager->makePDF("TEST",$menuName,$uID);
                $retVal["code"] = "success";
                $retVal["fname"] = $name;
                print_r(json_encode($retVal));


            }else{
                //MENU DOES NOT EXIST, DO NOT ALLOW AND ADDITION

                //$menuHandler->createEmptyMenu($uID,$menuName);
                $retVal["code"] = "error";
                print_r(json_encode($retVal));
            }

        }

    }



}elseif($url_request=="getMenu"){
    $menuName = $url_parts[2];

    $file_url = 'PDFs/'.$menuName.'.pdf';
    //header('Content-Type: application/octet-stream');
    //header("Content-Transfer-Encoding: Binary");
    //header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
    //readfile($file_url);

    if(file_exists($file_url)){
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);

        unlink($file_url);
    }

}elseif($url_request == "buildMenuIngredientsPDF"){
    $headers = getAuthorizationHeader();
    if(isset($headers)) {
        $token = getBearerToken();

        $val = (array)$authController->verify_jwt($token);
        if (isset($val["code"])) {
            echo "CODE SET";
        } else {
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];

            $menuName = $_POST["menuName"];
            //$drinkID = $_POST["drinkID"];

            $menuExistBool = $menuHandler->checkExistanceOfMenu($uID,$menuName);
            if ($menuExistBool){

                //$menuName = $_POST["menuName"];
                $ingredientString = $_POST["ingredString"];
                $garnishString = $_POST["garnishString"];

                $name = $pdfManager->makeIngredientsPDF("TEST",$menuName,$uID,$ingredientString,$garnishString);
                $retVal["code"] = "success";
                $retVal["fname"] = $name;
                print_r(json_encode($retVal));


            }else{
                //MENU DOES NOT EXIST, DO NOT ALLOW AND ADDITION

                //$menuHandler->createEmptyMenu($uID,$menuName);
                $retVal["code"] = "error";
                print_r(json_encode($retVal));
            }

        }

    }


}elseif($url_request=="getIngredPDF"){
    $filename = $url_parts[2];
    //print_r($url_parts);
    $file_url = 'PDFs/'.$filename.'.pdf';
    if(file_exists($file_url)){
      //  echo "EXISTS";
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);

        unlink($file_url);
    }else{
        echo"DNE";
    }




}else{
    print_r("HERE!!");
}

function convertDrinksToAssoc(array $drinks){
    $retVal = array();
    $idx = 0;
    foreach ($drinks as $searchRes){
        $dict_idx = "recipe_".$idx;
        $retVal[$dict_idx] = $searchRes;
        $idx ++;
    }
    return $retVal;

}

function preformAuthorizationCheck($authController){
    $headers = getAuthorizationHeader();
    if(isset($headers)){
        $token = getBearerToken();

        $val = (array) $authController->verify_jwt($token);
        //print_r($val);

        if(isset($val["code"])){
            //means an error happened
            //echo "CODE SET";
            //print_r((array)$authController->verify_jwt($token.getBearerToken()));
            return false;
        }else{
            //echo "NOT SET";//no error with the JWT
            //print_r($val);
            $uID = $val["id"];
            $valArray["uid"] = $uID;
            return $valArray;

        }

    }else{
        echo "HEADERS NOT SET";
        print_r($headers);
    }
}

function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}