<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'msg' => 'Não autenticado']);
    exit;
}

require "conexao.php";

$usuario_id = $_SESSION['usuario_id'];
$banner_id = $_POST['id'] ?? null;

if (!$banner_id) {
    echo json_encode(['success' => false, 'msg' => 'ID do banner não informado']);
    exit;
}

// Primeiro pega a imagem para remover do servidor
$stmt = $conn->prepare("SELECT imagem FROM banners WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $banner_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$banner = $result->fetch_assoc();

if ($banner) {
    $caminhoImagem = "uploads/banners/" . $banner['imagem'];
    if (file_exists($caminhoImagem)) {
        unlink($caminhoImagem); // remove a imagem do servidor
    }

    // Remove do banco
    $stmtDel = $conn->prepare("DELETE FROM banners WHERE id = ? AND usuario_id = ?");
    $stmtDel->bind_param("ii", $banner_id, $usuario_id);
    $stmtDel->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'msg' => 'Banner não encontrado']);
}
