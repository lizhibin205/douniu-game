<?php
namespace BGame\ZhaJinHua;

use BGame\Base\Poker;

class Player
{
    /**
     * 用户标识
     * @var string
     */
    private $flag;

    /**
     * 玩家的Poker牌
     * @var Poker
     */
    private $poker;

    /**
     * __construct
     * @param string $flag 用户标识
     */
    public function __construct($flag)
    {
        $this->flag = $flag;
    }

    /**
     * 为玩家分配poker牌
     * @param Poker $poker
     */
    public function setPoker(Poker $poker)
    {
        $this->poker = $poker;
        return $this;
    }

    /**
     * 返回当前玩家的牌
     * @return array 
     */
    public function getCards()
    {
        return $this->poker->toArray();
    }

    /**
     * 获取玩家手牌的得分
     * @return [$socre, $maxCard]
     */
    public function getScore()
    {
        //最大的一张牌
        $maxCard = max($this->poker->toArray());

        //判断是否豹子
        $score = Type::isPaozi($this->poker);
        if ($score > 0) {
            return [$score, $maxCard];
        }

        //判断是否顺金
        $score = Type::isShunJin($this->poker);
        if ($score > 0) {
            return [$score, $maxCard];
        }

        //判断是否金花
        $score = Type::isJinHua($this->poker);
        if ($score > 0) {
            return [$score, $maxCard];
        }

        //判断是否对子
        $score = Type::isDuiZi($this->poker);
        if ($score > 0) {
            return [$score, $maxCard];
        }

        //最后是散牌
        $score = Type::defaultCard($this->poker);
        return [$score, $maxCard];
    }
}