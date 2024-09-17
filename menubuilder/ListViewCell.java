package com.menubuilder.menubuilder;

import javafx.scene.control.ListCell;
import com.menubuilder.menubuilder.RecipeHeaderDisplay;

import java.util.ArrayList;
import java.util.List;

public class ListViewCell extends ListCell<String>
{
    @Override
    public void updateItem(String recipe, boolean empty)
    {
        super.updateItem(recipe,empty);
        if(recipe != null)
        {
            Data data = new Data();
            //System.out.println(recipe);
            String[] splitData = recipe.split("@");

            //data.setInfo(splitData[0],);

            String imgPath = "";
            if(splitData[2].equalsIgnoreCase("Copper Mug")){
                imgPath = "assets/DrinkImgs/CopperMug.png";
            }else if (splitData[2].equalsIgnoreCase("Absinthe")){
                imgPath = "assets/DrinkImgs/Absinthe.png";
            }else if (splitData[2].equalsIgnoreCase("Beer Mug")){
                imgPath = "assets/DrinkImgs/Beer.png";
            }else if (splitData[2].equalsIgnoreCase("Champagne Flute")){
                imgPath = "assets/DrinkImgs/Champagne.png";
            }else if (splitData[2].equalsIgnoreCase("Collins")){
                imgPath = "assets/DrinkImgs/Collins.png";
            }else if (splitData[2].equalsIgnoreCase("Cosmopolitan")){
                imgPath = "assets/DrinkImgs/Cosmopolitan.png";
            }else if (splitData[2].equalsIgnoreCase("Coupe")){
                imgPath = "assets/DrinkImgs/Coupe.png";
            }else if (splitData[2].equalsIgnoreCase("Goblet")){
                imgPath = "assets/DrinkImgs/Goblet.png";
            }else if (splitData[2].equalsIgnoreCase("Highball")){
                imgPath = "assets/DrinkImgs/Highball.png";
            }else if (splitData[2].equalsIgnoreCase("Hurricane")){
                imgPath = "assets/DrinkImgs/Hurricane.png";
            }else if (splitData[2].equalsIgnoreCase("Irish Coffee")){
                imgPath = "assets/DrinkImgs/IrishCoffee.png";
            }else if (splitData[2].equalsIgnoreCase("Margarita")){
                imgPath = "assets/DrinkImgs/Margarita.png";
            }else if (splitData[2].equalsIgnoreCase("Martini")){
                imgPath = "assets/DrinkImgs/Martini.png";
            }else if (splitData[2].equalsIgnoreCase("Milkshake")){
                imgPath = "assets/DrinkImgs/Milkshake.png";
            }else if (splitData[2].equalsIgnoreCase("Old Fashion")){
                imgPath = "assets/DrinkImgs/OldFashion.png";
            }else if (splitData[2].equalsIgnoreCase("Pilsner")){
                imgPath = "assets/DrinkImgs/Pilsner.png";
            }else if (splitData[2].equalsIgnoreCase("Pint")){
                imgPath = "assets/DrinkImgs/Pint.png";
            }else if (splitData[2].equalsIgnoreCase("Red Wine")){
                imgPath = "assets/DrinkImgs/RedWine.png";
            }else if (splitData[2].equalsIgnoreCase("Shot")){
                imgPath = "assets/DrinkImgs/Shot.png";
            }else if (splitData[2].equalsIgnoreCase("Sling")){
                imgPath = "assets/DrinkImgs/Sling.png";
            }else if (splitData[2].equalsIgnoreCase("Snifter")){
                imgPath = "assets/DrinkImgs/Snifter.png";
            }else if (splitData[2].equalsIgnoreCase("Weizen")){
                imgPath = "assets/DrinkImgs/Weizen.png";
            }else if (splitData[2].equalsIgnoreCase("White Wine")){
                imgPath = "assets/DrinkImgs/WhiteWine.png";
            }else if (splitData[2].equalsIgnoreCase("Zombie")){
                imgPath = "assets/DrinkImgs/Zombie.png";
            }else {
                imgPath = "assets/error.png";
            }
            //imgPath = "assets/Unknown.png";
            //imgPath = "@../assets/Unknown.png";
            data.setInfo(splitData[0],splitData[1],imgPath,splitData[3]);
            //data.setImg(imgPath);
            setGraphic(data.getBox());
        }
    }
}