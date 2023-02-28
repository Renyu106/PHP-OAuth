<?php

namespace Oauth;

class Redirect
{

    public function redirect_uri(){
        $URL = "##CALLBACK_URL##"; // CALLBACK URL
        return urlencode($URL);
    }

    public function client_id($PLATFORM){
        if($PLATFORM == "discord"){
            return "##CLIENT_ID##";
        }else if($PLATFORM == "google"){
            return "##CLIENT_ID##";
        }else if($PLATFORM == "github"){
            return "##CLIENT_ID##";
        }else if($PLATFORM == "kakao"){
            return "##CLIENT_ID##";
        }else{
            return false;
        }
    }

    public function secret_key($PLATFORM){
        if($PLATFORM == "discord"){
            return "##SECRET_KEY##";
        }else if($PLATFORM == "google"){
            return "##SECRET_KEY##";
        }else if($PLATFORM == "github"){
            return "##SECRET_KEY##";
        }else if($PLATFORM == "kakao"){
            return "##SECRET_KEY##";
        }else{
            return false;
        }
    }

    public function discord(){
        $SCOPE = urlencode("email identify");
        return "https://discord.com/api/oauth2/authorize?client_id={$this->client_id('discord')}&redirect_uri={$this->redirect_uri()}?oauth=discord&response_type=code&scope={$SCOPE}";
    }
    public function google(){
        $SCOPE = urlencode("email profile openid");
        return "https://accounts.google.com/o/oauth2/v2/auth?client_id={$this->client_id('google')}&redirect_uri={$this->redirect_uri()}?oauth=google&response_type=code&scope={$SCOPE}&access_type=offline";
    }
    public function github(){
        $SCOPE = urlencode("user:email");
        return "https://github.com/login/oauth/authorize?client_id={$this->client_id('github')}&scope={$SCOPE}";
    }
    public function kakao(){
        return "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id={$this->client_id('kakao')}&redirect_uri={$this->redirect_uri()}?oauth=kakao";
    }

}

class Get_token{

    public function kakao($CODE = null){
        if(empty($CODE)) $STATUS = 0;
        $CLIENT_ID = (new Redirect)->client_id('kakao');
        $SECRET_KEY = (new Redirect)->secret_key('kakao');
        $type_name = "카카오";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://kauth.kakao.com/oauth/token?=authorization_code&client_id=$CLIENT_ID&code=$CODE&client_secret=$SECRET_KEY&grant_type=authorization_code");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        $error_description = $response['error_description'];
        $access_token = $response['access_token'];
        $bearer_token = "Bearer " . $access_token . "";

        if(empty($access_token)){
            $ARRAY = array(
                "satus" => "error",
                "type_name" => $type_name,
                "error" => $response['error'],
                "error_description" => $error_description,
                "platform" => "kakao"
            );
        }else{
            $ARRAY = array(
                "satus" => "success",
                "type_name" => $type_name,
                "access_token" => $access_token,
                "bearer_token" => $bearer_token,
                "platform" => "kakao"
            );
        }

        return $ARRAY;
    }

    public function google($CODE = null)
    {
        if (empty($CODE)) $STATUS = 0;
        $CLIENT_ID = (new Redirect)->client_id('google');
        $SECRET_KEY = (new Redirect)->secret_key('google');
        $REDIRECT_URL = (new Redirect)->redirect_uri();
        $type_name = "구글";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://accounts.google.com/o/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=' . $CLIENT_ID . '&client_secret=' . $SECRET_KEY . '&grant_type=authorization_code&code=' . $CODE . '&redirect_uri=' . $REDIRECT_URL . '?oauth=google',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result, true);
        $error_description = $response['error_description'];
        $access_token = $response['access_token'];
        $bearer_token = "Bearer " . $access_token;

        if(empty($access_token)){
            $ARRAY = array(
                "satus" => "error",
                "type_name" => $type_name,
                "error" => $response['error'],
                "error_description" => $error_description,
                "platform" => "google"
            );
        }else{
            $ARRAY = array(
                "satus" => "success",
                "type_name" => $type_name,
                "access_token" => $access_token,
                "bearer_token" => $bearer_token,
                "platform" => "google"
            );
        }

        return $ARRAY;
    }

    public function discord($CODE = null)
    {
        if (empty($CODE)) $STATUS = 0;
        $CLIENT_ID = (new Redirect)->client_id('discord');
        $SECRET_KEY = (new Redirect)->secret_key('discord');
        $REDIRECT_URL = (new Redirect)->redirect_uri();
        $type_name = "디스코드";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://discord.com/api/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=' . $CLIENT_ID . '&client_secret=' . $SECRET_KEY . '&grant_type=authorization_code&code=' . $CODE . '&redirect_uri=' . $REDIRECT_URL . '?oauth=discord',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $get_token_json = json_decode($response, true);
        $error_description = $get_token_json['error_description'];
        $access_token = $get_token_json['access_token'];
        $bearer_token = "Bearer " . $access_token;

        if(empty($access_token)){
            $ARRAY = array(
                "satus" => "error",
                "type_name" => $type_name,
                "error" => $get_token_json['error'],
                "error_description" => $error_description,
                "platform" => "discord"
            );
        }else{
            $ARRAY = array(
                "satus" => "success",
                "type_name" => $type_name,
                "access_token" => $access_token,
                "bearer_token" => $bearer_token,
                "platform" => "discord"
            );
        }

        return $ARRAY;
    }

    public function github($CODE = null){
        if (empty($CODE)) $STATUS = 0;
        $CLIENT_ID = (new Redirect)->client_id('github');
        $SECRET_KEY = (new Redirect)->secret_key('github');
        $type_name = "깃허브";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://github.com/login/oauth/access_token?client_id=$CLIENT_ID&client_secret=$SECRET_KEY&code=$CODE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $header = array(
            'Accept: application/json'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
        curl_close($ch);
        $get_token_json = json_decode($response, true);
        $error = $get_token_json['error'];
        $error_description = $get_token_json['error_description'];
        $access_token = $get_token_json['access_token'];
        $bearer_token = "Bearer " . $access_token;

        if(empty($access_token)){
            $ARRAY = array(
                "satus" => "error",
                "type_name" => $type_name,
                "error" => $error,
                "error_description" => $error_description,
                "platform" => "github"
            );
        }else{
            $ARRAY = array(
                "satus" => "success",
                "type_name" => $type_name,
                "access_token" => $access_token,
                "bearer_token" => $bearer_token,
                "platform" => "github"
            );
        }
        return $ARRAY;
    }

}

class Token_info{

    public function info($BEARER_TOKEN = null, $PLATFORM = null){
        if(empty($BEARER_TOKEN) || empty($PLATFORM)) $STATUS = 0;
        if ($PLATFORM == "kakao") {
            $URL = "https://kapi.kakao.com/v2/user/me"; // 카카오 프로필 URL
        }else if($PLATFORM == "github"){
            $URL = "https://api.github.com/user"; // 깃허브 프로필 URL
        }else if($PLATFORM == "discord"){
            $URL = "https://discord.com/api/users/@me"; // 디스코드 프로필 URL
        }else if($PLATFORM == "google"){
            $URL = "https://www.googleapis.com/oauth2/v1/userinfo"; // 구글 프로필 URL
        }else{
            $STATUS = 0;
        }
        $curl = curl_init(); // 프로필 데이터 요청
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $BEARER_TOKEN,
                'User-Agent: AI.RENYU.CAT (AI ILLUSTRATOR LOGIN)'
            ),
        ));
        $get_profile = curl_exec($curl);
        curl_close($curl);
        $get_profile_json = json_decode($get_profile, true);

        if ($PLATFORM == "kakao") {
            $sns_id = $get_profile_json['id'];
            $email_verified = $get_profile_json['kakao_account']['is_email_verified'];
            $sns_email = $get_profile_json['kakao_account']['email'];
            $profile_image = $get_profile_json['kakao_account']['profile']['profile_image_url'];
            $sns_username = $get_profile_json['kakao_account']['profile']['nickname'];
        }

        if ($PLATFORM == "google") {
            $sns_id = $get_profile_json['id'];
            $sns_email = $get_profile_json['email'];
            $email_verified = $get_profile_json['verified_email'];
            $profile_image = $get_profile_json['picture'];
            $sns_username = $get_profile_json['name'];
            $given_name = $get_profile_json['given_name'];
        }

        if ($PLATFORM == "naver") {
            $sns_id = $get_profile_json['response']['id'];
            $sns_email = $get_profile_json['response']['email'];
            $email_verified = true;
        }

        if ($PLATFORM == "discord") {
            $sns_id = $get_profile_json['id'];
            $sns_email = $get_profile_json['email'];
            $sns_username = $get_profile_json['username'];
            $sns_premium_type = $get_profile_json['premium_type'];
            $sns_discriminator = $get_profile_json['discriminator'];
            $email_verified = $get_profile_json['verified'];
            $profile_image = "https://cdn.discordapp.com/avatars/{$get_profile_json['id']}/{$get_profile_json['avatar']}.png?size=1024";
        }

        if ($PLATFORM == "github") {
            $sns_id = $get_profile_json['id'];
            $sns_email = $get_profile_json['email'];
            $sns_username = $get_profile_json['login'];
            $email_verified = true;
            $profile_image = $get_profile_json['avatar_url'];
        }

        if (empty($sns_id) || empty($sns_email)) {
            $ARRAY = array(
                "satus" => "error",
                "error_description" => "이메일 또는 고유번호를 못받아왔어요!!",
                "response" => $get_profile_json
            );
            return $ARRAY;
        }

        if ($email_verified !== true) {
            $ARRAY = array(
                "satus" => "error",
                "error_description" => "인증된 계정이 아니에요!!",
            );
            return $ARRAY;
        }

        $ARRAY = array(
            "satus" => "success",
            "sns" => $PLATFORM,
            "uid" => $sns_id,
            "email" => $sns_email,
            "email_verified" => $email_verified,
            "username" => $sns_username,
            "discriminator" => $sns_discriminator,
            "premium_type" => $sns_premium_type,
            "profile_image" => $profile_image
        );

        return $ARRAY;

    }

}
