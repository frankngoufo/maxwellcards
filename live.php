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
  public $live = false; // True for production, false for development

  /**
   * Return base URL for app API
   *
   * @return string
   */
  public function getHome() {
    return $this->live ? 'https://phpvisa.com/' : 'http://localhost/phpvisa/';
  }

  /**
   * Return root resource for app API
   *
   * @return string
   */
  public function getRoot() {
    return $this->live ? $_SERVER['DOCUMENT_ROOT'].'/' : $_SERVER['DOCUMENT_ROOT'].'/phpvisa/';
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
      'database'  => 'phpvisa'
    );

    if($this->live) {
      $dbo = array(
        'host'      => 'localhost',
        'user'      => '',
        'password'  => '',
        'database'  => ''
      );
    }

    return $dbo;
  }
  
  	
}

