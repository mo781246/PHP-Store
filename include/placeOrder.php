<?php
require_once "include/Session.php";
$session = new Session();

if (!isset($session->user)) {  // invalid user
  die("prohibited");
}

require_once "include/DB.php";
DB::init();

$now = time();
foreach ($cart as $id => $quantity):
                $item = R::load('item', $id);
$order = R::dispense('order');
$order->user_id = $session->id;
$order->created_at = $now;
$order->link('item_order', 
        array('quantity' => 4,'price'=>$item->price))->item = $item;
$id = R::store($order);
echo "#$id: $user->name\n";
?>
