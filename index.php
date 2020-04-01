<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require 'models/ShoppingCart.php';

$shoppingCart = new ShoppingCart();
if (! empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if(!empty($_SESSION['user']['id'])){
                if (! empty($_POST["quantity"])) {
                    $productResult = $shoppingCart->getProductByCode($_GET["code"]);
                   
                    $cartResult = $shoppingCart->getCartItemByProduct([
                        'product_id' => $productResult["id"],
                        'user_id'   => $_SESSION['user']['id']
                    ]);
                    
                    if (! empty($cartResult)) {
                        //print_r($cartResult);
                        // Update cart item quantity in database
                        $newQuantity = $cartResult[0]["quantity"] + $_POST["quantity"];
                        $shoppingCart->updateCartQuantity([
                            'quantity'  => $newQuantity,
                            'id'    =>   $cartResult[0]["id"]
                        ]);
                    } else {
                        //print_r($productResult['id']);
                        // Add to cart table
                        $shoppingCart->addToCart([
                            'product_id'    =>  $productResult['id'],
                            'quantity'    => $_POST["quantity"],
                            'user_id'    => $_SESSION['user']['id']
                        ]);
                    
                    }
    
                }
            }else{
                header('Location: login.php');
            }
            
            break;
        case "remove":
            // Delete single entry from the cart
            $shoppingCart->deleteCartItem($_GET["id"]);
            break;
        case "empty":
            // Empty cart
            $shoppingCart->emptyCart($_SESSION['user']['id']);
            break;
    }
}
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
        <!--Navbar-->
        <?php include_once('includes/navbar.php'); ?>
        <!--endNavbar-->
</header>

<!-- Begin page content -->

<div class="container">
  <h3 class="mt-5">PHP carrito de compras con integración de PayPal</h3>
  <hr>
  <div class="row">
    <div class="col-12 col-md-12"> 
      <!-- Contenido -->
      

<?php
$item_quantity = 0;
$item_price = 0;

if(!empty($_SESSION['user']['id'])){
    
$cartItem = $shoppingCart->getMemberCartItem($_SESSION['user']['id']);
    if (! empty($cartItem)) {
        foreach ($cartItem as $item) {
            $item_quantity = $item_quantity + $item["quantity"];
            $item_price = $item_price + ($item["price"] * $item["quantity"]);
        }
    }
}

?>
<div id="shopping-cart">
        <div class="txt-heading">
            <div class="txt-heading-label">Carrito de Compras</div>

            <a id="btnEmpty" href="index.php?action=empty"><img
                src="image/empty-cart.png" alt="empty-cart"
                title="Carta vacia" class="float-right" /></a>
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
            <div class="align-right">
            <a href="ProcesoPago.php"><button class="btn-action" name="check_out">Ir a Pagos</button></a>
            </div>
<?php
        } // End if !empty $cartItem
        ?>

</div>
<?php
require_once "includes/listProducts.php";
?>
    

      <!-- Fin Contenido --> 
    </div>
  </div>
  <!-- Fin row --> 

  
</div>
<!-- Fin container -->
<footer class="footer">
  <div class="container"> <span class="text-muted">
    <p>Códigos Brayan </div>
</footer>
<script src="assets/jquery-1.12.4-jquery.min.js"></script> 

<script src="assets/jquery.validate.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>