<style>
    .zingaya-help img {
        border: 3px dotted #0074A2;
    }
    .zingaya-help {
        background-color: #ffffff;
        padding: 20px;
    }
</style>
<div style="display: inline-block; padding: 10px 20px; background-color: #ffffff; margin-top: 20px;">
<h2 style="margin-top: 0;"><?php _e("Contents", "zingaya"); ?></h2>
<ol>
    <li><a href="#part1"><?php _e("Creating/editing Zingaya widget", "zingaya"); ?></a></li>
    <li><a href="#part2"><?php _e("Customizing look&feel", "zingaya"); ?></a></li>
    <li><a href="#part3"><?php _e("Embedding widget", "zingaya"); ?></a></li>
    <li><a href="#part4"><?php _e("Call history", "zingaya"); ?></a></li>
    <li><a href="#part5"><?php _e("Features", "zingaya"); ?></a></li>
    <li><a href="#part6"><?php _e("Payment", "zingaya"); ?></a></li>
</ol>
</div>
<div class="zingaya-help">
<h2><?php _e("Creating/editing Zingaya widget", "zingaya"); ?><a name="part1">&nbsp;</a></h2>
<p><?php _e("Please see Zingaya widget form description below.", "zingaya"); ?></p>
<h3><?php _e("Widget name", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_name.png', __FILE__); ?>" />
    <br />
    <?php _e("Just name of your wiget - some string.", "zingaya"); ?>
</p>
<h3><?php _e("Is active", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_active.png', __FILE__); ?>" />
    <br />
    <?php _e("This field is available while widget editing only, enable/disable switch. Calls won't go through disabled widget.", "zingaya"); ?>
</p>
<h3><?php _e("Phone number", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_phone.png', __FILE__); ?>" />
    <br />
    <?php _e("Calls through widget will be forwarded to the specified phone number.", "zingaya"); ?>
</p>
<h3><?php _e("Working hours", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_hours.png', __FILE__); ?>" />
    <br />
    <?php _e("This feature allows you to choose the times of day that web calls will be forwarded to your phone (for each day of the week). Customers who call you outside of these hours can leave you a voicemail message.", "zingaya"); ?>
</p>
<h3><?php _e("Voicemail", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_voicemail.png', __FILE__); ?>" />
    <br />
    <?php _e("Record and listen to the conversations between your employees and your customers to evaluate the quality of your customer service, or to better learn what common questions your customers are having. Call records are stored for 30 days.", "zingaya"); ?>
</p>
<h3><?php _e("Call recording", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_record.png', __FILE__); ?>" />
    <br />
    <?php _e("Record and listen to the conversations between your employees and your customers to evaluate the quality of your customer service, or to better learn what common questions your customers are having. Call records are stored for 30 days.", "zingaya"); ?>
</p>
<h3><?php _e("Google analytics", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_ga.png', __FILE__); ?>" />
    <br />
    <?php _e("Data is sent to Google Analytics as events under Zingaya category. It helps to track increase in online conversion for visitors who made online call and check efficiency of marketing and advertising channels.", "zingaya"); ?>
</p>
<h3><?php _e("DTMF Keypad", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_dtmf.png', __FILE__); ?>" />
    <br />
    <?php _e("The keypad allows callers to enter the extension number of a particular employee or a required IVR menu option.", "zingaya"); ?>
</p>
<hr />

<h2><?php _e("Customizing look&feel", "zingaya"); ?><a name="part2">&nbsp;</a></h2>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_designer.png', __FILE__); ?>" />
</p>
<p>
    <?php _e("Call button look and feel can be customized in Zingaya control panel.", "zingaya"); ?>
</p>
<h3><?php _e("Widget look&feel preview", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_preview.png', __FILE__); ?>" />
    <br />
    <?php _e("You can see how your widget will look on your website.", "zingaya"); ?>
</p>
<h3><?php _e("Buttons area", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_buttons.png', __FILE__); ?>" />
    <br />
    <?php _e("`Choose preset` button lets you choose one of the presets from the library.", "zingaya"); ?>
    <?php _e("`More settings` show additional graphical settings.", "zingaya"); ?>
</p>
<h3><?php _e("Widget form", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_type.png', __FILE__); ?>" />
    <br />
    <?php _e("Two options are available - widget (floating button) or button.", "zingaya"); ?>
</p>
<h3><?php _e("Widget visual settings", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_settings.png', __FILE__); ?>" />
    <br />
    <?php _e("You can adjust visual settings using this section. Advanced mode lets you adjust additional visual settings like icon shadow, etc.", "zingaya"); ?>
</p>
<h3><?php _e("Saving settings", "zingaya"); ?></h3>
<p>
    <?php _e("Two options are available depending on the widget type.", "zingaya"); ?>
</p>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_savewidget.png', __FILE__); ?>" />
    <br />
    <?php _e("If `Widget` type was chosen, you can use `Place on website` checkbox to show the widget on every page of your website.", "zingaya"); ?>
</p>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_savebutton.png', __FILE__); ?>" />
    <br />
    <?php _e("If `Button` type was chosen, just click on `Save and show embed code` to get the code and embedding instructions.", "zingaya"); ?>
</p>
<hr />

<h2><?php _e("Embedding widget", "zingaya"); ?><a name="part3">&nbsp;</a></h2>
<h3><?php _e("Embedding instructions for `Widget` type from `Widget visual settings`", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_savewidget.png', __FILE__); ?>" />
    <br />
    <?php _e("To automatically embed widget on all pages of your website you need to enable `Place on website` checkbox before clicking `Save widget` button. You can have only one active widget of this type (widget) on your website.", "zingaya"); ?>
</p>
<h3><?php _e("Embedding instructions for `Widget` type from `Widgets list`", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widgets_save.png', __FILE__); ?>" />
    <br />
    <?php _e("Just click `Widget is active` checkbox to place the widget on your website. You can have only one active widget of this type (widget) on your website.", "zingaya"); ?>
</p>
<h3><?php _e("Embedding instructions for `Button` type", "zingaya"); ?></h3>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_shortcode.png', __FILE__); ?>" />
</p>
<p>
    <?php _e("Just copy short code from `Widgets list` or `Widget visual settings` and paste it into wordpress page content.", "zingaya"); ?>
</p>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_page.png', __FILE__); ?>" />
</p>
<h3><?php _e("Embedding instructions for `Button` type using Wordpress Widget", "zingaya"); ?></h3>
<p>
    <?php _e("You can also use standard Wordpress Widgets mechanism to add button on your website.", "zingaya"); ?>
</p>
<p>
    <img src="<?php echo plugins_url('/images/help/widget_widget.png', __FILE__); ?>" />
</p>
<hr />

<h2><?php _e("Call history", "zingaya"); ?><a name="part4">&nbsp;</a></h2>
<p><?php _e("Info about all calls via your widgets is available in call history.", "zingaya"); ?></p>

<hr />

<h2><?php _e("Features", "zingaya"); ?><a name="part5">&nbsp;</a></h2>
<h3><?php _e("Voicemail", "zingaya"); ?></h3>
<p><?php _e("You can listen to voicemail messages left by your customers called you during non-working hours.", "zingaya"); ?></p>

<h3><?php _e("IP Blacklist", "zingaya"); ?></h3>
<p><?php _e("Zingaya allows manual filtering by a caller’s IP address.", "zingaya"); ?></p>

<h3><?php _e("Notifications", "zingaya"); ?></h3>
<p><?php _e("Notification settings (SMS/Email)", "zingaya"); ?></p>

<hr />

<h2><?php _e("Payment", "zingaya"); ?><a name="part6">&nbsp;</a></h2>
<p><?php _e("You can pay for the service in Zingaya Control Panel", "zingaya"); ?></p>
</div>