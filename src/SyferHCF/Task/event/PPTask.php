<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\PP;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class PPTask extends Task {
	
	/**
	 * PPTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		PP::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!PP::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(PP::getTime() === 0){
			PP::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			PP::setTime(PP::getTime() - 1);
		}
	}
}

?>
