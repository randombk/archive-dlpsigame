<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Math {
	static function mt_randf($min, $max) {
		return $min + abs($max - $min) * mt_rand(0, mt_getrandmax())/mt_getrandmax(); 
	}
	
	static function nDn($rolls, $die) {
		$total = 0;
		for ($i=0; $i < $rolls; $i++) { 
			$total += mt_rand(0, $die);
		}
		return $total;
	}
	
	static function nDnf($rolls, $die) {
		$total = 0;
		for ($i=0; $i < $rolls; $i++) { 
			$total += self::mt_randf(0, $die);
		}
		return $total;
	}
}