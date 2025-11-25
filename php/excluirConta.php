<?php
session_start();
require 'conexao.php'; // ajuste o caminho se necessário

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 1. Deleta produtos da loja
$stmtProd = $conn->prepare("DELETE FROM produtos WHERE usuario_id = ?");
$stmtProd->bind_param("i", $usuario_id);
$stmtProd->execute();
$stmtProd->close();

// 2. Deleta a loja
$stmtLoja = $conn->prepare("DELETE FROM lojas WHERE usuario_id = ?");
$stmtLoja->bind_param("i", $usuario_id);
$stmtLoja->execute();
$stmtLoja->close();

// 3. Deleta o usuário
$stmtUser = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmtUser->bind_param("i", $usuario_id);
$stmtUser->execute();
$stmtUser->close();

// 4. Destroi a sessão e redireciona para login
session_destroy();
header("Location: ../login.php?msg=Conta+excluída+com+sucesso");
exit;
?>
