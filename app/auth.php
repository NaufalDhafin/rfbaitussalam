<?php  
    include "conf.php";
    include "google/auth.php";
    if(isset($_GET['google'])){
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["google"]);
        if(!isset($token['error'])){
            session_start();
            $google_client->setAccessToken($token['access_token']);
            $google_service = new Google_Service_Oauth2($google_client);
            $data = $google_service->userinfo->get();
            $cemail = $data['email'];
            $cuser = $conf->query("SELECT * FROM users WHERE email = '$cemail'");
            if($cuser->num_rows > 0){
                $ruser = $cuser->fetch_array();
                $_SESSION['userid'] = $ruser['userid'];
                echo "<meta http-equiv='refresh' content='0;../dashboard'>";
            }
            else{
                $fullname = $data['given_name']." ".$data['family_name'];
                $email    = $data['email'];
                $password = $data['password'];
                $conf->query("INSERT INTO users SET userid = '$create_userid', fullname = '$fullname', ktp = '', whatsapp = '', email = '$email', coin = '0', password = '', otp = ''");
                echo "<meta http-equiv='refresh' content='0;../login.php?act=continue&userid=$create_userid'>";
            }
        }
    }
?>