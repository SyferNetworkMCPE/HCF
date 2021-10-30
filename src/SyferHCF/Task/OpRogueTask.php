<?php

namespace SyferHCF\Task;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class OpRogueTask extends Task {

    /**
     * OpRogueTask Constructor.
     */
    public function __construct(){
        
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
            if(!$player instanceof Player && count(Loader::getInstance()->getServer()->getOnlinePlayers()) < 0) return;
            if($player->isBackStap()){
                if($player->getOpBackstapTime() === 12){
                    $player->setOpBackstap(false);
                }else{
                    $player->setOpBackstapTime($player->getOpBackstapTime() - 12);
                }
            }
        }
    }
}

?>
