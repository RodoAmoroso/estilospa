<?php 

class Mailing {

	private $_mailer,
					$_email='rodosoft@hotmail.com',
					$_fullname='EstiloSPA';

	public function __construct(){
		require PATH.DS.'lib'.DS.'phpmailer'.DS.'PHPMailerAutoload.php';
		$this->_mailer = $mailer;
	}

	public static function template($_body=''){
		ob_start();
		include PATH.DS.'templates'.DS.'email-template.php';
		$template = ob_get_contents();
		ob_end_clean();
		return $template;
	}

}