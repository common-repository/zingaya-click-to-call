<style>
	div.record.listened:before {
		display: inline-block;
		font-family: "dashicons";
    	content: "\f147";
    	font-size: 24px;
	}

	div.record.available:before {
		display: inline-block;
		font-family: "dashicons";
    	content: "\f500";
    	font-size: 24px;
	}
</style>
<div class="wrap">
<form id="voicemail_form" method="post">
<?php
	$voicemailTable->prepare_items(); 
	$voicemailTable->display(); 
?>
</form>
</div>
