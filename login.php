<?php
	$id=$_GET['id'];//帳戶編號
	
	//連結mysql
	include_once("..\link.php");
	$mysqli=pcf();
	
	
	$sql = "SELECT * FROM pcf_user ";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()){
		$dbid=md5($row['id']);
		
		if($dbid==$id){//用戶資料
			$login=$row['mt4_login'];
			$cash=$row['cash'];
			$credit=0;
		}
	}
	
	if($login!=null){
		$mysqlifac=fac();
		$sqlfac = "SELECT * FROM mt4_users where LOGIN=$login";
		$resultfac = $mysqlifac->query($sqlfac);
		while($rowfac = $resultfac->fetch_array()){
			$BALANCE=$rowfac['BALANCE'];//餘額
			$EQUITY=$rowfac['EQUITY'];//淨值
			$MARGIN_FREE=$rowfac['MARGIN_FREE'];//可用保證金
			$MARGIN_LEVEL=$rowfac['MARGIN_LEVEL'];//保证金比例
		}
		
		$sqlfac = "SELECT * FROM mt4_trades where LOGIN=$login and CMD<2";
		$resultfac = $mysqlifac->query($sqlfac);
		while($rowfac = $resultfac->fetch_array()){
			$lottotl=$lottotl+$rowfac['VOLUME'];
			if($rowfac['CLOSE_TIME']!='1970-01-01 00:00:00'){
				$rstotal=$rstotal+$rowfac['SWAPS']+$rowfac['PROFIT'];
			}else{
				$flottotal=$flottotal+$rowfac['SWAPS']+$rowfac['PROFIT'];
			}
		}
		
		
		$data['totalcash']=number_format($BALANCE+$cash,2);
		$data['mt4_cash']=number_format($BALANCE,2);//餘額
		$data['equity']=number_format($EQUITY,2);//淨值
		$data['credit']=number_format($credit,2);
		$data['margin_free']=number_format($MARGIN_FREE,2);//可用保證金
		$data['margin_level']=number_format($MARGIN_LEVEL,2);//保证金比例
		
		$data['lots']=$lottotl/100;//總手數
		$data['rstotal']=number_format($rstotal,2);//历史总收益
		$data['flottotal']=number_format($flottotal,2);//浮动盈亏
		$data['reper']=number_format(($rstotal/$BALANCE)*100,2);//总收益率
		$data['cash']=number_format($cash,2);
	}else{
		$data['totalcash']=number_format(0,2);
		$data['mt4_cash']=number_format(0,2);//餘額
		$data['equity']=number_format(0,2);//淨值
		$data['credit']=number_format(0,2);
		$data['margin_free']=number_format(0,2);//可用保證金
		$data['margin_level']=number_format(0,2);//保证金比例
		
		$data['lots']='0.00';//總手數
		$data['rstotal']=number_format(0,2);//历史总收益
		$data['flottotal']=number_format(0,2);//浮动盈亏
		$data['reper']=number_format(0,2);//总收益率
		$data['cash']=number_format(0,2);
	}
	
	
	
	echo json_encode($data);
?>