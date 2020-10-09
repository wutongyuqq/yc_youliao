<?php

class Goods
{
//判断商品是否可购买
    static function checkIsCanBuyThisGood($gid,$shop_id,$buynumber,$verifystock = true){
        global $_W;
        $goodinfo = Goods::getSingleGood($gid,$shop_id);
        if(empty($goodinfo))return'当前商品不存在'; //商品不存在
        if($goodinfo['status'] ==1) return'商品已经下架了';  //已经下架
        if($goodinfo['total'] < $buynumber) return'商品库存不足';  //没有库存了

        // 检查用户最多购买数量（查已购买）
        if($goodinfo['astrict'] >0){
            if( $buynumber > $goodinfo['astrict']) return'商品最多下单'.$goodinfo['astrict'].'件';
            $sql = 'SELECT SUM(`og`.`total`) AS `orderTotal` FROM ' . tablename(ORDER_GOODS) . ' AS `og` JOIN ' . tablename(ORDER) .
                ' AS `o` ON `og`.`orderid` = `o`.`id` WHERE `og`.`goodsid` = :goodsid AND `o`.`openid` = :openid';
            $params = array(':goodsid' =>$gid, ':openid' => $_W['openid']);
            $orderTotal = pdo_fetchcolumn($sql, $params);

            if ( (($orderTotal + $buynumber) > $goodinfo['astrict'])) {
                return'此商品限购'.$goodinfo['astrict'].'件，您已超过购买数量!';
            }
        }
        return 1;
    }
//是否秒杀中
    static function ms_status($info){
        $goods=Array();
        $today = strtotime(date('Y-m-d', time())); // 今日
        $mody = $today + 24 * 60 * 60; // 次日
       if($info['datestart']<=$today  && $info['datestart']< $mody && $info['dateend']>=$today ){
//        if($info['datestart']>=$today  && $info['dateend']<=$today ){
            //秒杀的日期
            $time_info=util::getSingelDataInSingleTable(MSTIME,array(
                'time_id'=>$info['time_id'],
            ));
            $goods['timeend']= $time_info['timeend'];
            $goods['timestart']= $time_info['timestart'];
            $nowhour=date("H",time());
            if ($nowhour >= $goods['timestart']) {//已开始
                if ($nowhour >= $goods['timeend'] || $goods['time_num']>0) {
                    $goods['times_flag']=2;  //已结束
                } else {
                    $goods['times_flag']=1;//秒杀中
                }
            }else{
                $goods['times_flag']=0;//未开始
            }

        }else{
            $goods['times_flag']=3;//日期未到或已结束
        }

    return $goods;
    }
// 查询单条商品
    static function getSingleGood($goods_id,$shop_id){
        global $_W;
        if($shop_id==0){
         $data= array('goods_id'=>$goods_id,'uniacid'=>$_W['uniacid']);
        }else{
         $data=array('goods_id'=>$goods_id,'shop_id'=>$shop_id,'uniacid'=>$_W['uniacid']);
        }
        $goodinfo = Util::getSingelDataInSingleTable(GOODS,$data);

        if(empty($goodinfo)) return array();
        return self::initSingleGoodPro($goodinfo);
        //需删除缓存
    }

    // 查询多条商品
    static function getAllGood($where,$page,$num,$order='`orderby` DESC'){
        $goodinfo = Util::getAllDataInSingleTable(GOODS,$where,$page,$num,$order);
        foreach($goodinfo[0] as $k=>$v){
            $newgoodinfo[$k] = self::initSingleGoodPro($v);
            $newgoodinfo[$k]['thumb'] = tomedia($v['thumb']);
        }
        return array($newgoodinfo,$goodinfo[1],$goodinfo[2]);
    }

    //转化商品图片等
    static function initSingleGoodPro($goodinfo){
        $goodinfo['inco'] = iunserializer($goodinfo['inco']);
        return $goodinfo;
    }
    function changeStatus($idarray,$type,$shop_id){
        global $_W;
        if($type == 'up') $status = 0;
        if($type == 'down') $status =1;
        if(empty($idarray)) $this->message('没有选择商品');
        foreach((array)$idarray as $k=>$v){
            $id = intval($v);
            pdo_update(GOODS,array('status'=>$status),array('uniacid'=>$_W['uniacid'],'shop_id'=>$shop_id,'goods_id'=>$id));
        }

        $this->message('操作完成',referer(),'success');
    }


    //批量排序
    static function orderData($orderarray,$tablename,$shop_id){
        global $_W;
        foreach($orderarray as $k=>$v){
            $id = intval($k);
            $order = intval($v);
            $res = pdo_update($tablename, array('orderby' => $order), array('goods_id' => $id,'uniacid'=>$_W['uniacid'],'shop_id'=>$shop_id));
        }
        return $res;
    }

    //设置商品session
    static function structGoodSession($data,$type){
        if($type == 'single'){ //单独购买
            $_SESSION['order'][$data['gid']]['buytype'] = 'single';
        }elseif($type == 'group'){ //组团
            $_SESSION['order'][$data['gid']]['buytype'] = 'group';
        }elseif($type == 'joingroup'){ //组团
            $_SESSION['order'][$data['gid']]['buytype'] = 'joingroup';
            $_SESSION['order'][$data['gid']]['groupid'] = $data['groupid'];
        }

        $_SESSION['order'][$data['gid']]['gid'] = $data['gid'];
        $_SESSION['order'][$data['gid']]['id'] = $data['id'];
        $_SESSION['order'][$data['gid']]['rule'] = $data['rule'];
        $_SESSION['order'][$data['gid']]['number'] = $data['number'];
        $_SESSION['order'][$data['gid']]['shop_id'] = $data['shop_id'];
    }


    //返回所选商品规格价格 rulearray必须是经过处理的。
    static function getGoodPriceInRule($goodinfo,$buytype,$rule){
        $after_money=0;
        if($goodinfo['hasoption'] != 1) {
            if ($buytype == 'single' || $buytype == 'cart') {
                $price = $goodinfo['marketprice'];
                $after_money=$price;
                //是否限时秒杀
                if ($goodinfo['is_time'] == 1) {
                    $ms_status = self::ms_status($goodinfo);
                    if ($ms_status['times_flag'] == 1) {
                        $price = $goodinfo['time_money'];
                    }
                }
            }elseif($buytype == 'group' || $buytype == 'joingroup') {
                $price = $goodinfo['groupprice'];
                $after_money=$price;
            }

        }elseif($goodinfo['hasoption'] == 1){//多规格
            if($buytype == 'single' || $buytype == 'cart') {
                $price = $rule['marketprice'];
                $after_money=$price;
                //是否限时秒杀
               if ($goodinfo['is_time'] == 1) {
                    $ms_status = self::ms_status($goodinfo);
                    if ($ms_status['times_flag'] == 1) {
                        $price =$rule['time_money'];
                    }
                }
            }elseif($buytype == 'group' || $buytype == 'joingroup') {
                $price = $rule['groupprice'];
                $after_money=$price;
            }

        }

        return array($after_money,$price);
    }

//返回选购的规格，用于在页面展示，返回字符串。 在确认订单，购物车页面需要
    static function getGoodRuleToView($goodruletype,$buyrule){
        $title="";
        $optionid=$buyrule ['optionid'];
        if (!empty($optionid)) {//产品规格
            $option = pdo_fetch("select  virtual,title,marketprice,weight,stock,groupprice from " . tablename(OPTION) . " where id=:id and goodsid=:goodsid limit 1",
                array(":id" =>$buyrule ['optionid'],":goodsid" =>$buyrule ['id']));
            if ($option) {
                $title       = $option['title'];
            }
        }
        return $title;
    }

    static function postData($shop_id){

        global $_W, $_GPC;
        $shop_id=intval($shop_id);
        if( $shop_id==0){
            return '获取商铺数据失败';
        }
        $id = intval($_GPC['id']);
        $title=trim($_GPC['goodsname']);
        if(empty($title)){
            return '商品名称不能为空';
        }
        $thumb=$_GPC['thumb'];
        if(empty($thumb)){
            return '商品图片不能为空';
        }
        $marketprice=round($_GPC['marketprice'], 2);
        if($marketprice=='0.00'){
            return '商品价格不能为空';
        }
        //秒杀
        $is_time= intval($_GPC['is_time']);
        if($is_time==1){
            $time_id=intval($_GPC['time_id']);
            $time_num=intval($_GPC['time_num']);
            $time_money=round($_GPC['time_money'], 2);
            $datestart=strtotime($_GPC['datestart']);
            $dateend=strtotime($_GPC['dateend']);
            if ($time_id==0) return '开启秒杀后，需选择秒杀专场';
            if ($time_num==0) return '开启秒杀后，需填写秒杀的商品数量';
            if (is_numeric($time_money) === false) return '开启秒杀后，需填写秒杀的价格';
            if(empty($datestart)|| empty($dateend) )
            return '开启秒杀后，需完善秒杀起始日期';
        }
        //拼团
        $is_group= intval($_GPC['is_group']);
        if($is_group==1) {
            $groupprice = round($_GPC['groupprice'], 2);
            $groupnum = intval($_GPC['groupnum']);
            $groupendtime = $_GPC['groupendtime'];
            if ($groupnum == 0) return '开启拼团后，需完善组团人数';
            if (is_numeric($groupprice) === false) return '开启拼团后，需完善拼团价格';
            if (empty($groupendtime)) return '开启拼团后，需完善组团时间';
        }
        $data = array(
            'uniacid' => intval($_W['uniacid']),
            'shop_id' => $shop_id,
            'thumb' => $thumb,
            'orderby' => intval($_GPC['orderby']),
            'title' => $title,
            'is_time' => $is_time,//1开启秒杀
            'time_id' => intval($_GPC['time_id']),//专场id
            'datestart' => strtotime($_GPC['datestart']),//专场日期
            'dateend' => strtotime($_GPC['dateend']),//专场结束日期
            'time_num' => $time_num,//秒杀数量
            'time_money' => $_GPC['time_money'],//秒杀价格
            'description' => htmlspecialchars_decode($_GPC['description']),
            'content' => htmlspecialchars_decode($_GPC['content']),
            'createtime' => TIMESTAMP,
            'marketprice' => $marketprice,
            'productprice' => $_GPC['productprice'],
            'hasoption' => intval($_GPC['hasoption']),
            'status' => intval($_GPC['status']),//0默认上架
            'sales' => intval($_GPC['sales']),
            'astrict' => intval($_GPC['astrict']),//限购几件
            'isfirstcut' => $_GPC['isfirstcut'],//首单优惠
            'credit' => $_GPC['credit'],//可抵扣多少元
            'is_hot' => intval($_GPC['is_hot']),//热卖推荐
            'is_group' =>$is_group,//1开启团购
            'groupprice' => $groupprice,//团购价
            'groupnum' => $_GPC['groupnum'],//团购人数
            'groupendtime' => $_GPC['groupendtime'],//团购时间
            'inco' => json_encode(iunserializer($_GPC['inco'])),//商品标签
            'isshowgroup' => intval($_GPC['isshowgroup']),//1开启最近团
            'share_img' => $_GPC['share_img'],//分享图片
            'share_title' => $_GPC['share_title'],//分享标题
            'share_info' => $_GPC['share_info'],//分享描述
            'totalcnf' => $_GPC['totalcnf'],//减库存方式
            'total' => $_GPC['total'],//库存
            'iscanrefund' => $_GPC['iscanrefund'],//'0支持退款 1不支持退款'
            'goods_cate' =>$_GPC['goods_cate'],//商品分类
        );

        if (!empty($_FILES['thumb']['tmp_name'])) {
            file_delete($_GPC['thumb_old']);
            $upload = file_upload($_FILES['thumb']);
            if (is_error($upload)) {
                return "error";
            }
            $data['thumb'] = $upload['path'];
        }
        if (!empty($_FILES['xsthumb']['tmp_name'])) {
            file_delete($_GPC['xsthumb_old']);
            $upload = file_upload($_FILES['xsthumb']);
            if (is_error($upload)) {
                return "error";
            }
            $data['xsthumb'] = $upload['path'];
        }
        $cur_index = 0;
        if (!empty($_GPC['piclist'])) {
            foreach ($_GPC['piclist'] as $index => $row) {
                if (empty($row)) {
                    continue;
                }
                $hsdata[$index] = array('attachment' => $_GPC['piclist'][$index],);
            }
            $cur_index = $index + 1;
        }
        if (!empty($_GPC['attachment'])) {
            foreach ($_GPC['attachment'] as $index => $row) {
                if (empty($row)) {
                    continue;
                }
                $hsdata[$cur_index + $index] = array('attachment' => $_GPC['attachment'][$index]);
            }
        }
        $data['thumb_url'] = serialize($hsdata);
        if ($id==0) {
            pdo_insert(GOODS, $data);
            $id = pdo_insertid();
        } else {
            unset($data['createtime']);
            pdo_update(GOODS, $data, array('goods_id' => $id));
        }

        return $id;
    }
    public static function getPiclist($pic){
        $piclist1 = unserialize($pic);
        $piclist = array();
        if(is_array($piclist1)){
            foreach($piclist1 as $p){
                $piclist[]  = is_array($p)?$p['attachment']:$p;
            }
        }
        return $piclist;
    }
    public static function getcate($shop_id,$where=''){
        global $_W;
        $category = pdo_fetchall("SELECT * FROM " . tablename(GOODS_CATE) . " WHERE uniacid = '{$_W['uniacid']}' and shop_id ={$shop_id} {$where} ORDER BY id ASC, displayorder DESC");
        return $category ;
    }

    public static function postCate($shop_id){
        global $_W, $_GPC;
        if (empty($_GPC['catename'])) {
           return '抱歉，请输入分类名称！';
        }
        $id 	  = intval($_GPC['id']);
        $data = array(
            'uniacid' => $_W['uniacid'],
            'name' 	=> $_GPC['catename'],
            'displayorder' => intval($_GPC['displayorder']),
            'cate_url' => $_GPC['cate_url'],
            'thumb'    =>$_GPC['images'],
            'shop_id'    =>$shop_id
        );
        if ($id>0) {
            pdo_update(GOODS_CATE, $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
        } else {
            pdo_insert(GOODS_CATE, $data);
            $id = pdo_insertid();
        }
        return $id;
    }

    public static function getXsmsGoods($joinCity,$codition,$page='',$num=''){
        global $_W, $_GPC;
        $today = strtotime(date('Y-m-d', time())); // 今日
        $mody = $today + 24 * 60 * 60; // 次日
        $stage_list =commonGetData::getmiaosha('',$page,$num,$joinCity,$codition,$today,$mody);
        $xs= array();
//处理场次信息 显示当前秒杀中的场次 及以后的场次信息 不足五场显示第二天的场次信息
        $nowhour=date("H",time());
        if($stage_list){
            foreach ($stage_list as $k => $v) {
                $timebegin = $v['timestart']; //场次开始时间
                $timeend = $v['timeend']; //场次结束时间
                if ($nowhour >= $timebegin) {
                    if ($nowhour >= $timeend) {
                        $v['first_status'] = 2;  //已结束
                    } else {
                        $v['first_status'] = 1; //秒杀中
                        $xs[] = $v;
                    }
                } else {
                    $v['first_status'] = 0; // 待秒杀
                }

            }
        }

        $xsms= array();
        //重新获取已开始的秒杀专场商品
        foreach($xs as $k => $v){
            if($v['first_status'] == 0){
                $id = $v['time_id'];

                $xsms=commonGetData::getmiaosha($id,'','',$joinCity,$codition,$today,$mody);
                $stage = $k;
                $current_status = 0;
                break;
            }
        }
//重新获取待开始的秒杀专场商品
        foreach($xs as $k => $v){
            if($v['first_status'] == 1){
                $id = $v['time_id'];
                $xsms=commonGetData::getmiaosha($id,'','',$joinCity,$codition,$today,$mody);
                $stage = $k;
                $current_status = 1;
                $msz_timestart= $v['timestart'];
                break;
            }
        }
        return array('xsms'=>$xsms,'stage'=>$stage,'current_status'=>$current_status,'msz_timestart'=>$msz_timestart);
    }


    public static function getGroup($joinCity,$condition,$cityCondition,$pageStart,$num){
        global $_W, $_GPC;
        $isgroup     = pdo_fetchall("SELECT g.*,s.shop_name FROM " . tablename(GOODS) . " g {$joinCity} WHERE  g.uniacid = '{$_W['uniacid']}'  and s.status=1 and g.status =0   {$condition} {$cityCondition} ORDER BY  g.createtime DESC, sales DESC limit {$pageStart},{$num}  ");
        foreach ($isgroup as $key => $value) {
            if($value['thumb']){
                $isgroup[$key]['thumb']=tomedia($value['thumb']);
            }

        }
        return $isgroup;
    }
}