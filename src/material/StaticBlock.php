<?php
/**
 * Small class to hopefully optimize entities a bit
 */
class StaticBlock
{
	const DEFAULT_SLIPPERINESS = 0.6;
	const DEFAULT_HARDNESS = 0;
	
	private static $NULL_BOUNDS;
	public static $isSolid = [];
	public static $isTransparent = [];
	public static $isFlowable = [];
	public static $isReplaceable = [];
	public static $isPlaceable = [];
	public static $hasPhysics = [];
	public static $isLiquid = [];
	public static $isFullBlock = [];
	
	public static $hardness = [];
	public static $slipperiness = [];
	public static $boundingBoxes = [];
	public static $minXs = [], $minYs = [], $minZs = [], $maxXs = [], $maxYs = [], $maxZs = [];
	
	public static function init(){
		self::$NULL_BOUNDS = new AxisAlignedBB(0, 0, 0, 0, 0, 0);
		foreach(Block::$class as $nonstaticname){
			/**@var Block $b*/
			$b = new $nonstaticname();
			
			self::$isSolid[$b->getID()] = $b->isSolid;
			self::$isTransparent[$b->getID()] = $b->isTransparent;
			self::$isFlowable[$b->getID()] = $b->isFlowable;
			self::$isReplaceable[$b->getID()] = $b->isReplaceable;
			self::$isPlaceable[$b->getID()] = $b->isPlaceable;
			self::$hasPhysics[$b->getID()] = $b->hasPhysics;
			self::$isLiquid[$b->getID()] = $b->isLiquid;
			self::$isFullBlock[$b->getID()] = $b->isFullBlock;
			self::$slipperiness[$b->getID()] = $b->slipperiness;
			self::$boundingBoxes[$b->getID()] = $b->boundingBox;
			self::$hardness[$b->getID()] = $b->getHardness();
			
			
			FireBlock::setFlammabilityAndCatchingChance($b->getID(), 0, 0);
			self::setBlockBounds($b->getID(), 0, 0, 0, 1, 1, 1);
		}
		
		self::setBlockBounds(BED_BLOCK, 0, 0, 0, 1, 0.5625, 1);
		//Cake: has bounds based on world state
		//Chest: has bounds based on world state
		self::setBlockBounds(CHEST, 0.025, 0, 0.025, 0.975, 0.95, 0.975);
		self::setBlockBounds(WHEAT_BLOCK, 0, 0, 0, 1, 0.25, 1);
		self::setBlockBounds(CARROT_BLOCK, 0, 0, 0, 1, 0.25, 1);
		self::setBlockBounds(POTATO_BLOCK,0, 0, 0, 1, 0.25, 1);
		self::setBlockBounds(BEETROOT_BLOCK, 0, 0, 0, 1, 0.25, 1); //i assume it extends class Crops
		self::setBlockBounds(WHEAT_BLOCK, 0, 0, 0, 1, 0.25, 1);
		self::setBlockBounds(DEAD_BUSH, 0.1, 0, 0.1, 0.9, 0.8, 0.9);
		self::setBlockBounds(DOOR_BLOCK, 0, 0, 0, 1, 1, 1); //has bounds based on rotation
		self::setBlockBounds(FARMLAND, 0, 0, 0, 1, 0.9375, 1);
		//Fence: bounds on state
		//Fence Gate: bounds on state
		self::setBlockBounds(TALL_GRASS, 0.1, 0, 0.1, 0.9, 0.8, 0.9);
		self::setBlockBounds(MELON_STEM, 0.5 - 0.125, 0, 0.5 - 0.125, 0.5 + 0.125, 0.25, 0.5 + 0.125);
		self::setBlockBounds(PUMPKIN_STEM, 0.5 - 0.125, 0, 0.5 - 0.125, 0.5 + 0.125, 0.25, 0.5 + 0.125);
		self::setBlockBounds(SAPLING, 0.1, 0, 0.1, 0.9, 0.8, 0.9);
		self::setBlockBounds(BROWN_MUSHROOM, 0.3, 0, 0.3, 0.8, 0.4, 0.8);
		self::setBlockBounds(SLAB, 0, 0, 0, 1, 0.5, 1);
		//Ladder: based on rotation
		//Glass Pane: based on state
		self::setBlockBounds(RAIL, 0, 0, 0, 1, 0.125, 1);
		self::setBlockBounds(POWERED_RAIL, 0, 0, 0, 1, 0.125, 1);
		
		self::setBlockBounds(DANDELION, 0.3, 0.0, 0.3, 0.7, 0.6, 0.7); //extends Bush
		self::setBlockBounds(ROSE, 0.3, 0.0, 0.3, 0.7, 0.6, 0.7); //extends Bush
		
		self::setBlockBounds(SUGARCANE_BLOCK, 0.5 - 0.375, 0, 0.5 - 0.375, 0.5 + 0.375, 1, 0.5 + 0.375);
		self::setBlockBounds(SNOW_LAYER, 0, 0, 0, 1, 0.125, 1);
		//Stairs: based on different factors
		//Stone wall: based on state
		
		//Fire related stuff
		FireBlock::setFlammabilityAndCatchingChance(PLANKS, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(DOUBLE_WOOD_SLAB, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(WOODEN_SLAB, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(FENCE, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(FENCE_GATE, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(SIGN, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(WALL_SIGN, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(WOODEN_STAIRS, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(BIRCH_WOODEN_STAIRS, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(SPRUCE_WOODEN_STAIRS, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(JUNGLE_WOODEN_STAIRS, 5, 20);
		FireBlock::setFlammabilityAndCatchingChance(TRUNK, 5, 5);
		FireBlock::setFlammabilityAndCatchingChance(LEAVES, 30, 60);
		FireBlock::setFlammabilityAndCatchingChance(BOOKSHELF, 30, 20);
		FireBlock::setFlammabilityAndCatchingChance(TNT, 15, 100);
		FireBlock::setFlammabilityAndCatchingChance(TALL_GRASS, 60, 100);
		FireBlock::setFlammabilityAndCatchingChance(WOOL, 30, 60);
		FireBlock::setFlammabilityAndCatchingChance(CARPET, 30, 60);
		FireBlock::setFlammabilityAndCatchingChance(COAL_BLOCK, 5, 5);
		FireBlock::setFlammabilityAndCatchingChance(HAY_BALE, 60, 20);
		FireBlock::setFlammabilityAndCatchingChance(SPONGE, 30, 60);
	}
	
	public static function setBlockBounds($blockID, $minX, $minY, $minZ, $maxX, $maxY, $maxZ){
		self::$maxXs[$blockID] = $maxX;
		self::$maxYs[$blockID] = $maxY;
		self::$maxZs[$blockID] = $maxZ;
		
		self::$minXs[$blockID] = $minX;
		self::$minYs[$blockID] = $minY;
		self::$minZs[$blockID] = $minZ;
	}
	
	public static function getAABB(Level $level, $x, $y, $z){
		$id = $level->level->getBlockID($x, $y, $z);
		return new AxisAlignedBB(self::$minXs[$id] + $x, self::$minYs[$id] + $y, self::$minZs[$id] + $z, self::$maxXs[$id] + $x, self::$maxYs[$id] + $y, self::$maxZs[$id] + $z); //TODO get bb from self::$boundingBoxes ?
	}
	
	public static function getBlock($id){
		return Block::$class[$id] ?? Block::$class[0];
	}
	
	public static function getHardness($id){
		return self::$hardness[$id] ??  StaticBlock::DEFAULT_HARDNESS;
	}
	
	//TODO: use static block min/max
	public static function getBoundingBoxForBlockCoords($id, $x, $y, $z){
		/**@var AxisAlignedBB $bb*/
		$bb = self::$boundingBoxes[$id] ?? false;
		if($bb === false){
			return clone self::$NULL_BOUNDS;
		}
		$bb = clone $bb;
		return $bb->setBounds($x + $bb->minX, $y + $bb->minY, $z + $bb->minZ, $x + $bb->maxX, $y + $bb->maxY, $z + $bb->maxZ);
	}
	
	
	public static function getSlipperiness($id){
		return self::$slipperiness[$id] ?? StaticBlock::DEFAULT_SLIPPERINESS;
	}
	
	public static function getIsSolid($id){
		return self::$isSolid[$id] ?? false;
	}
	
	public static function getIsTransparent($id){
		return self::$isTransparent[$id] ?? false;
	}
	
	public static function getIsFlowable($id){
		return self::$isFlowable[$id] ?? false;
	}
	
	public static function getIsReplaceable($id){
		return self::$isReplaceable[$id] ?? false;
	}
	
	public static function getIsPlaceable($id){
		return self::$isPlaceable[$id] ?? false;
	}
	
	public static function getHasPhysics($id){
		return self::$hasPhysics[$id] ??  false;
	}
	
	public static function getIsLiquid($id){
		return self::$isLiquid[$id] ?? false;
	}
	
	public static function getIsFullBlock($id){
		return self::$isFullBlock[$id] ?? false;
	}
}

