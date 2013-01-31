<select name="<?php echo $name;?>" id="<?php echo $id;?>">
	<?php foreach($options as $value=>$option):?>
		<option value="<?php echo $value;?>" <?php if($value == $selected) echo ' selected="selected"';?>><?php echo $option;?></option>
	<?php endforeach;?>
</select>