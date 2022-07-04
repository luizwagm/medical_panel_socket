<?php

/**
 * Documentation
 * https://openswoole.com/docs/modules/swoole-websocket-server-on
 */

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$server = new Server("0.0.0.0", 9500);

$server->on("Start", function (Server $server) {
    echo "Swoole WebSocket Server started at 0.0.0.0:9500\n";
});

$server->on('Open', function (Server $server, Request $request) {
    echo "Connection open: {$request->fd}\n";
});

$server->on('message', function (Server $server, Frame $frame) {
    $conexoes = $server->connections;
    $origem = $frame->fd;

    foreach ($conexoes as $conexao) {
        if ($conexao === $origem) continue;
        $server->push($conexao, json_encode(['type' => 'chat', 'text' => $frame->data]));
    }
});

$server->on('Close', function (Server $server, int $fd) {
    echo "Connection close: {$fd}\n";
});

$server->on('Disconnect', function (Server $server, int $fd) {
    echo "Connection disconnect: {$fd}\n";
});

$server->start();
