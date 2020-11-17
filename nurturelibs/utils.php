<?php

namespace Nurturelibs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Flash {

  public static function get($name, $default = null) {
    $sName = "_FLASH_$name";

    $message = array_key_exists($sName, $_SESSION) ? $_SESSION[$sName] : $default;

    unset($_SESSION[$sName]);

    return $message;
  }

  public static function set($name, $message) {
    $sName = "_FLASH_$name";

    $_SESSION[$sName] = $message;
  }

}

class Utils {

  public static $loggers = [];

  public static function addLogger($name, $level, $path) {
    Utils::$loggers[$name] = new Logger($name);
    Utils::$loggers[$name]->pushHandler(new StreamHandler($path, $level));
  }

  public static function log($msg, $params = [], $level = Logger::INFO) {
    foreach (Utils::$loggers as $logger)
      $logger->log($level, $msg, $params);
  }

  public static function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
      return $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    else
      return $_SERVER['REMOTE_ADDR'];
  }

}