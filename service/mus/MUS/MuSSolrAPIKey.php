<?php
abstract class MuSSolrAPIKey {
  protected $apikeyInfo = array();

  /**
   * Use the constructor to load the information from the database.
   */
  abstract function __construct($apikey);

  public function getAccessString() {
    return isset($this->apikeyInfo['access_rights']) ? $this->apikeyInfo['access_rights'] : '';
  }

  public function mayBypassBlacklist() {
    return isset($this->apikeyInfo['bypass_blacklist']) ? $this->apikeyInfo['bypass_blacklist'] : FALSE;
  }

  public function getFilterQuery() {
    return isset($this->apikeyInfo['filterquery']) ? $this->apikeyInfo['filterquery'] : FALSE;
  }

  /**
   * Factory function to get right implementation based on system detection
   * @param String $apikey
   * @return Implementation of MuSSolrAPIKey
   */
  static function getInstance($apikey) {
    // Detect Drupal
    if (function_exists('drupal_get_form')) {
      return new MuSSolrAPIKeyDrupal($apikey);
    }
    // Else Slim is used
    else {
      return new MuSSolrAPIKeySlim($apikey);
    }
  }

}

class MuSSolrAPIKeyDrupal extends MuSSolrAPIKey {
  function __construct($apikey) {
    db_set_active('musapi');
    $query = db_select('musapi', 'm');
    $query->fields('m');
    $query->condition('apikey', $apikey);
    $info = $query->execute()->fetchAssoc();
    if (isset($info['apikey'])) {
      $this->apikeyInfo = $info;
    }
    db_set_active();
  }
}


class MuSSolrAPIKeySlim extends MuSSolrAPIKey {
  function __construct($apikey) {
    // Get the slim instance
    $app = Slim::getInstance();
    // Generate PDO database string
    $dbConnectString = 'mysql:host=' . $app->config('db_host') . ';dbname=' . $app->config('db_database');
    $db = new PDO($dbConnectString, $app->config('db_username'), $app->config('db_password'));
    $query = $db->prepare('SELECT * FROM musapi WHERE apikey = :apikey');
    $query->execute(array(':apikey' => $apikey));
    $info = $query->fetch(PDO::FETCH_ASSOC);
    if (isset($info['apikey'])) {
      $this->apikeyInfo = $info;
    }
  }
}