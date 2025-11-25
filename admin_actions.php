<?php
session_start();
require_once __DIR__ . '/php/conexao_login.php';

// Proteção: somente admin
if (!isset($_SESSION['usuario_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') {
    header('Location: login.php');
    exit;
}

// Verifica se a ação é deletar usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete_user') {
    
    // Valida CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf_token']) {
        die("Token inválido!");
    }

    $userId = (int)$_POST['id'];

    // 1. Deletar produtos do usuário
    $stmt = $conn->prepare("DELETE FROM produtos WHERE usuario_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // 2. Deletar loja do usuário
    $stmt = $conn->prepare("DELETE FROM lojas WHERE usuario_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // 3. Deletar usuário
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        $_SESSION['msg_admin'] = "Usuário e todos os dados deletados com sucesso!";
    } else {
        $_SESSION['msg_admin'] = "Erro ao deletar usuário: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header("Location: AdminMasterPanel.php");
    exit;
}
?>
