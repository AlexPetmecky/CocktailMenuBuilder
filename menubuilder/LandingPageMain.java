package com.menubuilder.menubuilder;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.stage.Stage;

import java.io.IOException;

public class LandingPageMain {

    @FXML
    private Button searchByName;

    @FXML
    private Button searchBySpecificIngredient;

    @FXML
    private Button menuOpener;



    @FXML
    protected void nameSearchPageChange(ActionEvent event) throws IOException {
        Parent newRoot = FXMLLoader.load(getClass().getResource("NameSearchPage.fxml"));
        Stage stage = (Stage) searchByName.getScene().getWindow();

        stage.getScene().setRoot(newRoot);
    }

    @FXML
    protected void searchByincludingIngredientPageChange(ActionEvent event) throws IOException {
       Parent newRoot = FXMLLoader.load(getClass().getResource("SearchByIncludes.fxml"));
       Stage stage = (Stage) searchBySpecificIngredient.getScene().getWindow();

       stage.getScene().setRoot(newRoot);
    }

    @FXML
    protected void searchByBar(ActionEvent event) throws IOException {
        Parent newRoot = FXMLLoader.load(getClass().getResource("SearchByBar.fxml"));
        Stage stage = (Stage) searchBySpecificIngredient.getScene().getWindow();

        stage.getScene().setRoot(newRoot);
    }

    @FXML
    protected void openMenusPageChange(ActionEvent event) throws IOException {
        Parent newRoot = FXMLLoader.load(getClass().getResource("ViewMenus.fxml"));
        Stage stage = (Stage) menuOpener.getScene().getWindow();

        stage.getScene().setRoot(newRoot);
    }


}
