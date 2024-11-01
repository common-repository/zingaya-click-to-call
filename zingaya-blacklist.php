<script type="text/javascript">
	function addIpRequest() {
		var ip = document.getElementById("ipAddress").value;
		ip = ip.split(".");
		if (ip.length == 4) {
			if ((parseInt(ip[0]) >= 0 && parseInt(ip[0]) <= 255) &&
				(parseInt(ip[1]) >= 0 && parseInt(ip[1]) <= 255) &&
				(parseInt(ip[2]) >= 0 && parseInt(ip[2]) <= 255) &&
				(parseInt(ip[3]) >= 0 && parseInt(ip[3]) <= 255)) {
				document.getElementById("blackListForm").submit();
			} else alert("<?php _e('Wrong IP address specified', 'zingaya'); ?>");		
		} else alert("<?php _e('Wrong IP address specified', 'zingaya'); ?>");		
		return false;
	}
</script>
<div class="wrap">
<form id="blackListForm" name="blackListForm" method="POST">
<label for="ip_address"><?php _e("IP address", "zingaya"); ?></label><input name="ip" id="ipAddress" type="text" /><h2><a href="javascript:void(0);" id="addIpButton" onclick="addIpRequest()" class="add-new-h2"><?php _e("Add to the list", "zingaya"); ?></a></h2>
<input type="hidden" name="action" value="add_ip" />
</form>

<form id="blacklist_form" method="post">
<?php
	$blacklistTable->prepare_items(); 
	$blacklistTable->display(); 
?>
</form>
</div>
