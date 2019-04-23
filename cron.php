<?php 

require_once 'config.php';
require 'templates/templates-mail.php';
require 'lib/phpmailer/PHPMailerAutoload.php';

$minutos = 15;
$mailxhora = 100;
$limite = floor($minutos*$mailxhora/60);

//////////// MESSAGES //////////////////////
$Notifications = new Notifications();
$Notifications->limit = '0,'.$limite;
if($Notifications->get()){
	foreach($Notifications->data() as $notify){
		$mailer->addReplyTo($notify->email_from, $notify->name_from);
		$mailer->setFrom($notify->email_from, $notify->name_from);
		$mailer->Subject = $notify->subject;
		$mailer->Body = $MailHead.$notify->body.$MailFoot;

		$arrMails = str_replace(',', ';', $notify->email_to);
		$arrMails = explode(';',$arrMails);
		foreach($arrMails as $mail){
			$mailer->addAddress(strtolower(trim($mail)), $notify->name_to);
		}

		if(!$mailer->send()) echo 'fail';
		$mailer->clearAllRecipients();
		$mailer->clearReplyTos();
		$Notifications->delete($notify->id);

	}
}

echo 'ok';
