<?php
require_once __DIR__ . '/site-variables.php';

Class Route {
    public function __construct() {
        if(empty($_REQUEST['route'])) { // Exit with error if route unspecified
            echo json_encode(array('error' => 'No route specified'));
            exit();
        }
    }

    public function route() {

        $requests = array(
            'signin'                => '/signin.php',
            'save-user'             => '/me/save-user.php',
        );

        if(array_key_exists($_REQUEST['route'], $requests)) {

            // Set up sessions
            //$_SESSION['s_id'] = empty(json_decode($_REQUEST['session'])) ? "" : json_decode($_REQUEST['session'])->s_id;

            require_once __DIR__.$requests[$_REQUEST['route']];
            $endpoint = new Endpoint();
            $endpoint->get_content();
        } else {
            echo json_encode((array('error' => "Invalid Route!")));
            exit();
        }
    }
}

$route = new Route();
$route->route();