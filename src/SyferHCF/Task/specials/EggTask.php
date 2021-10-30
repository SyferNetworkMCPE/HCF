<?php

namespace SyferHCF\Task\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class EggTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * EggTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setEggTime(Loader::getDefaultConfig("Cooldowns")["EggPorts"]);
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        $player = $this->player;
        if(!$player->isOnline()){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        if($player->getEggTime() === 0){
            $player->setEgg(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setEggTime($player->getEggTime() - 1);
        }
    }
}

?>
