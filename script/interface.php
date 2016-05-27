<?php

	require '../config.php';
	
	dol_include_once('/personnalview/class/ps.class.php');

		$put = GETPOST('put');
		
		_put($put);
		
function _put($put) {
	$PDOdb = new TPDOdb;
	
	switch ($put) {
		case 'view':
			$ps =new TPersonnalView;
			$ps->loadByElementAction($PDOdb, GETPOST('element'),  GETPOST('action'));
			
			$ps->element = GETPOST('element');
			$ps->action = GETPOST('action');
			$ps->TField = GETPOST('TField','array');
			
			echo $ps->save($PDOdb);
			
			break;
		
	}
	
}
