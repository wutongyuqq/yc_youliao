<?php
global $_GPC,$_W;
$cfg=$this->module['config'];
$shopLogo=tomedia($cfg['shop_logo']);
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$userinfo = Member::initUserInfo(); //用户信息
$pageStart =$this->pageIndex();
$num = $this->getNum(); //每次取值的个数
$joinCity=Shop::joinCity();
$cityCondition=$this->getcookieCity();
$joinArea=$this->joinArea();
$condition="";
$codition .=Shop::getShopEndtime();
$orderby="orderby";
if($_GPC['type']=="4"){
    $btn ="去拼团";
    $title=$cfg['index_title'].' 全民拼团';
    $adv_type=4;//1首页，4拼团，5秒杀，6首单，7买单，8同城,9附近，10圈子
    $condition.=" and s.is_group=1 and g.is_group=1 and  g.groupnum>0 ";
}elseif($_GPC['type']=="6") {
    $btn ="首单优惠";
    $title=$cfg['index_title'].' 马上下单';
    $adv_type=6;
    $condition.="  and g.isfirstcut >0 ";
}elseif($_GPC['type']=="7") {
    $btn ="去买单";
    $title=$cfg['index_title'].' 优惠买单';
    $adv_type=7;
    $condition.=" and s.is_discount=1 ";
}elseif($_GPC['type']=="9") {
    $btn ="附近";
    $title=$cfg['index_title'].' 附近商圈';
    $adv_type=9;

}else{
    return;
}


$area = $this->getArea();
$business = $this->getBusiness();
$cate_id=intval($_GPC['cate_id']);
if($cate_id>0){
    $condition.=" and (s.ccate_id =".$cate_id." or  s.pcate_id=".$cate_id.' ) ';
}
$type_id=intval($_GPC['type_id']);
$condition_type=0;
if($_GPC['datatype']=="area"){
    $condition.=" and s.area_id =".$type_id;
    $condition_type=1;
}elseif($_GPC['datatype']=="business"){
    $condition.=" and s.business_id =".$type_id;
    $condition_type=1;
}elseif($_GPC['datatype']=="zn"){
    if($type_id==1){
        $orderby=" s.dp DESC";
    }elseif($type_id==2){
        $orderby=" s.renjun ASC";
    }

}
if($_GPC['type']=="7" || $_GPC['type']=="9") {
    $keyword =$_GPC['keyword'];//关键字搜索
    if($keyword){
        $condition.=" and (s.shop_name like '%".$keyword."%' or s.telphone like '%".$keyword."%' or s.address like '%".$keyword."%' ) ";
    }
    $shopObj=new Shop();
    $isgroup= $shopObj->getShopList($pageStart,$num, $_COOKIE['lat'], $_COOKIE['lng'],$condition.' ' .$cityCondition,$orderby,$condition_type);
}else{
    $isgroup     =Goods:: getGroup($joinCity,$condition,$cityCondition,$pageStart,$num);
}

//拼团用户头像
if($isgroup && $adv_type==4){
    $data1 =array();
    foreach ($isgroup as $key => $value) {
        $data    = pdo_fetchall("SELECT   distinct(o.openid),og.goodsid, u.avatar  FROM " . tablename(ORDER_GOODS)  .
            "og  left join" . tablename(ORDER)  ."o on og.orderid=o.id and og.uniacid=o.uniacid " .
            "left join" . tablename(MEMBER)  ."u on o.userid=u.id and o.uniacid=u.uniacid " .
            "WHERE og.uniacid = '{$_W['uniacid']}' 
				and og.goodsid='{$value['id']}' 
				and o.status>=1
				ORDER BY o.createtime DESC limit {$pageStart},{$num}");
             $data1[$value['id']]=$data;
            $isgroup[$key]['logo']=tomedia($value['logo']);
            $isgroup[$key]['thumb']=tomedia($value['thumb']);

    }


}

if($op=='ajax_req' ){
    if($isgroup){
        echo json_encode(array('status'=>'1','length'=>count($isgroup),'list'=>$isgroup,'data1'=>$data1,'logo'=>$shopLogo));
        exit;
    }else{
        echo json_encode(array('status'=>'0','length'=>0));
        exit;
    }

}


include $this->template('../mobile/list');
exit();