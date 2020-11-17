<?php

namespace Nurturelibs;

/**
 * Per errore indirizzi non verificati, vedere:
 * https://stackoverflow.com/questions/41724027/how-can-i-send-mail-without-verifying-the-recipients-in-amazon-ses
 * che riporta qui: http://docs.aws.amazon.com/ses/latest/DeveloperGuide/request-production-access.html
 */
class Mailer {

  protected $sesClient;
  protected $templatePath = __DIR__ . '/../mails';

  public function __construct($accesKey, $secretKey, $region = null, $scheme = null, $version = null, $templatePath = null) {
    if (!$region)
      $region = 'eu-west-1';
    
    if (!$scheme)
      $scheme = 'https';
    
    if (!$version)
      $version = 'latest';
    
    $this->sesClient = new Aws\Ses\SesClient([
      'version' => $version,
      'region' => $region,
//      'scheme' => $scheme,
      'credentials' => [
        'key' => $accesKey,
        'secret' => $secretKey,
      ]
    ]);
    
    if ($templatePath)
      $this->templatePath = $templatePath;
  }

  public function sendEmail(string $sender, string $recipient, string $subject, string $htmlBody, string $textBody, $replyTo = null, $bccRecipients = []) {
    if (!$replyTo)
      $replyTo = $sender;

    $data = [
      'Destination' => [
        'ToAddresses' => [$recipient],
        'BccAddresses' => $bccRecipients
      ],
      'Message' => [
        'Body' => [
          'Text' => ['Data' => $textBody],
          'Html' => ['Data' => $htmlBody]
        ],
        'Subject' => ['Data' => $subject],
      ],
      'ReplyToAddresses' => [$replyTo],
      'Source' => $sender,
    ];

    try {
      return $this->sesClient->sendEmail($data);
    } catch (Exception $e) {
      return false;
    }
  }

  public function compile(string $template, array $params, $html = true) {
    $ext = $html ? 'html' : 'txt';
    
    $content = file_get_contents("$this->templatePath/$template.$ext");

    foreach ($params as $k => $v)
      $content = str_replace("[%$k%]", $v, $content);

    return $content;
  }

}
