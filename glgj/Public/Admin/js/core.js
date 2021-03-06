$(function() {   
    KindEditor.ready(function(K) {
        /* 分类logo图片上传 */
        var editor1 = K.editor({
            allowFileManager : true
        });
        $('#logobtn').click(function() {
            editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#logoId").attr('src',url).parents('tr').show();
                        $("#logourl").val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
    });

    /* 焦点图切换请求对应模块下的所有数据 */
    $('#href_model').change(function() {
        var _this = $(this);
        $.post('?g=admin&m=admin&a=getModelAllDatas', {model_name: _this.find('option:selected').val()}, function( data ) {
            var _html = '';
            for (var i = 0; i < data.length; i++) {
                _html += '<option value="'+data[i]['id']+'">'+data[i]['title']+'</option>';
            }
            $('#data_id').html(_html);
        });
    });

    /* 内外链切换 */
    $('#outside').click(function() {
        $('#href_model,#data_id').parents('tr').hide();
        $('#url').show();
    });

    $('#inside').click(function() {
        $('#href_model,#data_id').parents('tr').show();
        $('#url').hide();
    });
});