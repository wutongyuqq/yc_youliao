<?php
//checkauth();
//发红包
global $_GPC,$_W;
$type=intval($_GPC['type']);
$userinfo = Member::initUserInfo(); //用户信息
$openid=$from_user=$this->getOpenid();
$pageIndex =$this->pageIndex();
$num = $this->getNum(); //每次取值的个数
$Redpackage=new Redpackage();
//抢钱榜
    $qlist =   $Redpackage ->getRedpackageList(1,$pageIndex,$num);
//土豪榜
    $tlist =   $Redpackage ->getRedpackageList(0,$pageIndex,$num);



include $this->template('../mobile/bang');
exit();

