package com.menubuilder.menubuilder;

import javafx.scene.SubScene;
import javafx.scene.control.ListCell;
import javafx.scene.image.ImageView;

public class SearchTermCell extends ListCell<SearchTermCellData> {
    private ImageView del_Image;
    @Override
    public void updateItem(SearchTermCellData cellData, boolean empty)
    {
        //super.updateItem(inputData,empty);
        //del_Image = cellData.getDelImage();
        super.updateItem(cellData,empty);
        if (cellData != null){
            //SearchTermCellData cellData = new SearchTermCellData();
            //System.out.println("HERE!1".concat(cellData.getText()));


            //System.out.println(del_Image.toString());
            del_Image = cellData.getDelImage();

            setGraphic(cellData.getGridPane());
        }else{
            //System.out.println("INPUT DATA NULL");
            //System.out.println("SEARCHTERMCELL.JAVA line 11");

        }



    }



}
