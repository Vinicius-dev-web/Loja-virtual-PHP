<?php
session_start();

$slug = $_POST["slug"] ?? "";

if ($slug === "") {
    echo json_encode(["status" => "erro", "msg" => "slug não recebido"]);
    exit;
}

$key = "carrinho_" . $slug;

if (!isset($_SESSION[$key])) {
    $_SESSION[$key] = [];
}

$nome = $_POST["nome"] ?? "";
$preco = $_POST["preco"] ?? "";
$imagem = $_POST["imagem"] ?? "";

$produto = [
    "nome" => $nome,
    "preco" => $preco,
    "imagem" => $imagem
];

$_SESSION[$key][] = $produto;

echo json_encode([
    "status" => "ok",
    "total_itens" => count($_SESSION[$key])
]);
