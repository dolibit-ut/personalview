<?php

class TPersonalView extends TObjetStd {
	
	function __construct() {
		
		$this->set_table(MAIN_DB_PREFIX . 'personal_view');
		$this->add_champs('TField',array('type'=>'array'));
		$this->add_champs('element,action',array('type'=>'string','length'=>30, 'index'=>true));
		$this->add_champs('fk_group,fk_user',array('type'=>'integer', 'index'=>true));
		
		$this->_init_vars();
		$this->start();
		
	}
	
	function loadByElementAction(&$PDOdb, $element, $action) {
			
		$PDOdb->Execute("SELECT rowid FROM ".$this->get_table()." 
			WHERE element=".$PDOdb->quote( $element )." AND action=".$PDOdb->quote( $action ));
		if($obj = $PDOdb->Get_line()) {
			return $this->load($PDOdb, $obj->rowid);
		}
		
		
	}
	
}
