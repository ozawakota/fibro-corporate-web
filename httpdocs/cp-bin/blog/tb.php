<?php
//-------------------------------------------------------------------------
//
// Trackback����
//
// LAST UPDATE 2004/10/14
//
// ���ȥ�å��Хå����Υ᡼�����URL�����������ä��Τ���
//-------------------------------------------------------------------------
require_once("common.php");

$tmode = $_GET["__mode"];
if (!$tmode) {
	/* ----- Trackback�����ᥤ��롼���� ----- */
	if (!get_tb_id()) {
		res_xml(1,"No TrackBack ID (tb_id)");	// TrackBack ID��̵�����ϥ��顼��λ
	}else{
		$no = get_tb_id();
	}

	// ���ե������ɤ߹���
	$loglist = IDCheck($no, 0);
	$log = file(LOGDIR.substr($loglist[0], 3, 4)."/".$loglist[0]);
	list(, , , , , , , , , $d_tok) = explode("<>", $log[IDSearch($no, $log)]);
	$d_tok = ereg_replace( "\n$", "", $d_tok);
	$d_tok = ereg_replace( "\r$", "", $d_tok);

	/* �ȥ�å��Хå����ĳ�ǧ */
	if ($d_tok != "0") res_xml(1,"TrackBack is not Permitted");

	if (!$_POST["url"]) {
		res_xml(1,"No URL (url)");	// url��̵�����ϥ��顼��λ
	}else{
		$url = $_POST["url"];
	}
	if (!$_POST["title"]) {
		$title = $url;
	}else{
		$title = $_POST["title"];
	}
	$title = mbConv($title,0,1);
	$excerpt = ($_POST["excerpt"] ? $_POST["excerpt"] : "");
	$excerpt = mbConv($excerpt,0,1);
	$excerpt = CleanHtml($excerpt);
	if (strlen($excerpt) > 255) $excerpt = mbtrim($excerpt,252)."...";	// Movable Type���͡�255�Х��Ȱʾ�ξ���ά
	$excerpt = rntobr($excerpt);
	$excerpt = strip_tags($excerpt);
	$blog_name = ($_POST["blog_name"] ? $_POST["blog_name"] : "");
	$blog_name = mbConv($blog_name,0,1);
	$ip_addr = $_SERVER["REMOTE_ADDR"];
	$user_agant = $_SERVER["HTTP_USER_AGENT"];
	$user_agant = mbConv($user_agant,0,1);
	$t_date = gmdate("Ymd", time()+TIMEZONE);
	$t_time = gmdate("Hi", time()+TIMEZONE);
	$trk = $no."<>".$blog_name."<>".$title."<>".$url."<>".$excerpt."<>".$t_date."<>".$t_time."<>".$ip_addr."<>".$user_agant."\r\n";
	$trkname = "trk".substr($t_date,0,6).".dat";

	if ($err = FileSearch($trkname)) {
		if ($oldtrk = @file(LOGDIR.substr($t_date,0,4)."/".$trkname)) {
			array_splice($oldtrk, 0, 0, $trk);
			$fp = fopen(LOGDIR.substr($t_date, 0, 4)."/".$trkname, "w");
			flock($fp, LOCK_EX);
			fputs($fp, implode('',$oldtrk));
			fclose($fp);
		}else{
			$fp = fopen(LOGDIR.substr($t_date, 0, 4)."/".$trkname, "w");
			flock($fp, LOCK_EX);
			fputs($fp, $trk);
			fclose($fp);
		}
		//�ȥ�å��Хå��������˻���᡼�륢�ɥ쥹��Ϣ��
		if (TINFO == 1 && trim(MADDRESS) != "") {
			if ($loglist = IDCheck($no,0)) {
				$log = file(LOGDIR.substr($loglist[0],3,4)."/".$loglist[0]);
				list($eid, , , , , $d_title, , , , ) = explode("<>", $log[IDSearch($no, $log)]);
				$sub = "�ȥ�å��Хå���������ޤ���";
				$sub = mbConv($sub,0,2);
				$sub = "=?iso-2022-jp?B?".base64_encode($sub)."?=";
				$mes = "��̾:".$d_title."\n";
				$mes .= "��ƥ�����̾:".$blog_name."\n";

//				$urilen = strlen(HOMELINK);
//				$reqfile = substr(strrchr(substr(HOMELINK, 0, $urilen - 1), "/"),1);
//				$reqfilelen = strlen($reqfile);
//				$reqdir = substr( HOMELINK, 0, $urilen - $reqfilelen - 1) ;

				$mes .= "URL:".HOMELINK."index.php?eid=".$eid."#trackback\n";
				$mes .= "�����Υ᡼�륢�ɥ쥹�ˤ��ֿ����ʤ��Ǥ���������";
				$mes = mbConv($mes,0,2);
				$from = "From:\"".SITENAME."\" <blog@localhost>";
				@mail(MADDRESS, $sub, $mes, $from);
			}
		}
	}

	res_xml(0,"");
	res_xml(1,"NO TrackBack ID(BLOG ID)");
}elseif ($tmode == "rss") {
	if (!get_tb_id()) {
		res_xml(1,"No TrackBack ID (tb_id)");	// TrackBack ID��̵�����ϥ��顼��λ
	}else{
		$no = get_tb_id();
	}

	// ���ե������ɤ߹���
	$loglist = IDCheck($no, 0);
	$log = file(LOGDIR.substr($loglist[0], 3, 4)."/".$loglist[0]);
	list(, , , , , , , , , $d_tok) = explode("<>", $log[IDSearch($no, $log)]);
	$d_tok = ereg_replace( "\n$", "", $d_tok);
	$d_tok = ereg_replace( "\r$", "", $d_tok);
	/* �ȥ�å��Хå����ĳ�ǧ */
	if ($d_tok != "0") res_xml(1,"TrackBack is not Permitted");
	$urilen = strlen(HOMELINK);
	$reqfile = substr(strrchr(substr(HOMELINK, 0, $urilen - 1), "/"),1);
	$reqfilelen = strlen($reqfile);
	$reqdir = substr( HOMELINK, 0, $urilen - $reqfilelen - 1) ;
	$val = '<rss version="0.91"><channel><title>'.$d_title.'</title><link>'.$reqdir.'?eid='.$eid.'</link><description>'.$d_mes.'</description><language>ja</language></channel></rss>';
	res_xml(0,$val);
	res_xml(1,"NO TrackBack ID(BLOG ID)");
}
exit;


/* ----- �ȥ�å��Хå�ID���� ----- */
function get_tb_id() {
	if (TTYPE != 1) {
		if ($pi = $_SERVER['PATH_INFO']) {
			$tb_id = substr($pi, strrpos($pi, "/") + 1, strlen($pi));
		}
	}else{
		$tb_id = $_SERVER['QUERY_STRING'];
	}
	return $tb_id;
}


/* ----- �����С��쥹�ݥ� ----- */
function res_xml($key,$val) {
	$dat = '<?xml version="1.0" encoding="UTF-8" ?><response>';
	if($key == 1) {
		$dat .= '<error>1</error><br /><message>'.$val.'</message>';
	}else{
		$dat .= '<error>0</error>'.($val ? $val : '');
	}
	$dat .= '</response>';
	$dat = mbConv($dat,0,4);
	echo $dat;
	exit;
}

?>
