package com.menubuilder.menubuilder.HttpHandler;

import java.io.IOException;
import java.net.URI;
import java.net.URLEncoder;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.stream.Collectors;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.menubuilder.menubuilder.Configurations;
import com.menubuilder.menubuilder.HttpHandler.responses.LoginResponse;
import org.json.JSONException;
import org.json.JSONObject;

public class RequestHandler {
    public final HttpClient client = HttpClient.newHttpClient();

    //public final String baseURL = "http://localhost:8888/MenuBuilder/";
    public final String baseURL = "https://thebarpro.000webhostapp.com/";



    public boolean login(String username,String password) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<String,String>();
        params.put("username",username);
        params.put("password",password);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "login";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                //.header("Content-Type", "application/json")
                .header("Access-Control-Allow-Origin","*")
                .header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.POST(HttpRequest.BodyPublishers.of)
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        System.out.println(response.statusCode());
        System.out.println(response.body());

        ObjectMapper mapper = new ObjectMapper();

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
            Configurations.setAccessToken((String) retVal.get("access_token"));
            System.out.println(retVal.get("access_token"));
            return true;
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");
            return false;

        }

    }

    public boolean signup(String username,String password) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<String,String>();
        params.put("username",username);
        params.put("password",password);

        String requestBody = this.buildPostBodyFromString(params);
        String URL = baseURL + "signup";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        System.out.println(response.statusCode());
        System.out.println(response.body());
        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
            Configurations.setAccessToken((String) retVal.get("access_token"));
            System.out.println(retVal.get("access_token"));
            return true;
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");
            return false;

        }

    }


    public JSONObject make_drink_insertion(ArrayList<String> headers, ArrayList<String> ingreds,ArrayList<String> ingredTypes,ArrayList<String>measures,ArrayList<String> amounts,ArrayList<String>comments,String instructions,String history) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("headers",String.join(",",headers));
        params.put("ingreds",String.join(",",ingreds));
        params.put("ingredTypes",String.join(",",ingredTypes));
        params.put("measures",String.join(",",measures));
        params.put("amounts",String.join(",",amounts));
        params.put("comments",String.join(",",comments));
        params.put("instructions",String.join(",",instructions));
        params.put("history",String.join(",",history));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "insertDrink";


        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                //.header("Content-Type", "application/json")
                .header("Access-Control-Allow-Origin","*")
                .header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.POST(HttpRequest.BodyPublishers.of)
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }
        return retVal;






    }

    public JSONObject make_drink_insertion_waiting(ArrayList<String> headers, ArrayList<String> ingreds,ArrayList<String> ingredTypes,ArrayList<String>measures,ArrayList<String> amounts,ArrayList<String>comments,String instructions,String history) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("headers",String.join(",",headers));
        params.put("ingreds",String.join(",",ingreds));
        params.put("ingredTypes",String.join(",",ingredTypes));
        params.put("measures",String.join(",",measures));
        params.put("amounts",String.join(",",amounts));
        params.put("comments",String.join(",",comments));
        params.put("instructions",String.join(",",instructions));
        params.put("history",String.join(",",history));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "insertDrinkWaiting";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }
        return retVal;

    }

    public JSONObject moveOutOfWaiting(String waitingDrinkID) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("waitingDrinkID",waitingDrinkID);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "acceptWaitingDrink";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }
        return retVal;

    }

    public JSONObject searchByName(String name) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("drinkName",name);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "nameSearch";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;

        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;

    }

    public JSONObject getGlassNameByID(String id) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("glassTypeID",id);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "getGlassType";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;

        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject searchByIncludes(String ingreds) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("ingredients",ingreds);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "searchByIncludes";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject getDrinkByID(int drinkID) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("drinkID",String.valueOf(drinkID));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "getDrinkByID";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }


    public JSONObject getMenusByUserID() throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("drinkID",String.valueOf(drinkID));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "getUserMenus";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("Res Body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;

    }

    public JSONObject checkIfDrinkExistsInMenu(String menuName,String drinkID) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("menuName",String.valueOf(menuName));
        params.put("drinkID",String.valueOf(drinkID));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "checkIfDrinkInMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject checkIfDrinkExistsInMainMenu(String menuName,String drinkID) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        params.put("menuName",String.valueOf(menuName));
        params.put("drinkID",String.valueOf(drinkID));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "checkDrinkInMainMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;

    }

    public JSONObject addDrinkToMainMenu(String drinkID) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("menuName",String.valueOf(menuName));
        params.put("drinkID",String.valueOf(drinkID));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "addDrinkToMainMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject deleteDrinkFromMainMenu(String drinkID) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("menuName",String.valueOf(menuName));
        params.put("drinkID",String.valueOf(drinkID));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "deleteDrinkFromMainMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;

    }

    public JSONObject getMenuByNameAndUserID(String menuName) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("menuName",String.valueOf(menuName));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "getMenuByNameAndUserID";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject checkIfMenuExists(String menuName) throws JSONException, IOException, InterruptedException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("menuName",String.valueOf(menuName));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "checkIfMenuExists";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject addMenu(String menuName) throws JSONException, IOException, InterruptedException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("menuName",String.valueOf(menuName));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "addUserMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;

    }

    public JSONObject addDrinkIDToMenu(String drinkID,String menuName) throws JSONException, IOException, InterruptedException {
        HashMap<String,String> params = new HashMap<>();
        params.put("drinkID",String.valueOf(drinkID));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "addDrinkToMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject deleteDrinkIDFromMenu(String drinkID,String menuName) throws JSONException, IOException, InterruptedException {
        HashMap<String,String> params = new HashMap<>();
        params.put("drinkID",String.valueOf(drinkID));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "deleteDrinkFromMenu";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        //System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject getMenuIngredients(String menuName) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("drinkID",String.valueOf(drinkID));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "getMenuIngredients";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        //System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject searchByBar(String ingreds,String useDefaultList) throws JSONException, IOException, InterruptedException {
        HashMap<String,String> params = new HashMap<>();
        params.put("ingredients",ingreds);
        params.put("useDefaultList",useDefaultList);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "searchByListStrict";

        HttpResponse<String> response = this.makeRequest(URL,requestBody);

        JSONObject retVal;
        //System.out.println(response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject runInserter() throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("drinkID",String.valueOf(drinkID));
        //params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "runInserter";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                //.header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject buildMenuPDF(String menuName) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("drinkID",String.valueOf(drinkID));
        params.put("menuName",String.valueOf(menuName));

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "buildMenuPDF";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }

    public JSONObject buildMenuIngredientsPDF(String menuName,String ingredString,String garnishString) throws IOException, InterruptedException, JSONException {
        HashMap<String,String> params = new HashMap<>();
        //params.put("drinkID",String.valueOf(drinkID));
        params.put("menuName",String.valueOf(menuName));
        params.put("ingredString",ingredString);
        params.put("garnishString",garnishString);

        String requestBody = this.buildPostBodyFromString(params);

        String URL = baseURL + "buildMenuIngredientsPDF";

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                .header("Authorization","Bearer "+Configurations.getAccessToken())
                //.header("Content-type", "application/json")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.header("content-type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();


        System.out.println(request.toString());
        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        JSONObject retVal;
        System.out.println("res_body: "+response.body());
        try {
            retVal = new JSONObject(response.body());
        }catch (JSONException err){
            System.out.println(err);
            retVal = new JSONObject("{\"ERR\":\"unknown\"}");

        }

        return retVal;
    }


    private String buildPostBodyFromString(HashMap<String,String> params){
        String requestBody = params.entrySet()
                .stream()
                .map(e -> e.getKey() + "=" + URLEncoder.encode((String) e.getValue(), StandardCharsets.UTF_8))
                .collect(Collectors.joining("&"));

        return requestBody;
    }

    private HttpResponse<String> makeRequest(String URL, String requestBody) throws IOException, InterruptedException {
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(URL))
                .version(HttpClient.Version.HTTP_1_1)
                //.header("Content-Type", "application/json")
                .header("Access-Control-Allow-Origin","*")
                .header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT")
                .header("Content-Type","application/x-www-form-urlencoded")
                .header("Accept", "application/json")
                //.POST(HttpRequest.BodyPublishers.of)
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        return response;
    }





}
