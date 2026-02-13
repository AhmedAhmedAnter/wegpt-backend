<?php

/**
 * Send a JSON response and exit
 *
 * @param mixed $data The data to return
 * @param int $status The HTTP status code (default 200)
 * @return void
 */
function sendResponse($data, $status = 200)
{
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Send an error response and exit
 *
 * @param string $message The error message
 * @param int $status The HTTP status code
 * @return void
 */
function sendError($message, $status = 400)
{
    sendResponse(['error' => $message], $status);
}

/**
 * Get JSON input from request body
 *
 * @return array|null
 */
function getJsonInput()
{
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}
