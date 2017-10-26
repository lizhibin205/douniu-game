<?php
namespace BGame\ZhaJinHua;

use BGame\Base\Poker;

class ZhaJinHua
{
    /**
     * 游戏的扑克牌
     * @var BGame\Base\Poker
     */
    private $poker;

    /**
     * 记录每个玩家手上的卡牌
     * @var array[] => BGame\Base\Poker
     */
    private $players;

    public function __construct()
    {
        $this->poker = Poker::getNewPoker(Poker::WITHOUT_KING)->shuffle();
    }

    /**
     * 初始化牌局
     * @param array $numberPlayer 玩家数组，数组的值表示玩家标识
     * @return $this
     */
    public function init(array $numberPlayer)
    {
        //最多支持10个玩家
        if (count($numberPlayer) > 17) {
            throw new Exception("炸金花：最多支持17个玩家");
        }

        //向每个玩家发3张牌
        foreach ($numberPlayer as $player) {
            //为每个玩家分配3张牌
            $playerObj = new Player($player);
            $playerObj->setPoker($this->poker->deal(3));
            $this->players[$player] = $playerObj;
        }
        return $this;
    }

    /**
     * 返回牌局的结果
     * @return array
     */
    public function getResult()
    {
        $result = [];

        //计算每个玩家牌型
        foreach ($this->players as $player) {
            
        }

        return $result;
    }
}