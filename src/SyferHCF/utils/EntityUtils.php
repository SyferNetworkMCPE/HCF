<?php
declare(strict_types=1);
namespace SyferHCF\utils;

use pocketmine\Player;
use SyferHCF\level\blocks\Portal;
use SyferHCF\level\blocks\EndPortal;
use pocketmine\block\Block;
use pocketmine\entity\Entity;

class EntityUtils extends Dimensions {
    public static function isInsideOfPortal(Entity $entity): bool {
        if($entity->level === null) return false;
        $block = $entity->getLevel()->getBlock($entity);
        if($block instanceof Portal) return true;
        return false;
    }
    public static function isInsideOfEndPortal(Entity $entity): bool {
        if($entity->level === null) return false;
        $block = $entity->getLevel()->getBlock($entity);
        if($block instanceof EndPortal) return true;
        return false;
    }
}
