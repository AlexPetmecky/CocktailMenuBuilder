package com.menubuilder.menubuilder;

import com.menubuilder.menubuilder.HttpHandler.RequestHandler;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.control.ListCell;
import javafx.scene.control.ListView;
import javafx.scene.control.TextField;
import javafx.scene.text.Text;
import javafx.stage.Stage;
import javafx.util.Callback;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Set;

public class ShowIngredientsPage {

    @FXML
    private Button homepagebtn;

    @FXML
    private TextField ingredientLinkText;


    @FXML
    private ListView ingredientListView;



    @FXML
    private ListView garnishListView;

    private Set<IngredientCell> ingredients = new HashSet<>();
    ObservableList<IngredientCell> ingredientObservableList = FXCollections.observableArrayList();
    private Set<IngredientCell> garnishes = new HashSet<>();
    ObservableList<IngredientCell> garnishObservableList = FXCollections.observableArrayList();

    private RequestHandler requestHandler = new RequestHandler();
    private Set<String> ingredientSet = new HashSet<>();
    private Set<String> garnishSet = new HashSet<>();

    @FXML
    public void initialize() throws JSONException {
        JSONObject menuIngredients = Configurations.getMenuIngredients();


        Iterator<String> keys = menuIngredients.keys();

        //separating garnishes from actual ingredients
        while(keys.hasNext()) {
            String key = keys.next();
            if (menuIngredients.get(key) instanceof JSONObject) {
                //the JSONObject is made of multiple jsonObjects, each subobject contains an ingredient
                //each ingredient has its type and name stored
                //System.out.println(menuIngredients.get(key).toString());
                String ingredType =((JSONObject) menuIngredients.get(key)).get("ingredType").toString();
                //preforming actual check
                if(ingredType.equalsIgnoreCase("garnish")){
                    String garnishName =((JSONObject) menuIngredients.get(key)).get("ingredName").toString();
                    IngredientCell garnishCell = new IngredientCell(garnishName);
                    garnishes.add(garnishCell);
                    garnishSet.add(garnishName);
                }else{
                    String ingredName =((JSONObject) menuIngredients.get(key)).get("ingredName").toString();
                    IngredientCell ingredCell = new IngredientCell(ingredName);
                    ingredients.add(ingredCell);
                    ingredientSet.add(ingredName);

                }
            }
        }


        ingredientObservableList.setAll(ingredients);
        garnishObservableList.setAll(garnishes);


        ingredientListView.setItems(ingredientObservableList);
        garnishListView.setItems(garnishObservableList);

        ingredientListView.setCellFactory(new Callback<ListView<IngredientCell>, ListCell<IngredientCell>>() {
            @Override
            public IngredientCellUpdater call(ListView listView) {
                return new IngredientCellUpdater();
            }
        });

        garnishListView.setCellFactory(new Callback<ListView<IngredientCell>, ListCell<IngredientCell>>() {
            @Override
            public IngredientCellUpdater call(ListView listView) {
                return new IngredientCellUpdater();
            }
        });











    }


    @FXML
    protected void makeIngredientPDF(ActionEvent event) throws JSONException, IOException, InterruptedException {
        String menuName = Configurations.getMenuName();

        String ingredientString = String.join(",", ingredientSet);
        String garnishString = String.join(",", garnishSet);

        JSONObject response = requestHandler.buildMenuIngredientsPDF(menuName,ingredientString,garnishString);

        if(response.has("fname")){
            String fileName =  response.getString("fname");
            ingredientLinkText.setText("Please go to: https://thebarpro.000webhostapp.com/getIngredPDF/"+fileName);

        } else{




        }


    }



    @FXML
    protected void homepagechange(ActionEvent event) throws IOException {
        System.out.println("BUTTON CLICKED");
        Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);

    }



}
