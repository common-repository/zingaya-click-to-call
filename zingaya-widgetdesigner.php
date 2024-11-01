<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//global $zpresets;
function __unescape($source) {
        $decodedStr = "";
        $pos = 0;
        $len = strlen ($source);
        while ($pos < $len) {
                $charAt = substr ($source, $pos, 1);
                if ($charAt=='%') {
                        $pos++;
                        $charAt = substr ($source, $pos, 1);
                        if($charAt=='u'){
                                // we got a unicode character
                                $pos++;
                                $unicodeHexVal = substr ($source, $pos, 4);
                                $unicode = hexdec ($unicodeHexVal);
                                $entity = "&#". $unicode . ';';
                                $decodedStr .= utf8_encode ($entity);
                                $pos += 4;
                        }else {
                                // we have an escaped ascii character
                                $hexVal = substr ($source, $pos, 2);
                                $decodedStr .= chr (hexdec ($hexVal));
                                $pos += 2;
                        }
                } else {
                        $decodedStr .= $charAt;
                        $pos++;
                }
        }
        return $decodedStr;
}

$widget_data = $GLOBALS['widget_data'];
$zpresets = $GLOBALS['zpresets'];
$callme_id = $widget_data->callme_id;
$widget_id = $widget_data->widget_id;
$callme_url = "https://cdn.zingaya.com/callme.swf";
$gaId = (isset($widget_data->google_analytics))?$widget_data->google_analytics:"";

$s = array();
$zp = array();
if ( isset($widget_data->button_graphics) && !empty($widget_data->button_graphics) ) $s = explode(';', $widget_data->button_graphics);
if ( count($s) > 0 ) {
    foreach($s as $data) {
        $pair = explode(':', $data);
        if (is_array($pair) && count($pair) > 1) {
            $key = $pair[0];
            $value = $pair[1];
            $value = stripslashes($value);
            $zp[$key] = __unescape($value);
        }
    }
}
$zingaya_params = $zp;

$g = array();
if ( isset($widget_data->graphics) ) $g = explode( ';', $widget_data->graphics );
$polls = false;

if ( count($g) > 0 ) {
    foreach($g as $data) {
        $pair = explode(':', $data);
        if ( !is_array($pair) || count($pair) < 2 ) continue;
        $key = $pair[0];
        $value = $pair[1];
        $value = stripslashes($value);

        if ( $key == 'polls' ) $polls = true; 
        if ( $key == 'poll_id' ) $poll_id = intval($value);
    }
}

$zaw = intval(get_option("zingaya_active_widget", 0));

?>
<link rel='stylesheet' id='gen_widget-css'  href='<?php echo plugins_url('/css/gen_widget.css', __FILE__); ?>' type='text/css' media='all' />
<?php wp_enqueue_style('zingaya-admin-ui-css', plugins_url('/css/ui-lightness/jquery-ui.css', __FILE__)); ?>
<link rel='stylesheet' id='colorpicker-css'  href='<?php echo plugins_url('/css/colorpicker.css', __FILE__); ?>' type='text/css' media='all' />
<link rel='stylesheet' id='colorpicker-css'  href='<?php echo plugins_url('/css/button.css', __FILE__); ?>' type='text/css' media='all' />

<script type='text/javascript'>
/* <![CDATA[ */
var commonL10n = {"warnDelete":"\u0412\u044b \u0441\u043e\u0431\u0438\u0440\u0430\u0435\u0442\u0435\u0441\u044c \u043d\u0430\u0432\u0441\u0435\u0433\u0434\u0430 \u0443\u0434\u0430\u043b\u0438\u0442\u044c \u0432\u044b\u0434\u0435\u043b\u0435\u043d\u043d\u044b\u0435 \u043e\u0431\u044a\u0435\u043a\u0442\u044b.\n  \u00ab\u041e\u0442\u043c\u0435\u043d\u0430\u00bb \u2014 \u043e\u0441\u0442\u0430\u0432\u0438\u0442\u044c, \u00abOK\u00bb \u2014 \u0443\u0434\u0430\u043b\u0438\u0442\u044c."};var heartbeatSettings = {"nonce":"1d2a932de1"};var authcheckL10n = {"beforeunload":"\u0412\u0430\u0448\u0430 \u0441\u0435\u0441\u0441\u0438\u044f \u0438\u0441\u0442\u0435\u043a\u043b\u0430. \u0412\u044b \u043c\u043e\u0436\u0435\u0442\u0435 \u0432\u043e\u0439\u0442\u0438 \u0441\u043d\u043e\u0432\u0430 \u0441 \u044d\u0442\u043e\u0439 \u0441\u0442\u0440\u0430\u043d\u0438\u0446\u044b \u0438\u043b\u0438 \u043f\u0435\u0440\u0435\u0439\u0442\u0438 \u043d\u0430 \u0441\u0442\u0440\u0430\u043d\u0438\u0446\u0443 \u0432\u0445\u043e\u0434\u0430.","interval":"180"};/* ]]> */
</script>

<?php 
wp_enqueue_script("jquery-ui-core"); 
wp_enqueue_script("jquery-ui-slider"); 
?>
<script type='text/javascript' src='<?php echo plugins_url('/js/colorpicker.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/zingayabutton.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('/js/ZeroClipboard.js', __FILE__); ?>'></script>
<div class="wrap">
<!--[if IE]>
  <script src='PIE.js'></script>
<![endif]--> 
<div class="gen_widget_main_container">
        <h1><?php echo __('Widget visual settings', "zingaya"); ?></h1>
        <h2><?php echo __('Customize the look and feel of your widget <br /> according to the style of your website', "zingaya"); ?></h2>
        <div class="left_container">
                <div class="preview_container light">
                        <div class="background_switcher_container">
                                <b><?php echo __('Preview background', "zingaya"); ?></b>
                                <div class="slider_container" style="float:right; margin-top: 4px; display: inline-block;">
                                        <span id="0"><?php echo __('Light', "zingaya"); ?></span>
                                        <div id="slider_preview" class="slider2"></div>
                                        <span id="1"><?php echo __('Dark', "zingaya"); ?></span>
                                </div>
                        </div>
                        <span class="background_preview_title"><?php echo __('PREVIEW', "zingaya"); ?></span> 
                        <a href="http://zingaya.com/widget/<?php echo $callme_id; ?>" onclick="window.open(this.href+'?referrer='+escape(window.location.href), '_blank', 'width=236,height=220,resizable=no,toolbar=no,menubar=no,location=no,status=no'); return false;" class="zingayaButton zingaya<?php echo $callme_id; ?>"></a>    
                        <!--<iframe src="/cp/widgets/previewbutton" id="preview_frame" frameborder="0" scrolling="none" width="450" height="550"></iframe>-->
                </div>
                <div class="finish_generate_container">
                    <div id="gen_code_textarea" style="display: none; cursor: text;"></div>
                    <div id="gen_code_button_block">
                        <p style="display:none;" id="b0">
                                <?php echo __('Finished? Click the button to save and show your widget`s embed code.', "zingaya"); ?> 
                        </p>
                        <p style="display:none;" id="b12">
                                <?php echo __('Finished? Click the button to Save or Save and place widget.', "zingaya"); ?> 
                        </p>
<?php 
$chkd = "";
if ( $zaw == 0 ) $chkd = " checked";
else {
    if ( $zaw == $widget_id ) $chkd = " checked";
}
?>                        
                        <a href="javascript: void(0);" style="display:none;" onclick="javascript: genButtonCode();" class="finish_button b0"><?php echo __('Save and show embed code', "zingaya"); ?></a>
                        <label style="text-align: center; display: none;" class="b2"><input name="widget_active" type="checkbox"<?php echo $chkd; ?> /><?php _e("Place on website", "zingaya"); ?></label>
                        <a href="javascript: void(0);" style="display:none;" onclick="javascript: genButtonCode(1);" class="finish_button b1"><?php echo __('Save widget', "zingaya"); ?></a>
                    </div>
                </div>
        </div>
        <div class="right_container" style="position:relative;">
                <div class="settings_groupe_container">
                        <div class="full_container" style="width:100%;">
                                <a href="javascript: void(0);" class="sign_up" id="show-presets" style="float:left"><?php echo __("Choose preset", "zingaya"); ?></a>
                                <a href="javascript: void(0);" onclick="javascript: /*jQuery('#adv_params').toggle();*/moreOnOff();" class="login" style="float:right"><?php echo __("More settings", "zingaya"); ?></a> 
                        </div>
                </div>
                <div class="dotted_line"> </div>
<div id="presets" style="opacity:0; display: none; z-index: 1000;">
    <div style="padding: 10px;">
    <h2 style="float:left;"><?php echo __("Presets", "zingaya"); ?></h2> 
    <a class="cls">&times;</a>
    <br class="clear" />
    <!--<a class="yazyk"><?php echo __("Presets", "zingaya"); ?></a>-->
    <?php $i = 0; foreach($zpresets as $name => $preset) { ?>
    <?php $preset->buttonLabel = __($preset->buttonLabel, "zingaya"); ?>  
    <?php echo "<div class=\"zingayaButton {$name}\" style=\"margin-bottom: 10px;\" onclick=\"javascript: from_template = true; updateButton(ZingayaConfig_{$name}, true);\"> </div><br />"; ?>
    <script>
        var zc = <?php echo json_encode($preset); ?>;
        var ZingayaConfig_<?php echo $name; ?> = Zingaya.Config();
        for(var k in ZingayaConfig_<?php echo $name; ?>) {
                if ( typeof(zc[k]) !== "undefined" ) ZingayaConfig_<?php echo $name; ?>[k] = zc[k];
                ZingayaConfig_<?php echo $name; ?>.callme_id = '<?php echo $name; ?>';
        }
//        var ZingayaConfig_<?php echo $name; ?> = <?php echo json_encode($preset); ?>;
        Zingaya.load(ZingayaConfig_<?php echo $name; ?>, '<?php echo $name; ?>'); 
    </script>
    <?php } ?>
    </div>
</div>
<input type="hidden" id="buttonShadow" value="" />                
                <div id="advanced">                
                <div class="settings_groupe_container">
                        <div class="left_container">
                                <b><?php echo __('Type', "zingaya"); ?></b>
                                <br class="clear" />
                                <div class="slider_container">
                                        <span id="0"><?php echo __('Widget', "zingaya"); ?></span>
                                        <div id="slider_type" class="slider2"></div>
                                        <span id="1"><?php echo __('Button', "zingaya"); ?></span>
                                </div>
                        </div>
                        <div class="left_container" id="widget_position">
                                <b><?php echo __('Position', "zingaya"); ?></b> 
                                <br class="clear" />
                                <div class="slider_container">
                                        <span id="0"><?php echo __('Left', "zingaya"); ?></span>
                                        <div id="slider_position" class="slider2"></div>
                                        <span id="1"><?php echo __('Right', "zingaya"); ?></span>
                                </div>
                        </div>
                </div>
                <div class="dotted_line"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container">
                                <b><?php echo __('Button Label', "zingaya"); ?></b>
                                <br class="clear" />
                                <input type="text" id="button_label" onblur="javascript: getConfigData();" value="<?php echo __('Call Us Online', "zingaya"); ?>" />
                        </div> 
                </div>
                <div id="adv_params" style="">
                <div class="dotted_line"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container">
                                <b><?php echo __('Button Preset', "zingaya"); ?></b>
                        </div>
                </div>
                <div class="dotted_line moreon"> </div>
                <div class="settings_groupe_container moreon">
                        <div class="full_container">
                                <b><?php echo __('Icon color&shadow', "zingaya"); ?></b> 
                        </div> 
                        <div class="left_container">
                                <input id="iconColor" style="display: none" value="#13487f"  /><div id="iconColor_picker" class="color_picker"><div style="background-color: #13487f" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Icon color',"zingaya"); ?></span>
                        </div>
                        <div class="left_container"> 
                                <?php echo __('Icon Shadow', "zingaya"); ?> 
                                <br class="clear" />
                                <div class="slider_container">
                                        <span id="0"><?php echo __('On', "zingaya"); ?></span>
                                        <div id="slider_icon_shadow" class="slider2"></div>
                                        <span id="1"><?php echo __('Off', "zingaya"); ?></span>
                                </div>
                        </div>
                </div>
                <div class="dotted_line"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container moreon">
                                <b><?php echo __('Button Colors', "zingaya"); ?></b>
                        </div>
                        <br class="clear moreon" />
                        <div class="left_container">
                                <input id="buttonGradientColor1" style="display: none" value="#68c3f0"  /><div id="buttonGradientColor1_picker" class="color_picker"><div style="background-color: #68c3f0" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Color 1',"zingaya"); ?></span>
                        </div>
                        <div class="left_container moreon">
                            <input id="buttonGradientColor2" style="display: none" value="#62bfef"  /><div id="buttonGradientColor2_picker" class="color_picker"><div style="background-color: #62bfef" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Color 2',"zingaya"); ?></span>
                        </div>
                        <br class="clear moreon moreon2" />
                        <div class="left_container moreon moreon2">
                                <input id="buttonGradientColor3" style="display: none" value="#68c3f0"  /><div id="buttonGradientColor3_picker" class="color_picker"><div style="background-color: #68c3f0" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Color 3',"zingaya"); ?></span>
                        </div>
                        <div class="left_container moreon moreon2">
                            <input id="buttonGradientColor4" style="display: none" value="#62bfef"  /><div id="buttonGradientColor4_picker" class="color_picker"><div style="background-color: #62bfef" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Color 4',"zingaya"); ?></span>
                        </div>
                </div>
                <div class="dotted_line moreon"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container moreon">
                                <b><?php echo __('Hover Button Colors', "zingaya"); ?></b>
                        </div>
                        <br class="clear moreon" />
                        <div class="left_container">
                                <input id="buttonHoverGradientColor1" style="display: none" value="#68c3f0"  /><div id="buttonHoverGradientColor1_picker" class="color_picker"><div style="background-color: #68c3f0" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Hover color 1',"zingaya"); ?></span>
                        </div>
                        <div class="left_container moreon">
                            <input id="buttonHoverGradientColor2" style="display: none" value="#62bfef"  /><div id="buttonHoverGradientColor2_picker" class="color_picker"><div style="background-color: #62bfef" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Hover color 2',"zingaya"); ?></span>
                        </div>
                        <br class="clear moreon moreon2" />
                        <div class="left_container moreon moreon2">
                                <input id="buttonHoverGradientColor3" style="display: none" value="#68c3f0"  /><div id="buttonHoverGradientColor3_picker" class="color_picker"><div style="background-color: #68c3f0" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Hover color 3',"zingaya"); ?></span>
                        </div>
                        <div class="left_container moreon moreon2">
                            <input id="buttonHoverGradientColor4" style="display: none" value="#62bfef"  /><div id="buttonHoverGradientColor4_picker" class="color_picker"><div style="background-color: #62bfef" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Hover color 4',"zingaya"); ?></span> 
                        </div>
                </div>
                <div class="dotted_line moreon"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container moreon">
                                <b><?php echo __('Label Colors', "zingaya"); ?></b>
                        </div> 
                         <div class="full_container">
                                <input id="top_color2" style="display: none" value="#13487f"  /><div id="top_color2_picker" class="color_picker"><div style="background-color: #13487f" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Text Color',"zingaya"); ?></span>
                        </div> 
                        <div class="full_container moreon">
                                <b><?php echo __('Label Shadow', "zingaya"); ?></b>
                        </div> 
                        <div class="left_container moreon"> 
                                <div class="slider_container" style="margin-top: 15px;">
                                        <span id="0"><?php echo __('On', "zingaya"); ?></span>
                                        <div id="slider_label_shadow" class="slider2"></div>
                                        <span id="1"><?php echo __('Off', "zingaya"); ?></span>
                                </div>
                        </div>
                        <div class="left_container moreon">
                                <input id="bottom_color2" style="display: none" value="#8fd3ec"  /><div id="bottom_color2_picker" class="color_picker"><div style="background-color: #8fd3ec" class="preview"></div></div> <span style="line-height: 35px;"><?php echo __('Shadow Color',"zingaya"); ?></span>
                        </div>  
                </div>
                <div class="dotted_line"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container">
                                <b><?php echo __('Padding', "zingaya"); ?></b>
                                <br class="clear" />
                                <div class="slider_container">
                                        <div id="slider_padding" class="slider23"></div>
                                        <br class="clear" />
                                        <div class="slider-markers"></div>
                                </div>
                        </div>
                </div>
                <div class="dotted_line"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container">
                                <b><?php echo __('Font Size', "zingaya"); ?></b>
                                <br class="clear" />
                                <div class="slider_container">
                                        <div id="slider_font_size" class="slider23"></div>
                                        <br class="clear" />
                                        <div class="slider-markers"></div>
                                </div>
                        </div>
                </div>
                <div class="dotted_line"> </div>
                <div class="settings_groupe_container">
                        <div class="full_container">
                                <b><?php echo __('Corner Roundness', "zingaya"); ?></b>
                                <br class="clear" />
                                <div class="slider_container">
                                        <div id="slider_corner_raundness" class="slider23"></div>
                                        <br class="clear" />
                                        <div class="slider-markers"></div>
                                </div>
                        </div>
                </div>
                </div>
                </div>
        </div>
</div>
<pre id="str1_template" style="display:none;">
(function(d, t) {
    var g = d.createElement(t),s = d.getElementsByTagName(t)[0];g.src = '//d1bvayotk7lhk7.cloudfront.net/js/zingayabutton.js';g.async = 'true';
        g.onload = g.onreadystatechange = function() {
        if (this.readyState && this.readyState != 'complete' && this.readyState != 'loaded') return;
        try {Zingaya.load(ZingayaConfig, 'zingaya<?php echo $callme_id; ?>'); if (!Zingaya.SVG()) {
                var p = d.createElement(t);p.src='//d1bvayotk7lhk7.cloudfront.net/PIE.js';p.async='true';s.parentNode.insertBefore(p, s);
                p.onload = p.onreadystatechange = function() {
                        if (this.readyState && this.readyState != 'complete' && this.readyState != 'loaded') return;
                        if (window.PIE) PIE.attach(document.getElementById("zingayaButton"+ZingayaConfig.callme_id)); 
        }}} catch (e) {}};
    s.parentNode.insertBefore(g, s);
}(document, 'script'));
</pre>
<br class="clearfix" /> 

<input type="hidden" id="buttonActiveShadowColor1" value="" />
<input type="hidden" id="buttonActiveShadowColor2" value="" />

<script>
var tmp_str1 = '<?php echo __("Choose preset", "zingaya"); ?>';    
var tmp_str2 = '<?php echo __("Hide", "zingaya"); ?>';
var clipboard_notification = '<?php echo htmlspecialchars(__("Successfully copied your HTML code to clipboard", "zingaya")); ?>';
var gen_code_info_widget_str = '<?php echo __("Copy the code below and paste it immediately before the closing </head> tag", "zingaya"); ?>';
var gen_code_info_button_str = '<?php echo __("Copy the code below and paste it in place where you want your button to be shown", "zingaya"); ?>';
var clip;
/*
    clip.on( "load", function(client) {
        client.on( "complete", function(client, args) {
          this.style.display = "none";
          alert('<?php echo __("HTML code successfully copied!", "zingaya"); ?>'); 
          jQuery("#copy_code").show();
          return false;
        } ); 
        client.on( "remove", function(client, args) {
            jQuery("#copy_code").show();
          return false;
        } ); 
    } );
*/
var save_url = ajaxurl + '?action=zingaya_save_button_ajax';  
var moreshow = false;
var opencode = false;
var from_template = false;
var zingaya_loading = true;
var zingaya_config = {};
var callme_id = '<?php echo $callme_id; ?>';
var widget_id = '<?php echo $widget_id; ?>';
var polls = "";
var poll_id = null;
<?php
 if (isset($gaId) && !empty($gaId)) {
     echo "var analytics = true;\n";
     echo "var analytics_id = '".$gaId."';\n"; 
 }
 else {
     echo "var analytics = false;\n";
     echo "var analytics_id = null;\n";
 }

 if ( $polls ) {
    if ( isset($poll_id) && intval($poll_id) > 0 ) {
        echo "var polls = '&extra='+escape('polls:true;poll_id:".$poll_id."');"; 
        echo "poll_id=" . $poll_id . ";";
    }
 }
?>
var onclick = ""; 
if (!analytics) {
    onclick = "window.open(this.href+'?referrer='+escape(window.location.href)+'"+polls+"', '_blank', 'width=236,height=220,resizable=no,toolbar=no,menubar=no,location=no,status=no'); return false;";
} else {
    onclick = "typeof(_gaq)=='undefined'?'':_gaq.push(['_trackEvent', 'Zingaya', 'ButtonClick']);typeof(_gat)=='undefined'?'':_gat._getTrackerByName()._setAllowLinker(true); window.open(typeof(_gat)=='undefined'?this.href+'?referrer='+escape(window.location.href)+'"+polls+"':_gat._getTrackerByName()._getLinkerUrl(this.href+'?referrer='+escape(window.location.href)+'"+polls+"'), '_blank', 'width=236,height=220,resizable=no,toolbar=no,menubar=no,location=no,status=no'); return false;";
}
zingaya_config.type                 = '<?php if ( !isset($zingaya_params["type"]) || $zingaya_params["type"] == "widget" ) echo "widget"; else echo "button"; ?>';
zingaya_config.widgetPosition       = '<?php if ( !isset($zingaya_params["widgetPosition"]) || $zingaya_params["widgetPosition"] == "right" ) echo "right"; else echo "left"; ?>';
zingaya_config.buttonLabel          = '<?php if ( !isset($zingaya_params["buttonLabel"]) ) echo __('Call Us Online', true); else echo $zingaya_params["buttonLabel"] ?>';
zingaya_config.iconDropShadow       = <?php if ( !isset($zingaya_params["iconDropShadow"]) || $zingaya_params["iconDropShadow"] == "true" ) echo 0; else echo 1; ?>;
zingaya_config.iconShadowColor = '<?php if ( !isset($zingaya_params["iconShadowColor"]) ) echo '#13487f'; else echo $zingaya_params["iconShadowColor"]; ?>';
zingaya_config.iconColor = '<?php if ( !isset($zingaya_params["iconColor"]) ) echo '#ffffff'; else echo $zingaya_params["iconColor"]; ?>';
zingaya_config.labelShadow      = <?php if ( !isset($zingaya_params["labelShadow"]) || $zingaya_params["labelShadow"] == "true" ) echo 0; else echo 1; ?>;
zingaya_config.buttonPadding        = <?php if ( !isset($zingaya_params["buttonPadding"]) ) echo 10; else echo intval($zingaya_params["buttonPadding"]); ?>;
zingaya_config.labelFontSize        = <?php if ( !isset($zingaya_params["labelFontSize"]) ) echo 3; else echo intval($zingaya_params["labelFontSize"])-12; ?>; 
zingaya_config.buttonCornerRadius   = <?php if ( !isset($zingaya_params["buttonCornerRadius"]) ) echo 2; else echo $zingaya_params["buttonCornerRadius"]; ?>;
zingaya_config.widget_id            = <?php echo intval($widget_id); ?>;
zingaya_config.callme_id            = '<?php echo $callme_id; ?>';
zingaya_config.labelColor = '<?php if ( !isset($zingaya_params["labelColor"]) ) echo "#13487f"; else echo $zingaya_params["labelColor"]; ?>';
zingaya_config.labelShadowColor = '<?php if ( !isset($zingaya_params["labelShadowColor"]) ) echo "#8fd3ec"; else echo $zingaya_params["labelShadowColor"]; ?>';
zingaya_config.buttonGradientColor1 = '<?php if ( !isset($zingaya_params["buttonGradientColor1"]) ) echo '#68c3f0'; else echo $zingaya_params["buttonGradientColor1"]; ?>';
zingaya_config.buttonGradientColor2 = '<?php if ( !isset($zingaya_params["buttonGradientColor2"]) ) echo '#5bbaee'; else echo $zingaya_params["buttonGradientColor2"]; ?>';
zingaya_config.buttonGradientColor3 = '<?php if ( !isset($zingaya_params["buttonGradientColor3"]) ) echo '#5fbdef'; else echo $zingaya_params["buttonGradientColor3"]; ?>';
zingaya_config.buttonGradientColor4 = '<?php if ( !isset($zingaya_params["buttonGradientColor4"]) ) echo '#62bfef'; else echo $zingaya_params["buttonGradientColor4"]; ?>';
zingaya_config.buttonBackgroundColor = '<?php if ( !isset($zingaya_params["buttonGradientColor1"]) ) echo '#68c3f0'; else echo $zingaya_params["buttonGradientColor1"]; ?>';
zingaya_config.buttonHoverGradientColor1 = '<?php if ( !isset($zingaya_params["buttonHoverGradientColor1"]) ) echo '#30b3f1'; else echo $zingaya_params["buttonHoverGradientColor1"]; ?>';
zingaya_config.buttonHoverGradientColor2 = '<?php if ( !isset($zingaya_params["buttonHoverGradientColor2"]) ) echo '#2aa8ef'; else echo $zingaya_params["buttonHoverGradientColor2"]; ?>';
zingaya_config.buttonHoverGradientColor3 = '<?php if ( !isset($zingaya_params["buttonHoverGradientColor3"]) ) echo '#2cacf0'; else echo $zingaya_params["buttonHoverGradientColor3"]; ?>';
zingaya_config.buttonHoverGradientColor4 = '<?php if ( !isset($zingaya_params["buttonHoverGradientColor4"]) ) echo '#2daef0'; else echo $zingaya_params["buttonHoverGradientColor4"]; ?>';

zingaya_config.buttonActiveShadowColor1 = '<?php if ( !isset($zingaya_params["buttonActiveShadowColor1"]) ) echo ""; else echo $zingaya_params["buttonActiveShadowColor1"]; ?>';
zingaya_config.buttonActiveShadowColor2 = '<?php if ( !isset($zingaya_params["buttonActiveShadowColor2"]) ) echo ""; else echo $zingaya_params["buttonActiveShadowColor2"]; ?>'; 
zingaya_config.buttonShadow = <?php if ( !isset($zingaya_params["buttonShadow"]) || $zingaya_params["buttonShadow"] == "true" ) echo 'true'; else echo 'false'; ?>;
zingaya_config.save = 0;
//zingaya_config.onclick = onclick;

zingaya_config.poll_id = poll_id;
zingaya_config.analytics_id = analytics_id;

if ( zingaya_config.type == "widget" ) {
    jQuery(".finish_button.b0").hide();
    jQuery(".finish_button.b1").show();
    jQuery(".b2").show();
    jQuery("p.b0").hide();
    jQuery("p.b12").show();
} else {
    jQuery(".finish_button.b0").show();
    jQuery(".finish_button.b1").hide();
    jQuery(".b2").hide();
    jQuery("p.b0").show();
    jQuery("p.b12").hide();
}

var zc = <?php echo json_encode($zingaya_params); ?>; 
var ZingayaConfig_ZingayaPreview = Zingaya.Config();
for(var k in ZingayaConfig_ZingayaPreview) {
        if ( typeof(zingaya_config[k]) !== "undefined" ) ZingayaConfig_ZingayaPreview[k] = zingaya_config[k]; 
        if ( k == "labelFontSize" ) ZingayaConfig_ZingayaPreview[k] = zingaya_config[k]+12; 
        ZingayaConfig_ZingayaPreview.callme_id = callme_id;
}

if ( typeof(ZingayaConfig_ZingayaPreview.type) === "undefined" ) ZingayaConfig_ZingayaPreview.type = zingaya_config.type;
if ( typeof(ZingayaConfig_ZingayaPreview.widgetPosition) === "undefined" ) ZingayaConfig_ZingayaPreview.widgetPosition = zingaya_config.widgetPosition; 
if ( typeof(ZingayaConfig_ZingayaPreview.callme_id) === "undefined" || callme_id === "" ) ZingayaConfig_ZingayaPreview.callme_id = zingaya_config.callme_id;


var sliders_values = {
    slider_preview          : [ "light", "dark"],
    slider_type             : [ "widget", "button" ], 
    slider_position         : [ "left", "right"],
    slider_icon_style       : [ "#ffffff", "#000000"],
    slider_icon_shadow      : [ "true", "false"],
    slider_label_shadow     : [ "true", "false"],
    slider_padding          : [ 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27 ],
    slider_font_size        : [ 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34 ],
    slider_corner_roundness : [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22 ]
};
var wizard_fields  = {
    slider_type             : { type : "slider2" },
    slider_position         : { type : "slider2" },
    button_label            : { type : "input" },
    slider_icon_style       : { type : "slider2" },
    slider_icon_shadow      : { type : "slider2" },
    slider_label_shadow     : { type : "slider2" },
    button_top_color        : { type : "color" },
    button_bottom_color     : { type : "color" },
    label_text_color        : { type : "color" },
    label_shadow_color      : { type : "color" },
    slider_padding          : { type : "slider23" },
    slider_font_size        : { type : "slider23" },
    slider_corner_roundness : { type : "slider23" }
};

jQuery(document).ready(function(){
    
    jQuery(".slider2").slider(
        {
            range: "max",
            min: 0,
            max: 1,
            value: 0,
            change: function(event, ui) {
                if ( !zingaya_loading ) getConfigData(false); 
            }
        }
    );
    
    jQuery("#show-presets, .cls").click(function(){ 
        if ( !jQuery("#presets").hasClass("opened") ) {
            jQuery("#presets").css("display", "block");
            jQuery("#presets").animate({opacity:1}, "slow", "linear", function(e){
                jQuery("#advanced").hide();
                jQuery("#presets").addClass("opened");
            });
            jQuery("#show-presets").text(tmp_str2);
        } else {
            jQuery("#presets").animate({ opacity : 0 }, "slow", "linear", function(e){
                jQuery("#advanced").show();
                jQuery("#presets").hide().removeClass("opened");
            });
            jQuery("#show-presets").text(tmp_str1);
        }
    });

    jQuery(".slider23").slider(
        {
            range: "max",
            min: 0,
            max: 22,
            value: 0,
            change: function(event, ui) {
                if ( !zingaya_loading ) getConfigData(false);
            }
        }
    );

    jQuery(".slider_container span").click(function(){
            jQuery(this).siblings(".slider2").slider("value", jQuery(this).attr("id"));
    });
    
    jQuery( ".slider2#slider_preview" ).slider({
        change: function( event, ui ) {
            jQuery(".preview_container").removeClass("dark").removeClass("light").addClass(sliders_values["slider_preview"][ui.value]);
        }
    });

    jQuery('#iconColor_picker').ColorPicker({
            color: jQuery('#iconColor').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#iconColor').val('#'+hex);
                    jQuery('#iconColor_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#iconColor').val(zingaya_config.iconColor);
    jQuery('#iconColor_picker .preview').css("backgroundColor", zingaya_config.iconColor);
    jQuery('#iconColor_picker').ColorPickerSetColor(zingaya_config.iconColor);

    jQuery('#buttonGradientColor1_picker').ColorPicker({
            color: jQuery('#buttonGradientColor1').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonGradientColor1').val('#'+hex);
                    jQuery('#buttonGradientColor1_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
                    if ( moreshow ) {
                        jQuery("#buttonGradientColor2").val(gradient_color);
                        jQuery("#buttonGradientColor3").val(gradient_color);
                        jQuery("#buttonGradientColor4").val(gradient_color);
                    }
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonGradientColor1').val(zingaya_config.buttonGradientColor1);
    jQuery('#buttonGradientColor1_picker .preview').css("backgroundColor", zingaya_config.buttonGradientColor1);
    jQuery('#buttonGradientColor1_picker').ColorPickerSetColor(zingaya_config.buttonGradientColor1);
    
    jQuery('#top_color2_picker').ColorPicker({
            color: jQuery('#top_color2').val(),
            livePreview : false,
            /*flat : true,*/
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) { 
                    jQuery('#top_color2').val('#'+hex);
                    jQuery('#top_color2_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#top_color2').val(zingaya_config.labelColor);
    jQuery('#top_color2_picker .preview').css("backgroundColor", zingaya_config.labelColor);
    jQuery('#top_color2_picker').ColorPickerSetColor(zingaya_config.labelColor);
    
    jQuery('#buttonHoverGradientColor1_picker').ColorPicker({
            color: jQuery('#buttonHoverGradientColor1').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonHoverGradientColor1').val('#'+hex);
                    jQuery('#buttonHoverGradientColor1_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
                    
                    if ( moreshow ) {
                        jQuery("#buttonHoverGradientColor2").val(gradient_color);
                        jQuery("#buttonHoverGradientColor3").val(gradient_color);
                        jQuery("#buttonHoverGradientColor4").val(gradient_color);
                    }
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonHoverGradientColor1').val(zingaya_config.buttonHoverGradientColor1)
    jQuery('#buttonHoverGradientColor1_picker .preview').css("backgroundColor", zingaya_config.buttonHoverGradientColor1);
    jQuery('#buttonHoverGradientColor1_picker').ColorPickerSetColor(zingaya_config.buttonHoverGradientColor1);
    
    
    
    jQuery('#buttonGradientColor2_picker').ColorPicker({
            color: jQuery('#buttonGradientColor2').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonGradientColor2').val('#'+hex);
                    jQuery('#buttonGradientColor2_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonGradientColor2').val(zingaya_config.buttonGradientColor2); 
    jQuery('#buttonGradientColor2_picker .preview').css("backgroundColor", zingaya_config.buttonGradientColor2);
    jQuery('#buttonGradientColor2_picker').ColorPickerSetColor(zingaya_config.buttonGradientColor2);
    
    jQuery('#bottom_color2_picker').ColorPicker({
            color: jQuery('#bottom_color2').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#bottom_color2').val('#'+hex);
                    jQuery('#bottom_color2_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#bottom_color2').val(zingaya_config.labelShadowColor);
    jQuery('#bottom_color2_picker .preview').css("backgroundColor", zingaya_config.labelShadowColor);
    jQuery('#bottom_color2_picker').ColorPickerSetColor(zingaya_config.labelShadowColor);
    
    jQuery('#buttonHoverGradientColor2_picker').ColorPicker({
            color: jQuery('#buttonHoverGradientColor2').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonHoverGradientColor2').val('#'+hex);
                    jQuery('#buttonHoverGradientColor2_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonHoverGradientColor2').val(zingaya_config.buttonHoverGradientColor2);
    jQuery('#buttonHoverGradientColor2_picker .preview').css("backgroundColor", zingaya_config.buttonHoverGradientColor2);
    jQuery('#buttonHoverGradientColor2_picker').ColorPickerSetColor(zingaya_config.buttonHoverGradientColor2); 
    
    jQuery('#buttonHoverGradientColor3_picker').ColorPicker({
            color: jQuery('#buttonHoverGradientColor3').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonHoverGradientColor3').val('#'+hex);
                    jQuery('#buttonHoverGradientColor3_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonHoverGradientColor3').val(zingaya_config.buttonHoverGradientColor3)
    jQuery('#buttonHoverGradientColor3_picker .preview').css("backgroundColor", zingaya_config.buttonHoverGradientColor3);
    jQuery('#buttonHoverGradientColor3_picker').ColorPickerSetColor(zingaya_config.buttonHoverGradientColor3);
    
    jQuery('#buttonHoverGradientColor4_picker').ColorPicker({
            color: jQuery('#buttonHoverGradientColor4').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonHoverGradientColor4').val('#'+hex);
                    jQuery('#buttonHoverGradientColor4_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonHoverGradientColor4').val(zingaya_config.buttonHoverGradientColor4)
    jQuery('#buttonHoverGradientColor4_picker .preview').css("backgroundColor", zingaya_config.buttonHoverGradientColor4);
    jQuery('#buttonHoverGradientColor4_picker').ColorPickerSetColor(zingaya_config.buttonHoverGradientColor4);
    
    jQuery('#buttonGradientColor3_picker').ColorPicker({
            color: jQuery('#buttonGradientColor3').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonGradientColor3').val('#'+hex);
                    jQuery('#buttonGradientColor3_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonGradientColor3').val(zingaya_config.buttonGradientColor3);
    jQuery('#buttonGradientColor3_picker .preview').css("backgroundColor", zingaya_config.buttonGradientColor3);
    jQuery('#buttonGradientColor3_picker').ColorPickerSetColor(zingaya_config.buttonGradientColor3);

    jQuery('#buttonGradientColor4_picker').ColorPicker({
            color: jQuery('#buttonGradientColor4').val(),
            onShow: function (colpkr) {
                    jQuery(colpkr).css('zIndex', 10000);
                    jQuery(colpkr).fadeIn(500);
                    return false;
            },
            onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
            },
            onChange: function (hsb, hex, rgb) {
                    jQuery('#buttonGradientColor4').val('#'+hex);
                    jQuery('#buttonGradientColor4_picker div').css('backgroundColor', '#' + hex);
                    gradient_color = '#'+hex;
            }, 
            onMup: function(){
                if ( !zingaya_loading ) getConfigData(false);
            }
    });
    jQuery('#buttonGradientColor4').val(zingaya_config.buttonGradientColor4);
    jQuery('#buttonGradientColor4_picker .preview').css("backgroundColor", zingaya_config.buttonGradientColor4);
    jQuery('#buttonGradientColor4_picker').ColorPickerSetColor(zingaya_config.buttonGradientColor4);
    
    jQuery("#buttonActiveShadowColor1").val(zingaya_config.buttonActiveShadowColor1);
    jQuery("#buttonActiveShadowColor2").val(zingaya_config.buttonActiveShadowColor2);
    
    updateButton(ZingayaConfig_ZingayaPreview, true); 
    setConfigData(ZingayaConfig_ZingayaPreview); 
    moreOnOff(false);
    
});

function updateButton(params, prev){ 
    if ( !opencode ) {
        jQuery("#gen_code_button_block").show(); 
        jQuery("#gen_code_textarea").hide(); 
        jQuery("#gen_code_info").hide();
        jQuery("#copy_code").hide();
        opencode = false;
    } else {
        opencode = false;
    }
    
    if ( typeof(params.type) === "undefined" ) params.type = sliders_values["slider_type"][jQuery(".slider2#slider_type").slider("value")];

    if ( params.type == "widget" ){
        jQuery(".finish_button.b0").hide();
        jQuery(".finish_button.b1").show();
        jQuery(".b2").show();
        jQuery("p.b0").hide();
        jQuery("p.b12").show();
    } else {
        jQuery(".finish_button.b0").show();
        jQuery(".finish_button.b1").hide();
        jQuery(".b2").hide();
        jQuery("p.b0").show();
        jQuery("p.b12").hide();
    }
        
    if ( params.iconDropShadow === true || params.iconDropShadow === 0 ) params.iconDropShadow = true;
    else params.iconDropShadow = false;
    
    if ( params.labelShadow === true || params.labelShadow === 0 ) params.labelShadow = true;
    else params.labelShadow = false;

    if ( from_template ) {
        params.buttonLabel = jQuery("#button_label").val();
        params.widgetPosition = sliders_values["slider_position"][jQuery(".slider2#slider_position").slider("value")];
        from_template = false; 
    }
    
    if ( typeof(params.poll_id) === "undefined" ) params.poll_id = poll_id;
    if ( typeof(params.analytics_id) === "undefined" ) params.analytics_id = analytics_id;
    
    jQuery(".ZingayaWidget").remove();
    if ( params.type === 1 || params.type === "button" ) { 
        jQuery(".zingayaButton").show();
        params.type = "button";
        Zingaya.load(params, "zingaya"+callme_id); 

        var w = parseInt(jQuery(".zingaya"+callme_id).outerWidth()/2);
        var h = parseInt(jQuery(".zingaya"+callme_id).outerHeight()/2);
        jQuery(".zingaya"+callme_id).css({
            position : "absolute",
            left: "50%",
            top: "50%",
            marginLeft: "-"+w+"px",
            marginTop: "-"+h+"px"
        });
    } else {
        jQuery(".zingayaButton.zingaya"+callme_id).hide();      
        Zingaya.load(params, "zingayaw"+callme_id); 
        var zw = jQuery(".ZingayaWidget").clone();
        jQuery(".ZingayaWidget").remove();
        jQuery(".preview_container").append(zw);
        
        var w = parseInt(jQuery(".ZingayaWidget").outerWidth()/2);
        var h = parseInt(jQuery(".ZingayaWidget").outerHeight()/2);
        var l = "0";
        var r = "auto";
        
        if ( params.widgetPosition === "right" || params.widgetPosition == 0 ) {
            l = "auto";
            r = "0";
        }
        
        jQuery(".ZingayaWidget").css({
            position : "absolute",
            left: l, 
            right: r,
            top: "50%",
            //marginLeft: "-"+w+"px",  
            marginTop: "-"+h+"px",
            zIndex: "9999"
        });
    }
    
    if ( typeof(prev) !== "undefined" && prev ) setConfigData(params);  
}

function moreOnOff(act){
    if ( typeof(act) !== "undefined" ) moreshow = act;

    if ( moreshow ) {
        jQuery(".moreon").show();
        if ( jQuery(".slider2#slider_type").slider("value") === 0 ) jQuery(".moreon2").hide(); else jQuery(".moreon2").show();
        moreshow = false;
    } else {
        jQuery(".moreon").hide();
        jQuery(".moreon2").hide();
        moreshow = true;
        
        getConfigData(); 
    }
}
 
function genButtonCode(id){
    jQuery("#gen_code_button_block").hide(); 
    jQuery("#gen_code_textarea").show();
    
    if ( jQuery(".slider2#slider_type").slider("value") == 0 ) {
        jQuery("#gen_code_info").text("");
    } else {
        jQuery("#gen_code_info").text("");
    }
    jQuery("#gen_code_info").show();
    jQuery("#copy_code").show();
    opencode = true;
    
    getConfigData(true);
}

// РЎРѕР±РёСЂР°РµРј РґР°РЅРЅС‹Рµ РёР· С„РѕСЂРјС‹
function getConfigData(save){
    if ( typeof(save) === "undefined" || !save ) save = 0;
    else save = 1;
    
    ZingayaConfig_ZingayaPreview.save   = save;
    ZingayaConfig_ZingayaPreview.type         = sliders_values["slider_type"][jQuery(".slider2#slider_type").slider("value")];
    //ZingayaConfig_ZingayaPreview.buttonPosition     = sliders_values["slider_position"][jQuery(".slider2#slider_position").slider("value")];
    ZingayaConfig_ZingayaPreview.buttonLabel        = jQuery("#button_label").val();
    ZingayaConfig_ZingayaPreview.iconColor          = jQuery("#iconColor").val(); //sliders_values["slider_icon_style"][jQuery(".slider2#slider_icon_style").slider("value")]; 
    ZingayaConfig_ZingayaPreview.buttonPadding      = sliders_values["slider_padding"][jQuery(".slider23#slider_padding").slider("value")];
    ZingayaConfig_ZingayaPreview.labelFontSize      = sliders_values["slider_font_size"][jQuery(".slider23#slider_font_size").slider("value")];  
    ZingayaConfig_ZingayaPreview.buttonCornerRadius = sliders_values["slider_corner_roundness"][jQuery(".slider23#slider_corner_raundness").slider("value")];
    
    ZingayaConfig_ZingayaPreview.analytics_id = analytics_id;
    ZingayaConfig_ZingayaPreview.poll_id = poll_id;
    
    if ( jQuery(".slider2#slider_icon_shadow").slider("value") == 1 ) {
        ZingayaConfig_ZingayaPreview.iconDropShadow = false; 
    } else {
        ZingayaConfig_ZingayaPreview.iconDropShadow = true;
    }
        
    if ( jQuery(".slider2#slider_label_shadow").slider("value") == 1 ) 
        ZingayaConfig_ZingayaPreview.labelShadow = false; 
    else
        ZingayaConfig_ZingayaPreview.labelShadow = true; 
    
    ZingayaConfig_ZingayaPreview.buttonShadow = jQuery("#buttonShadow").val();
    ZingayaConfig_ZingayaPreview.widgetPosition     = sliders_values["slider_position"][jQuery(".slider2#slider_position").slider("value")];
    ZingayaConfig_ZingayaPreview.buttonGradientColor1 = jQuery("#buttonGradientColor1").val();
    ZingayaConfig_ZingayaPreview.buttonGradientColor2 = jQuery("#buttonGradientColor2").val();
    ZingayaConfig_ZingayaPreview.buttonGradientColor3 = jQuery("#buttonGradientColor3").val();
    ZingayaConfig_ZingayaPreview.buttonGradientColor4 = jQuery("#buttonGradientColor4").val();
    ZingayaConfig_ZingayaPreview.buttonBackgroundColor = jQuery("#buttonGradientColor1").val();
    ZingayaConfig_ZingayaPreview.buttonHoverGradientColor1 = jQuery("#buttonHoverGradientColor1").val();
    ZingayaConfig_ZingayaPreview.buttonHoverGradientColor2 = jQuery("#buttonHoverGradientColor2").val();
    ZingayaConfig_ZingayaPreview.buttonHoverGradientColor3 = jQuery("#buttonHoverGradientColor3").val();
    ZingayaConfig_ZingayaPreview.buttonHoverGradientColor4 = jQuery("#buttonHoverGradientColor4").val();
    ZingayaConfig_ZingayaPreview.labelColor = jQuery("#top_color2").val();
    ZingayaConfig_ZingayaPreview.labelShadowColor = jQuery("#bottom_color2").val();
    ZingayaConfig_ZingayaPreview.buttonActiveShadowColor1 = jQuery("#buttonActiveShadowColor1").val();
    ZingayaConfig_ZingayaPreview.buttonActiveShadowColor2 = jQuery("#buttonActiveShadowColor2").val();

    updateButton(ZingayaConfig_ZingayaPreview, true);
       
    var str = [];
    var wa = false;
    if ( ZingayaConfig_ZingayaPreview.type == "widget" ){
        if ( jQuery("input[name=widget_active]").attr("checked") ) {
            wa = true;
            str[0] = "<h5 style=\"text-align: center; margin-bottom: 0;\"><?php _e("Your widget has been successfully saved <br />and placed on website", "zingaya"); ?></h5>";
        } else {
            str[0] = "<h5 style=\"text-align: center; margin-bottom: 0;\"><?php _e("Your widget has been successfully saved", "zingaya"); ?></h5>";
        }
    } else {
        str[0] = "<h5><?php _e("There are 3 different ways to embed widget onto your website", "zingaya"); ?></h5>";
        str[1] = "<ul><li><?php _e("Enable widget on <a href='/wp-admin/widgets.php'>Widgets</a> page", "zingaya"); ?></li>";
        str[2] = "<li><?php _e("Insert widget shortcode into page content", "zingaya"); ?> <b>[zingaya_widget widget_id="+widget_id+"]</b></li>";
        str[3] = "<li><?php _e("Add <b>zingaya_widget_show", "zingaya"); ?>("+widget_id+");</b> <?php _e("function call in your website template", "zingaya"); ?></li></ul>";
    }
    jQuery("#gen_code_textarea").html(str.join("\n"));
    if ( save ) {
        jQuery.ajax({
            url : save_url,
            data : { callme_id : callme_id, widget : widget_id, params : ZingayaConfig_ZingayaPreview, widget_active : wa },
            success : function(answer){ 
                
            },
            dataType : "json",
            type : "post"
        });
    }
}

// Р“СЂСѓР·РёРј РґР°РЅРЅС‹Рµ РІ С„РѕСЂРјСѓ
function setConfigData(params){ 
    zingaya_loading = true; 
    if ( params.type === "button" || params.type === 1 ) { 
        jQuery(".slider2#slider_type").slider({"value":1}); 
        jQuery("#widget_position").hide(); 
        
        if ( moreshow ){
            jQuery(".moreon2").hide();
        } else {
            jQuery(".moreon2").show(); 
        }
    }
    else {
        jQuery(".slider2#slider_type").slider({"value":0});  
        jQuery("#widget_position").show();
    }
    
    if ( params.widgetPosition === "right" || params.widgetPosition === 1 ) { 
        jQuery(".slider2#slider_position").slider({"value":1}); 
    }
    else { 
        jQuery(".slider2#slider_position").slider({"value":0});  
    }

    jQuery("#button_label").val(params.buttonLabel);

    if ( params.iconDropShadow === 0 || params.iconDropShadow === true ) params.iconDropShadow = 0;
    if ( params.iconDropShadow === 1 || params.iconDropShadow === false ) params.iconDropShadow = 1;
    jQuery(".slider2#slider_icon_shadow").slider({"value":params.iconDropShadow});    
    
    if ( params.labelShadow === 0 || params.labelShadow === true ) params.labelShadow = 0;
    if ( params.labelShadow === 1 || params.labelShadow === false ) params.labelShadow = 1;
    jQuery(".slider2#slider_label_shadow").slider({"value":params.labelShadow});
    
    jQuery(".slider23#slider_padding").slider("value", params.buttonPadding-5);
    jQuery(".slider23#slider_font_size").slider("value", params.labelFontSize-12);
    jQuery(".slider23#slider_corner_raundness").slider("value", params.buttonCornerRadius); 
    
    jQuery('#iconColor').val(params.iconColor);
    jQuery("#iconColor_picker").ColorPickerSetColor(params.iconColor);
    jQuery("#iconColor_picker .preview").css("backgroundColor", params.iconColor);

    jQuery('#buttonGradientColor1').val(params.buttonGradientColor1);
    jQuery("#buttonGradientColor1_picker").ColorPickerSetColor(params.buttonGradientColor1);
    jQuery("#buttonGradientColor1_picker .preview").css("backgroundColor", params.buttonGradientColor1);
    
    jQuery('#top_color2').val(params.labelColor);
    jQuery("#top_color2_picker").ColorPickerSetColor(params.labelColor);
    jQuery("#top_color2_picker .preview").css("backgroundColor", params.labelColor);
    
    jQuery('#buttonGradientColor2').val(params.buttonGradientColor2);
    jQuery("#buttonGradientColor2_picker").ColorPickerSetColor(params.buttonGradientColor2);
    jQuery("#buttonGradientColor2_picker .preview").css("backgroundColor", params.buttonGradientColor2);
    
    jQuery('#buttonGradientColor3').val(params.buttonGradientColor3);
    jQuery("#buttonGradientColor3_picker").ColorPickerSetColor(params.buttonGradientColor3);
    jQuery("#buttonGradientColor3_picker .preview").css("backgroundColor", params.buttonGradientColor3);
    
    jQuery('#buttonGradientColor4').val(params.buttonGradientColor4);
    jQuery("#buttonGradientColor4_picker").ColorPickerSetColor(params.buttonGradientColor4);
    jQuery("#buttonGradientColor4_picker .preview").css("backgroundColor", params.buttonGradientColor4);
    
    jQuery('#bottom_color2').val(params.labelShadowColor);
    jQuery("#bottom_color2_picker").ColorPickerSetColor(params.labelShadowColor);
    jQuery("#bottom_color2_picker .preview").css("backgroundColor", params.labelShadowColor);
    
    jQuery('#buttonHoverGradientColor1').val(params.buttonHoverGradientColor1);
    jQuery("#buttonHoverGradientColor1_picker").ColorPickerSetColor(params.buttonHoverGradientColor1);
    jQuery("#buttonHoverGradientColor1_picker .preview").css("backgroundColor", params.buttonHoverGradientColor1);
    
    jQuery('#buttonHoverGradientColor2').val(params.buttonHoverGradientColor2);
    jQuery("#buttonHoverGradientColor2_picker").ColorPickerSetColor(params.buttonHoverGradientColor2);
    jQuery("#buttonHoverGradientColor2_picker .preview").css("backgroundColor", params.buttonHoverGradientColor2);
    
    jQuery('#buttonHoverGradientColor3').val(params.buttonHoverGradientColor3);
    jQuery("#buttonHoverGradientColor3_picker").ColorPickerSetColor(params.buttonHoverGradientColor3);
    jQuery("#buttonHoverGradientColor3_picker .preview").css("backgroundColor", params.buttonHoverGradientColor3);
    
    jQuery('#buttonHoverGradientColor4').val(params.buttonHoverGradientColor4);
    jQuery("#buttonHoverGradientColor4_picker").ColorPickerSetColor(params.buttonHoverGradientColor4);
    jQuery("#buttonHoverGradientColor4_picker .preview").css("backgroundColor", params.buttonHoverGradientColor4); 
    
    jQuery('#buttonActiveShadowColor1').val(params.buttonActiveShadowColor1);
    jQuery('#buttonActiveShadowColor2').val(params.buttonActiveShadowColor2);
    
    jQuery("#buttonShadow").val(params.buttonShadow);
    
    zingaya_loading = false;
}
</script>
</div>