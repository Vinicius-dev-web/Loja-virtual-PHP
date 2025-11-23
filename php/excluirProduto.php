<?php
require "conexao.php";

$id = $_POST['id'];

$sql = "DELETE FROM produtos WHERE id=$id";
if($conn->query($sql)) {
    echo "Produto excluído com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}
?>
