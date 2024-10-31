<?php
if (function_exists('socket_create')) {
    echo "Sockets estan habilitados";
} else {
    echo "Sockets no estan habilitados";
}
$host = 'localhost';
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, $host, $port);
socket_listen($socket);

$clients = [];

while (true) {
    $read = array_merge([$socket], $clients);
    socket_select($read, $write, $except, null);

    if (in_array($socket, $read)) {
        $newClient = socket_accept($socket);
        $clients[] = $newClient;
        performHandshake($newClient, $host, $port);
        unset($read[array_search($socket, $read)]);
    }

    foreach ($read as $client) {
        $data = @socket_recv($client, $buffer, 2048, 0);
        if ($data === false || $data == 0) {
            socket_close($client);
            unset($clients[array_search($client, $clients)]);
            continue;
        }

        $decodedData = unmask($buffer);
        echo "Mensaje recibido: $decodedData\n";

        
        $decryptedMessage = decryptMessage($decodedData);
        echo "Mensaje descifrado: $decryptedMessage\n"; 

        foreach ($clients as $sendClient) {
            if ($sendClient != $client) {
                socket_write($sendClient, mask(encryptMessage($decryptedMessage)));
            }
        }
    }
}

function performHandshake($client, $host, $port) {
    $headers = socket_read($client, 1024);
    if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $matches)) {
        $key = base64_encode(pack('H*', sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                   "Upgrade: websocket\r\n" .
                   "Connection: Upgrade\r\n" .
                   "Sec-WebSocket-Accept: $key\r\n\r\n";
        socket_write($client, $upgrade);
    }
}

function unmask($payload) {
    $length = ord($payload[1]) & 127;
    if ($length == 126) {
        $masks = substr($payload, 4, 4);
        $data = substr($payload, 8);
    } elseif ($length == 127) {
        $masks = substr($payload, 10, 4);
        $data = substr($payload, 14);
    } else {
        $masks = substr($payload, 2, 4);
        $data = substr($payload, 6);
    }
    $text = '';
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }
    return $text;
}

function mask($text) {
    $b1 = 0x81;
    $length = strlen($text);

    if ($length <= 125) {
        $header = pack('CC', $b1, $length);
    } elseif ($length > 125 && $length < 65536) {
        $header = pack('CCn', $b1, 126, $length);
    } else {
        $header = pack('CCNN', $b1, 127, $length);
    }

    return $header . $text;
}

function encryptMessage($message) {
    // La clave de cifrado
    $key = "mi_clave_secreta";  
    return base64_encode(openssl_encrypt($message, 'aes-128-ecb', $key, OPENSSL_RAW_DATA));
}

function decryptMessage($ciphertext) {
    // La clave de descifrado
    $key = "mi_clave_secreta";  
    return openssl_decrypt(base64_decode($ciphertext), 'aes-128-ecb', $key, OPENSSL_RAW_DATA);
}
?>