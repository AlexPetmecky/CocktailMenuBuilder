package com.menubuilder.menubuilder;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.control.Label;
import javafx.scene.layout.GridPane;

import java.io.IOException;

public class IngredientCell {

    @FXML
    private GridPane gridpane;
    @FXML
    private Label ingredientLabel;


    public IngredientCell(String ingredientName){
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("IngredientCell.fxml"));
        fxmlLoader.setController(this);

        try
        {
            fxmlLoader.load();
        }
        catch (IOException e)
        {
            throw new RuntimeException(e);
        }
        ingredientLabel.setText(ingredientName);

    }

    public GridPane getGridpane(){
        return this.gridpane;
    }


}
