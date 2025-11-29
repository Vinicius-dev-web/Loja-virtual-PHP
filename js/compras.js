// ADICIONAR AO CARRINHO
document.querySelectorAll(".btn-comprar").forEach(btn => {
    btn.addEventListener("click", function () {

        const nome = this.dataset.produto;
        const preco = this.dataset.preco;
        const imagem = this.dataset.imagem;

        // PEGAR SLUG DIRETO DO PHP
        const slug = document.body.dataset.slug;

        fetch("../php/add_carrinho.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `nome=${encodeURIComponent(nome)}&preco=${preco}&imagem=${encodeURIComponent(imagem)}&slug=${slug}`
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("contadorCarrinho").innerText = data.total_itens;
        });
    });
});
