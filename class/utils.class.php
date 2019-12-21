<?php
declare(strict_types=1);

/**
 *
 */
class Utils {

	/**
	 * @param mixed $var
	 * @param bool $var_dump
	 */
	static function debug ( $var, bool $var_dump = false ) {
		echo "<br><pre>Print_r ::<br>";
		print_r( $var );
		echo "</pre>";
		if ( $var_dump ) {
			echo "<br><pre>Var_dump ::<br>";
			var_dump( $var );
			echo "</pre><br>";
		};
	}

	/**
	 * Prints formatted number: 1.000[,00]
	 * @param mixed $number
	 * @param int $dec_count [optional] default=2 <p> Number of decimals.
	 * @return string
	 */
	static function fNumber ( $number, int $dec_count = 2 ): string {
		return number_format( (float)$number, $dec_count, ',', '.' );
	}

	/**
	 * Check feedback variable, and prevent resending form on page refresh or back button.
	 * @return string $feedback
	 */
	static function check_feedback_POST() : string {
		// Stop form resending
		if ( !empty($_POST) or !empty($_FILES) ){
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit();
		}

		// Check the feedback from Session data
		$feedback = isset($_SESSION["feedback"]) ? $_SESSION["feedback"] : "";
		unset($_SESSION["feedback"]);
		return $feedback;
	}

	static function geoDistance($lon1, $lat1, $lon2, $lat2) {
		$R = 6371; // Radius of the earth in km
		$dLat = deg2rad($lat2 - $lat1);
		$dLon = deg2rad($lon2 - $lon1);
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $R * $c; // Distance in km

		return $d;
	}
}
