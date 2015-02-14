<?php

namespace Google\ReCaptcha;

class Client
{
	private static $signupUrl = 'https://www.google.com/recaptcha/admin';

	private static $siteVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify?';

	private $secret;

	private static $version = 'php_1.0';

	private $curl_opts;

	/**
	 * Constructor.
	 * @param string $secret shared secret between site and ReCAPTCHA server.
	 * @param array  $curl_opt
	 * @throws LogicException
	 */
	public function __construct( $secret, array $curl_opt = array() )
	{
		if ( is_null( $secret ) || empty( $secret ) )
		{
			throw new LogicException( sprintf(
				'To use reCAPTCHA you must get an API key from <a href="%s">%s</a>',
				self::$signupUrl,
				self::$signupUrl
			) );
		}
		$this->secret = $secret;
		if ( ! empty( $curl_opts ) )
			$this->curl_opts = $curl_opts;
	}

	/**
	 * Encodes the given data into a query string format.
	 * @param array $data array of string elements to be encoded.
	 * @return string - encoded request.
	 */
	private function _encodeQS( $data )
	{
		$req = '';
		foreach ( $data as $key => $value )
		{
			$req .= $key . '=' . urlencode( stripslashes( $value ) ) . '&';
		}

		// Cut the last '&'
		$req = substr( $req, 0, strlen( $req ) - 1 );

		return $req;
	}

	/**
	 * Submits an HTTP GET to a reCAPTCHA server.
	 * @param string $path url path to recaptcha server.
	 * @param array  $data array of parameters to be sent.
	 * @return array response
	 * @throws ConnectionException
	 */
	private function _submitHTTPGet( $path, $data )
	{
		$request  = $this->_encodeQS( $data );

		// modified from: http://stackoverflow.com/a/6595108
		if ( function_exists( 'curl_version' ) )
		{
			$opts = array(
				CURLOPT_HEADER         => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_USERAGENT      => 'ReCaptcha '.self::$version,
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => 60,
				CURLOPT_TIMEOUT        => 60,
				CURLOPT_MAXREDIRS      => 5,
				CURLOPT_ENCODING       => '',
			);
			// check if we got overrides, or extra options (eg. proxy configuration)
			if ( is_array( $this->curl_opts ) && ! empty( $this->curl_opts ) ) {
				$opts = array_merge( $opts, $this->curl_opts );
			}
			$conn = curl_init( "{$path}{$request}" );
			curl_setopt_array( $conn, $opts );
			$response = curl_exec( $conn );

			// handle a connection error
			$errno = curl_errno( $conn );
			if ( $errno > 0 )
			{
				throw new ConnectionException( sprintf(
					'Fatal error while contacting reCAPTCHA. %d : %s'.
					$errno,
					curl_error( $conn )
				) );
			}
			curl_close( $conn );
		}
		else {
			$response = file_get_contents( "{$path}{$request}" );
		}

		return $response;
	}

	/**
	 * Calls the reCAPTCHA siteverify API to verify whether the user passes
	 * CAPTCHA test.
	 * @param string $remoteIp IP address of end user.
	 * @param string|null $response response string from recaptcha verification.
	 * @return Response
	 */
	public function verifyResponse( $remoteIp, $response )
	{
		// Discard empty solution submissions
		if ( is_null( $response ) || strlen( $response ) == 0 )
		{
			$recaptchaResponse             = new Response();
			$recaptchaResponse->success    = false;
			$recaptchaResponse->errorCodes = 'missing-input';

			return $recaptchaResponse;
		}

		$getResponse       = $this->_submitHttpGet(
			self::$siteVerifyUrl,
			array(
				'secret'   => $this->secret,
				'remoteip' => $remoteIp,
				'v'        => self::$version,
				'response' => $response
			)
		);
		$answers           = json_decode( $getResponse, true );
		$recaptchaResponse = new Response();

		if ( trim( $answers['success'] ) == true )
		{
			$recaptchaResponse->success = true;
			$recaptchaResponse->errorCodes = $answers['error-codes'];
		}
		else
		{
			$recaptchaResponse->success = false;
		}

		return $recaptchaResponse;
	}
}
