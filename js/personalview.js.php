<?php

	require '../config.php';
?>
$(document).ready(function() {
	
	
});

var personalview = {
	
	hide : function(iTable, iRow) {
		$('table[pview-table='+iTable+'] tr[pview-row='+iRow+']').hide();
	}
	,highLight : function(iTable, iRow) {
		$('table[pview-table='+iTable+'] tr[pview-row='+iRow+']').css('font-weight', 'bold');
	}
	
	
}

