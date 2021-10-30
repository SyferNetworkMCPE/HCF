<?php

namespace SyferHCF\Task\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\AIRDROP;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class AIRDROPTask extends Task {
	
	/**
	 * AIRDROPTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		AIRDROP::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!AIRDROP::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(AIRDROP::getTime() === 0){
			AIRDROP::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			AIRDROP::setTime(AIRDROP::getTime() - 1);
		}
	}
}

?>
