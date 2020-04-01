<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require 'models/ShoppingCart.php';

$shoppingCart = new ShoppingCart();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="favicon.ico">
<title>PHP carrito de compras con integración de PayPal - BaulPHP</title>

<!-- Bootstrap core CSS -->
<link href="dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="assets/sticky-footer-navbar.css" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">

</head>

<body>
<header> 
  <!-- Fixed navbar -->
  <?php include_once('includes/navbar.php'); ?>


<!-- Begin page content -->

<div class="container">
  <h3 class="mt-5">PHP carrito de compras con integración de PayPal</h3>
  <hr>
  <div class="row">
    <div class="col-12 col-md-12"> 
      <!-- Contenido -->
<?php
$cartItem = $shoppingCart->getMemberCartItem($_SESSION['user']['id']);
$item_quantity = 0;
$item_price = 0;
    if (! empty($cartItem)) {
        foreach ($cartItem as $item) {
            $item_quantity = $item_quantity + $item["quantity"];
            $item_price = $item_price + ($item["price"] * $item["quantity"]);
        }
    }
?>
<div id="shopping-cart">
        <div class="txt-heading">
            <div class="txt-heading-label">Carrito de Compras</div>

            <a id="btnEmpty" href="index.php?action=empty"><img
                src="image/empty-cart.png" alt="empty-cart"
                title="Empty Cart" class="float-right" /></a>
            <div class="cart-status">
                <div>Total Cantidad: <?php echo $item_quantity; ?></div>
                <div>Total Pricio: $ <?php echo $item_price; ?></div>
            </div>
        </div>
        <?php
        if (! empty($cartItem)) {
            ?>
<?php
    require_once ("includes/listCart.php");
    ?>
<?php
        } // End if !empty $cartItem
        ?>

</div>
    <form name="frm_customer_detail" action="ProcesoOrden.php" method="POST">
    <div class="frm-heading">
        <div class="txt-heading-label">Detalles del cliente</div>
    </div>
    <div class="frm-customer-detail">
        <div class="form-row">
            <div class="input-field">
                <input type="text" name="name" id="name"
                    PlaceHolder="Nombres" required>
            </div>

            <div class="input-field">
                <input type="text" name="address" PlaceHolder="Direccion" required>
            </div>
        </div>

        <div class="form-row">
            <div class="input-field">
                <input type="text" name="city" PlaceHolder="Ciudad" required>
            </div>

            <div class="input-field">
                <input type="text" name="state" PlaceHolder="Estado" required>
            </div>
        </div>

        <div class="form-row">
            <div class="input-field">
                <input type="text" name="zip" PlaceHolder="Zip Code" required>
            </div>

            <div class="input-field">
                <input type="text" name="country" PlaceHolder="Pais" required>
            </div>
        </div>
    </div>
    <div>
        <input type="submit" class="btn-action"
                name="proceed_payment" value="Proceder con el Pago">
    </div>
    </form>
      <!-- Fin Contenido --> 
    </div>
  </div>
  <!-- Fin row --> 

  
</div>
<!-- Fin container -->
<footer class="footer">
  <div class="container"> <span class="text-muted">
  <p>Códigos Brayan </div>
    </span> </div>
</footer>
<script src="assets/jquery-1.12.4-jquery.min.js"></script> 

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 

<script src="dist/js/bootstrap.min.js"></script>

</body>
</html>