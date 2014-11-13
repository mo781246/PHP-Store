<?php
require_once "include/Session.php";
$session = new Session();

if (!isset($session->user) || $session->user->level == 0) {
  die("prohibited");
}
    
require_once "include/DB.php";
DB::init();

$params = (object) $_REQUEST;

$order = R::load('order',$params->orderId);
if ($order->id == 0) {
  die("no such order for id $params->orderId");
}
else{
    print_r($order->id);
  R::trash($order);
  header("location: viewOrders.php");
}
