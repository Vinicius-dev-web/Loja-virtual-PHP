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

    <link rel="icon" href="Bolvier.png">

    <title>BolvierShop</title>

</head>

<body>

    <header>

        <img src="Bolvier.png" alt="Bolvier">

        <h1>Bolvier Shop</h1>

        <button>Faça parte</button>

    </header>

    <!-- <nav>
        <button>Cadastrar</button>
        <button>Entrar</button>
    </nav> -->

    <main class="page" id="page">

        <h2>Crie sua loja</h2>
        <section class="home" id="home">


            <div class="title">

                <h1>Crie sua loja online<br>hoje mesmo</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem nisi natus laborum similique.
                    Similique voluptas, corporis nam nemo earum harum, laboriosam minima, rem cumque quisquam pariatur
                    porro
                    dolor nihil fugiat? Lorem ipsum dolor sit, amet consectetur adipisicing elit. Incidunt deserunt
                    quaerat quod sequi blanditiis perspiciatis accusantium beatae voluptates. Distinctio incidunt
                    pariatur molestiae necessitatibus at, dolor eligendi tempora deleniti quae? Vero? Lorem ipsum dolor
                    sit amet consectetur adipisicing elit. Enim cupiditate eaque praesentium a reprehenderit. Voluptates
                    corporis error voluptate vel repellat laboriosam! Autem sit voluptatem recusandae consectetur
                    aliquid quis natus odit?</p>

            </div>

            <img src="img/create.svg" alt="">

        </section>

        <section class="mobile" id="mobile">

            <img src="img/mobile.svg" alt="">

            <div class="title">

                <h1>Sistema adaptado <br>em versões mobile<br></h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem nisi natus laborum similique.
                    Similique voluptas, corporis nam nemo earum harum, laboriosam minima, rem cumque quisquam pariatur
                    porro
                    dolor nihil fugiat? Lorem ipsum dolor sit, amet consectetur adipisicing elit. Incidunt deserunt
                    quaerat quod sequi blanditiis perspiciatis accusantium beatae voluptates. Distinctio incidunt
                    pariatur molestiae necessitatibus at, dolor eligendi tempora deleniti quae? Vero? Lorem ipsum dolor
                    sit amet consectetur adipisicing elit. Enim cupiditate eaque praesentium a reprehenderit. Voluptates
                    corporis error voluptate vel repellat laboriosam! Autem sit voluptatem recusandae consectetur
                    aliquid quis natus odit?</p>

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

        <section class="planos" id="planos">

            <h2>Planos</h2>

            <div class="cards">

                <div class="card">
                    <h2>Básico</h2>
                    <p>Ideal para começar</p>
                    <ul>
                        <li>Recursos básicos</li>
                        <li>até 20 produtos</li>
                        <li>Até 2 banners personalizados</li>
                        <!-- <li>Suporte básico</li> -->
                    </ul>
                    <button>R$ 0 / mês</button>
                </div>

                <div class="card destaque">
                    <h2>Premium</h2>
                    <p>Para quem quer crescer</p>
                    <ul>
                        <li>Loja ilimitada</li>
                        <li>Produtos ilimitados</li>
                        <li>Banners ilimitados</li>
                        <li>Criação de categorias</li>
                        <li>Controle de saída</li>
                        <!-- <li>Suporte prioritário</li> -->
                    </ul>
                    <button>R$ 5 / mês</button>
                </div>

                <!-- <div class="card">
                    <h2>Ultimate</h2>
                    <p>Para profissionais</p>
                    <ul>
                        <li>Multi empresas</li>
                        <li>Relatórios avançados</li>
                        <li>Suporte 24h</li>
                    </ul>
                    <button>R$ 10 / mês</button>
                </div> -->

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