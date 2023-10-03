<?php
   //get parameters passed in by controller.
use store\model\Product;

$product = $params['product']??null;
   $action = $params['action']??"";
   $result = $params['result']??null;
   $message = $params['message']??null;
   $messageCorrect = $params['messageCorrect']??null;
   if (is_null($product)) {
        $product = new Product(1, "");
   }
   //stablish button activation
   $disableAddButton = ($action == "product/search" ||  $action == "product/modify"  || !isset($_SESSION['role']) )?"disabled":"";
   $disableModifyButton = ($action == "product/add" || $action == "product/form" || $action == "product/remove" || !isset($_SESSION['role']))?"disabled":"";
   $disableRemoveButton = ($action == "product/add" || $action == "product/form" || !isset($_SESSION['role']))?"disabled":"";
   if(isset($_SESSION['role'])){
      if($_SESSION['role'] != "admin" && $_SESSION['role'] != "staff"){
        $disableAddButton = "disabled";
        $disableModifyButton = "disabled";
        $disableRemoveButton = "disabled";
      }
   }
   //show previous action information, if present
   if (!is_null($result)) {
       echo <<<EOT
       <div><p class="alert">$result</p></div>
  EOT;
   }   
   echo <<<EOT
   <form id="product-form" method="post" action="index.php">
    <fieldset><legend>Product form</legend>
        <label for="id">Id: </label>
        <input type="text" name="id" id="id" placeholder="Enter id" value="{$product->getId()}"/>
        <label for="description">Description: </label>
        <input type="text" name="description" id="description" placeholder="Enter description" value="{$product->getDescription()}"/>
        <label for="price">Price: </label>
        <input type="text" name="price" id="price" placeholder="Enter price" value="{$product->getPrice()}"/>
        <label for="stock">Stock: </label>
        <input type="text" name="stock" id="stock" placeholder="Enter stock" value="{$product->getStock()}"/>
   </fieldset>

        <button type="submit" id="findItem" name="action" value="product/search">Search by Product ID</button>
        <button type="submit" id="addItem" name="action" value="product/add" {$disableAddButton}>Add Product</button>
        <button type="submit" id="modifyItem" name="action" value="product/modify" {$disableModifyButton}>Modify by Product ID</button>
        <button type="submit" id="removeItem" name="action" value="product/remove" {$disableRemoveButton}>Remove by Product ID</button>
        <button type="submit" id="Clear" name="action" value="product/form">Clear</button>
        <br><br>
        <label class="errors">$message</label>
        <label class="infoText">$messageCorrect</label>
</form>
EOT;
?>