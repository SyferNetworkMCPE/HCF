<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\EVENT;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class EVENTTask extends Task {
	
	/**
	 * EVENTTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		EVENT::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!EVENT::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(EVENT::getTime() === 0){
			EVENT::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			EVENT::setTime(EVENT::getTime() - 1);
		}
	}
}

?>
