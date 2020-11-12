<?php

	require '../config.php';
	
	dol_include_once('/personalview/class/ps.class.php');

		$put = GETPOST('put');
		
		_put($put);
		
function _put($put) {
	$PDOdb = new TPDOdb;
	
	switch ($put) {
		case 'view':
			$ps =new TPersonalView;
			$ps->loadByElementAction($PDOdb, GETPOST('element','alpha'),  GETPOST('action','alpha'));
			
			$ps->element = GETPOST('element','alpha');
			$ps->action = GETPOST('action','alpha');
			$ps->TField = GETPOST('TField','array');
			pre($ps->TField,1);
			echo $ps->save($PDOdb);
			
			break;
		
	}
	
}
