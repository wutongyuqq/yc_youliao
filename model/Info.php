<?php 
class Info{

    public function postInfo($openid,$type=1){
        global $_GPC, $_W;
        $cfg=$this->module['config'];
        $member = pdo_fetch('SELECT nickname,openid,avatar FROM ' . tablename(MEMBER) . " WHERE openid = '{$openid}'");
        if (empty($member)) {
            $resArr['error'] = 1;
            $resArr['message'] = '获取您的信息失败！';
            return $resArr;
        }
        $mid = intval($_GPC['mid']);
        $province = trim($_GPC['province']);
        $city = trim($_GPC['city']);
        $district = trim($_GPC['district']);
        $lng = trim($_GPC['lng']);
        $lat = trim($_GPC['lat']);
        if ($mid <= 0) {
            $resArr['error'] = 1;
            $resArr['message'] = '项目加载失败！';
            return $resArr;
        }
        if (empty($city)) {
            $resArr['error'] = 1;
            $resArr['message'] = '没有获取您所在的地理位置信息！';
            return $resArr;
        }
        $fieldslist = commonGetData::getfield($mid);
        foreach ($fieldslist as $k => $v) {
            $fieldname = $_GPC[$v['enname']];
            if( is_array( $fieldname ) )$fieldname =implode(" ",$fieldname);
            $fieldnamelen = mb_strlen($fieldname,'utf-8');
            if ($v['islenval'] == 1) {
                if ($fieldnamelen < $v['minlen'] || $fieldnamelen > $v['maxlen']) {
                    $resArr['error'] = 1;
                    $resArr['message'] = $v['name'] . '字符长度应该在' . $v['minlen'] . '字符到' . $v['maxlen'] . '字符之间，当前字符长度' . $fieldnamelen;
                    return $resArr;
                }
            }

            if ($v['isrequired'] == 1) {
                if (empty($fieldname)) {
                    $resArr['error'] = 1;
                    $resArr['message'] = '请填写' . $v['name'];
                    return $resArr;
                }
            }
            if ($v['mtype'] == 'telphone' && $v['isrequired'] == 1) {
                if (!util::isMobile($fieldname)) {
                    $resArr['error'] = 1;
                    $resArr['message'] = '请填写正确的手机号码';
                    return $resArr;
                }
            }
            if ($v['mtype'] == 'idcard' && $v['isrequired'] == 1) {
                if (!util::isCreditNo($fieldname)) {
                    $resArr['error'] = 1;
                    $resArr['message'] = '请填写正确的身份证号';
                    return $resArr;
                }
            }
        }

        $moduleres = pdo_fetch('SELECT isshenhe,minusscore,minscore,isneedpay,needpay,fid ,name FROM ' . tablename(CHANNEL) . " WHERE id = {$mid} AND weid = {$_W['uniacid']}");
        if($moduleres['minusscore'] > 0) {
            $credit_res = Member::updateUserCredit($member['openid'], -$moduleres['minusscore'], 7, '发布信息扣除积分');
            //审核不通过时，会把扣除的积分返回
            $credit_info='';
            if (!$credit_res) {
                $resArr['error'] = 1;
                $resArr['message'] = '很抱歉，发布信息需扣除'.$moduleres['minusscore'].'积分，您的积分不足';
                return $resArr;
            }else{
                $credit_info='并已扣除'.$moduleres['minusscore'].'积分';
            }
        }
        if ($moduleres['isneedpay'] == 1) {
            $data['isneedpay'] = 1;
            $tipinfo='提交信息成功'.$credit_info.',去支付'.$moduleres['needpay'].'元';
        }
        $data['weid'] = $_W['uniacid'];
        $data['mid'] = $mid;
        $data['fmid'] = $moduleres['fid'];
        $data['openid'] = $member['openid'];
        $data['nickname'] = $member['nickname'];
        $data['avatar'] = $member['avatar'];
        $data['province'] = $province;
        $data['city'] = $city;
        $data['district'] = $district;
        $data['lng'] = $lng;
        $data['lat'] = $lat;
        unset($_POST['province']);
        unset($_POST['city']);
        unset($_POST['district']);
        unset($_POST['lng']);
        unset($_POST['lat']);
        $data['content'] = serialize( commonGetData::guolv($_POST));
        $data['createtime'] = TIMESTAMP;
        $data['freshtime'] = TIMESTAMP;
        $data['status'] = $moduleres['isshenhe'] == 1 ? 0 : 1;
        $views_num=intval($cfg['views_num']);//随机最高浏览数量
        if($views_num>0) {
            $views = mt_rand(1, $views_num);//根据最大的积分数，随机获取签分
            $data['views'] = $views;
        }

        pdo_insert(INFO, $data);
        $message_id = pdo_insertid();
        $msg_flag=util::getSingelDataInSingleTable(INFO,array('admin_msg'=>1,'mid'=> $message_id),'admin_msg',2);
        if(!empty($msg_flag))return;
       if($moduleres['minusscore'] > 0){
            $datascore['weid'] = $_W['uniacid'];
            $datascore['openid'] = $member['openid'];
            $datascore['num'] = $moduleres['minusscore'];
            $datascore['time'] = TIMESTAMP;
            $datascore['explain'] = '发布信息扣除积分';
           $datascore['type'] =1;
            pdo_insert(INTEGRAL, $datascore);
        }

        if ($moduleres['isneedpay'] == 1 && $moduleres['needpay'] > 0) {
            $ordersn = date('Ymd') . random(7, 1);
            $dataorder['weid'] = $_W['uniacid'];
            $dataorder['from_user'] = $member['openid'];
            $dataorder['ordersn'] = $ordersn;
            $dataorder['price'] = $moduleres['needpay'];
            $dataorder['paytype'] = 1;
            $dataorder['createtime'] = TIMESTAMP;
            $dataorder['message_id'] = $message_id;
            $dataorder['module'] = $mid;
            pdo_insert(INFOORDER, $dataorder);
            $infoorder_id = pdo_insertid();
            $resArr['ispay'] = 1;
            $resArr['ordersn'] = $ordersn;
        } else {
            $resArr['ispay'] = 0;
            $resArr['ordersn'] = '';
        }

        if($moduleres['isneedpay'] == 1){//需要支付
            $resArr['message']=$tipinfo;
            $resArr['ordersn']=$ordersn;
            $resArr['orid']= $infoorder_id ;
        }elseif ($moduleres['isshenhe'] == 1 ) {//需要审核
            $resArr['message'] = '恭喜您，添加信息成功'.$credit_info.'，请等待管理员审核！';
            //模板消息通知管理员审核
            if($type==1){
                $url= Util::createModuleUrl('detail',array('id'=>$message_id));
                $name=$moduleres['name'];
                $msg_res=Message::admin_checkmsg('您有一条审核提醒!',$url,$name,$member['nickname'],TIMESTAMP);
                if( $msg_res==1){
                    pdo_update(INFO, array('admin_msg'=>1), array('id' => $message_id, 'weid' => $_W['uniacid']));
                }
            }

        } else {//无需审核，无需支付
            if ($moduleres['minscore'] > 0) {//需要支付的信息需要支付完成才有积分奖励，需要审核的信息需要审核通过才有积分奖励
               Member::updateUserCredit( $member['openid'],$moduleres['minscore'],6,'发布信息奖励积分');
            }
            $resArr['message'] = '恭喜您，添加信息成功！'.$credit_info;
            pdo_update(INFO, array('admin_msg'=>1), array('id' => $message_id, 'weid' => $_W['uniacid']));
        }
        $resArr['error'] = 0;
        return $resArr;

    }

    public function getInfoById ($id){
        global  $_W;
        $data=pdo_fetch('SELECT * FROM ' . tablename(INFO) . " WHERE weid = {$_W['uniacid']}  AND id = {$id} ");
        return $data;
    }
    public function getInfoByOId ($openid,$id){
        global  $_W;
        $data=pdo_fetch('SELECT * FROM ' . tablename(INFO) . " WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND id = {$id} ");
        return $data;
    }
    public function getInfoByuser ($openid,$where,$page,$num){
        global  $_W;
        $mymessagelist = pdo_fetchall("SELECT * FROM ".tablename(INFO)." WHERE weid = {$_W['uniacid']} AND openid = '{$openid} '{$where} ORDER BY createtime DESC LIMIT ".$page.",".$num);
        return $mymessagelist;
    }

    public function getPayInfoByuser ($openid,$where,$page,$num)
    {
        global $_W;
        $mymessagelist = pdo_fetchall("SELECT i.*,o.createtime as infotime,o.price as infoprice FROM " . tablename(INFOORDER) . " o left join  " . tablename(INFO) . "i on o.message_id=i.id WHERE i.weid = {$_W['uniacid']} AND openid = '{$openid} '{$where} ORDER BY createtime DESC LIMIT " . $page . "," . $num);
        return $mymessagelist;
    }

    public function getTopInfoByuser ($openid,$where,$page,$num)
    {
        global $_W;
        $mymessagelist = pdo_fetchall("SELECT i.*,o.createtime as zdtime,o.price as zdprice  FROM ".tablename(ZDORDER)." o left join  ".tablename(INFO)."i on o.message_id=i.id WHERE i.weid = {$_W['uniacid']} AND i.openid = '{$openid} '{$where} ORDER BY createtime DESC LIMIT ".$page.",".$num);
        return $mymessagelist;
    }

    public function getCollect ($openid,$page,$num,$infosql='')
    {
        global $_W;
        $list = pdo_fetchall("SELECT a.*,b.id as b_id,b.content,b.mid,b.createtime,b.nickname,b.avatar FROM ".tablename(COLLECT)." as a,".tablename(INFO)." as b WHERE a.message_id = b.id AND a.weid = {$_W['uniacid']} AND a.openid = '{$openid}' {$infosql} ORDER BY a.time DESC LIMIT ".$page.",".$num);
        foreach($list as $k=>$v){
            $module = pdo_fetch('SELECT name FROM ' . tablename(CHANNEL) . " WHERE weid = {$_W['uniacid']} AND id = {$v['mid']}");
            $list[$k]['con'] = unserialize($v['content']);
            $list[$k]['modulename'] = $module['name'];
        }
        return $list;
    }

    public function proCollect($openid){
        global $_W ,$_GPC;
        $message_id = intval($_GPC['message_id']);
        $collect = pdo_fetch("SELECT * FROM ".tablename(COLLECT)." WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND message_id = {$message_id}");
        if(!empty($collect)){
            pdo_delete(COLLECT,array('weid'=>$_W['uniacid'],'openid'=>$openid,'message_id'=>$message_id));
            $resArr['error'] = 0;
            $resArr['message'] = "取消收藏成功！";
            return $resArr;
        }else{
            $data['weid'] = $_W['uniacid'];
            $data['openid'] = $openid;
            $data['message_id'] = $message_id;
            $data['time'] = TIMESTAMP;
            pdo_insert(COLLECT,$data);
            $resArr['message'] = "恭喜您，收藏成功！";
            $resArr['error'] = 0;
            return $resArr;
        }
    }


    public static function getEnname($name,$mid){
        $data=util::getSingelDataInSingleTable(FIELDS,array('enname'=>$name,'mid'=>$mid),'name',2);
        return $data['name'];
    }

    public static function getPhone($mid,$feildlist){
        global $_W;
        $hasphonefield = pdo_fetch('SELECT enname FROM ' . tablename(FIELDS) . " WHERE weid = {$_W['uniacid']} AND mid = {$mid} AND mtype = 'telphone'");
        if ($hasphonefield) {
            $phone = $feildlist[$hasphonefield['enname']];
        }
        return $phone;
    }

    static  function getChildrenMsg($id){
        $data=util::getAllDataBySingleTable(CHANNEL,array('fid'=>$id),'displayorder ASC','*',2);
        return $data;
    }
}