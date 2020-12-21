<?php
error_reporting(E_ALL);

$sleep_time = 20;

$d = new DateTime();
$body = "[".$d->format("ymd_His.u")."] [".gethostname()."] Hello World"." #".rand(1000, 9999)."\n";

if (php_sapi_name() == "cli") {
	ini_set('memory_limit', '1G');
	$http = new Swoole\HTTP\Server("0.0.0.0", 9501, SWOOLE_BASE);

	$http->on('request', function ($request, $response) use ($sleep_time, $body) {
		if (@$request->get['sleep']) $sleep_time = $request->get['sleep'];
		if (@$request->get['text']) $body = $request->get['text'];
	    co::sleep($sleep_time);
		$body = "[".$request->server["request_uri"]."?".$request->server["query_string"]."]: ".$body;
	    $response->write($body);
	    $response->end();
	});

	$http->start();
} else {
	if (@$_GET['sleep']) $sleep_time = $_GET['sleep'];
	if (@$_GET['text']) $body = $_GET['text'];
    sleep($sleep_time);
	$body = "[".$_SERVER["REQUEST_URI"]."]: ".$body;

	echo $body;
}