<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require 'models/ShoppingCart.php';

$shoppingCart = new ShoppingCart();
?>

<HTML>
<HEAD>
<TITLE>Enriched Responsive Shopping Cart in PHP</TITLE>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="assets/style.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>




<?php
$cartItem = $shoppingCart->getMemberCartItem($_SESSION['user']['id']);
$item_quantity = 0;
$item_price = 0;
if (! empty($cartItem)) {
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
            <div class="txt-heading-label">Shopping Cart</div>

            <a id="btnEmpty" href="index.php?action=empty"><img
                src="image/empty-cart.png" alt="empty-cart"
                title="Empty Cart" class="float-right" /></a>
            <div class="cart-status">
                <div>Total Quantity: <?php echo $item_quantity; ?></div>
                <div>Total Price: <?php echo $item_price; ?></div>
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
        $shoppingCart->emptyCart($_SESSION['user']['id']);
        ?>

</div>
    
    <div class="success">
    Gracias por comprar con nosotros. Su pedido ha sido realizado. Su ID de pedido es <?php echo $_GET["item_number"]; ?>
    </div>
    <div>
        <a href="./"><button class="btn-continue">Continue Shopping</button></a>
    </div>
</BODY>
</HTML>