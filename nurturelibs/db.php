<?php

namespace Nurturelibs;

class Db {

  public $driver;

  public function __construct($host, $user, $pass, $dbName, $port = 3306) {
    $this->driver = new mysqli($host, $user, $pass, $dbName, $port);
    $this->driver->set_charset('utf8');
  }

  public function store($table, $fields, $values) {
    $strFields = join(array_map(function($e) {
      return "`$e`";
    }, $fields), ',');
    
    $strValues = join(array_map(function($e) {
      return "'$e'";
    }, $values), ',');
    
    $sql = "INSERT INTO $table($strFields) VALUES($strValues);";
    
    return $this->driver->query($sql);
}

}
