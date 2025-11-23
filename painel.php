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

            <h1>MarcosTech</h1>

            <ul>
                <li data-target="produtos">
                    <i class="bi bi-speedometer2"></i>
                    <span>PAINEL</span>
                </li>
                <li data-target="create">
                    <i class="bi bi-images"></i>
                    <span>PRODUTO</span>
                </li>
                <li data-target="users">
                    <i class="bi bi-people"></i>
                    <span>USUÁRIOS</span>
                </li>
                <li data-target="cog">
                    <i class="bi bi-building"></i>
                    <span>EMPRESA</span>
                </li>
                <li data-target="loja" onclick="loja()">
                    <i class="bi bi-basket3"></i>
                    <span>Loja</span>
                </li>
            </ul>

        </div>

        <div class="logout" id="logout" onclick="logout()">
            <i class="bi bi-box-arrow-right"></i>
            <span>SAIR</span>
        </div>

    </div>

    <nav class="main-content">

        <div class="adm-info-up">
            <h2>Marcos</h2>
            <span>Administrador</span>
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

                        $sql = "SELECT * FROM produtos ORDER BY id DESC";
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

                <h2>Cadastrar Produto</h2>

                <!-- repara no path: php/cadastroProduto.php -->
                <form action="php/cadastroProduto.php" method="POST" enctype="multipart/form-data">

                    <label>Nome do Produto</label>
                    <input type="text" name="nome" required>

                    <label>Preço</label>
                    <input type="number" name="preco" step="0.01" required>

                    <label>Imagem</label>
                    <input type="file" name="imagem" accept="image/*" required>

                    <button type="submit">Cadastrar</button>
                </form>

            </div>
        </section>

        <!-- resto das sections (users, cog, loja) fica igual ao que você já tinha -->

        <section class="users" id="users">
            <div class="form-div">
                <form action="">
                    <label for="file" id="file-user">
                        <i class="bi bi-person-bounding-box"></i>
                        <span>Adicione um perfil</span>
                        <input type="file" name="" id="file" hidden>
                    </label>

                    <input type="text" name="nome_usuario" id="name-user" placeholder="Nome do funcionário" required>

                    <input type="tel" name="" id="email-user" placeholder="Email do usuário" required>

                    <input type="password" name="" id="pwr-user" placeholder="Senha" required>

                    <input type="tel" name="" id="tel-user" placeholder="Número de telefone (opcional)">

                    <input type="text" name="" id="date-user" placeholder="Data de nascimento (opcional)">

                    <button type="button" class="submit" id="submit">Criar</button>

                </form>
            </div>
        </section>

        <section class="cog" id="cog">
            <div class="form-div">
                <form action="">
                    <label for="file" id="file-cog">
                        <i class="bi bi-building-gear"></i>
                        <span>Adicione uma logo</span>
                        <input type="file" name="" id="file-cog-image" hidden>
                    </label>

                    <input type="text" name="" id="cog-name-user" placeholder="Nome da empresa">
                    <input type="tel" name="" id="cog-tel-user" placeholder="Número de telefone (opcional)">

                    <button class="submit" id="submit">Salvar</button>
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

                <button onclick="pagephp()">Tente esse</button>
            </div>
        </section>

    </main>

    <div class="edit-produto-table" id="edit-produto-table">
        <div class="edit-table">
            <div class="form">
                <input type="hidden" id="edit-id">
                <label>Nome do produto</label>
                <input type="text" id="edit-nome" placeholder="Nome do produto">

                <label>Preço do produto</label>
                <input type="number" id="edit-preco" placeholder="Preço do produto" step="0.01">

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

<script>
    function logout() {
        location.href = "login.html";
    }
</script>

<script src="js/painel.js"></script>
<script src="js/datas.js"></script>
<script src="js/editTable.js"></script>
<script src="js/links.js"></script>

<script>
    function loja() {
        // window.open('https://marcos-loja.vercel.app/', '_blank');
        window.open('http://localhost/marcos_lojavirtual/index.php', '_blank');
    }
</script>

<!-- TEMA / MODO CLARO/ESCURO -->

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
        if (btn) {
            btn.addEventListener("click", toggleTheme);
        }
    });
</script>

</html>