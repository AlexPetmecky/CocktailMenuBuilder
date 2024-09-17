<?php
require "vendor/autoload.php";
require "DB_Conns/dbconn.php";


use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Auth{
    private $key = "EXAMPLEKEY";


    public function handle_signup(string $username,string $password){
        global $conn;
        if($conn->errno){
            exit();
        }

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows) {
            echo json_encode(["info" => "exists"]);
            exit();
        }else {

            $hashedPWD = password_hash($password,PASSWORD_DEFAULT);
            $sql="INSERT INTO users (username,password,accountType,refreshToken) VALUES(?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $accountType = "free";
            $refresh_tok = "unused";
            $stmt->bind_param("ssss",$username,$hashedPWD,$accountType,$refresh_tok);
            $stmt->execute();


            $insertedID = $stmt->insert_id;

            $payload = array(
                'iss'=> 'RecipeAPI',
                'aud' => 'RecipeFront',
                'id' => $insertedID
            );

            $jwt = $this->create_jwt($payload);
            //json_encode("access_token",$jwt);
            return json_encode(array(
                "access_token"=>$jwt

            ));
        }
    }

    public function handle_login(String $username, string $password){
        global $conn;
        if($conn->errno){
            exit();
        }

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt ->bind_param('s',$username);
        $stmt->execute();

        $result = $stmt->get_result();


        if($result->num_rows){
            #checks if user exists in db
            #echo "COMPLETE";
            $row=mysqli_fetch_assoc($result);
            if (password_verify($password, $row["password"])){
                #user exists and password verified
                $payload = array(
                    'iss'=> 'RecipeAPI',
                    'aud' => 'RecipeFront',
                    'id' => $row["user_id"]
                );

                $jwt = $this->create_jwt($payload);
                return json_encode(array(
                    "access_token"=>$jwt

                ));
            }else{
                echo json_encode(["info"=>"ERR"]);
                exit();
            }

        }else{
            echo json_encode(["info"=>"DNE"]);
            exit();
        }
    }

    private function create_jwt(array $payload){
        $iat = time();
        $exp = $iat + 60*60;
        array_push($payload,$iat,$exp);
        /*
        $payload = array(
            'iss'=> 'RecipeAPI',
            'aud' => 'RecipeFront',
            'iat' =>$iat,
            'exp'=>$exp
        );
        */
        $jwt = JWT::encode($payload,$this->key,'HS512');
        return $jwt;
    }

    public function verify_jwt($jwt){
        $decoded = JWT::decode($jwt,new Key($this->key, 'HS512'));
        return $decoded;

    }
}

