<?php
//checkauth();
global $_GPC,$_W;
$cfg=$this->module['config'];
$shopLogo=tomedia($cfg['shop_logo']);
if(!$shopLogo){
    $shopLogo =MODULE_URL.'/public/images/fabu_2.png';
}
$shopBgpic=tomedia($cfg['shop_bgpic']);
if(!$shopBgpic){
    $shopBgpic=MODULE_URL.'/public/images/user_top_bg-0.jpg';
}
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$userinfo = Member::initUserInfo(); //用户信息
$from_user=$openid=$this->getOpenid();
$city_id=$this->getCity_id();
$citycondition="";
$page =$this->getPage();
$num = $this->getNum(); //每次取值的个数
$shop_id=intval($_GPC['shop_id']);
$credit1=$this->getCredit1();
$item=  Shop::getShopInfoAll($shop_id);
if($item['endtime']<TIMESTAMP){
    message('店铺已到期');
}
if($_GPC['gps']=='1'){
    $formatted_address=$item['address'];
    $lng=$item['lng'];
    $lat=$item['lat'];
    include $this->template('../gps');
    exit();
}
$paynum=floatval($_GPC['paynum']);
if($item['is_discount']==1){
    $where['uniacid'] = $_W['uniacid'];
    $where['shop_id'] = $shop_id;
    if($paynum>0){
        $full=floatval($_GPC['paynum']);
        if($full)$where['fullmoney<'] =$paynum;
        $where['needcredit<'] =$credit1;
    }
    if(!empty($_GPC['type'])) $where['cardtype'] = intval($_GPC['type']);
    if($_GPC['status'] == '1') $where['status'] = 1;
    if($_GPC['status'] == '2') $where['starttime>'] = time();
    if($_GPC['status'] == '3') $where['endtime<'] = time();

    $order = '`id` ';
    if($_GPC['order'] == 'value') $order = '`cardvalue` ';
    if($_GPC['order'] == 'total') $order = '`totalnum` ';
    if($_GPC['order'] == 'taked') $order = '`takednum` ';
    if($_GPC['order'] == 'used') $order = '`usednum` ';

    $by = ' DESC ';
    if($_GPC['by'] == '1') $by = ' ASC ';

    $discount = Util::getAllDataInSingleTable(DISCOUNT,$where,$page,$num ,$order.$by);

}

if($op=="display"){
    //扫商户二维码进来的用户
    if($_GPC['flag']=='scan'){
        //以前是否扫过，没扫过就成为这个商家的客户
        $scan_data=array('shop_id'=>$shop_id,'id'=>$userinfo['id']);
        $scan_user=util::getSingelDataInSingleTable(MEMBER,$scan_data);
        if(empty($scan_user)){
            unset($scan_data['id']);
            pdo_update(MEMBER,$scan_data,array('id'=>$userinfo['id']));
        }
    }
    $where=Array();
    $share_img    = !empty($item['logo']) ? $_W['attachurl'].$item['logo'] :$_W['attachurl'].$cfg['share_img'];
    $share_info=!empty($item['intro']) ? trim($item['intro']) :$cfg['share_info'];
    $share_title=!empty($item['shop_name']) ? $item['shop_name'] :$cfg['share_title'];
    $share_link=$_W['siteroot']."app/".$this->createMobileUrl('shop',array('shop_id' => $shop_id));
    $where['shop_id'] = $shop_id;
    $where['status'] = 0;
    $goods= Goods::getAllGood($where,$page,$num,$order='`orderby` DESC');
    if($_GPC['isajax']==1) {
        echo json_encode(array('result'=>'1','list'=>$goods[0],'length'=>count($goods[0])));
        exit;
    }
    $title=$item['shop_name'].' '.$cfg['index_title'];
    //收藏
    $data['shop_id']=$shop_id;
    $data['type']=3;
    $data['openid']=$from_user;
    $collect=util::getSingelDataInSingleTable(COLLECT,$data,'id','2');
}elseif ($op=="discount"){//优惠买单
    $title=$item['shop_name'].'优惠买单'.' '.$cfg['index_title'];
    $adv_type=7;//1首页，4拼团，5秒杀，6首单，7买单，8同城
    include $this->template('../mobile/discount');
    exit();

}elseif ($op=="ajax_getdiscount") {//优惠买单ajax请求列表数据
    $list = Array();
    $list=Discount::getmoney($discount,$paynum);
    echo json_encode(array('status'=>'1','list'=>$list,'length'=>count($discount)));
    exit;
}elseif($op=='submit') {//提交表单
        if($paynum<=0){
            message('买单金额不能小于0',referer(),'error');
        }
        //aftermoney买单金额，paymoney支付金额
        $d_id=intval($_GPC['d_id']);
        if($d_id>0){
            $paymoney=floatval($_COOKIE[$d_id]);//获取随机金额的session
            if(empty($paymoney)){
                $d_info=Util::getSingelDataInSingleTable(DISCOUNT,array('id'=>$d_id,'shop_id'=>$shop_id));
                //根据id查找买单规则
                $paymoney=commonGetData::getNowmoney($d_info,$paynum);
            }
        }else{
            $paymoney=$paynum;
        }
            $ordersn=util::getordersn(DISCOUNT_RE);
            $in=Array();
            $in['uniacid']=$_W['uniacid'];
            $in['ordersn']=$ordersn;
            $in['mid']=$userinfo['id'];
            $in['aftermoney']=$paynum;
            $in['paymoney']=$paymoney;
            $in['createtime']=TIMESTAMP;
            $in['shop_id']=$shop_id;
            $in['discount_id']=$d_id;
            pdo_insert(DISCOUNT_RE,$in);
            $id=pdo_insertid();
            //$mihua_token=reqInfo::mihuatoken();
            //$tid=reqInfo::gettokenOrsn( 'dis'.$id,$mihua_token);
			//fix by weimao  @@2058430070
			$params = array(
                'tid' => TIMESTAMP,      //此号码用于业务模块中区分订单，交易的识别码
                'ordersn' =>'dis'.$id,  //收银台中显示的订单号
                'title' => $item['shop_name'].'--优惠买单',          //收银台中显示的标题
                'fee' => $in['paymoney'],      //收银台中显示需要支付的金额,只能大于 0
                'user' =>$openid,     //付款用户, 付款的用户名(选填项)
            );
            $result=commonGetData::insertlog($params);
            if($result==1){
                $params['shop_id']=$shop_id;
                $this->pay($params);
                exit();
            }

    message('系统繁忙，请您稍候再试');
    }
include $this->template('../mobile/shop');
exit();

