<?php
	$con = mysql_connect("localhost","root","1234");
	mysql_select_db("db_wms", $con);
	mysql_query("set names gb2312 ");

    $limit_string = '';
    $option = $_GET[option];
    if($option == 'date'){  //�����ڷ�Χ��ѯ
        $date1 = $_GET[date1];
        $date2 = $_GET[date2];
        if($date1 != '' && $date2 == '')
            $limit_string = " and date >= '$date1'";
        else if($date2 != '' && $date1 == '')
            $limit_string = " and date <= '$date2'";
        else if($date1 != '' && $date2 != '')
            $limit_string = " and date between '$date1' and '$date2'";
    }
    else if($option == 'warehouse'){//���ֿ⣨���ڵ������ǳ����ֿ⣩��ѯ
        $id = $_GET[id];
        $limit_string = " and warehouse = '$id'";
    }
    else if($option == 'warehouse2'){//�����ڵ�������������ֿ��ѯ
        $id = $_GET[id];
        $limit_string = " and warehouse2 = '$id'";
    }
	
	$query = "select count(*) as num from test_exchange";//echo $query."<br>";
	$result = mysql_query($query) or die("Invalid query: " . mysql_error());
	$RS = mysql_fetch_array($result);
	$num = $RS[num];
	
	$query = "select * from test_exchange where 1".$limit_string;//echo $query."<br>";
	$result_receipt = mysql_query($query);
    
    $query = "select * from table_warehouse order by id";//echo $query."<br>";
    $result_warehouse = mysql_query($query);//��ȡ�ֿ��б�
	
	$result_warehouse2 = mysql_query($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��������ѯ</title>
</head>
<script type="text/javascript" src="../js/Calendar3.js"></script>
<script language="javascript">
function gotoUrl(target){
	var id = target.innerHTML;
	var url = "receipt_show_exchange.php?id="+id;
	//location.href = url;
	window.open(url,'_blank','directorys=no,toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=640,height=480,top=176,left=161');
}
function optionChange(object){
    hideAll();
    switch(object.value){
        case 'inquiry_time':
            document.getElementById('inquiry_time').hidden = false;
            break;
        case 'inquiry_warehouse':
            document.getElementById('inquiry_warehouse').hidden = false;
            break;
        case 'inquiry_warehouse2':
            document.getElementById('inquiry_warehouse2').hidden = false;
            break;
        default:break;
    }
}
function hideAll(){
    document.getElementById('inquiry_time').hidden = true;  
    document.getElementById('inquiry_warehouse').hidden = true;
    document.getElementById('inquiry_warehouse2').hidden = true;
}
</script>
<script type="text/javascript" src="../js/TableSort_mains.js"></script>
<body>
<h3>��������ѯ</h3>
<?php
echo "<p>���� $num ����¼</p>";
?>
<p>�������ݱ�Ų鿴����</p>
<!--��ȷ��ѯ���-->
<div style="margin-bottom:8px; height:20px"> ��ȷ��ѯ��
  <input id="receipt_id" name="receipt_id" type="text" value="�����뵥��ID" onclick="this.value=''" onblur="if(this.value=='') this.value='�����뵥��ID'"/>
  <input name="search" type="button" value="����" onclick="location.href='receipt_show_exchange.php?id=' + document.getElementById('receipt_id').value"/>
</div>
<!--��ȷ��ѯ���-->
<!--��ѯ���-->
<div style="margin-bottom:8px; height:20px">
  <!--��ѯ��ʽѡ��-->
  <div style="float:left">��ѯ��ʽ��
    <select id="inquiry" name="inquiry" onchange="optionChange(this)">
      <option value="none" selected="selected">��</option>
      <option value="inquiry_time">���ڷ�Χ</option>
      <option value="inquiry_warehouse">�����ֿ�</option>
      <option value="inquiry_warehouse2">����ֿ�</option>
    </select>
  </div>
  <!--��ѯ��ʽѡ��-->
  <!--����-->
  <div id="inquiry_time"  style="float:left"> ��
    <input name="date1" type="text" id="date1" onclick="new Calendar().show(this);" size="8" maxlength="10"/>
    ��
    <input name="date2" type="text" id="date2" onclick="new Calendar().show(this);" size="8" maxlength="10" />
    <input name="search" type="button" value="����" onclick="location.href='inquire_exchange_receipt.php?option=date&date1=' + document.getElementById('date1').value + '&date2=' + document.getElementById('date2').value"/>
  </div>
  <!--����-->
  <!--�ֿ�-->
  <div id="inquiry_warehouse">
    <select name="menu1" onchange="location.href='inquire_exchange_receipt.php?option=warehouse&id='+this.value">
      <option value="none">��ѡ��</option>
      <?php 
          while($RS = mysql_fetch_array($result_warehouse))
            echo "<option value='$RS[id]'>$RS[name]</option>";
      ?>
    </select>
  </div>
  <!--�ֿ�-->
  <!--�ֿ�2-->
  <div id="inquiry_warehouse2">
    <select name="menu1" onchange="location.href='inquire_exchange_receipt.php?option=warehouse2&id='+this.value">
      <option value="none">��ѡ��</option>
      <?php 
          while($RS = mysql_fetch_array($result_warehouse2))
            echo "<option value='$RS[id]'>$RS[name]</option>";
      ?>
    </select>
  </div>
  <!--�ֿ�2-->
</div>
<!--��ѯ���-->
<script language="javascript">hideAll();</script>
<div>
  <input name="" type="button" value="��ʾȫ��" onclick="location.href='inquire_exchange_receipt.php'"/>
</div>
<table id="MyTable" width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#9999FF">
  <thead>
    <tr align="center" bordercolor="#9999FF">
      <td onclick="SortTable('MyTable',0,'string')" style="cursor:pointer">���ݱ��</td>
      <td onclick="SortTable('MyTable',1,'string')" style="cursor:pointer">�Ƶ�����</td>
      <td onclick="SortTable('MyTable',2,'string')" style="cursor:pointer">ҵ��Ա</td>
      <td onclick="SortTable('MyTable',3,'string')" style="cursor:pointer">�����ֿ�</td>
      <td onclick="SortTable('MyTable',4,'string')" style="cursor:pointer">����ֿ�</td>
      <td>��ע</td>
    </tr>
  </thead>
  <tbody>
    <?php
	while($RS = mysql_fetch_array($result_receipt))
	{
		echo "<tr align='center' bordercolor='#9999FF'>";
		echo "<td onclick='gotoUrl(this)' style='background-color:#CCCCCC'>$RS[id]</td>\n";
		echo "<td>$RS[date]</td>\n";
		echo "<td>$RS[yewuyuan]</td>\n";
		$query = "select name from table_warehouse where id = '$RS[warehouse]'";//echo $query."<br>";
		$result = mysql_query($query);
		$RS2 = mysql_fetch_array($result);
		echo "<td>$RS2[name]</td>\n";//�ֿ�����
		$query = "select name from table_warehouse where id = '$RS[warehouse2]'";//echo $query."<br>";
		$result = mysql_query($query);
		$RS2 = mysql_fetch_array($result);
		echo "<td>$RS2[name]</td>\n";//�ֿ�����
		echo "<td>$RS[remark]</td>\n";
		echo "</tr>";
	}
?>
  </tbody>
</table>
</body>
</html>