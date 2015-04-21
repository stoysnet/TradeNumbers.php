<?php
namespace TradeNumbers;

function classify($digits) {
	$digits = strval($digits);
	$notValid = function () {
		return false;
	};
	$type = 'unknown';
	$validator = $notValid;
	switch (strlen($digits)) {
	case 14:
		$type = 'GTIN-14';
		$validator = array('TradeNumbers\GTIN', 'isValid');
		break;
	case 13:
		switch (substr($digits, 0, 3)) {
		case '978':
			$type = 'ISBN-13/GTIN-13/EAN-13';
			$validator = array('TradeNumbers\ISBN', 'isValid');
			break;
		case '979':
			$type = 'ISBN-13/GTIN-13/EAN-13';
			$validator = array('TradeNumbers\ISBN', 'isValid');
			break;
		default: 
			$type = 'GTIN-13/EAN-13';
			$validator = array('TradeNumbers\GTIN', 'isValid');
		}
		break;
	case 10:
		$type = 'ISBN-10';
		$validator = array('TradeNumbers\ISBN', 'isValid');
		break;
	case 12:
		$type = 'GTIN-12/UPC-A';
		$validator = array('TradeNumbers\GTIN', 'isValid');
		break;
	case 8:
		$type = 'GTIN-8/EAN-8';
		$validator = array('TradeNumbers\GTIN', 'isValid');
		break;
	}
	if (!call_user_func($validator, $digits)) {
		return 'unknown';
	}
	return $type;
}
