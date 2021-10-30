<?php

namespace SyferHCF\Task;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class ArcherTask extends Task {

    /**
     * ArcherTask Constructor.
     */
    public function __construct(){

    }
    
    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
            if($player->isArcherClass() && $player->getArcherEnergy() < 60) $player->setArcherEnergy($player->getArcherEnergy() + 1);
        }
    }
}
           
            

?>
