package com.menubuilder.menubuilder;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.stage.Stage;
import org.json.JSONObject;

import java.io.IOException;

public class ListRecipesPage {
    @FXML
    private Button homepagebtn;



    @FXML
    protected void homepagechange(ActionEvent event) throws IOException {
        Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);

    }

    @FXML
    protected void initialize(){
        JSONObject drinkOptions = Configurations.getSavedObject();



    }




}
