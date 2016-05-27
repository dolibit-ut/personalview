<?php

	require '../config.php';
?>
$(document).ready(function() {
	
	
});

var personnalView = {
	
	hide : function(iTable, iRow) {
		$('table[pview-table='+iTable+'] tr[pview-row='+iRow+']').hide();
	}
	,highLight : function(iTable, iRow) {
		$('table[pview-table='+iTable+'] tr[pview-row='+iRow+']').css('font-weight', 'bold');
	}
	
	
}

