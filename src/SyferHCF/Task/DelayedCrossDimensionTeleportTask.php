<?php
declare(strict_types=1);
namespace SyferHCF\Task;

use SyferHCF\Loader;
use SyferHCF\utils\Dimensions;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\{ChangeDimensionPacket, PlayStatusPacket};
use pocketmine\Player;
use pocketmine\scheduler\Task;

class DelayedCrossDimensionTeleportTask extends Task {
    /** @var Player */
    protected $player;
    /** @var int */
    protected $dimension;
    /** @var Vector3 */
    protected $position;
    /** @var bool */
    protected $respawn;

    /**
     * @param Player $player
     * @param int $dimension
     * @param Vector3 $position
     * @param bool $respawn
     */
    public function __construct(Player $player, int $dimension, Vector3 $position, bool $respawn = false){
        $this->player = $player;
        $this->dimension = $dimension;
        $this->position = $position;
        $this->respawn = $respawn;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick){
        if(Dimensions::isDelayedTeleportCancelleable($this->player, $this->dimension)){
            unset(Loader::$onPortal[$this->player->getId()]);
            return false;
        }
        $this->player->sendDimensionPacket($this->dimension);
        $this->player->teleport($this->position);
        unset(Loader::$onPortal[$this->player->getId()]);
        return true; 
    }
}
