package com.menubuilder.menubuilder;

import com.menubuilder.menubuilder.HttpHandler.RequestHandler;
import javafx.beans.Observable;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.*;
import javafx.stage.Stage;
import javafx.util.Callback;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.net.http.HttpRequest;
import java.util.*;

public class NameSearchPage {



    @FXML
    private TextField drinkName;

    @FXML
    private Label msg;

    @FXML
    private Button homepagebtn;

    @FXML
    private ListView results;

    private Set<String> resultSet = new HashSet<>();
    ObservableList observableList = FXCollections.observableArrayList();

    private RequestHandler requestHandler = new RequestHandler();


    @FXML
    protected void homepagechange(ActionEvent event) throws IOException {
        Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);

    }


    @FXML
    protected void searchRecipes(ActionEvent event) throws JSONException, IOException, InterruptedException {
        results.getItems().clear();
        String drinkNameText = drinkName.getText();
        if (drinkNameText.strip().equalsIgnoreCase("")){
            System.out.println("RETURNING");
            return;
        }

        JSONObject obj = requestHandler.searchByName(drinkNameText);
        System.out.println("HERE");

        if (obj.has("ERR") || obj.has("error")) {
            msg.setText("Error!");
        } else {
            System.out.println(obj.toString());
            for (Iterator key = obj.keys(); key.hasNext();){


                JSONObject drink = (JSONObject) obj.get((String) key.next());

                JSONObject glassType = requestHandler.getGlassNameByID(String.valueOf(drink.get("glassTypeID")));
                System.out.println(glassType.toString());

                String drinkDataString =String.valueOf(drink.get("recipeID"))+ "@"+(String) drink.get("name")+"@"+(String) glassType.get("glassType") +"@"+"Drink Style: "+(String) drink.get(("drinkType"));


                resultSet.add(drinkDataString);
            }

            //System.out.println(resultSet.toString());
            observableList.setAll(resultSet);
            results.setItems(observableList);
            //System.out.println(results.getItems().toString());
            results.setCellFactory(new Callback<ListView<String>, javafx.scene.control.ListCell<String>>() {
                @Override
                public ListCell call(ListView listView) {
                    return new ListViewCell();
                }
            });



            //Configurations.setSavedObject(obj);
            //System.out.println(obj.toString());
            //System.out.println("SAVED OBJECT: ");
            //System.out.println(Configurations.getSavedObject().toString());
            //Parent newRoot = FXMLLoader.load(getClass().getResource("listrecipespage.fxml"));
            //Stage stage = (Stage) homepagebtn.getScene().getWindow();

            //stage.getScene().setRoot(newRoot);
        }

    }


}
