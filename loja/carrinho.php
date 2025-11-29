<?php
require "../php/conexao.php";
session_start();

// inicializa para evitar warnings
$nome_loja = 'Loja';

// valida slug
if (!isset($_GET['slug']) || trim($_GET['slug']) === '') {
    die("Loja não encontrada. (carrinho.php precisa de ?slug=...)");
}

$slug = $_GET['slug'];

// busca a loja pelo slug
$sql = "SELECT * FROM lojas WHERE slug = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro no banco: " . $conn->error);
}
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$loja = $result->fetch_assoc();

if (!$loja) {
    die("Loja não existe ou slug inválido.");
}

// define nome e telefone da loja
$nome_loja = $loja['nome_fantasia'] ?? 'Loja';
$telefone_loja = preg_replace('/\D/', '', $loja['telefone'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans+Code:wght@300;400;500;700&family=Zalando+Sans+SemiExpanded:wght@200;400;600;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="tema.php?slug=<?php echo urlencode($slug); ?>">
    <link rel="stylesheet" href="carrinho.css">

    <title><?php echo htmlspecialchars($nome_loja) ?> - Carrinho</title>
</head>

<body>

    <nav>
        <div class="logo"><?php echo htmlspecialchars($nome_loja) ?></div>
        <div class="icons">
            <i class="bi bi-shield-check"></i>
            <span>Carrinho</span>
        </div>
    </nav>

    <div class="carrinho">

        <div class="card-prod">
            <div class="lista-carrinho" id="lista-carrinho"></div>
        </div>

        <div class="card-price">
            <h2>Sumário</h2>

            <div class="price">
                <div class="price-ofc">
                    <span>Preço oficial</span>
                    <span id="precoTotal">R$0,00</span>
                </div>

                <div class="price-off">
                    <span>Desconto</span>
                    <span>R$0,00</span>
                </div>
            </div>

            <button class="btn-finalizar" id="btnFinalizar">Finalizar Pedido</button>
        </div>

    </div>

</body>

<script>
    // ==============================
    // CARREGAR CARRINHO
    // ==============================
    fetch("../php/get_carrinho.php?slug=<?php echo $slug ?>")

        .then(res => res.json())
        .then(produtos => {

            const lista = document.getElementById("lista-carrinho");
            const totalSpan = document.getElementById("precoTotal");

            if (produtos.length === 0) {
                lista.innerHTML = "<p>Seu carrinho está vazio.</p>";
                return;
            }

            let total = 0;

            produtos.forEach((p, index) => {
                total += parseFloat(p.preco);

                lista.innerHTML += `
                <div class="card">
                    <img src="${p.imagem}" alt="${p.nome}">
                    <div class="info-prod">
                        <h3>${p.nome}</h3>
                        <p>R$ ${parseFloat(p.preco).toFixed(2).replace(".", ",")}</p>
                    </div>
                    <button class="btn-excluir" data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            });

            totalSpan.innerText = "R$ " + total.toFixed(2).replace(".", ",");
        });


    // ==============================
    // EXCLUIR ITEM DO CARRINHO
    // ==============================
    document.addEventListener("click", function (e) {

        const btn = e.target.closest(".btn-excluir");

        if (btn) {
            const index = btn.getAttribute("data-index");

            fetch("../php/remover_item.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "index=" + index + "&slug=<?php echo $slug ?>"
            })
                .then(res => res.json())
                .then(data => {

                    if (data.sucesso) {
                        btn.parentElement.remove();
                        atualizarTotal();
                    }
                });
        }
    });


    // ==============================
    // ATUALIZAR TOTAL  (CORRIGIDO)
    // ==============================
    function atualizarTotal() {
        fetch("../php/get_carrinho.php?slug=<?php echo $slug ?>")
            .then(res => res.json())
            .then(produtos => {
                let total = 0;

                produtos.forEach(p => {
                    total += parseFloat(p.preco);
                });

                document.getElementById("precoTotal").innerText =
                    "R$ " + total.toFixed(2).replace(".", ",");
            });
    }


    // ==============================
    // FINALIZAR PEDIDO (CORRIGIDO)
    // ==============================
    document.getElementById("btnFinalizar").addEventListener("click", async () => {

        const telefone = "<?php echo $telefone_loja ?>";

        if (!telefone || telefone.trim() === "") {
            alert("Telefone da loja não cadastrado.");
            return;
        }

        const produtos = await fetch("../php/get_carrinho.php?slug=<?php echo $slug ?>").then(r => r.json());

        if (produtos.length === 0) {
            alert("Seu carrinho está vazio.");
            return;
        }

        let mensagem = "Olá! Quero finalizar meu pedido:%0A%0A";
        let total = 0;

        produtos.forEach(p => {
            mensagem += `*${p.nome}* - R$ ${parseFloat(p.preco).toFixed(2).replace(".", ",")}%0A`;
            total += parseFloat(p.preco);
        });

        mensagem += `%0A-------------------------%0A`;
        mensagem += `*Total:* R$ ${total.toFixed(2).replace(".", ",")}%0A`;

        const link = `https://wa.me/${telefone}?text=${mensagem}`;

        // Limpar carrinho no servidor
        await fetch("../php/finalizar_pedido.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "slug=<?php echo $slug ?>"
        });

        // Redirecionar
        window.location.href = link;
    });

</script>

</html>
