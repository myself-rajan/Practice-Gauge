<?php

$qbo = env('CURRENT_QBO');

if($qbo == 'production'){
	$client_id = env('LIVE_QBO_CLIENT_ID'); 
    $client_secret = env('LIVE_QBO_CLIENT_SECRET');
    $base_url = env('LIVE_QBO_BASE');
} else {
	$client_id = env('QBO_CLIENT_ID'); 
    $client_secret = env('QBO_CLIENT_SECRET');
    $base_url = env('QBO_BASE');
}

return [
    'client_id' => $client_id, 
    'client_secret' => $client_secret,
    'base_url' => $base_url,
];



