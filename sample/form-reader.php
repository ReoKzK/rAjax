<?php

	/** --------------------------------- **
	 *               rAjax                 *
	 * ----------------------------------- *
	 * File : form-reader.php              *
	 * Form reader written in PHP of rAjax *
	 * Sample - outputs xHTML page         *
	 * @author Reo Fox reo.fox@gmail.com   *
	 ** --------------------------------- **
	*/

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
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>rAjax :: Sample :: Forms</title>

		<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
		<script type="text/javascript" src="../src/rajax-0.1.1b.js"></script>
		<script type="text/javascript" src="../src/lang_defs/en.js"></script>
		<script type="text/javascript">
		// <![CDATA[
			
			rajax = new rAjax();
			rajax.lang = rAjax_lang;
			rajax.debugMode = true;
			rajax.run();
			
		// ]]>
		</script>
		
		<script type="text/javascript">
		// <![CDATA[
			
			/**
			 *  Function executed when loading
			 */
			rajax.onLoading = function()
			{
				//document.getElementById('output').innerHTML = "Loading...";
				document.getElementById('output').className = "loading";
				document.body.style.cursor = "progress";
			}
			
			function undisplayWaiting()
			{
				document.getElementById('output').className = "";
				document.body.style.cursor = "default";
			}
			
			function handleText(text)
			{
				document.getElementById('output').innerHTML = text;
				undisplayWaiting();
			}
			
			function handleXML(xmlData)
			{
				var xmlResponse = xmlData;
				
				// - Pobranie elementu dokumentu XML. -
				var xmlRoot = xmlResponse.documentElement;
	
				// - Pobranie zawartości strony. -
	
				var first = xmlRoot.getElementsByTagName("first");
				first = first.item(0).firstChild.data;
				
				var second = xmlRoot.getElementsByTagName("second");
				second = second.item(0).firstChild.data;
			
				document.getElementById('output').innerHTML = "<h1>" + first + "</h1><p>" + second + "</p>";
				undisplayWaiting();
			}
			
		// ]]>
		</script>
		
	</head>
	
	<body>

		<div id="container">
			<div id="header"><h1>rAjax Sample</h1></div>
			<div id="menu">
				<ul>
					<li><a href="index.html">Download Data</a></li>
					<li class="selected"><a href="forms.html">Submit Form</a></li>
				</ul>
			</div>
			
			<div id="content">
				
				<div class="message">
				
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
				
				</div>
				
			</div>
		</div>
		
	
	</body>
</html>

<?php

	}
	
?>