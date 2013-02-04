<div class="import-messages">
<?php if($message):?>
	<div class="import-message">
		<span class="response-message"><?php echo $message;?></span>
	</div>
<?php endif;?>

<?php if($errors):?>
	<ul class="import-errors">
		<?php foreach($errors as $error):?>
			<li class="response-error"><?php echo $error;?></li>
		<?php endforeach;?>
	</ul>
<?php endif;?>

</div>

<div class="product_pagination">
  <div class='products_found'><span class='product_count'><?php echo $product_count; ?></span> products found</div>
<?php if($prev_page):?>
	 <a class="product_prev_page_link" href="#"  data-page-number="<?php echo $prev_page;?>">Previous &lt;&lt;</a>
<?php endif;?>
<?php for($i = $start_index; $i <= $end_index; $i++): ?>
  <?php if($i == $current_page): ?>
      <span class='current_page'><?php echo $i;?></span>_
    <?php else: ?>
      <a class="product_page_link" href="#"  data-page-number="<?php echo $i;?>"><?php echo $i;?></a>
<?php   endif;
      endfor; ?>
<?php if($next_page):?>
	<a class="product_next_page_link" href="#"  data-page-number="<?php echo $prev_page;?>">Next &gt;&gt;</a>
<?php endif;?>
</div>

<form action="" id="shcp_import_form" method="post">

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
        <td class="image"><?php echo Plugin_Utils::image($product->imageurl); ?></td>
        <td class="name"><?php echo $product->name;?></td>
        <td class="partnumber"><?php echo $product->partnumber;?></td>
        <td class="cutprice"><?php echo $product->cutprice;?></td>
        <td class="displayprice"><?php echo $product->displayprice;?></td>
        <!-- <td><input type="checkbox" name="is_featured" class="checkbox" value="" /></td>
        <td><input type="checkbox" name="is_hidden" class="checkbox" value="" /></td> -->
      </tr>
<?php
  	 endforeach; 
  else:
?>
	<tr id="row_no_results">
		<td colspan="6">
			No results found for "<?php echo $search_term;?>".
		</td>
	</tr>

<?php endif;?>
  </tbody>
</table>
<br style='clear:both' />
<input type='submit' value='Save Selected Products' id='save_products' />
<br /><br />
<input type="hidden" name="search_term" id="search_term" value="<?php echo $search_term;?>" />
<input type="hidden" name="page_number" id="page_number" value="<?php echo $current_page;?>" />
</form>

