<?php

namespace Nurturelibs;

class Sendy {

  protected $sendyBaseUrl;
  protected $accessKey;

  public function __construct($sendyBaseUrl, $accessKey) {
    $this->sendyBaseUrl = $sendyBaseUrl;
    $this->accessKey = $accessKey;
  }

  /**
   * Subscribe an user in a sendy list
   * check https://sendy.co/api for more
   */
  public function subscribe($list, $email, $params = []) {
    $url = $this->sendyBaseUrl . '/subscribe';
    $data = [
      'api_key' => $this->accessKey,
      'email' => $email,
      'list' => $list
    ];
    
    foreach ($params as $k => $v)
      $data[$k] = $v;

    $options = [
      'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
      ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return $result;
  }

}
