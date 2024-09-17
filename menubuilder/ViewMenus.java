package com.menubuilder.menubuilder;

import com.menubuilder.menubuilder.HttpHandler.RequestHandler;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.*;
import javafx.stage.Stage;
import javafx.util.Callback;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Set;

public class ViewMenus {
    @FXML
    private Button homepagebtn;

    @FXML
    private Button clearMenuBtn;

    @FXML
    private ChoiceBox menuChoiceBox;

    @FXML
    private Button buildMenuBtn;

    @FXML
    private Button findMoreDrinksBtn;

    @FXML
    private Button generateIngredientsBtn;

    @FXML
    private ListView<MenuScrollPaneItemData> menuListView;


    //vars for adding new menus
    @FXML
    private TextField newMenuNameTXTFLD;
    @FXML
    private Button addMenuBtn;


    //vars for
    @FXML
    private TextField menuLinkText;

    ArrayList<String> menuNamesList = new ArrayList<>();





    private Set<MenuScrollPaneItemData> menuSet= new HashSet<>();
    ObservableList<MenuScrollPaneItemData> observableList = FXCollections.observableArrayList();


    private RequestHandler requestHandler = new RequestHandler();

    @FXML
    protected void homepagechange(ActionEvent event) throws IOException {
        System.out.println("BUTTON CLICKED");
        Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);

    }

    @FXML
    protected void clearMenu(ActionEvent event){
        //send request to clear menu, maybe reset page?
    }

    @FXML
    protected void buildMenu(ActionEvent event) throws JSONException, IOException, InterruptedException {
        String menuName = (String) menuChoiceBox.getSelectionModel().getSelectedItem();
        if (menuName != null && !menuName.trim().isEmpty()){
            JSONObject response = requestHandler.buildMenuPDF(menuName);
            //System.out.println(response.toString());
            String menuLinkName = response.getString("fname");


            menuLinkText.setText("Please go to: https://thebarpro.000webhostapp.com/getMenu/"+menuLinkName);
        }

    }

    @FXML
    protected void findMoreDrinks(ActionEvent event){

    }

    @FXML
    protected void generateIngredients(ActionEvent event) throws JSONException, IOException, InterruptedException {
        String menuName = (String) menuChoiceBox.getSelectionModel().getSelectedItem();
        if (menuName != null && !menuName.trim().isEmpty()){
            JSONObject menuIngredsObject = requestHandler.getMenuIngredients(menuName);
            //System.out.println(menuIngredsObject.toString());



            if(menuIngredsObject.has("code")){


            } else{
                Configurations.setMenuIngredients(menuIngredsObject);
                Configurations.setMenuName(menuName);

                Parent newRoot = FXMLLoader.load(getClass().getResource("ShowIngredientsPage.fxml"));
                Stage stage = (Stage) homepagebtn.getScene().getWindow();

                stage.getScene().setRoot(newRoot);



                /*
                Iterator<String> keys = menuIngredsObject.keys();

                while(keys.hasNext()) {
                    String key = keys.next();
                    if (menuIngredsObject.get(key) instanceof JSONObject) {
                        // do something with jsonObject here
                        System.out.println(menuIngredsObject.get(key).toString());
                    }
                }

                 */



            }





            //if()


        }
    }

    @FXML
    protected void initialize() throws JSONException, IOException, InterruptedException {
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

        menuChoiceBox.setItems(FXCollections.observableArrayList(menuNamesList));


/*

        JSONObject menus = requestHandler.getMenusByUserID();
        //System.out.println(menus.toString());
        Iterator<String> keys = menus.keys();

        while(keys.hasNext()) {
            String key = keys.next();
            if (menus.get(key) instanceof JSONObject) {
                // do something with jsonObject here
                String drinks = (String) ((JSONObject) menus.get(key)).get("drinkIDs");
                System.out.println(drinks);

                String[] drinkArray = drinks.split("/");
                for (int i = 0; i < drinkArray.length; i++) {
                    if (!drinkArray[i].equalsIgnoreCase("")){

                        //MSPCD.setDrinkID(drinkArray[i]);
                        JSONObject drinkObject= requestHandler.getDrinkByID(Integer.parseInt(drinkArray[i]));
                        System.out.println(drinkObject);

                        MenuScrollPaneItemData MSPCD = new MenuScrollPaneItemData((String) drinkObject.get("name"));

                        menuSet.add(MSPCD);
                    }


                }

            }

            observableList.setAll(menuSet);

            menuListView.setItems(observableList);

            menuListView.setCellFactory(new Callback<ListView<MenuScrollPaneItemData>, ListCell<MenuScrollPaneItemData>>(){
                @Override
                public MenuScrollPaneCellItem call(ListView listView){
                    return new MenuScrollPaneCellItem();
                }
            });


        }

        */
    }

    @FXML
    protected void loadMenu(ActionEvent event) throws JSONException, IOException, InterruptedException {
        menuSet.clear();
        String menuName = (String) menuChoiceBox.getSelectionModel().getSelectedItem();
        JSONObject response = requestHandler.getMenuByNameAndUserID(menuName);

        String drinkIDString = (String) response.get("drinkIDString");
        String[] drinkIDArray = drinkIDString.split("/");

        for (int i = 0; i < drinkIDArray.length; i++) {
            if (!drinkIDArray[i].equalsIgnoreCase("")){

                //MSPCD.setDrinkID(drinkArray[i]);
                JSONObject drinkObject= requestHandler.getDrinkByID(Integer.parseInt(drinkIDArray[i]));
                System.out.println(drinkObject);

                MenuScrollPaneItemData MSPCD = new MenuScrollPaneItemData((String) drinkObject.get("name"));

                menuSet.add(MSPCD);
            }


        }



        observableList.setAll(menuSet);

        menuListView.setItems(observableList);

        menuListView.setCellFactory(new Callback<ListView<MenuScrollPaneItemData>, ListCell<MenuScrollPaneItemData>>(){
            @Override
            public MenuScrollPaneCellItem call(ListView listView){
                return new MenuScrollPaneCellItem();
            }
        });


    }


    @FXML
    protected void addNewMenu(ActionEvent event) throws JSONException, IOException, InterruptedException {
        System.out.println(newMenuNameTXTFLD.getText());
        String newMenuName = newMenuNameTXTFLD.getText();

        JSONObject response =  requestHandler.checkIfMenuExists(newMenuName);

        if((boolean)response.get("menuExists")){
            //do not let user create the menu with that name
        }else{

            JSONObject addMenuRes = requestHandler.addMenu(newMenuName);
            String code = (String) addMenuRes.get("code");
            if(code.equalsIgnoreCase("MENUEXISTS")){
                System.out.println("ERROR");
            } else if (code.equalsIgnoreCase("success")) {

                System.out.println("Menu Added");
                Parent newRoot = FXMLLoader.load(getClass().getResource("ViewMenus.fxml"));
                Stage stage = (Stage) homepagebtn.getScene().getWindow();

                stage.getScene().setRoot(newRoot);

            }else{
                System.out.println("Unknown Error");
            }
        }


    }



}
