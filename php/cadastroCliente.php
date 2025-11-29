<?php
session_start();
require 'conexao_login.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---------------------------
    // CAPTURA E TRATAMENTO
    // ----------------------------

    $nome = trim($_POST['nome'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $senha_input = $_POST['senha'] ?? "";
    $telefone = trim($_POST['tel'] ?? "");

    // Evita campo vazio ou inexistente
    if ($nome === "" || $email === "" || $senha_input === "" || $telefone === "") {
        $_SESSION['msg_cadastro'] = "Preencha todos os campos.";
        header("Location: ../login.php");
        exit;
    }

    // Hash da senha
    $senha = password_hash($senha_input, PASSWORD_DEFAULT);

    // Limpa telefone
    $telefone_limpo = preg_replace('/\D/', '', $telefone);


    // ---------------------------
    // 1. VALIDAR SE EMAIL JÁ EXISTE
    // ---------------------------

    $checkEmail = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $_SESSION['msg_cadastro'] = "Este e-mail já possui cadastro.";
        header("Location: ../login.php");
        exit;
    }
    $checkEmail->close();


    // ---------------------------
    // 2. CADASTRAR USUÁRIO
    // ---------------------------

    $sql = "INSERT INTO usuarios (nome, email, senha, telefone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $senha, $telefone_limpo);

    if ($stmt->execute()) {

        $usuario_id = $conn->insert_id;

        // ---------------------------
        // 3. GERAR SLUG ÚNICO
        // ---------------------------

        $slug = strtolower($nome);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, "-");

        // Evitar slug duplicado
        $checkSlug = $conn->prepare("SELECT id FROM lojas WHERE slug = ?");
        $checkSlug->bind_param("s", $slug);
        $checkSlug->execute();
        $checkSlug->store_result();

        if ($checkSlug->num_rows > 0) {
            $slug .= "-" . $usuario_id;
        }
        $checkSlug->close();


        // ---------------------------
        // 4. UPLOAD DA IMAGEM
        // ---------------------------

        $imagem_nome = null;

        if (!empty($_FILES['imagem']['name'])) {

            $pasta = __DIR__ . "/../uploads/lojas/";

            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

            // Evitar extensões indevidas
            $permitidas = ['png', 'jpg', 'jpeg', 'webp'];
            if (!in_array($ext, $permitidas)) {
                $_SESSION['msg_cadastro'] = "Formato de imagem não permitido.";
                header("Location: ../login.php");
                exit;
            }

            // Nome seguro e único
            $imagem_nome = "loja_" . $usuario_id . "_" . time() . "." . $ext;

            move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $imagem_nome);
        }


        // ---------------------------
        // 5. CRIAR LOJA
        // ---------------------------

        $sqlLoja = "INSERT INTO lojas (usuario_id, nome_fantasia, slug, imagem, telefone) 
            VALUES (?, ?, ?, ?, ?)";
        $stmtLoja = $conn->prepare($sqlLoja);
        $stmtLoja->bind_param("issss", $usuario_id, $nome, $slug, $imagem_nome, $telefone_limpo);

        $stmtLoja->execute();
        $stmtLoja->close();

        // Mensagem e redirecionamento
        $_SESSION['msg_cadastro'] = "Loja criada com sucesso!";
        $_SESSION['slug_loja'] = $slug;

    } else {
        $_SESSION['msg_cadastro'] = "Erro ao cadastrar usuário: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../login.php");
    exit;
}
?>