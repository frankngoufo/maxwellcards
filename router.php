<?php
require_once __DIR__ . '/site-variables.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



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
            'kyc'                => '/me/kycpage.php',
            'signup'                => '/signup.php',
            'otp'                => '/submit-otp.php',
            'countries'              => '/countries.php',
          //   'get-countries'         => '/get-countries.php',  fund_card toggle_card_status.php get_beneficiaries.php
            'save-user'             => '/me/save-user.php',
            'get-user'              => '/me/get-user.php',
            'getuser'    => '/me/getuser.php',
            'updateuser'     => '/me/updateuser.php',
            'recent-transactions'     => '/me/get_recent_transactions.php',
            'all-transactions'     => '/me/get_all_transactions.php',
            'recent-notifications'     => '/me/get_recent_notifications.php',
            'add-notifications'     => '/me/add_notifications.php',
            'fund-card'     => '/me/fund_card.php',
            'withdraw-card'     => '/me/withdraw_card.php',
            'toggle-card'     => '/me/toggle_card_status.php',
            'delete-card'     => '/me/delete_card.php',
            'add-beneficiaries'     => '/me/add_beneficiaries.php',
            'get-beneficiaries'     => '/me/get_beneficiaries.php',
            'pay'                     => '/me/pay.php',
            'pay-callback'              => '/me/pay-callback.php', 
            'payout'              => '/me/payout.php', 
            'payout-callback'              => '/me/payout-callback.php',  
              'add-card'              => '/me/add-card.php', 
                'create-transaction-pin'              => '/me/create-transaction-pin.php', 


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