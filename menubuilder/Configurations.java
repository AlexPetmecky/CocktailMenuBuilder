package com.menubuilder.menubuilder;

import org.json.JSONObject;

public class Configurations {
    private static String ACCESS_TOKEN;
    private static JSONObject SAVED_OBJECT;

    private static int chosenDrinkID;

    private static String prevPageFXML;

    private static String menuName;
    private static JSONObject menuIngredients;

    public static JSONObject getSavedObject() {
        return SAVED_OBJECT;
    }

    public static void setSavedObject(JSONObject savedObject) {
        SAVED_OBJECT = savedObject;
    }



    public static int getChosenDrinkID() {
        return chosenDrinkID;
    }

    public static void setChosenDrinkID(int chosenDrinkID) {
        Configurations.chosenDrinkID = chosenDrinkID;
    }

    public static String getPrevPageFXML() {
        return prevPageFXML;
    }

    public static void setPrevPageFXML(String prevPageFXML) {
        Configurations.prevPageFXML = prevPageFXML;
    }


    public static String getAccessToken() {
        return ACCESS_TOKEN;
    }

    public static void setAccessToken(String accessToken) {
        ACCESS_TOKEN = accessToken;
    }

    public static JSONObject getMenuIngredients() {
        return menuIngredients;
    }

    public static void setMenuIngredients(JSONObject menuIngredients) {
        Configurations.menuIngredients = menuIngredients;
    }

    public static String getMenuName() {
        return menuName;
    }

    public static void setMenuName(String menuName) {
        Configurations.menuName = menuName;
    }
}
