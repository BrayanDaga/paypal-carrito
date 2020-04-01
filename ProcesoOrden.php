<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require 'models/ShoppingCart.php';

$shoppingCart = new ShoppingCart();
?>

<?php
$clave = session_id();
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

if(!empty($_POST["proceed_payment"])) {
    $name = $_POST ['name'];
    $address = $_POST ['address'];
    $city = $_POST ['city'];
    $zip = $_POST ['zip'];
    $country = $_POST ['country'];
    $state = $_POST ['state'];

}
$order = 0;
if (! empty ($name) && ! empty ($address) && ! empty ($city) && ! empty ($zip) && ! empty ($country)) {
    // able to insert into database
  //      print_r($_POST);
    $shoppingCart->insertOrder([
        'user_id' => $clave,
        'key_transact' => session_id(),
        'amount' => $item_price,
        'name' => $name,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'country' => $country
    ]);
    $order = $shoppingCart->endOrder();
//    print_r($order);    
    //print_r($cartItem);
    //print_r($_SESSION['user']['id']);
    if(!empty($order)) {
        if (! empty($cartItem)) {
            if (! empty($cartItem)) {
                foreach ($cartItem as $item) {
                    $shoppingCart->insertOrderItem ( $order, $item["id"], $item["price"], $item["quantity"]);
                }
            }
        }
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
    <style>
   
   /* Media query for mobile viewport */
   @media screen and (max-width: 400px) {
       #paypal-button-container {
          width: 100%;
       }
   }
  
   /* Media query for desktop viewport */
   @media screen and (min-width: 400px) {
       #paypal-button-container {
          width: 250px;
           display: inline-block;
       }
   }
  
</style>
</head>

<body>
   
    </header>
     <!-- Fixed navbar -->
        <!--Navbar-->
        <?php include_once('includes/navbar.php'); ?>
        <!--endNavbar-->
     <header>


    <div class="container">
        <h3 class="mt-2">PHP carrito de compras con integración de PayPal</h3>
        <hr>
        <div class="row">
            <div class="col-12 col-md-12">
                

            <div class="jumbotron text-center">
    <h1 class="display-4">¡Paso Final!</h1>
    <hr class="my-4">
    <p class="lead">Estas a punto de pagar con paypal la cantidad de :
        <h4>$<?= number_format($item_price,2) ?></h4>
        <div id="paypal-button-container"></div>
    </p>
    <p>Los productos podran ser descargados una vez se realize el pago  <br>
    <strong>(Para mas alaraciones : brayandaga5@gmail.com)</strong></p>
</div>


            </div>
        </div>

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
        <!-- Include the PayPal JavaScript SDK -->
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>

<script>
paypal.Button.render({
    env: 'sandbox', // sandbox | production
    style: {
        label: 'checkout',  // checkout | credit | pay | buynow | generic
        size:  'responsive', // small | medium | large | responsive
        shape: 'pill',   // pill | rect
        color: 'gold'   // gold | blue | silver | black
    },

    // PayPal Client IDs - replace with your own
    // Create a PayPal app: https://developer.paypal.com/developer/applications/create

    
    client: {
        sandbox:    'AZ4CQfVVXrrnbUFaXmMdaUHwum1MecrYmsD44Oh8U3n2JRfpUiL2fKvCDiQmV_OXXa63T0QHr5fpavLs',
        production: 'ASG9EobnfgoC49XC8Nit7OvaPaPmm_TIn2ayzmNl02JiR6-UtRY6RW155QY7LNXZ0oyNm0Lb7Z29ezc2   '
    },

    // Wait for the PayPal button to be clicked

    payment: function(data, actions) {
        return actions.payment.create({
            payment: {
                transactions: [
                    {
                        amount: { total: '<?=$item_price ?>', currency: 'USD' },
                        description:"Compra de productos a esta tienda:$<?= number_format($item_price,2) ?>",
                        custom: "<? $clave ?>#<?= openssl_encrypt($order,COD,KEY) ?>"
                    }
                ]
            }
        });
    },

    // Wait for the payment to be authorized by the customer

    onAuthorize: function(data, actions) {
        return actions.payment.execute().then(function() {
            console.log(data);
            window.location="verificador.php?paymentToken="+data.paymentToken+"&paymentID="+data.paymentID+"&orderID="+data.orderID;
        });
    }

}, '#paypal-button-container');

</script>

</body>

</html>
