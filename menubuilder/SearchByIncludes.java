package com.menubuilder.menubuilder;

import com.menubuilder.menubuilder.HttpHandler.RequestHandler;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.control.*;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.GridPane;
import javafx.stage.Stage;
import javafx.util.Callback;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Set;

public class SearchByIncludes {
    @FXML
    private Button homepagebtn;

    @FXML
    private Label msg;

    @FXML
    private Button clearBtn;

    @FXML
    private ListView<SearchTermCellData> search_terms;

    @FXML
    private Button addIngredientBtn;

    @FXML
    private Button searchButton;

    @FXML
    private Button searchByBarBTN;

    @FXML
    private ListView results;



    //variables for searching
    ObservableList<SearchTermCellData> observableList = FXCollections.observableArrayList();
    private Set<SearchTermCellData> inputObjectSet = new HashSet<>();

    private Set<String> currIngredInputSet = new HashSet<>();

    //variables for results
    private Set<String> resultSet = new HashSet<>();
    ObservableList observableListResults = FXCollections.observableArrayList();

    private RequestHandler requestHandler = new RequestHandler();



    @FXML
    protected void homepagechange(ActionEvent event) throws IOException {
        System.out.println("BUTTON CLICKED");
        Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
        Stage stage = (Stage) homepagebtn.getScene().getWindow();

        stage.getScene().setRoot(newRoot);

    }

    @FXML
    protected void searchDrinks() throws JSONException, IOException, InterruptedException {

        String searchString = "";
        for (SearchTermCellData cell:inputObjectSet) {
            System.out.println(cell.getText());
            String currText= cell.getText();
            if (currText.strip().equals("")){

            }else{
                searchString = searchString.concat(cell.getText().strip()).concat(",");
            }

        }
        if (searchString.equals("")){
            //do nothing, string is empty
            msg.setText("No Ingredients Given");
        }else{
            JSONObject obj = requestHandler.searchByIncludes(searchString);
            System.out.println(obj.toString());
            if (obj.has("ERR") || obj.has("error")){
                msg.setText("Error!");
            }else if (obj.has("code")) {
                if ( (obj.get("code").toString()).equalsIgnoreCase("DNE")){
                    msg.setText("Ingredient Not In Any Drinks");
                }

            }else{
                msg.setText("");
                for (Iterator key = obj.keys(); key.hasNext();){
                    JSONObject drink = (JSONObject) obj.get((String) key.next());
                    JSONObject glassType = requestHandler.getGlassNameByID(String.valueOf(drink.get("glassTypeID")));
                    System.out.println(glassType.toString());

                    String drinkDataString = String.valueOf(drink.get("recipeID"))+ "@"+(String) drink.get("name")+"@"+(String) glassType.get("glassType") +"@"+"Drink Style: "+(String) drink.get(("drinkType"));
                    resultSet.add(drinkDataString);
                }

                observableListResults.setAll(resultSet);
                results.setItems(observableListResults);
                results.setCellFactory(new Callback<ListView<String>, javafx.scene.control.ListCell<String>>() {
                    @Override
                    public ListCell call(ListView listView) {
                        return new ListViewCell();
                    }
                });
            }
        }




    }

    @FXML
    protected void searchByBar(ActionEvent event) throws JSONException, IOException, InterruptedException {
        String searchString = "";
        for (SearchTermCellData cell:inputObjectSet) {
            System.out.println(cell.getText());
            String currText= cell.getText();
            if (currText.strip().equals("")){

            }else{
                searchString = searchString.concat(cell.getText().strip()).concat(",");
            }

        }
        if (searchString.equals("")){
            //do nothing, string is empty
            msg.setText("No Ingredients Given");
        }else{
            //JSONObject obj = requestHandler.searchByIncludes(searchString);
            String useDefaultList = "true";
            JSONObject obj = requestHandler.searchByBar(searchString,useDefaultList);
            //System.out.println(obj.toString());
            if (obj.has("ERR") || obj.has("error")){
                msg.setText("Error!");
            } else if (obj.has("code")) {
                if ( (obj.get("code").toString()).equalsIgnoreCase("DNE")){
                    msg.setText("Ingredient Not In Any Drinks");

                    resultSet.clear();
                    observableList.clear();

                }

            } else{
                msg.setText("");
                for (Iterator key = obj.keys(); key.hasNext();){
                    JSONObject drink = (JSONObject) obj.get((String) key.next());
                    JSONObject glassType = requestHandler.getGlassNameByID(String.valueOf(drink.get("glassTypeID")));
                    System.out.println(glassType.toString());

                    String drinkDataString = String.valueOf(drink.get("recipeID"))+ "@"+(String) drink.get("name")+"@"+(String) glassType.get("glassType") +"@"+"Drink Style: "+(String) drink.get(("drinkType"));
                    resultSet.add(drinkDataString);
                }

                observableListResults.setAll(resultSet);
                results.setItems(observableListResults);
                results.setCellFactory(new Callback<ListView<String>, javafx.scene.control.ListCell<String>>() {
                    @Override
                    public ListCell call(ListView listView) {
                        return new ListViewCell();
                    }
                });
            }
        }
    }

    @FXML
    protected void addInputSlot(ActionEvent event){

        //String currIdx =  String.valueOf(observableList.size());
        String currIdx =  String.valueOf(inputObjectSet.size());
        //System.out.println(currIdx);
        inputObjectSet.add(new SearchTermCellData());
        //search_terms.getSelectionModel().selectAll();
        int idx = 0;
        if(Integer.parseInt(currIdx) != 0){

        }
        //currIngredInputSet.add(currIdx);
        //ArrayList<String> myList= new ArrayList<>();
        //myList.add("0");
        observableList.setAll(inputObjectSet);


        search_terms.setItems(observableList);
        //search_terms.setEditable(true);
        search_terms.setCellFactory(new Callback<ListView<SearchTermCellData>, javafx.scene.control.ListCell<SearchTermCellData>>() {
            @Override
            public SearchTermCell call(ListView listView) {
                //SearchTermCell cell = new SearchTermCell();
                //cell.getDel_Image().setOnMouseClicked((MouseEvent e)->{
                //    SearchTermCellData cellData=  cell.getItem();
                //    observableList.remove(cellData);
                //});
                /*
                try {
                    //cell.setDel(cell.getItem());
                    cell.getDel_Image().setOnMouseClicked((MouseEvent e)->{
                        SearchTermCellData cellData=  cell.getItem();
                        if (cellData != null){
                            observableList.remove(cellData);
                            search_terms.getSelectionModel().clearSelection();
                        }

                    });
                }catch (Exception e){
                    System.out.println("ERROR");
                }
                */

                return new SearchTermCell();
                //return cell;
            }

        });


    }


    @FXML
    protected void clearIngredients(ActionEvent event){
        //observableList.em
        inputObjectSet.clear();
        observableList.setAll(inputObjectSet);
    }







}
