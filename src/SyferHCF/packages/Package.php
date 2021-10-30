<?php

namespace SyferHCF\packages;

class Package {

    /** @var Array[] */
    protected $items = [];

    /**
     * Package Constructor.
     * @param Array $items
     */
    public function __construct(Array $items){
        $this->items = $items;
    }

    /**
	 * @return Array[]
	 */
	public function getItems() : Array {
		return $this->items ?? [];
	}

	/**
	 * @param Array $items
	 */
	public function setItems(Array $items){
		$this->items = $items;
	}
}

?>
