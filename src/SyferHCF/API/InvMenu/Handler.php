<?php

namespace SyferHCF\API\InvMenu;

class Handler {
	
	/** @var InvHandler */
	protected $inventory;
	
	/**
	 * Handler Constructor.
	 */
	public function __construct(){
		
	}
	
	/**
	 * @param InvHandler $inventory
	 */
	public function setMenuHandler(InvHandler $inventory){
		$this->inventory = $inventory;
	}
	
	/**
	 * @return InvHandler
	 */
	public function getMenuHandler() : InvHandler {
		return $this->inventory;
	}
}

?>
