<?php

class MyceliumBlock extends SolidBlock{
	public function __construct(){
		parent::__construct(MYCELIUM, 0, "Mycelium");
		$this->isActivable = true;
		$this->hardness = 3;
	}

	public function onUpdate($type){
		if($type === BLOCK_UPDATE_RANDOM && !$this->getSide(1)->isTransparent && mt_rand(0, 2) == 1){
			$this->level->setBlock($this, BlockAPI::get(DIRT, 0), true, false, true);
		}
		return BLOCK_UPDATE_RANDOM;
	}

	public function onActivate(Item $item, Player $player){ //uwu
		/*$oldtime = microtime(1);
		if(mt_rand(0, 1) === 0){
			$time = Structures::$SMALLFARM_VILLAGE->rotate90deg($this->level, $this->x, $this->y, $this->z)->build($this->level, $this->x, $this->y, $this->z);
		}else{
			$time = Structures::$SMALLFARM_VILLAGE->build($this->level, $this->x, $this->y, $this->z);
		}
		console("builded in ".($time - $oldtime));*/
		
		//(new WoodHutStructure())->build($this->level, $this->getX(), $this->getY(), $this->getZ());
	}

	public function getDrops(Item $item, Player $player){
		return array(
			array(DIRT, 0, 1),
		);
	}
}