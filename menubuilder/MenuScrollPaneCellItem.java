package com.menubuilder.menubuilder;

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.control.Button;
import javafx.scene.control.ButtonType;
import javafx.scene.control.Label;
import javafx.scene.control.ListCell;

import java.io.IOException;

public class MenuScrollPaneCellItem extends ListCell<MenuScrollPaneItemData> {

    @Override
    public void updateItem(MenuScrollPaneItemData cellData, boolean empty){
        super.updateItem(cellData,empty);

        if (cellData != null){
            //System.out.println("NOT NULL");
            setGraphic(cellData.getGridPane());
        }else{
            //System.out.println("CELLDATA NULL");
        }
    }
}
