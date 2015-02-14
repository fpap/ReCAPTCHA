<?php
/**
 * Sample PHP code to use reCAPTCHA V2.
 */

require_once "recaptchalib.php";

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = "";
$secret = "";
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = "en";

// The response object from reCAPTCHA
$resp = null;

// set up proxy parameters
$proxy_opts = array(
	CURLOPT_PROXY => "127.0.0.1",
	CURLOPT_PROXYPORT => 80,
	CURLOPT_PROXYTYPE => "HTTP",
	CURLOPT_PROTOCOLS => "CURLPROTO_HTTP | CURLPROTO_HTTPS",
	CURLOPT_PROXYUSERPWD => "user:password",
	CURLOPT_FAILONERROR => true,  // use only during tests
);

$reCaptcha = new ReCaptcha($secret, $proxy_opts);

// Was there a reCAPTCHA response?
if ($_POST["g-recaptcha-response"]) {
	try {
		$resp = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	} catch (ReCaptchaException $e) {
		// handle exception
	}
}
?>
<html>
<head><title>reCAPTCHA Example</title></head>
<body>
<?php
if (is_object($resp) && $resp->success === true) {
	echo "You got it!";
}
?>
<form action="?" method="post">
	<div class="g-recaptcha" data-sitekey="<?php echo $siteKey;?>"></div>
	<script type="text/javascript"
			src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang;?>">
	</script>
	<br/>
	<input type="submit" value="submit" />
</form>
</body>
</html>
