<?php

namespace TradeNumbers;
use \Exception;

class ISBN {
	public static function isbn10to13($digits) {
		$noCheck = '978' . substr(strval($digits), 0, 9);
		return $noCheck . GTIN::calcCheckDigit($noCheck);
	}
	
	public static function isbn13to10($digits) {
		$sub = substr(strval($digits), 3, 12);
		return $sub . self::calcCheckDigit($sub);
	}
	
	private static function _calcCheckDigit($noCheck) {
		$sum = 0;
		
		for ($i = 0; $i < count($noCheck); $i += 1) {
			$sum += ($i + 1) * intval($noCheck[$i]);
		}
		
		$result = $sum % 11;
		
		if ($result === 10) {
			return 'X';
		}
		
		return $result;
	}
	
	public static function isValid($withCheck) {
		$withCheck = strval($withCheck);
		if (!preg_match('/^\d+[xX]?$/', $withCheck)) {
			return false;
		}
		if (strlen($withCheck) === 13) {
			if (preg_match('/^(978|979)/', $withCheck)) {
				return GTIN::isValid($withCheck);
			}
			return false;
		}
		$noCheck = str_split($withCheck);
		$popped = strtoupper(array_pop($noCheck));
		$checkDigit = intval($popped);
		if ($popped === 'X') {
			$checkDigit = 'X';
		}
		return $checkDigit === self::_calcCheckDigit($noCheck);
	}
	
	public static function calcCheckDigit($noCheck) {
		if (count($noCheck) === 9) {
			return self::_calcCheckDigit(str_split(strval($noCheck)));
		}
		if (count($noCheck) === 12) {
			return GTIN::calcCheckDigit(str_split(strval($noCheck)));
		}
		throw new Exception(
			'To generate a check digit a candidate ISBN number must be either 9 or 12 characters long.'
		);
	}
}
