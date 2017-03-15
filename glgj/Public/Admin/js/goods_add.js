$(function() {
    /* 选择省 拉取后台城市数据并展示 重置区/县数据 */
    $('.province').change(function(){
        var id = $(this).val();
        if ( id ) {
            var city_arr = data[id];
            var str_city = '<option value="">请选择所在市</option>';
            for ( var i = 0; i < city_arr.length; i++ ) {
                str_city +='<option value="' + city_arr[i]['id'] + '">' + city_arr[i]['name'] + '</option>';
            }
            $('.city').html(str_city);
        }else{
            $('.city').html('<option value="">请选择所在市</option>');
            $('.towns').html('<option value="">请选择所在区/县</option>');
        }
    });
    /* 选择市 拉取后台区/县数据并展示 */
    $('.city').change(function(){
        var id = $(this).val();
        if ( id ) {
            var town_arr = data[id];
            var str_town = '<option value="">请选择所在区/县</option>';
            for (var i = 0; i < town_arr.length; i++) {
                str_town +='<option value="' + town_arr[i]['id'] + '">' + town_arr[i]['name'] + '</option>';
            }
            $('.towns').html(str_town);
        } else {
            $('.towns').html('<option value="">请选择所在区/县</option>');
        }
    });

    /*目的地部分*/
    /* 选择省 拉取后台城市数据并展示 重置区/县数据 */
    $('.province2').change(function(){
        var id = $(this).val();
        if ( id ) {
            var city_arr = data[id];
            var str_city = '<option value="">请选择所在市</option>';
            for ( var i = 0; i < city_arr.length; i++ ) {
                str_city +='<option value="' + city_arr[i]['id'] + '">' + city_arr[i]['name'] + '</option>';
            }
            $('.city2').html(str_city);
        }else{
            $('.city2').html('<option value="">请选择所在市</option>');
            $('.towns2').html('<option value="">请选择所在区/县</option>');
        }
    });
    /* 选择市 拉取后台区/县数据并展示 */
    $('.city2').change(function(){
        var id = $(this).val();
        if ( id ) {
            var town_arr = data[id];
            var str_town = '<option value="">请选择所在区/县</option>';
            for (var i = 0; i < town_arr.length; i++) {
                str_town +='<option value="' + town_arr[i]['id'] + '">' + town_arr[i]['name'] + '</option>';
            }
            $('.towns2').html(str_town);
        } else {
            $('.towns2').html('<option value="">请选择所在区/县</option>');
        }
    });
});