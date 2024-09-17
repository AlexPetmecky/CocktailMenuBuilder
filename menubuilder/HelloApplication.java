package com.menubuilder.menubuilder;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.image.Image;
import javafx.stage.Stage;

import java.io.IOException;

public class HelloApplication extends Application {

    private Parent root;


    @Override
    public void start(Stage stage) throws IOException {
        //FXMLLoader fxmlLoader = new FXMLLoader(HelloApplication.class.getResource("hello-view.fxml"));
        //Scene scene = new Scene(fxmlLoader.load(), 320, 240);
        //stage.setTitle("Hello!");
        //stage.setScene(scene);
        //stage.show();
        stage.getIcons().add(new Image(HelloApplication.class.getResourceAsStream("assets/icon.png")));
        //stage.getIcons().remove(0);
        root = FXMLLoader.load(getClass().getResource("hello-view.fxml"));
        stage.setTitle("Bar Pro");


        //Scene scene = new Scene(root.load(), 320, 240);
        stage.setScene(new Scene(root));
        stage.setHeight(700);
        stage.setWidth(700);
        stage.show();
    }
    public Parent getRoot(){
        return root;
    }

    public static void main(String[] args) {
        launch();
    }
}