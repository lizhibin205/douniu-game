<?php
namespace BGame\Base;

/**
 * 扑克牌
 * @author zhibin02.li
 * 卡牌：代码1-52，1方块A，2梅花A，3红桃A，4，黑桃A，5方块2……，53小王，54大王
 */
class Poker
{
    /**
     * 卡牌类型，默认54张
     * @var integer
     */
    const NORMAL = 0;

    /**
     * 卡牌类型，默认52张，没有王
     * @var integer
     */
    const WITHOUT_KING = 1;

    /**
     * 扑克牌卡牌集合
     * @var array
     */
    protected $cards = [];

    /**
     * 卡牌类型
     * @var int
     */
    protected $type = self::NORMAL;

    /**
     * 新建卡牌组合
     * @param int $type
     * @return BGame\Base\Poker
     */
    public static function getNewPoker($type)
    {
        if ($type == self::NORMAL) {
            return new self(range(1, 54), self::NORMAL);
        } else if ($type == self::WITHOUT_KING) {
            return new self(range(1, 52), self::WITHOUT_KING);
        } else {
            throw new \Exception("不合法的扑克类型ID：{$type}");
        }
    }

    /**
     * 卡牌的集合
     * @param array $cards
     * @param int $type
     */
    public function __construct(array $cards, $type)
    {
        if ($type == self::NORMAL) {
            $cardMax = 54;
        } else if ($type == self::WITHOUT_KING) {
            $cardMax = 52;
        } else {
            throw new \Exception("不合法的扑克类型ID：{$type}");
        }

        foreach ($cards as $card) {
            if ($card < 1 || $card > 54) {
                throw new \Exception("不合法的扑克ID：{$card}");
            }
        }
        $this->cards = $cards;
        $this->type = $type;
    }

    /**
     * 以数组的形式返回卡牌
     * @return array
     */
    public function toArray()
    {
        return $this->cards;
    }

    /**
     * 打乱数组，并返回$this
     * @return BGame\Base\Poker
     */
    public function shuffle()
    {
        shuffle($this->cards);
        return $this;
    }

    /**
     * 从卡牌中抽出N张牌
     * @param int $number
     * @return BGame\Base\Poker
     */
    public function deal($number)
    {
        $dealCards = [];
        for ($i = 1; $i <= $number; $i++) {
            $card = array_shift($this->cards);
            if (is_null($card)) {
                break;
            }
            $dealCards[] = $card;
        }
        return new Poker($dealCards, $this->type);
    }
}