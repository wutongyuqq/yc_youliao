<?php

/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2017/11/2
 * Time: 10:05
 */

class Redpackage
{
    static function getRedpackage($pageStart,$num,$condition){
        //获取指定状态的福利红包列表
        global $_W;
        $list = pdo_fetchall("SELECT r.*,u.avatar,u.nickname FROM " . tablename(REDPACKAGE) . " r  left join ". tablename(MEMBER) . "  u on r.openid=u.openid  and r.uniacid = u.uniacid WHERE  {$condition} and  r.uniacid=".$_W['uniacid']."  ORDER BY  red_id desc  limit {$pageStart},{$num}  ");
        return $list;
    }
    static function getRedpackageNum($condition){
        //获取指定状态的福利红包列表
        global $_W;
        $list =  pdo_fetchcolumn("SELECT count(*) FROM " . tablename(REDPACKAGE) . " r  left join ". tablename(MEMBER) . "  u on r.openid=u.openid  and r.uniacid = u.uniacid WHERE  {$condition} and  r.uniacid=".$_W['uniacid']."  ORDER BY  red_id desc   ");
        return $list;
    }
    static function getRedList($pageStart,$num,$lat, $lng,$type=0){
        $codition="r.status=1";
        if($type==1){
            $codition .=" and r.send_num< r.total_num";
        }
        $list_ch= Redpackage::getRedpackage($pageStart,$num,$codition);
        $list = array();
        foreach($list_ch as $k => $v) {
            $v['xsthumb'] = json_decode($v['xsthumb']);
            foreach ((array)$v['xsthumb'] as $a => $b) {
                if($b){
                    $v['xsthumb'] [$a] = tomedia($b);
                }
            }

            $d = util::getDistance($lat, $lng, $v['lat'], $v['lng']);
            if (!empty($v['distance']) && $v['distance'] > 0) {
                if ($v['distance'] > $d) {
                    $v['distance'] = $d;
                    $list[] = $v;
                }
            }else{
                $v['distance'] = $d;
                $list[] = $v;
            }

        }
        return $list;
    }
    static function getRedpackageRecords($condition){
        //获取指定状态的福利红包列表
        global $_W;
        $list = pdo_fetchall("SELECT r.*,u.avatar,u.nickname FROM " . tablename(GETREDPACKAGE) . " r  left join ". tablename(MEMBER) . "  u on r.openid=u.openid  and r.uniacid = u.uniacid WHERE  {$condition} and  r.uniacid=".$_W['uniacid']."  ORDER BY r.create_time");
        return $list;
    }

    public function getShangRecordList($openid,$pageIndex,$num)
    {
        global $_W;
        $list = pdo_fetchall("select * from  " .tablename(CASH). " where cash_type >=2 and status=1 and uniacid=" . $_W['uniacid'] . " and openid ='" . $openid."' order by cash_id desc limit {$pageIndex},{$num}");
        if( $list) {
            foreach ($list as $k => $v) {
                //收到打赏
                $list[$k]['type'] = 3;
                $userinfo = Member::getMemberByopenid($v['from_openid']);
                $list[$k]['avatar'] = $userinfo['avatar'];
                $list[$k]['nickname'] = $userinfo['nickname'];
                $list[$k]['create_time'] = date('Y-m-d H:i', $v['create_time']);
            }
        }
        return $list;
    }

    public function sendShangRecordList($openid,$pageIndex,$num)
    {
        global $_W;
        $list = pdo_fetchall("select * from  " .tablename(CASH) . " where cash_type >=2 and status=1 and uniacid=" . $_W['uniacid'] . " and from_openid ='" . $openid."' order by cash_id desc limit {$pageIndex},{$num} " );
        if( $list) {
            foreach ($list as $k => $v) {
                //发出打赏
                $list[$k]['type'] = 2;
                $userinfo = Member::getMemberByopenid($v['openid']);
                $list[$k]['avatar'] = $userinfo['avatar'];
                $list[$k]['nickname'] = $userinfo['nickname'];
                $list[$k]['create_time'] = date('Y-m-d H:i', $v['create_time']);
            }
        }
        return $list;
    }

    public function getRedpackageRecordList($openid,$pageIndex,$num)
    {
        global $_W;
        $list = pdo_fetchall("select * from  " . tablename(CASH) . " where cash_type= 1 and uniacid=" . $_W['uniacid'] . " and openid ='" . $openid."' order by cash_id desc limit {$pageIndex},{$num}  ");
        foreach ($list as $k => $v) {
            //抢到的红包
            $list[$k]['type'] = 1;
            $userinfo = Member::getMemberByopenid($v['from_openid']);
            $list[$k]['avatar'] = $userinfo['avatar'];
            $list[$k]['nickname'] = $userinfo['nickname'];
            $list[$k]['create_time'] =date('Y-m-d H:i',$v['create_time']);
        }
        return $list;
    }
    public function getRedpackageList($type,$pageIndex,$num)
    {
        global $_W;
        if ($type==1){//抢钱榜
            $where=' and cash_type =1 group by openid';
        }else{//土豪
           $where=' and cash_type >1 and status=1 group by from_openid ';
        }
        $list = pdo_fetchall("select *,sum(amount) from  " . tablename(CASH) . " where  uniacid=" . $_W['uniacid'] . " {$where}    order by amount desc limit {$pageIndex},{$num}  ");
        foreach ($list as $k => $v) {
            //抢到的红包
            $list[$k]['type'] = 1;
            $userinfo = Member::getMemberByopenid($v['from_openid']);
            $list[$k]['avatar'] = $userinfo['avatar'];
            $list[$k]['nickname'] = $userinfo['nickname'];
            $list[$k]['create_time'] =date('Y-m-d H:i',$v['create_time']);
        }
        return $list;
    }

    public function sendRedpackageRecordList($openid,$pageIndex,$num)
    {
        global $_W;
        $list = pdo_fetchall("select * from  " . tablename(REDPACKAGE) . " where  status=1 and uniacid=" . $_W['uniacid'] . " and openid ='" . $openid."' order by red_id desc limit {$pageIndex},{$num}");
        foreach ($list as $k => $v) {
            //抢到的红包
            $list[$k]['type'] = 1;
            $userinfo = Member::getMemberByopenid($v['openid']);
            $list[$k]['avatar'] = $userinfo['avatar'];
            $list[$k]['nickname'] = $userinfo['nickname'];
            $list[$k]['amount'] =  $list[$k]['total_amount'];
            $list[$k]['create_time'] =date('Y-m-d H:i',$v['create_time']);
        }
        return $list;
    }

    public function getRingRed_recordList($id,$type)//type 2圈子 3信息
    {
        global $_W;
        $list = pdo_fetchall("select * from  " . tablename(CASH) . " where cash_type= {$type} and uniacid=" . $_W['uniacid'] . " and type_id={$id} and status=1 order by cash_id desc  ");
        foreach ($list as $k => $v) {
            //抢到的红包
            $list[$k]['type'] = 1;
            $userinfo = Member::getMemberByopenid($v['from_openid']);
            $list[$k]['avatar'] = $userinfo['avatar'];
            $list[$k]['nickname'] = $userinfo['nickname'];
            $list[$k]['create_time'] =date('Y-m-d H:i',$v['create_time']);
        }
        return $list;
    }
    /**
     * @param $uniacid
     * @param $amount    金额
     * @param $toOpenid  收款人openid
     * @param $type_id   关联id
     * @param $fromOpenid 付款人openid
     * @param $cashType   打赏类型，目前支持1、抢红包，2圈子打赏
     */
    public static function addCashRecord($uniacid,$amount,$toOpenid,$type_id,$fromOpenid,$cashType,$totalAmount){
        $data = array(
            'uniacid' =>$uniacid,
            'amount' => $amount/100,
            'create_time' => time(),
            'openid' => $toOpenid,
            'type_id' => $type_id,
            'from_openid' =>$fromOpenid ,
            'cash_type' =>$cashType
        );
        $result=pdo_insert(CASH, $data);
        //更新用户总提现金额
        pdo_update(MEMBER, array('balance'=>$totalAmount), array('openid' => $toOpenid, 'uniacid' => $uniacid));
    }

    public static function checkRed($id,$userinfo){
        $openid=$userinfo['openid'];
        global $_W;
//      $user = pdo_fetch('SELECT * FROM ' . tablename(GETREDPACKAGE) . " WHERE uniacid =:uniacid AND (openid =:openid or  openid =:openid_wxapp ) and red_id=:red_id",array(':uniacid'=>$_W['uniacid'],':openid'=>$v['openid'],':openid_wxapp'=>$v['openid_wxapp'],':red_id'=>$id));
            $userData=util::getSingelDataInSingleTable(GETREDPACKAGE, array('red_id' => $id,'openid' => $openid,'uniacid' => $_W['uniacid']));
        return $userData;
    }
    public static function snatchRed($pageStart,$num,$id, $userinfo){
        global $_W,$_GPC;
        $openid=$userinfo['openid'];
        if(empty($openid)){
            return array('code' => '10006','str' => '用户openid不能为空');
        }
        $redpackageData= Redpackage::getRedpackage($pageStart,$num,"r.red_id=".$id);
        $getredpackageData=Redpackage::getRedpackageRecords("red_id=".$id);
        $userData=self::checkRed($id,$userinfo);
        if(empty($redpackageData)){
            //对应福利红包不存在
           return array('code' => '1001','str' => '很抱歉，红包不存在');
        }
        if(!empty($userData)){
            //重复领取
            return array('code' => '1005','str' => '很抱歉，您已领取过红包');
        }
        $getcount = count($getredpackageData);
        $getmoney = explode(',',$redpackageData[0]['rob_plan']);
        $getmoney =$getmoney[$getcount];
        if(!empty($getredpackageData) && $getcount>=$redpackageData[0]['total_num']){
            //福利红包已经领取完
            return array('code' => '1002','str' => '很抱歉，红包已领完');
        }
        if($redpackageData[0]['model']==2){
            if($redpackageData[0]['kouling']!=$_GPC['kouling']){
                //福利红包口令错误
                return array('code' => '1003','str' => '福利红包口令错误');
            }
        }

        //增加领取红包记录
        $data = array(
            'uniacid' => $_W['uniacid'],
            'get_amount' => $getmoney/100,
            'create_time' => time(),
            'openid' => $openid,
            'red_id' => $id,
        );
        $result=pdo_insert(GETREDPACKAGE, $data);
        if (!empty($result)) {
            $data1 = array(
                'send_num' => $redpackageData[0]['send_num']+1
            );
            pdo_update(REDPACKAGE, $data1, array('red_id' => $id, 'uniacid' => $_W['uniacid']));

            //将可提现金额存到可提现金额表中
            $data2 = array(
                'uniacid' => $_W['uniacid'],
                'amount' => $getmoney/100,
                'create_time' => time(),
                'openid' => $openid,
                'type_id' => $id,
                'from_openid' =>$redpackageData[0]['openid'] ,
                'cash_type' =>1
            );
            $result=pdo_insert(CASH, $data2);
            pdo_update(MEMBER, array('balance'=>($userinfo['balance']+($getmoney/100))), array('openid' => $openid, 'uniacid' => $_W['uniacid']));
            return array('code' => '1000','money' =>$getmoney/100,'red_id'=>$id);
        }else{
            //领取福利红包出错
            return array('code' => '10004','money' =>$getmoney,'str' => '领取福利红包出错');
        }
    }


    public function addRed($userinfo,$city,$red_num,$lng,$lat,$xsthumb){
        global $_W,$_GPC;
        $red_num     = is_numeric($red_num)?$red_num :1;
        $total_num = intval($_GPC['total_num']); // 红包数（个）
        $total_amount = floatval($_GPC['total_amount']); // 红包总额（元）
        if($total_num==0 || $total_amount<$red_num){
            return array('error'=>'1','str'=>'红包金额需大于'+$red_num+'元，红包个数最少为1个');
        }
        if($total_amount/$total_num<0.1 ){
            return array('error'=>'1','str'=>'每个红包金额平均不能小于0.1元，发'.$total_num.'个红包，最低需'.$total_num*0.1.'元');
        }
        if ($_GPC['allocation_way'] == '1') {
            //生成平均分配方案
            $plan = $this->red_average_plan($total_amount, $total_num);
        } else {
            // 生成随机分配方案
            $plan = $this->red_plan($total_amount, $total_num, 1);
        }
        $info = commonGetData::guolv($_GPC['info']);
        $plan = implode(',', $plan);
        $data = array(
            'uniacid' => $_W['uniacid'],
            'content' => $info,
            'create_time' => time(),
            'model' => $_GPC['model'],
            'kouling' => $_GPC['kouling'],
            'xsthumb' =>$xsthumb,
            'allocation_way' => $_GPC['allocation_way'],
            'distance' => $_GPC['distance'],
            'rob_plan' => $plan,
            'total_num' => $total_num,
            'total_amount' => $total_amount,
            'total_pay' => $total_amount,
            'lng' => $lng,
            'lat' => $lat,
            'openid' =>  $userinfo['openid'],
            'mid' => $userinfo['id'],
            'city' => $city
        );

        pdo_insert(REDPACKAGE, $data);
        $id = pdo_insertid();
        $mihua_token=reqInfo::mihuatoken();
        $tid=reqInfo::gettokenOrsn('redpackage'.$id,$mihua_token);
        $params = array(
            'tid' =>$tid,     //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' =>$tid,                //收银台中显示的订单号
            'title' => '福利红包',          //收银台中显示的标题
            'fee' => $total_amount,      //收银台中显示需要支付的金额,只能大于 0
            'user' =>$_W['openid'],       //付款用户, 付款的用户名(选填项)
        );

        return $params;
    }
    //平均分配金额
    protected function red_average_plan($total, $num)
    {
        $total = $total * 100;
        $average = $total / $num;//平均金额

        for ($i = 0; $i < $num; $i++)
            $packs[$i] = intval($average);

        return ($packs);
    }

    public function  getRefundtime(){
        $cfg = $this->module['config'];
        if ($cfg['refundtime']){
            $refundtime=$cfg['refundtime'];
        }else{
            $refundtime=7;
        }
        return $refundtime;
    }
    protected function red_plan($total, $num, $min)

    {
        /*
                $total = $total * 100;
                $min = $min * 100;
                $average = $total / $num;//平均金额
                $max = $average * 5;//用户最大可获得平均金额的倍率
                $base_total = $num * $min;//保底分配金额
                $over_total = $total - $base_total;//随机金额


                if ($total * 0.01 == $num) {//红包金额等于红包数量时，直接发放等额红包
                    for ($i = 0; $i < $num; $i++)
                        $packs[$i] = $average;
                } else {

                    for ($i = 1; $i < $num; $i++)//第一轮循环，分配小额
                    {
                        $temp = intval(rand(0, $average - $min));//每人递增  随机 0---平均值-最小额
                        $over_total -= $temp;
                        $packs[$i] = $temp + $min;
                    }

                    while ($over_total > 0) {//第二轮，分配剩余金额，出现大额红包
                        for ($i = 1; $i < $num; $i++) {
                            $temp = intval(rand(0, $max - $average - $min));
                            if ($temp > $over_total) $temp = $over_total;
                            $packs[$i] += $temp;
                            $over_total -= $temp;
                            if ($over_total == 0) break;
                        }
                    }
                }
                shuffle($packs);
                $packs[$num] = $packs[0];

                return $packs;*/
        $remain=$total * 100;
        $min=1;//每个人最少能收到0.01元
        if ($remain == $num) {//红包金额等于红包数量时，直接发放等额红包
            for ($i = 0; $i < $num; $i++)
                $packs[$i] = $remain / $num;//平均金额;
        }else{
            for ($i=0; $i<$num; $i++) {
                $safe_total=($remain-($num-$i)*$min)/($num-$i);
                if($i ==($num-1)){
                    $packs[$i] = $remain;
                }else{
                    $money=mt_rand($min,$safe_total);
                    $remain=$remain-$money;
                    $packs[$i] = $money;
                }
            }
        }
        return $packs;
    }

    public static function addRedMsg($openid,$nickname,$red_id){
        global $_W;
                $redmsg = array(
                    'uniacid' => $_W['uniacid'],
                    'openid' => $openid,
                    'red_id' => $red_id,
                    'nickname'=> $nickname,
                );
                $result = util::getSingelDataInSingleTable(REDMSG,array( 'red_id' => $red_id,'openid' =>  $openid));
                if(empty($result )){
                    pdo_insert(REDMSG, $redmsg);
                }

    }
}