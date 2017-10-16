<?php

$app->get('/', function() use ($app) {
    return response()->json([
        'version' => $app->version(),
        'time' => (new \DateTime())->format('d/m/Y h:i:s e'),
    ]);
});