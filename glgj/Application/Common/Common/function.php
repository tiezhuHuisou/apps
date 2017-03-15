<?php
/**
 * 保留最低级分类
 * @param  [Array]  $category   [需要取出数组列的多维数组]
 * @param  [String] $parent_key [父级分类ID]
 * @param  [String] $index_key  [作为返回数组的索引/键的列，它可以是该列的整数索引，或者字符串键值。默认是分类ID]
 * @return [type]   $result     [处理后的数组]
 */
function lowest( $category, $parent_key = 'parent_id', $index_key = 'id' ) {
    foreach ($category as $value) {
        $result[$value[$index_key]] = $value;
    }
    foreach ($result as $val) {
        if ( count($result[$val[$parent_key]]) > 0 ) {
            unset($result[$val[$parent_key]]);
        }
    }
    $result = array_values($result);
    return $result;
}

/**
 * 数字格式化
 */
function num_format($num) { 
    if(!is_numeric($num)){ 
        return false; 
    } 
    $num = explode('.',$num);
    $rl = $num[1];
    $j = strlen($num[0]) % 3;
    $sl = substr($num[0], 0, $j);
    $sr = substr($num[0], $j);
    $i = 0; 
    while($i <= strlen($sr)){ 
        $rvalue = $rvalue.','.substr($sr, $i, 3);
        $i = $i + 3; 
    } 
    $rvalue = $sl.$rvalue; 
    $rvalue = substr($rvalue,0,strlen($rvalue)-1);
    $rvalue = explode(',',$rvalue);
    if($rvalue[0]==0){ 
        array_shift($rvalue);
    } 
    $rv = $rvalue[0];
    for($i = 1; $i < count($rvalue); $i++){ 
        $rv = $rv.','.$rvalue[$i]; 
    } 
    if(!empty($rl)){ 
        $rvalue = $rv.'.'.$rl;
    }else{ 
        $rvalue = $rv;
    } 
    return $rvalue;
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 * @author 122837594@qq.com
 */
function think_encrypt($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time():0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 * @author 122837594@qq.com
 */
function think_decrypt($data, $key = ''){
    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
     $data .= substr('====', $mod4);
 }
 $data   = base64_decode($data);
 $expire = substr($data,0,10);
 $data   = substr($data,10);

 if($expire > 0 && $expire < time()) {
    return '';
}
$x      = 0;
$len    = strlen($data);
$l      = strlen($key);
$char   = $str = '';

for ($i = 0; $i < $len; $i++) {
    if ($x == $l) $x = 0;
    $char .= substr($key, $x, 1);
    $x++;
}

for ($i = 0; $i < $len; $i++) {
    if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
        $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
    }else{
        $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
    }
}
return base64_decode($str);
}

/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型
* asc正向排序 desc逆向排序 nat自然排序
* @return array
* @author 122837594@qq.com
*/
function list_sort_by($list,$field, $sortby='asc') {
 if(is_array($list)){
     $refer = $resultSet = array();
     foreach ($list as $i => $data)
         $refer[$i] = &$data[$field];
     switch ($sortby) {
           case 'asc': // 正向排序
           asort($refer);
           break;
           case 'desc':// 逆向排序
           arsort($refer);
           break;
           case 'nat': // 自然排序
           natcasesort($refer);
           break;
       }
       foreach ( $refer as $key=> $val)
         $resultSet[] = &$list[$key];
     return $resultSet;
 }
 return false;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 122837594@qq.com
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author 122837594@qq.com
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author 122837594@qq.com
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}

/**
 * 验证手机号码
 * $mobile intval 手机号码
 * @return boolean true-手机号码正确，false-手机号码错误
 * @author 122837594@qq.com
 */

function checkMobile($mobile){
    if(preg_match('/^1\d{10}$/', $mobile)){
        return true;
    }else{
        return false;
    }
}

/**
 * @param array $input 数组
 * @param string $columnKey 必需。需要返回值的列。
 */
if(!function_exists("array_column")){
    function array_column($input, $columnKey, $indexKey = NULL){
        $columnKeyIsNumber = (is_numeric($columnKey)) ? TRUE : FALSE;
        $indexKeyIsNull = (is_null($indexKey)) ? TRUE : FALSE;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? TRUE : FALSE;
        $result = array();

        foreach ((array)$input AS $key => $row){ 
            if ($columnKeyIsNumber){
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : NULL;
            }else{
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : NULL;
            }
            if (!$indexKeyIsNull){
                if ($indexKeyIsNumber){
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && ! empty($key)) ? current($key) : NULL;
                    $key = is_null($key) ? 0 : $key;
                }else{
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }
}


 /**
     * 时间戳转换成几分、几天、前格式
     * @param string $time 必需。需要转换的时间。
     */
 function mdate($time = NULL) {
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
        $t = time() - $time; //时间差 （秒）
        $y = date('Y', $time)-date('Y', time());//是否跨年
        switch($t){
           case $t == 0:
           $text = '刚刚';
           break;
           case $t < 60:
          $text = $t . '秒前'; // 一分钟内
          break;
          case $t < 60 * 60:
          $text = floor($t / 60) . '分钟前'; //一小时内
          break;
          case $t < 60 * 60 * 24:
          $text = floor($t / (60 * 60)) . '小时前'; // 一天内
          break;
          case $t < 60 * 60 * 24 * 3:
          $text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
          break;
          case $t < 60 * 60 * 24 * 30:
          $text = date('m月d日 H:i', $time); //一个月内
          break;
          case $t < 60 * 60 * 24 * 365&&$y==0:
          $text = date('m月d日', $time); //一年内
          break;
          default:
          $text = date('Y年m月d日', $time); //一年以前
          break; 
      }

      return $text;
  }
    /**
     * 得到新订单号
     * @return  string
     */
    function build_order_no()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);

        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
 * 处理无限级分类
 * @param $arr 要转换的数据集
 * @param $pid parent标记字段
 * @param $height 层数
 * @return  $arr
 * @author  122837594@qq.com
 */
    function getMyCategoryTree($arr, $pid = 0, $height = 0) {

        if (empty($arr[$pid]) or !isset($arr[$pid])) {
            return '';
        }
        $html = '';
        $solid = str_repeat('&nbsp', $height * 3);
        foreach ($arr[$pid] as $val) {

            $html .="<option value=" . $val["id"] . ">" . $solid . $val['title'] . "</option>";

            $html .=getMyCategoryTree($arr, $val["id"], $height + 1);
        }

        return $html;
    }