<?php
global $_GPC,$_W;
$title='信息采集';
$do     =  $_GPC['do'];
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$Admin = new Admin();

if ($op == 'display') {
	$mdlist = pdo_fetchall("SELECT id,name FROM ".tablename(CHANNEL)." WHERE canrelease = 1 AND weid = {$_W['uniacid']} AND fid = 0 ORDER BY displayorder ASC");
	foreach($mdlist as $k=>$v){
		$mdlist[$k]['children'] = pdo_fetchall("SELECT id,name FROM ".tablename(CHANNEL)." WHERE canrelease = 1 AND weid = {$_W['uniacid']} AND fid = {$v['id']} ORDER BY displayorder ASC");
	}
} elseif ($op == 'post') {
	// $openid = trim($_GPC['openid']);
	// if(empty($openid)){
	// 	$this->message("请先选择发布人！");
	// 	exit;
	// }
	$content = $_GPC['gatherData'];
	$data['openid'] = $_GPC['gatherOpenid'];
	$data['nickname'] = $_GPC['gatherNickname'];
	$data['avatar'] = $_GPC['gatherAvatar'];
	if ($data['openid'] != '0') {
		$member = Member::getMemberByopenid($data['openid']);
		$data['nickname'] = $member['nickname'];
		$data['avatar'] = $member['avatar'];
	}
	$data['weid'] = $_W['uniacid'];
	$data['mid'] = $_GPC['gatherItem'];
	$data['province'] = $_GPC['gatherProvince'];
	$data['city'] = $_GPC['gatherCity'];
	$data['district'] = $_GPC['gatherDistrict'];
	$data['createtime'] = time();
	$data['views'] = 0;
	$data['status'] = 1;
	$data['lng'] = '0';
	$data['lat'] = '0';
	$data['module'] = 0;
	$data['isneedpay'] = 0;
	$data['haspay'] = 0;
	$data['isding'] = 0;
	$data['dingtime'] = 0;
	$data['fmid'] = 0;
	$data['freshtime'] = time();
	foreach ($content as $key => $value) {
		$str = str_replace("&#039;", '"', $value);
		$arr = json_decode($str, true);
		$data['content'] = serialize($arr);
		pdo_insert(INFO, $data);
	}	
	echo json_encode(['code' => 0, 'msg' => '提交成功, 已存入']);
	exit;
} 

if ($op == 'list') {
	$item = $_GPC['gatheritem'];
	$province = $_GPC['gatherprovince'];
	$city = $_GPC['gathercity'];
	$area = $_GPC['gatherdistrict'];
	$page = $_GPC['gatherpage'];
	$url = 'http://admin.szmihua.com:88/module/shequ/collect';
	$url .= '?item=' . $item . '&province=' . $province . '&city=' . $city . '&area=' . $area . '&page=' . $page;
	$data = file_get_contents($url);
	echo $data;
	exit;
}



include $this->template('web/gather');
exit();
