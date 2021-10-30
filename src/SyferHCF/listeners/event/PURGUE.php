<?php

namespace SyferHCF\listeners\event;

use SyferHCF\Loader;
use SyferHCF\player\Player;
use SyferHCF\utils\Time;

use SyferHCF\Task\event\PURGUETask;

use pocketmine\event\Listener;

class PURGUE implements Listener {
	
	/** @var Loader */
	protected $plugin;
	
	/** @var bool */
	protected static $enable = false;
	
	/** @var Int */
	protected static $time = 0;
	
	/**
	 * PURGUE Constructor.
	 * @param Loader $plugin
	 */
	public function PURGUE__construct(Loader $plugin){
		$this->plugin = $plugin;
	}
	
	/**
	 * @return bool
	 */
	public static function isEnable() : bool {
		return self::$enable;
	}
	
	/**
	 * @param bool $enable
	 */
	public static function setEnable(bool $enable){
		self::$enable = $enable;
	}
	
	/**
	 * @param Int $time
	 */
	public static function setTime(Int $time){
		self::$time = $time;
	}
	
	/**
	 * @return Int
	 */
	public static function getTime() : Int {
		return self::$time;
	}
	
	/**
	 * @return void
	 */
	public static function start(Int $time = 60) : void {
		self::setEnable(true);
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new PURGUETask($time), 20);
	}
	
	/**
	 * @return void
	 */
	public static function stop() : void {
		self::setEnable(false);
	}
}

?>
