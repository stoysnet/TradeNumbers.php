<?php

namespace TradeNumbers;

class GTIN {
	private static function _calcCheckDigit($noCheck) {
		$padded = array_merge(
			str_split(str_repeat('0', 16 - count($noCheck))),
			$noCheck
		);
		
		$sum = 0;
		for ($i = 0; $i < count($padded); $i += 1) {
			$sum += (((($i % 2) * 2) + 1) * intval($padded[$i]));
		}
		
		$result = intval(ceil($sum / 10) * 10 - $sum);
		
		return $result;
	}
	
	public static function calcCheckDigit($noCheck) {
		return self::_calcCheckDigit(str_split($noCheck));
	}
	
	public static function isValid($withCheck) {
		$withCheck = strval($withCheck);
		if (!preg_match('/^\d+$/', $withCheck)) {
			return false;
		}
		$noCheck = str_split($withCheck);
		$checkDigit = intval(array_pop($noCheck));
		return $checkDigit === self::_calcCheckDigit($noCheck);
	}
}
