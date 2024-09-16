<?php 
/**
 * Set various variables based on whether app is live or local.
 * Typically, only one variable will be changed in this file, the $live variable.
 * Set to false when local, and to true when live.
 *
 * @author  Cedric Che-Azeh
 * @license Blogvisa Terms of Use
 */

 session_start();

class Live  {
  public $live = false; // Change based on state of app

  /**
   * Return base URL for app API
   *
   * @return string
   */
  public function getHome() {
    return $this->live ? 'https://dev.bleaglee.com/' : 'http://localhost/waste2reward/';
  }

  /**
   * Return root resource for app API
   *
   * @return string
   */
  public function getRoot() {
    return $this->live ? $_SERVER['DOCUMENT_ROOT'].'/app/' : $_SERVER['DOCUMENT_ROOT'].'/waste2rewards-backend/';
  }

  /**
   * Return database connection parameters
   *
   * @return array [host, user, password, database]
   */
  public function getDBParams() {
    $dbo = array(
      'host'      => 'localhost',
      'user'      => 'root',
      'password'  => 'growthisconstant',
      'database'  => 'waste2rewards'
    );

    if($this->live) {
      $dbo = array(
        'host'      => 'localhost',
        'user'      => 'bleagle1_waste2rewards',
        'password'  => 'PuZEtPIey',
        'database'  => 'bleagle1_waste2rewards'
      );
    }

    return $dbo;
  }
  
  	
}

