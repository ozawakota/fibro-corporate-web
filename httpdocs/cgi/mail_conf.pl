###############################################
# mail.cgi
# v1.0 (2004.09.27)
# v1.1 (2004.11.04)
# (c) m.s. @ freischtide

#---- Configuration ----#

#Toアドレス
#メール送信先アドレス
$mailto='';

#デフォルトFromアドレス
#メールアドレス入力が省略された際のFromアドレス
$mailfrom='';

#※メール送信先設定は mail.html にて行ってください。

#送信前の確認画面
#0:表示しない
#1:表示する
$confirm_key = 1;

#入力必須項目
#作成したHTMLフォームのname属性を指定します
#添付ファイルも必須に指定可能
#「'」で囲って「,」で区切る
@req = ('name','TEL','mail','mail_confirm','detail');

#記入されたメールアドレスにも
#同じメールを控えとして送信（ccで送信します）
#0:しない
#1:する
$copy_key = 1;

#メールの最大文字数(bytes)
#ここで指定した文字数を超えるメールは送信できなくなります。
#全角文字１文字＝2bytes
#半角文字1文字＝1byte
#20480文字
$max_length = 40960;

#最大ファイルサイズ(bytes)
#ファイルサイズの合計が、ここで指定した値を超えると送信できません
#5Mbytes
$max_file_size = 5242880;

#sendmailのパス
$sendmail = '/usr/sbin/sendmail';

#このスクリプトのファイル名
$scriptname = 'mail.cgi';

#自動応答(autoreply)メールテンプレートファイル
$template = './mail.txt';

#確認画面テンプレートファイル
$template2 = '../mail_confirm.html';

#自動送信(cron)メール数
$cronmail_cnt = 0;

#自動送信(cron)メールテンプレート
#テンプレートファイル名は初めは filename.txt とし
#二個目以降は filename2.txt, filename3.txt とインクリメントしていきます。
#またファイル拡張子は必ず .txt としてください。
$cron_temp = './mail_cron_temp.txt';

#添付ファイル一時保存ディレクトリ
#最後に/は付けない
$file_dir = './tmp';

#bodyタグ
#エラー表示画面で有効
$body_tag = '<body bg="#ffffff" text="#000000">';

#送信完了後に表示するHTML（httpから記入）
$jumpto = "/mail_thanks.html";

#削除完了後に表示するHTML（httpから記入）
$jumpto2 = "/mail_thanks.html";

#送信完了後のHTMLへのジャンプ
#0:Locationヘッダで移動（通常はこちら）
#1:METAタグで移動（Locationヘッダでうまくいかない場合はこちら）
$jump_header = 0;

#入力項目をCSVファイルに記録
#0:しない
#1:する
#ファイルには入力された個人情報も記録されます。
#取り扱いには充分ご注意ください。
$csv_key = 0;

#CSVファイル作成ディレクトリ
#機密性の高い情報や住所・電話番号等の重要な個人情報等が
#記録される可能性がある場合は、public_htmlの外など
#ブラウザから直接アクセスできない場所に作成するなどの
#セキュリティ対策を行うことをお勧めします。
$csv_dir = './csv';

#CSVファイル名
$csv_file = 'mail.csv';

#CSVファイルに記入する項目
#フォームのname属性で指定
#ここで指定した順序で記録されます。
@csv_items = ('');

#CSVコントロールカウンタ
#自動メール送信用カウンタ
#※特に必要がない限り変更しないこと！
$csv_cnt = '1';

#LOCKファイル格納DIR
#※LOCKDIRには必ずロックファイルを作ること！！！
$lockdir = './lock';

#do not edit following line !!!!
@items = ('name','mail','body');

#---- Configuration ----#
