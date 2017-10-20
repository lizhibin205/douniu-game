<?php
namespace BGame\Douniu;
/**
 * Created by lizhibin.
 * Date: 2017/10/19
 * Time: 21:17
 * 定义游戏规则配置数据格式如下
 * 卡牌：代码1-52，1方块A，2梅花A，3红桃A，4，黑桃A，5方块2……
 * 判断牛的结果：0是无牛，1-9牛几，10牛牛，31五花牛，32五小牛
 */
class Douniu
{
    /**
     * @var array
     * 初始化纸牌数据
     */
    private $cards = [];

    /**
     * @var array
     * 游戏玩家
     */
    private $players = [];

    /**
     * __construct.
     * 构造函数
     */
    public function __construct()
    {
        //产生一个长度为52的自然数组，代表52张牌，没有鬼牌
        $this->cards = range(1, 52);
        //将52张牌的顺序打乱，相当于洗牌
        shuffle($this->cards);
    }

    /**
     * 初始化牌局
     * @param array $numberPlayer 玩家数组，数组的值表示玩家标识
     * @return $this
     */
    public function init(array $numberPlayer)
    {
        //最多支持10个玩家
        if (count($numberPlayer) > 10) {
            throw new Exception("斗牛：最多支持10个玩家");
        }
        $sliceOffset = 0;
        foreach ($numberPlayer as $player) {
            //为每个玩家分配5张牌
            $this->players[$player] = array_slice($this->cards, $sliceOffset, 5);
            $sliceOffset += 5;
        }
        return $this;
    }

    /**
     * 返回牌局结果
     * @return array
     */
    public function getResult()
    {
        $result = [];
        //返回玩家的数据
        $result['players'] = [];
        foreach ($this->players as $playerKey => $player) {
            //计算牌型
            $paixing = $player;
            array_walk($paixing, [$this, 'changeCardIdToName']);
            //计算牛什么鬼
            $niu = $this->getNiu($player);

            $result['players'][$playerKey] = [
                'cards' => $player,//卡牌集合
                'paixing' => $paixing,//卡牌集合的名称
                'niu' => $niu,//牌型
                'max_card' => max($player)//最大的一张牌
            ];
        }
        //比较玩家排序
        uasort($result['players'], function ($valA, $valB) {
            if ($valA['niu'] == $valB['niu']) {
                return $valA['max_card'] > $valB['max_card'] ? -1 : 1;
            }
            return $valA['niu'] > $valB['niu'] ? -1 : 1;
        });
        $result['player_sequeue'] = array_keys($result['players']);
        return $result;
    }

    /**
     * 传入纸牌编号，返回当前纸牌的名称
     * @param ref $cardId 纸牌序号
     * @return void
     */
    protected function changeCardIdToName(&$cardId)
    {
        $name = ['方块', '草花', '红桃', '黑桃'];
        $num = ['A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'];
        $namekey = ($cardId % 4) > 0 ? ($cardId % 4 - 1) : 3;
        $numkey = ceil($cardId / 4) - 1;
        $cardId = $name[$namekey] . $num[$numkey];
    }

    /**
     * 计算牛什么鬼
     * 0是无牛，1-9牛几，10牛牛
     * @param $cards 5张纸牌的数组
     * @return int
     */
    protected function getNiu($cards)
    {
        //特殊牌型
        //是否五花
        if ($this->isWuHua($cards)) {
            return 31;
        }
        //是否五小牛
        if ($this->isWuXiaoNiu($cards)) {
            return 32;
        }
        //普通牌型
        return $this->getNormalNiu($cards);
    }

    /**
     * 计算牛什么鬼：普通牌型判断
     * 0是无牛，1-9牛几，10牛牛
     * @param $cards 5张纸牌的数组
     * @return int
     */
    protected function getNormalNiu($cards)
    {
        $compare = [];//待对比的数据
        //每3张牌组合，计算结果
        $pailie = [
            [0, 1, 2], [0, 1, 3], [0, 1, 4], [0, 2, 3], [0, 2, 4], [0, 3, 4],
            [1, 2, 3], [1, 2, 4], [1, 3, 4],
            [2, 3, 4]
        ];
        foreach ($pailie as $pai) {
            //拆分成2部分
            $partA = [$cards[$pai[0]], $cards[$pai[1]], $cards[$pai[2]]];
            $partB = array_diff($cards, $partA);
            $partASum = 0;
            foreach ($partA as $a) {
                $partASum += $this->getScore($a);
            }
            if ($partASum % 10 > 0) {
                continue;
            }
            $partBSum = 0;
            foreach ($partB as $b) {
                $partBSum += $this->getScore($b);
            }
            $compare[] = $partBSum % 10;
        }
        return isset($compare[0]) ? max($compare) : 0;
    }

    /**
     * 计算牛什么鬼：是否五花
     * @param $cards 5张纸牌的数组
     * @return boolean
     */
    protected function isWuHua($cards)
    {
        foreach ($cards as $c) {
            if ($c <= 40) {
                return false;
            }
        }
        return true;
    }

    /**
     * 计算牛什么鬼：是否五小牛
     * @param $cards 5张纸牌的数组
     * @return boolean
     */
    protected function isWuXiaoNiu($cards)
    {
        $sum = 0;
        foreach ($cards as $c) {
            $score = $this->getScore($c);
            if ($score >= 5) {
                return false;
            }
            $sum += $score;
        }
        return $sum <= 10;
    }

    /**
     * 传入纸牌编号返回当前纸牌的点数
     * @param int $card 纸牌序号
     * @return int
     */
    protected function getScore($card)
    {
        return ceil($card / 4) < 10 ? ceil($card / 4) : 10;
    }
}

