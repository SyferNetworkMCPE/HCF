<?php

namespace SyferHCF\Task;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class MageTask extends Task {

    /**
     * MageTask Constructor.
     */
    public function __construct(){

    }
    
    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
            if ($player->isMageClass() && $player->getMageEnergy() < 90) {
            $player->setMageEnergy($player->getMageEnergy() + 1);
            }
        }
    }
}
?>
