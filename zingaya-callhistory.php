<style>
	div.call.successful:before {
            display: inline-block;
            font-family: "dashicons";
            content: "\f147";
            font-size: 24px;
	}

	div.call.unsuccessful:before {
            display: inline-block;
            font-family: "dashicons";
            content: "\f335";
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
<form id="callhistory_form" method="post">
<?php
	$callhistoryTable->prepare_items(); 
	$callhistoryTable->display(); 
?>
</form>
</div>
