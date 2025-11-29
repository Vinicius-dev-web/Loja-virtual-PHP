<?php
session_start();

if (!isset($_POST["slug"])) {
    echo json_encode(["status" => "erro"]);
    exit;
}

$slug = $_POST["slug"];
$carrinho_key = "carrinho_" . $slug;

$_SESSION[$carrinho_key] = [];

echo json_encode(["status" => "ok"]);
