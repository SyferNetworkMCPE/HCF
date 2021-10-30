<?php

namespace SyferHCF\Task;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class OpArcherTask extends Task {

    /**
     * OpArcherTask Constructor.
     */
    public function __construct(){

    }
    
    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
            if($player->isOpArcherClass() && $player->getOpArcherEnergy() < 80) $player->setOpArcherEnergy($player->getOpArcherEnergy() + 1);
        }
    }
}
           
            

?>
