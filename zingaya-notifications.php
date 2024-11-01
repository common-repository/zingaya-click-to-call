<?php
global $userinfo;
?>
<div style="clear:both"></div>
<form method="post" action="" style="float: left"> 
<table class="form-table">
	<tr valign="top">
	<th scope="row">
    <label for="first_name"><?php _e("Mobile phone", "zingaya"); ?>:</label>
    </th>
	<td><input name="mobile_phone" value="<?php echo (isset($userinfo->mobile_phone))?$userinfo->mobile_phone:""; ?>" class="regular-text"></td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="disable_personal_mails"><?php _e("Disable personal mails", "zingaya"); ?>:</label>
    </th>
	<td><input type="checkbox" id="disable_personal_mails" name="disable_personal_mails"<?php if ( $userinfo->disable_personal_mails ) echo " checked"; ?>></td>
	</tr>
	<tr valign="top">
	<th scope="row">
    <label for="disable_common_mails"><?php _e("Disable common mails", "zingaya"); ?>:</label>
    </th>
	<td align="left"><input type="checkbox" name="disable_common_mails" id="disable_common_mails"<?php if ( $userinfo->disable_common_mails ) echo " checked"; ?>></td>
</table>
<input type="hidden" name="action" value="update">
<?php submit_button(__('Update', 'zingaya')); ?>
</form>