<?php
namespace OMCore;
// --------- CUSTOM Line  ---------- //

// LINE PUKAPAY
// define("LINE_channelAccessToken", 'CK0Q5QSO7DNnpzD3+HtrsjnE/TbkhZm+Fj/hb0fhyHhnGO6N/40AQ/7qEScbBxzck2EgObwoRkvi0UGmKdrENsYRpFmgwJx7ujfZsYjTtO8I8+bu2W0za5jvMeLugXZEtNLJ4JGEj3ZgC3I3k2J/cAdB04t89/1O/w1cDnyilFU=');
// define("LINK_getApiPush", "https://api.line.me/v2/bot/message/push");
// define("LINK_getApiReply", "https://api.line.me/v2/bot/message/reply");
// define("LINE_channelSecret", "4ac4e90784a885dbc41245647c6edc1c");

// LINE CCVISTION
define("LINE_channelAccessToken", 'JeD+hswQIwrOCj8mNNU3XzhygDYqIGZmqrwX27/HhZknbr/iV953LdtGlGAe+wLwtQGPHAdkcGg+VVsXBI/Cs/NqJwGOroyJbF02pW8PeAVWQRPw/Fo86iBjry36adVwyANmW35XlJUs4amqUZG7FwdB04t89/1O/w1cDnyilFU=');
define("LINK_getApiPush", "https://api.line.me/v2/bot/message/push");
define("LINK_getApiReply", "https://api.line.me/v2/bot/message/reply");
define("LINE_channelSecret", "7b410dcf4b017bca73b3f089b55631a3");

class Setting_Line {
	public static function getChannelAccessToken(){
		$channelAccessToken = LINE_channelAccessToken;
		return $channelAccessToken;
	}
	public static function getChannelSecret(){
		$channelSecret = LINE_channelSecret;
		return $channelSecret;
	}
	public static function getApiReply(){
		$api = LINK_getApiReply;
		return $api;
	}
	public static function getApiPush(){
		$api = LINK_getApiPush;
		return $api;
	}
}


class OMLinebot {
	private $channelAccessToken;
	private $channelSecret;
	private $webhookResponse;
	private $webhookEventObject;
	private $apiReply;
	private $apiPush;
	
	public function __construct(){
		$this->channelAccessToken = Setting_Line::getChannelAccessToken();
		$this->channelSecret = Setting_Line::getChannelSecret();
		$this->apiReply = Setting_Line::getApiReply();
		$this->apiPush = Setting_Line::getApiPush();
		$this->webhookResponse = file_get_contents('php://input');
		$this->webhookEventObject = json_decode($this->webhookResponse);
	}
	
	private function httpPost($api,$body){
		$ch = curl_init($api); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
		'Content-Type: application/json; charser=UTF-8', 
		'Authorization: Bearer '.$this->channelAccessToken)); 
		$result = curl_exec($ch); 
		curl_close($ch); 
		return $result;
	}
	
	public function reply($text){
		$api = $this->apiReply;
		$webhook = $this->webhookEventObject;
		$replyToken = $webhook->{"events"}[0]->{"replyToken"}; 
		$body["replyToken"] = $replyToken;
		$body["messages"][0] = array(
			"type" => "text",
			"text"=>$text
		);
		
		$result = $this->httpPost($api,$body);
		return $result;
	}
	
	public function push($body){
		$api = $this->apiPush;
		$result = $this->httpPost($api, $body);
		return $result;
    	}

    	public function pushText($to, $text){
		$body = array(
		    'to' => $to,
		    'messages' => [
			array(
			    'type' => 'text',
			    'text' => $text
			)
		    ]
		);
		$this->push($body);
	 }

   	 public function pushImage($to, $imageUrl, $previewImageUrl = false){
        	$body = array(
		    'to' => $to,
		    'messages' => [
			array(
			    'type' => 'image',
			    'originalContentUrl' => $imageUrl,
			    'previewImageUrl' => $previewImageUrl ? $previewImageUrl : $imageUrl
			)
		    ]
		);
		$this->push($body);
    	}

    	public function pushVideo($to, $videoUrl, $previewImageUrl){
        	$body = array(
          	  'to' => $to,
          	  'messages' => [
          	      array(
			    'type' => 'video',
			    'originalContentUrl' => $videoUrl,
			    'previewImageUrl' => $previewImageUrl
			)
		    ]
		);
        	$this->push($body);
    	}

    	public function pushAudio($to, $audioUrl, $duration){
		$body = array(
		    'to' => $to,
		    'messages' => [
			array(
			    'type' => 'audio',
			    'originalContentUrl' => $audioUrl,
			    'duration' => $duration
			)
		    ]
		);
		$this->push($body);
	}

    	public function pushLocation($to, $title, $address, $latitude, $longitude){
		$body = array(
		    'to' => $to,
		    'messages' => [
			array(
			    'type' => 'location',
			    'title' => $title,
			    'address' => $address,
			    'latitude' => $latitude,
			    'longitude' => $longitude
			)
		    ]
		);
		$this->push($body);
	}
	
	public function getMessageText(){
		$webhook = $this->webhookEventObject;
		$messageText = $webhook->{"events"}[0]->{"message"}->{"text"}; 
		return $messageText;
	}
	
	public function postbackEvent(){
		$webhook = $this->webhookEventObject;
		$postback = $webhook->{"events"}[0]->{"postback"}->{"data"}; 
		return $postback;
	}
	
	public function getUserId(){
		$webhook = $this->webhookEventObject;
		$userId = $webhook->{"events"}[0]->{"source"}->{"userId"}; 
		return $userId;
	}
	public function getGroupId(){
		$webhook = $this->webhookEventObject;
		$groupId = $webhook->{"events"}[0]->{"source"}->{"groupId"}; 
		return $groupId;
	}
	
}
