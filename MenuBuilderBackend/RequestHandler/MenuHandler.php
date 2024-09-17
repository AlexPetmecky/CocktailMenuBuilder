<?php
require "DB_Conns/dbconn.php";
class MenuHandler{

    public function checkIfDrinkExistsInMainMenu($uID,$drinkID){
        $drinkString = $this->getDrinksInMainMenu($uID);
        $drinkList = explode("/",$drinkString);

        if (in_array((string)$drinkID,$drinkList)){
            return true;
        }else{
            return false;
        }

    }
    public function getUserMenus($uID){
        global $conn;

        $sql = "SELECT * FROM menus WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$uID);
        $stmt->execute();
        $result = $stmt->get_result();

        $menus = array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($menus,$row);
        }

        return $menus;
    }

    public function checkDrinkInAllUserMenus($uID,$drinkID){
        $menus = $this->getUserMenus($uID);

        foreach($menus as $menu){

        }
    }

    public function createEmptyMenu($uID,$menuName){
        global $conn;

        $emptyMenu="";

        $sql = "INSERT INTO menus (menuName,userID,drinkIDs) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$menuName,$uID,$emptyMenu);
        $stmt->execute();
        //$result = $stmt->get_result();


    }


    /**
     * @param $uID
     * @return int|void -2 if menu already exists
     */
    public function CreateMainMenu($uID){
        global $conn;
        if ($this->checkExistanceOfMenu($uID,"main")){
            return -2;
            //menu already exists
        }

        $emptyMenu="";
        $menuName="main";
        $sql = "INSERT INTO menus (menuName,userID,drinkIDs) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$menuName,$uID,$emptyMenu);
        $stmt->execute();
    }


    /**
     * @param $uID,$menuName,$drinkID
     * @return int|void -2 if menu does not exist
     */
    public function updateMenu($uID,$menuName,$drinkID){
        global $conn;

        if(!$this->checkExistanceOfMenu($uID,$menuName)){
            //menu does not exist
            return -2;
        }

        $sql = "SELECT * FROM menus WHERE menuName=? AND userID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$menuName,$uID);
        $stmt->execute();

        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);

        $currMenu = explode("/",$row["drinkIDs"]);

        array_push($currMenu,$drinkID);

        $updatedDrinkIDList = implode("/",$currMenu);

        $this->updateDrinkIDCol($uID,$menuName,$updatedDrinkIDList);






    }

    public function deleteDrinkIDFromMenu($uID,$menuName,$drinkID){
        global $conn;

        if(!$this->checkExistanceOfMenu($uID,$menuName)){
            //menu does not exist
            return -2;
        }

        $sql = "SELECT * FROM menus WHERE menuName=? AND userID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$menuName,$uID);
        $stmt->execute();

        $result = $stmt->get_result();
        $row=mysqli_fetch_assoc($result);

        $currMenu = explode("/",$row["drinkIDs"]);

        if (($key = array_search($drinkID, $currMenu)) !== false) {
            unset($currMenu[$key]);
            $currMenu = array_values($currMenu);

            $menuString = implode("/",$currMenu);

            $this->updateDrinkIDCol($uID,$menuName,$menuString);

        }else{
            //key does not exist
        }


    }

    public function checkExistanceOfMenu($uID,$menuName){
        global $conn;
        $sql = "SELECT * FROM menus WHERE menuName=? AND userID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$menuName,$uID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows){
            return true;//menu exists
        }

        return false;
    }

    public function getDrinksInMainMenu($uId){
        global $conn;
        if($this->checkExistanceOfMenu($uId,"main")){
            //main menu exists
            $drinkIDsString = $this->getDrinksFromMenu($uId,"main");
            //$drinkList = explode("/",$drinkIDsString);
            return $drinkIDsString;

        }else{
            //main menu does not exist
            $creationVal = $this->CreateMainMenu($uId);
            return "";
        }

    }


    /**
     * @param $uID
     * @param $menuName
     * @return string concatinated drink string delim= /
     */
    public function getDrinksFromMenu($uID, $menuName){
        global $conn;
        $sql = "SELECT drinkIDs FROM menus WHERE menuName=? AND userID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$menuName,$uID);
        $stmt->execute();

        $result = $stmt->get_result();

        $row=mysqli_fetch_assoc($result);

        return $row["drinkIDs"];

    }


    /**
     * @param $uID string
     * @param $drinkID string
     * @param $menuName string
     * @return bool
     */
    public function checkIfDrinkExistsInMenu($uID, $drinkID, $menuName){
        //global $conn;
        $drinkString = $this->getDrinksFromMenu($uID,$menuName);
        $drinkList = explode("/",$drinkString);

        if (in_array($drinkID,$drinkList)){
            return true;
        }else{
            return false;
        }

    }


    /**
     * @param int $uID
     * @param string $menuName
     * @param string $updatedDrinkList
     * @return void
     */
    private function updateDrinkIDCol(int $uID, string $menuName, string $updatedDrinkListAsString){
        global $conn;
        $sql = "UPDATE menus SET drinkIDs=? WHERE menuName = ? AND userID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$updatedDrinkListAsString,$menuName,$uID);
        $stmt->execute();
    }







}