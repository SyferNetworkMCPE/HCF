<?php

namespace SyferHCF\entities;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;
use SyferHCF\API\projectile\Throwable;

use pocketmine\utils\TextFormat as TE;
use pocketmine\math\{Vector3, RayTraceResult};
use pocketmine\level\Level;
use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\{ProjectileHitEvent, ProjectileHitEntityEvent};

class Snowball extends Throwable {

    const NETWORK_ID = self::SNOWBALL;

    /** @var float */
    public $width = 0.5, $length = 0.5, $height = 0.5;

    /** @var float */
    protected $gravity = 0.03, $drag = 0.01;
	
	/**
     * Snowball Constructor.
     */
     
    public function __construct(){

    }

    /**
     * @param ProjectileHitEvent $event
     * @return void
     */
     
     public function onHit(ProjectileHitEvent $event): void
    {
			$sender = $projectile->getOwningEntity();
			if($sender instanceof Player and $event instanceof ProjectileHitEntityEvent){
				$player = $event->getEntityHit();
				if($player instanceof Player){
					if(!Factions::isSpawnRegion($sender) && !Factions::isSpawnRegion($player) && Factions::getFaction($sender->getName()) !== Factions::getFaction($player->getName())){
						if($player->getName() === $sender->getName()){
							return;
						}
						$sender->sendMessage(TE::BOLD.TE::GOLD."Snowball".TE::RESET.TE::WHITE.": ".TE::GRAY."you marked the player ".TE::BOLD.TE::GREEN.$player->getName());
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 * 5, 0));
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 20 * 5, 0));
                }
            }
        }
    }
}
