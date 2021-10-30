<?php
declare(strict_types=1);
namespace SyferHCF\level\block;

use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use SyferHCF\Loader;
use SyferHCF\item\Items;
use pocketmine\Player;
use pocketmine\event\BlockBreakEvent;
use pocketmine\block\{Air, Block, BlockToolType, Transparent};
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

class Portal extends Transparent {
	
    /** @var int $id */
    protected $id = Block::PORTAL;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getName(): string{
        return "Portal";
    }

    /**
     * @return float
     */
    public function getHardness(): float{
        return -1;
    }

    /**
     * @return float
     */
    public function getResistance(): float{
        return 0;
    }

    /**
     * @return int
     */
    public function getToolType(): int{
        return BlockToolType::TYPE_PICKAXE;
    }

    /**
     * @return bool
     */
    public function canPassThrough(): bool{
        return true;
    }

    /**
     * @return bool
     */
    public function hasEntityCollision(): bool{
        return true;
    }

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onBlockBreak(BlockBreakEvent $event) : bool {
        if($this->getSide(Vector3::SIDE_WEST) instanceof Portal or $this->getSide(Vector3::SIDE_EAST) instanceof Portal){
            for($x = $this->x; $this->getLevel()->getBlockIdAt($x, $this->y, $this->z) == Block::PORTAL; $x++){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
            }
            for($x = $this->x - 1; $this->getLevel()->getBlockIdAt($x, $this->y, $this->z) == Block::PORTAL; $x--){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($x, $y, $this->z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($x, $y, $this->z), new Air());
                }
            }
        } else {
            for($z = $this->z; $this->getLevel()->getBlockIdAt($this->x, $this->y, $z) == Block::PORTAL; $z++){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
            }
            for($z = $this->z - 1; $this->getLevel()->getBlockIdAt($this->x, $this->y, $z) == Block::PORTAL; $z--){
                for($y = $this->y; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y++){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
                for($y = $this->y - 1; $this->getLevel()->getBlockIdAt($this->x, $y, $z) == Block::PORTAL; $y--){
                    $this->getLevel()->setBlock(new Vector3($this->x, $y, $z), new Air());
                }
            }
        }
        return true;
    }


    /**
     * @param Entity $entity
     */
    public function onEntityCollide(Entity $entity): void{
        if($entity instanceof Player){
            $data = Loader::getDefaultConfig("Worlds");
            if($entity->getLevel()->getFolderName() === Loader::getInstance()->getServer()->getDefaultLevel()->getFolderName()){
            Loader::getInstance()->getServer()->dispatchCommand($entity, "mw tp ".$data["nether"]);
            }
            if($entity->getLevel()->getFolderName() === $data["nether"]){
                $entity->teleport(Loader::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
            }
        }
    }
}
