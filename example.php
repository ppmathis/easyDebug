<?php
	/*
	--------------------------------------------------------------------------
	-- easyDebug v1.2.1 beta
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
	
	// [EXAMPLE] OOP Test class
	class Test {
		private $privVar1 = 'priv1';
		protected $protectedVar1 = 'prot1';
		public $publicVar1 = 'pub1';
		
		public $ar = array('huhu' => 'hihi', 'hoho' => 'haha');
		public $in = 10;
		
		protected $prot = 10;
		
		function normal_function() {
			return;
		}
		
		private function private_function() {
			return;
		}
	}
	$priv = new Test;
	
	// [EXAMPLE] How to use easyDebug
	$test = 'Hello World';
	$debug->add('GET', $_GET);
	$debug->add('Testnumber', 1337);
	$debug->add('$test contains \'Hello World\'', $test);
	$debug->add('Boolean test', true);
	$debug->add('Array example', array(0 => 'huhu', 1 => 'haha', 2 => 'schlüssel', 3 => 4));
	$debug->add('OOP stuff', $priv, true);
	$debug->add('OOP stuff (no hijacking)', $priv);
	$debug->log('Log example');
	$debug->warning('Warnings should go here.');
	$debug->error('Oh my god that was bad...');
	$debug->show();
	
	// [EXAMPLE] End of html file
	echo '</body></html>';
?>