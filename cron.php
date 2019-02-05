<?php 

require_once 'config.php';
require 'ajax/templates-mail.php';
require 'ajax/phpmailer/PHPMailerAutoload.php';

$minutos = 15;
$mailxhora = 100;
$limite = floor($minutos*$mailxhora/60);

//////////// MESSAGES //////////////////////
$_notifications = new Notifications();
$_notifications->limit = '0,'.$limite;
if($_notifications->get()){
	foreach($_notifications->data() as $notify){
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
		$_notifications->delete($notify->id);

	}
}

echo 'ok';
