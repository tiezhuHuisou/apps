$(function () {
    KindEditor.ready(function (K) {
        /* 企业详情富文本编辑器初始化 */
        K.create('#company_content', {
            width: '670px',
            height: '300px',
            items: ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
            afterBlur: function () {
                this.sync();
            },
            resizeType: 0
        });

        /* 企业logo图片单图上传 */
        var editor1 = K.editor({
            allowFileManager: true
        });
        $('#logo_upload').click(function () {
            editor1.loadPlugin('image', function () {
                editor1.plugin.imageDialog({
                    showRemote: false,
                    clickFn: function (url, title, width, height, border, align) {
                        $('#logo_preview').attr('src', url);
                        $('#logo_url').val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });

        /*北京图片logo上传*/
        var editor1 = K.editor({
            allowFileManager: true
        });
        $('#bglogo_upload').click(function () {
            editor1.loadPlugin('image', function () {
                editor1.plugin.imageDialog({
                    showRemote: false,
                    clickFn: function (url, title, width, height, border, align) {
                        $('#bglogo_preview').attr('src', url);
                        $('#bglogo_url').val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });

    });
    var province = '';
    var city = '';
    var area = '';
    /* 省市区三级联动 */
    $('.province').change(function () {
        var id = $(this).val();
        province = $('.province option:selected').text();
        $('#address').val(province + city + area);
        if (id) {
            var city_arr = data[id];
            console.log(data[id]);
            var str = '<option value="">请选择所在市</option>';
            for (var i = 0; i < city_arr.length; i++) {
                str += '<option value="' + city_arr[i]['id'] + '">' + city_arr[i]['name'] + '</option>';
            }
            $('.city').html(str);
        } else {
            $('.city').html('<option value="">请选择所在市</option>');
            $('.towns').html('<option value="">请选择所在区/县</option>');
        }

    });
    $('.city').change(function () {
        var id = $(this).val();
        city = $('.city option:selected').text();
        area = $('.towns option:selected').text();
        if (city == '请选择所在市') {
            city = '';
        }
        if (area == '请选择所在区/县') {
            area = '';
        }
        $('#address').val(province + city + area);
        if (id) {
            var town_arr = data[id];
            console.log(town_arr)
            var str_town = '<option value="">请选择所在区/县</option>';
            for (var i = 0; i < town_arr.length; i++) {
                str_town += '<option value="' + town_arr[i]['id'] + '">' + town_arr[i]['name'] + '</option>';
            }
            $('.towns').html(str_town);
        } else {
            $('.towns').html('<option value="">请选择所在区/县</option>');
        }
    });
    $('.towns').change(function () {
        area = $('.towns option:selected').text();
        if (area == '请选择所在区/县') {
            area = '';
        }
        $('#address').val(province + city + area);
    })
});

