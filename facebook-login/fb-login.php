<!DOCTYPE html>
<html>
<head>
    <title></title>

<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>


<?php
 require_once "connect.php";
 
 if(isset($accessToken)){
    if(isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else{
        // Put short-lived access token in session
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        
          // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        
        // Set default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    
    // Redirect the user back to the same page if url has "code" parameter in query string
    if(isset($_GET['code'])){
        header('Location: ./');
    }
    
    // Getting user facebook profile info
    try {
        $profileRequest = $fb->get('/me?fields=id,name,first_name,last_name,email,link,gender,locale,picture');
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if ($conn->connect_error) {
            die("connection failed : ".$conn -> connect_error);
        }else{
            //variable FB Data
            $fb_id = $fbUserProfile["id"];
            $fb_fname = $fbUserProfile["first_name"];
            $fb_lname = $fbUserProfile["last_name"];
            $fb_email = $fbUserProfile["email"];
            


            $sql = "SELECT * FROM 13570112 WHERE id_user = ".$fb_id."";
            $result = $conn->query($sql);

            if ($result -> num_rows == 0){
                $sql_insert = "INSERT INTO 13570112(`id_user`, `f_name`, `l_name`, `email`) VALUES ('".$fb_id."', '".$fb_fname."', '".$fb_lname."', '".$fb_email."')";
                
                $conn->query($sql_insert);
                // echo $sql_insert;
                echo "INSERT COMPLETE";
                
            }else{
                echo "<h1>Login Success</h1>";

                echo "<p>with facebook api login</p>";
                // echo $sql;
                echo "<br>";
                    while ($row = $result -> fetch_assoc()) {
                        echo "<b>ID USER</b> = ".$row['id_user'];
                        echo "<br>";
                        echo "<b>First name</b> = ".$row['f_name'];
                        echo "<br>";
                        echo "<b>Last name</b> = ".$row['l_name'];
                        echo "<br>";
                        echo "<b>Email</b> = ".$row['email'];
                      
                        echo "<br>";
                        echo "<a href='logout.php' class='myButton1'>Logout</a>";
                    }
                $conn->close();
            }
        } 
}else{
    $fbloginUrl = $helper->getLoginUrl($fbRedirectURL, $fbPermissions);
    echo '<a href="'.$fbloginUrl.'" class="myButton">Login with Facebook</a>';
    
}


?>

</body>
</html>
