<?php

namespace SyferHCF\utils;

use SyferHCF\Loader;
use SyferHCF\player\Player;
use SyferHCF\utils\EntityUtils;

use pocketmine\level\Level;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\DimensionIds;

class Dimensions {
	
	public static function onChunkGenerated(Level $level, int $chunkX, int $chunkZ, callable $callback) : void{
		if($level->isChunkPopulated($chunkX, $chunkZ)){
			$callback();
			return;
		}
		$level->registerChunkLoader(new NetworkChunkLoader($level, $chunkX, $chunkZ, $callback), $chunkX, $chunkZ, true);
	}
	
  public static function isDelayedTeleportCancelleable(Player $player, int $id): bool {
        switch($id){
            case DimensionIds::NETHER:
                return (!EntityUtils::isInsideOfPortal($player));
            case DimensionIds::THE_END:
                return (!EntityUtils::isInsideOfEndPortal($player));
            case DimensionIds::OVERWORLD:
                return (!EntityUtils::isInsideOfEndPortal($player) && !EntityUtils::isInsideOfPortal($player));
        }
        return false;
    }

   /**
     * @param Player $player
     * @param int $id
     * @return bool
     */
    public static function getDimension(Level $level): int {
        if($level->getName() == Loader::getInstance()->getServer()->getLevelByName("nether")->getName()){
                return DimensionIds::NETHER;
        } elseif($level->getName() == Loader::getInstance()->getServer()->getLevelByName("end")->getName()){
                return DimensionIds::THE_END;
        }
        return DimensionIds::OVERWORLD;
    }
}
