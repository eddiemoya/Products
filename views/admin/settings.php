<div>
	<h2>SHC SSO-Profile Plugin Settings</h2>
	
	<form action="options.php" method="post">
	<?php settings_fields($settings_field); ?>
	<?php do_settings_sections($settings_section); ?>
	 
	<input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
</div>