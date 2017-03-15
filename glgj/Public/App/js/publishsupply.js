$(function() {
    /* 定义变量 */
    var btnUpload  = $('#covers_btn');
    var uid        = $('#uid').val();
    var uuid       = $('#uuid').val();
    var staticUrl  = $('#static').val();
    var uploadFlag = true;
    var ajaxFlag   = true;

    /* 判断登陆 */
    if ( !uid ) {
        layer.open({
            content : '请先登陆',
            time    : 1
        });
        return false;
    }

    /* app端访问 url需多带2个参数 */
    if ( uuid ) {
        uuid = '&appsign=1&uuid=' + uuid;
    }

    /* 图片上传 */
    new AjaxUpload(btnUpload, {
        action       : '?g=app&m=member&a=publishUpload' + uuid,
        name         : 'uploadfile',
        responseType : 'json',
        onSubmit : function( file, ext ) {
            if ( uploadFlag === true ) {
                uploadFlag = false;
            } else {
                return false;
            }
            if ( !( ext && /^(jpg|png|jpeg|gif)$/.test(ext) ) ){
                layer.open({
                    content : '请上传jpg，png或者gif格式的图片',
                    time    : 2
                });
                uploadFlag = true;
                return false;
            }
            layer.open({
                type    : 1,
                content : '<img src="'+staticUrl+'/loading.gif" width="20" height="20" style="display:inline-block;margin-right:5px;" />图片上传中...',
                style   : 'padding:10px;'
            });
        },
        onComplete : function( file, response ){
            if( response['info'] === 'success' ){
                $('#covers_preview').attr( 'src', response['path'] );
                $('#covers_path').val(response['path']);
                layer.closeAll();
            } else {
                layer.open({
                    content : '图片上传失败',
                    time    : 2,
                    success : function() {
                        layer.closeAll();
                    }
                });
            }
            uploadFlag = true;
        }
    });

    /* 表单验证 */
    $('.btn_publish').click(function() {
        /* 防止表单多次提交 */
        if ( ajaxFlag === true ) {
            ajaxFlag = false;
        } else {
            return false;
        }

        /* 验证标题长度 */
        var titleReg  = /^[\w\W]{2,50}$/;
        var titleLong = $('#name_long').val();
        if ( titleReg.test(titleLong) === false ) {
            layer.open({
                content: '请输入标题，2到50位字符',
                style: 'background-color:#fff; color:red; border:none;',
                time: 2
            });
            ajaxFlag = true;
            return false;
        }
        /* 验证求购价 */
        var moneyReg = /^[1-9]+(\.\d{1,2})?$/;
        var moneyVal = $('#money').val();
        if ( moneyReg.test(moneyVal) === false ) {
            layer.open({
                content: '请输入供应价，最多保留两位小数',
                style: 'background-color:#fff; color:red; border:none;',
                time: 2
            });
            ajaxFlag = true;
            return false;
        }
        /* 验证数量 */
        var numReg = /^\d+$/;
        var numVal = $('#number').val();
        if ( numReg.test(numVal) === false ) {
            layer.open({
                content: '请输入有效数量',
                style: 'background-color:#fff; color:red; border:none;',
                time: 2
            });
            ajaxFlag = true;
            return false;
        }
        /* 验证天数 */
        var dayReg = /^\d+$/;
        var dayVal = $('#day').val();
        if ( ( dayReg.test(dayVal) === false ) || ( parseInt( dayVal ) > 90 )  ) {
            layer.open({
                content: '请输入天数，大于等于0且小于等于90',
                style: 'background-color:#fff; color:red; border:none;',
                time: 3
            });
            ajaxFlag = true;
            return false;
        }
        /* 验证内容必填 */
        var requireReg = /[\w\W]+/;
        var requireVal = $('#desc').val();
        if ( requireReg.test(requireVal) === false ) {
            layer.open({
                content: '请输入内容',
                style: 'background-color:#fff; color:red; border:none;',
                time: 2
            });
            ajaxFlag = true;
            return false;
        }

        /* post */
        $.post('?g=app&m=member&a=publishsupply' + uuid, $('form').serialize(), function(data) {
            layer.open({
                content  : data.info,
                time     : 1,
                end      : function() {
                    if ( data.status == 1 && data.url ) {
                        window.location.href = data.url;
                    }
                    ajaxFlag = true;
                }
            });
        });
        return false;
    });    
});