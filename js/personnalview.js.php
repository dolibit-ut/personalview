<?php

	require '../config.php';
?>
$(document).ready(function() {
	
	$('table tr').each(function(i, item) {
		var $item = $(item);
	
		var $actions = $('<div class="actions"></div>');
		$actions.append('<a href="javascript:personnalView.hideThis('+i+')"><?php echo $langs->trans('Hide'); ?></a>');
		
		$item.attr('pview-row', i);
		
		$actions.find('a').attr('pview-row', i);
		
		$item.mouseenter(function() {
			
			var o = $item.offset();
			
			$item.find('td:first').append($actions);
			
			
		});
		
		$item.mouseleave(function() {
		var o = $item.offset();
				$item.find('div.actions').remove();
		});
	});
	
});

var personnalView = {
	
	hideThis : function(pviewRow) {
		$('tr[pview-row='+pviewRow+']').hide();
		console.log(pviewRow,$('tr[pview-row='+pviewRow+']'));
	}
	
	
}

