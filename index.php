<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Code:ital,wght@0,300..800;1,300..800&family=Zalando+Sans+SemiExpanded:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/loja.css">


    <title>Marcos Coutinho - A loja virtual</title>

</head>

<body>

    <nav>

        <label for="search">
            <i class="bi bi-search"></i>
            <input type="text" id="search" placeholder="Buscar produto ou palavra-chave">

            <button type="submit" id="btnBuscar" onclick="admlogin()">| Buscar</button>
        </label>

        <div class="icons-menu">

            <div class="bag-area">
                <i class="bi bi-handbag-fill" id="abrirCarrinho"></i>
                <span id="contadorCarrinho">0</span>
            </div>

            <div class="menu-mobile" id="abrirMenuMobile">
                <i class="bi bi-list"></i>
            </div>

        </div>

    </nav>

    <aside class="esquerda" id="esquerda">

        <div class="fecharMenuMobile" id="fecharMenuMobile">

            <i class="bi bi-arrow-left" style="margin-right: 10px;"></i>
            <span>Voltar</span>

        </div>

        <div class="menu-left">

            <div class="info-client">

                <img src="https://img.myloview.com.br/posters/funny-cartoon-monster-face-vector-monster-square-avatar-700-196485313.jpg"
                    alt="sem foto">

                <h4>User-1b7cxyz4</h4>

            </div>

            <ul>

                <li>
                    <i class="bi bi-house"></i>
                    <span>PAGINA INICIAL</span>
                </li>
                <li>
                    <i class="bi bi-columns-gap"></i>
                    <span>PRODUTOS</span>
                </li>
                <li>
                    <details>

                        <summary>

                            <i class="bi bi-funnel"></i>
                            <span>FILTRAR</span>

                        </summary>

                        <div class="list-deitails">
                            <span>Camisas</span>
                            <span>Perfumes</span>
                            <span>Outros</span>

                        </div>

                    </details>
                </li>

            </ul>

        </div>

    </aside>

    <!-- ----------- CARRINHO ----------- -->

    <div id="carrinho">
        <button id="fecharCarrinho">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2>Seu Carrinho</h2>
        <div id="lista-carrinho"></div>
        <button class="btn-finalizar" id="btnFinalizar">Finalizar Pedido</button>
    </div>

    <main class="page" id="page">

        <section class="home" id="home"></section>
        <!-- ----------- Produtos ----------- -->

        <section class="produtos" id="produtos">

            <header>
                <img src="https://files.tecnoblog.net/wp-content/uploads/2025/06/Capa-TBR-Eletronicos-de-Consumo-1060x596.png"
                    alt="Sem foto">
            </header>

            <h1>Produtos</h1>

            <div id="lista-produtos" class="lista-produtos">
                <?php
                require "./php/conexao.php";

                $sql = "SELECT * FROM produtos ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($p = $result->fetch_assoc()) {
                        echo '
            <div class="card">
                <img src="' . $p['imagem'] . '" alt="' . $p['nome'] . '" loading="lazy">

                <h3>' . $p['nome'] . '</h3>
                <p class="preco">R$ ' . number_format($p['preco'], 2, ',', '.') . '</p>

                <button class="btn-comprar" 
                    data-produto="' . $p['nome'] . '" 
                    data-preco="' . $p['preco'] . '" 
                    data-imagem="' . $p['imagem'] . '">
                    Adicionar ao Carrinho
                </button>
            </div>
            ';
                    }
                } else {
                    echo '<p>Nenhum produto cadastrado.</p>';
                }

                $conn->close();
                ?>
            </div>


        </section>

    </main>



    <script src="js/compras.js"></script>
    <script src="js/links.js"></script>
    <script src="js/produtos.js"></script>
    <script src="js/pegarProduto.js"></script>

</body>

</html>