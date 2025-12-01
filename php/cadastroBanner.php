<?php
session_start();
require "conexao.php"; // ajuste o caminho se necessário

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if (!empty($_FILES['imagem']['name'])) {

    // Pasta para salvar banners
    $pasta = "../uploads/banners/";
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    // Nome único para evitar conflitos
    $nomeImg = uniqid() . "-" . basename($_FILES['imagem']['name']);
    $caminho = $pasta . $nomeImg;

    // Move o arquivo para a pasta
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {

        // Adiciona no banco
        $sql = "INSERT INTO banners (usuario_id, imagem, data_criacao) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $usuario_id, $nomeImg);
        $stmt->execute();

        $_SESSION['sucesso_banner'] = "Banner cadastrado com sucesso!";
    } else {
        $_SESSION['erro_banner'] = "Erro ao enviar a imagem.";
    }

} else {
    $_SESSION['erro_banner'] = "Nenhuma imagem selecionada.";
}

// Volta para o painel
header("Location: ../painel.php");
exit;
