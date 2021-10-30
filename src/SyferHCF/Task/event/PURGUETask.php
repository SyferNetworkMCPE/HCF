<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\PURGUE;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class PURGUETask extends Task {
	
	/**
	 * PURGUETask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		PURGUE::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!PURGUE::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(PURGUE::getTime() === 0){
			PURGUE::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			PURGUE::setTime(PURGUE::getTime() - 1);
		}
	}
}

?>
