<?php 
function dd($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}


function getSortedCategory($data,$pid=0,$html="|---",$level=0)
{
	$temp = array();
	foreach ($data as $k => $v) {
		if($v['pid'] == $pid){
	
			$str = str_repeat($html, $level);
			$v['html'] = $str;
			$temp[] = $v;

			$temp = array_merge($temp,getSortedCategory($data,$v['id'],'|---',$level+1));
		}
		
	}
	return $temp;
}


function getSettingValueDataByKey($key)
{
	return M('setting')->getByKey($key);
}


function getSettingValueFieldByKey($key,$field)
{
	return M('setting')->getFieldByKey($key,$field);
}