package com.menubuilder.menubuilder;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.GridPane;

import java.io.IOException;

public class MenuScrollPaneItemData {

    @FXML
    private GridPane cellGridPane;
    @FXML
    private Label menuNameLabel;

    @FXML
    private Button chooseID;

    private String drinkID;



    public MenuScrollPaneItemData(String drinkName){
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("MenuScrollPaneCellItem.fxml"));
        fxmlLoader.setController(this);
        try
        {
            fxmlLoader.load();
        }
        catch (IOException e)
        {
            throw new RuntimeException(e);
        }

        menuNameLabel.setText(drinkName);
    }



    public GridPane getGridPane(){
        return cellGridPane;
    }

    public void setDrinkID(String drinkID){
        this.drinkID = drinkID;
    }

    public String getDrinkID(){
        return this.drinkID;
    }

}
