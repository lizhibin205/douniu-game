<?php
namespace BGame\ZhaJinHua;

use BGame\Base\Poker;

/**
 * 牌型
 * 豹子（炸弹）：三张点相同的牌。例：AAA、222。AAA最大，222最小，得分6xxxx
 * 顺金（同花顺、色托）：花色相同的顺子。例：黑桃456、红桃789。最大的顺金为花色相同的QKA，最小的顺金为花色相同的123。得分5xxxx
 * 金花（色皮）：花色相同，非顺子。例：黑桃368，方块145。得分4xxxx
 * 顺子（拖拉机）：花色不同的顺子。例：黑桃5红桃6方块7。最大的顺子为花色不同的QKA，最小的顺子为花色不同的123。，得分4xxxx
 * 对子：两张点数相同的牌。例：223，334。得分，2xxxx
 * 单张：三张牌不组成任何类型的牌。
 * 特殊：花色不同的235。
 * @author lizhibin
 * @see https://baike.baidu.com/item/%E7%82%B8%E9%87%91%E8%8A%B1/8806924?fr=aladdin
 * 判断3张牌的牌型
 */
class Type
{
    /**
     * 计算牌型是否豹子
     * @param Poker $poker
     * @return int 大于0是豹子，0则不是
     */
    public static function isPaozi(Poker $poker)
    {
        $cardType = $poker->toCardArray();
        $cardType = array_unique($cardType);
        if (count($cardType) === 1) {
            $firstCard = $cardType[0];
            if ($firstCard == 1) {
                return 60014;
            } else {
                return $firstCard + 60000;
            }
        } else {
            return 0;
        }
    }

    /**
     * 计算是否顺金
     * @param Poker $poker
     * @return int 大于0是金顺，0则不是
     */
    public static function isShunJin(Poker $poker)
    {
        $shunNumber = $poker->isShunZi();
        $colorNumber = $poker->isSameColor();
        if ($shunNumber > 0 && $colorNumber > 0) {
            return 50000 + $shunNumber * 100 + $colorNumber;
        } else {
            return 0;
        }
    }

    /**
     * 判断是否金花
     * @param Poker $poker
     * @return int 大于0是金花，0则不是
     */
    public static function isJinHua(Poker $poker)
    {
        $cardType = $poker->toCardArray();
        $colorNumber = $poker->isSameColor();
        if ($colorNumber > 0) {
            //A最大，2最小
            if (in_array(1, $cardType)) {
                $max = 14;
            } else {
                $max = max($cardType);
            }
            return 40000 + $max;
        } else {
            return 0;
        }
    }

    /**
     * 判断是否顺子
     * @param Poker $poker
     * @return int 大于0是顺子，0则不是
     */
    public static function isShunZi(Poker $poker)
    {
        $maxCard = max($poker->toArray());
        $shunNumber = $poker->isShunZi();
        if ($shunNumber > 0) {
            return 30000 + $shunNumber * 100 + $maxCard;
        } else {
            return 0;
        }
    }

    /**
     * 判断是否对子
     * @param Poker $poker
     */
    public static function isDuiZi(Poker $poker)
    {
        $cardType = $poker->toCardArray();
        $duiZi = [];
        foreach ($cardType as $cType) {
            if (!isset($duiZi[$cType])) {
                $duiZi[$cType] = 0;
            }
            $duiZi[$cType] += 1;
        }
        //找出对子
        $dui = array_search(2, $duiZi);
        if ($dui) {
            $maxDuiCard = 0;
            $cards = $poker->toArray();
            foreach ($cards as $c) {
                if ($c % 4 == $dui && $c > $maxDuiCard) {
                    $maxDuiCard = $c;
                }
            }
            return 20000 + $dui * 100 + $maxDuiCard;
        } else {
            return 0;
        }
    }

    /**
     * 计算散牌的分值
     * @param Poker $poker
     */
    public static function defaultCard(Poker $poker)
    {
        $cardType = $poker->toCardArray();
        if (in_array(1, $cardType)) {
            return 10000 + 14;
        } else {
            return 10000 + max($cardType);
        }
    }
}