<div class="wrap">
<h2><?php _e("Zingaya Administrator", "zingaya"); ?></h2>
<?php
    global $zingaya_countries;
    
    if ( isset($_GET['action']) && $_GET['action']=="authorize") {
    	// SHOW USER CREDENTIALS FORM
    	?>
        <p><?php _e("Please specify your Zingaya account info for authorization", "zingaya"); ?>.</p>
        <p><?php _e("If you don`t have Zingaya account yet, you can ", "zingaya"); ?> <a href="<?php echo admin_url('admin.php?page=zingaya/zingaya-admin.php&action=signup'); ?>"><?php _e("Sign up here", "zingaya"); ?></a>.</p>
    	<form method="post" action=""> 
    	<table class="form-table">
    		<tr valign="top">
    		<th scope="row">
            <label for="email"><?php _e("Email/Username", "zingaya"); ?>
            <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
            </th>
    		<td><input name="email" value="<?php echo get_option('zingaya_user_email', false); ?>" ></td>
    		</tr>
    		<tr valign="top">
    		<th scope="row">
                <label for="password"><?php _e("Password"); ?>
                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                </label>
            </th>
    		<td>
                    <input name="password" type="password" value="" >
                    <br />
                    <span class="description"><?php _e("Your Zingaya account password", "zingaya"); ?></span>
    		</td>
    		</tr>
    		<!--<tr valign="top"><td colspan="2">OR</td></tr>
    		<tr valign="top">
    		<th scope="row">API KEY</th>
    		<td>
    			<input name="apikey" >
    			<p class="description">Your Zingaya account APIKEY.</p>
    		</td>
    		</tr>-->
    	</table>
        <input type="hidden" name="action" value="authorize" >
    	<?php submit_button(__('Authorize & Enable', 'zingaya')); ?>
    	</form>
    	<?php
    } else {
    	// SHOW SIGN UP FORM
    	global $current_user;
		get_currentuserinfo();

    	?>
    	<p><?php _e("Please fill the form below to create your Zingaya account", "zingaya"); ?>.</p>
        <p><?php _e("Or", "zingaya"); ?> <a href='<?php echo admin_url('admin.php?page=zingaya/zingaya-admin.php&action=authorize'); ?>'><?php _e("Sign in here", "zingaya"); ?></a> <?php _e("if you already have Zingaya account", "zingaya"); ?>.</p>
    	<form method="post" action=""> 
            <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                                <label for="email">Email
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                                </label>
                        </th>
                        <td>
                            <input name="email" id="email" value="<?php echo (!isset($_POST['email'])?$current_user->user_email:$_POST['email']); ?>" >
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                                <label for="first_name"><?php _e("First name", "zingaya"); ?>
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                                </label>
                        </th>
                        <td><input name="first_name" id="first_name" value="<?php echo (isset($_POST['first_name']))?$_POST['first_name']:""; ?>" ></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                                <label for="last_name"><?php _e("Last name", "zingaya"); ?>
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                                </label>
                        </th>
                        <td><input name="last_name" id="last_name" value="<?php echo (isset($_POST['last_name']))?$_POST['last_name']:""; ?>" ></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="country"><?php _e("Country", "zingaya"); ?>
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                            </label>
                        </th>
                        <td>
                            <select name="country" id="country">
                                <?php foreach ( $zingaya_countries as $key => $value ) { ?>
                                    <option value="<?php echo $key; ?>"<?php echo ((isset($_POST['country']) && $_POST['country'] == $key)?" selected":""); ?>><?php _e($value, 'zingaya'); ?></option>
                                <?php } ?>
                            </select>
                            <br />
                            <span class="description"><?php _e("Select a country on the phone number which will be forwarded calls", "zingaya"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="phone"><?php _e("Phone", "zingaya"); ?>
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                            </label>
                        </th>
                        <td>
                            <input name="phone" id="phone" value="<?php echo (isset($_POST['phone']))?$_POST['phone']:""; ?>" >
                            <br />
                            <span class="description"><?php _e("For example, for US 18005553723", "zingaya"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="password"><?php _e("Password"); ?>
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                            </label>
                        </th>
                        <td>
                            <input type="password" id="password" name="password" value="" >
                            <br />
                            <span class="description"><?php _e("At least 6 characters", "zingaya"); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="password_confirmation" id="password_confirmation"><?php _e("Password Confirmation", "zingaya"); ?>
                                <span class="description">(<?php _e("required", "zingaya"); ?>)</span>
                            </label>
                        </th>
                        <td>
                            <input type="password" name="password_confirmation" value="" >
                        </td>
                    </tr>
            </table>
            <input type="hidden" name="action" value="signup" >
            <?php submit_button(__('Sign Up', 'zingaya')); ?>
    	</form>
    	<?php
    }

?>
</div>