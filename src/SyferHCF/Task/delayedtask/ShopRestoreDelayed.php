<?php

namespace SyferHCF\Task\delayedtask;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\shop\Shop;

use pocketmine\tile\{Tile, Sign};
use pocketmine\item\Item;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class ShopRestoreDelayed extends Task {
	
	/** @var Tile */
    protected $tile;
    
    /** @var Shop */
    protected $shop;
	
	/**
	 * ShopRestoreDelayed Constructor.
	 * @param Tile $tile
     * @param Shop $shop
	 */
	public function __construct(Tile $tile, Shop $shop){
        $this->tile = $tile;
        $this->shop = $shop;
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
        $tile = $this->tile;
        $shop = $this->shop;

        $item = Item::get($shop->getId(), $shop->getDamage(), $shop->getAmount());
        if($tile instanceof Sign){
            try {
                if($shop->getType() === \SyferHCF\listeners\interact\Shop::BUY){
                    $tile->setLine(0, TE::GREEN."[Shop]");
                    $tile->setLine(1, TE::BLACK.$item->getName());
                    $tile->setLine(2, TE::BLACK."x".$shop->getAmount());
                    $tile->setLine(3, TE::BLACK."$".$shop->getPrice());
                    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
                }elseif($shop->getType() === \SyferHCF\listeners\interact\Shop::SELL){
                    $tile->setLine(0, TE::RED."[Sell]");
                    $tile->setLine(1, TE::BLACK.$item->getName());
                    $tile->setLine(2, TE::BLACK."x".$shop->getAmount());
                    $tile->setLine(3, TE::BLACK."$".$shop->getPrice());
                    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
                }
            }catch(\Exception $exception){
                Loader::getInstance()->getLogger()->error($exception->getMessage());
            }
        }
        Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
	}
}

?>
