<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\FFA;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class FFATask extends Task {
	
	/**
	 * FFATask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		FFA::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!FFA::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(FFA::getTime() === 0){
			FFA::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			FFA::setTime(FFA::getTime() - 1);
		}
	}
}

?>
