document.addEventListener("click", function (e) {

    const btn = e.target.closest(".btn-comprar");
    if (!btn) return;

    const nome = btn.dataset.produto;
    const preco = btn.dataset.preco;
    const imagem = btn.dataset.imagem;

    // slug vindo do PHP
    const slug = "<?php echo $slug ?>";

    fetch("../php/add_carrinho.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `nome=${encodeURIComponent(nome)}&preco=${encodeURIComponent(preco)}&imagem=${encodeURIComponent(imagem)}&slug=${slug}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "ok") {
            document.getElementById("contadorCarrinho").innerText = data.total_itens;
        }
    });
});
