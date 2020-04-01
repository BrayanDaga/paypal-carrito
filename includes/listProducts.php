<div id="product-grid">
    <div class="txt-heading">
        <div class="txt-heading-label">Productos</div>
    </div>
    <?php
  require_once 'models/Product.php';
  $cProcut = new Product();
  $products = $cProcut ->getAll();
  //print_r($products);

    if (! empty($products)) :
        foreach ($products as $key => $value) :
            ?>
        <div class="product-item">
        <form method="post"
            action="index.php?action=add&code=<?php echo $products[$key]["code"]; ?>">
            <div class="product-image">
                <img src="<?php echo $products[$key]["image"]; ?>">
                <div class="product-title">
                    <?php echo $products[$key]["name"]; ?>
                </div>
            </div>
            <div class="product-footer">
                <div class="float-right">
                    <input type="text" name="quantity" value="1"
                        size="2" class="input-cart-quantity" /><input type="image"
                        src="image/add-to-cart.png" class="btnAddAction" />
                </div>
                <div class="product-price float-left"><?php echo "$".$products[$key]["price"]; ?></div>
                
            </div>
        </form>
    </div>
    <?php
        endforeach;
    endif;
    ?>
</div>