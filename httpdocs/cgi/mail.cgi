#!/usr/bin/perl

require'./jcode.pl';
require'./mimew.pl';
require'./mail_conf.pl';

###############################################
# mail.cgi
# v1.0 (2004.09.27)
# v1.1 (2004.11.05)：BUG FIX 添付ファイル名文字化け
# v1.2 (2004.11.07)：CSV記録機能, 排他ロック機能追加
# v1.3 (2004.11.19)：CSV記録に日付追加
# (c) m.s. @ freischtide
# フォームメールIF

# フォームからの入力読み込み
&get_form;

# モードによる動作判定
if($mode eq ''){
	if($confirm_key){
		&confirm;
	}else{
		&send;
	}
}
elsif($mode eq 'confirm'){&confirm;}
elsif($mode eq 'send'){&send;}
exit;

#-------------------------------------
#----送信
#-------------------------------------
sub send{

	if($confirm_key){#確認画面ありの場合の添付ファイル処理
		foreach $file (grep(/^file\d+$/,(keys %in))){
			$FILE_PATH{$file} = $in{"path_$file"};
			$FILE_NAME{$file} = $in{"name_$file"};
			binmode(FILE);
			open(FILE,$FILE_PATH{$file}) || &error("ファイルを開けません");
			while(<FILE>){
				$FILE_DATA{$file} .= $_;
			}
			close(FILE);
		}
	}
	
	#チェックが冗長になるため、やめ	
	#&chk_req;#必須項目チェック
	if($csv_key){&add_to_csv;}#csvファイルに加える

	&send_mail;#メール送信

	&delete_old_file;
	
	&jump("$jumpto");#送信完了画面へ
}


#-------------------------------------
#----メール送信
#-------------------------------------
sub send_mail{
	open(TEMPLATE,$template) || &error("テンプレートファイルを開けません");
	@allbody = <TEMPLATE>;
	close(TEMPLATE);
	
	my $boundary = "boundary";#区切り
	
	my $subject = shift(@allbody);
	my $body = join("",@allbody);
	
	foreach $key (keys %in){#テンプレートと入力項目から本文作成
		if($confirm_key){#確認画面のURLエスケープを復元
			$in{$key} =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		}
		$in{$key} =~ s/\r\n/\n/g;
		$in{$key} =~ s/\r/\n/g;

		$body =~ s/\Q<#$key#>\E/$in{$key}/g;
	}
	
	#チェックが冗長のためやめ
	#メールアドレスチェック		
	#&chk_mail($in{mail});
	
	#ホスト・IP表示	
	my($host,$ip) = &get_host;
	$body =~ s/\<\#host\#\>/$host/g;
	$body =~ s/\<\#ip\#\>/$ip/g;
	
	#未入力項目のタグ削除
	$body =~ s/\<\#.*\#\>//g;
	
	#長さチェック
	if(length($body) > $max_length){&error("最大文字数を超えています");}
	
	
	#件名エンコード
	$subject = &mimeencode(jcode'jis($subject));
	$subject =~ s/\n//g;
	
	#本文エンコード
	$body = jcode'jis($body);

	undef(@allbody);
	
	my $file_flag = 0;
	my $file_size = 0;
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){#添付ファイルの有無判定
		if($FILE_DATA{$file}){
			$file_flag++;
			$file_size += length($FILE_DATA{$file});#ファイルサイズ合計加算
		}
	}
	
	if($file_size > $max_file_size){&error("ファイルサイズの合計が大きすぎます");}

	if(!$in{mail}){
		open(MAIL,"|$sendmail -t -i -f $mailfrom") || &error("SENDMAILを開けません");
	}else{
		open(MAIL,"|$sendmail -t -i -f $in{mail}") || &error("SENDMAILを開けません");
	}

	
	if($file_flag){#添付ファイル有り
		
		#ヘッダ
		print MAIL "Mime-Version: 1.0\n";
		print MAIL "Content-Type: Multipart/Mixed; boundary=\"$boundary\"\n";
		print MAIL "Content-Transfer-Encoding: Base64\n";
		print MAIL "From: $in{mailto}\n"; 
		print MAIL "To: $in{mail}\n";
		if($copy_key){
			print MAIL "Cc: $in{mailto}\n";
		}
		print MAIL "Subject: $subject\n";
		
		#本文
		print MAIL "--$boundary\n";
		print MAIL "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
		print MAIL "\n";
		print MAIL "$body\n";
	
		#添付ファイル
		foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){ 	
			if($FILE_DATA{$file}){
				#$path = &save_file($FILE_DATA{$file},$FILE_NAME{$file});
				
				#添付ファイル名エンコード
				$FILE_NAME{$file} = jcode'jis($FILE_NAME{$file});
		
				#添付ファイルエンコード
				$FILE_DATA{$file} = &bodyencode($FILE_DATA{$file});
				$FILE_DATA{$file} .= &benflush;
				
				print MAIL "--$boundary\n";
				print MAIL "Content-Type: application/octet-stream; name=\"$FILE_NAME{$file}\"\n";
				print MAIL "Content-Transfer-Encoding: base64\n";
				print MAIL "Content-Disposition: attachment; filename=\"$FILE_NAME{$file}\"\n";
				print MAIL "\n";
				print MAIL "$FILE_DATA{$file}\n";
				print MAIL "\n";
	
			}
		}
	
	
		#マルチパート終了
		print MAIL "--$boundary--\n";
		
		#一時保存添付ファイル削除
		foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){#添付ファイルの有無判定
			if(-e $FILE_PATH{$file}){unlink $FILE_PATH{$file};}
		}
		
	}else{#添付ファイル無し
		
		#ヘッダ
		print MAIL "Mime-Version: 1.0\n";
		print MAIL "Content-Type: text/plain; charset=ISO-2022-JP\n";		
		print MAIL "Content-Transfer-Encoding: 7bit\n";
		print MAIL "From: $in{mailto}\n"; 
		print MAIL "To: $in{mail}\n";
		if($copy_key){
			print MAIL "Cc: $in{mailto}\n";
		}
		print MAIL "Subject: $subject\n";
		print MAIL "\n";
		
		#本文
		print MAIL $body."\n";
		
	}
	close MAIL;

}


#-------------------------------------
#----古い一時保存ファイルを削除
#-------------------------------------
sub delete_old_file{
	my $file;
	opendir(DIR,$file_dir);
	while($file = readdir(DIR)){
		if($file eq '.' || $file eq '..'){next;}
		if(time - (stat("$file_dir/$file"))[9] > 1*60*60){
			unlink("$file_dir/$file") || &error("$file_dir/$fileを削除できません");
		}
		
	}
	closedir(DIR);
}


#-------------------------------------
#----確認画面
#-------------------------------------
sub confirm{

	&chk_req;#必須項目チェック
	
	my @files;
	my $file_flag = 0;
	
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){
		if($FILE_DATA{$file} ne ''){
			$path = &save_file($FILE_DATA{$file},$FILE_NAME{$file});
			$FILE_PATH{$file} = $path;
			$file_flag++;
		}
	}
	
	print"Content-type: text/html\n\n";
	
	open(TEMP2,"$template2") || &error("確認画面テンプレートを開けません");
	$body = join("",<TEMP2>);
	close(TEMP2);
		
	#各項目
	foreach $key (keys %in){
		$escaped{$key} = $in{$key};
		
		$in{$key} = &del_tag($in{$key});#タグ除去
		$in{$key} =~ s/\r\n/<br>/g;
		$in{$key} =~ s/\r/<br>/g;
		$in{$key} =~ s/\n/<br>/g;
		
		$escaped{$key} =~ s/(\W)/'%'.unpack('H2',$1)/eg;#URLエスケープ		
		$hidden = "<input type=\"hidden\" name=\"$key\" value=\"$escaped{$key}\">";
		if($key eq "mailto"){
			$body =~ s/\Q<#$key#>\E/$hidden/g;
		} else {
			$body =~ s/\Q<#$key#>\E/$in{$key}$hidden/g;
		}
	}
	
	#添付ファイル
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){
		if($FILE_DATA{$file} eq ''){next;}
		$file_hidden .= "<a href=\"$FILE_PATH{$file}\">$FILE_NAME{$file}</a> ";
		$file_hidden .= "<input type=\"hidden\" name=\"$file\" value=\"$FILE_NAME{$file}\">";
		$file_hidden .= "<input type=\"hidden\" name=\"path_$file\" value=\"$FILE_PATH{$file}\">";
		$file_hidden .= "<input type=\"hidden\" name=\"name_$file\" value=\"$FILE_NAME{$file}\">";
	}
	$body =~ s/\<\#file\#\>/$file_hidden/g;
	
	$body =~ s/\<\#.*\#\>//g;#未入力項目のタグ削除
	

	$body =~ s/\<\/body\>//g;
	$body =~ s/\<\/html\>//g;
	print $body;
	&footer;


}


#-------------------------------------
#----フォーム入力取得
#-------------------------------------
sub get_form{
	if($ENV{'REQUEST_METHOD'} eq "POST"){
	    if($ENV{'CONTENT_TYPE'} =~ m|multipart/form-data; boundary=([^\r\n]*)$|io){
			&decode_form_multipart($1);
		}else{
			read(STDIN,$buffer,$ENV{'CONTENT_LENGTH'});
			&decode_form;
		}
	}else { 
		$buffer = $ENV{'QUERY_STRING'}; 
		&decode_form;
	}
	$mode = $in{'mode'};
}


#-------------------------------------
#----フォーム入力デコード
#-------------------------------------
sub decode_form{
	@pairs = split(/&/,$buffer);
	foreach $pair (@pairs) {
		($name,$value) = split(/=/, $pair);
		$value =~ tr/+/ /;
		$value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		$name =~ tr/+/ /;
		$name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		&jcode'convert(*value,'sjis');
		
		#$value = &del_tag($value);
		
		#同じname属性の場合
		if($in{$name} ne ''){
			$in{$name} .= " ".$value;
		}else{
			$in{$name} = $value;
		}
	}
}


#-------------------------------------
#----マルチパートフォームデコード
#-------------------------------------
sub decode_form_multipart{
	my($bound) = @_;
 	my($que,$remain,$tmp,@arr);
	$CRLF = "\r\n";
	$que = "$CRLF";
	$remain = $ENV{'CONTENT_LENGTH'};

	binmode(STDIN);
	while($remain){
		$remain -= sysread(STDIN,$tmp,$remain) || &error($!);
		$que .= $tmp;
	}

	@arr = split(/$CRLF-*$bound-*$CRLF/,$que);
	shift(@arr);
	foreach(@arr){
		$tmp = $_;

		if(/^Content-Disposition: [^;]*; name="[^;]*"; filename="[^;]*"/io){
			$tmp =~ s/^Content-Disposition: ([^;]*); name="([^;]*)"; filename="([^;]*)"($CRLF)Content-Type: ([^;]*)$CRLF$CRLF//io;
			$FILE_DATA{$2} = $tmp;
			$FILE_NAME{$2} = $3;
			$FILE_TYPE{$2} = $4;

		}elsif(/^Content-Disposition: [^;]*; name="[^;]*"/io){
			$tmp =~ s/^Content-Disposition: [^;]*; name="([^;]*)"$CRLF$CRLF//io;
			&jcode::convert(\$tmp,'sjis');
			&jcode::convert(\$1,'sjis');

			#$tmp = &del_tag($tmp);
			
			#同じname属性の場合
			if($in{$1} ne ''){
				$in{$1} .= " ".$tmp;
			}else{
				$in{$1} = $tmp;
			}
		}
	}
	
	foreach $k (keys %FILE_NAME){
		my @path = split(/\\/,$FILE_NAME{$k});
		$FILE_NAME{$k} = @path[$#path];
	}
}


#-------------------------------------
#----ファイル保存
#-------------------------------------
sub save_file{
	my($file_data,$file_name) = @_;

	open(IMG,">$file_dir/$file_name") || &error("ファイルを保存できません");
	print IMG $file_data;
	close(IMG);
	chmod 0666,"$file_dir/$file_name";

	return "$file_dir/$file_name";
}


#-------------------------------------
#----送信完了後移動
#-------------------------------------
sub jump{
	$jumpto = $_[0];
	if(!$jump_header){#Location
		print"Location:$jumpto\n\n";
	}else{#META
		print"Content-type :text/html\n\n";
		print"<html>\n";
		print"<head>\n";
		print"<meta http-equiv=\"Refresh\" content=\"0;URL=$jumpto\">\n";
		print"</head>\n";
		print"<body bgcolor=\"#ffffff\">\n";
		print"更新中・・・<Br>";
		print"しばらく待っても移動しない場合は<br>\n";
		print"<a href=\"$jumpto\">こちら</a>をクリックしてください\n";
		print"</body>\n";
		print"</html>\n";
	}
}


#-------------------------------------
#----必須項目チェック
#-------------------------------------
sub chk_req{
	foreach $req (@req){
		if($req =~ /^file\d+$/ && $FILE_DATA{$req} eq ''){#ファイルの場合
			&error("$reqが指定されていません");
		}elsif($req =~ /^mail$/){#メールの場合
			&chk_mail($in{mail},$in{mail_confirm});
		}elsif($req !~ /^file\d+$/ && $in{$req} eq ''){#ファイル,メール以外の項目
			&error("$req が入力されていません。");
		}
	}
}


#-------------------------------------
#----ヘッダ出力
#-------------------------------------
sub header{
	print"Content-Type:text/html\n\n";
	print<<"eof";
<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=Shift_JIS">
<STYLE type="text/css">
<!--
body,tr,td,th,blockquote{font-size:$font_size}
-->
</STYLE>
<title>$title</title>
</head>
$body_tag
<br>
eof
}


#-------------------------------------
#----フッタ出力
#-------------------------------------
sub footer{
	print"<hr>\n";
	print"</body>\n";
	print"</html>\n";
}


#-------------------------------------
#----メールアドレスチェック
#-------------------------------------
sub chk_mail{
	my $chk = $_[0]; 
	my $chk_cnfm = $_[1]; 
	if($chk ne $chk_cnfm){ &error("入力されたメールアドレスが一致していません");}
	if($chk eq ''){ &error("メールアドレスが入力されていません");}
	if($chk =~ /\,/){ &error("メールアドレスにコンマ（,）が含まれています"); }
	#if($chk !~ /[\w\.\-]+\@[\w\.\-]+\.[a-zA-Z]{2,3}/){
	#	&error("メールアドレスが全角で記入されているか、書式が正しくありません");
	#}
}


#-------------------------------------
#----ホスト・IP取得
#-------------------------------------
sub get_host{
	my ($ip,$host);
	$ip = $ENV{'REMOTE_ADDR'};
	$host = $ENV{'REMOTE_HOST'};
	if($host eq '' || $host eq $ip){$host = gethostbyaddr(pack("C4",split(/\./,$ip)),2);}
	if($host eq ''){$host = $ip;}
	return($host,$ip);
}


#-------------------------------------
#----タグ除去
#-------------------------------------
sub del_tag{
	my $str = $_[0];
	$str =~ s/&/&amp;/g;
	$str =~ s/,/&#44;/g;
	$str =~ s/</&lt;/g;
	$str =~ s/>/&gt;/g;
	$str =~ s/\"/&quot;/g;
	return $str;
}


#-------------------------------------
#----エラー表示
#-------------------------------------
sub error{
	if($_[1]){&unlock;}
	&header;
	print "<br>$_[0]";
	print"<br>ブラウザの戻るボタンでお戻りください。";
	&footer;
	exit;
}

#-------------------------------------
#---日時取得
#-------------------------------------
sub get_date{
        my($sec,$min,$hour,$mday,$mon,$year,$wdy,$yday,$isdst) = localtime(time);
        $mon++;
        $year += 1900;
        $year = substr($year,-2,2);
        if($mon < 10){$mon = "0$mon";}
        if($mday < 10){$mday = "0$mday";}
        if($hour < 10){$hour = "0$hour";}
        if($min < 10){$min = "0$min";}
        if($sec < 10){$sec = "0$sec";}
        $youbi = ('(Sun)','(Mon)','(Tue)','(Wed)','(Thu)','(Fri)','(Sat)')[$wdy];
        return($year,$mon,$mday,$hour,$min,$sec,$youbi);
}

#-------------------------------------
#----CSVファイルに記録
#-------------------------------------
sub add_to_csv{

	foreach $n (@csv_items){
		push(@csv,$in{$n});
	}

	my $line = join(",",@csv);#入力項目を連結
	$line = $line . "," . "$csv_cnt"; # 制御文字入力（cron.cgi 用）

	my($y,$m,$d) = &get_date;
	$line = "$y/$m/$d" . "," . "$line"; # 記録日入力
	
	$line =~ s/\r\n//g;
	$line =~ s/\r//g;
	$line =~ s/\n//g;



	if(!$csv_file){#TSVファイル名設定
		&error("CSVファイル名が指定されていません。");
	}

	if($confirm_key){#確認画面のURLエスケープを復元
		$line =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
	}
		$line =~ s/\r\n//g;
	$line =~ s/\r//g;
	$line =~ s/\n//g;

	$lfh = &fileLock("$csv_file") || &error("ファイルビジーです。しばらくしてからもう一度やりなおしてください。");

	open(CSV,">>$csv_dir/$csv_file") || &error("$csv_dir/$csv_file:CSVファイルに書き込めません");
	print CSV $line."\n";
	close(CSV);
	chmod(0666,"$csv_dir/$csv_file");

	&fileUnlock($lfh);
}

#-------------------------------------
#----ファイルロック
#-------------------------------------
sub fileLock {
  my %lfi = (dir => "$lockdir", lockname => @_[0], timeout => 60, trytime => 10);
  $lfi{base} = "$lfi{dir}/$lfi{lockname}";

  # ファイルロックの試行
  # 他でロックされていればロック用ファイルが存在しないのでrenameが失敗しロック中であることがわかる
  # result で付加される time は異常ロックのタイムアウトと札の意味がある
  for (my $i = 0; $i < $lfi{trytime}; $i++, sleep 1) {
    return \%lfi if (rename($lfi{base}, $lfi{result} = $lfi{base} . time));
  }

  # 異常ロックのチェック
  # ロック用ファイルのディレクトリ内を走査し、タイムアウトをチェック
  # 異常ロックファイルを新たなロックファイルに直接renameし、隙間なく移行している
  opendir(LOCKDIR, $lfi{dir});
  my @filelist = readdir(LOCKDIR);
  closedir(LOCKDIR);
  foreach (@filelist) {
    if (/^$lfi{lockname}(\d+)/) {
      return \%lfi if (time - $1 > $lfi{timeout} && rename($lfi{dir} . $_, $lfi{result} = $lfi{base} . time));
      last;
    }
  }

  # ロック中に付きロック情報は返さない
  undef;
}

#-------------------------------------
#----ファイルアンロック
#-------------------------------------
sub fileUnlock {
  rename($_[0]->{result}, $_[0]->{base});
}
