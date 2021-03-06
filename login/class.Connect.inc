<?php

/**
 * MySQLi database; only one connection is allowed. 
 */
class Connect {
	
  private $_connection;
  // Store the single instance.
  private static $_instance;
  
  /**
   * Get an instance of the Database.
   * @return Database 
   */
  public static function getInstance() {
    if (!self::$_instance) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
  /**
   * Constructor.
   */
  private function __construct() {
    $this->_connection = new mysqli('localhost', 'root','usbw','klanten', '3307');
    // Error handling.
    if (mysqli_connect_error()) {
      trigger_error('Failed to connect to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);
    }
  }
 
  /**
   * Get the mysqli connection. 
   */
  public function getConnection() {
    return $this->_connection;
  }
}