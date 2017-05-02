<?php

namespace chaensel;

/**
 * Class Tools
 *
 * @package chaensel
 *
 * This class is used for building some websites.
 * It does several things - just read the code
 *
 * @author  Christian Haensel <chris@chaensel.de>
 * @link    https:/chaensel.de
 *
 */


class Tools {

	public static function displayErrors( $displayErrors = true ) {
		if ( $displayErrors ) {
			ini_set( 'display_errors', 1 );
			error_reporting( E_ALL );
		} else {
			ini_set( 'display_errors', 0 );
		}
	}

	/**
	 * Log a string to a standard or defined log file.
	 *
	 * @param string      $logString
	 * @param string|null $logfile
	 *
	 * @return bool
	 */
	public static function logtoFile( string $logString, string $logfile = null ) {
		$logfile = $logfile ?? $logfile  ?? "log.txt";
		if ( ! file_exists( $logfile ) ) {
			touch( $logfile );
		}
		$log = time() . "\t" . $logString . "\n";
		if ( file_put_contents( $logfile, $log, FILE_APPEND ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Display data using either print_r or var_dump
	 *
	 * @param null $data
	 * @param bool $var_dump
	 */
	static function pre( $data = null, $var_dump = false ) {
		echo '<pre>';
		if ( $var_dump ) {
			var_dump( $data );
		} else {
			print_r( $data );
		}

		echo '</pre>';
	}


	/**
	 * Displays a http://placehold.it placeholder image
	 *
	 * @param int   $width
	 * @param int   $height
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public static function placeholdit( $width = 300, $height = null, array $options = [] ) {
		$background_color = $options['background-color'] ?? $options['background-color'] ?? null;
		$text_color       = $options['text-color'] ?? $options['text-color'] ?? null;
		$text             = $options['text'] ?? $options['text'] ?? null;
		$image_type       = $options['type'] ?? $options['type'] ?? 'png';
		$src              =
			'https://placehold.it/' . $width . 'x' . ( $height ?? $height ?? $width ) . '.' . $image_type . '/' . $background_color . '/' . $text_color . '/?text=' . ( $text ?? urlencode( $text ) ?? null );
		$image            = '<img src="' . $src . '" />';

		return $image;
	}


	/**
	 * Get something from remote by using curl.
	 *
	 * @param null $url
	 *
	 * @return bool
	 */
	public static function getData( $url = null ) {
		$returnvalue = null;
		if ( ! is_null( $url ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			$returnvalue = curl_exec( $ch );
			curl_close( $ch );
		}

		return $returnvalue;
	}


	/**
	 * Get random user data from http://uinames.com/api/
	 *
	 * @param array $options
	 *
	 * Available options:
	 * amount:  1 ... 500, default: 1
	 * gender:  female | male, default: *
	 * region:  country name, i.e. "germany", default: *
	 * minlen:  minimum length of characters in a name
	 * maxlen:  maximum length of characters in a name
	 * ext:     extended information, such as CC, password, photo. default: false
	 *
	 * @return bool
	 */
	public static function getRandomUserData( array $options = [] ) {
		$url = "http://uinames.com/api/?";
		if ( count( $options ) > 0 ):
			foreach ( $options as $ok => $ov ):
				$url .= "&" . $ok . '=' . $ov;
			endforeach;
		endif;

		return self::getData( $url );
	}


	/**
	 * Reverse geocode a lat lng pair using the free https://geocode.xyz API
	 *
	 * @param null $latitude
	 * @param null $longitude
	 *
	 * @return bool|null
	 */
	public static function reverseGeoCode( $latitude = null, $longitude = null ) {
		if ( ! is_null( $latitude ) && ! is_null( $longitude ) ) {
			$url = "https://geocode.xyz/" . $latitude . "," . $longitude . "?geoit=json";

			return self::getData( $url );
		}

		return null;
	}

	/**
	 * Map an IP to a country using the free service of https://api.ip2country.info
	 * Uses the user's IP if no IP parameter given
	 *
	 * @param null $ip
	 *
	 * @return bool
	 */
	public static function ip2geo( $ip = null ) {
		return self::getData( "https://api.ip2country.info/ip?" . ( $ip ?? $ip ?? $_SERVER['REMOTE_ADDR'] ) );
	}

	/**
	 * Returns a random Chuck Norris joke from https://api.chucknorris.io
	 *
	 * @return object | bool
	 */
	public static function chuck() {
		return self::getData( "https://api.chucknorris.io/jokes/random" );
	}

	/**
	 * Generate a random human-readable passwort
	 *
	 * @param int  $length       The length of the password to be returned
	 * @param bool $appendNumber Should a double-digit number be appended to the password?
	 *
	 * @return string
	 */
	public static function generateRandomPassword( $length = 16, $appendNumber = true ) {
		if ( ( $length % 2 ) !== 0 ) { // Length paramenter must be a multiple of 2
			$length = $length + 1;
		}

		if ( $appendNumber ) {
			$length = $length - 2;
		}; // Makes room for the two-digit number on the end
		$conso    = array( 'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z' );
		$vocal    = array( 'a', 'e', 'i', 'o', 'u' );
		$password = '';
		srand( (double) microtime() * 1000000 );
		$max = $length / 2;
		for ( $i = 1; $i <= $max; $i ++ ) {
			$password .= $conso[ rand( 0, 19 ) ];
			$password .= $vocal[ rand( 0, 4 ) ];
		}
		if ( $appendNumber ) {
			$password .= rand( 10, 99 );
		}
		$newpass = $password;

		return $newpass;
	}

}
