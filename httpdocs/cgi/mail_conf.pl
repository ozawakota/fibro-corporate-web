###############################################
# mail.cgi
# v1.0 (2004.09.27)
# v1.1 (2004.11.04)
# (c) m.s. @ freischtide

#---- Configuration ----#

#To�A�h���X
#���[�����M��A�h���X
$mailto='';

#�f�t�H���gFrom�A�h���X
#���[���A�h���X���͂��ȗ����ꂽ�ۂ�From�A�h���X
$mailfrom='';

#�����[�����M��ݒ�� mail.html �ɂčs���Ă��������B

#���M�O�̊m�F���
#0:�\�����Ȃ�
#1:�\������
$confirm_key = 1;

#���͕K�{����
#�쐬����HTML�t�H�[����name�������w�肵�܂�
#�Y�t�t�@�C�����K�{�Ɏw��\
#�u'�v�ň͂��āu,�v�ŋ�؂�
@req = ('name','TEL','mail','mail_confirm','detail');

#�L�����ꂽ���[���A�h���X�ɂ�
#�������[�����T���Ƃ��đ��M�icc�ő��M���܂��j
#0:���Ȃ�
#1:����
$copy_key = 1;

#���[���̍ő啶����(bytes)
#�����Ŏw�肵���������𒴂��郁�[���͑��M�ł��Ȃ��Ȃ�܂��B
#�S�p�����P������2bytes
#���p����1������1byte
#20480����
$max_length = 40960;

#�ő�t�@�C���T�C�Y(bytes)
#�t�@�C���T�C�Y�̍��v���A�����Ŏw�肵���l�𒴂���Ƒ��M�ł��܂���
#5Mbytes
$max_file_size = 5242880;

#sendmail�̃p�X
$sendmail = '/usr/sbin/sendmail';

#���̃X�N���v�g�̃t�@�C����
$scriptname = 'mail.cgi';

#��������(autoreply)���[���e���v���[�g�t�@�C��
$template = './mail.txt';

#�m�F��ʃe���v���[�g�t�@�C��
$template2 = '../mail_confirm.html';

#�������M(cron)���[����
$cronmail_cnt = 0;

#�������M(cron)���[���e���v���[�g
#�e���v���[�g�t�@�C�����͏��߂� filename.txt �Ƃ�
#��ڈȍ~�� filename2.txt, filename3.txt �ƃC���N�������g���Ă����܂��B
#�܂��t�@�C���g���q�͕K�� .txt �Ƃ��Ă��������B
$cron_temp = './mail_cron_temp.txt';

#�Y�t�t�@�C���ꎞ�ۑ��f�B���N�g��
#�Ō��/�͕t���Ȃ�
$file_dir = './tmp';

#body�^�O
#�G���[�\����ʂŗL��
$body_tag = '<body bg="#ffffff" text="#000000">';

#���M������ɕ\������HTML�ihttp����L���j
$jumpto = "/mail_thanks.html";

#�폜������ɕ\������HTML�ihttp����L���j
$jumpto2 = "/mail_thanks.html";

#���M�������HTML�ւ̃W�����v
#0:Location�w�b�_�ňړ��i�ʏ�͂�����j
#1:META�^�O�ňړ��iLocation�w�b�_�ł��܂������Ȃ��ꍇ�͂�����j
$jump_header = 0;

#���͍��ڂ�CSV�t�@�C���ɋL�^
#0:���Ȃ�
#1:����
#�t�@�C���ɂ͓��͂��ꂽ�l�����L�^����܂��B
#��舵���ɂ͏[�������ӂ��������B
$csv_key = 0;

#CSV�t�@�C���쐬�f�B���N�g��
#�@�����̍�������Z���E�d�b�ԍ����̏d�v�Ȍl��񓙂�
#�L�^�����\��������ꍇ�́Apublic_html�̊O�Ȃ�
#�u���E�U���璼�ڃA�N�Z�X�ł��Ȃ��ꏊ�ɍ쐬����Ȃǂ�
#�Z�L�����e�B�΍���s�����Ƃ������߂��܂��B
$csv_dir = './csv';

#CSV�t�@�C����
$csv_file = 'mail.csv';

#CSV�t�@�C���ɋL�����鍀��
#�t�H�[����name�����Ŏw��
#�����Ŏw�肵�������ŋL�^����܂��B
@csv_items = ('');

#CSV�R���g���[���J�E���^
#�������[�����M�p�J�E���^
#�����ɕK�v���Ȃ�����ύX���Ȃ����ƁI
$csv_cnt = '1';

#LOCK�t�@�C���i�[DIR
#��LOCKDIR�ɂ͕K�����b�N�t�@�C������邱�ƁI�I�I
$lockdir = './lock';

#do not edit following line !!!!
@items = ('name','mail','body');

#---- Configuration ----#
