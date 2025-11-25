<?php
session_start();

$erro = $_SESSION['erro_login'] ?? "";
$msg_cadastro = $_SESSION['msg_cadastro'] ?? "";
$slug_loja = $_SESSION['slug_loja'] ?? ""; // PEGANDO O SLUG DA LOJA

// apagar depois que pegar
unset($_SESSION['erro_login'], $_SESSION['msg_cadastro'], $_SESSION['slug_loja']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Code:ital,wght@0,300..800;1,300..800&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Zalando+Sans+SemiExpanded:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">

    <title>Entrar</title>
</head>

<body>
    <div class="form">

        <!-- Formulário de Login -->
        <form method="POST" action="php/loginCliente.php">
            <span>MarcosTech</span>

            <label for="email">
                <i class="bi bi-person"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </label>

            <label for="senha">
                <i class="bi bi-key"></i>
                <input type="password" name="senha" id="senha" placeholder="Senha" required>
            </label>

            <button type="submit"><b>ENTRAR</b></button>
            <!-- <button type="button" onclick="window.location.href='cadastro.php'"><b>CRIAR CONTA</b></button> -->

            <div id="error" style="color: var(--cor-vermelho);">
                <b><?php echo $erro; ?></b>
            </div>

            <!-- Mostra mensagem de cadastro -->
            <div id="cadastro-msg" style="color: green; margin-top: 10px;">
                <b><?php echo $msg_cadastro; ?></b><br>

                <?php if (!empty($slug_loja)): ?>
                    <a href="loja/index.php?slug=<?php echo $slug_loja; ?>" target="_blank"
                        style="color: blue; font-weight: bold;">
                        👉 Acessar sua loja
                    </a>

                <?php endif; ?>
            </div>


        </form>

    </div>
</body>

</html>