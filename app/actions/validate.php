<?php 
	function isWord($input){
		return (preg_match('/^[a-zA-Z_]+( [a-zA-Z_]+)*$/', $input));
	}

	function isValidLength($input){
		return ((strlen($input) >= 3) && (strlen($input) <=25));
	}

	function isValidProductName($input){
		return (isWord($input) && (isValidLength($input)));
	}

	function isValidCost($input){
		return (preg_match('/^[1-9][0-9]*$/', $input));
	}
?>