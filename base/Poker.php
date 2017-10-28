<?php
namespace BGame\Base;

/**
 * 扑克牌
 * @author zhibin02.li
 * 卡牌：代码1-52，1方块A，2梅花A，3红桃A，4，黑桃A，5方块2……，53小王，54大王
 * 牌型：1是A，2是2，13是K，14小王，15大王
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
     * 返回卡牌的牌型数组
     */
    public function toCardArray()
    {
        $cards = $this->cards;
        array_walk($cards, function(&$val){
            if ($val == 53) {
                $val = 14;
            } else if ($val == 54) {
                $val = 15;
            } else {
                $val = ceil($val / 4);
            }
        });
        return $cards;
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

    /**
     * 判断当前卡牌是否顺子
     * 支持A开头，A结尾的（比如12345，10JQKA）
     * @return int 如果不是顺子，返回0，否则返回顺子的权重 ，14为A尾的顺子
     */
    public function isShunZi()
    {
        $cardType = $this->toCardArray();
        //如果有王，就肯定不是顺子
        if ($this->hasWang()) {
            return 0;
        }
        //先排序
        sort($cardType);
        $isShun = function ($cardType) {
            $preType = null;
            foreach ($cardType as $cType) {
                if ($preType == null) {
                    $preType = $cType;
                    continue;
                }
                if ($cType - $preType != 1) {
                    return 0;
                }
                $preType = $cType;
            }
            return $preType;
        };
        //判断A结尾的牌型
        //先slice出[0]的A，在判断如下的牌是否顺子，且最大牌为K
        $aShun = call_user_func($isShun, array_slice($cardType, 1));
        if ($aShun == 13) {
            return 14;
        }
        //判断是否非A结尾的牌型
        return call_user_func($isShun, $cardType);
    }

    /**
     * 判断是否相同花色
     * @return int 0表示不同花色，1是方块，2是梅花，3是心，4是黑桃
     */
    public function isSameColor()
    {
        //如果有王牌，肯定不是相同花色
        if ($this->hasWang()) {
            return 0;
        }

        $cards = $this->toArray();
        $colors = [];
        foreach ($cards as $c) {
            $color = $c % 4;
            $colors[$color] = 1;
            if (count($colors) > 1) {
                return 0;
            }
        }

        //返回花色
        return array_keys($colors)[0];
    }

    /**
     * 判断是否有王牌
     * @return bool
     */
    protected function hasWang()
    {
        foreach ([53, 54] as $c) {
            if (in_array($c, $this->cards)) {
                return true;
            }
        }
        return false;
    }
}