<?php

namespace App\Http\Controllers;

class ClienteFrontControl extends Controller {

    private $port;
    private $host;

    public function __construct() {
        $this->port = 8888;
        $this->host = "127.0.0.1";
    }

    public function request() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            return "erro";
        }
        if(socket_connect($socket, $this->host, $this->port) === false) {
            return "erro";
        }
        $obj = json_encode(array("teste" => "tesadsasdasdsdfsdgfdhgfh df sdfg sd gadstysdtgsdfgsdfdste"));
        $size = strlen($obj);
        if (socket_send($socket, $obj, $size, MSG_EOF) !== false) {
            $next = "";
            $message = "";
            while ($next = socket_read($socket, 2048)) {
                $message .= $next;
            }
        }
        return $message;
    }

}
