<?php
class Shop{

    public static  function isshop_admin($openid)
    {
        $admin_where['openid'] = $openid;
        $admin_where['status'] = 0;
        $isshop_admin = util::getSingelDataInSingleTable(SHOP_ADMIN, $admin_where);
        return $isshop_admin;
    }

    public static function getShopRenewPrice($type,$cfg){
        if($type==1){//'1:1年，2:2年，3:3年'
            $price=$cfg['one_year_money'];
        }elseif($type==2){
            $price=$cfg['two_year_money'];
        }elseif($type==3){
            $price=$cfg['three_year_money'];
        }
        if(empty($price)){//未填写入驻套餐，免费入驻
            $status=1;
        }else{
            $status=0;
        }
        return array($price,$status);
    }
    public static function shopFreeRenew ($shop_id,$nickname,$type=1,$cfg=''){
        global $_W;
        $renew=0;
        if( $type==4 && $cfg['shop_enter_price']==2){//永久免费
            $renew=util::getYearStamp();
            $renew= $renew*99;
        }
        if( $type==0 && $cfg['shop_enter_price']==0){//免费一年
            $renew=util::getYearStamp();
        }
        pdo_update(SHOP,array('endtime'=> TIMESTAMP+$renew),array('shop_id'=>$shop_id,'uniacid'=>$_W['uniacid']));
        $url= Util::createModuleUrl('shop_admin',array('shop_id'=>$shop_id,'op'=>'info','check'=>1));
        $name='店铺入驻';
        Message::admin_checkmsg('有新的商家入驻!',$url,$name,$nickname,TIMESTAMP);
        return 1;
    }
public static function getShopInfo($shop_id){
    global  $_GPC,$_W;
    $where               = " shop_id=:shop_id and uniacid=:uniacid";
    $params[":shop_id"] = $shop_id;
    $params[":uniacid"] = $_W['uniacid'];
    $re = pdo_fetch("select * from  ".tablename(SHOP)." where ".$where,$params);

    return $re;
}
public function proShopImg($data){
    $cfg = $this->module['config'];
    $shopBgpic=$cfg['shop_bgpic'];
    if($data['bgpic']){
        $data['bgpic']=tomedia($data['bgpic']);
    }else if($shopBgpic){
        $data['bgpic']=tomedia($shopBgpic);
    }else{
        $data['bgpic']=MODULE_URL.'/public/images/user_top_bg-0.jpg';
    }

    $shopLogo=tomedia($cfg['shop_logo']);
    if($data['logo']){
        $data['logo']=tomedia($data['logo']);
    }else if( $shopLogo){
        $data['']=tomedia($shopLogo);
    }else{
        $data['logo']=MODULE_URL.'/public/images/fabu_2.png';
    }
    return $data;
}
public static function getShop_name($shop_id){
    global  $_GPC,$_W;
    $where               = " shop_id=:shop_id and uniacid=:uniacid";
    $params[":shop_id"] = $shop_id;
    $params[":uniacid"] = $_W['uniacid'];
    $re = pdo_fetch("select * from  ".tablename(SHOP)." where ".$where,$params);
    return $re['shop_name'];
}
public static function getShopInfoAll($shop_id){
    global  $_GPC,$_W;
    $where               = " s.shop_id=:shop_id and s.uniacid=:uniacid";
    $params[":shop_id"] = $shop_id;
    $params[":uniacid"] = $_W['uniacid'];
    $re = pdo_fetch("select s.*,c.cate_name, a.area_name from  ".tablename(SHOP)." s left join ".tablename(CATE)." c on s.ccate_id=c.cate_id or s.pcate_id=c.cate_id and c.uniacid=s.uniacid  left join ".tablename(AREA)." a on a.area_id=s.business_id and a.uniacid=s.uniacid and s.status=1 where ".$where,$params);
    return $re;
}

public static function getShopType($cate_id){
    global  $_GPC,$_W;
    $where               = "  uniacid=:uniacid and cate_id=:cate_id";
    $params[":cate_id"] = $cate_id;
    $params[":uniacid"] = $_W['uniacid'];
    $re = pdo_fetchcolumn("select cate_type from  ".tablename(CATE)." where ".$where,$params);
    return $re;
}
public static function getApplynum($shop_id,$f_type){
    global  $_GPC,$_W;
    $where               = " shop_id=:shop_id and uniacid=:uniacid and f_type=:f_type";
    $params[":shop_id"] = $shop_id;
    $params[":uniacid"] = $_W['uniacid'];
    $params[":f_type"] = $f_type;
    $re = pdo_fetchcolumn("SELECT count(*) from  ".tablename(SHOP_APPLY)." where ".$where,$params);
    return $re;
    }


    public static function getSingleAccount($shop_id,$condition,$sqlwhere='',$pageStart,$num){
        global   $_W;
        $where='';
        if(intval($shop_id)>0){
        $where.= " and shop_id=".$shop_id;
        }
        $sql=tablename(ACCOUNT)." where uniacid = '{$_W['uniacid']}'  ".$where;
        if($condition==1){
            $re = pdo_fetchall("select * from  ".$sql. "  ORDER BY cash_id desc LIMIT  {$pageStart},{$num} ");
            $total = pdo_fetchcolumn('SELECT count(*) FROM '  .$sql);
        }else{
            $re = pdo_fetchall("select * from  ".$sql.$sqlwhere. "  ORDER BY cash_id desc LIMIT  {$pageStart},{$num} ");
            $total = pdo_fetchcolumn('SELECT count(*) FROM '  .$sql.$sqlwhere);
        }
        return array($re,$total);
        }

    public static function postAccountApplay($shop,$transfer_min,$transfer_max ,$admin_id,$transfer){
    global  $_GPC,$_W;
    $balance=$shop['balance'];
    $shop_id=$shop['shop_id'];
         if( $balance<=1){
             return '可提现金额小于1元';
            }
        $money =round($_GPC['money'],2);

        if ( $money > $balance)
        {
            return '余额不足，无法提现';
        }
        $transfer=floatval($money*$transfer*0.01);
        $id = self::insertAccount($money,$transfer_min,$transfer_max,$transfer,2,$shop,$admin_id);
        //减去申请提现的金额
         if(intval($id)>0){
            $am=$money+$transfer;
            $acc=$balance-$am;
            pdo_update(SHOP, array('balance' =>$acc), array('shop_id' => $shop_id,'uniacid'=>$_W['uniacid']));
            return $id;
         }else{
                return $id;
            }


    }
    public static function insertAccount($money,$transfer_min,$transfer_max,$transfer,$type,$shop='',$admin_id=0){
        global  $_GPC,$_W;
         if($type==2){
             $shop_id=$shop['shop_id'];
             $where['status'] = 0;
             $where['admin_id'] = $admin_id;
             $a_data = util::getSingelDataInSingleTable(SHOP_ADMIN, $where, 'admin_type,openid');
             if($a_data['admin_type']!=1 ){
                 return '您没有提现权限';
             }
             $openid=$a_data['openid'];
             $title='店铺['.$shop['shop_name'].']申请提现';
         }else{
             $openid=$_W['openid'];
             $title='会员提现申请';
         }
        if ($money < $transfer_min || $money > $transfer_max )
        {
            return '申请需金额需小于等于'.$transfer_max.'元，大于等于'.$transfer_min.'元';
        }
        $paytype=intval($_GPC['paytype']);
        $alipay_account=$_GPC['alipay_account'];
        $alipay_name=$_GPC['alipay_name'];
        $bank_num=$_GPC['bank_num'];
        $bank_realname=$_GPC['bank_realname'];
        $bank_branch=$_GPC['bank_branch'];
        $arr = array();
        if($paytype==0) {
            return '请选择提现方式';
        }if($paytype==2) {//支付宝
            if($alipay_account==''){
                return '请检查支付宝账号';
            }
            if( $alipay_name==''){
                return '请检查支付宝姓名';
            }
        }elseif($paytype==2) {//银行卡
            if( $bank_num==''){
                return '请检查银行卡账号';
            }
            if($bank_realname==''){
                return '请检查银行卡姓名';
            }
            if($bank_branch==''){
                return '请检查开户银行';
            }
        }

        $ordersn=util::getordersn(ACCOUNT);

        $arr['uniacid'] = $_W['uniacid'];
        $arr['ordersn'] = $ordersn;
        $arr['admin_id'] = $admin_id;
        $arr['shop_id'] = $shop_id;
        $arr['amount'] = $money;
        $arr['transfer'] = $transfer;
        $arr['addtime'] =TIMESTAMP;
        $arr['alipay_account'] = $alipay_account;
        $arr['alipay_name'] = $alipay_name;
        $arr['bank_num'] = $bank_num;
        $arr['bank_realname'] =$bank_realname;
        $arr['bank_branch'] = $bank_branch;
        $arr['paytype'] = $paytype;
        $arr['openid'] = $openid;
        $arr['type'] = $type;
        //提现记录表插入提现记录
        pdo_insert(ACCOUNT, $arr);
        $id = pdo_insertid();

        //通知管理员
        if(intval($id)==0){return '数据有误';}
        $member_where['openid']=$_W['openid'];
        $admin_user=util::getSingelDataInSingleTable(MEMBER,$member_where,'nickname');
        $paytype=intval($_GPC['paytype']);
        if($transfer>0){
            $transfer='(含手续费：'.$transfer.'元)';
        }else{
            $transfer='(免手续费)';
        }
        $am=$money+$transfer;
        Message::admin_acount($title,'￥'.$am.$transfer,$admin_user['nickname'],TIMESTAMP,$id,$paytype);
        return $id ;
    }

    public static function getCate(){
    global $_W;
     $cate = pdo_fetchall("SELECT * FROM " . tablename(CATE) . " WHERE uniacid = '{$_W['uniacid']}' and (parent_id =0 or parent_id is null) ORDER BY orderby DESC");
        foreach ($cate as $k => $v) {
            if ($v['thumb']) {
                $cate[$k]['thumb'] = tomedia($v['thumb']);
            }else{
                $cate[$k]['thumb'] = MODULE_URL.'/public/images/fabu_2.png';
            }
        }
     return $cate;
    }
    public static function getCateName($id){
        global  $_W;
        $cate = pdo_fetchcolumn("SELECT cate_name FROM " . tablename(CATE) . " WHERE uniacid = '{$_W['uniacid']}' and
         cate_id={$id}  ");

        return $cate;
    }

     public static function getCcate(){
      global $_W;
     $ccate = pdo_fetchall("SELECT * FROM " . tablename(CATE) . " WHERE uniacid = '{$_W['uniacid']}' and parent_id >0  ORDER BY orderby DESC");
         foreach ( $ccate as $k => $v) {
             if ($v['thumb']) {
                 $ccate[$k]['thumb'] = tomedia($v['thumb']);
             }else{
                 $ccate[$k]['thumb'] = MODULE_URL.'/public/images/cate_icon.png';
             }
         }
     return $ccate;
    }
    public static function getCcateBypid($pid){
        global  $_W;
        $ccate = pdo_fetchall("SELECT * FROM " . tablename(CATE) . " WHERE uniacid = '{$_W['uniacid']}' and parent_id ={$pid} ORDER BY orderby DESC");
        foreach ( $ccate as $k => $v) {
            if ($v['thumb']) {
                $ccate[$k]['thumb'] = tomedia($v['thumb']);
            }else{
                $ccate[$k]['thumb'] = MODULE_URL.'/public/images/cate_icon.png';
            }
        }
        return $ccate;
    }
    public static function getCity(){
      global  $_GPC,$_W;
    $city = pdo_fetchall(" SELECT * FROM " . tablename(CITY) . " WHERE  uniacid = '{$_W['uniacid']}' ORDER BY  orderby asc");
     return $city;
    }

   public static function getArea(){
      global  $_GPC,$_W;
     $area = pdo_fetchall(" SELECT * FROM " . tablename(AREA) . " WHERE  uniacid = '{$_W['uniacid']}'  and (parent_id =0 or parent_id is null) ORDER BY  orderby asc");
     return $area;
    }
    public static function getBusiness(){
      global  $_GPC,$_W;
    $business =pdo_fetchall(" SELECT * FROM " . tablename(AREA) . " WHERE  uniacid = '{$_W['uniacid']}'  and parent_id >0 ORDER BY  orderby asc");
     return $business;
    }
    public static function getAreaByCityid($city_id){
        $list=util::getAllDataBySingleTable(AREA,array('city_id'=>$city_id),' orderby desc');
        return $list ;
    }
    public static function getBusinessByAreaid($parent_id){
    $list=util::getAllDataBySingleTable(AREA,array('parent_id'=>$parent_id),' orderby desc');
    return $list ;
}
    public static function editShopInfo($userid,$shop_id,$status){
     global  $_GPC,$_W;
        if (empty($_GPC['shop_name'])) return '店铺名称不能为空！';
        if (empty($_GPC['telphone']))return '联系电话不能为空！';
                $shop_in_data = array('uniacid' => $_W['uniacid'],
                'shop_name' => $_GPC['shop_name'],
                'city_id' => intval($_GPC['city_id']),
                'area_id' => intval($_GPC['area_id']),
                'business_id' => intval($_GPC['business_id']),
                'pcate_id' => intval($_GPC['pcate_id']),
                'ccate_id' => intval($_GPC['ccate_id']),
                'telphone' => $_GPC['telphone'],
                'manage' => $_GPC['manage'],
                'mid' =>$userid,
                 'lng' => $_GPC['lng'],
                 'lat' => $_GPC['lat'],
                  'intro' => trim($_GPC['intro']),
                 'inco'=>json_encode( iunserializer($_GPC['inco'])),//商品标签
                 'renjun' => $_GPC['renjun'],
                'opendtime' => $_GPC['opendtime'],
                'address' => $_GPC['address'],
                'address_detail' => $_GPC['address_detail'],
                'closetime' => $_GPC['closetime'],
            );
            if($status==0){//未成功入驻
            $shop_in_data['starttime'] = TIMESTAMP;
            $shop_in_data['status'] = $status;
            }
            $img_dir = util::getimgdir();//存入图片路径
            $type = strtolower($_FILES["logo"]["type"]);
            if (($type == "image/gif" || $type == "image/jpg" || $type == "image/jpeg" || $type == "image/png")
                //&& ($_FILES["logo"]["size"]) < 20000
            ) {//logo
                $logo = $_FILES["logo"]["tmp_name"];
                $logoname = util::createimgname();//建立图片名
                $logo_url = $img_dir . $logoname;//图片路径+图片名
                move_uploaded_file($logo, IA_ROOT . '/attachment/' . $logo_url);
                if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
                    $logo_url = util::checkdir($logo_url);
                }
                $shop_in_data['logo'] = $logo_url;
            }

            $cert_type = strtolower($_FILES["shop_cert"]["type"]);
            if (($cert_type == "image/gif" || $cert_type == "image/jpg" || $cert_type == "image/jpeg" || $cert_type == "image/png") ) {//营业执照&& ($_FILES["shop_cert"]["size"] < 20000)
                $cert = $_FILES["shop_cert"]["tmp_name"];
                $certname = util::createimgname();
                $cert_url = $img_dir . $certname;
                move_uploaded_file($cert, IA_ROOT . '/attachment/' . $cert_url);
                if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
                    $cert_url = util::checkdir($cert_url);
                }
                $shop_in_data['shop_cert'] = $cert_url;
            }

        $bgpic_type = strtolower($_FILES["bgpic"]["type"]);
        if (($bgpic_type == "image/gif" || $bgpic_type == "image/jpg" || $bgpic_type == "image/jpeg" || $bgpic_type == "image/png") ) {//营业执照&& ($_FILES["shop_cert"]["size"] < 20000)
            $cert = $_FILES["bgpic"]["tmp_name"];
            $certname = util::createimgname();
            $bgpic_url = $img_dir . $certname;
            move_uploaded_file($cert, IA_ROOT . '/attachment/' . $bgpic_url);
            if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
                $bgpic_url = util::checkdir($bgpic_url);
            }
            $shop_in_data['bgpic'] = $bgpic_url;
        }

            if (!empty($shop_id)) {
                pdo_update(SHOP, $shop_in_data, array('shop_id' => $shop_id, 'uniacid' => $_W['uniacid']));

            } else {
                pdo_insert(SHOP, $shop_in_data);
                $shop_id = pdo_insertid();

            }
            $apply_data = array('uniacid' => $_W['uniacid'],
                'shop_id' => $shop_id,
                'f_type' => 1,
                'mid' =>$userid,
                'applytime' => TIMESTAMP
            );
            pdo_insert(SHOP_APPLY, $apply_data);
            return $shop_id;
    }


    public static function insertRenew($type,$status, $price,$cfg,$flag,$shop_id,$mid){
        global  $_GPC,$_W;
        $ordersn=util::getordersn(RENEW);
        $in=Array();
        $in['uniacid']=$_W['uniacid'];
        $in['ordersn']=$ordersn;
        $in['mid']=$mid;
        $in['price']=$price;
        $in['createtime']=TIMESTAMP;
        $in['type']=$type;
        $in['flag']=$flag;
        $in['shop_id']=$shop_id;
        $in['status']=$status;
        pdo_insert(RENEW,$in);
        return $ordersn;
    }
    public static function getShopRenew($shop_id=0,$sqlwhere='',$id=0){
        global  $_GPC,$_W;
        if($shop_id>0){
            $where= ' and s.shop_id='.$shop_id;
        }
        if($id>0){
            $where.= ' and r.id='.$id;
        }
        $re = pdo_fetchall("select r.*,s.logo,s.shop_name,m.avatar,m.nickname from  ".tablename(RENEW)." r  left join ".tablename(SHOP)." s on s.shop_id=r.shop_id   left join".tablename(MEMBER)." m on m.id=r.mid  where r.uniacid = '{$_W['uniacid']}'  ".$where.$sqlwhere.' order by r.id desc ');
        return $re;
    }

    public function getShopAdmin($shop_id)
    {
        $where['shop_id'] =$shop_id;
        $a_data = util::getAllDataBySingleTable(SHOP_ADMIN, $where,'admin_id desc');
        return $a_data;
    }

    public function checkAdmin($openid='')
    {
        if($openid){
            $where['openid'] = $openid;
        }else{
            $where['openid'] = $this->getOpenid();
        }

        $where['status'] = 0;
        $a_data = util::getSingelDataInSingleTable(ADMIN, $where, 'admin_id');
        return $a_data;
    }

    public  function checkouth($openid){
        global $_W;
        if($openid){
            $a_data=$this->checkAdmin($openid);
            if(empty($a_data) ){
                return 1;
            }
        }elseif( $_W['isfounder']!=1){
            return 1;
        }
    }

    public  function getShopList($page,$num, $lat, $lng,$condition='',$orderby='orderby desc ',$joinArea='',$condition_type=0){
        global $_W;
        if($condition_type==0){
            $condition .= ' and s.lat >0  and s.lng >0 ';
        }
        $dis = pdo_fetchall("SELECT s.* FROM " . tablename(SHOP) . " s  WHERE  s.uniacid = '{$_W['uniacid']}'  {$condition} and s.status =1      ORDER BY {$orderby} limit {$page},{$num}  ");
        $isgroup = Array();
        $cfg = $this->module['config'];
        $logo=$cfg['shop_logo'];
        foreach ($dis as $k => $arr) {
            if($lat  && $lng){
                $arr['distance'] = util::getDistance($arr['lat'], $arr['lng'], $lat, $lng);
            }
            $arr['inco'] = json_decode($arr['inco']);
            if( $arr['logo'] ){
                $arr['logo'] = tomedia( $arr['logo']);
            }elseif($logo){
                $arr['logo'] = tomedia($logo);
            }else{
                $arr['logo'] = MODULE_URL.'/public/images/fabu_2.png';
            }

            $isgroup[] = $arr;
        }
        if($lat  && $lng) {
            $arrSort = array();
            $sort = array(
                'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field' => 'distance',       //排序字段
            );
            foreach ($isgroup AS $uniqid => $row) {
                foreach ($row AS $key => $value) {
                    $arrSort[$key][$uniqid] = $value;
                }
            }

            if ($sort['direction'] && count($isgroup) > 1) {
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $isgroup);
            }
        }
        return $isgroup;
    }

    public static function joinCity()
    {
        $codition = " left join " . tablename(SHOP) . " s on s.shop_id=g.shop_id left join " . tablename(AREA) . " a on a.area_id=s.area_id and s.status=1";
        return $codition;
    }

    public static function getShopEndtime($type=1)
    {
        if($type==1){
            $codition = " and s.endtime >=" . TIMESTAMP;
        }else{
            $codition = " and (s.endtime <" . TIMESTAMP. " or s.endtime is null) ";
        }
        return $codition;
    }
}

