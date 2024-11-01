<?php
    if ( isset ( $_GET['tab'] ) ) {
        $tab = $_GET['tab'];
    } else $tab = 'profile'; 
    
    if ( !isset($_GET["noheader"]) || $_GET["noheader"] !== "true" ) {
        echo '<div class="wrap">';
        echo '<h2>' . __("Zingaya Control Panel", "zingaya") . '</h2>';
        $planinfo = get_tariff_data();
        zingaya_admin_tabs($tab);    
    }
    global $AuthURL;
    $request = ZINGAYA_API_URL . '?cmd=GetAuthURL&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
            '&user_id=' . urlencode(get_option('zingaya_user_id', ''));

    $result = wp_remote_get( $request, array("sslverify" => false) );
    if ( is_wp_error( $result ) ) {
        br_trigger_error( $result->get_error_message(), E_USER_ERROR);
    } else {
        $obj = json_decode($result["body"]);
        if (isset($obj->error)) admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
        else {
            $AuthURL = $obj->result;
        }
    }
        
    switch ( $tab ) :
        case 'profile' :
        	zingaya_profile();
        	if (!$planinfo->custom && !isset($_POST['action'])) {
        	?>
        	<div id="planinfo" style="background: white; display: inline-block; padding: 15px; float: left; margin: 15px;">
        		<h3><?php _e("Current Plan", "zingaya"); ?>: <?php echo $planinfo->tariff_name . ($planinfo->trial?' (trial)':'');  ?></h3>
        		<p>
                <?php 
                    switch ($planinfo->tariff_type) {
                        case 'pstn':
                            echo "<p>".$planinfo->minutes_left . (($planinfo->minutes_left>1 || $planinfo->minutes_left==0)?' '.__('minutes left', 'zingaya'):' ' . __('minute left', 'zingaya')); ?> <?php _e("until", "zingaya"); ?> <?php echo $planinfo->expiration_date . "</p>";                            
                            break;
                        
                        case 'pay as you go':
                            _e('Payment mode - "Pay as you go"', 'zingaya');
                            break;

                        case 'voip':
                            break;
                            echo $planinfo->calls_left . (($planinfo->calls_left>1 || $planinfo->calls_left==0)?' '. __('calls left', 'zingaya'):' ' . __('call left', 'zingaya')); ?> <?php _e("until", "zingaya"); ?> <?php echo $planinfo->expiration_date; 
                        default:
                            # code...
                            break;
                    }

                    echo "<p>" . __("Number of operators", "zingaya") . " ".$planinfo->num_lines."</p>"; 
                    if (is_array($planinfo->features)) {
                        echo "<h4>" . __("Features", "zingaya") . "</h4>";
                        foreach ($planinfo->features as $key => $value) {
                            switch ($value) {
                                case 'working_hours':
                                    echo __("Working hours", "zingaya") . "<br/>";
                                    break;
                                case 'voicemail':
                                    echo __("Voicemail", "zingaya") . "<br/>";
                                    break;
                                case 'call_recording':
                                    echo __("Call recording", "zingaya") ."<br/>";
                                    break;
                                case 'zingaya_analytics':
                                    echo __("Analytics", "zingaya") . "<br/>";
                                    break;
                                case 'google_analytics':
                                    echo __("Google Analytics integration", "zingaya") . "<br/>";
                                    break;
                                case 'polls':
                                    echo __("Surveys", "zingaya") . "<br/>";
                                    break;
                                case 'flexible_routing':
                                    echo __("Call to multiple numbers", "zingaya") . "<br/>";
                                    break;
                                case 'geo_targeting':
                                    echo __("Country control", "zingaya") . "<br/>";
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                ?>
                </p>
        		
        		<?php 
        			if ($planinfo->trial && !$planinfo->custom && $planinfo->tariff_type == 'pstn') echo "<a href=\"".$AuthURL."\" class=\"button-primary\" target=\"_blank\">" . __('Upgrade', 'zingaya') . "</a>";
        			else if (!$planinfo->custom && $planinfo->tariff_type == 'pstn') echo "<a href=\"".$AuthURL."\" class=\"button-primary\" target=\"_blank\">" . __('Change plan', 'zingaya') . "</a>";
        		 ?>

        	</div>
        	<?php
        	}
        break;
        case 'widgets' :
            zingaya_widgets();
        break;
        case 'callhistory' :
            zingaya_callhistory();
        break;
        case 'features' :
            zingaya_features();
        break;
        case 'accounting' :
            zingaya_accounting();
        break;
        case 'billing':
            zingaya_billing();
        break;
        case 'help':
            zingaya_help();
        break;
    endswitch;


?>
</div>