<?php
error_reporting(E_ALL);
$sleep_time = 10;

if (php_sapi_name() == "cli") {
	ini_set('memory_limit', '1G');
	$http = new Swoole\HTTP\Server("0.0.0.0", 8080, SWOOLE_BASE);
	$http->on('request', function ($request, $response) use ($sleep_time) {
		if (isset($request->get['sleep'])) $sleep_time = $request->get['sleep'];
		if (isset($request->get['text'])) {
			$body = $request->get['text'];
		} else { 
			$d = new DateTime();
			$body = "[".$d->format("Y-m-d H:i:s.u")."] [".gethostname()."] Hello World, slept ".$sleep_time."s\n";
			$body = "[".$request->server["request_uri"]."?".$request->server["query_string"]."]: ".$body;
		}
		if (!isset($request->get['sleep'])) {
			$body .= "Use 'sleep' in query string to set sleep time (e.g. '?sleep=1')\n";
		}
	    co::sleep($sleep_time);
	    $response->write($body);
	    $response->end();
	});

	$http->start();
} else {
	if (isset($_GET['sleep'])) $sleep_time = $_GET['sleep'];
	if (isset($_GET['text'])) {
		$body = $_GET['text'];
	} else {
		$d = new DateTime();
		$body = "[".$d->format("Y-m-d H:i:s.u")."] [".gethostname()."] Hello World, slept ".$sleep_time."s\n";
		$body = "[".$_SERVER["REQUEST_URI"]."]: ".$body;
	}
	if (!isset($_GET['sleep'])) {
		$body .= "Use 'sleep' in query string to set sleep time (e.g. '?sleep=1')\n";
	}
    sleep($sleep_time);
	echo $body;
}