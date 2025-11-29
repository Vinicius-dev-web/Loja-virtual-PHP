<?php
session_start();

if (!isset($_POST["slug"])) {
    echo json_encode(["sucesso" => false]);
    exit;
}

$slug = $_POST["slug"];
$index = intval($_POST["index"]);

$carrinho_key = "carrinho_" . $slug;

if (isset($_SESSION[$carrinho_key][$index])) {
    unset($_SESSION[$carrinho_key][$index]);

    // reorganiza índices
    $_SESSION[$carrinho_key] = array_values($_SESSION[$carrinho_key]);

    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["sucesso" => false]);
}
?>
