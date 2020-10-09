<?php
global $_W, $_GPC;
$title="红包管理";
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {

    if (!empty($_GPC['akid'])) {
        if($_GPC['alldel'] == 'alldel'){
            foreach ($_GPC['akid'] as $id => $akid) {
                pdo_delete(REDPACKAGE, array('r_id' => $id, 'uniacid' => $_W['uniacid']));
            }
            $this->message('红包删除成功！', $this->createWebUrl('info', array('op' => 'display')), 'success');
        }
    }
    $red_condition=' 1=1 ';
    if (!empty($_GPC['keyword'])) {
        $red_condition.= " AND r.content LIKE '%{$_GPC['keyword']}%'";
    }
    if (!empty($_GPC['time']) && $_GPC['time']['start']!='1970-01-01') {
        $red_condition.= " and r.create_time >=" . strtotime($_GPC['time']['start']) . " and r.create_time <=" . strtotime($_GPC['time']['end']) . " ";
    }
    $city_id=$this->getAdmin_city_id();
    if($city_id>0){
        $red_condition.=" and r.city_id=".$city_id;
    }
    $status=intval($_GPC['status']);
    if($status==1){//已领完
        $red_condition.=" and r.total_num=r.send_num";
    }else if($status==2){//未领完
        $red_condition.=" and r.total_num>r.send_num";
    }
    $page = $this->getPage();
    $pageindex = $this->getWebPage();
    $num = $this->getNum(); //每次取值的个数
    $list=Redpackage::getRedpackage($pageindex,$num,$red_condition);
    $total=Redpackage::getRedpackageNum($red_condition);
	$pager = pagination($total, $page, $num);

}elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
    $city_id=$this->getAdmin_city_id();
    $red_condition ="  r.red_id=".$id;
    if($city_id>0){
        $red_condition="  r.city_id=".$city_id;
    }
    $getredpackageData=Redpackage::getRedpackageRecords("red_id=".$id);
    $data=Redpackage::getRedpackage(0,1,$red_condition);
    $item =$data[0];
    //群发消息
    $redMsg_y=util::getAllDataBySingleTable(REDMSG,array('uniacid' =>$_W['uniacid'],'red_id' =>$id,'status' =>1),' id asc');
    $redMsg_n=util::getAllDataBySingleTable(REDMSG,array('uniacid' =>$_W['uniacid'],'red_id' =>$id,'status' =>0),' id asc');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$message = pdo_fetch("SELECT red_id FROM ".tablename(REDPACKAGE)." WHERE red_id = {$id} AND uniacid= {$_W['uniacid']}");
	if (empty($message)) {
        $this->message('抱歉，红包不存在或是已经被删除！', $this->createWebUrl('red', array('op' => 'display')), 'error');
	}
	pdo_delete(REDPACKAGE, array('red_id' => $id,'uniacid'=>$_W['uniacid']));

    $this->message('红包删除成功！', $this->createWebUrl('red', array('op' => 'display')), 'success');
}
include $this->template('web/red');


exit;
?>