<?php

$sql="select * from tb_wxapp_config";
$query=$dbmgr->query($sql);
$wechatConfig=$dbmgr->fetch_array($query);
function WechatOauth($oauthcode){
	Global $wechatConfig;

	$token=md5($oauthcode.$type.strtotime("now"));
	//构建access token url

	$appid = $wechatConfig['appid'];
	$appkey = $wechatConfig['secrect'];
	$access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?"
	      ."appid=".$appid
	      ."&secret=".$appkey
	      ."&code=".$oauthcode
	      ."&grant_type=authorization_code";

	$response=request_get($access_token_url);
	$data = json_decode($response,true);
	logger_mgr::logInfo("oathlogin url:".$access_token_url." result:".json_encode($data));

	$ret=array();

	$userinfo_url = "https://api.weixin.qq.com/sns/userinfo?"
      ."access_token=".$data["access_token"]
      ."&openid=".$data["openid"];

     $response=request_get($userinfo_url);
	$data = json_decode($response,true);
	logger_mgr::logInfo("usefinfo url:".$userinfo_url." result:".json_encode($data));


	return $data;

}

function WechatGenAppPaymentJSON($subject,$price,$orderno,$notify_url){
	Global $wechatConfig;

	$appid = $wechatConfig['appid'];
	$mchid = $wechatConfig['mechant_id'];
	$secret = $wechatConfig['mechant_secrect'];

	$params = array(
        'appid'            => $appid,
        'mch_id'           => $mchid,
        'nonce_str'        => generateNonce(),
        'body'             => $subject,
        'out_trade_no'     => $orderno,
        'total_fee'        => $price*100,
        'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
        'notify_url'       => $notify_url,
        'trade_type'       => 'APP',
    );

	$prepay_id = generatePrepayId($params,$secret);
	$response = array(
	    'appid'     => $appid,
	    'partnerid' => $mchid,
	    'prepayid'  => $prepay_id,
	    'package'   => 'Sign=WXPay',
	    'noncestr'  => generateNonce(),
	    'timestamp' => time(),
	);
	$response['sign'] = calculateSign($response, $secret);
	return $response;
}





function generatePrepayId($params,$appsecret)
{
    // add sign
    $params['sign'] = calculateSign($params, $appsecret);
	//print_r($params);
    // create xml
    $xml = getXMLFromArray($params);
	$result=postXmlCurl($xml, "https://api.mch.weixin.qq.com/pay/unifiedorder");
	
    // get the prepay id from response
    $xml = simplexml_load_string($result);
    return (string)$xml->prepay_id;
}

/**
 * Generate a nonce string
 *
 * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=4_3
 */
function generateNonce()
{
    return md5(uniqid('', true));
}
/**
 * Get a sign string from array using app key
 *
 * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=4_3
 */
function calculateSign($arr, $key)
{
    ksort($arr);
    $buff = "";
    foreach ($arr as $k => $v) {
        if ($k != "sign" && $k != "key" && $v != "" && !is_array($v)){
            $buff .= $k . "=" . $v . "&";
        }
    }
    $buff = trim($buff, "&");
	$string=$buff . "&key=" . $key;
    return strtoupper(md5($string));
}

/**
 * Get xml from array
 */
function getXMLFromArray($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        $xml .= sprintf("<%s>%s</%s>", $key, $val, $key);
    }
    $xml .= "</xml>";
    return $xml;
}

?>