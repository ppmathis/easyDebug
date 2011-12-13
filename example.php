<?php
	/*
	--------------------------------------------------------------------------
	-- easyDebug v1.1.3 beta
	-- © 2011 P. Mathis - pmathis@snapserv.net
	--------------------------------------------------------------------------
	-- License info (CC BY-NC-SA 3.0)
	--
	-- This code is licensed via a Creative Commons Licence: http://creativecommons.org/licenses/by-nc-sa/3.0/
	-- Means: 	You may alter the code, but have to give the changes back.
	--			You may not use this work for commercial purposes.
	--			You must attribute the work in the manner specified by the author or licensor.
	-- If you like to use this code commercially, please contact pmathis@snapserv.net
	--------------------------------------------------------------------------
	*/

	// Set charset and include easyDebug
	header('Content-Type: text/html; charset=UTF-8');  
	require_once('easyDebug.php');

	// Get singleton instance
	$debug = easyDebug::getInstance();
	
	// [EXAMPLE] HTTP header
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><title>easyDebug example</title><meta http-equiv="Content-type" content="text/html;charset=UTF-8">';
	// Print easyDebug Header
	$debug->header();
	// [EXAMPLE] HTTP header end
	echo '</head><body>';
	
	// Some test functions
	$test = 'Hello World';
	$debug->add('Testnumber', 1337);
	$debug->add('$test contains \'Hello World\'', $test);
	$debug->add('Boolean test', true);
	$debug->add('Array example', array(0 => 'huhu', 1 => 'haha', 2 => 'schlüssel', 3 => 4));
	$debug->add('Debugger instance (only private objects)', $debug);
	$debug->log('Log example');
	$debug->warning('Warnings should go here.');
	$debug->error('Oh my god that was bad...');
	$debug->show();
	
	// [EXAMPLE] End of html file
	echo '</body></html>';
?>