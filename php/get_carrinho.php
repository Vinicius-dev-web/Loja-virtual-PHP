<?php
session_start();

// Se não receber slug, retorna carrinho vazio
if (!isset($_GET["slug"])) {
    echo json_encode([]);
    exit;
}

$slug = $_GET["slug"];

// Nome correto do carrinho
$carrinho_key = "carrinho_" . $slug;

// Se não existir ainda, retorna array vazio
if (!isset($_SESSION[$carrinho_key])) {
    echo json_encode([]);
    exit;
}

echo json_encode($_SESSION[$carrinho_key]);
