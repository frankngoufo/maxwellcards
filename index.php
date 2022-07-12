<?php
require_once("page-home.inc.php");

$page = new HomePage();
$body = $page->create();
print($page->get_page());