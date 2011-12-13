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
<pre>
EnableExplicit

Define windowID, editorGadget, windowEvent, oldCallback, detectedCharacterLimit, detectedLineLimit

; Custom window callback
Procedure callbackHandler(hWnd, uMsg, wParam, lParam)
  Shared oldCallback, detectedCharacterLimit, detectedLineLimit, editorGadget
  Protected scrollStyle, actualStyle, lineCount, charCount
  
  ; Disable all scrollbars
  Select uMsg
    Case #WM_SIZE
      ; Remove all settings, they're now invalid
      detectedCharacterLimit = 0
      detectedLineLimit = 0
      SendMessage_(hWnd, #EM_SETLIMITTEXT, 0, 0)
    Case #WM_KEYDOWN
      If wParam = #VK_RETURN
        ; Get line count
        lineCount = SendMessage_(hWnd, #EM_GETLINECOUNT, 0, 0)
        
        ; Text already long enough?
        If detectedLineLimit > 0 And lineCount = detectedLineLimit
          ; Block ENTER
          ProcedureReturn 0
        EndIf
      EndIf
    Case #WM_NCCALCSIZE
      scrollStyle = #WS_VSCROLL | #WS_HSCROLL
      actualStyle = GetWindowLong_(hWnd, #GWL_STYLE) 

      If (actualStyle & scrollStyle) <> 0
        ; Get char count
        lineCount = SendMessage_(hWnd, #EM_GETLINECOUNT, 0, 0)
        charCount = Len(GetGadgetText(editorGadget))
        
        ; Detect allowed chars
        detectedCharacterLimit = charCount - 1
        detectedLineLimit = lineCount - 1
        SendMessage_(hWnd, #EM_SETLIMITTEXT, detectedCharacterLimit, 0)
        RemoveGadgetItem(editorGadget, lineCount - 1)
        SendMessage_(hWnd, #EM_SCROLL, #SB_PAGEUP, 0)
        
        ; Disable scrollbars always
        SetWindowLong_(hWnd, #GWL_STYLE, actualStyle & (~scrollStyle))
      EndIf
  EndSelect
  
  ; Continue with normal window events
  ProcedureReturn CallWindowProc_(oldCallback, hWnd, uMsg, wParam, lParam)
EndProcedure

; Create window & check for success
windowID = OpenWindow(#PB_Any, 0, 0, 200, 200, "[EditorGadget] Line limit", #PB_Window_SystemMenu | #PB_Window_ScreenCentered)
If windowID
  ; Create editor gadget with custom callback
  editorGadget = EditorGadget(#PB_Any, 0, 0, 200, 200)
  SendMessage_(GadgetID(editorGadget), #EM_SETTARGETDEVICE, #Null, 0)
  oldCallback = SetWindowLong_(GadgetID(editorGadget), #GWL_WNDPROC, @callbackHandler())
  SetActiveGadget(editorGadget)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
  While detectedCharacterLimit = 0
                                                                                                                                                                                                                                                                                                                                                                          WindowEvent()                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
    keybd_event_(#VK_A, 0, 0, 0)
    keybd_event_(#VK_A, 0, #KEYEVENTF_KEYUP, 0)
  Wend
  SetGadgetText(editorGadget, "")                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
  
  ; Window event loop
  Repeat
    windowEvent = WaitWindowEvent()
  Until windowEvent = #PB_Event_CloseWindow
EndIf
; IDE Options = PureBasic 4.60 RC 2 (Windows - x64)
; CursorPosition = 62
; FirstLine = 14
; Folding = -
; EnableXP
</pre>