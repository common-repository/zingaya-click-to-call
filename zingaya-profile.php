<?php
	if (!isset($_POST['action'])) { 
?>
<div style="clear:both"></div>
<form method="post" action="" style="float: left"> 
<table class="form-table">
	<tr valign="top">
            <th scope="row">
                <label for="first_name"><?php _e("First name", "zingaya"); ?>:</label>
            </th>
            <td><input name="first_name" value="<?php echo $profile['first_name']; ?>" disabled class="regular-text"></td>
	</tr>
	<tr valign="top">
            <th scope="row">
                <label for="last_name"><?php _e("Last name", "zingaya"); ?>:</label>
            </th>
            <td><input name="last_name" value="<?php echo $profile['last_name']; ?>" disabled class="regular-text"></td>
	</tr>
	<tr valign="top">
            <th scope="row">
                <label for="email"><?php _e("Email", "zingaya"); ?>:</label>
            </th>
            <td><input name="email" value="<?php echo $profile['email']; ?>" disabled class="regular-text"></td>
	</tr>
	<tr valign="top">
            <th scope="row">
                <label for="timezone"><?php _e("Timezone", "zingaya"); ?>:</label>
            </th>
            <td><input name="timezone" value="<?php 
                foreach ($timezones as $key => $value) {
                        if ($key==$profile['timezone']) echo $value; 
                }
            ?>" disabled class="regular-text"></td>
	</tr>
	<tr valign="top">
            <th scope="row">
                <label for="company"><?php _e("Company", "zingaya"); ?>:</label>
            </th>
            <td><input name="company" value="<?php echo $profile['company']; ?>" disabled class="regular-text"></td>
	</tr>
	<tr valign="top">
            <th scope="row">
                <label for="address"><?php _e("Address", "zingaya"); ?>:</label>
            </th>
            <td><input name="address" value="<?php echo $profile['address']; ?>" disabled class="regular-text"></td>
	</tr>
</table>
<input type="hidden" name="action" value="edit">
<?php submit_button(__('Edit', 'zingaya')); ?>
</form>
<?php } else if ($_POST['action'] == 'edit') { ?>
<form method="post" action=""> 
<table class="form-table">
	<tr valign="top">
	<th scope="row">
    <label for="first_name"><?php _e("First name", "zingaya"); ?>:
    <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
    </label>
    </th>
	<td><input name="profile[first_name]" id="first_name" value="<?php echo $profile['first_name']; ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="last_name"><?php _e("Last name", "zingaya"); ?>:
    <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
    </label>
    </th>
	<td><input name="profile[last_name]" id="last_name" value="<?php echo $profile['last_name']; ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="email"><?php _e("Email", "zingaya"); ?>:
    <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
    </label>
    </th>
	<td><input name="profile[email]" id="email" value="<?php echo $profile['email']; ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="timezone"><?php _e("Timezone", "zingaya"); ?>:
    <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
    </label>
    </th>
	<td><select name="profile[timezone]" id="timezone">
		<?php
			foreach ($timezones as $key => $value) {
				echo '<option value="'.$key.'" '.($key==$profile['timezone']?'selected':'').'>'.$value.'</option>';
			}
		?>
	</select>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="company"><?php _e("Company", "zingaya"); ?>:</label>
    </th>
	<td><input name="profile[company]" id="company" value="<?php echo $profile['company']; ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="address"><?php _e("Address", "zingaya"); ?>:</label>
    </th>
	<td><input name="profile[address]" id="address" value="<?php echo $profile['address']; ?>" class="regular-text"></td>
	</tr>	
</table>
<input type="hidden" name="action" value="save">
<div style='float: left'><?php submit_button(__('Save', 'zingaya'), 'primary', 'submit', false); ?></div>
</form>
<form method="get" action="">
<input type="hidden" name="page" value="zingaya/zingaya-admin.php">
<input type="hidden" name="tab" value="profile">
<div style='float: left; margin-left: 10px'><?php submit_button(__('Cancel', 'zingaya'), 'secondary', null, false); ?></div>
</form>
<?php } ?>
