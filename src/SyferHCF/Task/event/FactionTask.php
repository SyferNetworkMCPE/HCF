<?php

namespace SyferHCF\Task\event;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use SyferHCF\AsyncTask\FactionData;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class FactionTask extends Task {

    /**
     * FactionTask Constructor.
     */
    public function __construct(){
        
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
    	$time = microtime(true);
    	try {
	    	foreach(Factions::getFactions() as $factionName){
	    		if(!Factions::isFreezeTime($factionName)){
	         	   Factions::setStrength($factionName, Factions::getMaxStrength($factionName));
	      	  }
	        }
	        Loader::getInstance()->getLogger()->info("Backup of factions was completed in: ".round((microtime(true) - $time), 3)." seconds");
		} catch(\Exception $exception){
			Loader::getInstance()->getLogger()->info($exception->getMessage());
		}
    }
}

?>
