<?php

include_once 'lib/db.php';

class ShoppingCart extends DB
{


    function getMemberCartItem($user_id)
    {
        $query = $this->connect()->prepare(
            'SELECT products.*, shoppingcart.id as cart_id, shoppingcart.quantity FROM products, shoppingcart WHERE 
            products.id = shoppingcart.product_id AND shoppingcart.user_id = :user_id');
        $query->execute([
            'user_id' => $user_id
        ]);

        $items = $query->fetchAll(PDO::FETCH_ASSOC);
        return $items;
    }

    function getProductByCode($product_code)
    {
        $query = $this->connect()->prepare('SELECT * FROM products WHERE code = :code');
        $query->execute([
            'code' => $product_code
        ]);
        $items = $query->fetch(PDO::FETCH_ASSOC);
        return $items;
        
    }

    function getCartItemByProduct($datos)
    {
        $query = $this->connect()->prepare(
            'SELECT * FROM shoppingcart WHERE product_id = :product_id AND user_id = :user_id');
        $query->execute([
            'product_id' => $datos['product_id'],
            'user_id' => $datos['user_id']
        ]);

        $items = $query->fetchAll(PDO::FETCH_ASSOC);
        return $items;

    }

    function addToCart($datos)
    {
        $query = $this->connect()->prepare(
            "INSERT INTO `shoppingcart` (`product_id`, `quantity`, `user_id`) VALUES ( :product_id, :quantity , :user_id)");
        try{
            $query->execute([
                'product_id'    => $datos['product_id'],
                'quantity'    => $datos['quantity'],
                'user_id'    => $datos['user_id'],
            ]);
            return true;

        }catch(PDOException $e){
            return false;

        }
    }

    function updateCartQuantity($item)
    {        
        $query = $this->connect()->prepare(
            "UPDATE shoppingcart SET  quantity = :quantity WHERE id= :id" );
        try{
            $query->execute([
                'quantity'            => $item['quantity'],
            'id'                     =>  $item['id']
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

    function deleteCartItem($id)
    {
             
        $query = $this->connect()->prepare('DELETE FROM shoppingcart WHERE id = :id');
        try{
            $query->execute([
                'id' => $id
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }   
    }

    function emptyCart($user_id)
    {
        
        $query = $this->connect()->prepare('DELETE FROM shoppingcart WHERE user_id = :user_id');
        
        try{
            $query->execute([
                'user_id' => $user_id
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }   
    }
    
    function insertOrder($datos)
    {
         $query = $this->connect()->prepare(
            "INSERT INTO `order` ( `user_id`,  `key_transact`, `amount`, `name`, `address`, `city`, `state`, `zip`, `country`, `order_status`, `order_at`) 
            VALUES ( :user_id, :key_transact, :amount, :name, :address, :city, :state, :zip, :country, 'PENDING', NOW());");
        try{
            $query->execute([
                'user_id' => $datos['user_id'],
                'key_transact' => $datos['key_transact'],
                'amount' => $datos['amount'],
                'name' => $datos['name'],
                'address' => $datos['address'],
                'city' => $datos['city'],
                'state' => $datos['state'],
                'zip' => $datos['zip'],
                'country' => $datos['country']
            ]);
            return true;

        }catch(PDOException $e){
            return $id;

        }
    }


    function endOrder(){
        $query = $this->connect()->prepare('SELECT MAX(`id`) as id FROM `order` ');
        $query->execute();
        $row=$query->fetch();

        return $row['id'];
    }
    
    function endPayment(){
        $query = $this->connect()->prepare('SELECT MAX(`id`) as id FROM `payment` ');
        $query->execute();
        $row=$query->fetch();

        return $row['id'];
    }

    function insertOrderItem($order, $product, $price, $quantity)
    {
        $query = $this->connect()->prepare(
            "INSERT INTO `order_item` (`order_id`, `product_id`, `item_price`, `quantity`) 
            VALUES ( :order_id , :product_id, :item_price, :quantity)");
        try{
            $query->execute([
                'order_id'    => $order,
                'product_id'    => $product,
                'item_price'    => $price,
                'quantity'    => $quantity
            ]);
            return true;

        }catch(PDOException $e){
            return false;

        }
    }
    
    function insertPayment($datos)
    { 
        $query = $this->connect()->prepare(
        "INSERT INTO `payment` (`order_id`, `payment_status`, `payment_response`, `create_at`) 
        VALUES ( :order_id, :payment_status, :payment_response, NOW() ) ");
        try{
        $query->execute([
            'order_id'    => $datos['order_id'],
            'payment_status'    => $datos['payment_status'],
            'payment_response' => $datos['payment_response']
        ]);
        return true;

        }catch(PDOException $e){
            return false;

        }    
    }
    
    function paymentStatusChange($datos) {
        $query = $this->connect()->prepare(
            "UPDATE  `order` SET  `order_status` = :order_status WHERE `id`= :id");
            try{
            $query->execute([
                'order_status'    => $datos['order_status'],
                'id'    =>      $datos['id'],
            ]);
            return true;
    
            }catch(PDOException $e){
                return false;
    
            }    
    }
}
?>

