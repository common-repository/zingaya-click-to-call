<?php
/*
    Plugin Name: Zingaya Click-to-call
    Plugin URI: http://wordpress.org/extend/plugins/zingaya/
    Description: Let your website visitors call you. Without a phone. Zingaya enables voice calls through any computer, right from a webpage.
    Author: Zingaya, Inc.
    Version: 1.0
    Author URI: http://zingaya.com/
    Text Domain: zingaya
    Domain Path: /languages
 
    Copyright 2014  Ivaneychik Dmitry  (email : ivaneychik@zingaya.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('ZINGAYA_API_URL', 'https://api.zingaya.com/ZingayaAPI2/');

/*function zingaya_free_redirect($url){
    wp_redirect($url);
    exit;
}
//add_action( 'admin_init', 'zingaya_free_redirect' );
add_action( 'admin_init', 'zingaya_free_redirect' );*/

function zingaya_load_textdomain() {
  load_plugin_textdomain( 'zingaya', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'zingaya_load_textdomain' );

$zingaya_countries = array(
    "RU" => __("Russia (area code 495, 499, 812)", "zingaya"),
    "RU2" => __("Russia (other landline numbers)", "zingaya"),
    "US" => __("United States (landline & mobile numbers)", "zingaya"),
    "AU" => __("Australia (landline numbers)", "zingaya"),
    "CA" => __("Canada (landline & mobile numbers)", "zingaya"),
    "FR" => __("France (landline numbers)", "zingaya"),
    "DE" => __("Germany (landline numbers)", "zingaya"),
    "IL" => __("Israel (landline numbers)", "zingaya"),
    "IT" => __("Italy (landline numbers)", "zingaya"),
    "JP" => __("Japan (landline numbers)", "zingaya"),
    "NL" => __("Netherlands (landline numbers)", "zingaya"),
    "KZ" => __("Kazakhstan (landline numbers)", "zingaya"),
    "KR" => __("Republic of Korea (landline numbers)", "zingaya"),
    "ES" => __("Spain (landline numbers)", "zingaya"),
    "TR" => __("Turkey (landline numbers)", "zingaya"),
    "GB" => __("United Kingdom (landline numbers)", "zingaya"),
    "UA" => __("Ukraine (landline numbers)", "zingaya"),

);

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$planinfo = null;
$userinfo = null;
$widget_data = null;
$zpresets = null;
$zingaya_redirect = false;
$AuthURL = "";
$zingaya_error = "";
$action_redirect = false;
$timezones =  array(
        'Pacific/Honolulu' => __('(GMT-10:00) Hawaii', 'zingaya'), 
        'America/Anchorage' => __('(GMT-09:00) Alaska', 'zingaya'), 
        'America/Los_Angeles' => __('(GMT-08:00) Pacific Time (US &amp; Canada)', 'zingaya'), 
        'America/Phoenix' => __('(GMT-07:00) Arizona', 'zingaya'), 
        'America/Denver' => __('(GMT-07:00) Mountain Time (US &amp; Canada)', 'zingaya'), 
        'America/Chicago' => __('(GMT-06:00) Central Time (US &amp; Canada)', 'zingaya'), 
        'America/New_York' => __('(GMT-05:00) Eastern Time (US &amp; Canada)', 'zingaya'), 
        'America/Indiana/Indianapolis' => __('(GMT-05:00) Indiana (East)', 'zingaya'), 
        'Etc/GMT-11' => __('(GMT-11:00) International Date Line West', 'zingaya'), 
        'Pacific/Midway' => __('(GMT-11:00) Midway Island', 'zingaya'), 
        'Pacific/Apia' => __('(GMT-11:00) Samoa', 'zingaya'), 
        'America/Tijuana' => __('(GMT-08:00) Tijuana', 'zingaya'), 
        'America/Chihuahua' => __('(GMT-07:00) Chihuahua', 'zingaya'), 
        'America/Mazatlan' => __('(GMT-07:00) Mazatlan', 'zingaya'), 
        'America/Guatemala' => __('(GMT-06:00) Central America', 'zingaya'), 
        'America/Mexico_City' => __('(GMT-06:00) Mexico City', 'zingaya'), 
        'America/Monterrey' => __('(GMT-06:00) Monterrey', 'zingaya'), 
        'America/Regina' => __('(GMT-06:00) Saskatchewan', 'zingaya'), 
        'America/Bogota' => __('(GMT-05:00) Bogota', 'zingaya'), 
        'America/Lima' => __('(GMT-05:00) Lima', 'zingaya'), 
        'Etc/GMT-5' => __('(GMT-05:00) Quito', 'zingaya'), 
        'America/Caracas' => __('(GMT-04:30) Caracas', 'zingaya'), 
        'America/Toronto' => __('(GMT-04:00) Atlantic Time (Canada)', 'zingaya'), 
        'America/La_Paz' => __('(GMT-04:00) La Paz', 'zingaya'), 
        'America/Santiago' => __('(GMT-04:00) Santiago', 'zingaya'), 
        'America/St_Johns' => __('(GMT-03:30) Newfoundland', 'zingaya'), 
        'America/Sao_Paulo' => __('(GMT-03:00) Brasilia', 'zingaya'), 
        'America/Argentina/Buenos_Aires' => __('(GMT-03:00) Buenos Aires', 'zingaya'), 
        'Etc/GMT-4' => __('(GMT-04:00) Georgetown', 'zingaya'), 
        'Etc/GMT-3' => __('(GMT-03:00) Greenland', 'zingaya'), 
        'Atlantic/South_Georgia' => __('(GMT-02:00) Mid-Atlantic', 'zingaya'), 
        'Atlantic/Azores' => __('(GMT-01:00) Azores', 'zingaya'), 
        'Atlantic/Cape_Verde' => __('(GMT-01:00) Cape Verde Is.', 'zingaya'), 
        'Africa/Casablanca' => __('(GMT) Casablanca', 'zingaya'), 
        'Europe/Dublin' => __('(GMT) Dublin', 'zingaya'), 
        'Europe/Lisbon' => __('(GMT) Lisbon', 'zingaya'), 
        'Europe/London' => __('(GMT) London', 'zingaya'), 
        'Africa/Monrovia' => __('(GMT) Monrovia', 'zingaya'), 
        'Europe/Amsterdam' => __('(GMT+01:00) Amsterdam', 'zingaya'), 
        'Europe/Belgrade' => __('(GMT+01:00) Belgrade', 'zingaya'), 
        'Europe/Berlin' => __('(GMT+01:00) Berlin',  'zingaya'), 
        'Europe/Zurich' => __('(GMT+01:00) Bern',  'zingaya'), 
        'Europe/Bratislava' => __('(GMT+01:00) Bratislava',  'zingaya'), 
        'Europe/Brussels' => __('(GMT+01:00) Brussels',  'zingaya'), 
        'Europe/Budapest' => __('(GMT+01:00) Budapest',  'zingaya'), 
        'Europe/Copenhagen' => __('(GMT+01:00) Copenhagen',  'zingaya'), 
        'Europe/Ljubljana' => __('(GMT+01:00) Ljubljana',  'zingaya'), 
        'Europe/Madrid' => __('(GMT+01:00) Madrid',  'zingaya'), 
        'Europe/Paris' => __('(GMT+01:00) Paris',  'zingaya'), 
        'Europe/Prague' => __('(GMT+01:00) Prague',  'zingaya'), 
        'Europe/Rome' => __('(GMT+01:00) Rome',  'zingaya'), 
        'Europe/Sarajevo' => __('(GMT+01:00) Sarajevo',  'zingaya'), 
        'Europe/Skopje' => __('(GMT+01:00) Skopje',  'zingaya'), 
        'Europe/Stockholm' => __('(GMT+01:00) Stockholm',  'zingaya'), 
        'Europe/Vienna' => __('(GMT+01:00) Vienna',  'zingaya'), 
        'Europe/Warsaw' => __('(GMT+01:00) Warsaw',  'zingaya'), 
        'Africa/Kinshasa' => __('(GMT+01:00) West Central Africa',  'zingaya'), 
        'Europe/Zagreb' => __('(GMT+01:00) Zagreb',  'zingaya'), 
        'Europe/Athens' => __('(GMT+02:00) Athens',  'zingaya'), 
        'Europe/Bucharest' => __('(GMT+02:00) Bucharest',  'zingaya'), 
        'Africa/Cairo' => __('(GMT+02:00) Cairo',  'zingaya'), 
        'Africa/Harare' => __('(GMT+02:00) Harare',  'zingaya'), 
        'Europe/Helsinki' => __('(GMT+02:00) Helsinki',  'zingaya'), 
        'Europe/Istanbul' => __('(GMT+02:00) Istanbul',  'zingaya'), 
        'Asia/Jerusalem' => __('(GMT+02:00) Jerusalem',  'zingaya'), 
        'Europe/Kiev' => __('(GMT+02:00) Kyiv',  'zingaya'), 
        'Europe/Minsk' => __('(GMT+02:00) Minsk',  'zingaya'), 
        'Etc/GMT+2' => __('(GMT+02:00) Pretoria',  'zingaya'), 
        'Europe/Riga' => __('(GMT+02:00) Riga',  'zingaya'), 
        'Europe/Sofia' => __('(GMT+02:00) Sofia',  'zingaya'), 
        'Europe/Tallinn' => __('(GMT+02:00) Tallinn',  'zingaya'), 
        'Europe/Vilnius' => __('(GMT+02:00) Vilnius',  'zingaya'), 
        'Asia/Baghdad' => __('(GMT+03:00) Baghdad',  'zingaya'), 
        'Asia/Kuwait' => __('(GMT+03:00) Kuwait',  'zingaya'), 
        'Europe/Moscow' => __('(GMT+04:00) Moscow',  'zingaya'), 
        'Africa/Nairobi' => __('(GMT+03:00) Nairobi',  'zingaya'), 
        'Asia/Riyadh' => __('(GMT+03:00) Riyadh',   'zingaya'), 
        'Europe/Volgograd' => __('(GMT+03:00) Volgograd',  'zingaya'), 
        'Asia/Tehran' => __('(GMT+03:30) Tehran',  'zingaya'), 
        'Etc/GMT+4' => __('(GMT+04:00) Abu Dhabi',  'zingaya'), 
        'Asia/Baku' => __('(GMT+04:00) Baku',  'zingaya'), 
        'Asia/Muscat' => __('(GMT+04:00) Muscat',  'zingaya'), 
        'Asia/Tbilisi' => __('(GMT+04:00) Tbilisi',  'zingaya'), 
        'Asia/Yerevan' => __('(GMT+04:00) Yerevan',  'zingaya'), 
        'Asia/Kabul' => __('(GMT+04:30) Kabul',  'zingaya'), 
        'Asia/Yekaterinburg' => __('(GMT+06:00) Ekaterinburg',  'zingaya'), 
        'Etc/GMT+5' => __('(GMT+05:00) Islamabad',  'zingaya'), 
        'Asia/Karachi' => __('(GMT+05:00) Karachi',  'zingaya'), 
        'Asia/Tashkent' => __('(GMT+05:00) Tashkent',  'zingaya'), 
        'Asia/Colombo' => __('(GMT+05:30) Chennai',  'zingaya'), 
        'Asia/Kolkata' => __('(GMT+05:30) New Delhi',  'zingaya'), 
        'Asia/Kathmandu' => __('(GMT+05:45) Kathmandu',   'zingaya'), 
        'Asia/Almaty' => __('(GMT+06:00) Astana',  'zingaya'), 
        'Asia/Dhaka' => __('(GMT+06:00) Dhaka',  'zingaya'), 
        'Asia/Novosibirsk' => __('(GMT+07:00) Novosibirsk',  'zingaya'), 
        'Asia/Rangoon' => __('(GMT+06:30) Rangoon',  'zingaya'), 
        'Asia/Bangkok' => __('(GMT+07:00) Bangkok',  'zingaya'), 
        'Est/GMT+7' => __('(GMT+07:00) Hanoi',  'zingaya'), 
        'Asia/Jakarta' => __('(GMT+07:00) Jakarta',  'zingaya'), 
        'Asia/Krasnoyarsk' => __('(GMT+08:00) Krasnoyarsk',  'zingaya'), 
        'Etc/GMT+8' => __('(GMT+08:00) Beijing', 'zingaya'), 
        'Asia/Hong_Kong' => __('(GMT+08:00) Hong Kong',  'zingaya'), 
        'Asia/Irkutsk' => __('(GMT+09:00) Irkutsk',  'zingaya'), 
        'Asia/Kuala_Lumpur' => __('(GMT+08:00) Kuala Lumpur', 'zingaya'),  
        'Australia/Perth' => __('(GMT+08:00) Perth',  'zingaya'), 
        'Asia/Singapore' => __('(GMT+08:00) Singapore',  'zingaya'), 
        'Asia/Taipei' => __('(GMT+08:00) Taipei',  'zingaya'), 
        'Asia/Ulaanbaatar' => __('(GMT+08:00) Ulan Bator',  'zingaya'), 
        'Asia/Urumqi' => __('(GMT+08:00) Urumqi',  'zingaya'), 
        'Asia/Seoul' => __('(GMT+09:00) Seoul',  'zingaya'), 
        'Asia/Tokyo' => __('(GMT+09:00) Tokyo',  'zingaya'), 
        'Asia/Yakutsk' => __('(GMT+10:00) Yakutsk',  'zingaya'), 
        'Australia/Adelaide' => __('(GMT+09:30) Adelaide',  'zingaya'), 
        'Australia/Darwin' => __('(GMT+09:30) Darwin',  'zingaya'), 
        'Australia/Brisbane' => __('(GMT+10:00) Brisbane',  'zingaya'), 
        'Pacific/Guam' => __('(GMT+10:00) Guam',  'zingaya'), 
        'Australia/Hobart' => __('(GMT+10:00) Hobart',  'zingaya'), 
        'Australia/Melbourne' => __('(GMT+10:00) Melbourne',  'zingaya'), 
        'Pacific/Port_Moresby' => __('(GMT+10:00) Port Moresby',  'zingaya'), 
        'Australia/Sydney' => __('(GMT+10:00) Sydney',  'zingaya'), 
        'Asia/Vladivostok' => __('(GMT+11:00) Vladivostok',  'zingaya'), 
        'Asia/Magadan' => __('(GMT+11:00) Magadan',  'zingaya'), 
        'Pacific/Noumea' => __('(GMT+11:00) New Caledonia',  'zingaya'), 
        'Etc/GMT+11' => __('(GMT+11:00) Solomon Is.',  'zingaya'), 
        'Pacific/Fiji' => __('(GMT+12:00) Fiji',  'zingaya'), 
        'Asia/Kamchatka' => __('(GMT+12:00) Kamchatka',  'zingaya'), 
        'Etc/GMT+12' => __('(GMT+12:00) Marshall Is.',  'zingaya'), 
        'Pacific/Auckland' => __('(GMT+12:00) Wellington',  'zingaya'), 
        'Pacific/Tongatapu' => __('(GMT+13:00) Nuku\'alofa',  'zingaya')
);

require_once('zingaya-classes.php');

function zingaya_redirects(){
    global $zingaya_error;
    
    if ( isset($_GET["action"])) {
        if ( $_GET["action"] === "authorize" || $_GET["action"] === "login" || $_GET["action"] === "signup" )  {
            //echo get_option('zingaya_api_key'); exit;
            if ( get_option('zingaya_api_key', '') != '' ) {
                wp_redirect(admin_url('admin.php?page=zingaya/zingaya-admin.php&action=profile'));
                exit;
            } else {
                
            }
        } else if ( $_GET["action"] === "logout" ) {
            delete_option('user_already_exists', '');
            delete_option('zingaya_api_key', '');
            delete_option('zingaya_user_name', '');
            delete_option('zingaya_user_email', '');
            delete_option('zingaya_user_id', '');
            
            wp_redirect(admin_url('admin.php?page=zingaya/zingaya-admin.php&action=authorize'));
            exit;
        }
    }
    
  
        if( isset($_POST[ "action" ]) ) {
            if ($_POST[ "action" ] == "authorize") {

                $request = ZINGAYA_API_URL . '?cmd=Logon&user_name=' . urlencode( $_POST[ "email" ] ) . '&password=' . urlencode( $_POST[ "password" ] );

                $result = wp_remote_get( $request, array("sslverify" => false) );

                if ( is_wp_error( $result ) ) {
                    br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                } else {
                    $obj = json_decode($result["body"]);
                    if (!isset($obj->error)) {
                        // get APIKEY and store it in database
                        if (!isset($obj->api_key)) $zingaya_error = admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b> ' . __('APIKEY doesn\'t exist for specified account. Contact Zingaya admin to resolve the issue', 'zingaya'), 'error', true);
                        else {
                            add_option('zingaya_user_name', $_POST["email"]);
                            add_option('zingaya_api_key', $obj->api_key);

                            $request = ZINGAYA_API_URL . 
                                '?cmd=GetCurrentUserID&user_name=' . urlencode( $_POST[ "email" ] ) . 
                                '&api_key=' . urlencode( $obj->api_key );
                            
                            $result = wp_remote_get( $request, array("sslverify" => false) );
                            if ( is_wp_error( $result ) ) {
                                br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                            } else {
                                $obj = json_decode($result["body"]);
                                add_option('zingaya_user_id', $obj->result);
                                wp_redirect(admin_url("admin.php?page=zingaya/zingaya-admin.php&tab=profile"));
                                exit;
                            }	                    
                        }
                    } else {
                        // show error message
                        $zingaya_error = admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error', true);
                    }
                }
            }

            if ($_POST[ "action" ] == "signup") {
                $_POST["phone"] = str_replace("+", "", $_POST["phone"]);
                if ( empty($_POST["phone"]) ) {
                    $zingaya_error = admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>134 ' . __('Phone number is required', 'zingaya'), 'error', true);
                } else {
                    $request = ZINGAYA_API_URL . '?cmd=GetTrialTariffs&country=' . $_POST['country'];
                    $result = wp_remote_get( $request, array("sslverify" => false) );
                    if ( is_wp_error( $result ) ) {
                        br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                    } else {
                        $obj = json_decode($result["body"]);
                        $register_tariff = null;
                        if ( count($obj->result) > 0 ) {
                            foreach ( $obj->result as $tariff ){
                                if ( $tariff->region_code == $_POST['country'] && strpos($tariff->tariff_name, "Medium") !== FALSE ) $register_tariff = $tariff;
                            }
                        }

                        if ( is_null($register_tariff) ) {
                            $zingaya_error = admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b> ' . __('Not find tariff for selected country', 'zingaya'), 'error', true);
                        } else {
                            global $timezones;
                            switch($_POST['country']){
                                case 'RU':
                                case 'RU2':
                                case 'RU3':
                                    $location = "Europe/Moscow";
                                    break;
                                case 'US':
                                    $location = "America/New_York";
                                    break;
                                case 'DE':
                                    $location = "Europe/Berlin";
                                    break;
                                case 'GB':
                                    $location = "Europe/London";
                                    break;
                                case 'FR':
                                    $location = "Europe/Paris";
                                    break;
                                case 'IL':
                                    $location = "Asia/Jerusalem";
                                    break;
                                case 'IT':
                                    $location = "Europe/Rome";
                                    break;
                                case 'JP':
                                    $location = "Asia/Tokyo";
                                    break;
                                case 'KR':
                                    $location = "Asia/Seoul";
                                    break;
                                case 'ES':
                                    $location = "Europe/Madrid";
                                    break;
                                case 'TR':
                                    $location = "Europe/Istanbul";
                                    break;
                                case 'UA':
                                    $location = "Europe/Kiev";
                                    break;
                                default:
                                    $location = "UTC/GMT";
                                    break;
                            }
                            
                            
                            $request = 'https://zingaya.com/wp/ajax.php?cmd=AddChildUser' . 
                                '&child_user_name=' . urlencode($_POST['email']) . 
                                '&child_password=' . urlencode($_POST['password']) . 
                                '&first_name=' . urlencode($_POST['first_name']) . 
                                '&last_name=' . urlencode($_POST['last_name']) . 
                                '&email=' . urlencode($_POST['email']) . 
                                '&tariff_id=' . $register_tariff->tariff_id . 
                                '&callme_number=' . urlencode($_POST['phone']) . 
                                '&api_enabled=true' . 
                                '&location=' . urlencode($location);

                            $result = wp_remote_get( $request, array("sslverify" => false) );
                            if ( is_wp_error( $result ) ) {
                                br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                            } else {
                                $obj = json_decode($result["body"]);
                                if (!isset($obj->error)) {
                                    update_option('zingaya_api_key', $obj->api_key);
                                    update_option('zingaya_user_name', $_POST['email']);
                                    update_option('zingaya_user_id', $obj->user_id);
                                    unset($_POST);
                                    unset($_GET['action']);

                                } else {
                                    // show error message
                                    $zingaya_error = admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error', true);
                                }
                            }
                        }
                    }
                }
            }		    

        }		

}
add_action( 'admin_init', 'zingaya_redirects' );

function my_plugin_admin_scripts() {
    wp_enqueue_style( 'my-plugin-style-slider1' );
    wp_enqueue_style( 'my-plugin-style-slider2' );
    wp_enqueue_script( 'my-plugin-script1' );
    wp_enqueue_script( 'my-plugin-script2' );
    wp_enqueue_script( 'my-plugin-script3' );
    wp_enqueue_script( 'my-plugin-script4' );
    wp_enqueue_script( 'my-plugin-script5' );
    wp_enqueue_script( 'my-plugin-script6' );
    wp_enqueue_script( 'my-plugin-script7' );
}

function zingaya_admin_tabs( $current = 'profile' ) {

	global $planinfo;
	global $selected_feature;
        global $selected_billing;

    $tabs = array( 
        'profile' => __('Profile', 'zingaya'), 
    	'widgets' => __('Widgets', 'zingaya'), 
    	'callhistory' => __('Call history', 'zingaya'),
    	'features' => __('Features', 'zingaya'),
    	'billing' => __('Billing', 'zingaya'),
        'help'  => __('Help', 'zingaya')
    );

    $links = array();
    foreach( $tabs as $tab => $name ) :
        if ( $tab == $current ) :
            $links[] = "<a class='nav-tab nav-tab-active' href='?page=zingaya/zingaya-admin.php&tab=$tab'>$name</a>";
        else :
            $links[] = "<a class='nav-tab' href='?page=zingaya/zingaya-admin.php&tab=$tab'>$name</a>";
        endif;
    endforeach;
    echo '<h2>';
    foreach ( $links as $link )
        echo $link;    

    echo "<span style='font-size: 70%; padding-left: 20px;'>" . __("authorized as", "zingaya") . " " . get_option('zingaya_user_name', '') . "</span> <a href=\"admin.php?page=zingaya/zingaya-admin.php&action=logout&noheader=true\" class=\"button-primary\">" . __("Logout", "zingaya") . "</a>";

    echo '</h2>';

    if ($current == "features") {    	
    	echo '<h3>';
    	if (is_array($planinfo->features)) {

	    	foreach ($planinfo->features as $key => $value) {
	    		if (!isset($_GET['feature']) && !isset($selected_feature)) {
	    			if ($value == 'voicemail' || $value == 'sip_routing' || $value == 'analytics'
	    				|| $value == 'blacklist' || $value == 'notifications') $selected_feature = $value;
	    		}
				else if (isset($_GET['feature'])) $selected_feature = $_GET['feature'];		
	    		switch ($value) {
	    		 	case 'voicemail':
	    		 		echo "<a class='nav-tab" . ($selected_feature==$value?' nav-tab-active':'') . "' href='?page=zingaya/zingaya-admin.php&tab=$current&feature=$value'>" . __("Voicemail", "zingaya") . "</a>";
	    		 		break;

	    		 	case 'sip_routing':
	    		 		echo "<a class='nav-tab" . ($selected_feature==$value?' nav-tab-active':'') . "' href='?page=zingaya/zingaya-admin.php&tab=$current&feature=$value'>" . __("SIP Settings", "zingaya") . "</a>";
	    		 		break;

	    		 	case 'analytics':
	    		 		echo "<a class='nav-tab" . ($selected_feature==$value?' nav-tab-active':'') . "' href='?page=zingaya/zingaya-admin.php&tab=$current&feature=$value'>" . __("Analytics", "zingaya") . "</a>";
	    		 		break;
	    		 	
	    		 	default:
	    		 		# code...
	    		 		break;
	    		 }    		    		 
	    	}

	    }

    	echo "<a class='nav-tab" . ($selected_feature=='blacklist'?' nav-tab-active':'') . "' href='?page=zingaya/zingaya-admin.php&tab=$current&feature=blacklist'>" . __("IP Blacklist", "zingaya") . "</a>";
    	echo "<a class='nav-tab" . ($selected_feature=='notifications'?' nav-tab-active':'') . "' href='?page=zingaya/zingaya-admin.php&tab=$current&feature=notifications'>" . __("Notifications", "zingaya") . "</a>";

    	echo '</h3>';
    }
    else if ($current == "billing") {
        
    }
}

function check_feature($name) {

	global $planinfo;
	if (isset($planinfo->features) && is_array($planinfo->features)) {
		foreach ($planinfo->features as $key => $value) {
			if ($value == $name) return true;
		}
	} 

	return false;
}

function get_tariff_data() {

	global $planinfo;

	$request = ZINGAYA_API_URL . '?cmd=GetAccountInfo&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', ''));	

	$result = wp_remote_get( $request, array("sslverify" => false) );	
	if ( is_wp_error( $result ) ) {
            br_trigger_error( $result->get_error_message(), E_USER_ERROR);
    } else {
        $obj = json_decode($result["body"]);
        if (!isset($obj->error)) {            	
        	$planinfo = $obj->result;  
        	if ($obj->result->frozen) admin_notice_message('<b>' . __('Your account is frozen', 'zingaya') . ', ' . __('please visit', 'zingaya') . ' <a href=\'?page=zingaya/zingaya-admin.php&tab=billing\'>' . __('Billing', 'zingaya') . '</a> ' . __('to resolve the problem', 'zingaya') . '</b>', 'update-nag');    	

        	// Get plan features and merge with planinfo
        	$request = ZINGAYA_API_URL . '?cmd=' . ($planinfo->trial?'GetTrialTariffs':'GetPaidTariffs');        	

			$result = wp_remote_get( $request, array("sslverify" => false) );

                if ( is_wp_error( $result ) ) {
                    br_trigger_error( $result->get_error_message(), E_USER_ERROR);
    		} else {
        		$obj = json_decode($result["body"]);
        		if (!isset($obj->error)) { 
        			// searching for our plan info
        			foreach ($obj->result as $key => $value) {
        				if ($planinfo->tariff_id == $value->tariff_id) {
        					$planinfo->features = $value->features;
        					$planinfo->num_lines = $value->num_lines;
        					break;
        				}
        			}
        		} else {
        			admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
        		}
        	}	

        	return $planinfo;
        } else {
        	admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
        }
    }

}

function zingaya_admin_ui() {
        global $zingaya_error;
        if ( !empty($zingaya_error) ) echo $zingaya_error;
        
        if ( get_option('zingaya_api_key', '') != '' ) include_once(plugin_dir_path( __FILE__ ) . '/zingaya-cp.php');
        else include_once(plugin_dir_path( __FILE__ ) . '/zingaya-admin.php');
        
}

function admin_notice_message($msg, $class='updated', $r=false){    
   if ( !isset($r) || !$r ) echo '<div class="'.$class.'"><p>'.$msg.'</p></div>';
   else return '<div class="'.$class.'"><p>'.$msg.'</p></div>';
}

function zingaya_plugin_menu() {
        $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKCSB3aWR0aD0iNjRweCIgaGVpZ2h0PSI2NHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDY0IDY0IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNTcuODU0LDE5LjgzM2MzLTcsNi0xMi42MjUsNi0xMi42MjVsLTguMTI1LTYuMzc1YzAsMC0yLjYyNSwwLjE4OC0yLjkzNCwwLjIwOAoJCUM0My42OSwxNC4yOTEsNDMuODQ1LDMwLjI0Miw0My44OTIsMzJjLTAuMDQ3LDEuNzU4LTAuMjAxLDE3LjcwOSw4LjkwNCwzMC45NTljMC4zMDksMC4wMjEsMi45MzQsMC4yMDgsMi45MzQsMC4yMDhsOC4xMjUtNi4zNzUKCQljMCwwLTMtNS42MjUtNi0xMi42MjVjLTMuMTI1LDAuNS01Ljk2NSwwLjU1OC01Ljk2NSwwLjU1OFM1MC4zMjIsNDAuNzcsNTAuMjMxLDMyYzAuMDkxLTguNzcsMS42NTgtMTIuNzI1LDEuNjU4LTEyLjcyNQoJCVM1NC43MjksMTkuMzMzLDU3Ljg1NCwxOS44MzN6Ii8+Cgk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMzQuMzEyLDE3SDIuNjQ2Yy0xLjM4MSwwLTIuNSwxLjExOS0yLjUsMi41czEuMTE5LDIuNSwyLjUsMi41aDMxLjY2NmMxLjM4MSwwLDIuNS0xLjExOSwyLjUtMi41CgkJUzM1LjY5MiwxNywzNC4zMTIsMTd6Ii8+Cgk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMzQuMzEyLDI4LjY2N0gxMi45NzljLTEuMzgxLDAtMi41LDEuMTE5LTIuNSwyLjVzMS4xMTksMi41LDIuNSwyLjVoMjEuMzMzYzEuMzgxLDAsMi41LTEuMTE5LDIuNS0yLjUKCQlTMzUuNjkyLDI4LjY2NywzNC4zMTIsMjguNjY3eiIvPgoJPHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTM0LjMxMiw0MC4zMzRIMjMuNjQ2Yy0xLjM4MSwwLTIuNSwxLjExOS0yLjUsMi41czEuMTE5LDIuNSwyLjUsMi41aDEwLjY2NmMxLjM4MSwwLDIuNS0xLjExOSwyLjUtMi41CgkJUzM1LjY5Miw0MC4zMzQsMzQuMzEyLDQwLjMzNHoiLz4KPC9nPgo8L3N2Zz4=';
        if ( floatval(get_bloginfo("version")) < 3.8 ) $icon = plugins_url('/images/logo_2.png', __FILE__);
        
	$page_hook_suffix = add_menu_page( __('Zingaya Settings', 'zingaya'), __('Zingaya', 'zingaya'), 'manage_options', 'zingaya/zingaya-admin.php', 'zingaya_admin_ui', $icon );
	add_action('admin_print_scripts-' . $page_hook_suffix, 'my_plugin_admin_scripts');
}
add_action( 'admin_menu', 'zingaya_plugin_menu' );
add_action( 'admin_init', 'zingaya_plugin_redirect' );

function br_trigger_error($message, $errno) {
    if(isset($_GET['action']) && $_GET['action'] == 'error_scrape') {
        echo '<strong>' . $message . '</strong>'; 
        exit; 
    } else {
        trigger_error($message, $errno); 
    } 
}

function zingaya_plugin_redirect($i) {
    global $zingaya_redirect;
    if ($zingaya_redirect) {
        $zingaya_redirect = false;
        wp_redirect("admin.php?page=zingaya/zingaya-admin.php");
    }
}

function zingaya_activation() {
    global $current_user;
    get_currentuserinfo();
}

register_activation_hook(__FILE__, 'zingaya_activation');

function zingaya_deactivation() {
	// Probably do nothing :)
        delete_option('zingaya_active_widget');
        delete_option('zingaya_widget');
	delete_option('user_already_exists');
	delete_option('zingaya_user_email');
	delete_option('zingaya_user_name');
	delete_option('zingaya_user_id');
	delete_option('zingaya_api_key');
}
register_deactivation_hook(__FILE__, 'zingaya_deactivation');

function zingaya_profile() {
    global $timezones;
    
    if (isset($_POST['action'])) {
		if ($_POST['action'] == 'save') {
			$data = $_POST['profile'];
			
			$request = ZINGAYA_API_URL . '?cmd=SetUserInfo&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', ''));		
			
			$request .= "&first_name=" . urlencode($data['first_name']);
			$request .= "&last_name=" . urlencode($data['last_name']);
			$request .= "&email=" . urlencode($data['email']);
			$request .= "&location=" . urlencode($data['timezone']);
			if ($data['company'] != "") $request .= "&company_name=" . urlencode($data['company']);
			if ($data['address'] != "") $request .= "&address=" . urlencode($data['address']);

			$result = wp_remote_get( $request, array("sslverify" => false) );

			if ( is_wp_error( $result ) ) {
				br_trigger_error( $result->get_error_message(), E_USER_ERROR);
			} else {
				$obj = json_decode($result["body"]);
				if (!isset($obj->error)) {
					unset($_POST['action']);
					admin_notice_message(__('Profile was updated successfully', 'zingaya'));
				} else {
					$_POST['action'] = 'edit';
					// show error message
                                    admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
				}
			}

		}
	}

	$request = ZINGAYA_API_URL . '?cmd=GetUsers&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
				'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
				'&user_id=' . urlencode(get_option('zingaya_user_id', ''));		
	$result = wp_remote_get( $request, array("sslverify" => false) );

	if ( is_wp_error( $result ) ) {
		br_trigger_error( $result->get_error_message(), E_USER_ERROR);
	} else {

		$obj = json_decode($result["body"]);
		if (!isset($obj->error)) {
			$profile = array();
			$profile['first_name'] = (isset($obj->result[0]->first_name))?$obj->result[0]->first_name:"";
			$profile['last_name'] = (isset($obj->result[0]->last_name))?$obj->result[0]->last_name:"";
			$profile['timezone']= (isset($obj->result[0]->location))?$obj->result[0]->location:"";
			$profile['email'] = (isset($obj->result[0]->email))?$obj->result[0]->email:"";
			$profile['company'] = (isset($obj->result[0]->company_name))?$obj->result[0]->company_name:"";
			$profile['address'] = (isset($obj->result[0]->address))?$obj->result[0]->address:"";
		} else {
			// show error message
	    	admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
	    	exit;	
		}
	}

	include_once(plugin_dir_path( __FILE__ ) . '/zingaya-profile.php');
}

function zingaya_widgets($process = true) {
    global $action_redirect;
    
    if (isset($_GET['action'])) {
        
        if ($_GET['action'] == 'create') {
            if ( isset($_POST["action"]) && $_POST["action"] == "create" ) {
                if ( empty($_POST['callme_number']) ) {
                    admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>134 ' . __('Phone number is required', 'zingaya'), 'error', false);
                    include_once(plugin_dir_path( __FILE__ ) . '/zingaya-createwidget.php');
                } else {
                    $request = ZINGAYA_API_URL . '?cmd=AddWidget&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                            '&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
                            ((isset($_POST["call_recording"]) && $_POST["call_recording"] == "on")?'&record_calls=true':'&record_calls=false') . 
                            ((isset($_POST["voicemail"]) && $_POST["voicemail"] == "on")?'&voicemail=true':'&voicemail=false') .
                            ((isset($_POST["dtmf"]) && $_POST["dtmf"] == "on")?'&graphics=' . urlencode('dtmf_keypad:true'):'&graphics=' . urlencode('dtmf_keypad:false')) .
                            '&google_analytics=' . urlencode($_REQUEST["google_analytics"]) . 
                            '&widget_name=' . urlencode($_REQUEST["widget_name"]) . 
                            '&callme_number=' . urlencode($_REQUEST["callme_number"]);

                    $result = wp_remote_get( $request, array("sslverify" => false) );
                    if ( is_wp_error( $result ) ) {
                        br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                        include_once(plugin_dir_path( __FILE__ ) . '/zingaya-createwidget.php');
                    } else {
                        $obj = json_decode($result["body"]);
                        if (!isset($obj->error)) {
                            $request = ZINGAYA_API_URL . '?cmd=GetCallmeNumbers&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                                    '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                                    '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                                    '&widget_id=' . $obj->widget_id;

                            $new_widget_id = $obj->widget_id;

                            $result = wp_remote_get( $request, array("sslverify" => false) );
                            $obj = json_decode($result["body"]);

                            $request = ZINGAYA_API_URL . '?cmd=SetWorkingHours&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                                    '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                                    '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                                    '&callme_number_id=' . $obj->result[0]->callme_number_id;

                            foreach( $_POST["hour"] as $key => $value ){
                                list($from, $to) = explode(";", $value);
                                $request .= "&".$key."=".($from/60)."-".($to/60);
                            }
                            $result = wp_remote_get( $request, array("sslverify" => false) );
                            $obj = json_decode($result["body"]);                        
                            admin_notice_message(__('Widget has been successfully added', 'zingaya') . ".");	
                            admin_notice_message("<b>" . __('You can customize look&feel of your widget in ', 'zingaya') . " <a href=\"".admin_url('admin.php?page=zingaya/zingaya-admin.php&action=widget_designer&tab=widgets&widget='.$new_widget_id)."\">" . __("Widget designer", "zingaya") . '</a></b>', 'update-nag');
                            unset($_GET["action"]);
                            zingaya_widgets();
                            include_once(plugin_dir_path( __FILE__ ) . '/zingaya-widgets.php');
                        } else {
                            // show error message
                            admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                            include_once(plugin_dir_path( __FILE__ ) . '/zingaya-createwidget.php');
                        }
                    }
                }
            } else include_once(plugin_dir_path( __FILE__ ) . '/zingaya-createwidget.php');
        } else if ($_GET['action'] == 'edit_widget') {
            global $widget_data;
            
            if ( isset($_POST["action"]) && $_POST["action"] == "edit_widget" ) {
                $request = ZINGAYA_API_URL . '?cmd=SetWidgetInfo&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                        '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                        '&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
                        '&widget_id=' . urlencode($_POST['widget_id']) .
                        ((isset($_POST["call_recording"]) && $_POST["call_recording"] == "on")?'&record_calls=true':"&record_calls=false") . 
                        ((isset($_POST["voicemail"]) && $_POST["voicemail"] == "on")?'&voicemail=true':"&voicemail=false") .
                        ((isset($_POST["dtmf"]) && $_POST["dtmf"] == "on")?'&graphics=' . urlencode('dtmf_keypad:true'):'&graphics=' . urlencode('dtmf_keypad:false')) .
                        '&google_analytics=' . urlencode($_REQUEST["google_analytics"]) . 
                        '&widget_name=' . urlencode($_REQUEST["widget_name"]);
                        //'&callme_number=' . urlencode($_REQUEST["callme_number"]);
                $result = wp_remote_get( $request, array("sslverify" => false) );
                if ( is_wp_error( $result ) ) {
                    br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                } else {
                    $obj = json_decode($result["body"]);
                    if (!isset($obj->error)) {
                        if ( isset($_POST['callme_number_id']) && intval($_POST['callme_number_id']) > 0 ) {
                        $request = ZINGAYA_API_URL . '?cmd=SetCallmeNumber&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                                '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                                '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                                '&callme_number_id=' . $_POST["callme_number_id"] . 
                                '&callme_number=' . urlencode($_POST["callme_number"]);
                        } else {
                            $request = ZINGAYA_API_URL . '?cmd=AddCallmeNumber&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                                '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                                '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                                '&widget_id=' . urlencode($_POST["widget_id"]) . 
                                '&callme_number=' . urlencode($_POST["callme_number"]);
                        }
                        $result = wp_remote_get( $request, array("sslverify" => false) );
                        if ( is_wp_error( $result ) ) {
                            br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                        } else {
                            $obj = json_decode($result["body"]);

                            if (isset($obj->error)) admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                            else {
                                if ( isset($_POST['callme_number_id']) && intval($_POST['callme_number_id']) > 0 ) {
                                    $cmnid = $_POST['callme_number_id'];
                                } else {
                                    $cmnid = $obj->callme_number_id;
                                }
                                $request = ZINGAYA_API_URL . '?cmd=SetWorkingHours&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                                        '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                                        '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                                        '&callme_number_id=' . $cmnid;
                                foreach( $_POST["hour"] as $key => $value ){
                                    list($from, $to) = explode(";", $value);
                                    $request .= "&".$key."=".($from/60)."-".($to/60);
                                }
                                $result = wp_remote_get( $request, array("sslverify" => false) );
                                $obj = json_decode($result["body"]);   
                                admin_notice_message(__('Widget has been successfully updated', 'zingaya') . ".");
                                admin_notice_message("<b>" . __('You can customize look&feel of your widget in ', 'zingaya') . " <a href=\"".admin_url('admin.php?page=zingaya/zingaya-admin.php&action=widget_designer&tab=widgets&widget='.$_GET["widget"])."\">" . __("Widget designer", "zingaya") . '</a></b>', 'update-nag');
                                $action_redirect = true;
                                //wp_redirect(admin_url('admin.php?page=zingaya/zingaya-admin.php&action=widget_designer&tab=widgets&widget='.$_GET["widget"]));
                            }
                        }
                    } else {
                        admin_notice_message('<b>Error:</b>' . $obj->error->code . " " . $obj->error->msg, 'error');
                    }
                }
            } else {
                //print_r($widget_data);
            }
                $request = ZINGAYA_API_URL . '?cmd=GetWidgets&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                        '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                        '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                        '&widget_id=' . $_GET["widget"];
                
                $result = wp_remote_get( $request, array("sslverify" => false) );
                $obj = json_decode($result["body"]);   
                $widget_data = $obj->result[0];
                //wp_redirect_admin_locations()
                
            include_once(plugin_dir_path( __FILE__ ) . '/zingaya-editwidget.php');
        } else if ( $_GET['action'] == 'widget_designer' ) {
            // Построчное чтение файла
            $full_url = plugin_dir_path( __FILE__ ) . 'presets/presets.txt';
            $handle = fopen ($full_url, "r");
            $zpresets = array();

            while (!feof ($handle)) {
                $buffer = fgets($handle);
                    if ( empty($buffer) ) continue;
                $pr = explode(":", $buffer);
                    if ( count($pr) > 0 ) {
                            $i = $pr[0];
                            unset($pr[0]);

                            $zpresets[$i] = json_decode(stripcslashes(implode(":", $pr))); 
                    }
            }
            fclose ($handle);
            $GLOBALS['zpresets'] = $zpresets;
            $request = ZINGAYA_API_URL . '?cmd=GetWidgets&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                    '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                    '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                    '&widget_id=' . $_GET["widget"];

            $result = wp_remote_get( $request, array("sslverify" => false) );
            $obj = json_decode($result["body"]);   
            $GLOBALS['widget_data'] = $obj->result[0];
            include_once(plugin_dir_path( __FILE__ ) . '/zingaya-widgetdesigner.php');
        } else if ( $_GET['action'] == 'delete_widget' ) {
            $request = ZINGAYA_API_URL . '?cmd=DelWidget&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                    '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                    '&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
                    '&widget_id=' . urlencode($_GET['widget']);

            $result = wp_remote_get( $request, array("sslverify" => false) );
            if ( is_wp_error( $result ) ) {
                br_trigger_error( $result->get_error_message(), E_USER_ERROR);
            } else {
                $obj = json_decode($result["body"]);
                if (isset($obj->error)) admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                else {
                    $av = intval(get_option("zingaya_active_widget", 0));
                    if ( $av > 0 && $av == $_GET['widget'] ) delete_option("zingaya_active_widget");
                    delete_option("zingaya_widget_short_" . $_GET['widget']);
                    
                    $options = get_option("zingaya_widget", false);
                    if ( $options ) {
                        $gr = $options['button_graphics'];
                        $gr = explode(";", $gr);
                        $widgetParams = array();
                        if ( count($gr) > 0 ) {
                            foreach( $gr as $g ) {
                                $p = explode(":", $g);
                                if ( count($p) == 2 && $p[0] == "widget_id" && intval($p[1]) == intval($_GET['widget']) ) delete_option ("zingaya_widget");
                            }
                        }
                    }
                    
                    admin_notice_message(__('Widget has been successfully deleted', 'zingaya'));
                }
            }
            
            unset($_GET["action"]);
            zingaya_widgets();
            include_once(plugin_dir_path( __FILE__ ) . '/zingaya-widgets.php');
        }
    } else {
        $widgetsTable = new WidgetsTable();	
        include_once(plugin_dir_path( __FILE__ ) . '/zingaya-widgets.php');
    }
}

add_action('wp_ajax_zingaya_save_button_ajax', 'zingaya_save_button_code');
add_action('wp_ajax_zingaya_active_widget_ajax', 'zingaya_active_widget');

function zingaya_save_button_code(){
    $wt = "button";
    foreach($_REQUEST["params"] as $k => $v){
        if ( $k == "type" && $v == "widget" ) $wt = "widget";
        $bg[] = $k . ":" . $v;
    }
    $w = implode(";", $bg);
    
    $request = ZINGAYA_API_URL . '?cmd=SetWidgetInfo&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
            '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
            '&widget_id=' . $_REQUEST["widget"] . 
            '&button_graphics=' . urlencode($w);

    $result = wp_remote_get( $request, array("sslverify" => false) );    

    if ( $wt == "widget" ) {
        $aw = intval(get_option("zingaya_active_widget"));
        if ( $aw == intval($_REQUEST["widget"]) ) {
            if ( $_REQUEST["widget_active"] == "false" ) delete_option("zingaya_active_widget");
        } else {
            if ( $_REQUEST["widget_active"] == "true" ) update_option("zingaya_active_widget", $_REQUEST["widget"]);
        }
    }
    $options = array(
        "button_graphics" => $w,
        "callme_id" => $_REQUEST["callme_id"]
    );
    
    update_option("zingaya_widget_short_".$_REQUEST["widget"], $options); 
    die($result["body"]);
}

function zingaya_active_widget(){
    $result = new stdClass();
    $result->result = 0;
    update_option("zingaya_active_widget", $_REQUEST["widget_id"]);
    $result->result = 1;
    
    die(json_encode($result));
}

function zingaya_widget_in_wp_head(){
    $w = intval(get_option("zingaya_active_widget", ""));
    if ( $w == 0 ) return false;
    
    zingaya_widget_shortcode( array(
        "widget_id" => $w,
        "embed_type" => "in_head"
    ) );
}
add_action( 'wp_head', 'zingaya_widget_in_wp_head' );

function register_zingaya_widget() { 
    wp_register_sidebar_widget('zingaya_widget', __('Zingaya click-to-call', 'zingaya'), "zingaya_widget_out", array( "description" => __("Zingaya Click-to-Call widget", "zingaya") ), array());
} 

function zingaya_widget_out( $args, $params ){
    extract($args); 
    $options = get_option("zingaya_widget", false);  
    
    if ( !$options ) return false;
    
    /*$gr = $options['button_graphics'];
    $gr = explode(";", $gr);
    $widgetParams = array();
    if ( count($gr) > 0 ) {
        foreach( $gr as $g ) {
            $p = explode(":", $g);
            if ( !empty($p[0]) ) $widgetParams[$p[0]] = $p[1];
        }
    }*/
    
    echo $before_widget; 
    if ( intval($options['show_title']) == 1 ) {
        echo $before_title; 
        echo $options['title']; 
        echo $after_title; 
    }
    zingaya_widget_shortcode(array( "widget_id" => $options['widget_id'], "embed_type" => "in_widget" ));

    echo $after_widget; 
}

function zingaya_widget_control() { 
    $request = ZINGAYA_API_URL . '?cmd=GetWidgets&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
            '&user_id=' . urlencode(get_option('zingaya_user_id', ''));
    
    $result = wp_remote_get( $request, array("sslverify" => false) );
    $obj = json_decode($result["body"]);   
    $widgets = $obj->result;
    $widget_data = (isset($_POST["zingaya_widget"]))?$_POST["zingaya_widget"]:0;  
    
    $opts = get_option("zingaya_widget");
    //print_r($opts);
    
    foreach($widgets as $k => $widget){
        $has_type = false;
        if ( isset($widget->button_graphics) ) {
            $wbg = explode(";", $widget->button_graphics);
            if (is_array($wbg) && count($wbg) > 0 ) {
                foreach($wbg as $w){
                    $pair = explode(":", $w);
                    if (is_array($pair) && count($pair) == 2 ) {
                        if ( $pair[0] == "type" && $pair[1] == "widget" ) {
                            if ( isset($opts["widget_id"]) && $widget->widget_id == $opts["widget_id"] ) delete_option("zingaya_widget");
                            unset($widgets[$k]);
                        } else if ($pair[0] == "type"){
                            $has_type = true;
                        }
                    }
                }
            }
        }
        if ( !$has_type ) unset($widgets[$k]);
    }
    
    if ($widget_data['submit']) {  
        
        if ( !isset($widget_data["widget_id"]) || intval($widget_data["widget_id"]) == 0 ) {
            delete_option("zingaya_widget");
        } else {
            $bg = "";
            $ga = "";
            foreach($widgets as $k => $widget){
                if ( $widget->widget_id == $widget_data["widget_id"] ) {
                    $bg = (isset($widget->button_graphics))?$widget->button_graphics:"";
                    $ga = (isset($widget->google_analytics))?$widget->google_analytics:"";
                }
            }
            $options = array(
                "title" => $widget_data['title'],
                "show_title" => ((isset($widget_data["show_title"]) && $widget_data["show_title"] == "on")?1:0),
                "widget_id" => $widget_data["widget_id"],
                "button_graphics" => $bg,
                "google_analytics" => $ga
            );
            update_option("zingaya_widget", $options);  
        }
    }  
    
    $options = get_option("zingaya_widget");  
    if (!is_array($options)) {  
      $options = array();  
    }  
    
    // Render form  
    $title = (isset($options['title']))?$options['title']:"";  
    $show_title = (isset($options['show_title']))?$options['show_title']:"";  
    $widget_id = (isset($options['widget_id']))?intval($options['widget_id']):0;  

    // The HTML form will go here  
    if ( count($widgets) > 0 ) {
?>  
<p>  
  <input type="checkbox" name="zingaya_widget[show_title]" id="zingaya_widget-showtitle"<?php if(intval($show_title) == 1) echo " checked"; ?> />
  <label for="zingaya_widget-showtitle"><?php _e("Show block title", "zingaya"); ?></label>  
</p> 
<p>  
  <label for="zingaya_widget-title"><?php _e("Widget block title", "zingaya"); ?></label>  
  <input class="widefat" type="text" name="zingaya_widget[title]" id="zingaya_widget-title" value="<?php echo $title; ?>"/>  
</p> 
<p>  
  <label for="zingaya_widget-select"><?php _e("Select widget", "zingaya"); ?></label>  
  <select name="zingaya_widget[widget_id]">
      <?php foreach($widgets as $widget) { ?>
      <option value="<?php echo $widget->widget_id; ?>"<?php if ( isset($widget_id) && $widget_id == $widget->widget_id ) echo " selected" ?>><?php echo $widget->widget_name; ?></option>
      <?php } ?>
  </select>
</p> 
<input type="hidden" name="zingaya_widget[submit]" value="1"/>  
<?php  
    } else { ?>
<p><?php _e("You don`t have any zingaya buttons", "zingaya"); ?></p> 
    <?php }
}

add_action('init', 'register_zingaya_widget');
wp_register_widget_control('zingaya_widget', __('Zingaya Click-to-Call widget', 'zingaya'), 'zingaya_widget_control' );


/*
 * Show Zingaya Widget shortcode
 */
function zingaya_widget_shortcode($atts){
    extract( shortcode_atts( array(
            'widget_id' => 'widget_id',
            'embed_type'   => 'embed_type'
    ), $atts ) );
    //print_r($widget_id);
    $options = get_option("zingaya_widget_short_" . $widget_id);  
    if ( !$options || !isset($options["callme_id"]) || !isset($options["button_graphics"]) ) {
        $request = ZINGAYA_API_URL . '?cmd=GetWidgets&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                '&user_id=' . urlencode(get_option('zingaya_user_id', '')) . 
                '&widget_id=' . $widget_id;

        $result = wp_remote_get( $request, array("sslverify" => false) );
        $obj = json_decode($result["body"]);   
        //print_r($obj);
        if (isset($obj->error)) return false;
        $widget_data = $obj->result[0];
        $options['button_graphics'] = (isset($widget_data->button_graphics))?$widget_data->button_graphics:"";
        $options['callme_id'] = $widget_data->callme_id;
    }
    
    if ( !isset($options['callme_id']) || !$options['callme_id'] || empty($options['callme_id']) ) return false;
    
    $gr = $options['button_graphics'];
    $gr = explode(";", $gr);
    $widgetParams = array();
    $widgetParams["callme_id"] = $options['callme_id'];
    if ( count($gr) > 0 ) {
        foreach( $gr as $g ) {
            $p = explode(":", $g);
            if ( !empty($p[0]) ) $widgetParams[$p[0]] = $p[1];
        }
    }    
    if ( !isset($widgetParams['type']) ) $widgetParams['type'] = "widget";
    
    if ( isset($embed_type) && $embed_type == "in_head" && $widgetParams['type'] == "button" ) return false;
    if ( !isset($embed_type) || ( $embed_type !== "in_widget" && $embed_type !== "in_head" ) ) ob_start();
?>
<?php if ( $widgetParams['type'] == "button" ) { ?><a class="zingayaButton zingaya<?php echo $widgetParams['callme_id']; ?>" id="zingayaButton<?php echo $widgetParams['callme_id']; ?>" href="https://zingaya.com/widget/<?php echo $widgetParams['callme_id']; ?>" onclick="window.open(this.href+'?referrer='+escape(window.location.href)+'', '_blank', 'width=236,height=220,resizable=no,toolbar=no,menubar=no,location=no,status=no'); return false;"></a><?php } ?>
<script>
var ZingayaConfig<?php echo $widgetParams['callme_id']; ?> = <?php echo json_encode($widgetParams); ?>;
(function(d, t) {
    if ( typeof(Zingaya) === "undefined" ) {
        var g = d.createElement(t),s = d.getElementsByTagName(t)[0];g.src = '//d1bvayotk7lhk7.cloudfront.net/js/zingayabutton.js';g.async = 'true';
        g.onload = g.onreadystatechange = function() {
        if (this.readyState && this.readyState != 'complete' && this.readyState != 'loaded') return;
        try {Zingaya.load(ZingayaConfig<?php echo $widgetParams['callme_id']; ?>, 'zingaya'+ZingayaConfig<?php echo $widgetParams['callme_id']; ?>.callme_id); if (!Zingaya.SVG()) {
                var p = d.createElement(t);p.src='//d1bvayotk7lhk7.cloudfront.net/PIE.js';p.async='true';s.parentNode.insertBefore(p, s);
                p.onload = p.onreadystatechange = function() {
                        if (this.readyState && this.readyState != 'complete' && this.readyState != 'loaded') return;
                        if (window.PIE) PIE.attach(document.getElementById("zingayaButton"+ZingayaConfig<?php echo $widgetParams['callme_id']; ?>.callme_id)); 
        }}} catch (e) {}};
        s.parentNode.insertBefore(g, s);
    } else {
        Zingaya.load(ZingayaConfig<?php echo $widgetParams['callme_id']; ?>, 'zingaya'+ZingayaConfig<?php echo $widgetParams['callme_id']; ?>.callme_id)
    }
}(document, 'script'));

</script>
<?php
    if ( !isset($embed_type) || ( $embed_type !== "in_widget" && $embed_type !== "in_head" ) ) return ob_get_clean();
}
add_shortcode('zingaya_widget', 'zingaya_widget_shortcode');


/*
 * Show Zingaya Widget in template function
 */
function zingaya_widget_show($widget_id){
    zingaya_widget_shortcode(array( "widget_id" => $widget_id, "embed_type" => "by_widget" ));
}
add_action('zingaya_widget_show', "zingaya_widget_show");

/*
 * Show Zingaya call history
 */
function zingaya_callhistory() {
	$callhistoryTable = new CallhistoryTable();
	include_once(plugin_dir_path( __FILE__ ) . '/zingaya-callhistory.php');
}

/*
 * Zingaya features tab
 */
function zingaya_features() {

	global $selected_feature;
	
	switch ($selected_feature) {
            case 'voicemail':
                if ( isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete_voicemail") {

                    $request = ZINGAYA_API_URL . '?cmd=DelVoicemail&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                            '&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
                            '&voicemail_id=' . urlencode($_REQUEST["voicemail_id"]);

                    $result = wp_remote_get( $request, array("sslverify" => false) );

                    if ( is_wp_error( $result ) ) {
                        br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                    } else {
                        $obj = json_decode($result["body"]);
                        if (!isset($obj->error)) {
                            admin_notice_message(__('Voicemail has been deleted successfully', 'zingaya'));	
                        } else {
                            // show error message
                            admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                        }
                    }
                }
                
                $voicemailTable = new VoicemailTable();
                include_once(plugin_dir_path( __FILE__ ) . '/zingaya-voicemail.php');
                break;
		case 'sip_routing':
			include_once(plugin_dir_path( __FILE__ ) . '/zingaya-sip.php');
			break;
		case 'analytics':
			include_once(plugin_dir_path( __FILE__ ) . '/zingaya-analytics.php');
			break;
		case 'blacklist':
                    if ( isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete_ip") {
                        $request = ZINGAYA_API_URL . '?cmd=DelBlackList&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                            '&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
                            '&ip=' . urlencode($_REQUEST["ip"]);

                        $result = wp_remote_get( $request, array("sslverify" => false) );

                        if ( is_wp_error( $result ) ) {
                            br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                        } else {
                            $obj = json_decode($result["body"]);
                            if (!isset($obj->error)) {
                                admin_notice_message('IP ' . $_REQUEST["ip"] . ' ' . __('has been successfully deleted from the blacklist', 'zingaya'));	
                            } else {
                                // show error message
                                admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                            }
                        }
		    } 
		    if ( isset($_REQUEST["action"]) && $_REQUEST['action'] == "add_ip") {

		    	$request = ZINGAYA_API_URL . '?cmd=AddBlackList&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
					'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
					'&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
					'&ip=' . urlencode($_POST["ip"]);

				$result = wp_remote_get( $request, array("sslverify" => false) );

				if ( is_wp_error( $result ) ) {
		            br_trigger_error( $result->get_error_message(), E_USER_ERROR);
		        } else {
		            $obj = json_decode($result["body"]);
		            if (!isset($obj->error)) {
		            	admin_notice_message('IP ' . $_REQUEST["ip"] . ' ' . __('has been successfully added to the blacklist', 'zingaya'));	
		            } else {
		                // show error message
		                admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
		            }
		        }

		    }
			$blacklistTable = new IPBlacklistTable();
			include_once(plugin_dir_path( __FILE__ ) . '/zingaya-blacklist.php');
			break;
		case 'notifications':
                        if ( isset($_REQUEST["action"]) && $_REQUEST['action'] == "update") {
                            $request = ZINGAYA_API_URL . '?cmd=SetUserInfo&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
					'&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
					'&user_id=' . urlencode(get_option('zingaya_user_id', '')) .
					'&mobile_phone=' . urlencode($_POST["mobile_phone"]) . 
                                        '&disable_personal_mails=' . ((isset($_REQUEST['disable_personal_mails']) && $_REQUEST['disable_personal_mails']=="on")?"true":"false") . 
                                        '&disable_common_mails=' . ((isset($_REQUEST['disable_common_mails']) && $_REQUEST['disable_common_mails']=="on")?"true":"false");

                            $result = wp_remote_get( $request, array("sslverify" => false) );
                            if ( is_wp_error( $result ) ) {
                                br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                            } else {
                                $obj = json_decode($result["body"]);
                                if (!isset($obj->error)) {
                                    admin_notice_message(__('Notifications settings has been updated successfully', 'zingaya'));	
                                } else {
                                    // show error message
                                    admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                                }
                            }
                        }
                        
                        $request = ZINGAYA_API_URL . '?cmd=GetUsers&user_name=' . urlencode(get_option('zingaya_user_name', '')) .
                            '&api_key=' . urlencode(get_option('zingaya_api_key', '')) . 
                            '&user_id=' . urlencode(get_option('zingaya_user_id', ''));	

                            $result = wp_remote_get( $request, array("sslverify" => false) );
                            if ( is_wp_error( $result ) ) {
                                br_trigger_error( $result->get_error_message(), E_USER_ERROR);
                            } else {
                                $obj = json_decode($result["body"]);
                                if (!isset($obj->error)) {
                                    global $userinfo;
                                    $userinfo = $obj->result[0];
                                } else {
                                    // show error message
                                    admin_notice_message('<b>' . __('Error', 'zingaya') . ':</b>' . $obj->error->code . " " . __($obj->error->msg, 'zingaya'), 'error');
                                }
                            }
			include_once(plugin_dir_path( __FILE__ ) . '/zingaya-notifications.php');
			break;
		default:
			# code...
			break;
	}

}

/*
 * Zingaya billing tab
 */
function zingaya_billing(){
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
    include_once(plugin_dir_path( __FILE__ ) . '/zingaya-billing.php');
}

function zingaya_help(){
    include_once(plugin_dir_path( __FILE__ ) . '/zingaya-help.php');
}
    ?>