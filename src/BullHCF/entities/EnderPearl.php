<?php

namespace BullHCF\entities;

use BullHCF\API\projectile\Throwable;

use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\RayTraceResult;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class EnderPearl extends Throwable {

    public const NETWORK_ID = self::ENDER_PEARL;

    protected function onHit(ProjectileHitEvent $event) : void{
        $owner = $this->getOwningEntity();
        if($owner !== null){
            //TODO: check end gateways (when they are added)
            //TODO: spawn endermites at origin

            $this->level->broadcastLevelEvent($owner, LevelEventPacket::EVENT_PARTICLE_ENDERMAN_TELEPORT);
            $this->level->addSound(new EndermanTeleportSound($owner));
            $owner->teleport($event->getRayTraceResult()->getHitVector());
            $this->level->addSound(new EndermanTeleportSound($owner));

            $owner->attack(new EntityDamageEvent($owner, EntityDamageEvent::CAUSE_FALL, 5));
        }
    }
}