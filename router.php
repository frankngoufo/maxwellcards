<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
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
            'get-orders'            => '/me/get-orders.php',
            'prod-clients'          => '/me/prod-clients.php',
            'submit-order'          => '/me/submit-order.php',
            'completed'             => '/me/completed.php',
            'download'              => '/me/download.php',
            'modify'                => '/me/modify.php',
            'get-products'          => '/me/get-products.php',
            'otp'                   => '/submit-otp.php',
            'pay'                   => '/me/pay.php',
            'callback'              => '/me/pay-callback.php',
            'save-user'             => '/me/save-user.php',
            'get-cities'            => '/get-cities.php',
            'app-update'            => '/app-update.php',
            'get-countries'         => '/get-countries.php',
            'save-address'          => '/me/save-address.php',
            'admin-signin'          => '/admin-signin.php',
            'admin-privileges'      => '/admin/privileges.php',
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