<?php
require "conexao.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = $_POST['preco'];

if(isset($_FILES['imagem']) && $_FILES['imagem']['name'] != "") {
    $pasta = "../uploads/";
    if(!is_dir($pasta)) mkdir($pasta, 0777, true);

    $nomeImagem = uniqid() . "-" . $_FILES['imagem']['name'];
    $caminhoImagem = $pasta . $nomeImagem;
    move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem);

    $sql = "UPDATE produtos SET nome='$nome', preco='$preco', imagem='$caminhoImagem' WHERE id=$id";
} else {
    $sql = "UPDATE produtos SET nome='$nome', preco='$preco' WHERE id=$id";
}

if($conn->query($sql)) {
    echo "Produto atualizado com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}
?>
