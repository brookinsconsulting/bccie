<?php

class BaseHandler {
	//place holder
	function exportAttribute(&$attribute, $separationChar)	{
	}

	//escape the string to use it in a CSV file type
	function escape($stringtoescape, $separationChar) {
	    $stringtoescape = preg_replace("(\r\n|\n|\r)", " ", $stringtoescape); 
		return addcslashes($stringtoescape, "$separationChar\0..\37\177..\377");
	}
}
?>