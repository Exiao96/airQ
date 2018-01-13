<?php
  $pm_25='http://www.pm25.in/api/querys/pm2_5.json?city=shanghai&token=5j1znBVAsnSf5xQyNQyq';
  $pm25_array=JsontoArray(getJson($pm_25));
  $array_length=count($pm25_array);
  insertDB($pm25_array,$array_length);
  
  


  //SQL语句：创建aqi表单
  //$sql="CREATE TABLE IF NOT EXISTS aqi(AQI INT(5),AREA VARCHAR(30),PM25 INT(5),PM25_24 INT(5),POSITION_NAME VARCHAR(30),PRIMARYP VARCHAR(30),QUILITY VARCHAR(30),SATATION_CODE VARCHAR(30),TIME_POINT TIMESTAMP)DEFAULT CHARSET=utf8";


  
  //方法：通过转换后的数组插入数据库 
  function insertDB($array,$length){
    for($i=0;$i<$length;++$i){
      $query="INSERT INTO aqi(AQI,AREA,PM25,PM25_24,POSITION_NAME,PRIMARYP,QUILITY,SATATION_CODE,TIME_POINT) VALUES ("."'".$array[$i]["aqi"]."'".","."'".$array[$i]["area"]."'".","."'".$array[$i]["pm2_5"]."'".","."'".$array[$i]["pm2_5_24h"]."'".","."'".$array[$i]["position_name"]."'".","."'".$array[$i]["primary_pollutant"]."'".","."'".$array[$i]["quality"]."'".","."'".$array[$i]["station_code"]."'".","."'".$array[$i]["time_point"]."'".");";
      echo $query;
      operateDB($query,"test_db");
    }
  }
 
 
  //方法：连接数据库并使用sql语句 参数1 sql语句 参数2 数据库名称
  function operateDB($sql,$dbname){
    $con=mysqli_connect("localhost:3306","root","exiao",$dbname);            //php5.5开始不支持mysql_函数 
    $query=$sql;                               
    if(!$con){
      die('Link disable,error:' . mysqli_connect_error());
    }
    if(mysqli_query($con,$query)){
      echo "operate success";
      echo "<br>";
    }
    else{
      echo "create disable:" . mysqli_connect_error();
      echo "<br>";
    }
      mysqli_close($con);
  }


  //方法:将pm25数组数据格式化输出
  function formatPrint($array,$length){
  	for($i=0;$i<$length;++$i){
  		echo $array[$i]["aqi"]." ".$array[$i]["area"]." ".$array[$i]["pm2_5"]." ".$array[$i]["pm2_5_24h"]." ".$array[$i]["position_name"]." ".$array[$i]["primary_pollutant"]." ".$array[$i]["quality"]." ".$array[$i]["station_code"]." ".$array[$i]["time_point"];
  		echo "<br>";
  	}

  }
  

  //方法：将json转化为数组
  function JsontoArray($jsonData){
  	$arrayData=json_decode($jsonData,true);           //强制转换成php 关联array 使用索引&序号查询
    return $arrayData;
  }
 

  //方法：使用curl模块调用api获取数据
  function getJson($url){   
    $con=curl_init($url);                           //初始化curl同时设置url实现get请求
    curl_setopt($con,CURLOPT_HEADER,false);         //curl设置  不反悔hppt请求头
    curl_setopt($con,CURLOPT_RETURNTRANSFER,true);  //curl设置 直接返回字符串而不是放入标准输出流
    curl_setopt($con,CURLOPT_TIMEOUT, 5);           //curl设置 响应时间
    $myJson=curl_exec($con);
    curl_close($con);                               //释放句柄
    return $myJson;  
  }

?>