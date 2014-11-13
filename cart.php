<?php
require_once "include/Session.php";
$session = new Session();

require_once "include/DB.php";
DB::init();

$params = (object) $_REQUEST;

$cart = $session->cart;
$cartTotal = 0;
$itemTotal = 0;

if (isset($params->remove)) {
  unset($session->cart[$params->itemID]); 
  header("location: cart.php");
}

if (isset($params->clear)) {
  session_destroy();
  header("location: cart.php");
}

if (isset($params->update)) {
try {
    $item = R::load('item',$params->id);
    $quantity = trim($params->quantity);
    if (!preg_match("/^\d+$/", $quantity)) {
      throw new Exception("illegal quantity format");
    }   
      if ($quantity == 0) {   
        unset($session->cart[$item->id]);
      } 
      else { 
      $session->cart[$item->id] = $quantity;

    }
    header("location: cart.php");
  } catch (Exception $ex) {
    $response = $ex->getMessage();
  }
}
else {
  $response = "";
  $params->quantity = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Shopping Cart</title>

    <link rel="stylesheet" type="text/css" href="css/superfish.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <link rel="stylesheet" type="text/css" href="css/table-display.css" />
    <style type="text/css">
      /* local style rules */
    </style>

  </head>

  <body>
    <div class="container">
      <div class="header"><?php require_once "include/header.php" ?></div>
      <div class="navigation"><?php require_once "include/navigation.php" ?></div>
      <div class="content"><!-- content -->

        <h2>My Cart</h2>
        <form name="form1" method="post">
          <table border="1" width = "100%">
            <?php
            if (is_array($cart)) {
              echo '<tr><td>ID</td><td>Name</td><td>Price</td>'
              . '<td>Qty</td><td>Amount</td><td>Options</td></tr>';
              ?>
              <?php
              foreach ($cart as $id => $quantity):
                $item = R::load('item', $id);
                $itemTotal = $item->price * $quantity;
                $cartTotal += $itemTotal;
                ?>
                <tr>
                  <td><?php echo $item->id ?></td>
                  <td><?php echo $item->name ?></td>
                  <td>$<?php echo $item->price ?></td>
                <form action="cart.php">
                  <td>
                      <input type="hidden" name="id" value="<?php echo $item->id ?>" />
                      <input type='text' size="1" name='quantity'
                             value="<?php echo htmlspecialchars($quantity) ?>" />
                      <button name="update">Update</button>
                  </td>
                </form>
                <td>$<?php echo $itemTotal ?></td>
                <form action = "cart.php">
                    <td>
                    <input type='hidden' name="itemID" value="<?php echo $item->id ?>" />
                    <button name="remove">Remove</button>
                  </td>
                </form>
                </tr>
              <?php endforeach ?>
              <tr>
                <td>Total:</td>
                <td>$<?php echo $cartTotal ?></td>
              <form action = "cart.php">
                <td colspan="2">
                  <button name="clear">Clear</button>
                </td>
              </form>
              <form action = "placeOrder.php">
                <td colspan="2">
                   <input type='hidden' name="cartItem" value="<?php echo $cart ?>" />
                  <button name="placeOrder">Place Order</button>
                </td>
              </tr>
              <?php
            }
            else {
              echo "<td>There are no items in your shopping cart!</td>";
            }
            ?>
          </table>
            <h3><?php echo $response?></h3>
      </div><!-- content -->
    </div><!-- container -->

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/superfish.min.js"></script>
    <script type="text/javascript" src="js/init.js"></script>
    <script type="text/javascript">
      /* local JavaScript */
    </script>

  </body>
</html>
