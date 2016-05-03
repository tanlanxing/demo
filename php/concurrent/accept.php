<?php
$serv = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr)
	or dir("create server failed");

for ($i=0; $i < 32; $i++) {
	if (pcntl_fork() ==  0) {
		while (true) {
			$conn = stream_socket_accept($serv);
			if ($conn == false) continue;
			$request = fread($conn, 4096);
			fwrite($conn, $request);
			fclose($conn);
		}
		exit(0);
	}
}
