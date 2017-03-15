$(function() {
    $('.delete_img').click(function() {
        $(this).parents('.img_wrap').remove();
    });
    
    KindEditor.ready(function(K) {
        var editor1 = K.editor({
            allowFileManager : true
        });

        var editor2 = K.editor({
            allowFileManager : true
        });

        var editor3 = K.editor({
            allowFileManager : true
        });

        var specImg = K.editor({
            themeType        : "simple",
            allowFileManager : true,
            formatUploadUrl  : false
        });

        K.create('#supply_content',{
            width        : '670px',
            height       : '300px',
            items       : ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
            afterBlur    : function(){this.sync();},
            resizeType   : 0
        });

        $('#carousel_btn').click(function() {           // 轮播图
            editor3.loadPlugin('multiimage', function() {
                editor3.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var temp = K('#carousel_img');
                        K.each(urlList, function(i, data) {
                            temp.append('<div class="img_wrap"><img class="delete_img" src="' + imgUrl + '/delete_img.png" width="20" height="20" /><img src="' + data.url + '" width="100" height="100" /><input value="' + data.url + '" name="allpic[]" type="hidden" /></div>');
                        });
                        $('.delete_img').click(function() {
                            $(this).parents('.img_wrap').remove();
                        });
                        $('#carousel_img').parents('tr').show();
                        editor3.hideDialog();
                    }
                });
            });
            return false;
        });

        /* 规格图片上传 */
        $('body').on('click', '.spec_img', function() {
            var _this = $(this);
            specImg.loadPlugin('image', function() {
                specImg.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        _this.attr('src', url).siblings('input[type="hidden"]').val(url);
                        specImg.hideDialog();
                    }
                });
            });
            return false;
        });
    });

    /* 分类列表显隐 */
    $('.category_btn').click(function(){
        $('.category_wrap').toggle();
    });
    /* 点击一级分类 */
    $('.one_list').change(function(){
        var _this = $(this);
        var _check = $(this).find('input[type="checkbox"]').prop('checked');
        _this.next().find('.two_list').prop('checked',_check);
    });

    /* 点击商品分类外部隐藏商品分类列表 */
    $('body').click(function(e){
        var wrapLeft   = $('.category_wrap').offset().left;                                  // 分类列表离左侧的距离
        var wrapRight  = wrapLeft + $('.category_wrap').width();                             // 分类列表右侧离左侧的距离
        var wrapTop    = $('.category_wrap').offset().top;                                   // 分类列表离顶部的距离
        var wrapBottom = $('.category_wrap').offset().top + $('.category_wrap').height();    // 分类列表底部离顶部的距离
        var eventX     = e.pageX;                                                            // 事件横坐标位置
        var eventY     = e.pageY;
        if ( eventX > wrapRight || eventX < wrapLeft ) {                                     // 事件横坐标不在分类列表区域内
            $('.category_wrap').hide();
        } else if ( eventY > wrapBottom || eventY < wrapTop - 28 ) {                         // 事件纵坐标不在分类列表区域内
            $('.category_wrap').hide();
        }
    });

    /* 开启规格 */
    $('#spec_on').click(function() {
        $('#spec_wrap').show();
        $('#spec_toggle').removeClass('no').text('收起规格');
        $('#is_spec').val(1);
    });

    /* 关闭规格 */
    $('#spec_off').click(function() {
        $('#spec_wrap').hide();
        $('#spec_toggle').addClass('no');
        $('#is_spec').val(0);
    });

    /* 隐藏、显示规格 */
    $('#spec_toggle').click(function() {
        if ( $('#spec_wrap').is(':hidden') ) {
            $(this).text('收起规格');
        } else {
            $(this).text('展开规格');
        }
        $('#spec_wrap').toggle();
    });

    /* 添加一行 */
    $('#spec_add').click(function() {
        var _html = '';
        _html += '<ul>';
        _html += '<li><input class="i70 spec_spec1" type="text" name="spec[spec1][]" value="" placeholder="例：红色" /></li>';
        _html += '<li><input class="i70 spec_spec2" type="text" name="spec[spec2][]" value="" placeholder="例：均码" /></li>';
        _html += '<li><input class="i50 spec_price" type="text" name="spec[price][]" value="' + $('#price').val() + '" /></li>';
        _html += '<li><input class="i50" type="text" name="spec[oprice][]" value="' + $('#oprice').val() + '" /></li>';
        if ( flashFlag == 1 ) {
            _html += '<li><input class="i50" type="text" name="spec[activity_price][]" value="' + $('#activity_price').val() + '" /></li>';
        }
        _html += '<li><input class="i50" type="text" name="spec[weight][]" value="' + $('#weight').val() + '" /></li>';
        _html += '<li><input class="i50 spec_stock" type="text" name="spec[stock][]" value="9999" /></li>';
        _html += '<li class="w80"><input class="i50 spec_buymin" type="text" name="spec[buymin][]" value="1" /></li>';
        _html += '<li><input class="i50 spec_sort" type="text" name="spec[sort][]" value="0" /></li>';
        _html += '<li class="img_wrap">';
        _html += '<img class="spec_img" src="'+staticPath+'/images/default_icon.jpg" width="50" height="50" />';
        _html += '<input type="hidden" name="spec[img][]" value="'+staticPath+'/images/default_icon.jpg">';
        _html += '</li>';
        _html += '<li>';
        _html += '<a class="spec_up" href="javascript:void(0);">使用上图</a>';
        _html += '<a class="spec_copy" href="javascript:void(0);">复制</a>';
        _html += '<a class="spec_del" href="javascript:void(0);">删除</a>';
        _html += '</li>';
        _html += '</ul>';
        $('#last_ul').before(_html);
        /* 重新执行验证规格数据函数 */
        checkSpecData();
    });

    /* 使用上图 */
    $('body').on('click', '.spec_up', function() {
        var newImgUrl = $(this).parents('ul').prev('ul').find('.spec_img').attr('src');
        $(this).parents('ul').find('.spec_img').attr('src', newImgUrl);
        $(this).parents('ul').find("input[type='hidden']").val(newImgUrl);
    });

    /* 复制 */
    $('body').on('click', '.spec_copy', function() {
        var i = flashFlag == 1 ? 5 : 4;             // 有活动i为5，没有活动i为4，方便复制功能
        var liObj = $(this).parents('ul').find('li');
        var _html = '';
        _html += '<ul>';
        _html += '<li><input class="i70 spec_spec1" type="text" name="spec[spec1][]" value="' + liObj.eq(0).find('input').val() + '" placeholder="例：红色" /></li>';
        _html += '<li><input class="i70 spec_spec2" type="text" name="spec[spec2][]" value="' + liObj.eq(1).find('input').val() + '" placeholder="例：均码" /></li>';
        _html += '<li><input class="i50 spec_price" type="text" name="spec[price][]" value="' + liObj.eq(2).find('input').val() + '" /></li>';
        _html += '<li><input class="i50" type="text" name="spec[oprice][]" value="' + liObj.eq(3).find('input').val() + '" /></li>';
        if ( flashFlag == 1 ) {
            _html += '<li><input class="i50" type="text" name="spec[activity_price][]" value="' + liObj.eq(4).find('input').val() + '" /></li>';
        }
        _html += '<li><input class="i50" type="text" name="spec[weight][]" value="' + liObj.eq(i).find('input').val() + '" /></li>';
        _html += '<li><input class="i50 spec_stock" type="text" name="spec[stock][]" value="' + liObj.eq(i+1).find('input').val() + '" /></li>';
        _html += '<li class="w80"><input class="i50 spec_buymin" type="text" name="spec[buymin][]" value="' + liObj.eq(i+2).find('input').val() + '" /></li>';
        _html += '<li><input class="i50 spec_sort" type="text" name="spec[sort][]" value="' + liObj.eq(i+3).find('input').val() + '" /></li>';
        _html += '<li class="img_wrap">';
        _html += '<img class="spec_img" src="' + liObj.eq(i+4).find('.spec_img').attr('src') + '" width="50" height="50" />';
        _html += '<input type="hidden" name="spec[img][]" value="' + liObj.eq(i+4).find('.spec_img').attr('src') + '">';
        _html += '</li>';
        _html += '<li>';
        _html += '<a class="spec_up" href="javascript:void(0);">使用上图</a>';
        _html += '<a class="spec_copy" href="javascript:void(0);">复制</a>';
        _html += '<a class="spec_del" href="javascript:void(0);">删除</a>';
        _html += '</li>';
        _html += '</ul>';
        $('#last_ul').before(_html);
    });

    /* 删除 */
    $('body').on('click', '.spec_del', function() {
        if ( $('.spec_table').find('ul').length > 3 ) {
            $(this).parents('ul').remove();
        } else {
            layer.msg('至少要保留一行', {time: 1500});
        }
    });

    /* 规格数据验证 */
    checkSpecData();

    /* 提交时再次验证规格 */
    $('.chksubmit').click(function() {
        /* 开启规格状态 */
        if ( $('#is_spec').val() == 1 ) {
            /* 标题1 */
            if ( !checkEmpty($('.spec_title1'), true) ) {
                return false;
            }
            /* 名称1 */
            var spec1Length = 0;
            $('.spec_spec1').each(function() {
                if ( !checkEmpty($(this), true) ) {
                    return false;
                }
                spec1Length++;
            });
            if ( $('.spec_spec1').length != spec1Length ) {
                return false;
            }
            /* 标题2不为空 验证名称2 */
            if ( $.trim($('.spec_title2').val()) ) {
                var spec2Length = 0;
                $('.spec_spec2').each(function() {
                    if ( !checkEmpty($(this), true) ) {
                        return false;
                    }
                    spec2Length++;
                });
                if ( $('.spec_spec2').length != spec2Length ) {
                    return false;
                }
            }
            /* 价格 */
            var priceLength = 0;
            $('.spec_price').each(function() {
                if ( !checkEmpty($(this), true) ) {
                    return false;
                }
                var regMoney = /^\d+(.\d{1,2})?$/;
                if ( !regMoney.test( $(this).val() ) ) {
                    $(this).focus();
                    layer.tips('请输入正确的价格', $(this), {
                        tips: 1,
                        time: 1500
                    });
                    return false;
                }
                priceLength++;
            });
            if ( $('.spec_price').length != priceLength ) {
                return false;
            }
            /* 库存 */
            var stockLength = 0;
            $('.spec_stock').each(function() {
                if ( !checkInt($(this), true) ) {
                    return false;
                }
                stockLength++;
            });
            if ( $('.spec_stock').length != stockLength ) {
                return false;
            }
            /* 最小购买数量 */
            var buyminLength = 0;
            $('.spec_buymin').each(function() {
                if ( !checkInt($(this), true) ) {
                    return false;
                }
                buyminLength++;
            });
            if ( $('.spec_stock').length != buyminLength ) {
                return false;
            }
            /* 排序 */
            var sortLength = 0;
            $('.spec_sort').each(function() {
                if ( !checkInt($(this), true) ) {
                    return false;
                }
                sortLength++;
            });
            if ( $('.spec_sort').length != sortLength ) {
                return false;
            }
        }
    });
});

/**
 * 验证对象的值是否为空
 * @param  {[Object]}  obj  [待验证对象]
 * @param  {[Boolean]} flag [true->验证不通过焦点定位到待验证对象上，false则不会定位焦点，默认false]
 * @return {[Boolean]}      [true->待验证对象的值不为空，反之false]
 */
function checkEmpty( obj, flag ) {
    var flag = arguments[1] || false;
    var _val = $.trim(obj.val());
    if ( !_val ) {
        if ( flag ) {
            obj.focus();
        }
        layer.tips('该项不能为空', obj, {
            tips: 1,
            time: 1000
        });
        return false;
    }
    return true;
}

/**
 * 验证对象的值是否为正整数
 * @param  {[Object]}  obj  [待验证对象]
 * @param  {[Boolean]} flag [true->验证不通过焦点定位到待验证对象上，false则不会定位焦点，默认false]
 * @return {[Boolean]}      [true->待验证对象的值为正整数，反之false]
 */
function checkInt( obj, flag ) {
    var flag = arguments[1] || false;
    var val  = obj.val();
    if ( !val ) {
        if ( flag ) {
            obj.focus();
        }
        layer.tips('该项不能为空', obj, {
            tips: 1,
            time: 1000
        });
        return false;
    }
    var reg = /^\d+$/;
    if ( !reg.test( val ) ) {
        if ( flag ) {
            obj.focus();
        }
        layer.tips('请输入正整数', obj, {
            tips: 1,
            time: 1500
        });
        return false;
    }
    return true;
}

/**
 * 验证规格数据
 */
function checkSpecData() {
    /* 标题1 */
    $('.spec_title1').blur(function() {
        checkEmpty($(this));
    });

    /* 名称1 */
    $('.spec_spec1').blur(function() {
        checkEmpty($(this));
    });

    /* 名称2 */
    $('.spec_spec2').blur(function() {
        if ( $.trim($('.spec_title2').val()) ) {
            checkEmpty($(this));
        }
    });

    /* 价格 */
    $('.spec_price').blur(function() {
        var _this = $(this);
        var _val  = _this.val();
        if ( !checkEmpty(_this) ) {
            return false;
        }
        var regMoney = /^\d+(.\d{1,2})?$/;
        if ( !regMoney.test( _val ) ) {
            layer.tips('请输入正确的价格', _this, {
                tips: 1,
                time: 1500
            });
            return false;
        }
    });

    /* 库存 */
    $('.spec_stock').blur(function() {
        checkInt($(this));
    });

    /* 最小购买数量 */
    $('.spec_buymin').blur(function() {
        checkInt($(this));
    });

    /* 排序 */
    $('.spec_sort').blur(function() {
        checkInt($(this));
    });
}