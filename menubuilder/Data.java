package com.menubuilder.menubuilder;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.ColumnConstraints;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.HBox;
import javafx.stage.Stage;

import java.io.File;
import java.io.IOException;
public class Data
{
    @FXML
    private HBox hBox;

    @FXML
    private HBox picRegion;

    @FXML
    private GridPane gridpane;


    @FXML
    private Button res_btn;

    @FXML
    private  Label info_txt;

    @FXML
    private ImageView imageView;

    @FXML
    private Image image;

    public Data()
    {
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("listCellItem.fxml"));
        fxmlLoader.setController(this);
        try
        {
            fxmlLoader.load();
        }
        catch (IOException e)
        {
            throw new RuntimeException(e);
        }
    }

    public void setInfo(String drinkID,String string,String imgPath,String drinkInfo)
    {
        res_btn.setText(string);

        info_txt.setText(drinkInfo);
        info_txt.setWrapText(true);
        //setting the buttons on action
        res_btn.setOnAction(e->{

            try{
                Configurations.setChosenDrinkID(Integer.valueOf(drinkID));
                Parent newRoot = FXMLLoader.load(getClass().getResource("chosenDrink.fxml"));
                Stage stage = (Stage) res_btn.getScene().getWindow();
                stage.getScene().setRoot(newRoot);
            } catch (IOException ex) {
                throw new RuntimeException(ex);
            }
        });

        System.out.println(imgPath);
        imageView = new ImageView(new Image(getClass().getResourceAsStream(imgPath)));
        imageView.setPreserveRatio(true);
        imageView.setFitWidth(50);

        HBox picRegion = new HBox();
        picRegion.getChildren().add(imageView);

        gridpane.getColumnConstraints().add(new ColumnConstraints(120));//sets first col to 100
        gridpane.getColumnConstraints().add(new ColumnConstraints(110));//sets second col to 100
        this.gridpane.add(picRegion,3,0);//puts the image in the 3rd row, in the right corner



    }
    public void setImg(String imgPath){

    }

    public GridPane getBox()
    {
        return gridpane;
    }
}
