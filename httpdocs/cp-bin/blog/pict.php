<?php
//------------------------------------------------------------------------
//
// ����Ѳ�������
//
// LAST UPDATE 2004/10/14
//
// ��������̵�����Υ��顼ɽ�����Фʤ��褦�˽���
//
//------------------------------------------------------------------------
/* ===== ��ͭ�ե������ɤ߹��� */
require_once("common.php");

session_start();
if(!logincheck($_SESSION['blogni'], $_SESSION['blognp'])) {
	echo '���å���󥨥顼���������̤�����ʤ����Ƥ���������';
	exit;
}

$upfile_name = @$_FILES["d_img"]["name"];
$upfile = @$_FILES["d_img"]["tmp_name"];

//������ƽ���
if ($action == "post") {
	$inform = "";
	if (!$upfile_name) {
		$inform = "���åץ��ɤ���ե�����̾�����Ϥ��Ƥ����ɲåܥ���򲡤��Ƥ���������";
	}elseif(ereg("[\xA1-\xFE]", $upfile_name)) {
		$inform = "���ܸ�ʸ����ޤ�ե�����̾�ϥ��åץ��ɤǤ��ޤ���<br>Ⱦ�ѱѿ�ʸ���ǥ��åץ��ɤ��Ƥ���������";
	}else{
		$dest = PICDIR.$upfile_name;
		if (file_exists($dest)) {
			$pathname = pathinfo($dest);
			$dest = PICDIR.gmdate("YmdHis",time() + TIMEZONE).$pathname['extension'];
			$inform = "��ʣ�����ե�����̾�����ä��١��ե�����̾���ѹ����ޤ�����<br>";
		}
		copy($upfile, $dest);
		$size = @getimagesize($dest);
		if($size[2] != 1 && $size[2] != 2 && $size[2] != 3){
			@unlink($dest);
			$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>������������װʳ��ϼ����դ��ޤ���<br>";
		}else{
			$finfo = pathinfo($upfile_name);
			if(file_exists($upfile)){
				if(!is_file($dest)) {
					$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>�����Ф����ݡ��Ȥ��Ƥ��ʤ���ǽ��������ޤ�<br>";
				}else{
					$inform .= "�����򥢥åץ��ɤ��ޤ�����";
				}
			}
		}
	}
}

if ($page == "") $page = 0;
//$page����ɽ�����ϰ��֤򻻽�
$pcnt = $page * 5;	//5���1�ڡ���ɽ��
$page_st = 5 * $page + 1;
$page_ed = $page_st + 5;
$i = 0;
$exitkey = 0;

$dir_name = dir(PICDIR);
while($file_name = $dir_name->read()) {
	if (!is_dir($file_name)) {
		if ($size = @getimagesize(PICDIR.$file_name)) {
			if ($size[2] == 1 || $size[2] == 2 || $size[2] == 3) {
				if ($size[0] < 80 && $size[1] < 80) {
					$width = $size[0];
					$height = $size[1];
				}elseif ($size[0] > $size[1]) {
					$width = 80;
					$height = round(80 * $size[1] / $size[0]);
				}else{
					$width = round(80 * $size[0] / $size[1]);
					$height = 80;
				}
				$picdir[$i] = PICDIR.$file_name;
				$picwh[$i] = 'width="'.$width.'" height="'.$height.'"';
				$ftime[$i] = date("Y/m/d H:i:s", filemtime(PICDIR.$file_name));


				if ($size[0] > MAXWIDTH || $size[1] > MAXHEIGHT) {
					$ratio1 = MAXWIDTH / $size[0];
					$ratio2 = MAXHEIGHT / $size[1];
					if ($ratio1 < $ratio2) {
						$ratio = $ratio1;
					}else{
						$ratio = $ratio2;
					}
					$rwidth = round($size[0] * $ratio);
					$rheight = round($size[1] * $ratio);
					$picwhjs[$i] = 'width=\"'.$rwidth.'\" height=\"'.$rheight.'\"';
					$picbsjs[$i] = true;
				}else{
					$picwhjs[$i] = 'width=\"'.$size[0].'\" height=\"'.$size[1].'\"';
					$picbsjs[$i] = false;
				}

				$i++;
			}
		}
	}
}
@arsort($ftime);

echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<title>��������</title>
<meta http-equiv=content-type content="text/html; charset=EUC-JP">
<style type="text/css">
<!--
body {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	margin: 10px;
	background-color: #FFFF;
}
#auto {overflow: auto;}

a:link    {color:#414D7B; text-decoration: underline;}
a:visited {color:#775E81; text-decoration: none;}
a:active  {color:#FFCC33; text-decoration: underline;}
a:hover   {color:#FFCC33; text-decoration: none;}

hr {
	margin: 2px 0px 2px 0px;
	border: 1px solid #CCCCCC;
}
.maintitle {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 14px;
	font-weight: bolder;
	color: #414D7B;
	border-bottom: 1px dotted #CCCCCC;
}

.logbody {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 12px;
	color: #666666;
}
.linktitle {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 12px;
	font-weight: bolder;
	color: #000000;
	margin: 0px 0px 0px 0px;
}
.inputarea {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 10px;
	color: #000000;
	margin: 0px;
}
.mainstate {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 10px;
	color: #999999;
}
.maininfo {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 12px;
	font-weight: bolder;
	color: #414D7B;
	border-left: 10px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	padding: 5px 0px 5px 5px;
}
-->
</style>
<script language="JavaScript">
<!--
';
if (count($picdir) != 0) {
	$k = 0;
	echo '
		picdir = new Array();
		picwh = new Array();
		picbs = new Array();
	';
	$j = 1;
	while(list($key, $val) = each($ftime)) {
		if ($j >= $page_st && $j < $page_ed) {
			echo '
			picdir['.$k.'] = "'.$picdir[$key].'";
			picwh['.$k.'] = "'.$picwhjs[$key].'";
			picbs['.$k.'] = "'.$picbsjs[$key].'";
			';
			$k++;
//			if ($j >= $i) break;
		}
		$j++;
	}
}

echo '
function pins(t) {
	if (document.pict.pins[0].checked) {
		var txt = window.opener.document.post.d_mes;
	}else{
		var txt = window.opener.document.post.d_more;
	}
	if (document.pict.pfloat[0].checked) {
		if (picbs[t]) {
			var text = "<a href=\"" + picdir[t] + "\" target=\"_blank\"><img src=\"" + picdir[t] + "\" " + picwh[t] + "></a>";
		}else{
			var text = "<img src=\"" + picdir[t] + "\" " + picwh[t] + ">";
		}
	} else if (document.pict.pfloat[1].checked) {
		if (picbs[t]) {
			var text = "<a href=\"" + picdir[t] + "\" target=\"_blank\"><img src=\"" + picdir[t] + "\" " + picwh[t] + " style=\"float:left;\"></a>";
		}else{
			var text = "<img src=\"" + picdir[t] + "\" " + picwh[t] + " style=\"float:left;\">";
		}
	}else{
		if (picbs[t]) {
			var text = "<a href=\"" + picdir[t] + "\" target=\"_blank\"><img src=\"" + picdir[t] + "\" " + picwh[t] + " style=\"float:right;\"></a>";
		}else{
			var text = "<img src=\"" + picdir[t] + "\" " + picwh[t] + " style=\"float:right;\">";
		}
	}
	txt.focus();
	if (txt.createTextRange && txt.caretPos) {
		var caretPos = txt.caretPos;
		caretPos.text = caretPos.text + text;
		txt.focus();
	} else if (txt.selectionStart) {
		txt.value = (txt.value).substring(0,txt.selectionStart) + text + (txt.value).substring(txt.selectionEnd, txt.textLength);
	}else{
		txt.value  += text;
		txt.focus();
	}
}
//-->
</script>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
';
if ($inform != "") {
	echo '<div class="maininfo">'.$inform.'</div>';
}
echo '
<div class="mainbody">
	<div class="maintitle">�������ɲ�����</div>
	<div class="linktitle">�� �������åץ��� ��</div>
	<table border="0" cellspacing="0" cellpadding="3" class="logbody">
		<form action="'.PHP_SELF.'?action=post" enctype="multipart/form-data" method=post>
		<tr>
			<td>
				<input type="file" name=d_img accept="image/jpeg,image/gif,image/png" size=30>
				<input type="submit" value="�ɲä���">
			</td>
		</tr>
		</form>
	</table>
	<div class="mainstate">�� �ե���������� .GIF��.JPG��.PNG �Ǥ��ꤤ���ޤ���</div>

	<table border="0" cellspacing="0" cellpadding="3" class="logbody">
		<form name=pict>
		<tr>
			<td>
				<div class="linktitle">�� ���åץ��ɤ���Ƥ���������� ��</div>
				| ����������
				<input type="radio" name="pins" value="mes" id="mes" checked><label for="mes">��ʸ</label>
				<input type="radio" name="pins" value="more" id="more"><label for="more">³��</label>
				 | �����β�����
				<input type="radio" name="pfloat" value="none" id="none" checked><label for="none">̵��</label>
				<input type="radio" name="pfloat" value="left" id="left"><label for="left">�����</label>
				<input type="radio" name="pfloat" value="right" id="right"><label for="right">�����</label> |
';

$loopcnt = 0;
if (count($picdir) == 0) {
	echo '��Ͽ����Ƥ�������Ϥ���ޤ���';
}else{
	echo '<table border="0" cellspacing="0" cellpadding="0"><tr>';
	$j = 1;
	reset($ftime);
	while(list($key, $val) = each($ftime)) {
		if ($j >= $page_st && $j < $page_ed) {
			echo '
				<td align="center" valign="center" style="border:solid 1px;width:90px;height:90px">
				<a href="javascript:pins('.$loopcnt.');">
				<img src="'.$picdir[$key].'" '.$picwh[$key].' style="border:0px;">
				</a>
				</td>
			';
			$loopcnt++;
//			if ($j >= $i) break;
		}
		$j++;
	}
	echo '</tr></form></table>';
}
$pcnt_start = $pcnt + 1;  		// ɽ�����ϰ���
$pcnt_end = $pcnt + $loopcnt;		// ɽ����λ����
$bpage = $page - 1;			// ���ڡ���
$npage = $page + 1;			// ���ڡ���
$imgcnt = $i;
if ($imgcnt != 0) {
	$mpage = ceil($imgcnt / 5);
}else{
	$mpage = 0;
}

if ($imgcnt != 0) {
	echo '<table width="450" border="0" cellspacing="0" cellpadding="0" class="logbody">';
	if ($page > 0 && $page < $mpage - 1) {
		echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
	}elseif($page > 0 && $page == $mpage - 1) { 
		echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
	}elseif($page == 0 && $page < $mpage - 1) { 
		echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
	}elseif($page == 0 && $page == $mpage - 1) { 
		echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
	}
	echo '</table><br>';
	echo '<table width="450" border="0" cellspacing="0" cellpadding="0" class="logbody">';
	echo '<tr><td align="center">Page: ';
	for ($i = 0; $i < $mpage; $i++) {
		$j = $i + 1;
		if ($i == $page) {
			echo '<a href='.PHP_SELF.'?page='.$i.'>['.$j.']</a> ';
		}else{
			echo '<a href='.PHP_SELF.'?page='.$i.'>'.$j.'</a> ';
		}
	}
	echo '</td></tr></table>';
}
	
echo '
			</td>
		</tr>
	</table>
<div align="center"><form><input type="button" value="�Ĥ���" onclick="window.close()"></form></div>
</div>
</body>
</html>
';

exit;


/* ����������å� */
function logincheck($loginid, $loginpw){
	if (file_exists(LOGDIR."init.dat")){
		$init = file(LOGDIR."init.dat");
		//$init������ԥ����ɺ��
		$init[0] = ereg_replace( "\n$", "", $init[0]);
		$init[0] = ereg_replace( "\r$", "", $init[0]);
		list($id,$pw) = explode("<>", $init[0]);
		if ($loginid == $id && $loginpw == $pw) {
			return true;
		}else{
			return false;
		}
	}else{
		echo '�ե����륪���ץ󥨥顼';
		exit;
	}
}

?>
