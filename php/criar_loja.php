<?php
session_start();
require "conexao_login.php";

if (!isset($_SESSION['usuario_id'])) {
    die("Erro: usuário não autenticado.");
}

$usuario_id = $_SESSION['usuario_id'];
$nomeLoja = strtolower(trim($_POST['nome_loja']));

// segurança da URL
$nomeLoja = preg_replace('/[^a-z0-9\-]/', '-', $nomeLoja);

if (strlen($nomeLoja) < 3) {
    die("Nome da loja muito curto.");
}

// Atualiza no banco
$sql = "UPDATE usuarios SET loja_slug = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $nomeLoja, $usuario_id);

if ($stmt->execute()) {
    header("Location: ../painel.php?loja_criada=1");
    exit;
} else {
    echo "Erro ao criar loja: " . $stmt->error;
}

?>
