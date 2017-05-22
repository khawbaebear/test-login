<?php
include_once 'src/google/Google_Client.php';
include_once 'src/google/contrib/Google_Oauth2Service.php';


$clientId = '35665660781-7991n5meh4nh3l1o03ne7c4bvnstrrq3.apps.googleusercontent.com'; //Google client ID
$clientSecret = '-MUwNti7AeY5ntMA0bcUP9cv'; //Google client secret
$gRedirectURL = 'http://www.mytesttodo.me/google-login.php'; //Callback URL

$gClient = new Google_Client();
$gClient->setApplicationName('xxxx');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($gRedirectURL);
$gClient->setAccessType('online');
$gClient->setApprovalPrompt('auto') ;
$google_oauthV2 = new Google_Oauth2Service($gClient);
$gloginUrl = $gClient->createAuthUrl();
?>