package com.menubuilder.menubuilder;

import com.menubuilder.menubuilder.HttpHandler.RequestHandler;

import javafx.collections.FXCollections;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.control.ChoiceBox;
import javafx.scene.control.Label;
import javafx.scene.control.ScrollPane;
import javafx.scene.text.Text;
import javafx.stage.Popup;
import javafx.stage.Stage;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.Iterator;

public class ChosenDrink {

    @FXML
    private Button homepagebtn;

    @FXML
    private Label drinkName;

    @FXML
    private Button addToMenuBTN;

    @FXML
    private ChoiceBox menusChoiceBox;


    @FXML
    private ScrollPane ingredients;
    @FXML
    private ScrollPane factSheetPane;
    @FXML
    private ScrollPane instructionsPane;
    @FXML
    private ScrollPane historyPane;

    ArrayList<String> menuNamesList = new ArrayList<>();




    RequestHandler requestHandler = new RequestHandler();

    @FXML
    protected void homepagechange(ActionEvent event) throws IOException {
        //System.out.println("BUTTON CLICKED");
        Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);

    }

    @FXML
    protected void pageBack(ActionEvent event) throws IOException {
        Parent newRoot = FXMLLoader.load(getClass().getResource(Configurations.getPrevPageFXML()));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);
    }

    @FXML
    protected void initialize() throws JSONException, IOException, InterruptedException, URISyntaxException {
        int drinkID = Configurations.getChosenDrinkID();
        JSONObject drink = requestHandler.getDrinkByID(drinkID);
        System.out.println(drink.toString());

        String name = (String) drink.get("name");
        JSONObject ingreds = (JSONObject) drink.get("ingredients");
        //System.out.println(ingredsMain.toString());
        JSONArray ingredsMain = ingreds.getJSONArray("ingredsMain");
        JSONArray garnishes = ingreds.getJSONArray("garnishes");

        //for (int i =0;i< ingredsMain.length();i++) {
        //    System.out.println(ingredsMain.get(i));
        //}
        String instructions = (String) drink.get("instructions");
        String history = (String) drink.get("history");


        drinkName.setText(name);
        String ingredsText = "";

        //getting all ingredients/garnsishes to a string
        for (int i=0;i<ingredsMain.length();i++) {
            ingredsText =ingredsText.concat("\n"+ingredsMain.get(i));
        }
        ingredsText.concat("\n");
        for (int i=0;i<garnishes.length();i++) {
            ingredsText =ingredsText.concat("\n"+garnishes.get(i));
        }
        //setting text properties
        Text ingredientText = new Text(ingredsText);
        ingredientText.wrappingWidthProperty().bind(ingredients.widthProperty());
        //putting ingredients into scrollview
        ingredients.setContent(ingredientText);


        //setting the instructions
        Text instructionText = new Text(instructions);
        instructionText.wrappingWidthProperty().bind(instructionsPane.widthProperty());
        instructionsPane.setContent(instructionText);

        //setting the factsheet
        String factSheetString = "";
        factSheetString= factSheetString.concat("Drink Type: "+ (String)drink.get("drinkType")+"\n");

        factSheetString=factSheetString.concat("Glass Type: "+(String) drink.get("glassType")+"\n");

        if (!((String)drink.get("notes")).equalsIgnoreCase("no note")){
            factSheetString = factSheetString.concat((String)drink.get("notes")+"\n");
        }

        Text factSheetText = new Text(factSheetString);
        factSheetText.wrappingWidthProperty().bind(factSheetPane.widthProperty());
        factSheetPane.setContent(factSheetText);

        Text historyText = new Text(history);
        historyText.wrappingWidthProperty().bind(historyPane.widthProperty());
        historyPane.setContent(historyText);


        //setting up the options for the user menus
        JSONObject userMenus = requestHandler.getMenusByUserID();
        Iterator<String> keys = userMenus.keys();
        while (keys.hasNext()){
            String key = keys.next();
            if(userMenus.get(key) instanceof JSONObject){
                String menuName = (String) ((JSONObject) userMenus.get(key)).get("menuName");
                menuNamesList.add(menuName);

            }
        }

        menusChoiceBox.setItems(FXCollections.observableArrayList(menuNamesList));

        /*
        JSONObject response=requestHandler.checkIfDrinkExistsInMainMenu("main", String.valueOf(drinkID));
        if ((boolean)response.get("drinkExists")){
            addToMenuBTN.setText("Remove from List");

        }else {
            addToMenuBTN.setText("Add to List");
        }
        */



    }

    @FXML
    protected void addToMenu(ActionEvent event) throws JSONException, IOException, InterruptedException {

        int drinkID = Configurations.getChosenDrinkID();
        //JSONObject response=requestHandler.checkIfDrinkExistsInMainMenu("main", String.valueOf(drinkID));
        String chosenMenu = (String) menusChoiceBox.getSelectionModel().getSelectedItem();
        if (chosenMenu != null && !chosenMenu.isEmpty()){
            JSONObject response = requestHandler.checkIfDrinkExistsInMenu(chosenMenu,String.valueOf( Configurations.getChosenDrinkID()));
            if ((boolean)response.get("drinkExists")){
                try {
                    //JSONObject retVal=  requestHandler.deleteDrinkFromMainMenu(String.valueOf(drinkID));
                    JSONObject retVal = requestHandler.deleteDrinkIDFromMenu(String.valueOf(drinkID),chosenMenu);
                    System.out.println(retVal.toString());
                }catch (Exception exception){
                    System.out.println("EXCEPTION CALLED");
                    System.out.println(exception);
                }

                addToMenuBTN.setText("Add to List");
            }else {
                try {
                    //JSONObject retVal=  requestHandler.addDrinkToMainMenu(String.valueOf(drinkID));
                    JSONObject retVal = requestHandler.addDrinkIDToMenu(String.valueOf(drinkID),chosenMenu);
                    System.out.println(retVal.toString());
                }catch (Exception exception){
                    System.out.println("EXCEPTION CALLED");
                    System.out.println(exception);
                }
                addToMenuBTN.setText("Remove from List");

            }


        }else {
            System.out.println("No Menu Chosen");
            addToMenuBTN.setText("No Menu Chosen");
            //System.out.println("No Menu Chosen");
            //set value somewhere
            return;
        }



    }


    @FXML
    protected void checkIfDrinkInMenu(ActionEvent event) throws JSONException, IOException, InterruptedException {
        String chosenMenu = (String) menusChoiceBox.getSelectionModel().getSelectedItem();
        System.out.println(chosenMenu);

        JSONObject response = requestHandler.checkIfDrinkExistsInMenu(chosenMenu,String.valueOf( Configurations.getChosenDrinkID()));
        if((Boolean) response.get("drinkExists")){
            addToMenuBTN.setText("Remove from List");
        }else{
            addToMenuBTN.setText("Add to List");
        }



    }

}
