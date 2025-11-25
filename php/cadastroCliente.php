<?php
session_start();  // sempre no topo

require 'conexao_login.php';

$msg = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // 1. Cadastrar usuário
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {

        // PEGAR O ID DO NOVO USUÁRIO
        $usuario_id = $conn->insert_id;

        // 2. Gerar slug da loja a partir do nome do usuário
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $nome));

        // Evitar slug duplicado — acrescenta número se já existir
        $checkSlug = $conn->prepare("SELECT id FROM lojas WHERE slug = ?");
        $checkSlug->bind_param("s", $slug);
        $checkSlug->execute();
        $checkSlug->store_result();

        if ($checkSlug->num_rows > 0) {
            $slug .= "-" . $usuario_id;
        }

        // 3. Criar loja automaticamente
        $sqlLoja = "INSERT INTO lojas (usuario_id, nome_fantasia, slug) VALUES (?, ?, ?)";
        $stmtLoja = $conn->prepare($sqlLoja);
        $stmtLoja->bind_param("iss", $usuario_id, $nome, $slug);
        $stmtLoja->execute();

        $msg = "Usuário e loja criados com sucesso!";

        // 🔥 IMPORTANTE → PASSAR O SLUG PARA O login.php EXIBIR O LINK
        $_SESSION['slug_loja'] = $slug;

    } else {
        $msg = "Erro ao cadastrar usuário: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Armazena a mensagem na sessão para exibir no login.php
$_SESSION['msg_cadastro'] = $msg;
header("Location: ../login.php");
exit;
?>
