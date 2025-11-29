<?php
session_start();
require './conexao_login.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Erro: usuário não autenticado.");
}

$usuario_id = $_SESSION['usuario_id'];

// Caminhos das pastas de imagens
$produtosDir = "C:/xampp/htdocs/marcos_lojavirtual/uploads/produtos/";
$lojasDir = "C:/xampp/htdocs/marcos_lojavirtual/uploads/lojas/";

/* ======================================================
   1. BUSCAR IMAGEM DA LOJA DO USUÁRIO
====================================================== */
$sqlLoja = $conn->prepare("SELECT slug, imagem FROM lojas WHERE usuario_id = ?");
$sqlLoja->bind_param("i", $usuario_id);
$sqlLoja->execute();
$resultLoja = $sqlLoja->get_result();
$loja = $resultLoja->fetch_assoc();
$sqlLoja->close();

if ($loja) {
    // Apagar a imagem da loja
    if (!empty($loja['imagem'])) {
        $arquivoLoja = $lojasDir . $loja['imagem'];
        if (file_exists($arquivoLoja)) {
            unlink($arquivoLoja);
        }
    }
}

/* ======================================================
   2. BUSCAR E APAGAR IMAGENS DOS PRODUTOS DO USUÁRIO
====================================================== */
$sqlProd = $conn->prepare("SELECT imagem FROM produtos WHERE usuario_id = ?");
$sqlProd->bind_param("i", $usuario_id);
$sqlProd->execute();
$resultProd = $sqlProd->get_result();

while ($prod = $resultProd->fetch_assoc()) {
    if (!empty($prod['imagem'])) {
        $arquivoProd = $produtosDir . $prod['imagem'];
        if (file_exists($arquivoProd)) {
            unlink($arquivoProd);
        }
    }
}
$sqlProd->close();

/* ======================================================
   3. DELETAR PRODUTOS DO BANCO
====================================================== */
$stmtProd = $conn->prepare("DELETE FROM produtos WHERE usuario_id = ?");
$stmtProd->bind_param("i", $usuario_id);
$stmtProd->execute();
$stmtProd->close();

/* ======================================================
   4. DELETAR LOJA DO BANCO
====================================================== */
$stmtLoja = $conn->prepare("DELETE FROM lojas WHERE usuario_id = ?");
$stmtLoja->bind_param("i", $usuario_id);
$stmtLoja->execute();
$stmtLoja->close();

/* ======================================================
   5. DELETAR USUÁRIO
====================================================== */
$stmtUser = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmtUser->bind_param("i", $usuario_id);
$stmtUser->execute();
$stmtUser->close();

/* ======================================================
   6. FINALIZAR SESSÃO
====================================================== */
session_destroy();

header("Location: ../login.php?msg=Conta+excluída+com+sucesso");
exit;
?>
