<?php foreach($options as $label=>$value): ?>
	<input type="radio" name="<?php echo $name;?>" id="<?php echo $id . '_' . $value;?>" value="<?php echo $option;?>" <?php if($checked == $value) echo ' checked="checked"';?> />
	<label for="<?php echo $id . '_' . $value;?>"><?php echo $label;?></label>
<?php endforeach;?>