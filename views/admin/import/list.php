<div class="product_pagination">
  <div class='products_found'><span class='product_count'><?php echo $product_count; ?></span> products found</div>
<?php for($i=1; $i): ?>
  <?php if($page['number'] == $current_page): ?>
      <span class='current_page'><?php echo $page['number']; ?></span>
    <?php else: ?>
      <a class="product_page_link" href="#"  data-page-number=""><?php echo $page['message']; ?></a>
<?php   endif;
      endforeach; ?>
</div>

<form action="" id="shcp_import_form" method="post">

<div class="shcp_import_all_button">
  <input type='submit' value='Import all <?php echo $product_count; ?> Products' id='save_all_products' data-product-count="<?php echo $product_count; ?>" data-method="<?php echo $method; ?>" />
</div>  
<table class="widefat" id="shcp_import_table">
  <thead>
    <tr>
      <th></th>
      <th>Product</th>
      <th>Title</th>
      <th>Part Number</th>
      <th>Cut Price</th>
      <th>Display Price</th>
    
    </tr>
    <tr>
      <th><input type="checkbox" name="import_all" id="import_all" /></th>
      <th colspan="7">Import All</th>
    </tr>
  </thead>
  <tbody>
<?php
	if($products):
  		foreach($products as $product):
?>
      <tr id="row_<?php echo $product->partnumber; ?>">
        <td>
          <input type="checkbox" name="import_single[]" class="checkbox" value="<?php echo $product->partnumber; ?>" />
        </td>
        <td class="image"><?php echo Helper_Products::image($product->imageurl); ?></td>
        <td class="name"><?php echo $product->name;?></td>
        <td class="partnumber"><?php echo $product->partnumber;?></td>
        <td class="cutprice"><?php echo $product->cutprice;?></td>
        <td class="displayprice"><?php echo $product->displayprice;?></td>
        <!-- <td><input type="checkbox" name="is_featured" class="checkbox" value="" /></td>
        <td><input type="checkbox" name="is_hidden" class="checkbox" value="" /></td> -->
      </tr>
<?php
  	 endforeach; 
   endif;
?>
  </tbody>
</table>
<br style='clear:both' />
<input type='submit' value='Save Selected Products' id='save_products' />
<br /><br />
<input type="hidden" name="page_number" id="page-number" value="<?php echo $next_page;?>" />
</form>

