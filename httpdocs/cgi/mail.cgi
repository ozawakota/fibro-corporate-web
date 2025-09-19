#!/usr/bin/perl

require'./jcode.pl';
require'./mimew.pl';
require'./mail_conf.pl';

###############################################
# mail.cgi
# v1.0 (2004.09.27)
# v1.1 (2004.11.05)�FBUG FIX �Y�t�t�@�C������������
# v1.2 (2004.11.07)�FCSV�L�^�@�\, �r�����b�N�@�\�ǉ�
# v1.3 (2004.11.19)�FCSV�L�^�ɓ��t�ǉ�
# (c) m.s. @ freischtide
# �t�H�[�����[��IF

# �t�H�[������̓��͓ǂݍ���
&get_form;

# ���[�h�ɂ�铮�씻��
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
#----���M
#-------------------------------------
sub send{

	if($confirm_key){#�m�F��ʂ���̏ꍇ�̓Y�t�t�@�C������
		foreach $file (grep(/^file\d+$/,(keys %in))){
			$FILE_PATH{$file} = $in{"path_$file"};
			$FILE_NAME{$file} = $in{"name_$file"};
			binmode(FILE);
			open(FILE,$FILE_PATH{$file}) || &error("�t�@�C�����J���܂���");
			while(<FILE>){
				$FILE_DATA{$file} .= $_;
			}
			close(FILE);
		}
	}
	
	#�`�F�b�N���璷�ɂȂ邽�߁A���	
	#&chk_req;#�K�{���ڃ`�F�b�N
	if($csv_key){&add_to_csv;}#csv�t�@�C���ɉ�����

	&send_mail;#���[�����M

	&delete_old_file;
	
	&jump("$jumpto");#���M������ʂ�
}


#-------------------------------------
#----���[�����M
#-------------------------------------
sub send_mail{
	open(TEMPLATE,$template) || &error("�e���v���[�g�t�@�C�����J���܂���");
	@allbody = <TEMPLATE>;
	close(TEMPLATE);
	
	my $boundary = "boundary";#��؂�
	
	my $subject = shift(@allbody);
	my $body = join("",@allbody);
	
	foreach $key (keys %in){#�e���v���[�g�Ɠ��͍��ڂ���{���쐬
		if($confirm_key){#�m�F��ʂ�URL�G�X�P�[�v�𕜌�
			$in{$key} =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
		}
		$in{$key} =~ s/\r\n/\n/g;
		$in{$key} =~ s/\r/\n/g;

		$body =~ s/\Q<#$key#>\E/$in{$key}/g;
	}
	
	#�`�F�b�N���璷�̂��߂��
	#���[���A�h���X�`�F�b�N		
	#&chk_mail($in{mail});
	
	#�z�X�g�EIP�\��	
	my($host,$ip) = &get_host;
	$body =~ s/\<\#host\#\>/$host/g;
	$body =~ s/\<\#ip\#\>/$ip/g;
	
	#�����͍��ڂ̃^�O�폜
	$body =~ s/\<\#.*\#\>//g;
	
	#�����`�F�b�N
	if(length($body) > $max_length){&error("�ő啶�����𒴂��Ă��܂�");}
	
	
	#�����G���R�[�h
	$subject = &mimeencode(jcode'jis($subject));
	$subject =~ s/\n//g;
	
	#�{���G���R�[�h
	$body = jcode'jis($body);

	undef(@allbody);
	
	my $file_flag = 0;
	my $file_size = 0;
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){#�Y�t�t�@�C���̗L������
		if($FILE_DATA{$file}){
			$file_flag++;
			$file_size += length($FILE_DATA{$file});#�t�@�C���T�C�Y���v���Z
		}
	}
	
	if($file_size > $max_file_size){&error("�t�@�C���T�C�Y�̍��v���傫�����܂�");}

	if(!$in{mail}){
		open(MAIL,"|$sendmail -t -i -f $mailfrom") || &error("SENDMAIL���J���܂���");
	}else{
		open(MAIL,"|$sendmail -t -i -f $in{mail}") || &error("SENDMAIL���J���܂���");
	}

	
	if($file_flag){#�Y�t�t�@�C���L��
		
		#�w�b�_
		print MAIL "Mime-Version: 1.0\n";
		print MAIL "Content-Type: Multipart/Mixed; boundary=\"$boundary\"\n";
		print MAIL "Content-Transfer-Encoding: Base64\n";
		print MAIL "From: $in{mailto}\n"; 
		print MAIL "To: $in{mail}\n";
		if($copy_key){
			print MAIL "Cc: $in{mailto}\n";
		}
		print MAIL "Subject: $subject\n";
		
		#�{��
		print MAIL "--$boundary\n";
		print MAIL "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
		print MAIL "\n";
		print MAIL "$body\n";
	
		#�Y�t�t�@�C��
		foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){ 	
			if($FILE_DATA{$file}){
				#$path = &save_file($FILE_DATA{$file},$FILE_NAME{$file});
				
				#�Y�t�t�@�C�����G���R�[�h
				$FILE_NAME{$file} = jcode'jis($FILE_NAME{$file});
		
				#�Y�t�t�@�C���G���R�[�h
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
	
	
		#�}���`�p�[�g�I��
		print MAIL "--$boundary--\n";
		
		#�ꎞ�ۑ��Y�t�t�@�C���폜
		foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){#�Y�t�t�@�C���̗L������
			if(-e $FILE_PATH{$file}){unlink $FILE_PATH{$file};}
		}
		
	}else{#�Y�t�t�@�C������
		
		#�w�b�_
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
		
		#�{��
		print MAIL $body."\n";
		
	}
	close MAIL;

}


#-------------------------------------
#----�Â��ꎞ�ۑ��t�@�C�����폜
#-------------------------------------
sub delete_old_file{
	my $file;
	opendir(DIR,$file_dir);
	while($file = readdir(DIR)){
		if($file eq '.' || $file eq '..'){next;}
		if(time - (stat("$file_dir/$file"))[9] > 1*60*60){
			unlink("$file_dir/$file") || &error("$file_dir/$file���폜�ł��܂���");
		}
		
	}
	closedir(DIR);
}


#-------------------------------------
#----�m�F���
#-------------------------------------
sub confirm{

	&chk_req;#�K�{���ڃ`�F�b�N
	
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
	
	open(TEMP2,"$template2") || &error("�m�F��ʃe���v���[�g���J���܂���");
	$body = join("",<TEMP2>);
	close(TEMP2);
		
	#�e����
	foreach $key (keys %in){
		$escaped{$key} = $in{$key};
		
		$in{$key} = &del_tag($in{$key});#�^�O����
		$in{$key} =~ s/\r\n/<br>/g;
		$in{$key} =~ s/\r/<br>/g;
		$in{$key} =~ s/\n/<br>/g;
		
		$escaped{$key} =~ s/(\W)/'%'.unpack('H2',$1)/eg;#URL�G�X�P�[�v		
		$hidden = "<input type=\"hidden\" name=\"$key\" value=\"$escaped{$key}\">";
		if($key eq "mailto"){
			$body =~ s/\Q<#$key#>\E/$hidden/g;
		} else {
			$body =~ s/\Q<#$key#>\E/$in{$key}$hidden/g;
		}
	}
	
	#�Y�t�t�@�C��
	foreach $file (grep(/^file\d+$/,(keys %FILE_DATA))){
		if($FILE_DATA{$file} eq ''){next;}
		$file_hidden .= "<a href=\"$FILE_PATH{$file}\">$FILE_NAME{$file}</a> ";
		$file_hidden .= "<input type=\"hidden\" name=\"$file\" value=\"$FILE_NAME{$file}\">";
		$file_hidden .= "<input type=\"hidden\" name=\"path_$file\" value=\"$FILE_PATH{$file}\">";
		$file_hidden .= "<input type=\"hidden\" name=\"name_$file\" value=\"$FILE_NAME{$file}\">";
	}
	$body =~ s/\<\#file\#\>/$file_hidden/g;
	
	$body =~ s/\<\#.*\#\>//g;#�����͍��ڂ̃^�O�폜
	

	$body =~ s/\<\/body\>//g;
	$body =~ s/\<\/html\>//g;
	print $body;
	&footer;


}


#-------------------------------------
#----�t�H�[�����͎擾
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
#----�t�H�[�����̓f�R�[�h
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
		
		#����name�����̏ꍇ
		if($in{$name} ne ''){
			$in{$name} .= " ".$value;
		}else{
			$in{$name} = $value;
		}
	}
}


#-------------------------------------
#----�}���`�p�[�g�t�H�[���f�R�[�h
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
			
			#����name�����̏ꍇ
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
#----�t�@�C���ۑ�
#-------------------------------------
sub save_file{
	my($file_data,$file_name) = @_;

	open(IMG,">$file_dir/$file_name") || &error("�t�@�C����ۑ��ł��܂���");
	print IMG $file_data;
	close(IMG);
	chmod 0666,"$file_dir/$file_name";

	return "$file_dir/$file_name";
}


#-------------------------------------
#----���M������ړ�
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
		print"�X�V���E�E�E<Br>";
		print"���΂炭�҂��Ă��ړ����Ȃ��ꍇ��<br>\n";
		print"<a href=\"$jumpto\">������</a>���N���b�N���Ă�������\n";
		print"</body>\n";
		print"</html>\n";
	}
}


#-------------------------------------
#----�K�{���ڃ`�F�b�N
#-------------------------------------
sub chk_req{
	foreach $req (@req){
		if($req =~ /^file\d+$/ && $FILE_DATA{$req} eq ''){#�t�@�C���̏ꍇ
			&error("$req���w�肳��Ă��܂���");
		}elsif($req =~ /^mail$/){#���[���̏ꍇ
			&chk_mail($in{mail},$in{mail_confirm});
		}elsif($req !~ /^file\d+$/ && $in{$req} eq ''){#�t�@�C��,���[���ȊO�̍���
			&error("$req �����͂���Ă��܂���B");
		}
	}
}


#-------------------------------------
#----�w�b�_�o��
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
#----�t�b�^�o��
#-------------------------------------
sub footer{
	print"<hr>\n";
	print"</body>\n";
	print"</html>\n";
}


#-------------------------------------
#----���[���A�h���X�`�F�b�N
#-------------------------------------
sub chk_mail{
	my $chk = $_[0]; 
	my $chk_cnfm = $_[1]; 
	if($chk ne $chk_cnfm){ &error("���͂��ꂽ���[���A�h���X����v���Ă��܂���");}
	if($chk eq ''){ &error("���[���A�h���X�����͂���Ă��܂���");}
	if($chk =~ /\,/){ &error("���[���A�h���X�ɃR���}�i,�j���܂܂�Ă��܂�"); }
	#if($chk !~ /[\w\.\-]+\@[\w\.\-]+\.[a-zA-Z]{2,3}/){
	#	&error("���[���A�h���X���S�p�ŋL������Ă��邩�A����������������܂���");
	#}
}


#-------------------------------------
#----�z�X�g�EIP�擾
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
#----�^�O����
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
#----�G���[�\��
#-------------------------------------
sub error{
	if($_[1]){&unlock;}
	&header;
	print "<br>$_[0]";
	print"<br>�u���E�U�̖߂�{�^���ł��߂肭�������B";
	&footer;
	exit;
}

#-------------------------------------
#---�����擾
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
#----CSV�t�@�C���ɋL�^
#-------------------------------------
sub add_to_csv{

	foreach $n (@csv_items){
		push(@csv,$in{$n});
	}

	my $line = join(",",@csv);#���͍��ڂ�A��
	$line = $line . "," . "$csv_cnt"; # ���䕶�����́icron.cgi �p�j

	my($y,$m,$d) = &get_date;
	$line = "$y/$m/$d" . "," . "$line"; # �L�^������
	
	$line =~ s/\r\n//g;
	$line =~ s/\r//g;
	$line =~ s/\n//g;



	if(!$csv_file){#TSV�t�@�C�����ݒ�
		&error("CSV�t�@�C�������w�肳��Ă��܂���B");
	}

	if($confirm_key){#�m�F��ʂ�URL�G�X�P�[�v�𕜌�
		$line =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
	}
		$line =~ s/\r\n//g;
	$line =~ s/\r//g;
	$line =~ s/\n//g;

	$lfh = &fileLock("$csv_file") || &error("�t�@�C���r�W�[�ł��B���΂炭���Ă��������x���Ȃ����Ă��������B");

	open(CSV,">>$csv_dir/$csv_file") || &error("$csv_dir/$csv_file:CSV�t�@�C���ɏ������߂܂���");
	print CSV $line."\n";
	close(CSV);
	chmod(0666,"$csv_dir/$csv_file");

	&fileUnlock($lfh);
}

#-------------------------------------
#----�t�@�C�����b�N
#-------------------------------------
sub fileLock {
  my %lfi = (dir => "$lockdir", lockname => @_[0], timeout => 60, trytime => 10);
  $lfi{base} = "$lfi{dir}/$lfi{lockname}";

  # �t�@�C�����b�N�̎��s
  # ���Ń��b�N����Ă���΃��b�N�p�t�@�C�������݂��Ȃ��̂�rename�����s�����b�N���ł��邱�Ƃ��킩��
  # result �ŕt������� time �ُ͈탍�b�N�̃^�C���A�E�g�ƎD�̈Ӗ�������
  for (my $i = 0; $i < $lfi{trytime}; $i++, sleep 1) {
    return \%lfi if (rename($lfi{base}, $lfi{result} = $lfi{base} . time));
  }

  # �ُ탍�b�N�̃`�F�b�N
  # ���b�N�p�t�@�C���̃f�B���N�g�����𑖍����A�^�C���A�E�g���`�F�b�N
  # �ُ탍�b�N�t�@�C����V���ȃ��b�N�t�@�C���ɒ���rename���A���ԂȂ��ڍs���Ă���
  opendir(LOCKDIR, $lfi{dir});
  my @filelist = readdir(LOCKDIR);
  closedir(LOCKDIR);
  foreach (@filelist) {
    if (/^$lfi{lockname}(\d+)/) {
      return \%lfi if (time - $1 > $lfi{timeout} && rename($lfi{dir} . $_, $lfi{result} = $lfi{base} . time));
      last;
    }
  }

  # ���b�N���ɕt�����b�N���͕Ԃ��Ȃ�
  undef;
}

#-------------------------------------
#----�t�@�C���A�����b�N
#-------------------------------------
sub fileUnlock {
  rename($_[0]->{result}, $_[0]->{base});
}
