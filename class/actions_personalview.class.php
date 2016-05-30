<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_personalview.class.php
 * \ingroup personalview
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class Actionspersonalview
 */
class Actionspersonalview
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function formObjectOptions($parameters, &$object, &$action, $hookmanager)
	{
		
		if (in_array('globalcard', explode(':', $parameters['context'])))
		{
			//'js'=>array('/personalview/js/personalview.js.php','/personalview/lib/colorPicker/jqColorPicker.min.js')
			
			global $langs;
			
			$langs->load('personalview@personalview');
			
			echo '<div class="inline-block" id="personalviewbuttons" style="display:none;">';
			echo '<a rel="edit" href="javascript:personalview.edit();">'.img_picto($langs->trans('EditView'), 'personalview@personalview',' style="width:16px;" ').'</a>';
			echo '<a rel="running" href="javascript:personalview.save();">'.img_picto($langs->trans('EditViewRunning'), 'personalview-edit@personalview',' style="width:16px;" ').'</a>';
			echo '<a rel="exist" href="javascript:personalview.edit();">'.img_picto($langs->trans('EditViewSaved'), 'personalview-saved@personalview',' style="width:16px;" ').'</a>';
			echo '</div>';
			
			?>
			<script src="<?php echo dol_buildpath('/personalview/lib/colorPicker/jqColorPicker.min.js',1); ?>" type="text/javascript"></script>
			<script type="text/javascript">
				
				$(document).ready(function() {
					$('#personalviewbuttons').prependTo('div.login_block div.login_block_other').show();
						
					<?php
					
					define('INC_FROM_DOLIBARR',1);
					dol_include_once('/personalview/config.php');
					dol_include_once('/personalview/class/ps.class.php');
					
					$PDOdb=new TPDOdb;
					$ps=new TPersonalView;
					if($ps->loadByElementAction($PDOdb, $object->element, $action) && !empty($ps->TField)) {
						
						?>
						$('#personalviewbuttons [rel=running],#personalviewbuttons [rel=edit]').hide();
					
						$('div.fiche:first table.border').not('table table,.ui-dialog,.confirmquestions').each(function(it, table) {
								$table = $(table);
							console.log($table, $table.parent(),it);
								$table.attr('pview-table', it);
								$table.addClass('PSTable');
						
								$table.find('>tbody>tr').each(function(i, item) {
									var $item = $(item);
									$item.attr('pview-row', i);
								});
						});
						<?php
						
						foreach($ps->TField as &$row) {
							$iTable = $row['iTable'];
							$iRow = $row['iRow'];
								
							if(!empty($row['bold'])) echo '$("table[pview-table='.$iTable.'] tr[pview-row='.$iRow.']").addClass("PSBolder");';
							if(!empty($row['hide'])) echo '$("table[pview-table='.$iTable.'] tr[pview-row='.$iRow.']").addClass("PSHidden");';
							if(!empty($row['color'])) {echo '$("table[pview-table='.$iTable.'] tr[pview-row='.$iRow.']").addClass("PSColor").attr("ps-color","'.$row['color'].'").css("background-color","#'.$row['color'].'");';
							
							}
						}
						
					}
					else{
						?>
						$('#personalviewbuttons').prependTo('div.login_block div.login_block_other').show();
						$('#personalviewbuttons [rel=running],#personalviewbuttons [rel=exist]').hide();
					
						<?php	
					}
											
					?>
					
				});
				
				var personalview = {
					
					hide : function(iTable, iRow) {
						$tr = $('table[pview-table='+iTable+'] tr[pview-row='+iRow+']')
						
						if($tr.hasClass('PSNotReallyHide')) {
							$tr.removeClass('PSNotReallyHide');	
						}
						else{
							$tr.addClass('PSNotReallyHide');
						}
						
						
					}
					,highLight : function(iTable, iRow) {
						$tr = $('table[pview-table='+iTable+'] tr[pview-row='+iRow+']')
						
						if($tr.hasClass('PSBolder')) {
							$tr.removeClass('PSBolder');	
						}
						else{
							$tr.addClass('PSBolder');
						}
						
					}
					,remove :function() {
						$("tr.PSNotReallyHide").removeClass("PSNotReallyHide");
						$("tr.PSBolder").removeClass("PSBolder");
						$("tr.PSColor").removeClass("PSColor").css({"background-color":"none"}).removeAttr("ps-color");
					
						personalview.save();
					}
					,save : function() {
						$('table[pview-table] tr').unbind('mouseenter').unbind('mouseleave');
						$('#personalviewbuttons [rel=running],#personalviewbuttons [rel=edit]').hide();
						$('#personalviewbuttons [rel=exist]').show();
						$('.PSCanEdit').remove();
						
						$('.PSNotReallyHide').addClass('PSHidden').removeClass('PSNotReallyHide');
						
						TField = [];
						$('table[pview-table]').each(function(it, table) {
							
							$(table).find('tr[pview-row]').each(function(i,item) {
								$item = $(item);
								//console.log($item,it,i);
								var row = { iTable : it, iRow : i, color:'',hide:0,bold:0  };
								
								if($item.hasClass('PSBolder')) row.bold = 1;
								if($item.hasClass('PSHidden')) row.hide = 1;
								if($item.hasClass('PSColor')) row.color = $item.attr('ps-color');
								
								TField.push(row);		
							});
						
						});
						
						$.ajax({
							url:"<?php echo dol_buildpath("/personalview/script/interface.php",1) ?>"
							,data: {
								put:'view'
								,element:"<?php echo $object->element ?>"
								,action:"<?php echo $action ?>"
								,TField:TField
							}
							,method:"post"
						});
						
					}
					,edit : function() {
						$('.PSHidden').addClass('PSNotReallyHide').removeClass('PSHidden');
						
						$('#personalviewbuttons [rel=exist],#personalviewbuttons [rel=edit]').hide();
						$('#personalviewbuttons [rel=running]').show();
						
						$('div.fiche:first table.border').not('table table').each(function(it, table) {
						var $table = $(table);
						
						$table.before('<div class="PSCanEdit"><?php echo '<div class="buttons">'
								.' <a href="javascript:personalview.save();">'.img_picto($langs->trans('SaveView'),'ok@personalview').'</a>' 
								.' <a href="javascript:personalview.remove();">'.img_picto($langs->trans('RemoveView'),'ko@personalview').'</a>'
								.'</div>'.$langs->trans('YouCanEditThisTable');
								?></div>');
						
						if($table.hasClass('nobordernopadding')) {
							$('table').css({
								'border':'1px dashed #ccc'
							});
						}
						
						$table.attr('pview-table', it);
						$table.addClass('PSTable');
						
						$table.find('>tbody>tr').each(function(i, item) {
							var $item = $(item);
							$item.attr('pview-row', i);
						
							var $actions = $('<div class="PSActions" rel="personnal-view-data"></div>');
							$actions.append('<a rel="hide" href="javascript:personalview.hide('+it+','+i+')"><?php echo img_picto($langs->trans('HideOrNot'), 'personalview@personalview'); ?></a>');
							$actions.append('<a href="javascript:personalview.highLight('+it+','+i+')"><?php echo img_picto($langs->trans('HighLight'), 'bold@personalview'); ?></a>');
							
							//$actions.append('<input type="text" pview-table="'+it+'" pview-row="'+i+'" id="color_'+it+'_'+i+'" value="" class="color" size="2" />');
							$actions.append('<a href="javascript:;" pview-table="'+it+'" pview-row="'+i+'" id="color_'+it+'_'+i+'"><?php echo img_picto($langs->trans('PickColor'), 'color@personalview'); ?></a>');
							$('#color_'+it+'_'+i).colorPicker({
								renderCallback:function($elm, toggled) {
									
									var it = $elm.attr('pview-table');
									var i = $elm.attr('pview-row');
									
									var colors = this.color.colors;
									
									var $tr = $('table[pview-table='+it+'] tr[pview-row='+i+']');
									if(colors=='ffffff' ) {
										$tr.removeClass('PSColor');
									}
									else{
										$tr.css('background-color','#'+ colors.HEX);
										$tr.addClass("PSColor");
										$tr.attr('ps-color',colors.HEX);console.log(colors.HEX);
									}
									
									
									
								}
							});
							
							
							
							$actions.find('a').attr('pview-row', i);
							
							$item.mouseenter(function() {
								
								var o = $item.offset();
								
								$item.find('td').first().append($actions);
								
								
							});
							
							$item.mouseleave(function() {
							var o = $item.offset();
									$item.find('div.PSActions').remove();
							});
						});
						
					});
					
					
						
					}
					
				}

				
			</script>
			<?php
		}
	}
}