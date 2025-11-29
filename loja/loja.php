<?php
require "../php/conexao.php";

// Verifica se recebeu o slug
if (!isset($_GET["slug"])) {
    die("Loja não encontrada.");
}

$slug = $_GET["slug"];

// Buscar a loja pelo slug
$sql = "SELECT * FROM lojas WHERE slug = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$loja = $stmt->get_result()->fetch_assoc();

if (!$loja) {
    die("Loja não existe.");
}

$usuario_id = $loja["usuario_id"];
$nome_loja = $loja["nome_fantasia"];
$imagem_db = $loja["imagem"] ?? "";

// Função auxiliar para resolver o caminho correto da imagem
function resolverCaminhoImagemLoja($valorDb)
{
    $baseDir = realpath(__DIR__ . "/../") . "/";
    $v = trim($valorDb);

    if ($v === "" || $v === null) {
        return null;
    }

    if (preg_match('#^https?://#i', $v) || strpos($v, '/') === 0) {
        return $v;
    }

    $candidates = [
        'uploads/loja/' . $v,
        'uploads/lojas/' . $v,
        'uploads/' . $v,
        'loja/' . $v,
        $v,
        '../' . $v
    ];

    foreach ($candidates as $rel) {
        $physical = realpath($baseDir . $rel);
        if ($physical && file_exists($physical)) {
            if (strpos($rel, '/') === 0) {
                return $rel;
            }
            return '../' . $rel;
        }
    }

    if (preg_match('#^https?://#i', $v)) {
        return $v;
    }

    return null;
}

$imagem_loja_path = resolverCaminhoImagemLoja($imagem_db);

if (!$imagem_loja_path) {
    $imagem_loja_path = '../uploads/default-loja.png';
    if (!file_exists(__DIR__ . '/../uploads/default-loja.png')) {
        $imagem_loja_path = 'https://via.placeholder.com/300x120?text=Loja';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Code:wght@300;400;500;600;700;800&family=Zalando+Sans+SemiExpanded:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="tema.php?slug=<?php echo urlencode($slug); ?>">
    <link rel="stylesheet" href="loja.css">

    <title><?php echo htmlspecialchars($nome_loja); ?> - Loja Virtual</title>
</head>

<body data-slug="<?php echo $slug ?>">

    <nav class="nav">

        <span class="logo" id="logo">
            <?php echo htmlspecialchars($nome_loja); ?>
        </span>

        <div class="icons-menu" onclick="carrinho()">

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
                <img src="<?php echo $imagem_loja_path ?>" alt="<?php echo htmlspecialchars($nome_loja) ?>"
                    loading="lazy">

                <h4><?php echo htmlspecialchars($nome_loja) ?></h4>

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

    <header class="carrossel-container">

        <div class="carrossel-track">

            <img src="../img/FRETEGRATIS.png" alt="">
            <img src="../img/Banner moda feminina bolsa e acessorios desconto.png" alt="">
            <img src="../img/Banner Moda Masculina Nova Coleção Moderno Preto e Cinza.png" alt="">

        </div>

    </header>

    <main class="page" id="page">

        <section class="home" id="home">
            <div class="info-client">

                <img src="<?php echo $imagem_loja_path ?>" alt="<?php echo htmlspecialchars($nome_loja) ?>"
                    loading="lazy">

                <h1><?php echo htmlspecialchars($nome_loja) ?></h1>

                <label for="chat-shop-user" onclick="chatShopUser()">
                    <i class="bi bi-chat-right-dots"></i>
                    <button id="chat-shop-user">Falar com o vendedor</button>
                </label>

            </div>
        </section>

        <!-- ----------- Produtos ----------- -->

        <section class="produtos" id="produtos">

            <h2>Produtos</h2>

            <div id="lista-produtos" class="lista-produtos">
                <?php
                $sql = "SELECT * FROM produtos WHERE usuario_id = ? ORDER BY id DESC";
                $stmtProdutos = $conn->prepare($sql);
                $stmtProdutos->bind_param("i", $usuario_id);
                $stmtProdutos->execute();
                $result = $stmtProdutos->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($p = $result->fetch_assoc()) {

                        $prod_img_db = $p['imagem'] ?? '';
                        $prod_path = null;

                        $prod_candidates = [
                            'uploads/loja/' . $prod_img_db,
                            'uploads/lojas/' . $prod_img_db,
                            'uploads/' . $prod_img_db,
                            $prod_img_db,
                        ];

                        foreach ($prod_candidates as $c) {
                            $physical = realpath(__DIR__ . "/../" . $c);
                            if ($physical && file_exists($physical)) {
                                $prod_path = '../' . $c;
                                break;
                            }
                        }

                        if (!$prod_path) {
                            if ($prod_img_db && (strpos($prod_img_db, 'uploads/') === 0 || strpos($prod_img_db, '../') === 0)) {
                                $prod_path = strpos($prod_img_db, '../') === 0 ? $prod_img_db : '../' . $prod_img_db;
                            } else {
                                $prod_path = '../uploads/default-prod.png';
                                if (!file_exists(__DIR__ . '/../uploads/default-prod.png')) {
                                    $prod_path = 'https://via.placeholder.com/300?text=Produto';
                                }
                            }
                        }

                        echo '<div class="card">
                            <img src="' . htmlspecialchars($prod_path) . '" alt="' . htmlspecialchars($p['nome']) . '" loading="lazy">
                            <h3>' . htmlspecialchars($p['nome']) . '</h3>
                            <p class="preco"><b>R$ ' . number_format($p['preco'], 2, ',', '.') . '</b></p>
                            <button class="btn-comprar" 
                                data-produto="' . htmlspecialchars($p['nome']) . '" 
                                data-preco="' . htmlspecialchars($p['preco']) . '" 
                                data-imagem="' . htmlspecialchars($prod_path) . '">
                                Adicionar ao Carrinho
                            </button>
                        </div>';
                    }
                } else {
                    echo '<p>Nenhum produto cadastrado nesta loja.</p>';
                }

                $conn->close();
                ?>

            </div>

        </section>

        <!-- ----------- banners -----------  -->

        <section class="banners" id="banners">

            <h2>Destaques</h2>

            <div class="banners-div" id="banners-div">
                <img src="https://marketplace.canva.com/EAF0RxuySjc/1/0/800w/canva-banner-de-black-friday-formato-paisagem-org%C3%A2nico-delicado-em-lavanda-e-cinza-ard%C3%B3sia-yiGSUITHLd0.jpg"
                    alt="banner">
            </div>

        </section>

    </main>

</body>

<script src="../js/compras.js"></script>
<script src="../js/links.js"></script>
<script src="../js/produtos.js"></script>
<script src="../js/pegarProduto.js"></script>

<script>
    function chatShopUser() {
        window.open("http://localhost/marcos_lojavirtual/chatVendedor.php", "_blank")
    }
</script>

<script>
    const track = document.querySelector('.carrossel-track');
    const slides = Array.from(track.children);
    let index = 0;

    function slideShow() {
        index++;
        if (index >= slides.length) index = 0;
        track.style.transform = `translateX(-${index * 100}%)`;
    }

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

<script>
    function carrinho() {
        const slug = "<?php echo urlencode($slug); ?>";
        window.location.href = "carrinho.php?slug=" + slug;
    }
</script>

</html>