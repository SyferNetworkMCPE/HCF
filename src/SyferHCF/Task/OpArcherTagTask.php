<?php

namespace SyferHCF\Task;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class OpArcherTagTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * OpArcherTagTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setOpArcherTagTime(Loader::getDefaultConfig("Cooldowns")["OpArcherTag"]);
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
        if(!$player->isOpArcherTag()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if($player->getOpArcherTagTime() === 0){
            $player->setOpArcherTag(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setOpArcherTagTime($player->getOpArcherTagTime() - 1);
        }
    }
}

?>
