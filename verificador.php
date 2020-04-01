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
</header>


<?php
//print_r($_GET);

   
    $Login= curl_init(LINKAPI."/v1/oauth2/token");
    curl_setopt($Login,CURLOPT_SSL_VERIFYPEER, false);//Linea necesaria para desbloquear y quitar la verificacion de seguridad
    curl_setopt($Login,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($Login,CURLOPT_USERPWD,CLIENTID.":".SECRET);
    curl_setopt($Login,CURLOPT_POSTFIELDS,"grant_type=client_credentials");//Credenciales
    $Respuesta=curl_exec($Login);
    $objRespuesta=json_decode($Respuesta); 
    $AccesToken=$objRespuesta->access_token;
  //  echo "<br>";
   // print_r($AccesToken);
   // echo "<br><h1>-----------</h1>";
    $venta=curl_init(LINKAPI."/v1/payments/payment/".$_GET['paymentID']);
    curl_setopt($venta,CURLOPT_SSL_VERIFYPEER,false); 
    curl_setopt($venta,CURLOPT_HTTPHEADER,array("Content-Type: application/json","Authorization: Bearer ".$AccesToken));
    curl_setopt($venta,CURLOPT_RETURNTRANSFER,true);
    $payment_response=curl_exec($venta);
    //print_r($payment_response);

    $objDatosTransaccion =  json_decode($payment_response);
    //print_r($objDatosTransaccion->payer->payer_info->email);
    
    $payment_status =  $objDatosTransaccion->state;
    $email = $objDatosTransaccion->payer->payer_info->email;

    $total = $objDatosTransaccion->transactions[0]->amount->total;
    $currency = $objDatosTransaccion->transactions[0]->amount->currency;
    $custom = $objDatosTransaccion->transactions[0]->custom;

    $clave = explode('#',$custom);
    $ID = $clave[0];
    $order_id = openssl_decrypt($clave[1],COD,KEY);

    //print_r($claveVenta);
    $req = 'cmd=_notify-validate';

   curl_close($venta);
    curl_close($Login);

   // echo  $claveVenta;
   $isPaymentCompleted = false;

    if($payment_status == "approved"){
      $isPaymentCompleted = true;
        $shoppingCart->insertPayment([
          'order_id'=>$order_id,
          'payment_status' => $payment_status,
          'payment_response' => $payment_response
        ]);
        $payment_id= $shoppingCart->endPayment();
	// process payment and mark item as paid.
	    $shoppingCart->paymentStatusChange ([
        'order_status' => 'PAID',
        'id' => $order_id
      ]);
  error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ");
  $mensajePaypal = "Pago aprobado";

    }else{
      error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req");

        $mensajePaypal = "Hay un problema con el pago de paypal";
    }
    echo $mensajePaypal;
    header('Location: respuesta.php?item_number='.$clave[1]);

?>