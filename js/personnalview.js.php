<?php

	require '../config.php';
?>
$(document).ready(function() {
	
	$('table').each(function(it, table) {
		var $table = $(table);
		
		$table.attr('pview-table', it);
		console.log($table.children());
		$table.find('tr').each(function(i, item) {
			var $item = $(item);
		
			var $actions = $('<div class="actions"></div>');
			$actions.append('<a href="javascript:personnalView.hide('+it+','+i+')"><?php echo $langs->trans('Hide'); ?></a>');
			$actions.append('<a href="javascript:personnalView.highLight('+it+','+i+')"><?php echo $langs->trans('HighLight'); ?></a>');
			
			$actions.append('<input type="text" id="color_'+it+'_'+i+'" value="" class="color" />');
			$('#color_'+it+'_'+i).colorPicker({
				renderCallback:function($elm, toggled) {
					var colors = this.color.colors;
					$('table[pview-table='+it+'] tr[pview-row='+i+']').css('background-color','#'+ colors.HEX);
					
				}
			});
			
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
	
	
});

var personnalView = {
	
	hide : function(iTable, iRow) {
		$('table[pview-table='+iTable+'] tr[pview-row='+iRow+']').hide();
	}
	,highLight : function(iTable, iRow) {
		$('table[pview-table='+iTable+'] tr[pview-row='+iRow+']').css('font-weight', 'bold');
	}
	
	
}

