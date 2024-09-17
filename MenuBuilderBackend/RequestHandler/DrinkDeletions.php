<?php
require "DB_Conns/dbconn.php";
class DrinkDeletions{
    public function deleteHeadersWaiting(int $recipeID){
        global $conn;
        $sql = "DELETE FROM drink_headers_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$recipeID);
        $stmt->execute();

    }

    public function deleteHeaders(int $recipeID){
        global $conn;
        $sql = "DELETE FROM drink_headers WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$recipeID);
        $stmt->execute();
    }

    public function deleteHistoryWaiting(int $recipeID){
        global $conn;
        $sql = "DELETE FROM history_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$recipeID);
        $stmt->execute();
    }
    public function deleteInstructionsWaiting(int $recipeID){
        global $conn;
        $sql = "DELETE FROM instructions_waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$recipeID);
        $stmt->execute();
    }

    public function deleteIngredientMapperWaiting(int $recipeID){
        global $conn;
        $sql = "DELETE FROM ingredient_Mapper_Waiting WHERE recipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$recipeID);
        $stmt->execute();
    }

}