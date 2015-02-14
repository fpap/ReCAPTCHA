<?php
/**
 * Sample PHP code to use reCAPTCHA V2.
 */

if ( file_exists( __DIR__.'../src/recaptchalib.php' ) )
	require_once __DIR__.'../src/recaptchalib.php';

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = "";
$secret  = "";
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = "en";

// The response object from reCAPTCHA
$response = null;


$reCaptcha = new \Google\ReCaptcha\Client( $secret, array(
	CURLOPT_PROXY        => '127.0.0.1',
	CURLOPT_PROXYPORT    => 80,
	CURLOPT_PROXYTYPE    => 'HTTP',
	CURLOPT_PROTOCOLS    => 'CURLPROTO_HTTP | CURLPROTO_HTTPS',
	CURLOPT_PROXYUSERPWD => 'user:password',
	// use only during tests
	CURLOPT_FAILONERROR  => false,
) );

// Was there a reCAPTCHA response?
if ( isset( $_POST['g-recaptcha-response'] ) ) {
	try {
		$response = $reCaptcha->verifyResponse(
			$_SERVER['REMOTE_ADDR'],
			$_POST['g-recaptcha-response']
		);
	}
	catch ( \Google\ReCaptcha\ConnectionException $e ) {
		// handle exception
	}
}
?>
<html>
<head><title>reCAPTCHA Example</title></head>
<body>
<?php
if ( $response instanceof \Google\ReCaptcha\Response && $response->success ) {
	echo 'You got it!';
}
?>
<form action="" method="post">
	<div class="g-recaptcha" data-sitekey="<?php echo $siteKey;?>"></div>
	<script type="text/javascript"
			src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang;?>">
	</script>
	<br/>
	<input type="submit" value="submit" />
</form>
</body>
</html>
