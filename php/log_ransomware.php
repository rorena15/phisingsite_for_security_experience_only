<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$logLine = date('Y-m-d H:i:s') . " - Session: " . session_id() . " - " . $data['event'] . "\n";
file_put_contents(dirname(__FILE__) . '/../log.txt', $logLine, FILE_APPEND | LOCK_EX);
echo 'Logged';
