#!/usr/local/bin/php
<?php
	ob_start();
	session_name('finalproject'); // continue the session
	session_start(); // start a session

	$unvalidated = fopen("unvalidated_email.txt", 'r');
	$text = file_get_contents("unvalidated_email.txt");          // store everything in unvalidated.txt into a string
	fclose($unvalidated);
	
	$line = explode("\n", $text);             // separate the string by new line character
	
	foreach ($line as $fields){
		$field = explode("\t", $fields);            // separate the string by tab
		$field2 = trim($field[2]);
		$token = $_GET['token'];              // get token from url
		
		if ($field2 === $token){
			$validated = fopen("validated_email.txt", 'a');
			fwrite($validated, "$field[0]\t$field[1]\n");		
			fclose($validated);
			// add the email and password corresponding to the token to the validated_email.txt
			
			$remove = "$field[0]\t$field[1]\t$field[2]\n";			
			// get the string of the line that needs to be removed from $text
		}
		// iterate each line of unvalidated_email and look for the token validated, create email and password info in validated_email.txt and remove this entry in unvalidated_email.txt
	}
	
	$text = str_replace($remove, '', $text);
	
	$unvalidated = fopen("unvalidated_email.txt", 'w');
	fwrite($unvalidated, $text);                    
	fclose($unvalidated);
	// rewrite information in unvalidated_email.txt with the validated line removed
	
	$_SESSION['registered'] = true;
?>

<!DOCTYPE html>
<html>
<head>
	<title> You're Registered! </title>
</head>
<body>
	<p>
		You are registered!
	</p>
</body>
</html>


