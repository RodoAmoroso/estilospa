<?php

class Responses {

	private static	$_arrout=array();

	public static function response($status='',$message='',$array=array(),$enc_out=true){
		self::$_arrout['status'] = $status;
		switch ($status) {
			case 'ok':
				$custommessage = '';
				break;
			case 'fail':
				$custommessage = 'Hubo problemas al procesar la solicitud. Intentá nuevamente más tarde.';
				break;
			case 'restricted':
				$custommessage = 'No tenés permiso para realizar esta operación.';
				break;
			case 'required':
				$custommessage = 'Faltan parámetros.';
				break;
			case 'user_exists':
				$custommessage = 'Ya existe un usuario registrado en nuestra base de datos con esa cuenta de email. Si no recordás la contraseña hacé click en el siguiente link para poder reestablecer tu contraseña: <a href="'.View::url('recuperar-password').'">'.View::url('recuperar-password').'</a>';
				break;
			case 'user_unexists':
				$custommessage = 'El mail ingresado no se encuentra registrado en nuestra base de datos. Verificá si el email es correcto. Si todavía no estás registrado, <a href="'.View::url('registro').'">click aquí</a> para registrarte y acceder a todos los beneficios dentro de EstiloSPA.';
				break;
			case 'wrong_password':
				$custommessage = 'La constraseña ingresada es incorrecta.';
				break;
			case 'password_match':
				$custommessage = 'Ambas contraseñas deben coincidir.';
				break;
			case 'invalid_pass':
				$custommessage = 'La contraseña ingresada no es válida. Debe contener al menos 8 caracteres';
				break;
			case 'invalid_email':
				$custommessage = 'El email ingresado es inválido.';
				break;
			case 'login_fail':
				$custommessage = 'Los datos de ingreso son erróneos. Intentá nuevamente. Si no recordás la contraseña <a href="'.View::url('recuperar-password').'" >click aquí</a> para recuperarla.';
				break;
			case 'unauthorized':
				$custommessage = 'No estás autorizado para ingresar.';
				break;

			case 'phone_length':
				$custommessage = 'El número de teléfono debe tener al menos 8 caracteres.';
				break;
			case 'phone_number':
				$custommessage = 'El número de teléfono debe tener algún número.';
				break;


			case 'user_inactive':
				$custommessage = 'Tu cuenta no ha sido activada. Por razones de seguridad necesitamos que activés tu cuenta desde el link que te fue enviado por email al registrarte. Si no recibiste el email de activación podés volver a enviarlo <a href="'.View::url('reenviar-activacion').'">desde aquí</a>';
				break;
			case 'user_active':
				$custommessage = 'Tu cuenta ya ha sido activada. Si no recordás la contraseña hacé click en el siguiente link para poder reestablecer tu contraseña: <a href="'.View::url('recuperar-password').'">'.View::url('recuperar-password').'</a>';
				break;
			case 'subscriber_exists':
				$custommessage = 'El email ingresado ya forma parte de la lista de subscripciones. Gracias.';
				break;

			case 'max_size':
				$custommessage = 'El tamaño del archivo no debe superar '.ini_get('upload_max_filesize').'';
				break;
			case 'upload_fail':
				$custommessage = 'Hubo problemas al subir el archivo. Intentá nuevamente más tarde.';
				break;
			case 'folder_fail':
				$custommessage = 'No se pudo crear la carpeta en el servidor.';
				break;

			case 'require_login':
				$custommessage = 'Necesitás registrarte para realizar esta operación.';
				break;

			case 'url':
				$custommessage = 'La url ingresada es incorrecta.';
				break;

		}
		self::$_arrout['message'] = empty($message) ? $custommessage : $message;
		if(!empty($array)){
			self::$_arrout = array_merge(self::$_arrout,$array);
		}
		if($enc_out) return json_encode(self::$_arrout);
		return self::$_arrout;
	}

	public static function get_message($status){
		self::response($status);
		return self::$_arrout['message'];
	}


}