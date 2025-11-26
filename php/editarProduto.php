<?php
session_start();
require "conexao.php";

if (!isset($_POST['id'])) {
    exit; // silencioso
}

$id = intval($_POST['id']);
$nome = $_POST['nome'];
$preco = floatval($_POST['preco']);
$usuario_id = $_SESSION['usuario_id'];

// Atualizar sem imagem
$sql = "UPDATE produtos SET nome = ?, preco = ? WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdii", $nome, $preco, $id, $usuario_id);
$stmt->execute();

// Se não enviou imagem → finaliza silenciosamente
if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] == 4) {
    exit;
}

// Se enviou imagem nova
$pasta = "../uploads/";
if (!is_dir($pasta)) mkdir($pasta, 0777, true);

$nomeImagem = uniqid() . "-" . $_FILES['imagem']['name'];
$caminhoImagem = "uploads/" . $nomeImagem;
move_uploaded_file($_FILES['imagem']['tmp_name'], "../" . $caminhoImagem);

// Atualizar imagem
$sql2 = "UPDATE produtos SET imagem = ? WHERE id = ? AND usuario_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("sii", $caminhoImagem, $id, $usuario_id);
$stmt2->execute();

exit;
