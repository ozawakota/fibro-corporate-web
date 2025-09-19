<?php
//------------------------------------------------------------------------
//
// ��������
//
// LAST UPDATE 2005/02/08
//
// ��IE����Ѥ�����硢����������˥֥饦����������λ�����������
// ���ݡ��ȣ����ʳ��Υ����Ȥ˥ȥ�å��Хå��Ǥ��ʤ��������
//
//------------------------------------------------------------------------
/* ===== ��ͭ�ե������ɤ߹��� */
require_once("common.php");

//ǧ��
$error = "";
if ($flg = @$loginid) {
	setcookie("blognid",$loginid, time() + 3600 * 24 * 7);		// �����֥��å�����¸
	if ($flg = @$alogin) {
		setcookie("blognpw",$loginpw, time() + 3600 * 24 * 7);	// �����֥��å�����¸
	}
}
session_start();
if (!$PHPSESSID) {
	session_register("blogni");
	session_register("blognp");
	$_SESSION['blogni'] = $_COOKIE['blognid'];
	if ($flg = $_COOKIE['blognpw']) {
		$_SESSION['blognp'] = md5($_COOKIE['blognpw']);
	}
}
if (!logincheck($loginid, md5($loginpw))) {
	if(!logincheck($_SESSION['blogni'], $_SESSION['blognp'])) {
		$error = "������桼����̾���ѥ���ɤ����Ϥ��Ƥ���������";
		loginform($error);
		exit;
	}
}



$upfile_name = @$_FILES["d_img"]["name"];
$upfile = @$_FILES["d_img"]["tmp_name"];
$htm_name = @$_FILES["s_htm"]["name"];
$css_name = @$_FILES["s_css"]["name"];
$uphtm = @$_FILES["s_htm"]["tmp_name"];
$upcss = @$_FILES["s_css"]["tmp_name"];
$qry_sid = @$_GET["sid"];
$qry_mode = @$_GET["mode"];
$qry_action = @$_GET["action"];
$qry_lid = @$_GET["lid"];
$qry_uid = @$_GET["uid"];
$qry_page = @$_GET["page"];
$qry_comment = @$_GET["comment"];
$qry_trackback = @$_GET["trackback"];
$qry_coid = @$_GET["coid"];
$qry_toid = @$_GET["toid"];
$qry_eid = @$_GET["eid"];
$qry_file = @$_GET["file"];
$qry_ip = @$_GET["ip"];
$qry_pos = @$_GET["pos"];
$qry_updown = @$_GET["updown"];

/* �Ʋ����ڤ��ؤ� */
	switch ($qry_mode) {
		case "log":			//��������ơ�����
			headhtml(1);
			contentshtml();
			if ($preview != NULL) $qry_action = "preview";
			blog($qry_action, $newpost, $d_eid, $d_year, $d_month, $d_day, $d_hour, $d_minutes, $d_second, $d_cid, $pid, $d_title, $d_mes, $d_more, $cok, $tok, $d_trackback, $upfile, $upfile_name, $ping_url);
			foothtml();
			break;
		case "list":			//�����ΰ����Խ����
			mes_list($qry_action, $qry_page, $qry_comment, $qry_trackback, $qry_eid, $qry_coid, $qry_toid);
			foothtml();
			break;
		case "file":		//�ե�����Υ��åץ���
			headhtml(0);
			contentshtml();
			file_edit($qry_action, $qry_page, $upfile, $upfile_name, $qry_file);
			foothtml();
			break;
		case "category":		//���ƥ�����Խ�
			headhtml(0);
			contentshtml();
			category($qry_action, $cid, $c_name, $qry_pos, $qry_updown);
			foothtml();
			break;
		case "link":			//��󥯤��Խ�
			headhtml(0);
			contentshtml();
			linklist($qry_action, $qry_lid, $s_name, $s_url, $qry_pos, $qry_updown);
			foothtml();
			break;
		case "ping":
			headhtml(0);
			contentshtml();
			pinglist($qry_action, $qry_uid, $u_name, $u_url);
			foothtml();
			break;
		case "profile": 		//�ץ�ե�������Խ�
			headhtml(0);
			contentshtml();
			profile($p_name, $p_email, $p_data, $p_oldimg, $p_delimg, $upfile, $upfile_name);
			foothtml();
			break;
		case "init":		// ������ID,PASS������
			headhtml(0);
			contentshtml();
			init_edit($newloginid, $newloginpw, $checkpw);
			foothtml();
			break;
		case "config":		// �֥��ν������
			headhtml(0);
			contentshtml();
			conf_edit($qry_action, $sitename, $sitedesc, $width, $height, $logcount, $arcount, $necount, $rccount, $rtcount, $imcount, $tz, $charset, $address, $cok_send, $tok_send, $comment_maxsize, $comment_maxtime, $trackback_type);
			foothtml();
			break;
		case "ip":					//������������
			headhtml(0);
			contentshtml();
			ip_check($qry_action, $deny_ip, $iid, $qry_ip);
			foothtml();
			break;
		case "mobile":			//��֥�����
			headhtml(0);
			contentshtml();
			mobile($qry_action, $receive, $send, $pop, $mobile_id, $mobile_pass, $from, $to, $access_time, $apop, $mobile_cok, $mobile_tok, $mobile_category);
			foothtml();
			break;
		case "logout":
			session_destroy();		// ���å������˴�
			setcookie("blognpw");	// ���å������˴�
			$error = "�������Ȥ��ޤ�����";
			loginform($error);
			exit;
		case "skinup":			//��������ɲ�
			headhtml(0);
			contentshtml();
			skinup($action, $page, $s_title, $uphtm, $upcss, $htm_name, $css_name, $qry_sid);
			foothtml();
			break;
		case "skinfile":		//�������Ѳ�������Ͽ
			headhtml(0);
			contentshtml();
			skinfile($qry_action, $qry_page, $upfile, $upfile_name, $qry_file);
			foothtml();
			break;
		case "skinlist":			//������Υ������ޥ���
			headhtml(0);
			contentshtml();
			skinlist($action, $htm_text, $css_text, $s_title, $qry_sid);
			foothtml();
			break;
		case "skinset":			//���ѥ��������Ͽ
			headhtml(0);
			contentshtml();
			skinset($action, $vid, $nskin, $rskin, $dskin, $sskin, $pskin, $iskin, $mskin, $cskin);
			foothtml();
			break;
		case "convert":			//�ǡ����ν���
			if ($action == "export") data_export();
			headhtml(0);
			contentshtml();
			data_convert();
			foothtml();
			break;
		default:						//TOP����ɽ��
			headhtml(0);
			contentshtml();
			up_mes($qry_info);
			top_view();
			foothtml();
			break;
	}
exit;


/* ��������� */
function loginform($error){
	headhtml(0);
	echo '
	<td width="10" background="./images/left.gif"></td>
	<td align="center" valign="center"><img src="./images/blank.gif" width="1" height="100">
	<p class="textlink">'.$error.'</p>
	<form action="'.PHP_SELF.'?mode=login" method=post>
	<table class="logbody">
		<tr align="left"><td>�桼����̾</td><td><input type="text" name=loginid value="'.$_COOKIE["blognid"].'" style="width:150px;"></td></tr>
		<tr align="left"><td>�ѥ����</td><td><input type="password" name=loginpw  style="width:150px;"></td></tr>
		<tr align="center"><td colspan="2"><input type="checkbox" name=alogin value="on" id="alogin"><label for="alogin">��ư�������ͭ���ˤ���</label></td></tr>
	</table>
	<br>
	<input type="submit" value="LOGIN">
	</form>
	<img src="./images/blank.gif" width="1" height="100">
	</td>
	';
	foothtml();
}


/* ����������å� */
function logincheck($loginid, $loginpw){
	if (file_exists(LOGDIR."init.dat")){
		$init = file(LOGDIR."init.dat");
		//$init������ԥ����ɺ��
		$init[0] = ereg_replace( "\n$", "", $init[0]);
		$init[0] = ereg_replace( "\r$", "", $init[0]);
		list($id,$pw) = explode("<>", $init[0]);
		if ($loginid == $id && $loginpw == $pw) {
			$_SESSION['blogni'] = $loginid;
			$_SESSION['blognp'] = $loginpw;
			return true;
		}else{
			return false;
		}
	}else{
		$error = "�ե����륪���ץ󥨥顼��init.dat<br>�ե�������ǧ���Ƥ���������";
		loginform($error);
		exit;
	}
}


/* html�ʥإå����� */
function headhtml($key) {
header("Content-Type: text/html; charset=EUC-JP");
echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<title>��������</title>
<meta http-equiv=content-type content="text/html; charset=EUC-JP">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<style type="text/css">
<!--
body {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	margin: 0px;
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

blockquote {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 11px;
	color: #333333;
	margin: 10px 10px 10px 10px;
	padding: 10px;
	border: 1px solid #CCCCCC;
}

.btn_font{
 font-size: 11px;
 border-width: 1px;
 border-style: solid;
 background-color: #FFFFFF;
}

.link_tag:link {
	text-decoration: none;
	color: #000000;
	font-size: 10px;
	border-width: 2px;
	border-style: outset;
	border-color: #CCCCCC;
	background-color: #CCCCCC;
	padding: 2px;
	margin: 1px;
}

.link_tag:visited {
	text-decoration: none;
	color: #000000;
	font-size: 10px;
	border-width: 2px;
	border-style: outset;
	border-color: #CCCCCC;
	background-color: #CCCCCC;
	padding: 2px;
	margin: 1px;
}

.link_tag:hover {
	text-decoration: none;
	color: #000000;
	font-size: 10px;
	border-width: 2px;
	border-style: outset;
	border-color: #CC3300;
	background-color: #CCCCCC;
	padding: 2px;
	margin: 1px;
}

.link_tag:active {
	text-decoration: none;
	color: #000000;
	font-size: 10px;
	border-width: 2px;
	border-style: inset;
	border-color: #CCCCCC;
	background-color: #CCCCCC;
	padding: 2px;
	margin: 1px;
}

.mainbody {
	margin: 0px 10px 0px 10px;
}

.maintitle {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 14px;
	font-weight: bolder;
	color: #414D7B;
	border-bottom: 1px dotted #CCCCCC;
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

.mainstate {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 10px;
	color: #999999;
}

.logtitle {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 12px;
	font-weight: bolder;
	color: #000000;
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
.linktext {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 10px;
	color: #999999;
	margin: 0px;
}

.inputarea {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 10px;
	color: #000000;
	margin: 0px;

}

.contentsbody {
	margin: 0px 10px 0px 10px;
}

.copyright {
	font-family: "Verdana", "Helvetica", "�ͣ� �����å�", "Osaka", "�ҥ饮�γѥ� Pro W3";
	font-size: 11px;
	color: #333333;
	text-align: right;
	margin: 0px 10px 0px 0px;
}
-->
</style>
';

if ($key == 1) {
	echo '
<script language="JavaScript">
<!--
var c_pc = navigator.userAgent.toLowerCase();
var c_ver = parseInt(navigator.appVersion);
var ie = ((c_pc.indexOf("msie") != -1) && (c_pc.indexOf("opera") == -1));
var win = ((c_pc.indexOf("win")!=-1) || (c_pc.indexOf("16bit") != -1));

optags = new Array("<span style=\"font-size:7px\">","<span style=\"font-size:9px\">","<span style=\"font-size:12px\">","<span style=\"font-size:18px\">","<span style=\"font-size:24px\">","<b>","<i>","<u>","<s>","<div style=\"text-align:left\">","<div style=\"text-align:center\">","<div style=\"text-align:right\">","<span style=\"color:black\">","<span style=\"color:brown\">","<span style=\"color:red\">","<span style=\"color:orange\">","<span style=\"color:yellow\">","<span style=\"color:green\">","<span style=\"color:blue\">","<span style=\"color:violet\">","<span style=\"color:gray\">","<span style=\"color:white\">","<p>","<blockquote>");
cltags = new Array("<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/b>","<\/i>","<\/u>","<\/s>","<\/div>","<\/div>","<\/div>","<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/span>","<\/p>","<\/blockquote>");
linktags1 = new Array("<a href=\"","<a href=\"");
linktags2 = new Array("\" target=\"_blank\">","\">");
linktags3 = new Array("<\/a>","<\/a>");
ictags = new Array();
';

$icon = file(LOGDIR."icon.dat");
for ($i = 0; $i < 100; $i++) {
	$icon[$i] = ereg_replace( "\n$", "", $icon[$i] );
	$icon[$i] = ereg_replace( "\r$", "", $icon[$i] );
	list($i_pic, $i_data) = explode("<>", $icon[$i]);
	echo 'ictags['.$i.'] = "'.$i_data.'";
';
}

echo '
function iconview(v) {
	if (v == 0) {
		document.getElementById("MesIconOn").style.display = "block";
		document.getElementById("MesIconOff").style.display = "none";
		document.getElementById("MoreIconOn").style.display = "block";
	}else{
		document.getElementById("MesIconOn").style.display = "none";
		document.getElementById("MesIconOff").style.display = "block";
		document.getElementById("MoreIconOn").style.display = "none";
	}
}

function wopen_pict() {
	window.open("pict.php","WindowOpen1","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=480,height=350")
}

function linkins(tags,d) {
	if (d == 0) {
		var txt = document.post.d_mes;
	}else{
		var txt = document.post.d_more;
	}
	txt.focus();
	if (tags == 0) {
		msg = prompt("��󥯥��ɥ쥹���ϥե�����","http://");
	}else{
		msg = prompt("�᡼�륢�ɥ쥹���ϥե�����","mailto:");
	}
	if (!msg) return;
	t0 = false;
	if ((c_ver >= 4) && ie && win) {
		if (t0 = document.selection.createRange().text) {
			document.selection.createRange().text = linktags1[tags] + msg + linktags2[tags]+ t0 + linktags3[tags];
			t0 = "";
			txt.focus();
			return;
		}else{
			txt.focus();
			tag = document.selection.createRange();
			tag.text = linktags1[tags] + msg + linktags2[tags] + linktags3[tags];
			return;
		}
	} else if (txt.selectionEnd && (txt.selectionEnd - txt.selectionStart > 0)) {
		if (txt.selectionEnd == 1 || txt.selectionEnd == 2) txt.selectionEnd = txt.textLength;
		txt.value = (txt.value).substring(0,txt.selectionStart) + linktags1[tags] + msg + linktags2[tags] + (txt.value).substring(txt.selectionStart, txt.selectionEnd) + linktags3[tags] + (txt.value).substring(txt.selectionEnd, txt.textLength);
		txt.focus();
		return;
	} else if (txt.selectionStart) {
		txt.value = (txt.value).substring(0,txt.selectionStart) + linktags1[tags] + msg + linktags2[tags] + linktags3[tags] + (txt.value).substring(txt.selectionEnd, txt.textLength);
		return;
	}
	txt.value += linktags1[tags] + msg + linktags2[tags] + linktags3[tags];
	txt.focus();
}

function ins(tags,d) {
	if (d == 0) {
		var txt = document.post.d_mes;
	}else{
		var txt = document.post.d_more;
	}
	txt.focus();
	t0 = false;
	if ((c_ver >= 4) && ie && win) {
		if (t0 = document.selection.createRange().text) {
			document.selection.createRange().text = optags[tags] + t0 + cltags[tags];
			t0 = "";
			txt.focus();
			return;
		}else{
			txt.focus();
			tag = document.selection.createRange();
			if (msg = prompt("���ϥե�����","")) {
				tag.text = optags[tags] + msg + cltags[tags];
			}else{
				tag.text = optags[tags] + cltags[tags];
			}
			return;
		}
	} else if (txt.selectionEnd && (txt.selectionEnd - txt.selectionStart > 0)) {
		if (txt.selectionEnd == 1 || txt.selectionEnd == 2) txt.selectionEnd = txt.textLength;
		txt.value = (txt.value).substring(0,txt.selectionStart) + optags[tags] + (txt.value).substring(txt.selectionStart, txt.selectionEnd) + cltags[tags] + (txt.value).substring(txt.selectionEnd, txt.textLength);
		txt.focus();
		return;
	} else if (txt.selectionStart) {
		if (msg = prompt("���ϥե�����","")) {
			txt.value = (txt.value).substring(0,txt.selectionStart) + optags[tags] + msg + cltags[tags] + (txt.value).substring(txt.selectionEnd, txt.textLength);
		}else{
			txt.value = (txt.value).substring(0,txt.selectionStart) + optags[tags] + cltags[tags] + (txt.value).substring(txt.selectionEnd, txt.textLength);
		}
		return;
	}
	if (msg = prompt("���ϥե�����","")) {
		txt.value += optags[tags] + msg + cltags[tags];
	}else{
		txt.value += optags[tags] + cltags[tags];
	}
	txt.focus();
	pos(txt);
}

function icon(t1,d) {
	if (d == 0) {
		var txt = document.post.d_mes;
	}else{
		var txt = document.post.d_more;
	}
	if (document.selection) {
		txt.focus();
		sel = document.selection.createRange();
		sel.text = ictags[t1];
	} else if (txt.selectionStart) {
		txt.value = (txt.value).substring(0,txt.selectionStart) + ictags[t1] + (txt.value).substring(txt.selectionEnd, txt.textLength);
	}else{
		txt.value  += ictags[t1];
	}
	t1 = "";
	txt.focus();
	return;
}

function dnow(){
	now = new Date();
	document.post.d_year.selectedIndex=1;
	document.post.d_month.selectedIndex=now.getMonth();
	document.post.d_day.selectedIndex=now.getDate()-1;
	document.post.d_hour.selectedIndex=now.getHours();
	document.post.d_minutes.selectedIndex=now.getMinutes();
	document.post.d_second.selectedIndex=now.getSeconds();
}
//-->
</script>';
}elseif ($key == 2) {
	echo '
<script language="JavaScript">
<!--
function delcheck(ref) {
	if (confirm("������Ƥ�����Ǥ���")) {
		window.location.href=ref;
	}
}

//-->
</script>';
}

echo '
</head>
<body style="margin:0px;">
<a name="top"></a>
<table width="720" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="10" background="./images/left.gif"><img src="./images/blank.gif" width="1" height="50"></td>
<td width="700" height="50" background="./images/admin.jpg" align="right" valign="top"><span class="copyright"></span></td>
<td width="10" background="./images/right.gif"><img src="./images/blank.gif" width="1" height="50"></td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="top">
';
}

/* html�ʥեå����� */
function foothtml() {
echo '
<td width="10" background="./images/right.gif"><img src="./images/blank.gif" width="1" height="150"></td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0" align="center">
<tr valign="top">
<td width="10" background="./images/left.gif"><img src="./images/blank.gif" width="1" height="100"></td>
<td width="700">
<div class="copyright">@Next Style BLOG Version 1.0.0</div>
</td>
<td width="10" background="./images/right.gif"><img src="./images/blank.gif" width="1" height="100"></td>
</tr>
</table>
</body>
</html>
';
}


/* html�ʥ���ƥ�ġ� */
function contentshtml() {
echo '
<td width="10" background="./images/left.gif"><img src="./images/blank.gif" width="1" height="150"></td>
<td width="140">
<br>
<div class="contentsbody">
<div class="linktitle">�� �Խ���˥塼 ��</div>
<div class="linktext">
<hr>
<a href="'.PHP_SELF.'?mode=top">[TOP]</a><br>
<a href="./index.php" target="_blank">[�֥��򸫤�]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=log">[�����ο������]</a><br>
<a href="'.PHP_SELF.'?mode=list">[�������Խ����]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=file">[�����Υ��åץ���]</a><br>
<a href="'.PHP_SELF.'?mode=category">[���ƥ���δ���]</a><br>
<a href="'.PHP_SELF.'?mode=ping">[Ping������δ���]</a><br>
<a href="'.PHP_SELF.'?mode=link">[��󥯤δ���]</a><br>
<a href="'.PHP_SELF.'?mode=profile">[�ץ�ե�����δ���]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=skinup">[��������ɲ��Խ�]</a><br>
<a href="'.PHP_SELF.'?mode=skinfile">[�������Ѳ�������Ͽ]</a><br>
<a href="'.PHP_SELF.'?mode=skinset">[���ѥ��������Ͽ]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=convert">[�ǡ�������]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=init">[�����������]</a><br>
<a href="'.PHP_SELF.'?mode=config">[�֥��ν������]</a><br>
<a href="'.PHP_SELF.'?mode=mobile">[��Х�����������]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=ip">[������������]</a><br>
<hr>
<a href="'.PHP_SELF.'?mode=logout">[��������]</a><br>
<hr>
</div></div>
</td>
';
}


/* ����ե��᡼��������ɽ�� */
function up_mes($inform){
	echo '<td width="560">';
	if (trim($inform) != "") {
		echo '<br><table width="550" border="0"  cellspacing="0" cellpadding="0">
		<tr><td>
		<div class="mainbody">
		<div class="maininfo">'.$inform.'</div>
		</div>
		</td></tr>
		</table>';
	}
}


/* TOP����ɽ�� */
function top_view(){
	echo '<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">TOP</div>
	';
	if($safe = ini_get('safe_mode')) {
		echo '
		<br>
		<div class="logbody">�����ѤΥ����С��ϥ����ե⡼�ɤ�ͭ���ˤʤäƤ��ޤ���<br>
		<br>
		�������ե⡼�ɤ�ͭ���ˤʤäƤ��륵���С��Ǥϰ����ε�ǽ�����¤���ޤ���<br>
		<br>
		��/log�ǥ��쥯�ȥ�ʲ��˼�ư��ǯ�ǥ��쥯�ȥ��������ʤ���Фʤ�ʤ���<br>
		����������ǯ�ǥ��쥯�ȥ�Υѡ��ߥå����򣷣����ʣ������ˤ��ѹ����ʤ���Фʤ�ʤ���
		</div>
		';
	}
	echo '
	</div>
	</td></tr></table>
	</td>';
}


/* �������̥��������� */
function init_edit($loginid, $loginpw, $checkpw){
	if (file_exists(LOGDIR."init.dat")){
//		$oldinit = file(LOGDIR."init.dat");
		if ($loginid) {
			if (!CheckIDPW($loginid) || !CheckIDPW($loginpw)) {
				$inform = "ID�ڤ�PASS�ˤ�Ⱦ�ѱѿ�������Ѥ���������";
			}else{
				if ($loginpw != $checkpw) {
					$inform = "������PASS�ȳ�ǧ��PASS��Ʊ���ǤϤ���ޤ���<br>�⤦�������Ϥ��Ƥ���������";
				}else{
					$_SESSION["blognp"] = $loginpw;
					$newinit = $loginid."<>".md5($loginpw)."\r\n";
					$fp = fopen(LOGDIR."init.dat", "w");
					flock($fp, LOCK_EX);
					fputs($fp, $newinit);
					fclose($fp);
					$inform = "������ID,PASS�򹹿����ޤ�����";
				}
			}
		}
	}else{
		$inform = "�ե����륪���ץ󥨥顼��init.dat<br>�ե�������ǧ���Ƥ���������";
	}
	up_mes($inform);
	if (file_exists(LOGDIR."init.dat")){
		$init = file(LOGDIR."init.dat");
		//$init������ԥ����ɺ��
		$init[0] = ereg_replace( "\n$", "", $init[0]);
		$init[0] = ereg_replace( "\r$", "", $init[0]);
		list($loginid,$loginpw) = explode("<>", $init[0]);
	}
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�����������</div><br>

	<table border="0" cellspacing="0" cellpadding="0" class="logbody">
	<form action="'.PHP_SELF.'?mode=init" method=post>
	<tr><td>
	<table border="1" cellspacing="0" cellpadding="0" class="logbody">
	<tr>
	<td width="150" bgcolor="#82BE7D"><label for=loginid>������ID</label></td>
	<td width="250"><input type="text" id=loginid name=newloginid size=10 value="'.$loginid.'"></td>
	</tr>
	<tr>
	<td width="150" bgcolor="#82BE7D"><label for=loginpw>������PASS</label></td>
	<td width="250"><input type="password" id=loginpw name=newloginpw size=10 value="'.$_SESSION["blognp"].'"></td>
	</tr>
	<tr>
	<td width="150" bgcolor="#82BE7D"><label for=checkpw>��ǧ�ѡʺ����ϡ�</label></td>
	<td width="250"><input type="password" id=checkpw name=checkpw size=8></td>
	</tr>
	</table>
	</td></tr>
	<tr><td align="right">
	<input type="submit" value="��������">
	</td></tr>
	</form>
	</table>
	</div>
	</td></tr>
	</table>
	</td>
	';
}


function data_convert() {
	echo '
	<td>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�ǡ�������</div><br>
<form action="./admin.php?mode=convert&action=export" method="post">
<div class="logbody">���Υ������ݡ��� ��Movable Type�����ʰ�����ĥ��<input type="submit" value="���Ϥ���"></div>
</form>



	</td></tr>
	</table>
	</div>
	</td>
	';
}


function data_export() {
	$convdata = "";
	if ($loglist = LogFileList(0)){
		sort($loglist);	// �����ե�����̾�򾺽祽���ȡʸŤ�����
		while(list ($key, $val) = each($loglist)) {
			$log = file(LOGDIR.substr($val,3,4)."/".$val);
			krsort($log);	// ���̥��ե�����Υǡ����򥭡��ǹ߽祽���ȡʸŤ����ս��
			while (list($logkey, $logval) = each($log)) {
				$logval = preg_replace( "/\n$/", "", $logval );
				$logval = preg_replace( "/\r$/", "", $logval );
				list($eid, $d_date, $d_time, $cid, $pid, $d_title, $d_mes, $d_more, $d_cok, $d_tok) = explode("<>", $logval);

				$convdata .= "TITLE: ".$d_title."\n";
				$p_name = profile_call($pid);
				$convdata .= "AUTHOR: ".$p_name."\n";
				$convdata .= "DATE: ".substr($d_date,4,2)."/".substr($d_date,6,2)."/".substr($d_date,0,4)." ".substr($d_time,0,2).":".substr($d_time,2,2).":".substr($d_time,4,2)."\n";
				$c_name = category_call($cid);
				$convdata .= "CATEGORY: ".$c_name."\n";
				if ($d_cok != "1") {
					$convdata .= "ALLOW COMMENTS: 1\n";
				}
				if ($d_tok != "1") {
					$convdata .= "ALLOW PINGS: 1\n";
				}
				$convdata .= "-----\n";
				$d_mes = tagreplaceStr($d_mes);
				$d_mes = str_replace("<br />", "\n", $d_mes);
				$d_mes = str_replace("<br>", "\n", $d_mes);
				$convdata .= "BODY:\n".$d_mes."\n";
				$convdata .= "-----\n";
				$d_more = tagreplaceStr($d_more);
				$d_more = str_replace("<br />", "\n", $d_more);
				$d_more = str_replace("<br>", "\n", $d_more);
				$convdata .= "EXTENDED BODY:\n";
				if ($d_more) $convdata .= $d_more."\n";
				$convdata .= "-----\n";
				$convdata .= "EXCERPT:\n";
				$convdata .= "-----\n";
				$convdata .= "KEYWORDS:\n";
				$convdata .= "-----\n";

				if ($cmtlist = IDCheck($eid, 1)) {
					@sort($cmtlist);
					for ($i = 0; $i < count($cmtlist); $i++ ) {
						$cmt = file(LOGDIR.substr($cmtlist[$i], 3, 4)."/".$cmtlist[$i]);
						krsort($cmt);
						while (list($cmtkey, $cmtval) = each($cmt)) {
							$cmtval = preg_replace( "/\n$/", "", $cmtval );
							$cmtval = preg_replace( "/\r$/", "", $cmtval );
							list($c_eid, $c_date, $c_time, $c_name, $c_email, $c_mes, $c_ip_addr, $c_user_agent, $c_url) = explode("<>", $cmtval);
							if ($eid == $c_eid) {
								$convdata .= "COMMENT:\n";
								$convdata .= "AUTHOR: ".$c_name."\n";
								$convdata .= "DATE: ".substr($c_date,4,2)."/".substr($c_date,6,2)."/".substr($c_date,0,4)." ".substr($c_time,0,2).":".substr($c_time,2,2).":00\n";
								if ($c_ip_addr) $convdata .= "IP: ".$c_ip_addr."\n";
								if ($c_user_agent) $convdata .= "AGENT: ".$c_user_agent."\n";
								if ($c_email) $convdata .= "EMAIL: ".$c_email."\n";
								if ($c_url) $convdata .= "URL: ".$c_url."\n";
								$c_mes = str_replace("<br />", "\n", $c_mes);
								$c_mes = str_replace("<br>", "\n", $c_mes);
								$convdata .= $c_mes."\n";
								$convdata .= "-----\n";
							}
						}
					}
				}
				if ($trklist = IDCheck($eid, 2)) {
					@sort($trklist);
					for ($i = 0; $i < count($trklist); $i++ ) {
						$trk = file(LOGDIR.substr($trklist[$i], 3, 4)."/".$trklist[$i]);
						krsort($trk);
						while (list($trkkey, $trkval) = each($trk)) {
							$trkval = preg_replace( "/\n$/", "", $trkval );
							$trkval = preg_replace( "/\r$/", "", $trkval );
							list($t_eid, $blog_name, $t_title, $t_url, $t_excerpt, $t_date, $t_time, $t_ip_addr, $t_user_agent ) = explode("<>", $trkval);
							if ($eid == $t_eid) {
								$convdata .= "PING:\n";
								$convdata .= "TITLE: ".$t_title."\n";
								$convdata .= "URL: ".$t_url."\n";
								if ($t_ip_addr) $convdata .= "IP: ".$t_ip_addr."\n";
								$convdata .= "BLOG NAME: ".$blog_name."\n";
								$convdata .= "DATE: ".substr($t_date,4,2)."/".substr($t_date,6,2)."/".substr($t_date,0,4)." ".substr($t_time,0,2).":".substr($t_time,2,2).":00\n";
								$t_excerpt = str_replace("<br />", "\n", $t_excerpt);
								$t_excerpt = str_replace("<br>", "\n", $t_excerpt);
								$convdata .= $t_excerpt."\n";
								$convdata .= "-----\n";
							}
						}
					}
				}
			$convdata .= "--------\n";
			}
		}
	}
	header("Content-type: text/plain name=log_mt_mode.txt"); 
	header("Content-Disposition: attachment; filename=log_mt_mode.txt"); 

	echo $convdata;
	exit;
}


/* ----- �ץ�ե�����͡������ ----- */
function profile_call($p_id){
	if (file_exists(LOGDIR."profile.dat")){
		$profile = file(LOGDIR."profile.dat");
		//$profile������ԥ����ɺ��
		for ( $k = 0; $k < count( $profile ); $k++ ) {
			$profile[$k] = preg_replace( "/\n$/", "", $profile[$k] );
			$profile[$k] = preg_replace( "/\r$/", "", $profile[$k] );
			list($pid, $p_name, $p_data, $p_img) = explode("<>", $profile[$k]);
			if ($p_id == $pid) {
				$p_data = $p_name;
				break;
			}else{
				$p_data = -1;
			}
		}
  }else{
		$p_data = -1;
	}
	return $p_data;
}


/* ----- ���ƥ��꡼���� ----- */
function category_call($c_id){
	if (file_exists(LOGDIR."category.dat")){
		$category = file(LOGDIR."category.dat");
		//$category������ԥ����ɺ��
		if (count($category) != 0) {
			for ( $k = 0; $k < count( $category ); $k++ ) {
				$category[$k] = preg_replace( "/\n$/", "", $category[$k] );
				$category[$k] = preg_replace( "/\r$/", "", $category[$k] );
				list($cid, $c_name) = explode("<>", $category[$k]);
				if ($c_id == $cid) {
					$c_data = $c_name;
					break;
				}else{
					$c_data = "-";
			  }
			}
		}else{
			$c_data = "-";
		}
  }else{
		$c_data = "-";
	}
	return $c_data;
}


function conf_edit($action, $sitename, $sitedesc, $width, $height, $logcount, $arcount, $necount, $rccount, $rtcount, $imcount, $tz, $charset, $address, $cok_send, $tok_send, $comment_maxsize, $comment_maxtime, $trackback_type){
	if ($action == "set"){
		if (file_exists(LOGDIR."conf.dat")) {
			$oldconf = file(LOGDIR."conf.dat");
			$conf[0] = ereg_replace( "\n$", "", $conf[0]);
			$conf[0] = ereg_replace( "\r$", "", $conf[0]);
			list(,,,,,,,,,,,$old_charset,,,,,,) = explode("<>", $conf[0]);

			if (!$sitename) {
				$inform = "������̾����������Ƥ��ޤ���";
			}elseif (!$sitedesc) {
				$inform = "�����Ȥ���������������Ƥ��ޤ���";
			}elseif (!is_numeric($width) || !is_numeric($height)) {
				$inform = "ɽ��������������Ŭ�ڤǤϤ���ޤ���";
			}else{
				$sitename = CleanStr($sitename);
				$sitedesc = CleanStr($sitedesc);
				$newconf = $sitename."<>".$sitedesc."<>".$width."<>".$height."<>".$logcount."<>".$arcount."<>".$necount."<>".$rccount."<>".$rtcount."<>".$imcount."<>".$tz."<>".$charset."<>".$address."<>".$cok_send."<>".$tok_send."<>".$comment_maxsize."<>".$comment_maxtime."<>".$trackback_type."\r\n";

				$fp = fopen(LOGDIR."conf.dat", "w");
				flock($fp, LOCK_EX);
				fputs($fp, $newconf);
				fclose($fp);
				$inform = "�������򹹿����ޤ�����";
				if ($old_charset != $charset) {
					if ($charset == 0) {
						$convkey = 2;
					}elseif ($charset == 1) {
						$convkey = 1;
					}elseif ($charset == 2) {
						$convkey = 4;
					}
					if ($skin = file(LOGDIR."skin.dat")) {
						for ($i = 0; $i <count($skin); $i++) {
							$skin[$i] = ereg_replace( "\n$", "", $skin[$i] );
							$skin[$i] = ereg_replace( "\r$", "", $skin[$i] );
							list($sid, $title, $htm_name, $css_name) = explode("<>",$skin[$i]);

							if ($htm_skin = file(SKINDIR.$htm_name)) {
								$htm_text = implode('', $htm_skin);
								$htm_text = mbConv($htm_text,0,$convkey);
								if (unlink(SKINDIR.$htm_name)) {
									$fp = fopen(SKINDIR.$htm_name, "w");
									flock($fp, LOCK_EX);
									fputs($fp, $htm_text);
									fclose($fp);
								}
							}
							if ($css_skin = file(SKINDIR.$css_name)) {
								$css_text = implode('', $css_skin);
								$css_text = mbConv($css_text,0,$convkey);
								if (unlink(SKINDIR.$css_name)) {
									$fp = fopen(SKINDIR.$css_name, "w");
									flock($fp, LOCK_EX);
									fputs($fp, $css_text);
									fclose($fp);
								}
							}
						}
					}
				}
			}
		}else{
			$inform = "�ե����륪���ץ󥨥顼��conf.dat<br>�ե�������ǧ���Ƥ���������";
		}
	}
	up_mes($inform);
	
	if (file_exists(LOGDIR."conf.dat")){
		$conf = file(LOGDIR."conf.dat");
		//$init������ԥ����ɺ��
		$conf[0] = ereg_replace( "\n$", "", $conf[0]);
		$conf[0] = ereg_replace( "\r$", "", $conf[0]);
		list($sitename,$sitedesc,$width,$height,$logcount,$arcount,$necount,$rccount,$rtcount,$imcount,$tz,$charset, $address, $cok_send, $tok_send, $comment_maxsize, $comment_maxtime, $trackback_type) = explode("<>", $conf[0]);
	}
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�֥��ν������</div><br>
	<table border="0"  cellspacing="0" cellpadding="0" class="logbody">
	<form action="'.PHP_SELF.'?mode=config&action=set" method=post>
	<tr><td>
	<table border="1"  cellspacing="0" cellpadding="0" class="logbody">
	<tr>
	<td width="200" bgcolor="#82BE7D"><label for=sitename>������̾</label></td>
	<td width="300"><input type="text" id=sitename name=sitename size=40 value="'.$sitename.'"></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D"><label for=sitedesc>�����Ȥ�����</label></td>
	<td width="300"><input type="text" id=sitedesc name=sitedesc size=50 value="'.$sitedesc.'"></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D"><label for=width>ɽ������������ ��</label></td>
	<td width="300"><input type="text" id=width name=width size=8 value="'.$width.'"> pixel</td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D"><label for=height>ɽ������������ ��</label></td>
	<td width="300"><input type="text" id=height name=height size=8 value="'.$height.'"> pixel</td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">����ɽ�����</td>
	<td width="300"><select name=logcount>';
	for ($i = 1; $i <= 10; $i++) {
		if ($i == $logcount) {
			echo '<option value="'.$i.'" selected>'.$i.'��</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'��</option>';
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">���̵�������ɽ�����</td>
	<td width="300"><select name=arcount>';
	for ($i = 1; $i <= 10; $i++) {
		if ($i == $arcount) {
			echo '<option value="'.$i.'" selected>'.$i.'��</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'��</option>';
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">�ǿ���������ɽ�����</td>
	<td width="300"><select name=necount>';
	for ($i = 1; $i <= 10; $i++) {
		if ($i == $necount) {
			echo '<option value="'.$i.'" selected>'.$i.'��</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'��</option>';
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">�ǿ������Ȱ���ɽ�����</td>
	<td width="300"><select name=rccount>';
	for ($i = 1; $i <= 10; $i++) {
		if ($i == $rccount) {
			echo '<option value="'.$i.'" selected>'.$i.'��</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'��</option>';
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">�ǿ��ȥ�å��Хå�����ɽ�����</td>
	<td width="240"><select name=rtcount>';
	for ($i = 1; $i <= 10; $i++) {
		if ($i == $rtcount) {
			echo '<option value="'.$i.'" selected>'.$i.'��</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'��</option>';
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">���ӤǤ�ɽ�����</td>
	<td width="300"><select name=imcount>';
	for ($i = 1; $i <= 5; $i++) {
		if ($i == $imcount) {
			echo '<option value="'.$i.'" selected>'.$i.'��</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'��</option>';
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">�����ॾ���������</td>
	<td width="300"><select name=tz>';
	for ($i = -12; $i <= 13; $i++) {
		if ($i == $tz) {
			echo '<option value="'.$i.'" selected>GMT ';
			if ($i < 0) {
				echo $i.' Hours</option>';
			}elseif ($i == 0) {
				echo '</option>';
			}elseif ($i == 9) {
				echo '+'.$i.' Hours [�������塦����]</option>';
			}else{
				echo '+'.$i.' Hours</option>';
			}
		}else{
			echo '<option value="'.$i.'">GMT ';
			if ($i < 0) {
				echo $i.' Hours</option>';
			}elseif ($i == 0) {
				echo '</option>';
			}elseif ($i == 9) {
				echo '+'.$i.' Hours [�������塦����]</option>';
			}else{
				echo '+'.$i.' Hours</option>';
			}
		}
	}
	echo '
	</select></td>
	</tr>
	<tr>
	<td width="200" bgcolor="#82BE7D">����ʸ��������</td>
	<td width="300"><select name=charset>';
	if ($charset == 0) {
		echo '
			<option value="0" selected>Shift_JIS</option>
			<option value="1">EUC-JP</option>
			<option value="2">UTF-8</option>
		';
	}elseif ($charset == 1) {
		echo '
			<option value="0">Shift_JIS</option>
			<option value="1" selected>EUC-JP</option>
			<option value="2">UTF-8</option>
		';
	}elseif ($charset == 2) {
		echo '
			<option value="0">Shift_JIS</option>
			<option value="1">EUC-JP</option>
			<option value="2" selected>UTF-8</option>
		';
	}
	echo '
	</select></td>
	</tr>
  <tr>
	<td width="200" rowspan="3" bgcolor="#82BE7D">�᡼�����ε�ǽ<br>����Ͽ�����᡼�륢�ɥ쥹�����Ρ�</td>
	<td width="300">E-Mail:
	<input type="text" name=address size=30 value="'.$address.'">
	</td></tr>
	<tr>
	<td width="300">
	';
	if ($cok_send != 1) {
		echo '<input type="checkbox" name="cok_send" value=1 id="cok">';
	}else{
		echo '<input type="checkbox" name="cok_send" value=1 checked id="cok">';
	}
	echo '<label for=cok>�����Ȥ�����Х᡼���Ϣ��</label>
	</td></tr>
	<tr>
	<td width="300">
	';
	if ($tok_send != 1) {
		echo '<input type="checkbox" name="tok_send" value=1 id="tok">';
	}else{
		echo '<input type="checkbox" name="tok_send" value=1 checked id="tok">';
	}
	echo '<label for=tok>�ȥ�å��Хå�������Х᡼���Ϣ��</label>
	</td></tr>
	<tr><td rowspan="2" bgcolor="#82BE7D">�������������
	</td><td>����ʸ������Ⱦ�ѡ� <input type="text" name=comment_maxsize size=4 value="'.$comment_maxsize.'"> ʸ��
	</td></tr>
	<tr><td>Ϣ³������¡�ʬ�� <select name=comment_maxtime> 
	';
	$maxtime = array("����̵��","1ʬ","2ʬ","3ʬ","4ʬ","5ʬ"); 
	while(list($key,$val) = each($maxtime)) { 
		if ($comment_maxtime == $key) {
			echo '<option value="'.$key.'" selected>'.$val.'</option>';
		}else{
			echo '<option value="'.$key.'">'.$val.'</option>';
		}
	}
	echo '
	</select>
	</td></tr>
	<tr><td bgcolor="#82BE7D">�ȥ�å��Хå�����
</td>
	<td>
';
	if ($trackback_type != 1) {
		echo '<input type="checkbox" name="trackback_type" value=1 id="trackbacktype">';
	}else{
		echo '<input type="checkbox" name="trackback_type" value=1 checked id="trackbacktype">';
	}
	echo '<label for=trackbacktype>�ȥ�å��Хå�������ˡ��[��]��[��]���ѹ�</label>
<div class="mainstate">�� �ȥ�å��Хå��������Ǥ��ʤ����ϥ����å�������Ƥ���������</div>
	</td></tr>
	</table>
	</td></tr>
	<tr><td align="right">
	<input type="submit" value="��������">
	</form>
	</td></tr>
	</table>
	</div>
	</td></tr>
	</table>
	</div>
	</td>
	';
}


function CheckIDPW ($str){
	if (!ereg("[^[:alnum:]]",$str)) {
		return true;
	}else{
		return false;
	}
}


/* �ץ�ե�����ι������� */
function profile($p_name, $p_email, $p_data, $p_oldimg, $p_delimg, $upfile, $upfile_name){
	if ($p_name) {
		//�ƥ���������
		$p_name = CleanStr($p_name);
		$p_email = CleanStr($p_email);
		$p_data = CleanStr($p_data);
		// ����ʸ�������졣 
		$p_data = str_replace( "\r\n",  "\n", $p_data);
		$p_data = str_replace( "\r",  "\n", $p_data);
		$p_data = nl2br($p_data);						//����ʸ��������<br>����������
		$p_data = str_replace("\n",  "", $p_data);		//\n��ʸ���󤫤�ä���

		if(file_exists($upfile)){
			$p_delimg = "del";
		}
		if ($p_delimg == "del") {
			$dest = PICDIR.$p_oldimg;
			@unlink($dest);
			$p_oldimg = "";
		}
		if(file_exists($upfile)){
			if(ereg("[\xA1-\xFE]", $upfile_name)) {
				$pathname = pathinfo($dest);
				$upfile_name = gmdate("YmdHis",time() + TIMEZONE).$pathname['extension'];
			}
			$dest = PICDIR.$upfile_name;
			copy($upfile, $dest);
			if(!is_file($dest)) error("���åץ��ɤ˼��Ԥ��ޤ�����<br>�����Ф����ݡ��Ȥ��Ƥ��ʤ���ǽ��������ޤ�");
			$size = @getimagesize($dest);
			if($size[2]=="") error("���åץ��ɤ˼��Ԥ��ޤ�����<br>�����ե�����ʳ��ϼ����դ��ޤ���");
			$newprofile = "0<>".$p_name."<>".$p_email."<>".$p_data."<>".$upfile_name."\r\n";
		}else{
			$newprofile = "0<>".$p_name."<>".$p_email."<>".$p_data."<>".$p_oldimg."\r\n";
		}
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."profile.dat", "w");
		flock($fp, LOCK_EX);
		if (strlen($newprofile)){
			fputs($fp, $newprofile);
		}
		fclose($fp);
		$inform = "�ץ�ե�����򹹿����ޤ�����";
	}else{
		$inform = "";
	}
	up_mes($inform);
	if (file_exists(LOGDIR."profile.dat")){
		$profile = file(LOGDIR."profile.dat");
		//$profile������ԥ����ɺ��
		$profile[0] = ereg_replace( "\n$", "", $profile[0]);
		$profile[0] = ereg_replace( "\r$", "", $profile[0]);
		list($pid, $p_name, $p_email, $p_data, $p_img) = explode("<>", $profile[0]);
		//$p_data����<br>����ԥ����ɤ��ִ�
		$p_data = str_replace("<br />", "\n", $p_data);
  }else{
    $pid = 0;
    $p_name = "";
    $p_email = "";
    $p_data = "";
    $p_img = "";
	}
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�ץ�ե�����δ���</div><br>
	<div class="linktitle">�� �ץ�ե�������Խ� ��</div>
	<form action="'.PHP_SELF.'?mode=profile" enctype="multipart/form-data" method=post>
	<table border="0"  cellspacing="0" cellpadding="0" class="logbody">
	<tr><td width="50%" valign="top">
	<label for=p_name>�桼����̾</label><br>
	<input id=p_name name=p_name value="'.$p_name.'" style="width:200px;"><br>
	<label for=p_email>�᡼�륢�ɥ쥹</label><br>
	<input id=p_email name=p_email value="'.$p_email.'" style="width:200px;"><br>
	</td><td width="50%" valign="top">
	<label for=d_img>���Х����ե����륢�åץ���</label><br>
	<input type="file" name=d_img accept="image/jpeg,image/gif,image/png" size=30 style="width:200px;"><br>
	<input type="checkbox" name=p_delimg value="del" id="c1"><label for="c1">���Х�������</label><br>
	';
	if ($p_img != "") {
		echo '<img src="'.PICDIR.$p_img.'" alt="'.$p_img.'">
		<input type="hidden" name="p_oldimg" value="'.$p_img.'">';
	}else{
		echo 'NO AVATAR
		<input type="hidden" name="p_oldimg" value="">';
	}
	echo'
	</td></tr>
	<tr><td colspan="2">
	<label for=p_data>�桼����������</label><br>
	<textarea id=p_data style="WIDTH: 500px;font-size: 12px;" name=p_data rows=10 cols=50>'.$p_data.'</textarea><br><br>
	</td></tr>
	<tr><td colspan="2" align="right">
	<input type="submit" value="��������">
	</td></tr>
	</table>
	</form>
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* ��󥯴�������ɽ�� */
function linklist($action, $lid, $s_name, $s_url, $pos, $updown){
	if ($action == "cpost") {		// ��󥯥��ƥ�����ɲ�
		if (file_exists(LOGDIR."link.dat")){
			$oldlink = file(LOGDIR."link.dat");
			$lid = count($oldlink);
		}else{
			$oldlink = "";
			$lid = 0;
		}
		//�ƥ���������
		$s_name = CleanStr($s_name);
		$newlink = $s_name."<><>1\r\n";
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."link.dat", "w");
		flock($fp, LOCK_EX);
		if (strlen($newlink)){
			fputs($fp, $newlink);
		}
		fputs($fp, implode('', $oldlink));
		fclose($fp);
		$inform = "��󥯥����Ȥ��ɲä��ޤ�����";
	}elseif ($action == "post") {		// ��󥯥����Ȥ��ɲ�
		if (file_exists(LOGDIR."link.dat")){
			$oldlink = file(LOGDIR."link.dat");
			$lid = count($oldlink);
		}else{
			$oldlink = "";
			$lid = 0;
		}
		//�ƥ���������
		$s_name = CleanStr($s_name);
		$s_url = CleanStr($s_url);
		$newlink = $s_name."<>".$s_url."<>0\r\n";
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."link.dat", "w");
		flock($fp, LOCK_EX);
		if (strlen($newlink)){
			fputs($fp, $newlink);
		}
		fputs($fp, implode('', $oldlink));
		fclose($fp);
		$inform = "��󥯥����Ȥ��ɲä��ޤ�����";
	}elseif ($action == "edit") {		// ��󥯤ι���
		$linklist = file(LOGDIR."link.dat");
		list($old_name, $old_url, $old_category) = explode("<>",$linklist[$lid]);
		$old_category = ereg_replace( "\n$", "", $old_category);
		$old_category = ereg_replace( "\r$", "", $old_category);
		//�ƥ���������
		$s_name = CleanStr($s_name);
		if ($old_category != 1) {
			$s_url = CleanStr($s_url);
			$linklist[$lid] = $s_name."<>".$s_url."<>0\r\n";
		}else{
			$linklist[$lid] = $s_name."<><>1\r\n";
		}
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."link.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $linklist));
		fclose($fp);
		$inform = "��󥯤򹹿����ޤ�����";
	}elseif ($action == "delete") {		// ��󥯤κ��
		$linklist = file(LOGDIR."link.dat");
		array_splice($linklist, $lid, 1);
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."link.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $linklist));
		fclose($fp);
		$inform = "��󥯤������ޤ�����";
	//------------------------------------------------------------------
	}elseif ($action == "change") {
		$linklist = file(LOGDIR."link.dat");
		if ($updown == "up") {
			$tmplink1 = $linklist[$pos];
			$tmplink2 = $linklist[$pos - 1];
			$linklist[$pos] = $tmplink2;
			$linklist[$pos - 1] = $tmplink1;
		}else{
			$tmplink1 = $linklist[$pos];
			$tmplink2 = $linklist[$pos + 1];
			$linklist[$pos] = $tmplink2;
			$linklist[$pos + 1] = $tmplink1;
		}
		$fp = fopen(LOGDIR."link.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $linklist));
		fclose($fp);
		$inform = "��󥯤ν��֤��ѹ����ޤ�����";
	//------------------------------------------------------------------
	}else{
		$inform = "";
	}
	up_mes($inform);
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">��󥯤δ���</div><br>
	<div class="linktitle">�� ��󥯥��ƥ����ɲ� ��</div>
	<table border="0" cellspacing="0" cellpadding="0" class="logbody">
	<form action="'.PHP_SELF.'?mode=link&action=cpost" method=post>
	<tr><td>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr><td width=100 align="center" bgcolor="#82BE7D"><label for=s_name>���ƥ���̾</label></td><td width="400"><input id=s_name tabIndex=1 name=s_name size=60 style="width:390px;"></td></tr>
	</table>
	</td></tr>
	<tr><td align="right">
  <input type="submit" value="�ɲä���">
	</td></tr>
  </form>
	</table><br>
	<div class="linktitle">�� ��󥯥������ɲ� ��</div>
	<table border="0" cellspacing="0" cellpadding="0" class="logbody">
	<form action="'.PHP_SELF.'?mode=link&action=post" method=post>
	<tr><td>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr><td width=100 align="center" bgcolor="#82BE7D"><label for=s_name>������̾</label></td><td width="400"><input id=s_name tabIndex=1 name=s_name size=60 style="width:390px;"></td></tr>
	<tr><td width=100 align="center" bgcolor="#82BE7D"><label for=s_url>URL</label></td><td width="400"><input id=s_url tabIndex=2 name=s_url size=60 style="width:390px;"></td></tr>
	</table>
	</td></tr>
	<tr><td align="right">
	<input type="submit" value="�ɲä���">
	</td></tr>
	</form>
	</table><br>
	<div class="linktitle">�� ��Ͽ������ ��</div>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr align="center" bgcolor="#82BE7D"><td width="200" colspan="2">������̾</td><td width="150">URL</td><td width="50">�ѹ�</td><td width="50">���</td><td colspan="2" width="50">�¤��ؤ�</td></tr>
	';
  if (file_exists(LOGDIR."link.dat")){
    $link = file(LOGDIR."link.dat");
    //$link������ԥ����ɺ��
		if (count($link) == 0) {
			echo '<tr><td colspan="7" align="center">��󥯤���Ͽ����Ƥ��ޤ���</td></tr>';
		}else{
			for ( $i = 0; $i < count( $link ); $i++ ) {
				$link[$i] = ereg_replace( "\n$", "", $link[$i] );
				$link[$i] = ereg_replace( "\r$", "", $link[$i] );
				list($l_name, $l_url, $l_category) = explode("<>", $link[$i]);
				echo '<form action="'.PHP_SELF.'?mode=link&action=edit&lid='.$i.'" method=post>';
				if ($l_category != 1) {
					echo '<tr><td>+</td><td><input id=s_name name=s_name size=18 value="'.$l_name.'" style="width:140px;"></td><td><input id=s_url name=s_url size=28 value="'.$l_url.'" style="width:140px;"></td><td class="entry_body" align ="center"><input type="image" src="./images/update.gif" name="submit" alt="����"></td><td align ="center"><a href="'.PHP_SELF.'?mode=link&action=delete&lid='.$i.'"><img src="./images/trash.gif" border="0"></a></td>';
				}else{
					echo '<tr><td colspan="3" bgcolor="#cccccc">���ƥ���̾ <input id=s_name name=s_name size=18 value="'.$l_name.'" style="width:260px;"></td><td class="entry_body" align ="center"><input type="image" src="./images/update.gif" name="submit" alt="����"></td><td align ="center"><a href="'.PHP_SELF.'?mode=link&action=delete&lid='.$i.'"><img src="./images/trash.gif" border="0"></a></td>';
				}
				if ($i == 0 && $i == count($link) - 1) {
					echo '<td align="center" width="25">��</td><td align="center" width="25">��</td>';
				}elseif ($i == 0) {
					echo '<td align="center" width="25">��</td><td align="center" width="25"><a href="'.PHP_SELF.'?mode=link&action=change&pos='.$i.'&updown=down"><img src="./images/down.gif" width="20" height="20" border="0"></a></td>';
				}elseif ($i == count($link) - 1) {
					echo '<td align="center" width="25"><a href="'.PHP_SELF.'?mode=link&action=change&pos='.$i.'&updown=up"><img src="./images/up.gif" width="20" height="20" border="0"></a></td><td align="center" width="25">��</td>';
				}else{
					echo '<td align="center" width="25"><a href="'.PHP_SELF.'?mode=link&action=change&pos='.$i.'&updown=up"><img src="./images/up.gif" width="20" height="20" border="0"></a></td><td align="center" width="25"><a href="'.PHP_SELF.'?mode=link&action=change&pos='.$i.'&updown=down"><img src="./images/down.gif" width="20" height="20" border="0"></a></td>';
				}
				echo '</tr></form>';
			}
		}
	}
	echo '
	</table>
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* Ping�������������ɽ�� */
function pinglist($action, $uid, $u_name, $u_url){
	if ($action == "post") {		// �ɲ�
		if (file_exists(LOGDIR."ping.dat")){
			$oldping = file(LOGDIR."ping.dat");
			$uid = count($oldping);
		}else{
			$olduid = "";
			$uid = 0;
		}
		//�ƥ���������
		$u_name = CleanStr($u_name);
		$u_url = CleanStr($u_url);
		$newping = $u_name."<>".$u_url."\r\n";
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."ping.dat", "w");
		flock($fp, LOCK_EX);
		if (strlen($newping)){
			fputs($fp, $newping);
		}
		fputs($fp, implode('', $oldping));
		fclose($fp);
		$inform = "Ping��������ɲä��ޤ�����";
	}elseif ($action == "edit") {		// ����
		$pinglist = file(LOGDIR."ping.dat");
		//�ƥ���������
		$u_name = CleanStr($u_name);
		$u_url = CleanStr($u_url);
		$pinglist[$uid] = $u_name."<>".$u_url."\r\n";
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."ping.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $pinglist));
		fclose($fp);
		$inform = "Ping������򹹿����ޤ�����";
	}elseif ($action == "delete") {		// ���
		$pinglist = file(LOGDIR."ping.dat");
		array_splice($pinglist, $uid, 1);
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."ping.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $pinglist));
		fclose($fp);
		$inform = "Ping������������ޤ�����";
	}else{
		$inform = "";
	}
	up_mes($inform);
  echo '
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr><td>
  <div class="mainbody">
  <div class="maintitle">Ping������δ���</div><br>
  <div class="linktitle">�� Ping�������ɲ� ��</div>
  <table border="0" cellspacing="0" cellpadding="0" class="logbody">
  <form action="'.PHP_SELF.'?mode=ping&action=post" method=post>
	<tr><td>
  <table border="1" cellspacing="0" cellpadding="5" class="logbody">
    <tr><td width=100 align="center" bgcolor="#82BE7D"><label for=u_name>������̾</label></td><td width="400"><input id=u_name tabIndex=1 name=u_name size=60 style="width:390px;"></td></tr>
    <tr><td width=100 align="center" bgcolor="#82BE7D"><label for=u_url>URL</label></td><td width="400"><input id=u_url tabIndex=2 name=u_url size=60 style="width:390px;"></td></tr>
  </table>
	</td></tr>
	<tr><td align="right">
  <input type="submit" value="�ɲä���">
	</td></tr>
  </form>
	</table>
	<br>
  <div class="linktitle">�� ��Ͽ������ ��</div>
  <table border="1" cellspacing="0" cellpadding="5" class="logbody">
  <tr align="center" bgcolor="#82BE7D"><td width="170">������̾</td><td width="230">URL</td><td width="50">�ѹ�</td><td width="50">���</td></tr>
  ';
  if (file_exists(LOGDIR."ping.dat")){
    $ping = file(LOGDIR."ping.dat");
    //$ping������ԥ����ɺ��
		if (count($ping) == 0) {
			echo '<tr><td colspan="4" align="center">Ping���������Ͽ����Ƥ��ޤ���</td></tr>';
		}else{
			for ( $i = 0; $i < count( $ping ); $i++ ) {
				$ping[$i] = ereg_replace( "\n$", "", $ping[$i] );
				$ping[$i] = ereg_replace( "\r$", "", $ping[$i] );
				list($u_name, $u_url) = explode("<>", $ping[$i]);
				echo '
				<form action="'.PHP_SELF.'?mode=ping&action=edit&uid='.$i.'" method=post>
				<tr><td><input name=u_name size="22" value="'.$u_name.'" style="width:160px;"></td><td><input name=u_url size="30" value="'.$u_url.'" style="width:220px;"></td><td class="entry_body" align ="center"><input type="image" src="./images/update.gif" name="submit" alt="����"></td><td align ="center"><a href="'.PHP_SELF.'?mode=ping&action=delete&uid='.$i.'"><img src="./images/trash.gif" border="0"></a></td></tr>
				</form>
				';
			}
		}
	}
	echo '
	</table>
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* ���ƥ����������ɽ�� */
function category($action, $cid, $c_name, $pos, $updown){
	//------------------------------------------------------------------
	if ($action == "post") {
		if (file_exists(LOGDIR."category.dat")){
			$oldcategory = file(LOGDIR."category.dat");
			$cid = count($oldcategory);
		}else{
			$oldcategory = "";
			$cid = 0;
		}
		//�ƥ���������
		$c_name = CleanStr($c_name);
		$newcategory = $cid."<>".$c_name."\r\n";
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."category.dat", "w");
		flock($fp, LOCK_EX);
		if ($oldcategory != 0) fputs($fp, implode('', $oldcategory));
		fputs($fp, $newcategory);
		fclose($fp);
		$inform = "���ƥ�����ɲä��ޤ�����";
	//------------------------------------------------------------------
	}elseif ($action == "edit") {
		$category = file(LOGDIR."category.dat");
		//�ƥ���������
		$c_name = CleanStr($c_name);

		for ( $i = 0; $i < count( $category ); $i++ ) {
			//$category������ԥ����ɺ��
			$tmp = ereg_replace( "\n$", "", $category[$i] );
			$tmp = ereg_replace( "\r$", "", $tmp);
			list($old_cid, $old_name) = explode("<>", $tmp);
			if ($cid == $old_cid) {
				$category[$i] = $cid."<>".$c_name."\r\n";
				break;
			}
		}
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."category.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $category));
		fclose($fp);
		$inform = "���ƥ���򹹿����ޤ�����";
	//------------------------------------------------------------------
	}elseif ($action == "change") {
		$category = file(LOGDIR."category.dat");
		if ($updown == "up") {
			$tmpcategory1 = $category[$pos];
			$tmpcategory2 = $category[$pos - 1];
			$category[$pos] = $tmpcategory2;
			$category[$pos - 1] = $tmpcategory1;
		}else{
			$tmpcategory1 = $category[$pos];
			$tmpcategory2 = $category[$pos + 1];
			$category[$pos] = $tmpcategory2;
			$category[$pos + 1] = $tmpcategory1;
		}
		$fp = fopen(LOGDIR."category.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $category));
		fclose($fp);
		$inform = "���ƥ���ν��֤��ѹ����ޤ�����";
	//------------------------------------------------------------------
	}else{
		$inform = "";
	}
	up_mes($inform);
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">���ƥ���δ���</div><br>
	<div class="linktitle">�� ���ƥ����ɲ� ��</div>
	<table border="0" cellspacing="0" cellpadding="0" class="logbody">
	<tr><td>
	<form action="'.PHP_SELF.'?mode=category&action=post" method=post>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr class="entry_body"><td width="100" align="center" bgcolor="#82BE7D"><label for=c_name>���ƥ���̾</label></td><td width="400"><input id=c_name tabIndex=1 name=c_name size=50 style="width:390px;"></td></tr>
	</table>
	</td></tr>
	<tr><td align="right">
	<input type="submit" value="�ɲä���">
	</td></tr>
	</form>
	</table>
	<br>
  <div class="linktitle">�� ��Ͽ���ƥ��� ��</div>
  <table border="1" cellspacing="0" cellpadding="5" class="logbody">
  <tr align="center" bgcolor="#82BE7D" class="entry_body"><td>���ƥ���̾</td><td width="50">�ѹ�</td><td colspan="2">�¤��ؤ�</td></tr>
  ';
	if (file_exists(LOGDIR."category.dat")){
		$category = file(LOGDIR."category.dat");
		if (count($category) == 0) {
			echo '<tr class="entry_body"><td colspan="4" align="center">��Ͽ����Ƥ��륫�ƥ��꡼����ޤ���</td></tr>';
		}else{
			for ( $i = 0; $i < count( $category ); $i++ ) {
				//$link������ԥ����ɺ��
				$category[$i] = ereg_replace( "\n$", "", $category[$i] );
				$category[$i] = ereg_replace( "\r$", "", $category[$i] );
				list($cid, $c_name) = explode("<>", $category[$i]);
				echo '
				<form action="'.PHP_SELF.'?mode=category&action=edit&cid='.$cid.'" method=post>
				<tr class="entry_body"><td width="400"><input id=c_name name=c_name size=50 value="'.$c_name.'" style="width:390px;"></td><td width="50" align ="center"><input type="image" src="./images/update.gif" name="submit" alt="����"></td>';
				if ($i == 0 && $i == count($category) - 1) {
					echo '<td align="center" width="25">��</td><td align="center" width="25">��</td>';
				}elseif ($i == 0) {
					echo '<td align="center" width="25">��</td><td align="center" width="25"><a href="'.PHP_SELF.'?mode=category&action=change&pos='.$i.'&updown=down"><img src="./images/down.gif" width="20" height="20" border="0"></a></td>';
				}elseif ($i == count($category) - 1) {
					echo '<td align="center" width="25"><a href="'.PHP_SELF.'?mode=category&action=change&pos='.$i.'&updown=up"><img src="./images/up.gif" width="20" height="20" border="0"></a></td><td align="center" width="25">��</td>';
				}else{
					echo '<td align="center" width="25"><a href="'.PHP_SELF.'?mode=category&action=change&pos='.$i.'&updown=up"><img src="./images/up.gif" width="20" height="20" border="0"></a></td><td align="center" width="25"><a href="'.PHP_SELF.'?mode=category&action=change&pos='.$i.'&updown=down"><img src="./images/down.gif" width="20" height="20" border="0"></a></td>';
				}
				echo '</tr></form>';
			}
		}
	}
	echo '
	</table>
	<div class="mainstate">
	�����ƥ��꼫�Τ������뤳�ȤϽ���ޤ���<br>
	�����ƥ���������������ϥ��ƥ���̾�� - �Ȥ��ƹ������Ƥ���������
	</div>
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* ----- ���������ϡ����� ----- */
function blog($action, $newpost, $d_eid, $d_year, $d_month, $d_day, $d_hour, $d_minutes, $d_second, $d_cid, $pid, $d_title, $d_mes, $d_more, $cok, $tok, $d_trackback, $upfile, $upfile_name, $ping_url) {
	$inform = "";
// -------------------------------------------------------------------
	if ($action == "post") {
		$d_date = sprintf("%4d%02d%02d", $d_year, $d_month, $d_day);
		$d_time = sprintf("%02d%02d%02d", $d_hour, $d_minutes, $d_second);
		// ���ե����å�
		if (!checkdate($d_month, $d_day, $d_year)) {
			$inform = "ǯ�������ϥߥ���<br>���ǯ�������ǧ���Ƥ���������";
		}else{
			//eid�����å�
			$oldid = file(LOGDIR."id.dat");
			$oldid[0] = ereg_replace( "\n$", "", $oldid[0] );
			$oldid[0] = ereg_replace( "\r$", "", $oldid[0] );
			$oldid[0]++;
			if ($d_eid != $oldid[0]) $d_eid = $oldid[0];
			// �ץ�ե�����ID����
			$pid = 0;
			//�ƥ���������
			$d_title  = CleanStr($d_title);
			$d_mes  = CleanStr($d_mes);
			$d_more  = CleanStr($d_more);
			// ����ʸ�������졣 
			$d_mes = rntobr($d_mes);
			$d_mes = str_replace( "&nbsp;", " ", $d_mes);		// &nbsp �򥹥ڡ������Ѵ�����
			$d_more = rntobr($d_more);
			$d_more = str_replace( "&nbsp;", " ", $d_more);		// &nbsp �򥹥ڡ������Ѵ�����
			$log = $d_eid."<>".$d_date."<>".$d_time."<>".$d_cid."<>".$pid."<>".$d_title."<>".$d_mes."<>".$d_more."<>".$cok."<>".$tok."\r\n";

			//���ե�����񤭹���
			$logname = "log".substr($d_date, 0, 6).".dat";
			$err = FileSearch($logname);
			if ($err == -1) {
				$inform = "�����ν񤭹��ߤ˼��Ԥ��ޤ�����<br>".LOGDIR."�ǥ��쥯�ȥ�Υѡ��ߥå������ǧ���Ƥ���������";
			}elseif ($err == 0) {
				$inform = "���Υ����С��ϥ����ե⡼�ɤǤ���<br>".LOGDIR."�ʲ���ǯ�ǥ��쥯�ȥ���ư�Ǻ������Ƥ���������";
			}elseif ($err == 1) {
				if ($newlog = @file(LOGDIR.substr($d_date, 0, 4)."/".$logname)) {
					$datetime = $d_date.$d_time;
					$newkey = DateSearch($datetime, $newlog);
					array_splice($newlog, $newkey, 0, $log);
					$fp = fopen(LOGDIR.substr($d_date, 0, 4)."/".$logname, "w");
					flock($fp, LOCK_EX);
					fputs($fp, implode('',$newlog));
					fclose($fp);
				}else{
					$fp = fopen(LOGDIR.substr($d_date, 0, 4)."/".$logname, "w");
					flock($fp, LOCK_EX);
					fputs($fp, $log);
					fclose($fp);
				}
				$inform .= "��������Ƥ��ޤ�����";

				//eid��¸
				if ($fp = fopen(LOGDIR."id.dat", "w")) {
					flock($fp, LOCK_EX);
					fputs($fp, $d_eid);
					fclose($fp);
				}else{
					$inform .= "<br>ID�򹹿�����ޤ���Ǥ�����";
				}
				//trackback����
				if (strlen($d_trackback) != 0) {
					$tb = trackback_send($d_trackback,HOMELINK."?eid=".$d_eid, $d_title, $d_mes);
					$inform .= $tb;
				}
				//ping����
				if (count($ping_url) != 0) {
					$pu = ping_send($ping_url);
					$inform .= $pu;
				}
			}
		}
		//$mes����<br>����ԥ����ɤ��ִ�
		$d_mes = str_replace("<br />", "\n", $d_mes);
		$d_mes = str_replace("<br>", "\n", $d_mes);
		$d_more = str_replace("<br />", "\n", $d_more);	// <----- v1.3.1 �ɲ�
		$d_more = str_replace("<br>", "\n", $d_more);		// <----- v1.3.1 �ɲ�
// -------------------------------------------------------------------
	}elseif ($action == "edit") {
		// ���ǡ����ɤ߹���
		$logfile = IDCheck($d_eid, 0);
		$log = file(LOGDIR.substr($logfile[0], 3, 4)."/".$logfile[0]);
		$tmplog = ereg_replace( "\n$", "", $log[IDSearch($d_eid, $log)] );
		$tmplog = ereg_replace( "\r$", "", $tmplog );
		list($d_eid, $d_date, $d_time, $d_cid, $pid, $d_title, $d_mes, $d_more, $cok, $tok) = explode("<>", $tmplog);

		//$mes����<br>����ԥ����ɤ��ִ�
		$d_mes = str_replace("<br />", "\n", $d_mes);
		$d_mes = str_replace("<br>", "\n", $d_mes);
		$d_more = str_replace("<br />", "\n", $d_more);	// <----- v1.3.1 �ɲ�
		$d_more = str_replace("<br>", "\n", $d_more);		// <----- v1.3.1 �ɲ�

// -------------------------------------------------------------------
	}elseif ($action == "update") {
		$d_date = sprintf("%4d%02d%02d", $d_year, $d_month, $d_day);
		$d_time = sprintf("%02d%02d%02d", $d_hour, $d_minutes, $d_second);
		// ��������
		if (!checkdate($d_month, $d_day, $d_year)) {
			$inform = "ǯ�������ϥߥ���<br>���ǯ�������ǧ���Ƥ���������";
		}else{
			// �ץ�ե�����ID����
			$pid = 0;
			// $eid����
			$logfile = IDCheck($d_eid, 0);
			$oldlog = file(LOGDIR.substr($logfile[0], 3, 4)."/".$logfile[0]);
			$key = IDSearch($d_eid, $oldlog);

			//�ƥ���������
			$d_title  = CleanStr($d_title);
			$d_mes  = CleanStr($d_mes);
			$d_more  = CleanStr($d_more);
			// ����ʸ�������졣 
			$d_mes = rntobr($d_mes);
			$d_mes = str_replace( "&nbsp;", " ", $d_mes);		// &nbsp �򥹥ڡ������Ѵ�����
			$d_more = rntobr($d_more);
			$d_more = str_replace( "&nbsp;", " ", $d_more);		// &nbsp �򥹥ڡ������Ѵ�����
			$finfo = pathinfo($upfile_name);
			$log = $d_eid."<>".$d_date."<>".$d_time."<>".$d_cid."<>".$pid."<>".$d_title."<>".$d_mes."<>".$d_more."<>".$cok."<>".$tok."\r\n";

			list($old_eid, $old_date, $old_time) = explode("<>", $oldlog[$key], 4);
			$logname = "log".substr($d_date, 0, 6).".dat";
			$oldlogname = "log".substr($old_date, 0, 6).".dat";

			if (substr($d_date, 0, 6) != substr($old_date, 0, 6)) {
				$err = FileSearch($logname);
				if ($err == -1) {
					$inform = "�����ν񤭹��ߤ˼��Ԥ��ޤ�����<br>".LOGDIR."�ǥ��쥯�ȥ�Υѡ��ߥå������ǧ���Ƥ���������";
				}elseif ($err == 0) {
					$inform = "���Υ����С��ϥ����ե⡼�ɤǤ���<br>".LOGDIR."�ʲ���ǯ�ǥ��쥯�ȥ���ư�Ǻ������Ƥ���������";
				}elseif ($err == 1) {
					if ($newlog = @file(LOGDIR.substr($d_date, 0, 4)."/".$logname)) {
						$datetime = $d_date.$d_time;
						$newkey = DateSearch($datetime, $newlog);
						array_splice($newlog, $newkey, 0, $log);
						$fp = fopen(LOGDIR.substr($d_date, 0, 4)."/".$logname, "w");
						flock($fp, LOCK_EX);
						fputs($fp, implode('',$newlog));
						fclose($fp);
					}else{
						$fp = fopen(LOGDIR.substr($d_date, 0, 4)."/".$logname, "w");
						flock($fp, LOCK_EX);
						fputs($fp, $log);
						fclose($fp);
					}
					array_splice($oldlog, $key, 1);
					$fp = fopen(LOGDIR.substr($old_date, 0, 4)."/".$oldlogname, "w");
					flock($fp, LOCK_EX);
					fputs($fp, implode('', $oldlog));
					fclose($fp);
					$inform .= "�����򹹿����ޤ�����";
				}
			}else{
				if ($d_date == $old_date && $d_time == $old_time) {
					$oldlog[$key] = $log;
				}else{
					array_splice($oldlog, $key, 1);
					$datetime = $d_date.$d_time;
					$newkey = DateSearch($datetime, $oldlog);
					if ($newkey == -1) {
						$oldlog[0] = $log;
					}else{
						array_splice($oldlog, $newkey, 0, $log);
					}
				}
				//���ե�����񤭹���
				//����������ѹ�����Ƥ��ʤ����ޤ���Ʊ��ǯ��ξ�繹��
				//����ʳ��ξ����ѹ����ǡ�������
				$fp = fopen(LOGDIR.substr($old_date, 0, 4)."/".$oldlogname, "w");
				flock($fp, LOCK_EX);
				fputs($fp, implode('', $oldlog));
				fclose($fp);
				$inform .= "�����򹹿����ޤ�����";
			}
			//trackback����
			if (strlen($d_trackback) != 0) {
				$tb = trackback_send($d_trackback,HOMELINK."?eid=".$d_eid, $d_title, $d_mes);
				$inform .= $tb;
			}
			//ping����
			if (count($ping_url) != 0) {
				$pu = ping_send($ping_url);
				$inform .= $pu;
			}
		}
		//$mes����<br>����ԥ����ɤ��ִ�
		$d_mes = str_replace("<br />", "\n", $d_mes);
		$d_mes = str_replace("<br>", "\n", $d_mes);
		$d_more = str_replace("<br />", "\n", $d_more);	// <----- v1.3.1 �ɲ�
		$d_more = str_replace("<br>", "\n", $d_more);		// <----- v1.3.1 �ɲ�
// -------------------------------------------------------------------
	}elseif ($action == "preview") {
		$d_mes  = CleanStr($d_mes);
		$d_more  = CleanStr($d_more);
		// ����ʸ�������졣 
		$d_mes = str_replace( "&nbsp;", " ", $d_mes);		// &nbsp �򥹥ڡ������Ѵ�����
		$d_more = str_replace( "&nbsp;", " ", $d_more);		// &nbsp �򥹥ڡ������Ѵ�����
		$d_date = sprintf("%4d%02d%02d", $d_year, $d_month, $d_day);
		$d_time = sprintf("%02d%02d%02d", $d_hour, $d_minutes, $d_second);
// -------------------------------------------------------------------
	}else{
		//eid�����å�
		$oldid = file(LOGDIR."id.dat");
		$oldid[0] = ereg_replace( "\n$", "", $oldid[0] );
		$oldid[0] = ereg_replace( "\r$", "", $oldid[0] );
		$oldid[0]++;
		$d_eid = $oldid[0];


		$d_cid = "-1";
		$d_title = "";
		$d_mes = "";
		$d_more = "";		// <----- v1.3.1 �ɲ�
		$cok = "0";
		$tok = "0";
	}
	up_mes($inform);
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">';
	if ($action == "edit" || $action == "update" || $action == "post" || $action == "preview") {
		$pre_mes = IconStr($d_mes);
		$pre_mes = tagreplaceStr($pre_mes);
		$pre_mes = rntobr($pre_mes);
		$pre_more = IconStr($d_more);
		$pre_more = tagreplaceStr($pre_more);
		$pre_more = rntobr($pre_more);
		echo '<br>
		<div class="maintitle">�����Υץ�ӥ塼</div>
		<br>
		<div id="auto" style="width: 530px; height: 200px; background: #dcdcdc; padding: 5px; border: solid 1px #000000;">
		<div class="logtitle">'.$d_title.'</div>
		<br>
		<div class="logbody">'.$pre_mes.'</div>
		<hr>
		<div class="logbody">'.$pre_more.'</div>
		</div>
		<br>
		';
	}

	if ($action == "edit" || $action == "update" || $action == "post" || $newpost == "old") {
		echo '
		<div class="maintitle">�������Խ�  <span class="mainstate">����ID:['.$d_eid.']</span></div><br>
		<table border="0" cellspacing="0" cellpadding="0">
		<form action="'.PHP_SELF.'?mode=log&action=update" enctype="multipart/form-data" method=post name=post>
		';
	}else{
		echo '
		<div class="maintitle">�����ο������  <span class="mainstate">����ID:['.$d_eid.']</span></div><br>
		<table border="0" cellspacing="0" cellpadding="0">
		<form action="'.PHP_SELF.'?mode=log&action=post" enctype="multipart/form-data" method=post name=post>
		';
	}
	echo '
	<tr><td valign="top" colspan="2" width="270">
	<div class="linktitle">�� �����Υ����ȥ� ��</div>
	<div class="inputarea"><input name=d_title size=30 value="'.$d_title.'" style="width:300px"></div>
	<br>
	</td>
	<td>
</td>
</tr>
	<tr><td valign="top" width="100">
	<div class="linktitle">�� ������ ��</div>
	<div class="inputarea"><select name=cok>';
	if ($cok != "1") {
		echo '<option value="0" selected>���դ����</option>
		<option value="1">���դ����</option>';
	}else{
		echo '<option value="0">���դ����</option>
		<option value="1" selected>���դ����</option>';
	}
	echo '</select></div></td><td width="130">
	<div class="linktitle">�� �ȥ�å��Хå� ��</div>
	<div class="inputarea"><select name=tok>';
	if ($tok != "1") {
		echo '<option value="0" selected>���դ����</option>
		<option value="1">���դ����</option>';
	}else{
		echo '<option value="0">���դ����</option>
		<option value="1" selected>���դ����</option>';
	}
	echo '</select></div></td><td>
	<div class="linktitle">�� ���ƥ��� ��</div>
	<div class="inputarea"><select name=d_cid>
	';
	if ($d_cid == "-1") {
		echo '<option value="-1" selected>����̵��</option>';
	}else{
		echo '<option value="-1">����̵��</option>';
	}
	if (file_exists(LOGDIR."category.dat")){
		$category = file(LOGDIR."category.dat");
		//$category������ԥ����ɺ��
		for ( $i = 0; $i < count( $category ); $i++ ) {
			$category[$i] = ereg_replace( "\n$", "", $category[$i] );
			$category[$i] = ereg_replace( "\r$", "", $category[$i] );
			list($c_cid, $c_name) = explode("<>", $category[$i]);
			if ($d_cid == $c_cid) {
				echo '<option value="'.$c_cid.'" selected>'.$c_name.'</option>';
			}else{
				echo '<option value="'.$c_cid.'">'.$c_name.'</option>';
			}
		}
	}
	echo '</select></div>
	</td>
	</tr>
	</table>
	<br>
	<table width="540" border="0" cellspacing="0" cellpadding="0">
	<tr><td valign="top">
	<div class="linktitle">�� ���������� ��</div>
	<div id="MesIconOff" style="margin: 0px 20px 5px 0px;text-align:right;"><a href="javascript:;" onclick=iconview(0) style="font-size:12px;border:solid 1px;padding: 1px 5px 1px 5px;">��ʸ��ɽ��</a> <a href="javascript:;" onclick=wopen_pict() style="font-size:12px;border:solid 1px;padding: 1px 5px 1px 5px;">�����ɲ�����</a></div>
	<div id="MesIconOn" style="display:none;"><div style="margin: 0px 20px 5px 0px;text-align:right;"><a href="javascript:;" onclick=iconview(1) style="font-size:12px;border:solid 1px;padding: 1px 5px 1px 5px;">��ʸ����ɽ��</a> <a href="javascript:;" onclick=wopen_pict() style="font-size:12px;border:solid 1px;padding: 1px 5px 1px 5px;">�����ɲ�����</a></div>
	<table border="1" cellspacing="0" cellpadding="2">
	<tr><td>
	<table border="0" cellspacing="0" cellpadding="2">
	<tr>
	';
	$icon = file(LOGDIR."icon.dat");
	for ($i = 0; $i < 100; $i++) {
		$icon[$i] = ereg_replace( "\n$", "", $icon[$i] );
		$icon[$i] = ereg_replace( "\r$", "", $icon[$i] );
		list($i_pic, $i_data) = explode("<>", $icon[$i]);
		$size = @getimagesize(ICONDIR.$i_pic);
		echo '<td><a href="javascript:;" onclick=icon('.$i.',0)><img src="'.ICONDIR.$i_pic.'" '.$size[3].' alt="'.$i_data.'" border="0"></a></td>';
		if ($i == 24 || $i == 49 || $i == 74) {
			echo '</tr><tr>';
		}
	}
	echo "
	</tr></table>
	</td><tr></table>
	</div>
	<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"inputarea\">
	<tr><td>| ʸ��������:<select name=\"font_mes\" onChange=\"ins(this.form.font_mes.selectedIndex,0)\" class=\"btn_font\">
		<option value=\"7\">7 px</option>
		<option value=\"9\">9 px</option>
		<option value=\"12\"selected>12 px</option>
		<option value=\"18\">18 px</option>
		<option value=\"24\">24 px</option>
	</select>
	| <a href=\"javascript:;\" onclick=\"ins(5,0)\" class=\"link_tag\">����</a>
	<a href=\"javascript:;\" onclick=\"ins(6,0)\" class=\"link_tag\">����</a>
	<a href=\"javascript:;\" onclick=\"ins(7,0)\" class=\"link_tag\">����</a>
	<a href=\"javascript:;\" onclick=\"ins(8,0)\" class=\"link_tag\">�����</a> |
	<a href=\"javascript:;\" onclick=\"ins(9,0)\" class=\"link_tag\")>��·��</a>
	<a href=\"javascript:;\" onclick=\"ins(10,0)\" class=\"link_tag\")>���·��</a>
	<a href=\"javascript:;\" onclick=\"ins(11,0)\" class=\"link_tag\")>��·��</a> |</td></tr>
	<tr><td>| ʸ����:
	<a href=\"javascript:;\" onclick=\"ins(12,0)\" style=\"background-color:black;border:solid 1px black;\")><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(13,0)\" style=\"background-color:brown;border:solid 1px black;\")><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(14,0)\" style=\"background-color:red;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(15,0)\" style=\"background-color:orange;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(16,0)\" style=\"background-color:yellow;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(17,0)\" style=\"background-color:green;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(18,0)\" style=\"background-color:blue;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(19,0)\" style=\"background-color:violet;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(20,0)\" style=\"background-color:gray;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a>
	<a href=\"javascript:;\" onclick=\"ins(21,0)\" style=\"background-color:white;border:solid 1px black;\"><img src=\"./images/blank.gif\" width=\"10\" height=\"11\" border=\"0\"></a> | 
	<a href=\"javascript:;\" onclick=\"linkins(0,0)\" class=\"link_tag\">���</a>
	<a href=\"javascript:;\" onclick=\"linkins(1,0)\" class=\"link_tag\">�᡼��</a> |
	<a href=\"javascript:;\" onclick=\"ins(22,0)\" class=\"link_tag\">����</a>
	<a href=\"javascript:;\" onclick=\"ins(23,0)\" class=\"link_tag\">����ʸ</a> |</td></tr>
	</table>
	<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"inputarea\">
	<tr><td>
	<textarea style=\"WIDTH: 520px;font-size:12px;\" id=\"d_mes\" name=\"d_mes\" rows=\"10\" cols=\"50\" wrap=\"virtual\">".tagreplaceStr($d_mes)."</textarea><br><br>
	</td></tr>
	</table>";
	if ($action == "edit" || $action == "update" || $action == "post" || $newpost == "old") {
		echo '<div align="center"><input type=submit name="preview" value="�ץ�ӥ塼"><input type=submit name="post" value="��������"></div>';
	}else{
		echo '<div align="center"><input type=submit name="preview" value="�ץ�ӥ塼"><input type=submit name="post" value="��Ƥ���"></div>';
	}

	echo '
	<div class="linktitle">�� ������³�� ��</div>
	<div id="MoreIconOn" style="display:none;">
	<table border="1" cellspacing="0" cellpadding="2">
	<tr><td>
	<table border="0" cellspacing="0" cellpadding="2">
	<tr>';
	$icon = file(LOGDIR."icon.dat");
	for ($i = 0; $i < 100; $i++) {
		$icon[$i] = ereg_replace( "\n$", "", $icon[$i] );
		$icon[$i] = ereg_replace( "\r$", "", $icon[$i] );
		list($i_pic, $i_data) = explode("<>", $icon[$i]);
		$size = @getimagesize(ICONDIR.$i_pic);
		echo '<td><a href="javascript:;" onclick=icon('.$i.',1)><img src="'.ICONDIR.$i_pic.'" '.$size[3].' alt="'.$i_data.'" border="0"></a></td>';
		if ($i == 24 || $i == 49 || $i == 74) {
			echo '</tr><tr>';
		}
	}
	echo '</tr></table>
	</td><tr></table>
	</div>
	<table border="0" cellspacing="1" cellpadding="3" class="inputarea">
	<tr><td>| ʸ��������:<select name="font_more" onChange=ins(this.form.font_more.selectedIndex,1) class="btn_font">
		<option value="7">7 px</option>
		<option value="9">9 px</option>
		<option value="12"selected>12 px</option>
		<option value="18">18 px</option>
		<option value="24">24 px</option>
	</select>
	| <a href="javascript:;" onclick=ins(5,1) class="link_tag">����</a>
	<a href="javascript:;" onclick=ins(6,1) class="link_tag">����</a>
	<a href="javascript:;" onclick=ins(7,1) class="link_tag">����</a>
	<a href="javascript:;" onclick=ins(8,1) class="link_tag">�����</a> |
	<a href="javascript:;" onclick=ins(9,1) class="link_tag")>��·��</a>
	<a href="javascript:;" onclick=ins(10,1) class="link_tag")>���·��</a>
	<a href="javascript:;" onclick=ins(11,1) class="link_tag")>��·��</a> |</td></tr>
	<tr><td>| ʸ����:
	<a href="javascript:;" onclick=ins(12,1) style="background-color:black;border:solid 1px black;")><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(13,1) style="background-color:brown;border:solid 1px black;")><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(14,1) style="background-color:red;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(15,1) style="background-color:orange;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(16,1) style="background-color:yellow;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(17,1) style="background-color:green;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(18,1) style="background-color:blue;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(19,1) style="background-color:violet;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(20,1) style="background-color:gray;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a>
	<a href="javascript:;" onclick=ins(21,1) style="background-color:white;border:solid 1px black;"><img src="./images/blank.gif" width="10" height="11" border="0"></a> | 
	<a href="javascript:;" onclick=linkins(0,1) class="link_tag">���</a>
	<a href="javascript:;" onclick=linkins(1,1) class="link_tag">�᡼��</a> |
	<a href="javascript:;" onclick=ins(22,1) class="link_tag">����</a>
	<a href="javascript:;" onclick=ins(23,1) class="link_tag">����ʸ</a> |</td></tr>
	</table>
	<table border="0" cellspacing="1" cellpadding="3" class="inputarea">
	<tr><td>
	<textarea style="WIDTH: 520px;font-size:12px;" id="d_more" name="d_more" rows="10" cols="50" wrap="virtual">'.tagreplaceStr($d_more).'</textarea><br><br>
	</td></tr>
	</table>
	';

	if ($action == "edit" || $action == "update" || $action == "post" || $action == "preview") {
		$d_year =  substr($d_date, 0, 4);
		$d_month = substr($d_date, 4, 2);
		$d_day = substr($d_date, 6, 2);
		$d_hour = substr($d_time, 0, 2);
		$d_minutes = substr($d_time, 2, 2);
		$d_second = substr($d_time, 4, 2);
	}else{
		$d_year = gmdate ("Y", time()+TIMEZONE);
		$d_month = gmdate ("m", time()+TIMEZONE);
		$d_day = gmdate ("d", time()+TIMEZONE);
		$d_hour = gmdate ("H", time()+TIMEZONE);
		$d_minutes = gmdate ("i", time()+TIMEZONE);
		$d_second = gmdate ("s", time()+TIMEZONE);

	}
	$oldyear = 1970;
	$newyear = gmdate ("Y", time()+TIMEZONE) + 1;
	echo '
	</td>
	</tr>
	</table>
	<div class="linktitle">�� ���������� ��</div>
	<div class="inputarea">
	<select name="d_year">';
	for ( $i = $newyear; $i >= $oldyear; $i-- ) {
		if ($d_year == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
	echo ' ǯ ';
	echo '<select name="d_month">';
	for ( $i = 1; $i <= 12; $i++ ) {
		if ($d_month == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
	echo ' �� ';
	echo '<select name="d_day">';
	for ( $i = 1; $i <= 31; $i++ ) {
		if ($d_day == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
	echo ' ��   ';
	echo '<select name="d_hour">';
	for ( $i = 0; $i <= 23; $i++ ) {
		if ($d_hour == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
	echo ' �� ';
	echo '<select name="d_minutes">';
	for ( $i = 0; $i <= 59; $i++ ) {
		if ($d_minutes == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
	echo ' ʬ ';
	echo '<select name="d_second">';
	for ( $i = 0; $i <= 59; $i++ ) {
		if ($d_second == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '</select>';
	echo ' �� ';


	echo '<input type="button" name="datenow" value="���߻�������" onclick="dnow()">';


	echo '</div>
	<br><br>
	<div class="linktitle">�� �ȥ�å��Хå����������� ��</div>
	<textarea style="WIDTH: 500px;font-size:12px;" name=d_trackback rows=4 cols=50>'.$d_trackback.'</textarea><br><br>
	<div class="linktitle">�� ����Ping���������� ��</div>
	<div class="inputarea">';
	if (file_exists(LOGDIR."ping.dat")){
		$ping = file(LOGDIR."ping.dat");
		if (count($ping) == 0) {
			echo '�� Ping���������Ͽ����Ƥ��ޤ���<br>';
		}else{
			for ( $i = 0; $i < count( $ping ); $i++ ) {
				//$ping������ԥ����ɺ��
				$ping[$i] = ereg_replace( "\n$", "", $ping[$i] );
				$ping[$i] = ereg_replace( "\r$", "", $ping[$i] );
				list($u_name, $u_url) = explode("<>", $ping[$i]);
				echo '<input type="checkbox" name="ping_url[]" value="'.$u_url.'" id="p'.$i.'">';
				echo '<label for="p'.$i.'">'.$u_name.'</label><br>';
			}
		}
	}
	echo '<br>';
	if ($action == "edit" || $action == "update" || $action == "post" || $newpost == "old") {
		echo '<INPUT type="hidden" value="old" name=newpost><INPUT type="hidden" value="'.$d_eid.'" name=d_eid><div align="center"><input type=submit name="preview" value="�ץ�ӥ塼"><input type=submit name="post" value="��������"></div>';
	}else{
		echo '<INPUT type="hidden" value="new" name=newpost><INPUT type="hidden" value="'.$d_eid.'" name=d_eid><div align="center"><input type=submit name="preview" value="�ץ�ӥ塼"><input type=submit name="post" value="��Ƥ���"></div>';
	}
	echo '
	</form>
	</div></div>
	</td></tr>
	</table>
	</td>
	';
}


/* �����ν���������� */
function mes_list($action, $page, $comment_eid, $trackback_eid, $qry_eid, $qry_coid, $qry_toid) {
	if ($action == "cm_delete") {
		// ----- �����Ȥκ������
		$cmtlist = IDCheck($qry_eid, 1);
		@sort($cmtlist);
		$cnt = 0;
		for ($i = 0; $i < count($cmtlist); $i++ ) {
			$cmt = file(LOGDIR.substr($cmtlist[$i], 3, 4)."/".$cmtlist[$i]);
			$cmt = array_reverse($cmt);
			while (list($cmtkey, $cmtval) = each($cmt)) {
				$cmtval = ereg_replace( "\n$", "", $cmtval );
				$cmtval = ereg_replace( "\r$", "", $cmtval );
				list($c_eid) = explode("<>", $cmtval, 2);
				if ($c_eid == $qry_eid) {
					if ($qry_coid == $cnt) {
						array_splice($cmt, $cmtkey, 1);
						$cmt = array_reverse($cmt);
						$fp = fopen(LOGDIR.substr($cmtlist[$i], 3, 4)."/".$cmtlist[$i], "w");
						flock($fp, LOCK_EX);
						fputs($fp, implode('', $cmt));
						fclose($fp);
						$comment_eid = $qry_eid;
						$link_url = PHP_SELF.'?mode=list&page='.$page.'&comment='.$comment_eid.'#comments';
						echo '<html><head><META HTTP-EQUIV="refresh"content="1;URL='.$link_url.'"></head>
						<body><div style="text-align:center;margin-top:10px;">�����Ȥ������ޤ�����<br>
						���̤���ưŪ���ڤ��ؤ��ʤ�����<br>
						<a href="'.$link_url.'">������</a>�򥯥�å����Ƥ���������</div></body></html>';
						exit;
					}
					$cnt++;
				}
			}
		}
	}elseif ($action == "tb_delete") {
		// ----- �ȥ�å��Хå��κ��
		$trklist = IDCheck($qry_eid, 2);
		@sort($trklist);
		$cnt = 0;
		for ($i = 0; $i < count($trklist); $i++ ) {
			$trk = file(LOGDIR.substr($trklist[$i], 3, 4)."/".$trklist[$i]);
			$trk = array_reverse($trk);
			while (list($trkkey, $trkval) = each($trk)) {
				$trkval = ereg_replace( "\n$", "", $trkval );
				$trkval = ereg_replace( "\r$", "", $trkval );
				list($t_eid) = explode("<>", $trkval, 2);
				if ($t_eid == $qry_eid) {
					if ($qry_toid == $cnt) {
						array_splice($trk, $trkkey, 1);
						$trk = array_reverse($trk);
						$fp = fopen(LOGDIR.substr($trklist[$i], 3, 4)."/".$trklist[$i], "w");
						flock($fp, LOCK_EX);
						fputs($fp, implode('', $trk));
						fclose($fp);
						$trackback_eid = $qry_eid;
						$link_url = PHP_SELF.'?mode=list&page='.$page.'&trackback='.$trackback_eid.'#trackback';
						echo '<html><head><META HTTP-EQUIV="refresh"content="1;URL='.$link_url.'"></head>
						<body><div style="text-align:center;margin-top:10px;">�ȥ�å��Хå��������ޤ�����<br>
						���̤���ưŪ���ڤ��ؤ��ʤ�����<br>
						<a href="'.$link_url.'">������</a>�򥯥�å����Ƥ���������</div></body></html>';
						exit;
					}
					$cnt++;
				}
			}
		}
	}elseif ($action == "delete") {
		// ----- �Ƶ����κ��
		if ($loglist = IDCheck($qry_eid,0)) {
			$log = file(LOGDIR.substr($loglist[0],3,4)."/".$loglist[0]);
			$key = IDSearch($qry_eid, $log);
			array_splice($log, $key, 1);
			//���ե�����񤭹���
			$fp = fopen(LOGDIR.substr($loglist[0],3,4)."/".$loglist[0], "w");
			flock($fp, LOCK_EX);
			fputs($fp, implode('', $log));
			fclose($fp);
		}
		// ----- ����ID�Υ����Ȱ����
		if($cmtlist = IDCheck($qry_eid, 1)) {
			for ($i = 0; $i < count($cmtlist); $i++ ) {
				$cmt = file(LOGDIR.substr($cmtlist[$i], 3, 4)."/".$cmtlist[$i]);
				$cnt = 0;
				$newcomment = array();
				while (list($cmtkey, $cmtval) = each($cmt)) {
					list($c_eid) = explode("<>", $cmtval, 2);
					if ($c_eid != $qry_eid) {
						$newcomment[$cnt] = $cmtval;
						$cnt++;
					}
				}
				//�����ȥե�����񤭹���
				$fp = fopen(LOGDIR.substr($cmtlist[$i], 3, 4)."/".$cmtlist[$i], "w");
				flock($fp, LOCK_EX);
				fputs($fp, implode('', $newcomment));
				fclose($fp);
			}
		}
		// ----- ����ID�Υȥ�å��Хå������
		if ($trklist = IDCheck($qry_eid, 2)) {
			for ($i = 0; $i < count($trklist); $i++ ) {
				$trk = file(LOGDIR.substr($trklist[$i], 3, 4)."/".$trklist[$i]);
				$cnt = 0;
				$newtrackback = array();
				while (list($trkkey, $trkval) = each($trk)) {
					list($t_eid) = explode("<>", $trkval, 2);
					if ($t_eid != $qry_eid) {
						$newtrackback[$cnt] = $trkval;
						$cnt++;
					}
				}
				//�����ȥե�����񤭹���
				$fp = fopen(LOGDIR.substr($trklist[$i], 3, 4)."/".$trklist[$i], "w");
				flock($fp, LOCK_EX);
				fputs($fp, implode('', $newtrackback));
				fclose($fp);
			}
		}
		if (!$page) $page = 0;
		$link_url = PHP_SELF.'?mode=list&page='.$page;
		echo '<html><head><META HTTP-EQUIV="refresh"content="1;URL='.$link_url.'"></head>
		<body><div style="text-align:center;margin-top:10px;">�����������ޤ�����<br>
		���̤���ưŪ���ڤ��ؤ��ʤ�����<br>
		<a href="'.$link_url.'">������</a>�򥯥�å����Ƥ���������</div></body></html>';
		exit;
	}else{
		$inform = "";
	}

	headhtml(2);
	contentshtml();

	up_mes($inform);
	if ($page == "") $page = 0;

	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�������Խ������</div><br>';
	
	if ($loglist = LogFileList(0)){
		echo '
		<div class="linktitle">�� �������� ��</div>
		<table width="500" border="1" cellspacing="0" cellpadding="0" class="logbody">
		<tr align="center" bgcolor="#82BE7D"><td width="50" rowspan="2">���</td><td colspan="2">�����ȥ�</td><td colspan="2">���ƥ���</td><td rowspan="2">�Խ�</td></tr>
		<tr align="center" bgcolor="#82BE7D"><td width="50">ID</td><td>����</td><td width="50">����</td><td width="50">�ȥ�</td></tr>';

		$category = file(LOGDIR."category.dat");
		for ( $i = 0; $i < count( $category ); $i++ ) {
			//$category������ԥ����ɺ��
			$category[$i] = ereg_replace( "\n$", "", $category[$i] );
			$category[$i] = ereg_replace( "\r$", "", $category[$i]);
		}

		//$page����ɽ�����ϰ��֤򻻽�
		$pcnt = $page * 10;	//10���1�ڡ���ɽ��
		$page_st = 10 * $page + 1;
		$page_ed = $page_st + 10;
		$i = 1;
		$loopcnt = 0;
		$col = 0;
		$exitkey = 0;
		while(list ($key, $val) = each($loglist)) {
			$log = file(LOGDIR.substr($val,3,4)."/".$val);
			if ($exitkey == 1) break;
			while (list($logkey, $logval) = each($log)) {
				if ($page_st <= $i && $page_ed > $i) {
					$logval = ereg_replace( "\n$", "", $logval );
					$logval = ereg_replace( "\r$", "", $logval );
					list($eid, $d_date, $d_time, $cid, $pid, $d_title, , , $d_cok, $d_tok) = explode("<>", $logval);

					$comment_cnt = CommentCount($eid);

					$trackback_cnt = TrackbackCount($eid);

					$c_name = "-";
					for ( $j = 0; $j < count( $category ); $j++ ) {
						list($tmp_cid, $tmp_name) = explode("<>", $category[$j]);
						if ($cid == $tmp_cid) {
							$c_name = $tmp_name;
							break;
						}
					}

					echo '<form action="'.PHP_SELF.'?mode=log&action=edit" method="post">';
					echo '<INPUT type="hidden" value="'.$eid.'" name="d_eid">';
					if ($col == 0){
						echo '<tr bgcolor="#F5F5F5">';
					}else{
						echo '<tr>';
					}
					if (trim($d_title) == "") {
						$d_title = "** ̵�� **";
					}
					$delref = PHP_SELF.'?mode=list&action=delete&eid='.$eid;
					echo '<td align ="center" width="50" rowspan="2"><a href=Javascript:delcheck("'.$delref.'");><img src="./images/trash.gif" border="0"></a></td><td colspan="2"><a href="'.HOMELINK.'?eid='.$eid.'" target="_blank"><input type="text" value="'.$d_title.'" size="42" readonly="readonly" style="width:290px;cursor: pointer;"></a></td><td colspan="2" align="center">'.$c_name.'</td>';
					echo '<td align ="center" width="50" rowspan="2"><input type="image" src="./images/edit.gif" name="submit" alt="�Խ�"></td></tr>';
					if ($col == 0){
						echo '<tr bgcolor="#F5F5F5">';
						$col = 1;
					}else{
						echo '<tr>';
						$col = 0;
					}
					echo '<td align="center" width="50">'.$eid.'</td><td align="center" width="250">'.date("Y/m/d h:i A", mktime(substr($d_time,0,2),substr($d_time,2,2),0,substr($d_date,4,2), substr($d_date,6,2), substr($d_date,0,4))).'</td>';
					if ($comment_cnt == 0) {
						echo '<td align="center" width="50">0</td>';
					}else{
						echo '<td align="center" width="50"><a href='.PHP_SELF.'?mode=list&page='.$page.'&comment='.$eid.'#comments>'.$comment_cnt.'</a></td>';
					}
					if ($trackback_cnt == 0) {
						echo '<td align="center" width="50">0</td>';
					}else{
						echo '<td align="center" width="50"><a href='.PHP_SELF.'?mode=list&page='.$page.'&trackback='.$eid.'#trackback>'.$trackback_cnt.'</a></td>';
					}
					echo '</tr>';
					echo '</form>';
					$loopcnt++;
					if ($loopcnt >= 10) {
						$exitkey = 1;
						break;
					}
				}
				$i++;
			}
		}
		echo '</table>';
	}else{
		echo '
		<div class="linktitle">�� �������� ��</div>
		<div class="logbody">�����Ϥ���ޤ���</div>
		';
	}

	$pcnt_start = $pcnt + 1;  		// ɽ�����ϰ���
	$pcnt_end = $pcnt + $loopcnt;		// ɽ����λ����
	$bpage = $page - 1;			// ���ڡ���
	$npage = $page + 1;			// ���ڡ���
	if (!$logcnt = @array_sum(archive_count())) {
		$logcnt = 0;
		$mpage = 0;
	}else{
		$mpage = ceil($logcnt / 10);
	}
	// ���ڡ��������ڡ���ɽ���ѥ��
	
	if ($logcnt != 0) {
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		if ($page > 0 && $i < $logcnt) {
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=list&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$logcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=list&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page > 0 && $i >= $logcnt) { 
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=list&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$logcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}elseif($page == 0 && $i < $logcnt) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$logcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=list&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page == 0 && $i > $logcnt) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$logcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}
		echo '</table><br>';
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		echo '<tr><td align="center">Page: ';
		for ($i = 0; $i < $mpage; $i++) {
			$j = $i + 1;
			if ($i == $page) {
				echo '<a href='.PHP_SELF.'?mode=list&page='.$i.'>['.$j.']</a> ';
			}else{
				echo '<a href='.PHP_SELF.'?mode=list&page='.$i.'>'.$j.'</a> ';
			}
		}
		echo '</td></tr></table><br>';
	}

	if ($comment_eid != "") {
		if ($cmtlist = IDCheck($comment_eid, 1)) {
			echo '
			<a name="comments"></a>
			<div class="linktitle">�� �����Ȱ��� ��</div>';
			@sort($cmtlist);
			$cnt = 0;
			for ($i = 0; $i < count($cmtlist); $i++ ) {
				$cmt = file(LOGDIR.substr($cmtlist[$i], 3, 4)."/".$cmtlist[$i]);
				$cmt = array_reverse($cmt);
				while (list($cmtkey, $cmtval) = each($cmt)) {
					$cmtval = ereg_replace( "\n$", "", $cmtval );
					$cmtval = ereg_replace( "\r$", "", $cmtval );
					list($c_eid, $c_date, $c_time, $c_name, $c_email, $c_mes, $ip_addr, $user_agent, $c_url) = explode("<>", $cmtval);
					if ($c_eid == $comment_eid) {
						$c_mes = rntobr($c_mes);
						echo '
						<table width="500" border="1" cellspacing="0" cellpadding="0"  class="logbody">
						<tr bgcolor="#82BE7D" align="center"><td width="50">ID</td><td width="150">����</td><td width="150">��Ƽ�</td><td width="50">URL</td><td width="50">MAIL</td><td width="50">���</td></tr>
						<tr align="center"><td bgcolor="#82BE7D">'.$cnt.'</td><td>'.date("Y/m/d h:i A", mktime(substr($c_time,0,2),substr($c_time,2,2),0,substr($c_date,4,2), substr($c_date,6,2), substr($c_date,0,4))).'</td><td>'.$c_name.'</td><td>
						';
						if ($c_url == "") {
							echo "-";
						}else{
							echo '<a href="'.$c_url.'"><img src="./images/url.gif" border="0"></a>';
						}
						echo '</td><td>';
						if ($c_email == "") {
							echo "-";
						}else{
							echo '<a href="mailto:'.$c_email.'"><img src="./images/mail.gif" border="0"></a>';
						}
						$delref = PHP_SELF.'?mode=list&action=cm_delete&page='.$page.'&eid='.$comment_eid.'&coid='.$cnt.'#comments';
						echo '
						</td><td><a href=Javascript:delcheck("'.$delref.'"); class="button"><img src="./images/trash.gif" border="0"></a></td></tr>
						<tr><td bgcolor="#82BE7D" align="center">����</td><td colspan="5">'.$c_mes.'</td></tr>
						<tr><td bgcolor="#82BE7D" align="center">IP</td><td colspan="5">'.$ip_addr.'</td></tr>
						<tr><td bgcolor="#82BE7D" align="center">�֥饦��</td><td colspan="5">'.$user_agent.'</td></tr>
						</table><br>
						';
						$cnt++;
					}
				}
			}
		}
	}
	if ($trackback_eid != "") {
		if ($trklist = IDCheck($trackback_eid, 2)) {
			echo '
			<a name="trackback"></a>
			<div class="linktitle">�� �ȥ�å��Хå����� ��</div>';
			@sort($trklist);
			$cnt = 0;
			for ($i = 0; $i < count($trklist); $i++ ) {
				$trk = file(LOGDIR.substr($trklist[$i], 3, 4)."/".$trklist[$i]);
				$trk = array_reverse($trk);
				while (list($trkkey, $trkval) = each($trk)) {
					$trkval = ereg_replace( "\n$", "", $trkval );
					$trkval = ereg_replace( "\r$", "", $trkval );
						list($t_eid, $blog_name, $t_title, $t_url, $t_excerpt, $t_date, $t_time, $ip_addr, $user_agent) = explode("<>", $trkval);
						if ($t_eid == $trackback_eid) {
						$t_excerpt = str_replace( "\r\n",  "\n", $t_excerpt); 
						$t_excerpt = str_replace( "\r",  "\n", $t_excerpt);
						$t_excerpt = nl2br($t_excerpt);				//����ʸ��������<br>����������
						$t_excerpt = str_replace("\n",  "", $t_excerpt);		//\n��ʸ���󤫤�ä���
						echo '
						<table width="500" border="1" cellspacing="0" cellpadding="0"  class="logbody">
						<tr bgcolor="#82BE7D" align="center"><td width="50">ID</td><td width="150">����</td><td width="200">������̾</td><td width="50">URL</td><td width="50">���</td></tr>
						<tr align="center"><td bgcolor="#82BE7D">'.$cnt.'</td><td>'.date("Y/m/d h:i A", mktime(substr($t_time,0,2),substr($t_time,2,2),substr($t_time,4,2),substr($t_date,4,2), substr($t_date,6,2), substr($t_date,0,4))).'</td><td>'.$blog_name.'</td><td>
						';
						echo '<a href="'.$t_url.'"><img src="./images/url.gif" border="0"></a>';
						$delref = PHP_SELF.'?mode=list&action=tb_delete&page='.$page.'&eid='.$trackback_eid.'&toid='.$cnt.'#trackback';
						echo '
						</td><td><a href=Javascript:delcheck("'.$delref.'"); class="button"><img src="./images/trash.gif" border="0"></a></td></tr>
						<tr><td bgcolor="#82BE7D" align="center">����</td><td colspan="4">'.$t_excerpt.'</td></tr>
						<tr><td bgcolor="#82BE7D" align="center">IP</td><td colspan="4">'.$ip_addr.'</td></tr>
						<tr><td bgcolor="#82BE7D" align="center">�֥饦��</td><td colspan="4">'.$user_agent.'</td></tr>
						</table><br>
						';
						$cnt++;
					}
				}
			}
		}
	}

	echo '</div></td></tr></table></td>';
}


/* �ե�����Υ��åץ��ɲ��� */
function file_edit($action, $page, $upfile, $upfile_name, $qry_file) {
	$inform = "";
	if ($action == "post") {
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
	}elseif ($action == "delete") {
		if (!unlink(PICDIR.$qry_file)) {
			$inform = "�ե�����κ���˼��Ԥ��ޤ�����";
		}else{
			$inform = "�ե�����������ޤ�����";
		}
	}else{
		$inform = "";
	}
	up_mes($inform);
	if ($page == "") $page = 0;
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�����Υ��åץ���</div><br>
	<div class="linktitle">�� �������åץ��� ��</div>
	<table border="0" cellspacing="0" cellpadding="3" class="logbody">
	<form action="'.PHP_SELF.'?mode=file&action=post" enctype="multipart/form-data" method=post>
	<tr><td>
	<input type="file" name=d_img accept="image/jpeg,image/gif,image/png" size=30>
	</td></tr>
	<tr><td>
	<input type="submit" value="�ɲä���">
	</td></tr>
	<tr><td>
	<div class="mainstate">
	�� �ե���������� .GIF��.JPG��.PNG �Ǥ��ꤤ���ޤ���<br>
	</div>
	</td></tr>
	</form>
	</table>
  <br>
	<div class="linktitle">�� ���åץ��ɤ���Ƥ���������� ��</div>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr align="center" bgcolor="#82BE7D"><td width="200">����̾</td><td width="100">������</td><td width="100">��Ͽ��</td><td width="50">���</td></tr>
	';

	//$page����ɽ�����ϰ��֤򻻽�
	$pcnt = $page * 10;	//10���1�ڡ���ɽ��
	$page_st = 10 * $page + 1;
	$page_ed = $page_st + 10;
	$i = 1;
	$exitkey = 0;

	$dir_name = dir(PICDIR);
	$fname = array();
	$fsize = array();
	$ftime = array();
	$width = array();
	$height = 30;
	while($file_name = $dir_name->read()) {
		if (!is_dir($file_name)) {
			if ($size = @getimagesize(PICDIR.$file_name)) {
				if ($size[2] == 1 || $size[2] == 2 || $size[2] == 3) {
					$width[] = 30 * $size[0] / $size[1];
					$fname[] = $file_name;
					$fsize[] = round(filesize(PICDIR.$file_name) / 1024, 2);
					$ftime[] = date("Y/m/d H:i:s", filemtime(PICDIR.$file_name));
					$i++;
				}
			}
		}
	}
	arsort($ftime);
	$loopcount = 0;
	if (count($fname) == 0) {
		echo '<tr><td colspan="4" align="center">��Ͽ����Ƥ�������Ϥ���ޤ���</td></tr>';
	}else{
		$j = 1;
		while(list($key, $val) = each($ftime)) {
			if ($j >= $page_st && $j < $page_ed) {
				echo '
				<form action="'.PHP_SELF.'?mode=file&action=delete&file='.$fname[$key].'" method=post>
				<tr><td><img src="'.PICDIR.$fname[$key].'" width="'.$width[$key].'" height="'.$height.'" style="border:solid 1px;float:left;"><a href="'.PICDIR.$fname[$key].'" target="_blank">'.PICDIR.$fname[$key].'</a></td><td align="right">'.$fsize[$key].'KB</td><td align="center">'.substr($val,0,10).'</td><td align="center"><input type="image" src="./images/trash.gif" name="submit" alt="���"></td></tr>
				</form>
				';
				$loopcnt++;
//				if ($j >= $i-1) break;
			}
			$j++;
		}
	}
	echo '</table>';

	$pcnt_start = $pcnt + 1;  		// ɽ�����ϰ���
	$pcnt_end = $pcnt + $loopcnt;		// ɽ����λ����
	$bpage = $page - 1;			// ���ڡ���
	$npage = $page + 1;			// ���ڡ���
	$imgcnt = $i - 1;
	if ($imgcnt != 0) {
		$mpage = ceil($imgcnt / 10);
	}else{
		$mpage = 0;
	}

	if ($imgcnt != 0) {
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		if ($page > 0 && $page < $mpage - 1) {
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=file&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=file&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page > 0 && $page == $mpage - 1) { 
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=file&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}elseif($page == 0 && $page < $mpage - 1) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=file&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page == 0 && $page == $mpage - 1) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}
		echo '</table><br>';
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		echo '<tr><td align="center">Page: ';
		for ($i = 0; $i < $mpage; $i++) {
			$j = $i + 1;
			if ($i == $page) {
				echo '<a href='.PHP_SELF.'?mode=file&page='.$i.'>['.$j.']</a> ';
			}else{
				echo '<a href='.PHP_SELF.'?mode=file&page='.$i.'>'.$j.'</a> ';
			}
		}
		echo '</td></tr></table><br>';
	}
	
	echo '
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* ������������ */
function ip_check($action, $deny_ip, $iid, $ipaddr) {
	if($action == "post") {
		$oldip = file(LOGDIR."ip.dat");
		$ipdate = sprintf("%4d%02d%02d", gmdate ("Y", time()+TIMEZONE), gmdate ("m", time()+TIMEZONE), gmdate ("d", time()+TIMEZONE));
		//�ƥ���������
		$deny_ip = CleanStr($deny_ip);
		$newip = $deny_ip."<>".$ipdate."\r\n";
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."ip.dat", "w");
		flock($fp, LOCK_EX);
		if (strlen($newip)){
			fputs($fp, $newip);
		}
		fputs($fp, implode('', $oldip));
		fclose($fp);
		$inform = "�����������¤��ɲä��ޤ�����";
	}elseif($action == "delete") {
		$oldip = file(LOGDIR."ip.dat");
		array_splice($oldip, $key, 1);
		//���ե�����񤭹���
		$fp = fopen(LOGDIR."ip.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode('', $oldip));
		fclose($fp);
		$inform = "�����������¤������ޤ�����";
	}elseif($action == "cm_delete") {
		list($deny_key1, $deny_key2, $deny_key3, $deny_key4) = explode(".",$ipaddr);

		$bufdir = dir(LOGDIR);
		while (($ent = $bufdir->read()) !== false) {
			if ($ent != "." && $ent != "..") {
				if (is_dir(LOGDIR.$ent)) $result[] = $ent;
			}
		}
		for ($i = 0; $i < count($result); $i++) {
			$bufdir = dir(LOGDIR.$result[$i]);
			while (($ent = $bufdir->read()) !== false) {
				if (substr($ent,0,3) == "cmt") {
					if (is_file(LOGDIR.$result[$i]."/".$ent)) {
						$cnt = 0;
						$cmt = file(LOGDIR.$result[$i]."/".$ent);
						$newcomment = array();
						while (list($key, $val) = each($cmt)) {
							list(, , , , , , $ip_addr,) = explode("<>",$val);
							list($key1, $key2, $key3, $key4) = explode(".",$ip_addr);
							if ($deny_key1 == "*") $key1 = "*";
							if ($deny_key2 == "*") $key2 = "*";
							if ($deny_key3 == "*") $key3 = "*";
							if ($deny_key4 == "*") $key4 = "*";
							if ($deny_key1 != $key1 || $deny_key2 != $key2 || $deny_key3 != $key3 || $deny_key4 != $key4) {
								$newcomment[$cnt] = $cmt[$key];
								$cnt++;
							}
						}
						if ($cnt != count($cmt)) {
							//���ե�����񤭹���
							$fp = fopen(LOGDIR.$result[$i]."/".$ent, "w");
							flock($fp, LOCK_EX);
							fputs($fp, implode('', $newcomment));
							fclose($fp);
						}
					}
				}
			}
		}
		$inform = "������������IP�Υ����Ȥ������ޤ�����";
	}
	up_mes($inform);
  echo '
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr><td>
  <div class="mainbody">
  <div class="maintitle">������������</div><br>
  <div class="linktitle">�� ����IP�ɲ� ��</div>
  <table border="0" cellspacing="0" cellpadding="0" class="logbody">
	<tr><td>
  <form action="'.PHP_SELF.'?mode=ip&action=post" method=post>
  <table border="1" cellspacing="0" cellpadding="5" class="logbody">
  <tr><td width="50" align="center" bgcolor="#82BE7D">IP</td><td><input type="text" name=deny_ip size=30></td>
	</tr>
	</table>
	</td>
	<td> <input type="submit" value="�ɲä���"></td>
	</tr>
	</form>
	</table>
<div class="mainstate">
�� IP���ɥ쥹�ˤϥ磻��ɥ�����(*)�����ѤǤ��ޤ���<br>
�� �������192.168.0.* �� 192.168.0.0��192.168.0.255�ޤǤ�IP����ݤ��ޤ���
</div>
<br>
	<div class="linktitle">�� ����IP�ꥹ�� ��</div>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr align="center" bgcolor="#82BE7D"><td width="100">IP</td><td width="100">��Ͽ��</td><td width="150">�����Ȱ����</td><td width="100">��ϿIP���</td></tr>
	';
	$ip = file(LOGDIR."ip.dat");
	if (count($ip) == 0) {
		echo '<tr align="center"><td colspan="4">�ػ�IP����Ͽ����Ƥ��ޤ���</td></tr>';
	}else{
		for ( $i = 0; $i < count( $ip ); $i++ ) {
      $ip[$i] = ereg_replace( "\n$", "", $ip[$i] );
      $ip[$i] = ereg_replace( "\r$", "", $ip[$i] );
      list($ipaddr, $ipdate) = explode("<>", $ip[$i]);
      echo '
      <form action="'.PHP_SELF.'?mode=ip&action=cm_delete&ip='.$ipaddr.'" method=post>
      <tr align="center"><td>'.$ipaddr.'</td><td>'.date("Y/m/d", mktime(0,0,0,substr($ipdate,4,2), substr($ipdate,6,2), substr($ipdate,0,4))).'</td><td align ="center"><input type="image" src="./images/trashl.gif" name="submit" alt="�����"><td align ="center"><a href="'.PHP_SELF.'?mode=ip&action=delete&iid='.$i.'"><img src="./images/trash.gif" border="0"></a></td></tr>
      </form>
      ';
    }
  }
  echo '
  </table>
<div class="mainstate">
�� �����Ȱ���� - �����IP����Υ����Ȥ����ƺ�����ޤ���<br>
�� ��ϿIP��� - ����IP�ꥹ�Ȥ���IP�������ޤ���
</div>
  </div>
	</td></tr>
  </table>
  </td>
  ';
}


/* ��Х���������� */
function mobile($qry_action, $receive, $send, $pop, $mobile_id, $mobile_pass, $from, $to, $access_time, $apop, $mobile_cok, $mobile_tok, $mobile_category) {
	if ($qry_action == "edit") {
		//�ƥ���������
		$receive = CleanStr($receive)."\r\n";
		$send = CleanStr($send)."\r\n";
		$pop = CleanStr($pop)."\r\n";
		$mobile_id = CleanStr($mobile_id)."\r\n";
		$mobile_pass = CleanStr($mobile_pass)."\r\n";
		$access_time = $access_time."\r\n";
		$apop = $apop."\r\n";
		$mobile_cok = $mobile_cok."\r\n";
		$mobile_tok = $mobile_tok."\r\n";
		$mobile_category = $mobile_category."\r\n";

		//���ե�����񤭹���
		$fp = fopen(LOGDIR."mobile.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, $receive);								// [0]
		fputs($fp, $send);									// [1]
		fputs($fp, $pop);										// [2]
		fputs($fp, $mobile_id);							// [3]
		fputs($fp, $mobile_pass);						// [4]
		fputs($fp, $access_time);						// [5]
		fputs($fp, $apop);									// [6]
		fputs($fp, $mobile_cok);						// [7]
		fputs($fp, $mobile_tok);						// [8]
		fputs($fp, $mobile_category);				// [9]

		fclose($fp);
		$inform = "���ӤΥ��ɥ쥹��Ͽ�ڤӥ����������֤�������ѹ����ޤ�����";
	}elseif($qry_action == "send"){
		$sub = "Blog����Υ�å�����";
		$sub = mbConv($sub,0,2);
		$sub = "=?iso-2022-jp?B?".base64_encode($sub)."?=";
		$mes = $from."\n";
		$mes = $mes."���Ӥ���Ѥ��ƾ嵭���ɥ쥹����̥᡼����Ƥ�����ޤ���\n";
		$mes = $mes."�����Υ᡼�륢�ɥ쥹�ˤ��ֿ����ʤ��Ǥ���������\n";
		$mes = mbConv($mes,0,2);
		$from = "From:blog";
		if (!trim($to)) {
			$inform = "�᡼�������襢�ɥ쥹����Ͽ����Ƥ��ޤ���";
		}elseif (!mail($to, $sub, $mes, $from)) {
			$inform = "�᡼�뤬�����Ǥ��ޤ���Ǥ�����";
		}else{
			$inform = "�᡼����������ޤ�����";
		}
	}else{
		$inform = "";
	}

	up_mes($inform);

  $mobile = file(LOGDIR."mobile.dat");
  if (count($mobile) == 0) $mobile = "";

  echo '
  <br>
  <table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr><td>
  <div class="mainbody">
  <div class="maintitle">��Х�����������</div><br>
  <div class="linktitle">�� ���ѥ᡼�륢�ɥ쥹 ��</div>
  <table border="0" cellspacing="0" cellpadding="0" class="logbody">
  <form action="'.PHP_SELF.'?mode=mobile&action=edit" method=post>
	<tr><td>
  <table border="1" cellspacing="0" cellpadding="5" class="logbody">
  <tr><td width="150" align="center" bgcolor="#82BE7D">�������ɥ쥹��Ͽ</td><td width="350"><input type="text" name=receive size=50 value='.$mobile[0].'></td></tr>
  <tr><td width="150" align="center" bgcolor="#82BE7D">POP3�����С�</td><td width="350"><input type="text" name=pop size=50 value='.$mobile[2].'></td></tr>
  <tr><td width="150" align="center" bgcolor="#82BE7D">�桼����ID</td><td width="350"><input type="text" name=mobile_id size=50 value='.$mobile[3].'></td></tr>
  <tr><td width="150" align="center" bgcolor="#82BE7D">�ѥ����</td><td width="350"><input type="password" name=mobile_pass size=10 value='.$mobile[4].'></td></tr>
  <tr><td width="150" align="center" bgcolor="#82BE7D">�������ɥ쥹��Ͽ</td><td width="350"><input type="text" name=send size=50 value='.$mobile[1].'></td></tr>
  <tr><td width="150" align="center" bgcolor="#82BE7D">�᡼�륢�������ֳ�(ʬ)</td><td width="350">
	<select name="access_time">';
	$access_time = array(1,2,3,4,5,6,7,8,9,10,15,30,60); 
	foreach ($access_time as $i) { 
		if ($mobile[5] == $i) {
			echo '<option value="'.$i.'" selected>'.$i.'</option>';
		}else{
			echo '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	echo '
</select>
</td></tr>
	<tr><td width="150" align="center" bgcolor="#82BE7D">ǧ������</td><td width="350">
	<input type="radio" name="apop" value="0"';
	if ($mobile[6] == 0) {
		echo " checked";
	}
	echo '>ɸ��
	<input type="radio" name="apop" value="1"';
	if ($mobile[6] == 1) {
		echo "checked";
	}
	echo '>APOP
</td></tr>
	<tr><td width="150" align="center" bgcolor="#82BE7D">������</td><td width="350">
	<select name=mobile_cok>';
	if ($mobile[7] != 1) {
		echo '<option value="0" selected>���դ����</option>
		<option value="1">���դ����</option>';
	}else{
		echo '<option value="0">���դ����</option>
		<option value="1" selected>���դ����</option>';
	}
	echo '</select></td></tr>
	<tr><td width="150" align="center" bgcolor="#82BE7D">�ȥ�å��Хå�</td><td width="350">
	<select name=mobile_tok>';
	if ($mobile[8] != 1) {
		echo '<option value="0" selected>���դ����</option>
		<option value="1">���դ����</option>';
	}else{
		echo '<option value="0">���դ����</option>
		<option value="1" selected>���դ����</option>';
	}
	echo '</select></td></tr>
	<tr><td width="150" align="center" bgcolor="#82BE7D">���ƥ���</td><td width="350">
 	<select name=mobile_category>';
	if (file_exists(LOGDIR."category.dat")){
		$category = file(LOGDIR."category.dat");
		echo '<option value="-1">����ʤ�</option>';
		if (count($category) > 0) {
			for ( $i = 0; $i < count( $category ); $i++ ) {
				$category[$i] = ereg_replace( "\n$", "", $category[$i] );
				$category[$i] = ereg_replace( "\r$", "", $category[$i] );
				list($cid, $c_name) = explode("<>", $category[$i]);
				echo '<option value="'.$cid.'"';
				if (trim((string)$mobile[9]) == $cid) echo ' selected';
				echo '>'.$c_name.'</option>';
			}
		}
	}
	echo '</select>
</td></tr>
  </table>
	</td></tr>
	<tr><td align="right">
  <input type="submit" value="��Ͽ����">
	</td></tr>
  </form>
	</table>
	<br>
  <div class="linktitle">�� ���Ӥ���Ͽ���ɥ쥹��ž������ ��</div>
  <table border="0" cellspacing="0" cellpadding="5" class="logbody">
  <form action="'.PHP_SELF.'?mode=mobile&action=send" method=post>
	<tr><td>
  <table border="1" cellspacing="0" cellpadding="5" class="logbody">
  <input type="hidden" name=from value="'.$mobile[0].'">
  <input type="hidden" name=to value="'.$mobile[1].'">
  <tr><td width="150" align="center" bgcolor="#82BE7D">�����襢�ɥ쥹</td><td width="350">'.$mobile[1].'</td></tr>
  </table>
	</td></tr>
	<tr><td align="right">
  <input type="submit" value="��������">
	</td></tr>
  </form>
	</table>
  </div>
	</td></tr>
  </table>
  </td>
  ';
}


/* ��������ɲ� */
function skinup($action, $page, $s_title, $uphtm, $upcss, $htm_name, $css_name, $qry_sid) {
	if ($action == "post") {
		$inform = "";
		if ($skin = file(LOGDIR."skin.dat")) {
			list($sid) = explode("<>",$skin[0],2);
			$sid++;
		}else{
			$sid = 0;
		}
		if(file_exists($uphtm) && file_exists($upcss)) {
			//html�ե�����Υ��åץ���
			$finfo = pathinfo($htm_name);
			if ($finfo["extension"] == "html" || $finfo["extension"] == "htm"){
				$htm_name = strtolower($sid.".html");
				$dest = SKINDIR.$htm_name;
				copy($uphtm, $dest);
				if(!is_file($dest)) {
					$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>�����Ф����ݡ��Ȥ��Ƥ��ʤ���ǽ��������ޤ���";
				}else{
					//css�ե�����Υ��åץ���
					$finfo = pathinfo($css_name);
					if ($finfo["extension"] == "css"){
						$css_name = strtolower($sid.".css");
						$dest = SKINDIR.$css_name;
						copy($upcss, $dest);
						if(!is_file($dest)) {
							$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>�����Ф����ݡ��Ȥ��Ƥ��ʤ���ǽ��������ޤ���";
						}else{
							
							//���ե�����񤭹���
							if (trim($s_title) == "") {
								$s_title = "������_".$sid;
								$inform = "������̾��̤�����ΰ١���ư�ǥ�����̾������������ޤ�����<br>";
							}
							$newskin = $sid."<>".$s_title."<>".$htm_name."<>".$css_name."\r\n";
							$fp = fopen(LOGDIR."skin.dat", "w");
							flock($fp, LOCK_EX);
							fputs($fp, $newskin);
							if ($sid != 0) {
								fputs($fp, implode('', $skin));
							}
							fclose($fp);
							$inform .= "�ե�����򥢥åץ��ɤ��ޤ�����";
						}
					}else{
						$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>CSS�ե�����ʳ��ϼ����դ��ޤ���";
					}
				}
			}else{
				$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>HTML�ե�����ʳ��ϼ����դ��ޤ���";
			}
		}else{
			$inform = "���åץ��ɤ˼��Ԥ��ޤ�����<br>�ե����뤬���򤵤�Ƥ��ޤ���";
		}
	}elseif ($action == "delete") {
		if ($skin = file(LOGDIR."skin.dat")) {
			$newskin = array();
			for ($i = 0; $i <count($skin); $i++) {
				list($sid) = explode("<>",$skin[$i],2);
				if ($sid != $qry_sid) $newskin[] = $skin[$i];
			}
			$fp = fopen(LOGDIR."skin.dat", "w");
			flock($fp, LOCK_EX);
			if (count($newskin) != 0) {
				fputs($fp, implode('', $newskin));
			}else{
				fputs($fp, "");
			}
			fclose($fp);
		}
		if (!unlink(SKINDIR.$qry_sid.".html") || !unlink(SKINDIR.$qry_sid.".css")) {
			$inform = "������ե�����κ���˼��Ԥ��ޤ�����";
		}else{
			$inform = "������ե�����������ޤ�����";
		}
	}else{
		$inform = "";
	}
	up_mes($inform);
	if ($page == "") $page = 0;
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">��������ɲ��Խ�</div><br>
	<table width="500" border="0" cellspacing="0" cellpadding="3" class="logbody">
	<form action="'.PHP_SELF.'?mode=skinup&action=post" enctype="multipart/form-data" method=post>
	<tr>
	<td colspan="2">
	<div class="linktitle">�� ������̾ ��</div>
	<input type="text" name=s_title size=50>
	</td></tr>
	<tr>
	<td width="250">
	<div class="linktitle">�� HTML���åץ��� ��</div>
	<input type="file" name=s_htm accept="text/html" size=30>
	</td>
	<td width="250">
	<div class="linktitle">�� CSS���åץ��� ��</div>
	<input type="file" name=s_css accept="text/css" size=30>
	</td>
	</tr>
	<tr><td>
	<div class="mainstate">�� �ե���������� .html��.htm �Ǥ��ꤤ���ޤ���</div>
	</td>
	<td>
	<div class="mainstate">�� �ե���������� .css �Ǥ��ꤤ���ޤ���</div>
	</td></tr>
	<tr><td colspan="2">
	<input type="submit" value="�ɲä���">
	</td></tr>
	</form>
	</table>
  <br>
	<div class="linktitle">�� ���åץ��ɤ���Ƥ��륹������� ��</div>
	<table border="1" cellspacing="0" cellpadding="5" class="logbody">
	<tr align="center" bgcolor="#82BE7D"><td width="100">������̾</td><td width="125">HTML������</td><td width="125">CSS������</td><td width="50">�Խ�</td><td width="50">���</td></tr>
	';

	//$page����ɽ�����ϰ��֤򻻽�
	$pcnt = $page * 10;	//10���1�ڡ���ɽ��
	$page_st = 10 * $page + 1;
	$page_ed = $page_st + 10;
	$loopcount = 0;
	if ($skin = file(LOGDIR."skin.dat")) {
		for ($j = $page_st; $j < $page_ed; $j++) {
			$skin[$j-1] = ereg_replace( "\n$", "", $skin[$j-1] );
			$skin[$j-1] = ereg_replace( "\r$", "", $skin[$j-1] );
			list($sid, $s_title, $htm_name, $css_name) = explode("<>",$skin[$j-1]);
			$htm_time = date('Y/m/d H:i', filemtime(SKINDIR.$htm_name));
			$css_time = date('Y/m/d H:i', filemtime(SKINDIR.$css_name));
			echo '
			<form action="'.PHP_SELF.'?mode=skinlist&action=edit&sid='.$sid.'" method=post>
			<tr><td><input type="text" style="width:90px;" name="s_title" readonly="readonly" value="'.$s_title.'"></td><td align="center">'.$htm_time.'</td><td align="center">'.$css_time.'</td><td align="center"><input type="image" src="./images/edit.gif" name="submit" alt="�Խ�"></td><td align="center"><a href="'.PHP_SELF.'?mode=skinup&action=delete&sid='.$sid.'"><img src="./images/trash.gif" border="0"></a></td></tr>
			</form>
			';
			$loopcnt++;
			if ($j >= count($skin)) break;
		}
	}else{
		echo '<tr><td colspan="5" align="center">��Ͽ����Ƥ��륹����Ϥ���ޤ���</td></tr>';
	}
	echo '</table>';

	$pcnt_start = $pcnt + 1;  		// ɽ�����ϰ���
	$pcnt_end = $pcnt + $loopcnt;		// ɽ����λ����
	$bpage = $page - 1;			// ���ڡ���
	$npage = $page + 1;			// ���ڡ���
	if (count($skin) != 0) {
		$mpage = ceil(count($skin) / 10);
	}else{
		$mpage = 0;
	}

	if (count($skin) != 0) {
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		if ($page > 0 && $page < $mpage - 1) {
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=skinup&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=skinup&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page > 0 && $page == $mpage - 1) { 
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=skinup&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}elseif($page == 0 && $page < $mpage - 1) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=skinup&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page == 0 && $page == $mpage - 1) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}
		echo '</table><br>';
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		echo '<tr><td align="center">Page: ';
		for ($i = 0; $i < $mpage; $i++) {
			$j = $i + 1;
			if ($i == $page) {
				echo '<a href='.PHP_SELF.'?mode=skinup&page='.$i.'>['.$j.']</a> ';
			}else{
				echo '<a href='.PHP_SELF.'?mode=skinup&page='.$i.'>'.$j.'</a> ';
			}
		}
		echo '</td></tr></table><br>';
	}
	
	echo '
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* �������Ѳ����Υ��åץ��� */
function skinfile($action, $page, $upfile, $upfile_name, $qry_file) {
	$inform = "";
	if ($action == "post") {
		if (!$upfile_name) {
			$inform = "���åץ��ɤ���ե�����̾�����Ϥ��Ƥ����ɲåܥ���򲡤��Ƥ���������";
		}elseif(ereg("[\xA1-\xFE]", $upfile_name)) {
			$inform = "���ܸ�ʸ����ޤ�ե�����̾�ϥ��åץ��ɤǤ��ޤ���<br>Ⱦ�ѱѿ�ʸ���ǥ��åץ��ɤ��Ƥ���������";
		}else{
			$dest = SKINPICDIR.$upfile_name;
			if (file_exists($dest)) {
				$pathname = pathinfo($dest);
				$dest = SKINPICDIR.gmdate("YmdHis",time() + TIMEZONE).$pathname['extension'];
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
	}elseif ($action == "delete") {
		if (!unlink(SKINPICDIR.$qry_file)) {
			$inform = "�ե�����κ���˼��Ԥ��ޤ�����";
		}else{
			$inform = "�ե�����������ޤ�����";
		}
	}else{
		$inform = "";
	}
	up_mes($inform);
	if ($page == "") $page = 0;
	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">�������Ѳ����Υ��åץ���</div><br>
	<div class="linktitle">�� �������åץ��� ��</div>
	<table border="0" cellspacing="0" cellpadding="3" class="logbody">
	<form action="'.PHP_SELF.'?mode=skinfile&action=post" enctype="multipart/form-data" method=post>
	<tr><td>
	<input type="file" name=d_img accept="image/jpeg,image/gif,image/png" size=30>
	</td></tr>
	<tr><td>
	<input type="submit" value="�ɲä���">
	</td></tr>
	<tr><td>
	<div class="mainstate">
	�� �ե���������� .GIF��.JPG��.PNG �Ǥ��ꤤ���ޤ���<br>
	</div>
	</td></tr>
	</form>
	</table>
  <br>
	<div class="linktitle">�� ���åץ��ɤ���Ƥ���������� ��</div>
	<table border="0" cellspacing="0" cellpadding="0" class="logbody">
	<tr align="center">
	';

	//$page����ɽ�����ϰ��֤򻻽�
	$pcnt = $page * 5;	//10���1�ڡ���ɽ��
	$page_st = 5 * $page + 1;
	$page_ed = $page_st + 5;
	$i = 1;
	$exitkey = 0;

	$dir_name = dir(SKINPICDIR);
	$fname = array();
	$fsize = array();
	$ftime = array();
	$width = array();
	while($file_name = $dir_name->read()) {
		if (!is_dir($file_name)) {
			if ($size = @getimagesize(SKINPICDIR.$file_name)) {
				if ($size[2] == 1 || $size[2] == 2 || $size[2] == 3) {
					if ($size[0] < 80 && $size[1] < 80) {
						$width[] = $size[0];
						$height[] = $size[1];
					}elseif ($size[0] > $size[1]) {
						$width[] = 80;
						$height[] = round(80 * $size[1] / $size[0]);
					}else{
						$width[] = round(80 * $size[0] / $size[1]);
						$height[] = 80;
					}
					$fname[] = $file_name;
					$fsize[] = round(filesize(SKINPICDIR.$file_name) / 1024, 2);
					$ftime[] = date("Y/m/d", filemtime(SKINPICDIR.$file_name));
					$i++;
				}
			}
		}
	}
	arsort($ftime);
	$loopcount = 0;
	if (count($fname) == 0) {
		echo '<td align="center">��Ͽ����Ƥ�������Ϥ���ޤ���</td>';
	}else{
		$j = 1;
		while(list($key, $val) = each($ftime)) {
			if ($j >= $page_st && $j < $page_ed) {
				echo '
				<form action="'.PHP_SELF.'?mode=skinfile&action=delete&file='.$fname[$key].'" method=post>
				<td width="100" align="center" style="border:solid 1px;">
				<table border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="center" style="border:solid 1px;width:90px;height:90px"><img src="'.SKINPICDIR.$fname[$key].'" width="'.$width[$key].'" height="'.$height[$key].'" style="border:0px;"></td></tr></table>
				<a href="'.SKINPICDIR.$fname[$key].'" target="_blank"><input type="text" value="'.$fname[$key].'" readonly="readonly" style="border:0px;width:90px;height:20px;cursor: pointer;"></a>
				<div style="height:20px;">'.$fsize[$key].'KB</div>
				<div style="height:20px;">'.$val.'</div>
				<input type="image" src="./images/trash.gif" name="submit" alt="���">
				</td>
				</form>
				';
				$loopcnt++;
//				if ($j >= $i-1) break;
			}
			$j++;
		}
	}
	echo '</tr></table>';

	$pcnt_start = $pcnt + 1;  		// ɽ�����ϰ���
	$pcnt_end = $pcnt + $loopcnt;		// ɽ����λ����
	$bpage = $page - 1;			// ���ڡ���
	$npage = $page + 1;			// ���ڡ���
	$imgcnt = $i - 1;
	if ($imgcnt != 0) {
		$mpage = ceil($imgcnt / 5);
	}else{
		$mpage = 0;
	}

	if ($imgcnt != 0) {
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		if ($page > 0 && $page < $mpage - 1) {
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=skinfile&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=skinfile&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page > 0 && $page == $mpage - 1) { 
			echo '<tr><td align="left" width="100"><a href='.PHP_SELF.'?mode=skinfile&page='.$bpage.'>&lt;&lt;���Υڡ���</a></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}elseif($page == 0 && $page < $mpage - 1) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><a href='.PHP_SELF.'?mode=skinfile&page='.$npage.'>���Υڡ���&gt;&gt;</a></td></tr>';
		}elseif($page == 0 && $page == $mpage - 1) { 
			echo '<tr><td align="left" width="100"><img src="./images/blank.gif" width="1" height="1"></td><td align="center">�� '.$imgcnt.' ���� '.$pcnt_start.' - '.$pcnt_end.' ��ɽ��</td><td align="right" width="100"><img src="./images/blank.gif" width="1" height="1"></td></tr>';
		}
		echo '</table><br>';
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0" class="logbody">';
		echo '<tr><td align="center">Page: ';
		for ($i = 0; $i < $mpage; $i++) {
			$j = $i + 1;
			if ($i == $page) {
				echo '<a href='.PHP_SELF.'?mode=skinfile&page='.$i.'>['.$j.']</a> ';
			}else{
				echo '<a href='.PHP_SELF.'?mode=skinfile&page='.$i.'>'.$j.'</a> ';
			}
		}
		echo '</td></tr></table><br>';
	}
	
	echo '
	</div>
	</td></tr>
	</table>
	</td>
	';
}


/* ������Υ������ޥ��� */
function skinlist($action, $htm_text, $css_text, $s_title, $qry_sid) {
	if ($action == "htmcss") {
		if ($skin = file(LOGDIR."skin.dat")) {
			for ($i = 0; $i <count($skin); $i++) {
				list($sid) = explode("<>",$skin[$i],2);
				if ($sid == $qry_sid) {
					$skin[$i] = ereg_replace( "\n$", "", $skin[$i] );
					$skin[$i] = ereg_replace( "\r$", "", $skin[$i] );
					list($old_sid, $old_title, $htm_name, $css_name) = explode("<>",$skin[$i]);
					$skin[$i] = $sid."<>".$s_title."<>".$htm_name."<>".$css_name."\r\n";
					break;
				}
			}
			$fp = fopen(LOGDIR."skin.dat", "w");
			flock($fp, LOCK_EX);
			fputs($fp, implode('', $skin));
			fclose($fp);
		}

		$s_htm  = stripslashes($htm_text);
		$s_css  = stripslashes($css_text);
		if (CHARSET == 0) {
			// Shift_JIS
			$s_htm = mbConv($s_htm,1,2);
			$s_css = mbConv($s_css,1,2);
		}elseif (CHARSET == 1) {
			// EUC-JP
			$s_htm = mbConv($s_htm,1,1);
			$s_css = mbConv($s_css,1,1);
		}elseif (CHARSET == 2) {
			// UTF-8
			$s_htm = mbConv($s_htm,1,4);
			$s_css = mbConv($s_css,1,4);
		}

		$htmstr = $qry_sid.".html";
		if (unlink(SKINDIR.$htmstr)) {
			$fp = fopen(SKINDIR.$htmstr, "w");
			flock($fp, LOCK_EX);
			fputs($fp, $s_htm);
			fclose($fp);
		}
		$cssstr = $qry_sid.".css";
		if (unlink(SKINDIR.$cssstr)) {
			$fp = fopen(SKINDIR.$cssstr, "w");
			flock($fp, LOCK_EX);
			fputs($fp, $s_css);
			fclose($fp);
		}

		$inform .= "HTML/CSS�ե�����򹹿����ޤ�����";

		$htm_text  = CleanStr($htm_text);
		$css_text  = CleanStr($css_text);
		// ����ʸ�������졣 
		$htm_text = str_replace( "&nbsp;", " ", $htm_text);		// &nbsp �򥹥ڡ������Ѵ�����
		$css_text = str_replace( "&nbsp;", " ", $css_text);		// &nbsp �򥹥ڡ������Ѵ�����

	}elseif($action == "edit") {
		if ($htm_skin = file(SKINDIR.$qry_sid.".html")) {
			$htm_text = implode('', $htm_skin);
			$htm_text = mbConv($htm_text,0,1);
		}else{
			$htm_text = "";
		}
		if ($css_skin = file(SKINDIR.$qry_sid.".css")) {
			$css_text = implode('', $css_skin);
			$css_text = mbConv($css_text,0,1);
		}else{
			$css_text = "";
		}
		$htm_text  = CleanStr($htm_text);
		$css_text  = CleanStr($css_text);
		// TAB��Ⱦ�ѥ��ڡ���2ʸ�����Ѵ�
		$htm_text = preg_replace('/[\t]+?/', '  ',$htm_text);
		$css_text = preg_replace('/[\t]+?/', '  ',$css_text);
		// ����ʸ�������졣 
		$htm_text = str_replace( "&nbsp;", " ", $htm_text);		// &nbsp �򥹥ڡ������Ѵ�����
		$css_text = str_replace( "&nbsp;", " ", $css_text);		// &nbsp �򥹥ڡ������Ѵ�����
	}
	up_mes($inform);

	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0" class="logbody">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">��������Խ�</div><br>
	<table width="500" border="0" cellspacing="0" cellpadding="3">
	<form action="'.PHP_SELF.'?mode=skinlist&action=htmcss&sid='.$qry_sid.'" method=post>
	<tr><td>
	<div class="linktitle">�� ������̾ ��</div>
	<input type="text" name=s_title size=50 value="'.$s_title.'">
	</td></tr>
	<tr><td><br>
	<div class="linktitle">�� HTML ��</div>
	<textarea style="WIDTH: 500px;font-size:12px;" id="htm_text" name="htm_text" rows="15" cols="50" wrap="virtual">'.tagreplaceStr($htm_text).'</textarea>
	</td></tr>
	<tr><td><br>
	<div class="linktitle">�� CSS ��</div>
	<textarea style="WIDTH: 500px;font-size:12px;" id="css_text" name="css_text" rows="15" cols="50" wrap="virtual">'.tagreplaceStr($css_text).'</textarea>
	<div align="center"><input type="submit" name="htmcss" value="HTML/CSS�򹹿�����"></div>
	</td></tr>
	</form>
	</table>
	</td></tr>
	</table>
	';
}


/* ���ѥ��������Ͽ */
function skinset($action, $vid, $nskin, $rskin, $dskin, $sskin, $pskin, $iskin, $mskin, $cskin) {
	$inform = "";
	if ($action == "set") {
		$skinview = array();
		if ($vid == 0) {
			// �Ρ��ޥ�ɽ����
			$skinview[0] = "0<>".$nskin."<>\r\n";
			$inform = "���ѥ��������Ͽ�򤷤ޤ��������Ρ��ޥ�ɽ��";
		}elseif ($vid == 1) {
			// ������ɽ����
			$i = 0;
			while(list($key,$val) = each($rskin)) {
				$skinview[$i] = "1<>".$key."<>\r\n";
				$i++;
			}
			$inform = "���ѥ��������Ͽ�򤷤ޤ�������������ɽ��";
		}elseif ($vid == 2) {
			// ��������ɽ����
			$skinview[0] = "2<>".$dskin."<>\r\n";
			$skinview[1] = "2<>".$sskin."<>\r\n";
			$skinview[2] = "2<>".$pskin."<>\r\n";
			$skinview[3] = "2<>".$iskin."<>\r\n";
			for ($i = 0; $i < 12; $i++) {
				$skinview[$i+4] = "2<>".$mskin[$i]."<>\r\n";
			}
			$i = 16;
			while(list($key,$val) = each($cskin)) {
				$skinview[$i] = "2<>".$val."<>".$key."\r\n";
				$i++;
			}
			$inform = "���ѥ��������Ͽ�򤷤ޤ���������������ɽ��";
		}
		$fp = fopen(LOGDIR."skinview.dat", "w");
		flock($fp, LOCK_EX);
		fputs($fp, implode("",$skinview));
		fclose($fp);
	}

	if ($skinview = file(LOGDIR."skinview.dat")) {
		for ($i = 0; $i < count($skinview); $i++) {
			$skinview[$i] = ereg_replace( "\n$", "", $skinview[$i] );
			$skinview[$i] = ereg_replace( "\r$", "", $skinview[$i] );
			list($vid, $v_sid[$i], $v_cid[$i]) = explode("<>",$skinview[$i]);
		}
	}
	if ($skin = file(LOGDIR."skin.dat")) {
		for ($i = 0; $i <count($skin); $i++) {
			list($sid[$i],$sname[$i]) = explode("<>",$skin[$i],3);
		}
	}else{
		$inform .= "��������Ͽ����Ƥ��ޤ���<br>��������ɲä��Ƥ�����ѥ��������Ͽ���Ƥ���������";
	}
	up_mes($inform);

	echo '
	<br>
	<table width="550" border="0" cellspacing="0" cellpadding="0" class="logbody">
	<tr><td>
	<div class="mainbody">
	<div class="maintitle">���ѥ��������Ͽ</div><br>
	<table width="500" border="0" cellspacing="0" cellpadding="3">
	<tr><td>
	<form action="'.PHP_SELF.'?mode=skinset&action=set" method=post>
	<table border="0" cellspacing="0" cellpadding="3" class="logbody">
	<tr><td>
	<div class="linktitle">
	';
	if ($vid == 0) {
		echo '<input type="radio" name="vid" value=0 id="normal" checked>';
	}else{
		echo '<input type="radio" name="vid" value=0 id="normal">';
	}
	echo '
	<label for="normal">�� �Ρ��ޥ�ɽ�� ��</label>
	</div>
	<div style="margin-left:20px;">
	<table width="240" border="1" cellspacing="0" cellpadding="3" class="logbody">
	<tr><td width="50" align="center" bgcolor="#82BE7D">
	����
	</td><td>
	<select name=nskin style="width:150px;">
	';
	for ($i = 0; $i <count($sid); $i++) {
		if ($vid == 0) {
			if ($v_sid[0] == $sid[$i]) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}elseif ($i == 0) {
			echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
		}else{
			echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
		}
	}
	echo '
	</select>
	</td></tr>
	</table>
	</div>
	</td></tr>
	<tr><td>
	<div class="linktitle">
	';
	if ($vid == 1) {
		echo '<input type="radio" name="vid" value=1 id="random" checked>';
	}else{
		echo '<input type="radio" name="vid" value=1 id="random">';
	}
	echo '
	<label for="random">�� ������ɽ�� ��</label>
	</div>
	<div style="margin-left:20px;">
	<table width="480" border="1" cellspacing="0" cellpadding="3" class="logbody">
	';
	for ($i = 0; $i <count($sid); $i++) {
		echo '<tr><td width="230" bgcolor="#82BE7D">'.$sname[$i].'</td><td>';
		
		if ($vid == 1) {
			$vkey = 0;
			foreach($v_sid as $val) {
				if ($val == $sid[$i]) {
					echo '<input type="checkbox" name="rskin['.$sid[$i].']" value=1 id="rskin'.$i.'" checked>';
					$vkey = 1;
				}
			}
			if ($vkey == 0) {
				echo '<input type="checkbox" name="rskin['.$sid[$i].']" value=1 id="rskin'.$i.'">';
			}
		}else{
			echo '<input type="checkbox" name="rskin['.$sid[$i].']" value=1 id="rskin'.$i.'">';
		}
		echo '<label for="rskin'.$i.'">ɽ������</label></td></tr>';
	}
	echo '
	</table>
	</div>
	</td></tr>
	<tr><td>
	<div class="linktitle">
	';
	if ($vid == 2) {
		echo '<input type="radio" name="vid" value=2 id="calendar" checked>';
	}else{
		echo '<input type="radio" name="vid" value=2 id="calendar">';
	}
	echo '
	<label for="calendar">�� ��������ɽ�� ��</label>
	</div>
	<div style="margin-left:20px;">
	<table width="480" border="1" cellspacing="0" cellpadding="3" class="logbody">
		<tr><td colspan="2">���ɽ�����������⸡��ɽ�����ץ�ե�����ɽ�������국��ɽ��</td></tr>
	<tr><td width="150" bgcolor="#82BE7D">
	���ɽ��
	</td><td>
	<select name=dskin style="width:150px;">
	';
	for ($i = 0; $i <count($sid); $i++) {
		if ($vid == 2) {
			if ($v_sid[0] == $sid[$i]) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}elseif ($i == 0) {
			echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
		}else{
			echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
		}
	}
	echo '
	</select>
	</td></tr>
	<tr><td width="150" bgcolor="#82BE7D">
	�������⸡��ɽ��
	</td><td>
	<select name=sskin style="width:150px;">
	';
	for ($i = 0; $i <count($sid); $i++) {
		if ($vid == 2) {
			if ($v_sid[1] == $sid[$i]) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}elseif ($i == 0) {
			echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
		}else{
			echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
		}
	}
	echo '
	</select>
	</td></tr>
	<tr><td width="150" bgcolor="#82BE7D">
	�ץ�ե�����ɽ��
	</td><td>
	<select name=pskin style="width:150px;">
	';
	for ($i = 0; $i <count($sid); $i++) {
		if ($vid == 2) {
			if ($v_sid[2] == $sid[$i]) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}elseif ($i == 0) {
			echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
		}else{
			echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
		}
	}
	echo '
	</select>
	</td></tr>
	<tr><td width="150" bgcolor="#82BE7D">
	���국��ɽ��
	</td><td>
	<select name=iskin style="width:150px;">
	';
	for ($i = 0; $i <count($sid); $i++) {
		if ($vid == 2) {
			if ($v_sid[3] == $sid[$i]) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}elseif ($i == 0) {
			echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
		}else{
			echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
		}
	}
	echo '
	</select>
	</td></tr>
	</table>
	<table width="480" border="1" cellspacing="0" cellpadding="3" class="logbody">
		<tr><td colspan="4">����ɽ��</td></tr>
	';

	for ($m = 0; $m < 12; $m++) {
		if ($m == 0 || $m == 2 || $m == 4 || $m == 6 || $m == 8 || $m == 10) {
			echo '<tr><td width="50" align="center" bgcolor="#82BE7D">';
		}else{
			echo '<td width="50" align="center" bgcolor="#82BE7D">';
		}
		$month = $m + 1;
		echo '
		'.$month.'��
		</td><td>
		<select name=mskin['.$m.'] style="width:150px;">
		';
		for ($i = 0; $i < count($sid); $i++) {
			if ($vid == 2) {
				if ($v_sid[$m+4] == $sid[$i]) {
					echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
				}else{
					echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
				}
			}elseif ($i == 0) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}
		echo '
		</select>
		';
		if ($m == 0 || $m == 2 || $m == 4 || $m == 6 || $m == 8 || $m == 10) {
			echo '</td>';
		}else{
			echo '</td></tr>';
		}
	}

	echo '
	</table>
	<table width="480" border="1" cellspacing="0" cellpadding="3" class="logbody">
		<tr><td colspan="2">���ƥ�����ɽ��</td></tr>
	';

	if (file_exists(LOGDIR."category.dat")){
		$category = file(LOGDIR."category.dat");
	}

	for ($c = -1; $c < count($category); $c++) {
		if ($c == -1) {
			$cid = -1;
			$cname = "����̵��";
		}else{
			$category[$c] = ereg_replace( "\n$", "", $category[$c] );
			$category[$c] = ereg_replace( "\r$", "", $category[$c] );
			list($cid, $cname) = explode("<>",$category[$c]);
		}
		echo '
		<tr><td width="230" bgcolor="#82BE7D">'.$cname.'
		</td><td>
		<select name=cskin['.$cid.'] style="width:150px;">
		';
		for ($i = 0; $i < count($sid); $i++) {
			if ($vid == 2) {
				if ($v_sid[$c+17] == $sid[$i]) {
					echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
				}else{
					echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
				}
			}elseif ($i == 0) {
				echo '<option value="'.$sid[$i].'" selected>'.$sname[$i].'</option>';
			}else{
				echo '<option value="'.$sid[$i].'">'.$sname[$i].'</option>';
			}
		}
		echo '
		</select>
		</td></tr>
		';
	}

	echo '
	</table>
	</div><br>
	<div align="center"><input type="submit" value="���������Ͽ����"></div>
	</td></tr>
	</table>
	</form>
	</td></tr>
	</table>
	</td></tr>
	</table>
	';
}


/* �ȥ�å��Хå����� */
function trackback_send($ping_url, $client_url, $title, $excerpt) {
	$return_code = "";
	$blog_name = SITENAME;
		// ����ʸ�������졣 
	$excerpt = str_replace( "<br>",  "\r\n", $excerpt); 
	$excerpt = str_replace( "<br />",  "\r\n", $excerpt);

	$excerpt = CleanHtml($excerpt);

//	$excerpt = nl2br($excerpt);
//	$excerpt = str_replace("\n", "", $excerpt);
	if (strlen($excerpt) > 255) $excerpt = mbtrim($excerpt,255);	// Movable Type���͡�255�Х��Ȱʾ�ξ���ά
	$post = "title=".$title."&url=".$client_url."&excerpt=".$excerpt."&blog_name=".$blog_name;
	$post = mbConv($post,0,4);

	$ping_url = str_replace("\r$", "", $ping_url);
	$ping_url = ereg_replace("\n$", "", $ping_url);
	$ping_urls = explode("\n", $ping_url);
	$return_code = "";
	for ($i = 0; $i < count($ping_urls); $i++) {
		$cnt = $i + 1;
		$ping = parse_url($ping_urls[$i]);
		$req  = "POST ".$ping_urls[$i]." HTTP/1.0\r\n";
		$req .= "Host: ".$ping['host']."\r\n";
		$req .= "User-Agent:blog-trackback\r\n";
		$req .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$req .= "Content-Length: ".strlen($post)."\r\n";
		$req .= "\r\n";
		$req .= $post."\r\n";
		if (!$ping["port"]) $ping["port"] = 80;
		$fp = fsockopen($ping["host"], $ping["port"], $errno, $errstr, 10);
		if ($fp) {
			fputs ($fp, $req);
			while (!feof($fp)) {
				$res = fgets($fp, 4096);
			}
			fclose($fp);
			$str = $res;
			if(preg_match("/<error>0<\/error>/",$str) == 0){
				$return_code .= "<br>�ȥ�å��Хå�����".$cnt."���ܡ�������λ";
			}else{
				$return_code .= "<br>�ȥ�å��Хå�����".$cnt."���ܡ�������λ";
			}
		}else{
			$return_code .= "<br>�ȥ�å��Хå�����".$cnt."���ܡ�CONNECTION ERROR";
		}
	}
	return $return_code;

}


/* weblogUpdates.ping ���� */
function ping_send($ping_url){
	$return_code = "";
	$blog_name = SITENAME;
	$port = 80;
	$return_code = "";
	for ($i = 0; $i < count($ping_url); $i++) {
		$ping_url[$i] = str_replace("\r$", "", $ping_url[$i]);
		$ping_url[$i] = ereg_replace("\n$", "", $ping_url[$i]);
		$cnt = $i + 1;
		$post = '<?xml version="1.0" encoding="UTF-8" ?>
<methodCall>
<methodName>weblogUpdates.ping</methodName>
<params>
<param>
<value>'.$blog_name.'</value>
</param>
<param>
<value>'.HOMELINK.'</value>
</param>
</params>
</methodCall>';
		$post = mbConv($post,0,4);
		$ping = parse_url($ping_url[$i]);
		$req  = "POST ".$ping_url[$i]." HTTP/1.0\r\n";
		$req .= "Host: ".$ping['host']."\r\n";
		$req .= "User-Agent:blog-send-ping\r\n";
		$req .= "Content-Type: text/xml\r\n";
		$req .= "Content-Length: ".strlen($post)."\r\n";
		$req .= "\r\n";
		$req .= $post."\r\n";
		$fp = fsockopen($ping["host"], $port, $errno, $errstr, 10);
		if ($fp) {
			fputs ($fp, $req);
			while (!feof($fp)) {
				$res = fgets($fp, 4096);
			}
			fclose($fp);
			$return_code .= "<br>Ping����".$cnt."���ܡ�������λ";
		}else{
			$return_code .= "<br>Ping����".$cnt."���ܡ�CONNECTION ERROR";
		}
	}
	return $return_code;
}


?>
