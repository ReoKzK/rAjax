<?php

	/** --------------------------------- **
	 *               rAjax                 *
	 * ----------------------------------- *
	 * File : form-validate-ajax.php       *
	 * Form validator written in PHP for   *
	 * AJAX request (outputs XML) of rAjax *
	 * Sample                              *
	 * @author Reo Fox reo.fox@gmail.com   *
	 ** --------------------------------- **
	*/

	// - Zmienne $_POST['inputValue'] i $_POST['fieldID'] są wymagane -

	if ( !isset($_POST['inputValue']) || !isset($_POST['fieldID']) )
	{
		echo '>ERROR';
		exit;
	}

 // ------------------------------------------------------------------------------------

	require_once 'lang/en.php';
	require_once 'class/validate.class.php';

 // ------------------------------------------------------------------------------------

	$validator = new Validate();
	
	$response  = '<?xml version="1.0" encoding="utf-8" standalone="yes"?'.'>'."\n";
	$response .= '<response>'."\n";
	$response .= '	<result>';
	$response .= $validator -> validate_ajax($_POST['inputValue'], $_POST['fieldID']);
	$response .= '</result>'."\n";
	$response .= '	<fieldid>';
	$response .= $_POST['fieldID'];
	$response .= '</fieldid>'."\n";
	$response .= '	<message>';
	$response .= $validator -> get_message();
	$response .= '</message>'."\n";
	$response .= '</response>';

	header('Content-Type: application/xml');
	echo $response;

?>