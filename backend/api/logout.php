<?php
require_once 'config.php';
session_unset();
session_destroy();
send_success(['message' => 'Logged out.']);
