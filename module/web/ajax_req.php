<?php
global $_GPC,$_W;
//if(!$_W['isajax']) die;
$op=$_GPC['op'];
$shop=new Shop();
if($op == 'changearea'){
    $city_id = intval($_GPC['id']);
    if( $city_id){
        $condition="and city_id=".$city_id;
    }
    $list = pdo_fetchall(" SELECT area_id as id,area_name as name FROM " . tablename(AREA) . " WHERE  uniacid = '{$_W['uniacid']}' {$condition} ORDER BY  orderby asc");
    if (!empty($list)) {
        echo json_encode(array('status'=>'1','list'=>$list,));
    }else{
        echo json_encode(array('status'=>'2','str'=>'暂时没有数据'));
    }

    exit;

}if($op == 'changearea2'){
    $city_id = intval($_GPC['id']);

    if( $city_id){
        $condition="and city_id=".$city_id;
    }
    $list = pdo_fetchall(" SELECT area_id as id,area_name as name FROM " . tablename(AREA) . " WHERE  uniacid = '{$_W['uniacid']}' {$condition} and (parent_id is null or parent_id=0) ORDER BY  orderby asc");

    if (!empty($list)) {
        echo json_encode(array('status'=>'1','list'=>$list,));
    }else{
        echo json_encode(array('status'=>'2','str'=>'暂时没有数据'));
    }

    exit;

}elseif($op == 'changebusiness'){
    $area_id = intval($_GPC['id']);
    if( $area_id){
        $condition="and parent_id=".$area_id;
    }
    $list = pdo_fetchall(" SELECT area_id as id,area_name as name FROM " . tablename(AREA) . " WHERE  uniacid = '{$_W['uniacid']}' {$condition}  and (parent_id is not null or parent_id!=0) ORDER BY  orderby asc");

    if (!empty($list)) {
        echo json_encode(array('status'=>'1','list'=>$list,));
    }else{
        echo json_encode(array('status'=>'2','str'=>'暂时没有数据'));
    }

    exit;

}elseif($op == 'changeline'){
    $cate_id = intval($_GPC['id']);
    if( $cate_id){
        $condition="and parent_id=".$cate_id;
    }

    $list = pdo_fetchall("SELECT cate_id as id,cate_name as name FROM " . tablename(CATE) . " WHERE uniacid = '{$_W['uniacid']}' {$condition}  ORDER BY orderby DESC");

    if (!empty($list)) {
        echo json_encode(array('status'=>'1','list'=>$list,));
    }else{
        echo json_encode(array('status'=>'2','str'=>'暂时没有数据'));
    }

exit;

}elseif($op=='check_shop_apply'){

    $check_admin=$shop->checkouth('');
    if($check_admin==1){
        echo json_encode(array('status'=>'2','str'=>'您没有权限'));
    }
    $shop_id = intval($_GPC['shop_id']);
    $opt= intval($_GPC['opt']);
    $type= intval($_GPC['type']);//1店铺入驻, 2首页推荐, 3秒杀专场, 4拼团, 5优惠买单
    if($type==1){
        $data = array('status' => $opt);
        $remark.='申请类型：店铺入驻';
    }elseif($type==2){
        $remark.='申请类型：首页推荐';
        $data = array('is_hot' => $opt);
    }elseif($type==3){
        $remark.='申请类型：秒杀专场';
        $data = array('is_time' => $opt);
    }elseif($type==4){
        $remark.='申请类型：全民拼团';
        $data = array('is_group' => $opt);
    }elseif($type==5){
        $remark.='申请类型：优惠买单';
        $data = array('is_discount' => $opt);
    }
    if($opt==1){
        $i_item='恭喜您，店铺申请审核已通过';
        $status_info='审核通过';
    }else{
        $i_item='很抱歉，店铺申请审核未通过';
        $status_info='审核未通过';
    }
    //更新状态
    pdo_update(SHOP, $data, array('shop_id' =>$shop_id, 'uniacid' => $_W['uniacid']));
    //删除申请
    pdo_delete(SHOP_APPLY, array('shop_id' => $shop_id,'f_type' =>$type,'uniacid' => $_W['uniacid']));
    //循环发送通知
    $reason=$_GPC['reason'];
    if($reason){
        $remark .= '\\n';
        $remark.='未通过原因：'.$reason;
    }
    $url= Util::createModuleUrl('shop_admin',array('shop_id'=>$shop_id));
    $info_where['shop_id']=$shop_id;
    $shop_name=$shop->getShop_name($shop_id);
    $shop_admin=$shop->getShopAdmin($shop_id);
    foreach ($shop_admin as $k=> $v){
        if($v['openid']){
            Message::admin_checkmessage($i_item,$url,$v['openid'],$status_info,$shop_name,$remark);
        }

    }
    echo json_encode(array('status'=>'1','str'=>'操作成功！'));
    exit;
} elseif ($op == 'status') {
    $check_admin=$shop->checkouth('');
    if($check_admin==1){
        echo json_encode(array('status'=>'2','str'=>'您没有权限'));
    }
    $id = intval($_GPC['shop_id']);
    $opt= intval($_GPC['opt']);
    $data = array('status' => $opt);
    pdo_update(SHOP, $data, array('shop_id' => $id, 'uniacid' => $_W['uniacid']));
    if($opt==1){
        $info="开启店铺";
    }else {
        $info = "暂停店铺";
    }
    echo json_encode(array('status'=>'1','str'=>$info.'操作成功！'));
    exit;
}elseif ($op == 'is_hot') {
    $check_admin=$shop->checkouth('');
    if($check_admin==1){
        echo json_encode(array('status'=>'2','str'=>'您没有权限'));
    }
    $id = intval($_GPC['shop_id']);
    $opt= intval($_GPC['opt']);
    $data = array('is_hot' => $opt);
    pdo_update(SHOP, $data, array('shop_id' => $id, 'uniacid' => $_W['uniacid']));
    if($opt==1){
        $info="首页推荐";
        pdo_delete(SHOP_APPLY, array('shop_id' => $id,'f_type' => '2','uniacid' => $_W['uniacid']));
        //'0无申请 1入驻申请，2首页推荐，3秒杀，4拼团，5优惠买单'
    }else {
        $info = "取消首页推荐";
    }   
    echo json_encode(array('status'=>'1','str'=>$info.'操作成功！'));
    exit;
}elseif ($op == 'is_group') {
    $check_admin=$shop->checkouth('');
    if($check_admin==1){
        echo json_encode(array('status'=>'2','str'=>'您没有权限'));
    }
    $id = intval($_GPC['shop_id']);
    $opt= intval($_GPC['opt']);
    $data = array('is_group' => $opt);
    pdo_update(SHOP, $data, array('shop_id' => $id, 'uniacid' => $_W['uniacid']));
    if($opt==1){
        $info="开启拼团";
        pdo_delete(SHOP_APPLY, array('shop_id' => $id,'f_type' => '4','uniacid' => $_W['uniacid']));
        //'0无申请 1入驻申请，2首页推荐，3秒杀，4拼团，5优惠买单'
    }else {
        $info = "关闭拼团";
    }    
    echo json_encode(array('status'=>'1','str'=>$info.'操作成功！'));
    exit;
}elseif ($op == 'is_discount') {
    $check_admin=$shop->checkouth('');
    if($check_admin==1){
        echo json_encode(array('status'=>'2','str'=>'您没有权限'));
    }
    $id = intval($_GPC['shop_id']);
    $opt= intval($_GPC['opt']);
    $data = array('is_discount' => $opt);
    pdo_update(SHOP, $data, array('shop_id' => $id, 'uniacid' => $_W['uniacid']));
    if($opt==1){
        pdo_delete(SHOP_APPLY, array('shop_id' => $id,'f_type' => '5','uniacid' => $_W['uniacid']));
        //'0无申请 1入驻申请，2首页推荐，3秒杀，4拼团，5优惠买单'
        $info="开启优惠买单";
    }else {
        $info = "关闭优惠买单";
    }
    echo json_encode(array('status'=>'1','str'=>$info.'操作成功！'));
    exit;
}elseif ($op == 'is_time') {
    $check_admin=$shop->checkouth('');
    if($check_admin==1){
        echo json_encode(array('status'=>'2','str'=>'您没有权限'));
    }
    $id = intval($_GPC['shop_id']);
    $opt= intval($_GPC['opt']);
    $data = array('is_time' => $opt);
    pdo_update(SHOP, $data, array('shop_id' => $id, 'uniacid' => $_W['uniacid']));
    if($opt==1){
        $info="开启秒杀";
        pdo_delete(SHOP_APPLY, array('shop_id' => $id,'f_type' => '3','uniacid' => $_W['uniacid']));
    }else {
        $info = "关闭秒杀";
    }
    echo json_encode(array('status'=>'1','str'=>$info.'操作成功！'));
    exit;
}elseif ($op == 'addtime') {
    $timestart = intval($_GPC['timestart']);
    $timeend = intval($_GPC['timeend']);
    $timenum= pdo_fetch("SELECT count(*)as num FROM ".tablename(MSTIME)." WHERE uniacid = {$_W['uniacid']} and timestart ={$timestart} and timeend= {$timeend}  ");
    if($timenum['num']>=1){
        echo json_encode(array('status'=>'0','str'=>'专场已存在'));
        exit;
    }
    $data = array('timestart' => $timestart,'timeend' => $timeend,'uniacid' => $_W['uniacid']);
    $res =pdo_insert(MSTIME, $data);
    $id = pdo_insertid();
    echo json_encode(array('status'=>'1','id'=>$id));
    exit;
}elseif ($op == 'deletetime') {
    $time_id = intval($_GPC['id']);
    pdo_delete(MSTIME, array('time_id' => $time_id,'uniacid' => $_W['uniacid']));
    echo json_encode(array('status'=>'1'));
    exit;
}elseif ($op == 'admin_status') {//店铺管理员状态
    $admin_id = intval($_GPC['admin_id']);
    $opt= intval($_GPC['opt']);
    $data = array('status' => $opt);
    pdo_update(SHOP_ADMIN, $data, array('admin_id' => $admin_id, 'shop_id' =>getShop_id(),'uniacid' => $_W['uniacid']));
    echo json_encode(array('status'=>'1','str'=>'操作成功',));
    exit;
}elseif ($op == 'a_admin_status') {//管理员
    $admin_id = intval($_GPC['admin_id']);
    $opt= intval($_GPC['opt']);
    $data = array('status' => $opt);
    pdo_update(ADMIN, $data, array('admin_id' => $admin_id, 'uniacid' => $_W['uniacid']));
    echo json_encode(array('status'=>'1','str'=>'操作成功',));
    exit;
}elseif ($op == 'msg_flag') {
    $admin_id = intval($_GPC['admin_id']);
    $opt= intval($_GPC['opt']);
    $data = array('msg_flag' => $opt);
    pdo_update(SHOP_ADMIN, $data, array('admin_id' => $admin_id, 'shop_id' => getShop_id(),'uniacid' => $_W['uniacid']));
    echo json_encode(array('status'=>'1','str'=>'操作成功',));
    exit;
}elseif ($op == 'a_msg_flag') {
    $admin_id = intval($_GPC['admin_id']);
    $opt= intval($_GPC['opt']);
    $data = array('msg_flag' => $opt);
    pdo_update(ADMIN, $data, array('admin_id' => $admin_id, 'uniacid' => $_W['uniacid']));
    echo json_encode(array('status'=>'1','str'=>'操作成功',));
    exit;
}elseif( $_GPC['op'] == 'queue'){
    //Util::deleteCache('sq_queue','sq_q');//===调试
    for( $i = 0;$i<3;$i++ ){
        $cache = Util::getCache('sq_queue','sq_q');
        if( empty( $cache ) || $cache['time'] < (time()- 40) ){
            if( $i == 2 ){
                $url = Util::createModuleUrl('message',array('op'=>1));
                $res = Util::http_request($url);
                die('----status:0----');
            }
            sleep(1);
        }else{
           die('----status:1----');
        }

       }
    echo json_encode(array('status'=>'3','str'=>'该请求没有数据'+$_GPC['op'],));
    exit;
}elseif( $_GPC['op'] == 'recash'){//付款给申请的商户
    //验证是否管理员
    $openid = $_GPC['openid'];
    $check_admin=0;
    if($openid){
        $a_data=$this->checkAdmin($openid);
        if(empty($a_data) ){
            echo json_encode(array('status'=>'0','str'=>'您不是管理员或管理员身份已失效',));
            exit;
        }
        $check_admin=$a_data['admin_id'];
    }elseif( $_W['isfounder']!=1){
        echo json_encode(array('status'=>'0','str'=>'此项操作必须站长权限',));
        exit;
    }
    //验证是否存在该提现申请
    $id = intval($_GPC['id']);
    $item=commonGetData::getAccountDetail($id,' and a.status=0');
    $shop_id=intval($item['shop_id']);
    if(empty($item)){
        echo json_encode(array('status'=>'0','str'=>'申请不存在或已处理'));
        exit;
    }
    //1同意申请 2驳回申请
    $type = intval($_GPC['type']);
    if($type==1 && $item['paytype']==1){//同意申请：微信（企业直接付给个人）支付
        $amount=$item['amount'];
        $desc=$this->module['config']['index_title'];
        if($shop_id>0){
            $desc.='-商户提现';
        }else{
            $desc.='-会员提现';
        }
        if(empty($desc)){
            $desc= $_W['uniaccount']['name'];
        }
        $core=new Core();
        $res=$core->wechat_MchPay($item['openid'], $amount, $item['ordersn'],$desc );
        if($res['status']==0){
            echo json_encode(array('status'=>'0','str'=>$res['str']));
            exit;
        }
    }elseif( $type==1 && $item['paytype']==4){//同意申请：微信（企业直接付给个人）支付)
        if($item['type']=='1'){
           $tag='用户提现至余额' ;
        }else if($item['type']=='1'){
            $tag='商户提现至余额' ;
        }
        $re = Member::updateUserMoney($item['openid'], $item['amount'], 6, $tag);
        $credits = commonGetData::getUserCreditList($item['openid']);
        $_fee=floatval($credits['credit2']);
        $word="恭喜您，提现：".$item['amount']."元现金已到账\\n您现在的余额为：". $_fee."元";
        $this->rep_text($item['openid'], $word);
    }
    //更新状态
    $reason=$_GPC['reason'];//驳回原因
    pdo_update(ACCOUNT, array('status'=>$type,'reason'=> $reason,'checktime'=>TIMESTAMP,'check_admin'=>$check_admin), array('cash_id' => $id,'uniacid' => $_W['uniacid']));
    if($type==2){//驳回申请，把余额也返回，并通知用户
        if( $shop_id>0){
            $shop=Shop::getShopInfo($shop_id);
            $acc=$item['amount']+$item['transfer'];
            $balance=$shop['balance']+$acc;//返回余额
            pdo_update(SHOP, array('balance' =>$balance), array('shop_id' =>$shop_id,'uniacid'=>$_W['uniacid']));
        }else{//用户提现返回
            $member=Member::getMemberByopenid($item['openid']);
            $acc=$item['amount']+$item['transfer'];
            $balance=$member['balance']+$acc;//返回余额
            pdo_update(MEMBER, array('balance' =>$balance), array('openid' =>$item['openid'],'uniacid'=>$_W['uniacid']));
        }
        $userinfo = Member::getSingleUser($item['openid']); //用户信息


        if($reason){
            $remark.='未通过原因：'.$reason;
            $remark .= '\\n';
        }
        $remark.='点击查看详情';
        message::amount($item['openid'],'很抱歉，您的提现申请被驳回，余额已原路返还',$acc,$userinfo['nickname'],TIMESTAMP,$id,intval($item['paytype']),$remark);
    }
    echo json_encode(array('status'=>'1'));
    exit;
}elseif($op == 'getChildCate'){//查询同城分类的子类
    $id = intval($_GPC['id']);
    $data=util::getAllDataBySingleTable(CHANNEL,array('fid'=>$id),'displayorder asc ','id,name,thumb','2');
    foreach ($data as $k=> $v){
        if($v['thumb']){
            $data[$k]['thumb']=tomedia($v['thumb']);
        }
    }
    echo json_encode(array('status'=>'1','list'=>$data,'len'=>count($data)));
    exit;
}elseif($op=='getChanne'){
    $flag=$_GPC['flag'];
    $condition='';
    if($flag=='msglist'){
        $flag_num=2;
    }else{
        $flag_num=1;
    }
    $list= commonGetData::getChannel(2,$flag_num);
    echo json_encode(array('status'=>'1','list'=>$list,'len'=>count($list)));
    exit;
}elseif($op=='getAddress'){
    $address = pdo_fetchall("select * from " . tablename(ADDRESS) . " where  uniacid='{$_W['uniacid']}' and openid='{$_W['openid']}' order by id desc ");
    echo json_encode(array('status'=>'1','list'=>$address,'len'=>count($address)));
    exit;
}elseif($op=='getShopCate'){
    $pid=intval($_GPC['pid']);
    if($pid>0){
        $list = Shop:: getCcateBypid($pid);
    }else{
        $list = Shop::getcate();
    }

    echo json_encode(array('status'=>'1','list'=>$list,'len'=>count($list)));
    exit;
}elseif ($op=='user_setting'){
    $status= intval($_GPC['status']);
    $mid= intval($_GPC['mid']);
    $name=$_GPC['name'];
    if($mid==0 || empty($name)){
        echo json_encode(array('status'=>'0','str'=>'操作失败'));
        exit;
    }
    $data = array($name =>$status);
    $id= intval($_GPC['setting_id']);
    if($id==0){
        $data['mid'] =  $mid;
        $data['uniacid'] = $_W['uniacid'];
        pdo_insert(USER_SET, $data);
    }else{
        pdo_update(USER_SET, $data, array('mid' => $mid,'uniacid' => $_W['uniacid']));
    }

    echo json_encode(array('status'=>'1','str'=>'操作成功'));
    exit;
}elseif  ($op=='animation_list'){
    //id,val,logo

    //查询今天的订单  and DATE_FORMAT(FROM_UNIXTIME(o.createtime),'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')
    $order=  pdo_fetchall('SELECT m.nickname as name ,m.avatar as thumb ,o.createtime,o.id as order_id FROM ' . tablename(ORDER) . " o  left join ". tablename(MEMBER) . " m on m.openid=o.openid and o.uniacid=m.uniacid WHERE o.uniacid = {$_W['uniacid']}"
        ."  order by  o.createtime  desc limit 5 " );

    //查询今天发布的贴子  发布新消息 and DATE_FORMAT(FROM_UNIXTIME(createtime),'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')
    $info=   pdo_fetchall('SELECT nickname as name ,avatar as thumb,createtime,id as info_id FROM ' . tablename(INFO) . " WHERE weid = {$_W['uniacid']}  order by createtime  DESC  limit 5 " );

    //查询今天入驻的用户  申请入驻  and DATE_FORMAT(FROM_UNIXTIME(starttime),'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')
    $shop=   pdo_fetchall('SELECT shop_name as name,logo  as thumb, starttime as createtime ,shop_id  FROM ' . tablename(SHOP) . " WHERE uniacid = {$_W['uniacid']}  order by  starttime desc  limit 5 " );

    if($order && $info){//订单和信息都有数据的时候就合并
        $data= array_merge($order, $info);
    }elseif($order){
        $data= $order;
    }elseif($info){
        $data= $info;
    }

    if($data && $shop){
        $data= array_merge($data, $shop);
    }elseif($shop){
        $data= $shop;
    }
    if($data) {
        foreach ($data as $k => $v) {
            if($v['thumb']){
                $data[$k]['thumb'] = tomedia($v['thumb']);
            }

            if($v['order_id']){
                $data[$k]['val']=$v['name'].'下单成功' ;
                if(empty($v['thumb'])){
                    $data[$k]['thumb'] = STYLE.'images/tx7.gif';
                }
            }elseif($v['info_id']){
                $data[$k]['val']=$v['name'].'发布新消息' ;
                if(empty($v['thumb'])){
                $data[$k]['thumb'] = STYLE.'images/fabu_1.png';
            }
            }elseif($v['shop_id']){
                $data[$k]['val']=$v['name'].'申请入驻' ;
                if(empty($v['thumb'])){
                    $data[$k]['thumb'] = STYLE.'images/fabu_2.png';
                }
            }
            $flag[] = $v["createtime"];
        }
        array_multisort($flag, SORT_DESC, $data);
    }
    echo json_encode($data);
    exit;

}


