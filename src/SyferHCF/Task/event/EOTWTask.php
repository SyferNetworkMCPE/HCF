<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\EOTW;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class EOTWTask extends Task {
	
	/**
	 * EOTWTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		EOTW::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!EOTW::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(EOTW::getTime() === 0){
			EOTW::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			EOTW::setTime(EOTW::getTime() - 1);
		}
	}
}

?>
