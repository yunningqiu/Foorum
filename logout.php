#!/usr/local/bin/php
<?php
	ob_start();
	session_name('finalproject'); // continue the session
	session_start(); // start a session
	session_unset();
	session_destroy();
	// clear all session variables and end session
	
	header("Location: index.html");
	// derict back to login page
?>