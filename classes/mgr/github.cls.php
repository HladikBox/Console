<?php
class GithubMgr {
  private $client_id;
  private $client_secret;
  private $access_token;

  public function __construct($client_id, $client_secret) {
    $this->client_id = $client_id;
    $this->client_secret = $client_secret;
    $this->access_token=$_SESSION[SessionName]["access_token"];
    $this->code=$_SESSION[SessionName]["code"];


  }


  public function setAccessToken($code){
    if($this->code==$code){
      return;
    }

    $url="https://github.com/login/oauth/access_token";
    //$data="client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$code;
    $data["client_id"]=$this->client_id;
    $data["client_secret"]=$this->client_secret;
    $data["code"]=$code;
	  $ret= request_post($url,$data);
    $ret=explode("&",$ret);
    $ret=explode("=",$ret[0]);
    $access_token=$ret[1];
    if($access_token==""){
        return;
    }
    $_SESSION[SessionName]["code"]=$code;
    $_SESSION[SessionName]["access_token"]=$access_token;
    $this->access_token=$access_token;
    $this->code=$code;
  }

  public function getUser(){
    $url="https://api.github.com/user?access_token=".$this->access_token;
    $res= request_get($url);
    $res=json_decode($res);
    //print_r($res);
    return $res;
  }

}
$githubMgr=new GithubMgr($CONFIG['github']["client_id"], $CONFIG['github']["client_secret"]);
?>