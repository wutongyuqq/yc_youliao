//order 117694284353898298
function fetchbyajax1(url,value1,op){
    $.ajax({
        type:'post',
        url:url,
        dataType: 'json',
        data:{'id':value1,'op':op},
        success:function(data){
            if(data.status=='1'){
                var content="";
                $.each(data.list,function(k,v){
                    content+=("<option value='"+v.id+"'>"+v.name+"</option>");
                })
                $('select[name="ccate_id"]').html(content);
            }else{
                tip(data.str);
                tip_close();
            }

        }
    });

}

function fetchbyajax2(url,value1,op){
    $.ajax({
        type:'post',
        url:url,
        dataType: 'json',
        data:{'id':value1,'op':op},
        success:function(data){
            if(data.status=='1'){
                var content="";
                $.each(data.list,function(k,v){
                    content+=("<option value='"+v.id+"'>"+v.name+"</option>");
                    changebusiness(v.id);
                })


                $('select[name="area_id"]').html(content);

            }else{
                tip(data.str);
                tip_close();
            }

        }
    });

}

function fetchbyajax3(url,value1,op){
    $.ajax({
        type:'post',
        url:url,
        dataType: 'json',
        data:{'id':value1,'op':op},
        success:function(data){
            if(data.status=='1'){
                var content="";
                $.each(data.list,function(k,v){
                    content+=("<option value='"+v.id+"'>"+v.name+"</option>");

                })


                $('select[name="business_id"]').html(content);

            }else{
                tip(data.str);
                tip_close();
            }

        }
    });

}
function checkform(){
    var x = $("#map_x").val();
    var y = $("#map_y").val();
    var n = $("#name").val();
    var t = $('input[name="telphone"]').val();
    var r = $('input[name="manage"]').val();
    var area_id= $('select[name="area_id"]').val();
    var business_id= $('select[name="business_id"]').val();
    var city_id= $('select[name="city_id"]').val();
    var ccate_id= $('select[name="ccate_id"]').val();
    var pcate_id= $('select[name="pcate_id"]').val();
    if(n==""){
        tip("请填写店铺名称");
        tip_close();
        return false;
    }
    if(t==""){
        tip("请填写联系电话");
        tip_close();
        return false;
    }
    if(r==""){
        tip("请填写联系人");
        tip_close();
        return false;
    }
    if(pcate_id==0){
        tip("请选择行业分类");
        tip_close();
        return false;
    }
    // if(ccate_id==0){
    //     tip("请选择行业子类");
    //     tip_close();
    //     return false;
    // }
    if(city_id==0){
        tip("请选择城市");
        tip_close();
        return false;
    }
    // if(area_id==0){
    //     tip("请选择区域");
    //     tip_close();
    //     return false;
    // }
    // if(business_id==0){
    //     tip("请选择商圈");
    //     tip_close();
    //     return false;
    // }
    // if(x=="" || y==""){
    //     tip("城市坐标不能为空");
    //     tip_close();
    //     return false;
    // }
    if(n==""){
        tip("名称不能为空");
        tip_close();
        return false;
    }
    if(vailPhone()!=true){
        return false;
    }
}
//验证手机号
function vailPhone(){
    var phone = jQuery("#phone").val();
    var flag = false;
    var message = "";
    var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    if(phone == ''){
        message = "请输入正确格式的手机号码！";
    }else if(phone.length !=11){
        message = "请输入有效的手机号码！";
    }else if(!myreg.test(phone)){
        message = "请输入有效的手机号码！";
    }else{
        flag = true;
    }
    if(!flag){
        //提示错误效果
        jQuery("#phoneP").html("");
        jQuery("#phoneP").html("<i class=\"icon-error ui-margin-right10\">&nbsp;<\/i>"+message);
        jQuery("#phone").focus();
    }else{
        jQuery("#phoneP").html("");
    }
    return flag;
}


function getImageSize(){
    var _w = parseInt($(window).width());//获取浏览器的宽度
    $(".my-gallery img").each(function(i){
        var img = $(this);
        var realWidth;//真实的宽度
        var realHeight;//真实的高度
//这里做下说明，$("<img/>")这里是创建一个临时的img标签，类似js创建一个new Image()对象！
        $("<img/>").attr("src", $(img).attr("src")).load(function() {
            /*
             如果要获取图片的真实的宽度和高度有三点必须注意
             1、需要创建一个image对象：如这里的$("<img/>")
             2、指定图片的src路径
             3、一定要在图片加载完成后执行如.load()函数里执行
             */
            realWidth = this.width;
            realHeight = this.height;
//如果真实的宽度大于浏览器的宽度就按照100%显示
         //如果小于浏览器的宽度按照原尺寸显示
                $(img).parent(".gallery-a").attr('data-size', realWidth+'x'+realHeight);

        });
    });
}
function getShopQr(url) {
    showBigImage(url,$('#qr_code'));
}

function checkAdmin(){
    var openid= $('#openid').val();
    if (openid=="") {
        tip( '请选择管理员!');
        tip_close();
        return false;
    }

    var admin_type= $("input[name='admin_type']:checked").val();//radio
    if(admin_type==undefined || admin_type==0 || admin_type==""){
        tip( '请选择管理员类型!');
        tip_close();
        return false;
    }
    if( admin_type !=3){
        var passport= $('#passport').val();
        if (passport=="") {
            tip( '用户名不能为空!');
            tip_close();
            return false;
        }
        var password= $('#password').val();
        if (password=="") {
            tip( '密码不能为空!');
            tip_close();
            return false;
        }

    }


}
function checkAdmin_role(){
    var openid= $('#openid').val();
    if (openid=="") {
        tip( '请选择管理员!');
        tip_close();
        return false;
    }


}
function select_saler(obj,data){
    $('body').find('#picc').attr('src',data.avatar);
    $('#nickname').val(data.nickname);
    $('#nickname').text(data.nickname);
    $('#openid').val(data.openid);
    $('#avatar').val(data.avatar);
    $('#modal-module-menus').modal('hide');
    $('#module-menus').hide();
    $('.modal-dialog').hide();
}

function gatherCheck(host) {
    var module = $("#neizhi-item").val();
    var page = $("input[name='page']").val();
    if(module==''){
        tip('请选择采集频道');
        tip_close();
        return false;
    }
    var provance=$('#sel-provance').val();
    if(provance=='' || provance=='省/直辖市'){
        tip('请选择省份');
        tip_close();
        return false;
    }

    var city=$('#sel-city').val();
    if(city=='' || city=='市'){
        tip('请选择市');
        tip_close();
        return false;
    }

    var area=$('#sel-area').val();
    if(area=='区/县'){
        $('#sel-area').val('');
    }
    collect(host, module, provance, city, area, page);
}
function dataStore() {
    //模型
    var module = $("#gatherMid").val();
    if(module === '0'){
        tip('请选择模型');
        tip_close();
        return false;
    }
    var provance=$('#sel-provance').val();
    if(provance=='' || provance=='省/直辖市'){
        tip('请选择省份');
        tip_close();
        return false;
    }

    var city=$('#sel-city').val();
    if(city=='' || city=='市'){
        tip('请选择市');
        tip_close();
        return false;
    }

    var district=$('#sel-area').val();
    if(district=='区/县'){
        $('#sel-area').val('');
    }
    // 发布人 粉丝1 或 马甲2
    var gatherOpenid = '0';
    var gatherAvatar = '0';
    var gatherNickname = '0';
    if ($('input[name="gatherPoster"]:checked').val() == '1') {
        gatherOpenid = $('input[name="gatherOpenid"]').val();
        if(gatherOpenid === '') {
            tip('请选择发布人');
            tip_close();
            return false;
        }
    } else {
        gatherAvatar = $('input[name="gatherMajiaAvatar"]').val();
        gatherAvatar = $("#gatherMajiaImg").attr("src");
        gatherNickname = $('input[name="gatherMajiaName"]').val();
        if(gatherNickname === '') {
            tip('请选择发布人.');
            tip_close();
            return false;
        }
    }
    //content
    var checkedbox = $('input[type="checkbox"]:checked');
    if(checkedbox.size() < 1) {
        tip('没有选择数据');
        tip_close();
        return false;
    }
    var data = []
    $.each(checkedbox, function(index, value){
       data.push(value.value); 
    });
    console.log("data:", data);
    var url = $("#info-form").data("url");

    tip("正在提交");
    tip_close();

    $.ajax({
        type:'post',
        url: url,
        dataType: 'json',
        data: { gatherItem: module, gatherProvince: provance, gatherCity: city, gatherDistrict: district, gatherData: data, gatherOpenid: gatherOpenid, gatherAvatar: gatherAvatar, gatherNickname: gatherNickname },
        success:function(data){
            if(data.code === 0){
                tip(data.msg);
                tip_close();
            }else{
                tip(data.msg);
                tip_close();
            }

        },
        error: function(data) {
            console.log(data);
        }
    });

}

function collect(host, module, provance, city, area, page = 1) {
    $("#collect").html('');
    tip("正在采集...");
    tip_close();
    $.ajax({
        type:'get',
        url: host,
        dataType: 'json',
        data: { gatheritem: module, gatherprovince: provance, gathercity: city, gatherdistrict: area, gatherpage: page },
        success:function(data){
            if(data.code === 0){
                let content="";
                if(data.data.length === 0) {
                    tip('没有采集到数据');
                    tip_close();
                    $("#collect").html(content);
                    return false;
                }
                $.each(data.data, function(k,v){
                    let contentObj = JSON.parse(v.content);
                    let contentStr = v.content.replace(/"/g,"'");
                    let dianhua = contentObj.dianhua;
                    let weixin = contentObj.weixin;
                    let title = contentObj.title;
                    let shouji = contentObj.shouji;
                    if(typeof(contentObj.dianhua) === "undefined") {
                        dianhua = '';
                    }
                    if(typeof(contentObj.title) === "undefined") {
                        title = '';
                    }
                    if(typeof(contentObj.shouji) === "undefined") {
                        shouji = '';
                    }
                    if (typeof(contentObj.weixin) === "undefined") {
                        weixin = '';
                    }
                    if (shouji.indexOf("http") >= 0){
                        shouji = '<img src="' + shouji + '">';
                    }
                    content += '<tr>' +
                    '<td>' +
                        '<input type="checkbox" data-id="' + v.id + '" name="gather-content[]" value="' + contentStr + '" class="checkbox">' +
                    '</td>' +
                    '<td>' + title + '</td>' +
                    '<td>' + contentObj.lianxiren + '</td>' +
                    '<td>' + shouji + '</td>' +
                    '<td>' + dianhua + '</td>' +
                    '<td>' + weixin +'</td>' +
                '</tr>';
                });
                $("#collect").html(content);
                tip_close();
            }else{
                tip(data.msg);
                tip_close();
                $("#collect").html('');
            }

        },
        error: function(data) {
            console.log(data);
        }
    });
}
