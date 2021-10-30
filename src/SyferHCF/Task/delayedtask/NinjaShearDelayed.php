<?php

namespace SyferHCF\Task\delayedtask;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\math\Vector3;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class NinjaShearDelayed extends Task {
	
	/** @var Player */
	protected $player;
	
	/** @var Vector3 */
	protected $position;
	
	/**
	 * NinjaShearDelayed Constructor.
	 * @param Player $player
	 * @param Vector3 $position
	 */
	public function __construct(Player $player, Vector3 $position){
		$this->player = $player;
		$this->position = $position;
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		$player = $this->player;
		$position = $this->position;
		if(!$player->isOnline()){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        $player->teleport($position);
        Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
	}
}

?>
