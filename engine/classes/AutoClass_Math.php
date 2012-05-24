<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Math
 */
class Math {
	/**
	 * @param float $min
	 * @param float $max
	 * @return mixed
	 */
	static function mt_randf($min, $max) {
		return $min + abs($max - $min) * mt_rand(0, mt_getrandmax())/mt_getrandmax(); 
	}

	/**
	 * @param int $rolls
	 * @param int $die
	 * @return int
	 */
	static function nDn($rolls, $die) {
		$total = 0;
		for ($i=0; $i < $rolls; $i++) { 
			$total += mt_rand(0, $die);
		}
		return $total;
	}

	/**
	 * @param int $rolls
	 * @param float $die
	 * @return float
	 */
	static function nDnf($rolls, $die) {
		$total = 0;
		for ($i=0; $i < $rolls; $i++) { 
			$total += self::mt_randf(0, $die);
		}
		return $total;
	}
}