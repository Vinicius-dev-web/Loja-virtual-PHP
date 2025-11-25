<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$erro = $_SESSION['erro_login'] ?? "";
$msg_cadastro = $_SESSION['msg_cadastro'] ?? "";
unset($_SESSION['erro_login'], $_SESSION['msg_cadastro']);

// Conexão e pegar o slug da loja
require "php/conexao.php";
$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT slug FROM lojas WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$loja = $result->fetch_assoc();
$slug_loja = $loja['slug'] ?? "";
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
    <link rel="stylesheet" href="css/painel.css">
    <link rel="stylesheet" href="css/modal.css">

    <title>Painel de controle - Administativo</title>

</head>

<body>

    <div class="painel">

        <div class="painel-info">

            <h1>Painel</h1>

            <ul>
                <li data-target="produtos">
                    <i class="bi bi-speedometer2"></i>
                    <span>PAINEL</span>
                </li>
                <li data-target="create">
                    <i class="bi bi-images"></i>
                    <span>PRODUTO</span>
                </li>
                <li data-target="orders">
                    <i class="bi bi-cart3"></i>
                    <span>PEDIDOS</span>
                </li>
                <li data-target="msg-sec">
                    <i class="bi bi-chat-square-dots"></i>
                    <span>CHAT</span>
                </li>
                <li data-target="loja" id="menuLoja">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>Loja</span>
                </li>
            </ul>

        </div>

        <div class="logout" id="logout" onclick="window.location.href='php/logout.php'">
            <i class="bi bi-box-arrow-right"></i>
            <span>SAIR</span>
        </div>

    </div>

    <nav class="main-content">

        <div class="adm-info-up">
            <h2><?php echo $_SESSION['usuario']; ?></h2>
            <span>Painel de controle</span>
        </div>

        <div class="info-now-up">
            <span id="dataAtual"></span>

            <button id="toggleThemeBtn" class="toggle-theme-btn">
                <i id="themeIcon" class="bi bi-brightness-high"></i>
            </button>
        </div>

    </nav>

    <main class="page">

        <section class="produtos" id="produtos">
            <div class="tabela-container">
                <table class="tabela-estilo">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Hora</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require "php/conexao.php";

                        $usuario_id = $_SESSION['usuario_id'];
                        $sql = "SELECT * FROM produtos WHERE usuario_id = $usuario_id ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($p = $result->fetch_assoc()) {
                                echo "<tr>
                    <td><img src='" . $p['imagem'] . "' width='60'></td>
                    <td>" . $p['nome'] . "</td>
                    <td>R$ " . number_format($p['preco'], 2, ',', '.') . "</td>
                    <td>" . date('H:i', strtotime($p['data_criacao'])) . "</td>
                    <td>
                        <button class='btn-editar' onclick='editarProduto(" . $p['id'] . ", \"" . addslashes($p['nome']) . "\", " . $p['preco'] . ")'>
                            <i class='bi bi-pencil-square'></i> Editar
                        </button>
                    </td>
                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Nenhum produto cadastrado.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="create" id="create">
            <div class="form-div" id="file-create">
                <form action="php/cadastroProduto.php" method="POST" enctype="multipart/form-data">
                    <label>
                        <i class="bi bi-card-image"></i>
                        <span>Coloque uma imagem*</span>
                        <input type="file" name="imagem" accept="image/*" required hidden>
                    </label>
                    <input type="text" name="nome" placeholder="Nome do produto*" maxlength="25" required>
                    <input type="text" name="preco" step="0.01" placeholder="Preço do produto*" maxlength="6" required>
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </section>

        <section class="orders" id="orders"></section>

        <section class="cog" id="cog">
            <div class="form-div">
                <h3>Configurar Loja</h3>
                <form action="php/criarLoja.php" method="POST">
                    <input type="text" name="nome_loja" placeholder="Nome da loja (ex: minha-loja)" required>
                    <button type="submit">Criar loja</button>
                </form>
                <form action="php/excluirConta.php" method="POST"
                    onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita!')">
                    <button id="deleteAccount" type="submit">Excluir conta</button>
                </form>
            </div>
        </section>

        <section class="loja" id="loja">
            <div class="msg" id="msg">
                <h1>Você está acessando a loja...</h1>
                <span>Verifique se outra aba está aberta no seu navegador. Caso não esteja, considere como um erro
                    técnico ou que os arquivos ainda estejam sendo enviados ao servidor.</span>
                <img src="https://png.pngtree.com/png-clipart/20190120/ourmid/pngtree-go-to-bed-sleeping-pig-piggy-pig-sleeping-png-image_493040.png"
                    alt="error">
                <!-- <button>Tente esse</button> -->
            </div>
        </section>

        <section class="msg-sec" id="msg-sec">

            <ul class="painel-msg" id="painel-msg">

                <li class="profile-msg" data-target="chat-div">

                    <div class="profile-msg-card">

                        <img src="https://img.myloview.com.br/posters/funny-cartoon-monster-face-vector-monster-square-avatar-700-196485313.jpg"
                            alt="sem foto">

                        <h3>Vinicius</h3>

                    </div>

                    <div class="info-msg-card">

                        <span class="info-msg-card" id="msg-msg-card">+1 mensagem</span>

                    </div>

                </li>
                <li class="profile-msg" data-target="chat-div">

                    <div class="profile-msg-card">

                        <img src="https://img.myloview.com.br/posters/funny-cartoon-monster-face-vector-monster-square-avatar-700-196485313.jpg"
                            alt="sem foto">

                        <h3>Vinicius</h3>

                    </div>

                    <div class="info-msg-card">

                        <span class="info-msg-card" id="msg-msg-card">+1 mensagem</span>

                    </div>

                </li>

            </ul>

        </section>

        <section class="chat-div" id="chat-div">

            <div class="chat-form">

                <div class="info-cliente">

                    <img src="https://img.myloview.com.br/posters/funny-cartoon-monster-face-vector-monster-square-avatar-700-196485313.jpg"
                        alt="sem foto">
                    <h1>Cliente</h1>

                </div>

                <div class="msg-chat">
                    <span>Cliente: Olá</span>
                    <span>Cliente: Gostaria de saber qual o valor do Conjunto Baruk vermelho</span>
                    <span>Cliente: Quanto custa?</span>
                </div>

                <div class="chat-commands" id="chat-commands">

                    <div class="chat-commands-input" id="chat-commands-input">

                        <label for="file-chat">

                            <input type="file" name="" id="file-chat" hidden>

                            <i class="bi bi-plus-lg"></i>

                        </label>

                        <input type="text" name="" id="" placeholder="Fale com o cliente...">

                        <button type="submit">
                            <i class="bi bi-send"></i>
                        </button>

                    </div>

                </div>

                <div class="showImgMsg" id="showImgMsg" hidden>
                    <img src="" alt="">
                </div>

            </div>


            </div>
        </section>

    </main>

    <div class="edit-produto-table" id="edit-produto-table">
        <div class="edit-table">
            <div class="form">
                <input type="hidden" id="edit-id">
                <label>Nome do produto</label>
                <input type="text" id="edit-nome" placeholder="Nome do produto" maxlength="25">
                <label>Preço do produto</label>
                <input type="number" id="edit-preco" placeholder="Preço do produto" step="0.01" maxlength="6">
                <label>Imagem (opcional)</label>
                <input type="file" id="edit-imagem" accept="image/*">
                <button id="delete-produto">Excluir</button>
                <div class="btn-edit-table">
                    <button id="save-table">Salvar</button>
                    <button id="cancel">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="js/painel.js"></script>
<script src="js/datas.js"></script>
<script src="js/editTable.js"></script>
<script src="js/links.js"></script>

<!-- Tema / Modo claro/escuro -->
<script>
    function updateIcon(theme) {
        const icon = document.getElementById("themeIcon");
        if (!icon) return;
        if (theme === "light") {
            icon.classList.remove("bi-brightness-high");
            icon.classList.add("bi-moon");
        } else {
            icon.classList.remove("bi-moon");
            icon.classList.add("bi-brightness-high");
        }
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute("data-theme");
        const newTheme = currentTheme === "light" ? "dark" : "light";
        document.documentElement.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
        updateIcon(newTheme);
    }

    document.addEventListener("DOMContentLoaded", () => {
        const savedTheme = localStorage.getItem("theme") || "dark";
        document.documentElement.setAttribute("data-theme", savedTheme);
        updateIcon(savedTheme);

        const btn = document.getElementById("toggleThemeBtn");
        if (btn) btn.addEventListener("click", toggleTheme);

        // Loja - menu e botão
        const urlLoja = "loja/loja.php?slug=<?php echo $slug_loja; ?>";

        const liLoja = document.getElementById("menuLoja");
        if (liLoja) {
            liLoja.addEventListener("click", () => window.open(urlLoja, "_blank"));
            liLoja.querySelector("span").textContent = "Minha Loja";
        }

        const btnLoja = document.querySelector("#loja button");
        if (btnLoja) {
            btnLoja.addEventListener("click", () => window.open(urlLoja, "_blank"));
        }
    });
</script>

</html>