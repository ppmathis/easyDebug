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
	
	class easyDebug {
		// Configuration
		const width = 600;					// [C ] Debugger width
		const height = 800;					// [C ] Debugger height
		const menuSize = 2;					// [C ] Menu size
		const compressionRate = 1000;		// [C ] Every ?? log lines, compress data
		const colorNumeric = '#C0E0A0';		// [C ] Color for numeric variables
		const colorString = '#A0C0E0';		// [C ] Color for strings
		const colorBoolean = '#E0E0A0';		// [C ] Color for boolean variables
		const colorArray = '#E0A0C0';		// [C ] Color for arrays
		const colorObject = '#E0C0A0';		// [C ] Color for objects
		const colorUnknown = '#E0E0E0';		// [C ] Color for unknown types
	
		static private $instance = null;	// [SP] Singleton instance
		private $memoryLimit = null;		// [ P] Memory limit
		private $actualData = array();		// [ P] Actual debugger data
		private $sysLog = array();			// [ P] Sys log (private!)
		private $logData = array();			// [ P] Log data
		private $compressedLog = array();	// [ P] Compressed log
		private $actualBlock = 1;			// [ P] Actual block (don't modify that value!)
		private $compressedData = array();	// [ P] Compressed variable data
		
		static public function getInstance() {
			// Create new instance if necessary
			if(null === self::$instance) {
				self::$instance = new self;
			}
			// Init class
			self::$instance->memoryLimit = ini_get('memory_limit');
			// Return singleton instance
			return self::$instance;
		}
		
		final public function add($varName, $varData) {
			// Store variable data
			$this->actualData[] = array(
				'Name' => $varName,
				'Data' => json_encode($varData)
			);
			
			// Array too big?
			if(count($this->actualData) % self::compressionRate == 0) {
				$serialized = serialize($this->actualData);
				$this->compressedData[] = gzcompress($serialized, 9);
				unset($this->actualData);
				$this->logData[] = array(
					'Type' => 'info',
					'Data' => 'More than ' . self::compressionRate . ' variables. Compressing data...'
				);
			}
			
			// Return
			return;
		}
		
		final public function log($logText) {
			$this->logData[] = array(
				'Type' => 'info',
				'Data' => $logText
			);
			
			// Array too big?
			if(count($this->logData) % self::compressionRate == 0) {
				$serialized = serialize($this->logData);
				$this->compressedLog[] = gzcompress($serialized, 9);
				unset($this->logData);
				$this->logData = array();
				$this->logData[] = array(
					'Type' => 'info',
					'Data' => 'More than ' . self::compressionRate . ' log entries. Compressing data...'
				);
			}
		}
		
		final public function warning($logText) {
			$this->logData[] = array(
				'Type' => 'warning',
				'Data' => $logText
			);
		}
		
		final public function error($logText) {
			$this->logData[] = array(
				'Type' => 'error',
				'Data' => $logText
			);
		}
		
		final private function syslog($logType, $logText) {
			$this->sysLog[] = array(
				'Type' => $logType,
				'Data' => $logText
			);
		}
		
		final private function processVariable($varName, $varData, $indent = 0) {
			// Get type
			echo '<div class="easyDebug variables varEntry indent" style="width: ' . $indent . 'px;"></div>';
			if (is_numeric($varData)) {
				echo '<div class="easyDebug variables varEntry type" style="background-color: ' . self::colorNumeric . ';">N</div>';
			} else if (is_string($varData)) {
				echo '<div class="easyDebug variables varEntry type" style="background-color: ' . self::colorString . ';">S</div>';
			} else if (is_bool($varData)) {
				echo '<div class="easyDebug variables varEntry type" style="background-color: ' . self::colorBoolean . ';">B</div>';
			} else if (is_array($varData)) {
				echo '<div class="easyDebug variables varEntry type" style="background-color: ' . self::colorArray . ';">A</div>';
			} else if (is_object($varData)) {
				echo '<div class="easyDebug variables varEntry type" style="background-color: ' . self::colorObject . ';">O</div>';
			} else {
				echo '<div class="easyDebug variables varEntry type" style="background-color: ' . self::colorUnknown . ';">U</div>';
			}
			
			// Print name
			echo '<div class="easyDebug variables varEntry name" style="width: ' . (self::width / 2 - 24 - $indent) . 'px;">' . $varName . '</div>';
			
			
			// Print data
			if (is_string($varData) && !is_numeric($varData)) {
				echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">' . htmlspecialchars($varData) . '</div>';
			} else if (is_numeric($varData)) {
				echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">' . $varData . '</div>';
			} else if (is_bool($varData)) {
				if($varData === true) {
					echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">true</div>';
				} else {
					echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">false</div>';
				}
			} else if (is_array($varData)) {
				if(count($varData) > 0) {
					echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; "><a href="#" onClick="easyDebug_toggleVar(' . $this->actualBlock . '); return false;">Toggle array (' . count($varData) . ' elements)</a></div>';
					echo '<div id="easyDebug_variableDetail_id' . $this->actualBlock . '" style="display: none;">';
					$this->actualBlock++;
					foreach($varData as $key => $value) {
						$this->processVariable($key, $value, $indent + 20);
					}
					echo '</div>';
				} else {
					echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">(Empty array)</div>';
				}
			} else if (is_object($varData)) {
				if(count((array) $varData) > 0) {
					echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; "><a href="#" onClick="easyDebug_toggleVar(' . $this->actualBlock . '); return false;">Toggle object (' . count((array) $varData) . ' properties)</a></div>';
					echo '<div id="easyDebug_variableDetail_id' . $this->actualBlock . '" style="display: none;">';
					$this->actualBlock++;
					foreach($varData as $key => $value) {
						$this->processVariable($key, $value, $indent + 20);
					}
					echo '</div>';
				} else {
					echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">(Empty object)</div>';
				}
			} else {
				echo '<div class="easyDebug variables varEntry value" style="width: ' . (self::width / 2 - 2) . 'px; ">N/A (Unsupported type)</div>';
			}
		}
		
		final public function header() {
			// CSS files
			echo '<link href="css/easyDebug.css" rel="stylesheet" type="text/css">';
			// JS files
			echo '<script src="js/jquery.min.js" type="text/javascript"></script>';
			echo '<script src="js/easyDebug.js" type="text/javascript"></script>';
		}
		
		final public function show() {
			// Create syslog
			$this->syslog('info', 'easyDebug v1.1.3 beta');
			$this->syslog('info', 'PHP version: ' . phpversion());
			$this->syslog('info', 'Detected memory limit: ' . $this->memoryLimit);
			$this->syslog('info', 'Peak memory usage: ' . round(memory_get_peak_usage() / 1024 / 1024, 2) . 'M');

			// Create debugger button
			echo '<div class="easyDebug button" id="easyDebug_button" onClick="easyDebug_toggle();">easyDebug</div>';
			// Create debugger menu
			echo '
<div class="easyDebug window" id="easyDebug_window" style="display: none; width: ' . self::width . 'px; height: ' . self::height . 'px;">
	<div class="easyDebug menuButton active" id="easyDebug_button_console" onClick="easyDebug_toggleMenu(\'console\');" style="width: ' . (self::width / self::menuSize - 2) . 'px;">Console</div>
	<div class="easyDebug menuButton" id="easyDebug_button_variables" onClick="easyDebug_toggleMenu(\'variables\');" style="width: ' . (self::width / self::menuSize - 2) . 'px;">Variables</div>
			';
			// Create console window
			echo '<div class="easyDebug menuWindow" id="easyDebug_window_console" style="width: ' . self::width . 'px; height: ' . (self::height - 21) . 'px;">';
			// System log
			echo '<div class="easyDebug console logFrame" style="width: ' . (self::width) . 'px; height: ' . self::height . 'px;">';
			foreach($this->sysLog as $log) {
				echo '<div class="easyDebug console logEntry ' . $log['Type'] . '" style="width: ' . (self::width - 22) . 'px; ">' . $log['Data'] . '</div>';
			}
			echo '<div class="easyDebug console logSep" style="width: ' . self::width . 'px; "></div>';
			// Compressed log
			foreach($this->compressedLog as $data) {
				$logList = unserialize(gzuncompress($data));
				foreach($logList as $log) {
					echo '<div class="easyDebug console logEntry ' . $log['Type'] . '">' . $log['Data'] . '</div>';
				}
			}
			// Uncompressed log
			foreach($this->logData as $log) {
				echo '<div class="easyDebug console logEntry ' . $log['Type'] . '">' . $log['Data'] . '</div>';
			}
			echo '</div></div>';
			// Create variables window
			echo '<div class="easyDebug menuWindow" id="easyDebug_window_variables" style="display: none; width: ' . self::width . 'px; height: ' . (self::height - 21) . 'px;">';
			echo '<div class="easyDebug variables varFrame" style="width: ' . (self::width) . 'px; height: ' . self::height . 'px;">';
			// Create compressed variable list
			foreach($this->compressedData as $key => $compressed) {
				$uncompressed = unserialize(gzuncompress($compressed));
				unset($this->compressedData[$key]);
				foreach($uncompressed as $data) {
					$varName = $data['Name'];
					$varData = json_decode($data['Data']);
					
					// Show variable entry
					echo '<div class="easyDebug variables varEntry">';
					$this->processVariable($varName, $varData);
					echo '</div>';
				}
			}
			// Create variable list
			foreach($this->actualData as $data) {
				$varName = $data['Name'];
				$varData = json_decode($data['Data']);
				
				// Show variable entry
				echo '<div class="easyDebug variables varEntry" style="width: ' . (self::width) . 'px;">';
				$this->processVariable($varName, $varData);
				echo '</div>';
			}
			echo '</div></div>';
			// Close debugger div
			echo '</div>';
		}
	}
?>