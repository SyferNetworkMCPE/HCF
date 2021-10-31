<?php

namespace SyferHCF\API\InvMenu;

use SyferHCF\player\Player;

use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\tile\{Tile, Chest};

use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\ContainerInventory;

use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;

abstract class InvHandler extends ContainerInventory implements WindowTypes {
	
	/** @var Vector3 */
	protected $position;
	
	/**
	 * InvHandler Constructor.
	 */
	public function __construct(){
		BaseInventory::__construct([], 0, "");
        $this->setName($this->getBlock()->getName());
        $this->setSize($this->getDefaultSize());
	}
	
	/**
	 * @return Block
	 */
	public function getBlock() : Block {
		return Block::get($this->getBlockId());
	}
	
	/**
	 * @param String $title
	 */
	public function setName(String $title){
		$this->title = $title;
	}
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return $this->title;
	}
	
	/**
	 * @return Int
	 */
	abstract public function getBlockId() : int;
	
	/**
	 * @param Vector3 $holder
	 */
	public function setPosition(Vector3 $holder){
		$this->holder = $holder;
	}
	
	/**
	 * @return Vector3
	 */
	public function getPosition() : Vector3 {
		return $this->holder;
	}
	
	/**
	 * @param Player $player
	 * @return void
	 */
	public function openInventory(Player $player) : void {
		$inventory = clone $this;
		$position = new Vector3($player->getFloorX(), $player->getFloorY() + 5, $player->getFloorZ());
		$inventory->setPosition($position);
	
		$handler = new Handler();
		$handler->setMenuHandler($inventory);
	
		self::sendFakeBlock($player, $handler->getMenuHandler()->getPosition(), $handler->getMenuHandler()->getBlock());
	
		$player->setInventoryHandler($handler);
		$player->addWindow($handler->getMenuHandler());
	}
	
	/**
	 * @param Player $player
	 * @param Vector3 $position
	 *@param Block $block
	 */
	public static function sendFakeBlock(Player $player, Vector3 $position, Block $block){
		$pk = new UpdateBlockPacket();
		$pk->x = (int)$position->x;
		$pk->y = (int)$position->y;
		$pk->z = (int)$position->z;
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$pk->blockRuntimeId = $block->getRuntimeId();
        $player->dataPacket($pk);
    }
    
    /**
     * @param Player $player
     * @return void
     */
    public function removeFakeBlock(Player $player) : void {
    	$inventory = $player->getInventoryHandler();
		self::sendFakeBlock($player, $inventory->getMenuHandler()->getPosition(), Block::get(Block::AIR));
    }
}

?>
