BGame卡牌游戏 
=============
支持比较流行的卡牌游戏算法

特性
---------------
+ 卡牌：代码1-52，1方块A，2梅花A，3红桃A，4，黑桃A，5方块2……，53小王，54大王
+ 目前支持斗牛

斗牛
---------------
示例代码：
```php
$game = new BGame\Douniu\Douniu();
//返回斗牛游戏玩家的结果
$result = $game->init(['1', '2', '3', '4', '5', '6'])->getResult();
```

炸金花
---------------
示例代码：
```php
$zhaJinHua = new ZhaJinHua();
$result = $zhaJinHua->init(['1', '2', '3', '4', '5', '6'])->getResult();
```