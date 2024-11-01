<style>
	div.widget.active:before {
            display: inline-block;
            font-family: "dashicons";
            content: "\f147";
            font-size: 24px;
	}

	div.widget.inactive:before {
            display: inline-block;
            font-family: "dashicons";
            content: "\f335";
            font-size: 24px;
	}
        
        .widgets-list-shortcode {
            display: inline-block;
            font-size: 11px;
            white-space: nowrap;
            width: 210px;
            text-align: center;
        }
        div.widget {
            border:0;
            background: none;
        }
</style>
<script>
jQuery(document).ready(function(){
    jQuery("input[name=zingaya_active_widget]").change(function(e){
        var wid = 0;
        if ( jQuery(this).attr("checked") === "checked" ) {
            jQuery("input[name=zingaya_active_widget]:not([data-widget="+jQuery(this).data("widget")+"]):checked").removeAttr("checked");
            wid = jQuery(this).data("widget");
        }
        
        jQuery.ajax({
            url : ajaxurl + '?action=zingaya_active_widget_ajax',
            data : { widget_id : wid },
            success : function(answer){
                
            },
            dataType : "json"
        });
    });
});
</script>
<div class="wrap">

<h2><a href="?page=zingaya/zingaya-admin.php&tab=widgets&action=create" class="add-new-h2"><?php _e("Create Widget", "zingaya"); ?></a></h2>
<form id="widgets_form" method="post">
<?php
	$widgetsTable->prepare_items(); 
	$widgetsTable->display(); 
?>
</form>

</div>
