<?php

	/** --------------------------------- **
	 *               rAjax                 *
	 * ----------------------------------- *
	 * File : validate.class.php           *
	 * Form data validation class          *
	 * @author Reo Fox reo.fox@gmail.com   *
	 ** --------------------------------- **
	*/

	//require_once PHP_DIR.'consts.php';
	
	class Validate
	{
		/**
		 * Validation message.
		 * 
		 * @var string
		 */
		
		private $message = '';
	

		/**
		 * Validate::validate_ajax()
		 * Sprawdza pojedyńczą wartość pola formularza.
		 * @param string $input_value
		 * @param string $field_id
		 * @param object &$data_getter - Referencja do obiektu klasy dostępu do danych
		 * @return int
		 */
	
		public function validate_ajax($input_value, $field_id)
		{
			// - Sprawdzenie, które pole jest weryfikowane. -
			switch($field_id)
			{
				// - Nazwa użytkownika. -
				case 'login' :
					return $this -> validate_login($input_value);
					break;
				
				// - Hasło użytkownika. -
				case 'password' :
					return $this -> validate_password($input_value);
					break;
				
				// - Poprawność adresu e-mail. -
				case 'email' :
					return $this -> validate_email($input_value);
					break;
				
				// -- TODO: return for unknown field_id
			}
		}
		
		/**
		 * Validate::validate_php()
		 * Waliduje cały formularz
		 * @return bool
		 */
		
		public function validate_php()
		{
			// -- Flaga wystąpienia błędu - ma wartość 1 gdy wystąpi błąd. -
			$error_exist = 0;
			
			// - Wyczyszczenie flag błędow w sesji. -
			if ( isset($_SESSION['errors']) )
				unset($_SESSION['errors']);
			
			if ( isset($_SESSION['classes']) )
				unset($_SESSION['classes']);
			
			// - Domyślnie wartości pol są poprawne. -
			$_SESSION['errors']['login']    = '';
			$_SESSION['errors']['email']    = '';
			$_SESSION['errors']['password'] = '';
			
			$_SESSION['classes']['login']    = '';
			$_SESSION['classes']['email']    = '';
			$_SESSION['classes']['password'] = '';
			
			// - Weryfikacja nazwy użytkownika. -
			if ( !$this -> validate_login($_POST['login']) )
			{
				$_SESSION['errors']['login']  = $this -> get_message();
				$_SESSION['classes']['login'] = VALIDATE_ERROR_CLASS;
				$error_exist = 1;
			}
			
			else
			{
				$_SESSION['errors']['login']  = $this -> get_message();
				$_SESSION['classes']['login'] = VALIDATE_OK_CLASS;
			}
			
			// - Weryfikacja hasła. -
			if ( !$this -> validate_password($_POST['password']) ) {
				$_SESSION['errors']['password'] = $this -> get_message();
				$_SESSION['classes']['password'] = VALIDATE_ERROR_CLASS;
				$error_exist = 1;
			}
			
			else {
				$_SESSION['errors']['password'] = $this -> get_message();
				$_SESSION['classes']['password'] = VALIDATE_OK_CLASS;
			}
			
			// - Weryfikacja e-maila. -
			if ( !$this -> validate_email($_POST['email']) ) {
				$_SESSION['errors']['email'] = $this -> get_message();
				$_SESSION['classes']['email'] = VALIDATE_ERROR_CLASS;
				$error_exist = 1;
			}
			
			else {
				$_SESSION['errors']['email'] = $this -> get_message();
				$_SESSION['classes']['email'] = VALIDATE_OK_CLASS;
			}
			
			// - Jeśli nie ma błedow. -
			if ( $error_exist == 0 )
			{
				return true;
			}
			
			// - Jeśli wystąpiły błędy - zapisanie wartości. -
			else
			{
				foreach ( $_POST as $key => $value )
				{
					$_SESSION['values'][$key] = $_POST[$key];
				}
				
				return false;
			}
		}
		
		
	   /**
	    * Validate::validate_login()
	    * Weryfikuje nazwę użytkownika.
	    * Nie może być pusta ani już zarejestrowana.
	    * @param string $value
		* @return int
	    */
	    
		private function validate_login($value)
		{
			// - Przycięcie białych znaków. -
			$value = trim($value);
			
			// - Pusta nazwa jest niepoprawna. -
			if ( $value == '' )
			{
				$this -> message = _LOGIN_ERROR_001;
				return 0;
			}
			
			// - Login musi pasować do wzorca. - // - '[(a-z)(A-Z)ęóąśłżźćńĘÓĄŚŁŻŹĆŃ_(0-9)\t\n\r\f]*'
			if ( !eregi('[(a-z)(A-Z)_(0-9)\t\n\r\f]*', $value)  )
			{
				$this -> message = 'Nie pasuje';
				return 0;
			}
			
			// - Login nie może być dłuższy niż 80 znaków. -
			if ( strlen($value) > 80  )
			{
				$this -> message = _LOGIN_ERROR_002;
				return 0;
			}
			
			$this -> message = _OK;
			return 1;
		}

		/**
	    * Validate::validate_password()
	    * Weryfikuje hasło.
	    * Nie może być puste, zawierać białych znaków, być dłuższe niż 80 znaków.
	    * @param string $value
	    * @return int
	    */
	    
		private function validate_password($value)
		{
			// - Pusta nazwa jest niepoprawna. -
			if ( $value == '' )
			{
				$this -> message = _PASSWORD_ERROR_001;
				return 0;
			}
		
			// - Hasło nie może zawierać białych znaków. -
			if ( ( strpos($value, ' ') !== false ) or ( strpos($value, "\t") !== false ) )
			{
				$this -> message = _PASSWORD_ERROR_003;
				return 0;
			}
			
			// - Hasło nie może być dłuższe niż 80 znaków. -
			if ( strlen($value) > 80  )
			{
				$this -> message = _PASSWORD_ERROR_002;
				return 0;
			}
			
			// - Hasło nie może być krótsze niż 5 znaków. -
			else if ( strlen($value) < 5  )
			{
				$this -> message = _PASSWORD_ERROR_005;
				return 0;
			}
			
			else
			{
				$this -> message = _OK;
				return 1;
			}
		}
		

		/**
	    * Validate::validate_email()
	    * Validates e-mail.
	    * @param string $value
	    * @return int
	    */

		private function validate_email($value)
		{
			// -----------------------
			// - Poprawne formaty :  |
			// - *@*.*               |
			// - *@*.*.*             |
			// - *.*@*.*             |
			// - *.*@*.*.*           |
			// -----------------------
			
			if ( trim($value) == '' )
			{
				$this -> message = _EMAIL_ERROR_002;
				return 0;
			}
			
			if ( !eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[_a-z0-9-]+)*(\.[a-z]{2,3})$', $value) )
			{
				$this -> message = _EMAIL_ERROR_001;
				return 0;
			}
			
			else
			{
				$this -> message = _OK;
				return 1;
			}
		}
		
	   /**
	    * Validate::get_message()
	    * Returns field validation message
	    * @return string
	    */
	   
		public function get_message()
		{
			return $this -> message;
		}
		
	}

?>
