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

// ADICIONADO → Buscar ID da loja e cor do tema
$stmt = $conn->prepare("SELECT id, slug, imagem, cor_tema FROM lojas WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$loja = $result->fetch_assoc();

// ADICIONADO → Variáveis necessárias
$loja_id = $loja['id'] ?? "";
$slug_loja = $loja['slug'] ?? "";
$imagem_loja = $loja['imagem'] ?? "";
$cor_tema = $loja['cor_tema'] ?? "#000000"; // Valor padrão
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

    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/painel.css">

    <title>Painel de controle - Administativo</title>

</head>

<body>

    <div class="painel">

        <div class="painel-info">

            <?php if (!empty($imagem_loja)): ?>
                <img src="uploads/lojas/<?php echo $imagem_loja; ?>" alt="Foto da loja">
            <?php endif; ?>

            <br>

            <h2>
                <?php echo $_SESSION['usuario']; ?>
            </h2>

            <ul>
                <li data-target="produtos">
                    <i class="bi bi-speedometer2"></i>
                    <span>PAINEL</span>
                </li>
                <li data-target="create">
                    <i class="bi bi-images"></i>
                    <span>CRIAR</span>
                </li>
                <!-- <li data-target="orders">
                    <i class="bi bi-cart3"></i>
                    <span>PEDIDOS</span>
                </li>
                <li data-target="msg-sec">
                    <i class="bi bi-chat-square-dots"></i>
                    <span>CHAT</span>
                </li> -->
                <li data-target="cog">
                    <i class="bi bi-building-gear"></i>
                    <span>LOJA</span>
                </li>
                <li data-target="loja" id="menuLoja">
                    <i class="bi bi-arrow-up-right-square"></i>
                    <span>Minha Loja</span>
                </li>
                <li data-target="" id="">
                    <i class="las la-crown"></i>
                    <span>VIP</span>
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
            <h2>Painel de controle</h2>
            <span>Administrativo</span>
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

                <h1>Produto</h1>

                <form action="php/cadastroProduto.php" method="POST" enctype="multipart/form-data" class="form-prod">

                    <label id="labelImagemProd">
                        <i class="bi bi-card-image" id="iconeLabelProd"></i>
                        <span id="textoLabelProd">Coloque uma imagem*</span>

                        <input type="file" id="inputImagem" name="imagem" accept="image/*" required hidden>

                        <img id="previewImagemProd">
                    </label>

                    <div class="values-create" id="values-create">

                        <div class="input-values-create">

                            <input type="text" name="nome" placeholder="Nome do produto*" maxlength="25" required>
                            <input type="text" name="preco" step="0.01" placeholder="Preço do produto*" maxlength="6"
                                required>

                            <input type="text" name="" id="description-prod"
                                placeholder="Descrição do produto. Ex.: Cor Amarelo, Azul">

                            <select name="" id="">

                                <option value="">Sem tamanho</option>
                                <option value="">PP</option>
                                <option value="">P</option>
                                <option value="">M</option>
                                <option value="">G</option>
                                <option value="">GG</option>

                            </select>

                        </div>

                        <button type="submit">Cadastrar</button>


                    </div>


                </form>

                <h1>Banner</h1>

                <form action="php/cadastroBanner.php" method="POST" enctype="multipart/form-data" class="form-banner">

                    <label id="labelImagemBanner">

                        <i class="bi bi-card-image" id="iconeLabelBanner"></i>

                        <span id="textoLabelBanner">Coloque uma imagem*</span>

                        <input type="file" name="imagem" accept="image/*" required hidden>

                        <img id="previewImagemBanner">

                    </label>

                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </section>

        <section class="orders" id="orders"></section>

        <section class="cog" id="cog">

            <div class="form-div">

                <form action="" method="POST">

                    <label id="labelImagemCog">

                        <i class="bi bi-card-image" id="iconeLabelCog"></i>
                        <span id="textoLabelCog">Coloque uma imagem*</span>

                        <input type="file" id="inputImagem" name="imagem" accept="image/*" hidden>

                        <img src="" alt="" id="previewImagemCog" hidden>

                    </label>

                    <div class="values-create" id="values-create">

                        <div class="input-values-create">

                            <input type="text" name="nome" placeholder="Nome da empresa*" maxlength="25">

                            <input type="text" name="" id="description-prod"
                                placeholder="Descrição da loja. Ex.: Loja de Roupas e Sapatos 😎✌️👕👖👟">
                            </textarea>


                            <select name="" id="">

                                <option value="">Sem descrição</option>
                                <option value="">Roupas</option>
                                <option value="">Sapatos</option>
                                <option value="">Roupas e sapatos</option>
                                <option value="">Eletrônicos</option>
                                <option value="">Informática</option>
                                <option value="">Eletrônicos e informática</option>
                                <option value="">Diversos</option>

                            </select>

                        </div>

                        <button type="submit">Cadastrar</button>


                    </div>


                </form>

                <form action="php/salvarCor.php" method="POST">
                    <label>Cor da Loja:</label>

                    <!-- ADICIONADO value para exibir cor salva -->
                    <input type="color" name="cor_tema" id="corLoja" value="<?= $cor_tema ?>">

                    <input type="hidden" name="loja_id" value="<?= $loja_id ?>">
                    <button type="submit">Salvar cor</button>
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

        </section>

    </main>

    <div class="edit-produto-table" id="edit-produto-table">

        <div class="edit-table">

            <div class="form">

                <input type="hidden" id="edit-id">
                <label>
                    <i class="bi bi-pencil"></i>
                    <input type="text" id="edit-nome" placeholder="Nome do produto" maxlength="25">
                </label>


                <label>
                    <i class="bi bi-pencil"></i>
                    <input type="number" id="edit-preco" placeholder="Preço do produto" step="0.01" maxlength="6">
                </label>

                <label class="upload-edit">
                    <i class="bi bi-image"></i>
                    <input type="file" id="edit-imagem" accept="image/*" hidden>
                </label>

                <button id="save-table">Salvar</button>

                <div class="btn-edit-table">
                    <button id="delete-produto">Excluir</button>
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
<script src="js/modais.js"></script>

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

<script>

    document.getElementById("labelImagemProd").addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const preview = document.getElementById("previewImagemProd");
                preview.src = e.target.result;
                preview.style.display = "block"; // mostra a imagem

                document.getElementById("iconeLabelProd").style.display = "none";
                document.getElementById("textoLabelProd").style.display = "none";
            };

            reader.readAsDataURL(file);
        }
    });

    document.getElementById("labelImagemBanner").addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const preview = document.getElementById("previewImagemBanner");
                preview.src = e.target.result;
                preview.style.display = "block";

                document.getElementById("iconeLabelBanner").style.display = "none";
                document.getElementById("textoLabelBanner").style.display = "none";
            };

            reader.readAsDataURL(file);
        }
    });

    document.getElementById("labelImagemCog").addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const preview = document.getElementById("previewImagemCog");
                preview.src = e.target.result;
                preview.style.display = "block";

                document.getElementById("iconeLabelCog").style.display = "none";
                document.getElementById("textoLabelCog").style.display = "none";
            };

            reader.readAsDataURL(file);
        }
    });

</script>


</html>