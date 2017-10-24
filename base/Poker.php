<?php
namespace BGame\Base;

class Poker
{
    /**
     * 初始化卡牌
     * @var integer
     */
    const INIT_TYPE = 0;

    /**
     * 扑克牌卡牌集合
     * @var array
     */
    protected $cards = [];

    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }
}