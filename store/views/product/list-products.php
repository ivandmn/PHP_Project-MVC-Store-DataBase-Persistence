<?php 
//Get message error if exist
$message = $params['message']??null;  
//$productList contains all product array passed from the controller
$productList = $params['productList'];
?>
<table <?php if (empty($productList)){ ?> hidden <?php }?> >
    <h2>List all products</h2>
    <tr>
        <th>id</th>
        <th>description</th>
        <th>price</th>
    </tr>
    <?php
    //display list of items in a table.
    //Iterate $productList for print information
    foreach ($productList as $elem) {
        echo <<<EOT
        <tr>
            <td>{$elem->getId()}</td>
            <td>{$elem->getDescription()}</td>
            <td>{$elem->getPrice()}</td>
        </tr>               
        EOT;
    }
    ?>
</table>
<p class="error"><?php echo $message ?></p>