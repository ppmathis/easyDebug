<?php
	/*
	--------------------------------------------------------------------------
	-- easyDebug v1.1 beta
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
	$test = 'Hallo Welt';
	$debug->add('Nummer', 1337);
	$debug->add('$test enthält \'Hallo Welt\'', $test);
	$debug->add('Boolsches Zeugs', true);
	$debug->add('Array', array(0 => 'huhu', 1 => 'haha', 2 => 'schlüssel', 3 => 4));
	$debug->add('Der Debugger selbst...', $debug);
	$debug->log('Testeintrag in der Log');
	$debug->warning('Achtung.... Da ist etwas ziemlich kritisch!');
	$debug->error('E-PIG FAIL!');
	$debug->show();
	
	// [EXAMPLE] End of html file
	echo '</body></html>';
?>