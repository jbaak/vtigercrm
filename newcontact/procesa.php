<?php

$contact = [];

$contact = [
    "name" => $_POST["name"],
    "lastname" => $_POST["lastname"],
    "phone" => $_POST["phone"],
    "email" => $_POST["email"],
];

echo json_encode($contact);