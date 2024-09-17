package com.menubuilder.menubuilder;

import javafx.scene.control.ListCell;

public class IngredientCellUpdater extends ListCell<IngredientCell> {
    @Override
    public void updateItem(IngredientCell cellData, boolean empty){
        super.updateItem(cellData,empty);
        if(cellData != null){
            setGraphic(cellData.getGridpane());
        }else{
            //System.out.println("CELL DATA NULL");
        }
    }

}
