<style>
    .work_hours {
        margin-top: -37px !important;
    }
</style>
<link rel='stylesheet' href='<?php echo plugins_url('/css/jslider.css', __FILE__); ?>' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo plugins_url('/css/jslider.round.plastic.css', __FILE__); ?>' type='text/css' media='all' />

<script type='text/javascript' src='<?php echo plugins_url('/js/jshashtable-2.1_src.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/jquery.numberformatter-1.2.3.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/tmpl.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/jquery.dependClass-0.1.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/draggable-0.1.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/jquery.slider.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/script.js', __FILE__); ?>'></script>
<?php 

global $planinfo; 
global $widget_data;

$gr = (isset($widget_data->graphics))?$widget_data->graphics:"";
$gr = explode(";", $gr);
$graphics = array();
if (is_array($gr) && count($gr) > 0 ) {
    foreach( $gr as $k => $v ){
        if ( empty($v) ) break;
        list($g1, $g2) = explode(":", $v);
        $graphics[$g1] = $g2;
    }
}
?>
<div class="wrap">
<h2><a href="?page=zingaya/zingaya-admin.php&tab=widgets" class="add-new-h2"><?php _e("Widgets list", "zingaya"); ?></a></h2>
<form method="post" action="" style="float: left"> 
    
<table class="form-table">
	<tr valign="top">
	<th scope="row">
    <label for="widget_name"><?php _e("Widget name", "zingaya"); ?> <span class="description">(<?php _e("required", "zingaya"); ?>)</span>:</label>
    </th>
	<td><input name="widget_name" value="<?php echo (isset($widget_data->widget_name))?$widget_data->widget_name:""; ?>" class="regular-text"></td>
	</tr>
    <tr valign="top">
        <th scope="row">
            <label for="active"><?php _e("Is active", "zingaya"); ?>:</label>
        </th>
        <td>
            <input type="checkbox" name="active" id="active"<?php echo ((isset($widget_data->active) && $widget_data->active)?" checked":""); ?>>
        </td>
    </tr>
	<tr valign="top">
	<th scope="row">
            <label for="callme_number"><?php _e("Phone number", "zingaya"); ?> <span class="description">(<?php _e("required", "zingaya"); ?>)</span>:</label>
        </th>
	<td><input name="callme_number" value="<?php echo (isset($widget_data->callme_numbers[0]->callme_number))?$widget_data->callme_numbers[0]->callme_number:""; ?>" class="regular-text">
        <br /><span class="description"><?php _e("For example, for US 18005553723"); ?></span>
        </td>
	</tr>
<?php if ( in_array("working_hours", $planinfo->features) ) { ?>
    <tr valign="top">
        <th scope="row">
            <label for="working_hours"><?php _e("Working hours", "zingaya"); ?>:</label>
        </th>
        <td>
            
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Sunday" class="alignright"><?php _e("Sunday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Sunday" class="work_hours" type="text" name="hour[SUN]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->SUN[0]))?$widget_data->callme_numbers[0]->hours->SUN[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->SUN[1]))?$widget_data->callme_numbers[0]->hours->SUN[1]*60:1140; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Monday" class="alignright"><?php _e("Monday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Monday" class="work_hours" type="text" name="hour[MON]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->MON[0]))?$widget_data->callme_numbers[0]->hours->MON[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->MON[1]))?$widget_data->callme_numbers[0]->hours->MON[1]*60:1140; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Tuesday" class="alignright"><?php _e("Tuesday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Tuesday" class="work_hours" type="text" name="hour[TUE]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->TUE[0]))?$widget_data->callme_numbers[0]->hours->TUE[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->TUE[1]))?$widget_data->callme_numbers[0]->hours->TUE[1]*60:1140; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Wednesday" class="alignright"><?php _e("Wednesday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Wednesday" class="work_hours" type="text" name="hour[WED]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->WED[0]))?$widget_data->callme_numbers[0]->hours->WED[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->WED[1]))?$widget_data->callme_numbers[0]->hours->WED[1]*60:1140; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Thursday" class="alignright"><?php _e("Thursday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Thursday" class="work_hours" type="text" name="hour[THU]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->THU[0]))?$widget_data->callme_numbers[0]->hours->THU[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->THU[1]))?$widget_data->callme_numbers[0]->hours->THU[1]*60:1140; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Friday" class="alignright"><?php _e("Friday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Friday" class="work_hours" type="text" name="hour[FRI]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->FRI[0]))?$widget_data->callme_numbers[0]->hours->FRI[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->FRI[1]))?$widget_data->callme_numbers[0]->hours->FRI[1]*60:1140; ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="Saturday" class="alignright"><?php _e("Saturday", "zingaya"); ?>:</label>
        </th>
        <td>
            <input id="Saturday" class="work_hours" type="text" name="hour[SAT]" value="<?php echo (isset($widget_data->callme_numbers[0]->hours->SAT[0]))?$widget_data->callme_numbers[0]->hours->SAT[0]*60:540; ?>;<?php echo (isset($widget_data->callme_numbers[0]->hours->SAT[1]))?$widget_data->callme_numbers[0]->hours->SAT[1]*60:1140; ?>" />
        </td>
    </tr>
<?php } ?>
<?php if ( in_array("voicemail", $planinfo->features) ) { ?>
    <tr valign="top">
        <th scope="row">
            <label for="voicemail"><?php _e("Voicemail", "zingaya"); ?>:</label>
        </th>
        <td>
            <input type="checkbox" name="voicemail" id="voicemail" <?php echo ((isset($widget_data->voicemail) && $widget_data->voicemail)?" checked":""); ?>>
            <span class="description"><?php _e("Voicemail prompt will be used when somebody calls you while non-working hours", "zingaya"); ?></span>
        </td>
    </tr>
<?php } ?>
<?php if ( in_array("call_recording", $planinfo->features) ) { ?>
    <tr valign="top">
        <th scope="row">
            <label for="call_recording"><?php _e("Call recording", "zingaya"); ?>:</label>
        </th>
        <td>
            <input type="checkbox" name="call_recording" id="call_recording"<?php echo ((isset($widget_data->record_calls) && $widget_data->record_calls)?" checked":""); ?>>
            <span class="description"><?php _e("Enable/disable call recording feature for the widget", "zingaya"); ?></span>
        </td>
    </tr>
<?php } ?>
<?php if ( in_array("google_analytics", $planinfo->features) ) { ?>
    <tr valign="top">
        <th scope="row">
            <label for="google_analytics"><?php _e("Google analytics", "zingaya"); ?>:</label>
        </th>
        <td>
            <input class="regular-text" name="google_analytics" value="<?php echo (isset($widget_data->google_analytics))?$widget_data->google_analytics:""; ?>" id="google_analytics">
            <br />
            <span class="description"><?php _e("Your Google Analytics ID in the following format UA-XXXX-YY, <br />you can get it in your Google Analytics control panel. <br />Just leave it blank if you don't want to use integration with Google Analytics", "zingaya"); ?></span>
        </td>
    </tr>
<?php } ?>
    <tr valign="top">
        <th scope="row">
            <label for="dtmf"><?php _e("DTMF Keypad", "zingaya"); ?>:</label>
        </th>
        <td>
            <input type="checkbox" name="dtmf" id="dtmf"<?php echo ((isset($graphics["dtmf_keypad"]) && $graphics["dtmf_keypad"]=="true")?" checked":""); ?>>
            <span class="description"><?php _e("Allows to enter extension numbers in connected state", "zingaya"); ?></span>
        </td>
    </tr>
</table>
<input type="hidden" name="action" value="edit_widget">
<input type="hidden" name="widget_id" value="<?php echo $widget_data->widget_id; ?>">
<input type="hidden" name="callme_number_id" value="<?php echo (isset($widget_data->callme_numbers) && isset($widget_data->callme_numbers[0]->callme_number_id))?$widget_data->callme_numbers[0]->callme_number_id:0; ?>">
<?php submit_button(__('Save', 'zingaya')); ?>
</form>
</div>
<script>get_sliders();</script>
<?php 
global $action_redirect;
if ( $action_redirect) { ?>
<script>
//document.location.href = "<?php echo admin_url('admin.php?page=zingaya/zingaya-admin.php&action=widget_designer&tab=widgets&widget='.$_GET["widget"]); ?>";
</script>
<?php } ?>