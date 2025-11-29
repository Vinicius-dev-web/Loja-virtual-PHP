<?php
require "./php/conexao.php"; // Uma única conexão para toda a página
?>

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
    <link rel="stylesheet" href="css/index.css">

    <title>Bumi</title>

</head>

<body>

    <nav class="nav">

        <span class="logo" id="logo">
            <span>B</span>umi
        </span>

    </nav>

    <!-- <aside class="esquerda" id="esquerda">

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

    </aside> -->

    <header class="carrossel-container">

        <div class="carrossel-track">

            <img src="img/FRETEGRATIS.png" alt="">

            <img src="img/Banner moda feminina bolsa e acessorios desconto.png" alt="">

            <img src="img/Banner Moda Masculina Nova Coleção Moderno Preto e Cinza.png" alt="">

        </div>

    </header>

    <main class="page" id="page">

        <section class="home" id="home"></section>

        <!-- ----------- Produtos ----------- -->

        <section class="produtos" id="produtos">

            <h1>Produtos</h1>

            <div id="lista-produtos" class="lista-produtos">
                <?php
                $sql = "SELECT * FROM produtos ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($p = $result->fetch_assoc()) {

                        $nome = htmlspecialchars($p['nome']);
                        $imagem = htmlspecialchars($p['imagem']);
                        $preco = number_format($p['preco'], 2, ',', '.');

                        echo '
                        <div class="card">
                            <img src="' . $imagem . '" alt="' . $nome . '" loading="lazy">

                            <h3>' . $nome . '</h3>
                            <p class="preco">R$ ' . $preco . '</p>

                            <button class="btn-comprar" 
                                data-produto="' . $nome . '" 
                                data-preco="' . $p['preco'] . '" 
                                data-imagem="' . $imagem . '">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                        ';
                    }
                } else {
                    echo '<p>Nenhum produto cadastrado.</p>';
                }
                ?>

                <!-- <div class="card">
                    <img src="uploads/6927189ed988d-11420304253jaajbg8h6.jpeg" alt="">

                    <h3>Teste</h3>
                    <p class="preco">R$50,00</p>

                    <button class="btn-comprar" data-produto="' . $nome . '" data-preco="' . $p['preco'] . '"
                        data-imagem="">
                        Adicionar ao Carrinho
                    </button>
                </div>
                <div class="card">
                    <img src="uploads/6927189ed988d-11420304253jaajbg8h6.jpeg" alt="">

                    <h3>Teste</h3>
                    <p class="preco">R$50,00</p>

                    <button class="btn-comprar" data-produto="' . $nome . '" data-preco="' . $p['preco'] . '"
                        data-imagem="">
                        Adicionar ao Carrinho
                    </button>
                </div>
                <div class="card">
                    <img src="uploads/6927189ed988d-11420304253jaajbg8h6.jpeg" alt="">

                    <h3>Teste</h3>
                    <p class="preco">R$50,00</p>

                    <button class="btn-comprar" data-produto="' . $nome . '" data-preco="' . $p['preco'] . '"
                        data-imagem="">
                        Adicionar ao Carrinho
                    </button>
                </div>
                <div class="card">
                    <img src="uploads/6927189ed988d-11420304253jaajbg8h6.jpeg" alt="">

                    <h3>Teste</h3>
                    <p class="preco">R$50,00</p>

                    <button class="btn-comprar" data-produto="' . $nome . '" data-preco="' . $p['preco'] . '"
                        data-imagem="">
                        Adicionar ao Carrinho
                    </button>
                </div>
                <div class="card">
                    <img src="uploads/6927189ed988d-11420304253jaajbg8h6.jpeg" alt="">

                    <h3>Teste</h3>
                    <p class="preco">R$50,00</p>

                    <button class="btn-comprar" data-produto="' . $nome . '" data-preco="' . $p['preco'] . '"
                        data-imagem="">
                        Adicionar ao Carrinho
                    </button>
                </div>
                <div class="card">
                    <img src="uploads/6927189ed988d-11420304253jaajbg8h6.jpeg" alt="">

                    <h3>Teste</h3>
                    <p class="preco">R$50,00</p>

                    <button class="btn-comprar" data-produto="' . $nome . '" data-preco="' . $p['preco'] . '"
                        data-imagem="">
                        Adicionar ao Carrinho
                    </button>
                </div> -->
            </div>

        </section>

        <!-- ----------- Lojas ----------- -->
        <section class="lojas" id="lojas">

            <header style="display: none;">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Sem foto">
            </header>

            <h2>Lojas parceiras</h2>

            <div id="lista-lojas" class="lista-lojas">
                <?php
                $sql_lojas = "SELECT * FROM lojas ORDER BY id DESC";
                $result_lojas = $conn->query($sql_lojas);

                if ($result_lojas && $result_lojas->num_rows > 0) {
                    while ($loja = $result_lojas->fetch_assoc()) {

                        // Nome correto da loja
                        $nome_loja = htmlspecialchars($loja['nome_fantasia']);

                        $imagem = htmlspecialchars($loja['imagem'] ?? "https://via.placeholder.com/150");
                        $descricao = htmlspecialchars($loja['descricao'] ?? "");
                        $slug = htmlspecialchars($loja['slug'] ?? "#");

                        echo '
    <a href="loja/loja.php?slug=' . $slug . '" target="_blank" class="btn-entrar">
        <div class="card-loja">
            <img src="uploads/lojas/' . $imagem . '" alt="' . $nome_loja . '" loading="lazy">
            <h3>' . $nome_loja . '</h3>
        </div>
    </a>
';



                    }
                } else {
                    echo '<p>Nenhuma loja cadastrada.</p>';
                }

                $conn->close();
                ?>

                <!-- <a href="loja.php?slug=' . $slug . '" class="btn-entrar">
                    <div class="card-loja">
                        <img src="img/carregador_power_bank_5_000mah_11082_1_2b9fd69b3ca4b56d80bcdc71271a290a.webp"
                            alt="' . $nome_loja . '" loading="lazy">
                        <h3>BolvierTeam</h3>
                        <p>' . $descricao . '</p>

                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <a href="loja.php?slug=' . $slug . '" class="btn-entrar">
                    <div class="card-loja">
                        <img src="img/carregador_power_bank_5_000mah_11082_1_2b9fd69b3ca4b56d80bcdc71271a290a.webp"
                            alt="' . $nome_loja . '" loading="lazy">
                        <h3>BolvierTeam</h3>
                        <p>' . $descricao . '</p>

                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a> -->

            </div>

        </section>

    </main>

    <footer>
        &copy;BolvierTeam

        <a href="">Virar vendedor</a>
    </footer>

</body>

<script src="js/compras.js"></script>
<script src="js/links.js"></script>
<script src="js/produtos.js"></script>
<script src="js/pegarProduto.js"></script>
<script src="js/search.js"></script>

<script>

    const track = document.querySelector('.carrossel-track');
    const slides = Array.from(track.children);
    let index = 0;

    function slideShow() {
        index++;
        if (index >= slides.length) index = 0;
        track.style.transform = `translateX(-${index * 100}%)`; // 100% da largura do container
    }

    // Troca a cada 3 segundos
    setInterval(slideShow, 3000);

</script>

<script>
    window.addEventListener("scroll", function () {
        const navbar = document.querySelector(".nav");

        if (window.scrollY > 10) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

</script>

</html>