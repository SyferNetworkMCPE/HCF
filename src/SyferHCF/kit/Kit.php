<?php

namespace SyferHCF\kit;

class Kit {
	
	/** @var Array[] */
	protected $items = [];

	/** @var Array[]  */
	protected $armorItems = [];
	
	/** @var String */
	protected $name;

    /** @var String */
	protected $permission;

    /** @var String */
	protected $nameFormat;

    /**
     * Kit Constructor.
     * @param String $name
     * @param array $items
     * @param array $armorItems
     * @param String $permission
     * @param String $nameFormat
     */
	public function __construct(String $name, Array $items, Array $armorItems, String $permission, String $nameFormat){
		$this->name = $name;
		$this->items = $items;
		$this->armorItems = $armorItems;
		$this->permission = $permission;
		$this->nameFormat = $nameFormat;
	}
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return $this->name;
	}

	/**
	 * @param String $name
	 */
	public function setName(String $name){
		$this->name = $name;
	}
	
	/**
	 * @return Array[]
	 */
	public function getItems() : Array {
		return $this->items;
	}

	/**
	 * @param Array $items
	 */
	public function setItems(Array $items){
		$this->items = $items;
	}
	
	/**
	 * @return Array[]
	 */
	public function getArmorItems() : Array {
		return $this->armorItems;
	}

	/**
	 * @param Array $armorItems
	 */
	public function setArmorItems(Array $armorItems){
		$this->armorItems = $armorItems;
	}

	/**
	 * @return String
	 */
	public function getPermission() : String {
		return $this->permission;
	}

	/**
	 * @param String $permission
	 */
	public function setPermission(String $permission){
		$this->permission = $permission;
	}
	
	/**
	 * @return String
	 */
	public function getNameFormat() : String {
		return str_replace("&", "§", $this->nameFormat);
	}

	/**
	 * @param String $nameFormat
	 */
	public function setNameFormat(String $nameFormat){
		$this->nameFormat = $nameFormat;
	}
}

?>
