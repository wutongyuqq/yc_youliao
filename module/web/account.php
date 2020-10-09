<?php
clear_cook();
global $_GPC,$_W;
$title='资金管理';
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pindex    = $this->getWebPage();
$page  = $this->getPage();
$psize     =$this->getWebNum();
$shop_where['shop_id>']=0;
$search_type=3;
$shop=util::getAllDataBySingleTable(SHOP,array(status=>1),'shop_id,shop_name');
$starttime = strtotime($_GPC['time']['start']);
$endtime   = strtotime($_GPC['time']['end']);
$sqlwhere='';
$shop_id=intval($_GET['shop_id']);
if($shop_id){
    $sqlwhere.=" and shop_id =".$shop_id;
}
$type     =intval($_GPC['type']) ;
if($op=='display'){

    if($starttime){
        $sqlwhere.=' and createtime >='.$starttime;
    }
    if($endtime){
        $sqlwhere.=' and createtime <='.$endtime;
    }
    if($_GPC['ordersn']){
        $sqlwhere.=" and ordersn LIKE '%{$_GPC['ordersn']}%'";
    }

    //订单记录
    if($type==0) {
        $list = pdo_fetchall('SELECT * FROM ' . tablename(ORDER_RE) . " WHERE uniacid = {$_W['uniacid']}   {$sqlwhere} ORDER BY id DESC LIMIT  {$pindex},{$psize } ");
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename(ORDER_RE) . " WHERE uniacid = {$_W['uniacid']}   {$sqlwhere}  ");
        $pager = pagination($total, $page, $psize);

    }elseif($type==1) {
        //买单记录
        $sqlwhere .= ' and status=1';
        $list2 = pdo_fetchall('SELECT * FROM ' . tablename(DISCOUNT_RE) . " WHERE uniacid = {$_W['uniacid']}   {$sqlwhere} ORDER BY id DESC LIMIT  {$pindex},{$psize} ");
        $total2 = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename(DISCOUNT_RE) . " WHERE uniacid = {$_W['uniacid']}   {$sqlwhere}  ");
        $pager2 = pagination($total2, $page, $psize);
    }elseif($type==2){
        $list= pdo_fetchall("SELECT i.*,o.createtime as infotime,o.price ,o.ordersn,o.message_id FROM ".tablename(INFOORDER)." o left join  ".tablename(INFO)."i on o.message_id=i.id WHERE i.weid = {$_W['uniacid']} {$sqlweher} ORDER BY createtime DESC LIMIT   {$pindex},{$psize} ");
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename(INFOORDER) . " WHERE weid = {$_W['uniacid']}   {$sqlwhere}  ");
        $pager= pagination($total, $page, $psize);
    }elseif($type==3){
        $list= pdo_fetchall("SELECT i.*,o.createtime as zdtime,o.price ,o.ordersn,o.message_id FROM ".tablename(ZDORDER)." o left join  ".tablename(INFO)."i on o.message_id=i.id WHERE i.weid = {$_W['uniacid']} {$sqlweher} ORDER BY createtime DESC LIMIT  {$pindex},{$psize} ");
        $total= pdo_fetchcolumn('SELECT count(*) FROM ' . tablename(ZDORDER) . " WHERE weid = {$_W['uniacid']}   {$sqlwhere}  ");
        $pager = pagination($total, $page, $psize);
    }

}elseif($op=='record'){
    if($starttime){
        $sqlwhere.=' and addtime >='.$starttime;
    }
    if($endtime){
        $sqlwhere.=' and addtime <='.$endtime;
    }
    if($_GPC['ordersn']){
        $sqlwhere.=" and ordersn LIKE '%{$_GPC['ordersn']}%'";
    }

    $sqlwhere.=" and type =".$type;
    $data=Shop::getSingleAccount('',0,$sqlwhere,$pindex,$psize);
    $list=$data[0];
    $total=$data[1];
    $pager = pagination($total, $page, $psize);
}elseif($op=='check_post'){
    if($starttime){
        $sqlwhere.=' and a.createtime >='.$starttime;
    }
    if($endtime){
        $sqlwhere.=' and a.createtime <='.$endtime;
    }
    if($_GPC['ordersn']){
        $sqlwhere.=" and a.ordersn LIKE '%{$_GPC['ordersn']}%'";
    }

    $status = intval($_GPC['status']);

    $sqlwhere.=" and a.type =".$type;
    $data=Admin::getAccount_applay('',$status,$pindex,$psize,$sqlwhere);
    $list=$data[0];
    $total=$data[1];
    $pager = pagination($total, $page, $psize);
}elseif($op=='detail'){
    $a_data =1;
    $id = intval($_GPC['cash_id']);
    $item=commonGetData::getAccountDetail($id);
}
include $this->template('web/account');
exit();