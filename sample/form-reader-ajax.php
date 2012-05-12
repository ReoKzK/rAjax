<?php

	/** --------------------------------- **
	 *               rAjax                 *
	 * ----------------------------------- *
	 * File : form-reader-ajax.php         *
	 * Form reader written in PHP for AJAX *
	 * request (outputs XML) of rAjax      *
	 * Sample                              *
	 * @author Reo Fox reo.fox@gmail.com   *
	 ** --------------------------------- **
	*/

	header('Content-Type: application/xml');

?>
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<form-read>

<?php
	
	if ( isset($_POST['from']) )
	{
		if ( isset($_POST['login']) )
			$login = $_POST['login'];
		else
			$login = '';
			
		if ( isset($_POST['password']) )
			$password = $_POST['password'];
		else
			$password = '';
			
		if ( isset($_POST['email']) )
			$email = $_POST['email'];
		else
			$email = '';
			
		if ( isset($_POST['programing']) )
			$programing = $_POST['programing'];
		else
			$programing = '';
			
		if ( isset($_POST['webmastering']) )
			$webmastering = $_POST['webmastering'];
		else
			$webmastering = '';
			
		if ( isset($_POST['gender']) )
			$gender = $_POST['gender'];
		else
			$gender = '';
			
		if ( isset($_POST['surfingweb']) )
			$surfingweb = $_POST['surfingweb'];
		else
			$surfingweb = '';
		
		if ( isset($_POST['food']) )
			$food = $_POST['food'];
		else
			$food = array();
			
		if ( isset($_POST['about']) )
			$about = $_POST['about'];
		else
			$about = '';
			
		if ( isset($_POST['from']) )
			$from = $_POST['from'];
		else
			$from = '';
	

?>
	<status>1</status>

	<login><![CDATA[<?php echo $login; ?>]]></login>

	<message><![CDATA[
		<h1>Hello <?php echo htmlspecialchars($login); ?>!</h1>
		<p>Your e-mail is <?php echo htmlspecialchars($email); ?> and you're identified by <span title="<?php echo htmlspecialchars($password); ?>"><?php for ( $i = 0; $i < strlen($password); $i++ ) echo '*'; ?></span></p>
		<p>You interest in: <ul><?php if ( $programing == 'on' )
											echo '<li>Programing</li>';
										if ( $webmastering == 'on' )
											echo '<li>Webmastering</li>';
										if ( $programing != 'on' && $webmastering != 'on' )
											echo '<li>nothing (?)</li>'; ?></ul></p>
		<p><?php
				
			$gender = strtolower($gender);
				
			if ( in_array($gender, array('male', 'female') ) )
			{
				if ( $gender == 'male' )
					echo 'You are male.';
				else if ( $gender == 'female' )
					echo 'You are female.';
			}
					
			else
			{
				echo 'You don\'t specified your gender.';
			}
				
		?></p>
		
		<p>Also you <?php echo strtolower($surfingweb); ?> surf on web<?php $food_count = count($food);
						if ( $food_count > 0 )
						{
							echo ' and like ';
						
							for ( $i = 0; $i < $food_count; $i++ )
							{
								echo $food[$i];
								
								if ( $i + 1 < $food_count )
								{
									if ( $i + 2 < $food_count )
										echo ', ';
									else
										echo ' &amp; ';
								}
							}
							
							echo ' food';
						}
						
						echo '.';
						
					?></p>
		
		<p>Something about you:</p>
		<blockquote><?php echo nl2br(htmlspecialchars($about)); ?></blockquote>
				
				
	]]></message>

<?php

	}
	
	else
	{
	
	?>
	
	<status>0</status>

<?php
	
	}
	
?>

</form-read>