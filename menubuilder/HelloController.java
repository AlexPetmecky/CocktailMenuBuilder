package com.menubuilder.menubuilder;

import com.menubuilder.menubuilder.HttpHandler.RequestHandler;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;
import javafx.stage.Stage;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;


public class HelloController {

    @FXML
    private TextField username;
    @FXML
    private PasswordField passwordField;

    @FXML
    private Button signupbtn;

    @FXML
    private Button loginbtn;

    @FXML Label msg;
    private RequestHandler requestHandler = new RequestHandler();

    @FXML
    protected void loginSubmit(ActionEvent event) throws IOException, InterruptedException, JSONException {
        //System.out.println("USERNAME"+username.getText());

        boolean loginSuccess = requestHandler.login(username.getText(),passwordField.getText());

        //requestHandler.getMenuByUserID();
        if(loginSuccess){
            //JSONObject obj = requestHandler.runInserter();
            //System.out.println(obj.toString());

            Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
            Stage stage = (Stage) loginbtn.getScene().getWindow();

            stage.getScene().setRoot(newRoot);
            return;
        }else{
            msg.setText("Username or Password Incorrect");
            return;
        }




    }

    @FXML
    protected void signupSubmit(ActionEvent event) throws IOException, InterruptedException, JSONException {
        boolean signupSuccess = requestHandler.signup(username.getText(),passwordField.getText());

        if(signupSuccess){
            Parent newRoot = FXMLLoader.load(getClass().getResource("landingpagemain.fxml"));
            Stage stage = (Stage) signupbtn.getScene().getWindow();

            stage.getScene().setRoot(newRoot);
            return;
        }else{
            msg.setText("Unable to signup. Please try again");
            return;
        }

    }


}

