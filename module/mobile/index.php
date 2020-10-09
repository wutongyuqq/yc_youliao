<?php
global $_GPC,$_W;
//reqInfo::mihuatoken();
$cfg=$this->module['config'];
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$title=$cfg['index_title'];
$adv_type=1;//1首页，4拼团，5秒杀，6首单，7买单，8同城
$userinfo = Member::initUserInfo(); //用户信息
$joinCity=Shop::joinCity();
$codition =$this->getcookieCity();
$codition .=Shop::getShopEndtime();
//城市
//print_r($_COOKIE);//打印所有缓存
//setcookie('address', '', time()-3600 * 24 * 7);
//setcookie('city_name', '', time()-3600 * 24 * 7);
if($op=='display') {
    //统计
    $user_views=$cfg['user_views'];
    $userNum=util::countDataNumber(MEMBER,array('uniacid'=>$_W['uniacid']));
    $userNum=$userNum+$user_views;
    $shopNum=util::countDataNumber(SHOP,array('uniacid'=>$_W['uniacid']));
    $shop_views=$cfg['shop_views'];
    $shopNum=$shopNum+$shop_views;
    $viewsData= commonGetData::sensitiveword(3);
    if(empty($viewsData)){
        pdo_insert(WORD,array('contract'=>1,'type'=>3, 'uniacid' => $_W['uniacid']));
    }else{//更新访问量
        pdo_update(WORD,array('contract'=>$viewsData['contract']+1 ),array('type'=>3,'uniacid' => $_W['uniacid']));
    }
    $views=$cfg['views_start'];
    $viewsNum=intval($viewsData['contract'])+1+$views;
    //公告
    $msg=Member::member_msg(1);
    $city_name=$this->getCity_name();
    //天气  fix by weimao  QQ:2058430070
    //$weatherdata = $this->getWeather($city_name);
    $weatherurl = $this->weatherreq();
    $weatherdata=util::getWeather($weatherurl,$city_name);
    $weather2=json_decode($weatherdata,1);
    if( $weather2['status']=='1002'){//天气数据不正常时
        $weatherdata=util::getWeather($weatherurl,$_COOKIE['district']);
    }

	//签到
    $uid         = mc_openid2uid($_W['openid']);
    $timestr = date("Y-m-d", TIMESTAMP);
    $result = pdo_fetchcolumn("select count(*) from " . tablename(QIAN) . " where timestr=:timestr and uid=:uid", array(':timestr' => $timestr, ':uid' => $uid));
    $qiandao_random = $cfg['qiandao_random'];//是否随机签到
    if ($result > 0) {
        $qian_flag ="今日已签到";
    }elseif ($qiandao_random == 1) {
        $jifen ="获取随机积分";//根据最大的积分数，随机获取签分
    }elseif(!empty($cfg['qiandao_jifen'])){
        $jifen =' +'. $cfg['qiandao_jifen'].'积分';
    }

//同城信息
    $shownum=$this->showMsgNum();
    $info_condition=$this->info_condition();
    $lat=$this->getLat();
    $lng=$this->getLng();
    //今日置顶
    $zdmessagelist =commonGetData::getTopMsg($info_condition,$shownum,$lat,$lng);
    //最新
    $lastedmessagelist = commonGetData::getNewMsg($info_condition,$shownum,$lat,$lng);
//推荐商家
    $codition1 = " and s.status=1 and s.is_hot=1 ";


// 1入驻申请，2首页推荐，3秒杀，4拼团，5优惠买单,6店铺开启
    $shoplist = pdo_fetchall("SELECT s.*, a.area_name FROM " . tablename(SHOP) . " s  left join " .
        tablename(AREA) . " a on a.area_id=s.area_id and s.uniacid=a.uniacid WHERE s.uniacid =:uniacid {$codition} {$codition1} and s.status=1 ORDER BY  s.orderby DESC ", array(':uniacid' => $_W['uniacid']));


//查找最近的秒杀商品
    $goods_data=Goods::getXsmsGoods($joinCity,$codition);
    $xsms=$goods_data['xsms'];
    $stage=$goods_data['stage'];
    $current_status=$goods_data['current_status'];
    $msz_timestart=$goods_data['msz_timestart'];

}elseif($op=="mapWeather"){//根据坐标获取城市名再获取天气
    $latitude=$_GPC['latitude'];
    $longitude=$_GPC['longitude'];
    $mapreq=$this->mapreq();
    $chagexy=$this->chagexy();
    $data=util::getDistrictByLatLng($latitude,$longitude,$mapreq,$chagexy);
    $weatherdata= $this->getWeather($data['city']);
    echo json_encode(array('status' => $data['status'],'weatherdata' => $weatherdata,'address' => $data['address'],'formatted_address' => $data['formatted_address']));
    exit;

}
include $this->template('../mobile/index');
exit();