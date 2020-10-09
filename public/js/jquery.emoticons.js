;(function (factory) {
    if (typeof define === "function" && (define.amd || define.cmd) && !jQuery) {
        // AMD或CMD
        define([ "jquery" ],factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = function( root, jQuery ) {
            if ( jQuery === undefined ) {
                if ( typeof window !== 'undefined' ) {
                    jQuery = require('jquery');
                } else {
                    jQuery = require('jquery')(root);
                }
            }
            factory(jQuery);
            return jQuery;
        };
    } else {
        //Browser globals
        factory(jQuery);
    }
}(function ($) {
    $.emoticons = function(parameter,getApi) {
        if(typeof parameter == 'function'){ //重载
            getApi = parameter;
            parameter = {};
        }else{
            parameter = parameter || {};
            getApi = getApi||function(){};
        }
        var defaults = {
            'prefix':'mihua',
            'publisherCls':'publisher',
            'triggerCls':'trigger',
            'activeCls':'active',
            'path':'public/image/',
            'closer' : 'closer',
            'boxTag': 'input',
            'list':[
                {'title':'微笑','url':'weixiao.gif'},
                {'title':'嘻嘻','url':'xixi.gif'},
                {'title':'哈哈','url':'haha.gif'},
                {'title':'可爱','url':'keai.gif'},
                {'title':'可怜','url':'kelian.gif'},
                {'title':'挖鼻','url':'wabi.gif'},
                {'title':'吃惊','url':'chijing.gif'},
                {'title':'害羞','url':'haixiu.gif'},
                {'title':'挤眼','url':'jiyan.gif'},
                {'title':'闭嘴','url':'bizui.gif'},
                {'title':'鄙视','url':'bishi.gif'},
                {'title':'爱你','url':'aini.gif'},
                {'title':'泪','url':'lei.gif'},
                {'title':'偷笑','url':'touxiao.gif'},
                {'title':'亲亲','url':'qinqin.gif'},
                {'title':'生病','url':'shengbing.gif'},
                {'title':'太开心','url':'taikaixin.gif'},
                {'title':'白眼','url':'baiyan.gif'},
                {'title':'右哼哼','url':'youhengheng.gif'},
                {'title':'左哼哼','url':'zuohengheng.gif'},
                {'title':'嘘','url':'xu.gif'},
                {'title':'衰','url':'shuai.gif'},
                {'title':'吐','url':'tu.gif'},
                {'title':'哈欠','url':'haqian.gif'},
                {'title':'抱抱','url':'baobao.gif'},
                {'title':'怒','url':'nu.gif'},
                {'title':'疑问','url':'yiwen.gif'},
                {'title':'馋嘴','url':'chanzui.gif'},
                {'title':'拜拜','url':'baibai.gif'},
                {'title':'思考','url':'sikao.gif'},
                {'title':'汗','url':'han.gif'},
                {'title':'困','url':'kun.gif'},
                {'title':'睡','url':'shui.gif'},
                {'title':'钱','url':'qian.gif'},
                {'title':'失望','url':'shiwang.gif'},
                {'title':'酷','url':'ku.gif'},
                {'title':'色','url':'se.gif'},
                {'title':'哼','url':'heng.gif'},
                {'title':'鼓掌','url':'guzhang.gif'},
                {'title':'晕','url':'yun.gif'},
                {'title':'悲伤','url':'beishang.gif'},
                {'title':'抓狂','url':'zhuakuang.gif'},
                {'title':'黑线','url':'heixian.gif'},
                {'title':'阴险','url':'yinxian.gif'},
                {'title':'怒骂','url':'numa.gif'},
                {'title':'互粉','url':'hufen.gif'},
                {'title':'书呆子','url':'shudaizi.gif'},
                {'title':'愤怒','url':'fennu.gif'},
                {'title':'感冒','url':'ganmao.gif'},
                {'title':'心','url':'xin.gif'},
                {'title':'伤心','url':'shangxin.gif'},
                {'title':'猪','url':'zhu.gif'},
                {'title':'熊猫','url':'xiongmao.gif'},
                {'title':'兔子','url':'tuzi.gif'},
                {'title':'OK','url':'ok.gif'},
                {'title':'耶','url':'ye.gif'},
                {'title':'GOOD','url':'good.gif'},
                {'title':'NO','url':'no.gif'},
                {'title':'赞','url':'zan.gif'},
                {'title':'来','url':'lai.gif'}
            ],
            'top':0,
            'left':0,
            'onShow':function(){},
            'onHide':function(){},
            'onSelect':function(){}
        };
        var options = $.extend({}, defaults, parameter);

        var _api = {};
        var $document = $(document);
        var $wap = $('.' + options.publisherCls);
        var $layer = $('<div class="'+options.prefix+'-layer">').appendTo($wap);
        var $panel = $('<div class="'+options.prefix+'-panel"></div>').appendTo($layer);
        var $list = $('<ul></ul>').appendTo($panel);
        var $trigger = null;
        var $textarea = null;
        var _hash = {};
        //结构处理
        $.each(options.list,function(index,item){
            _hash[item.title] = options.path+item.url;
            $list.append('<li title="'+item.title+'"><img data-src="'+_hash[item.title]+'"/></li>');
        });
        // 计算高度
        var deviceWidth = document.documentElement.clientWidth;
        var height = Math.ceil(options.list.length / deviceWidth * 32)
        $.emoticons.height = 32 * height
        //接口处理
        _api.getTextarea = function(){
            return $textarea;
        },
        _api.format = function(str){
            var list = str.match(/\[[\u4e00-\u9fa5]*\w*\]/g);
            var filter = /[\[\]]/g;
            var title;
            if(list){
                for(var i=0;i<list.length;i++){
                    title = list[i].replace(filter,'');
                    if(_hash[title]){
                        str = str.replace(list[i],' <img src="'+_hash[title]+'"/> ');
                    }
                }                
            }
            return str;
        };
        //关闭弹框
        var closeLayer = function(cb){
            if($trigger){
                $trigger.removeClass(options.activeCls);
            }
            $layer.css('height', 0 + 'px')
            $trigger = null;
            $textarea = null;
            setTimeout(cb,300)
        };
        //事件绑定
        $("." + options.triggerCls).on('click',function(){
            $trigger = $(this);
            if($trigger.hasClass('active')) {
                closeLayer()
            } else {
                var $publisher = $trigger.parents('.'+options.publisherCls);
                $textarea = $publisher.find(options.boxTag);
                $trigger.addClass(options.activeCls);
                $layer.find('img').each(function(){
                    var $this = $(this);
                    $this.attr('src',$this.data('src'));
                });
                $layer.css('height', 32 * height + 'px')
            }
        });
        $document.on('click',function(e){
            var $target = $(e.target);
            if(!$target.is('.'+options.triggerCls)&&!$target.closest('.'+options.prefix+'-layer').length){
                closeLayer();
            }
        });
        $wap.find(options.boxTag).on('click', function (e) {
            var that = $(this)
            if($trigger && $trigger.hasClass('active')) {
                closeLayer()
            }
        })
        $layer.on('click','li',function(){
            var $this = $(this);
            var title = $this.attr('title');
            if($textarea){
                console.log($textarea)
                $textarea.val($textarea.val() + '['+title+']')
                // insertText($textarea[0],'['+title+']');
            }
            options.onSelect(_api);
        });
        //为了兼容insertText
        // $document.on('select click keyup','.'+ options.publisherCls +' ' + options.boxTag,function(){ 
        //     if (this.createTextRange){
        //         this.caretPos = document.selection.createRange().duplicate();
        //     }
        // });
        //初始化
        getApi(_api);
        return this;
    };

    //插入文字
    function insertText(obj,str) {
        if(document.all && obj.createTextRange && obj.caretPos){
            var caretPos=obj.caretPos; 
            caretPos.text = caretPos.text.charAt(caretPos.text.length-1) == '' ? 
            str+'' : str; 
        }else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
            var startPos = obj.selectionStart,
                endPos = obj.selectionEnd,
                cursorPos = startPos,
                tmpStr = obj.value;
            obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
            cursorPos += str.length;
            obj.selectionStart = obj.selectionEnd = cursorPos;
        } else {
            obj.value += str;
        }
    }
}));
