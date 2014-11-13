<?php
require_once "include/Session.php";
$session = new Session();

if (!isset($session->user) || $session->user->level == 0) {
  die("prohibited");
}

require_once "include/DB.php";
DB::init();
$categorys = array("Accessory","Calculator","Camera","Computer",
    "Copy-Scan","Network","Printer","Storage","Video-Audio","Voice");

$params = (object) $_REQUEST;
//print_r($params);

if (isset($params->doit)) {
  try {
    $name = trim($params->name);
    $category = $params->category;
    $price = trim($params->price);
    $description = trim($params->description);
    $image = trim($params->image);

    if (strlen($name) < 3) {
      throw new Exception("Item name must contain at least 3 chars");
    }
    if (!preg_match("/^[0-9]+(?:\.[0-9]{2})?$/", $price)) {
      throw new Exception("Illegal price format");
    }

    $item = R::dispense('item');
    $item->name = $name;
    $item->price = $price;
    $item->category = $category;
    $item->description  = $description;
    $item->image = $image;
    $id = R::store($item);
    header("location: showItem.php?item_id=$id");
    exit();
  //} catch (RedBean_Exception_SQL $ex) {
  //  $response = "duplicate title";
  } catch (Exception $ex) {
    $response = $ex->getMessage();
  }
}
else {
  $response = "";
  $params->name = "";
  $params->price = "";
  $params->category = "";
  $params->description = "";
  $params->image = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>Add Book</title>

<link rel="stylesheet" type="text/css" href="css/superfish.css" />
<link rel="stylesheet" type="text/css" href="css/layout.css" />
<link rel="stylesheet" type="text/css" href="css/table-display.css" />
<link rel="stylesheet" type="text/css" href="css/book-form.css" />
<style type="text/css">
</style>

</head>

<body>
<div class="container">
<div class="header"><?php require_once "include/header.php" ?></div>
<div class="navigation"><?php require_once "include/navigation.php" ?></div>
<div class="content"><!-- content -->

<h2>Add Book</h2>

<form action="adminAddItem.php" method="post">
  <table>
    <tr>
      <th>Name:</th>
      <td><input type='text' name='name' 
                 value="<?php echo htmlspecialchars($params->name) ?>" />
      </td>
    </tr>
   <tr>
      <th>Price:</th>
      <td><input type='text' name='price'
                 value="<?php echo htmlspecialchars($params->price) ?>" />
      </td>
    </tr>
    <tr>
      <th>Category:</th>
      <td>
          <select name='category'>
  <?php foreach($categorys as $value) : ?>
    <option value="<?php echo $params->category = $value ?>"><?php echo $value ?></option>
     <?php endforeach ?> 
</select>
 </td>     
    </tr>
    <tr>
      <th>Description:</th>
      <td><textarea rows="20" cols="50" name='description' 
                    <?php echo $params->description;?> />
</textarea>
      </td>
    </tr>
    <tr>
      <th>Image:</th>
      <td><input type='text' size= "75" name='image'
                 value="<?php echo htmlspecialchars($params->image) ?>" />
      </td>
    </tr>
    <tr>
      <td></td>
      <td><button type="submit" name="doit">Add</button></td>
    </tr>
  </table>
</form>

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
