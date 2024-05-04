<?php

class DandelionBlock extends FlowableBlock{
	public function __construct(){
		parent::__construct(DANDELION, 0, "Dandelion");
		$this->isActivable = true;
		$this->hardness = 0;
	}
	public static function getAABB(Level $level, $x, $y, $z){
		return null;
	}
	public function place(Item $item, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
			$down = $this->getSide(0);
			if($down->getID() === 2 or $down->getID() === 3 or $down->getID() === 60){
				$this->level->setBlock($block, $this, true, false, true);
				return true;
			}
		return false;
	}

	public function onUpdate($type){
		if($type === BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent === true){ //Replace with common break method
				ServerAPI::request()->api->entity->drop(new Position($this->x+0.5, $this->y, $this->z+0.5, $this->level), BlockAPI::getItem($this->id));
				$this->level->setBlock($this, new AirBlock(), false, false, true);
				return BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}

	public function onActivate(Item $item, Player $player){
		if($item->getID() === DYE and $item->getMetadata() === 0x0F){
			if(($player->gamemode & 0x01) === 0){
				$player->removeItem(DYE,0x0F,1);
			}
			$random = new Random();
			self::placeFlowers($this->level, new Vector3($this->x, $this->y, $this->z), $random, $random->nextRange(2, 5), 2);
			return true;
		}
		return false;
	}

	public static function placeFlowers(Level $level, Vector3 $pos, Random $random, $count, $radius){
		for($c = 0; $c < $count; ++$c){
			$x = $random->nextRange($pos->x - $radius, $pos->x + $radius);
			$z = $random->nextRange($pos->z - $radius, $pos->z + $radius);
			for($y = $pos->y - 2; $y <= $pos->y + 2; ++$y){
				if($level->level->getBlockID($x, $y + 1, $z) === AIR and $level->level->getBlockID($x, $y, $z) === GRASS){
					$changeFlower = $random->nextRange(1, 7);
					if($changeFlower === 1){
						$t = BlockAPI::get(CYAN_FLOWER, 0);
					} else {
						$t = BlockAPI::get(DANDELION, 0);
					}
					$level->setBlockRaw(new Vector3($x, $y + 1, $z), $t);
					break;
				}
			}
		}
	}
}