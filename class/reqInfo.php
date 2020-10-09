<?php

class ReqInfo
{
    static public function mapReq($cfg)
    {//获取地理位置信息
        $ak = $cfg['mapak'];
        if (empty($ak)) {
            return 'https://api.map.baidu.com/geocoder/v2/?ak=sUgbA9k3BkDWbGZwHWP6EAhe6DaR4z6N&output=json&pois=1';
        } else {
            return 'https://api.map.baidu.com/geocoder/v2/?output=json&pois=1&ak=' . $ak;
        }

    }

    static public function mapUrl($cfg)
    {//获取地图

        $ak = $cfg['mapak'];
        if (empty($ak) || $ak=='') {
            return "https://api.map.baidu.com/api?v=2.0&ak=sUgbA9k3BkDWbGZwHWP6EAhe6DaR4z6N&s=1";
        } else {
            return 'https://api.map.baidu.com/api?v=2.0&s=1&ak=' . $ak;
        }

    }

    static public function chagexy($cfg)
    {//获取计算后的坐标
        $ak = $cfg['mapak'];
        if (empty($ak) || $ak=='') {
            return 'https://api.map.baidu.com/geoconv/v1/?ak=sUgbA9k3BkDWbGZwHWP6EAhe6DaR4z6N&from=1&to=5&s=1';
        } else {
            return 'https://api.map.baidu.com/geoconv/v1/?from=1&s=1&to=5&ak=' . $ak;
        }


    }

    static public function weatherreq()
    {
        return 'http://wthrcdn.etouch.cn/weather_mini?city=';

    }

    static public function msgNum($cfg)
    {
        $shownum = intval($cfg['showChannelNum']);
        if (empty($shownum)) {
            $shownum =5;
        }
        return $shownum;
    }
    static public function num()
    {
        global $_GPC;
        $num= intval(empty($_GPC['num']) ? 10 : $_GPC['num']);
        return $num;
    }
    static public function page()
    {
        global $_GPC;
        $page = intval(empty($_GPC['page']) ? 0 : $_GPC['page']);
        return $page;
    }
    static public function pageIndex()
    {
        global $_GPC;
        $page = intval(empty($_GPC['page']) ? 1 : $_GPC['page']);
        $num=self::num();
        $start = ($page-1)*$num;
        return $start;

    }

    public static function mihuaUrl(){
        //return "http://api.szmihua.com/";
    }

    public static function gettokenNum($ordersn,$token){
        $url = self::mihuaUrl().'module/order?order_secret='.$ordersn;
        $ch = curl_init();
        util::setCurl($token,$url,$ch);
        $content= curl_exec($ch);
        //print_r($content);
        curl_close($ch);
        if(empty($content) && $content == ''){
            return '';
        }else{
            $json_content = json_decode($content);
            if($json_content->code == 0){
                return $json_content->data;
            }else{
                return '';
            }
        }
    }

    public static function gettokenOrsn($ordersn,$token){
        $post_data['order_sn']=$ordersn;
        $url = self::mihuaUrl().'module/order';
        $crl = curl_init();
        util::setCurl($token,$url,$crl);
        curl_setopt($crl, CURLOPT_POST,true);
        $post_data['order_sn']=$ordersn;
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        $content= curl_exec($crl);
        curl_close($crl);
        if(empty($content) && $content == ''){
            return '';
        }else{
            $json_content = json_decode($content,1) ;
            if($json_content['code'] == 0){
                return $json_content['data'];

            }else{
                return '';
            }
        }
    }

public  static function cfg(){
        global $_W;
        $cfg=pdo_fetchcolumn("SELECT settings FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => MODULE, ':uniacid' => $_W['uniacid']));
        return unserialize($cfg);
    }

    public  static  function gettoken(){
        global $_W;
        $outhData= commonGetData::getouthData();
        $appId = $outhData['mh_appid'];
        $appSecretKey  = $outhData['mh_appkey'];
//        print_r($appId);
//        print_r( $appSecretKey);
//        exit;
            $randStr = time();
            $link=$_SERVER ['HTTP_HOST'];
            $preSign = "app_id=".$appId."app_secret_key=".$appSecretKey."rand_str=".$randStr;
            $sign = md5($preSign);
            $query =  'app_id=' .$appId. '&rand_str=' . $randStr.'&sign='.$sign.'&link='.$link.'&name='.$_W['uniaccount']['name'];
            $url =  self::mihuaUrl().'auth?'.$query;
          //echo $url;
       //     $content = ihttp_request($url);//ihttp_request
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HEADER, false);//设置header
        $content= curl_exec($ch);//运行curl
        curl_close($ch);
       // print_r($content);
        if(empty($content) && $content == ''){
            return '';
        }else{
            $json_content =json_decode($content);
            //print_r($json_content );
            if($json_content->code== 0){
                setcookie('mihua_token', $json_content->data, time()+3600 * 2);
               // echo  $json_content->data;exit;
                return $json_content->data;
            }else{
                return '';
            }
        }
    }

    public static function mihuatoken(){
        global $_W;
        $sesstion_token=self::gettoken();//$_COOKIE['mihua_token'];//
        if( $sesstion_token==''){//先查缓存是否有token
            $sesstion_token= self::gettoken();//token 重新请求
            /**if( $sesstion_token=='') {
                if($_W['isajax']){
                    die("<script>window.location.href='http://www.weixin2015.cn'</script>");
                }else {
                    message('<a href="http://www.weixin2015.cn">很抱歉，出问题了，请联系微猫QQ：2058430070！</a>');
                }
            }**/
        }
        return $sesstion_token;

    }

}
?>