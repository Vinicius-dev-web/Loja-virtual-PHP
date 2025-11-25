<?php
session_start();

require_once "conexao_login.php";

$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);
$confirma = trim($_POST['confirma']);

// Verificações
if (empty($nome) || empty($email) || empty($senha) || empty($confirma)) {
    $_SESSION['erro_admin'] = "Preencha todos os campos!";
    header("Location: ../criar_admin.php");
    exit;
}

if ($senha !== $confirma) {
    $_SESSION['erro_admin'] = "As senhas não coincidem!";
    header("Location: ../criar_admin.php");
    exit;
}

// Verifica se email já existe
$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['erro_admin'] = "Email já cadastrado!";
    header("Location: ../criar_admin.php");
    exit;
}

$stmt->close();

// INSERIR ADMIN
$hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha, role) 
        VALUES (?, ?, ?, 'admin')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $hash);

if ($stmt->execute()) {

    $user_id = $stmt->insert_id;

    // ---------- A PARTIR DAQUI → CRIA LOJA SOMENTE PARA USUÁRIO COMUM ---------
    // Se você QUISER criar loja para admin também, deixe essa parte ativa.
    // Caso contrário, remova.

    // Criar slug base
    $slug_base = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nome));
    $slug_base = trim($slug_base, '-');

    $slug = $slug_base;
    $i = 1;

    // Verificar slug duplicado
    while (true) {
        $check = $conn->prepare("SELECT id FROM lojas WHERE slug = ?");
        $check->bind_param("s", $slug);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {
            break; // slug disponível
        }

        $slug = $slug_base . "-" . $i;
        $i++;
    }

    // Criar loja
    $sql_loja = "INSERT INTO lojas (usuario_id, nome_fantasia, slug) 
                 VALUES (?, ?, ?)";
    $stmt_loja = $conn->prepare($sql_loja);
    $stmt_loja->bind_param("iss", $user_id, $nome, $slug);
    $stmt_loja->execute();

    $_SESSION['ok_admin'] = "Admin criado com sucesso! Loja criada automaticamente.";

} else {
    $_SESSION['erro_admin'] = "Erro ao criar admin!";
}

$stmt->close();
$conn->close();

header("Location: ../criar_admin.php");
exit;
