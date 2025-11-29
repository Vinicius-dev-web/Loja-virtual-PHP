<?php
session_start();

$erro = $_SESSION['erro_login'] ?? "";
$msg_cadastro = $_SESSION['msg_cadastro'] ?? "";
$slug_loja = $_SESSION['slug_loja'] ?? ""; // ← PEGANDO O SLUG

// ❗ IMPORTANTE: só apague depois de pegar os valores
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

    <title>Criar loja</title>
</head>

<body>
    <div class="form">

        <!-- Formulário de Login -->

        <form method="POST" action="php/cadastroCliente.php" enctype="multipart/form-data">

            <span>Cadastrar loja</span>

            <label for="inputImagemRegister" id="imageRegisterLabel">

                <i class="bi bi-person-fill" id="iconeLabelRegister"></i>

                <span id="textoLabelRegister">Sua logo*</span>

                <input type="file" name="imagem" id="inputImagemRegister" accept="image/*" hidden required>

                <img src="" alt="" id="previewImagemRegister" hidden>

            </label>

            <label class="nomeLabel" for="nomeLabel">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="nome" id="nomeLabel" placeholder="Empresa" required>
            </label>

            <label class="emailLabel" for="emailLabel">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" id="emailLabel" placeholder="Email" required>
            </label>

            <label class="telLabel" for="telLabel">
                <i class="bi bi-telephone-fill"></i>
                <input type="text" name="tel" id="telLabel" placeholder="Telefone" required>
            </label>

            <label class="senhaLabel" for="senhaLabel">
                <i class="bi bi-key-fill"></i>
                <input type="password" name="senha" id="senhaLabel" placeholder="Senha" required>
            </label>

            <button type="submit"><b>CADASTRAR</b></button>
            <button type="button" onclick="window.location.href='login.php'"><b>ENTRAR</b></button>

        </form>

    </div>
</body>

<script>
    document.getElementById("telLabel").addEventListener("input", function () {
        let v = this.value.replace(/\D/g, "");
        if (v.length > 11) v = v.slice(0, 11);

        if (v.length > 6) {
            this.value = `(${v.slice(0, 2)}) ${v.slice(2, 7)}-${v.slice(7)}`;
        } else if (v.length > 2) {
            this.value = `(${v.slice(0, 2)}) ${v.slice(2)}`;
        } else {
            this.value = v;
        }
    });
</script>

<!-- JS correto para a pré-visualização -->
<script>
    document.getElementById("inputImagemRegister").addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const preview = document.getElementById("previewImagemRegister");
                preview.src = e.target.result;
                preview.hidden = false;

                // esconder ícone e texto
                document.getElementById("iconeLabelRegister").style.display = "none";
                document.getElementById("textoLabelRegister").style.display = "none";
            };

            reader.readAsDataURL(file);
        }
    });
</script>

</html>
