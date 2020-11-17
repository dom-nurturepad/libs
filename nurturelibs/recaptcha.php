<?php

namespace Nurturelibs;

class Recaptcha {

  protected $apiKey;
  protected $secretKey;
  protected $lastSuccess = '';
  protected $lastAction = null;
  protected $lastScore = -1;

  public function __construct($apiKey, $secretKey) {
    $this->apiKey = $apiKey;
    $this->secretKey = $secretKey;
  }

  public function check($token, $action, $scoreThreshold = 0.5) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['secret' => $this->secretKey, 'response' => $token]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $res = json_decode($response, true);

    $this->lastSuccess = $res['success'];
    $this->lastAction = $res['action'];
    $this->lastScore = $res['score'];

    return $this->lastSuccess == '1' && $this->lastAction == $action && $this->lastScore >= $scoreThreshold;
  }
  
  public function getLastCheck() {
    return [$this->lastAction, $this->lastSuccess, $this->lastScore];
  }

}
