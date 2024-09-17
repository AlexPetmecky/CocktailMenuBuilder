package com.menubuilder.menubuilder;

import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.TextFieldListCell;
import javafx.scene.image.ImageView;
import javafx.scene.layout.GridPane;

import java.io.IOException;

public class SearchTermCellData {


    @FXML
    private GridPane gridPaneInner;

    @FXML
    private TextField inputIngredient;

    @FXML
    private ImageView delImage;






    public SearchTermCellData()
    {

        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("SearchTermCell.fxml"));
        fxmlLoader.setController(this);
        try
        {
            fxmlLoader.load();
        }
        catch (IOException e)
        {
            throw new RuntimeException(e);
        }

        //delImage.setOnMouseClicked(e->{
        //    String item = delImage.getItem
        //});


    }

    public void addData(String id){
        gridPaneInner.setId(id);
    }
    public ImageView getDelImage(){
        return delImage;
    }


    public GridPane getGridPane(){
        return gridPaneInner;
    }

    public String getText(){
        return inputIngredient.getText();
    }
}
