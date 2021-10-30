<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\KEYALL;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class KEYALLTask extends Task {
	
	/**
	 * KEYALLTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		KEYALL::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!KEYALL::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(KEYALL::getTime() === 0){
			KEYALL::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			KEYALL::setTime(KEYALL::getTime() - 1);
		}
	}
}

?>
