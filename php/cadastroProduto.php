<?php
require "./conexao.php";

// Recebe os dados do formulário
$nome = $_POST['nome'];
$preco = $_POST['preco'];

// Pasta pública onde a imagem será salva
$pasta = "../uploads/"; // ajuste conforme a estrutura do seu projeto
if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
}

// Arquivo enviado
$arquivo = $_FILES['imagem'];
$nomeOriginal = basename($arquivo['name']);
$tipoArquivo = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
$nomeImagem = uniqid() . "-" . $nomeOriginal;

// Tipos permitidos
$tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// Tamanho máximo permitido (5MB)
$tamanhoMaximo = 5 * 1024 * 1024;

if (!in_array($tipoArquivo, $tiposPermitidos)) {
    die("Erro: Tipo de arquivo não permitido. Envie apenas JPG, PNG, GIF ou WEBP.");
}

if ($arquivo['size'] > $tamanhoMaximo) {
    die("Erro: Arquivo muito grande. O tamanho máximo é 5MB.");
}

// Move o arquivo
if (move_uploaded_file($arquivo['tmp_name'], $pasta . $nomeImagem)) {

    // Caminho que será salvo no banco (relativo à raiz pública)
    $caminhoImagem = "uploads/" . $nomeImagem;

    // Insere no banco
    $sql = "INSERT INTO produtos (nome, preco, imagem) VALUES ('$nome', '$preco', '$caminhoImagem')";

    if ($conn->query($sql)) {
        header("Location: ../painel.php?sucesso=1");
        exit;
    } else {
        echo "Erro ao cadastrar produto: " . $conn->error;
    }

} else {
    echo "Erro ao enviar a imagem. Verifique permissões da pasta uploads.";
}

$conn->close();
?>
