<?php

require_once '../src/Services/CalculateDueDateService.php';

$submitTime = "2023-08-29 14:11";

$turnaroundTime = 3;

$calculate_due_date_serve = new CalculateDueDateService();

try {

    $res = $calculate_due_date_serve->calculateDueDate($submitTime, $turnaroundTime);

    header('Content-Type: application/json; charset=utf-8');

    $response = ["due_date" => $res];

    echo json_encode($response);

    exit();

} catch (Exception $e) {

    header('Content-Type: application/json; charset=utf-8');

    $message = "An error occurred while calculating the due date";

    $code = $e->getCode();

    $response = ["message" => $message, "code" => $code, "description" => $e->getMessage()];

    echo json_encode($response);

    exit();
}
