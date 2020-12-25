<?php
error_reporting(E_ALL);
$sleep_time = 2;

if (isset($_GET['sleep'])) $sleep_time = $_GET['sleep'];
if (isset($_GET['text'])) {
	$body = $_GET['text'];
} else {
	$d = new DateTime();
	$body = "[".$_SERVER["REQUEST_URI"]."]";
	$body .= ": [".$d->format("Y-m-d H:i:s.u")."] [".gethostname()."] Hello World, slept ".$sleep_time."s\n";
	if (!isset($_GET['sleep'])) {
		$body .= "Use 'sleep' in query string to set sleep time (e.g. '?sleep=1')\n";
	}
}
if ($sleep_time) sleep($sleep_time);
echo $body;
