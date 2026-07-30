<?php
/**
 * Shared bootstrap for every API endpoint.
 * - Starts the session (so login state persists across API calls, same as before)
 * - Connects to the database
 * - Always responds with JSON
 * - Provides small helpers so every endpoint responds in a consistent shape
 */

session_start();

// Frontend and backend are served from the same site (same scheme+host+port),
// just different folders, so plain same-origin fetch() + cookies works with
// no CORS headers needed. If you ever host the frontend on a different origin,
// you'd need to add CORS headers here and set fetch's credentials to 'include'.

header('Content-Type: application/json');

require_once __DIR__ . '/../../database/db_connect.php';

/** Send a JSON response and stop execution. */
function send_json($data, $http_code = 200) {
    http_response_code($http_code);
    echo json_encode($data);
    exit;
}

/** Standard shape for a failure response. */
function send_error($message, $http_code = 400) {
    send_json(['success' => false, 'message' => $message], $http_code);
}

/** Standard shape for a success response, optionally with extra data merged in. */
function send_success($extra = []) {
    send_json(array_merge(['success' => true], $extra));
}

/** Require a logged-in regular user; otherwise stop with a 401. */
function require_user() {
    if (!isset($_SESSION['user_id'])) {
        send_error('You must be logged in.', 401);
    }
    return $_SESSION['user_id'];
}

/** Require a logged-in admin; otherwise stop with a 401. */
function require_admin() {
    if (!isset($_SESSION['admin_id'])) {
        send_error('Admin login required.', 401);
    }
    return $_SESSION['admin_id'];
}
