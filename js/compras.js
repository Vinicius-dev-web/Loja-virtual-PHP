document.addEventListener("DOMContentLoaded", () => {

    const numeroWhatsApp = "988671941";
    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

    const iconeMenuMobile = document.getElementById("abrirMenuMobile");
    const painelMenuMobile = document.getElementById("esquerda");

    const iconeCarrinho = document.getElementById("abrirCarrinho");
    const painelCarrinho = document.getElementById("carrinho");

    const listaCarrinho = document.getElementById("lista-carrinho");
    const btnFinalizar = document.getElementById("btnFinalizar");

    iconeCarrinho.addEventListener("click", () => {
        painelCarrinho.classList.toggle("aberto");
        renderizarCarrinho();
    });

    iconeMenuMobile.addEventListener("click", () => {
        painelMenuMobile.classList.toggle("abrir");
    });

    document.querySelectorAll(".btn-comprar").forEach(btn => {
        btn.addEventListener("click", () => {
            const produto = btn.getAttribute("data-produto");
            carrinho.push(produto);
            salvarCarrinho();
            renderizarCarrinho();
            atualizarContador();
        });
    });

    function salvarCarrinho() {
        localStorage.setItem("carrinho", JSON.stringify(carrinho));
    }

    function renderizarCarrinho() {
        listaCarrinho.innerHTML = "";
        carrinho.forEach((produto, index) => {
            const item = document.createElement("p");
            item.innerHTML = `${produto} <button onclick="removerItem(${index})">X</button>`;
            listaCarrinho.appendChild(item);
        });
        btnFinalizar.disabled = carrinho.length === 0;
    }

    window.removerItem = function (index) {
        carrinho.splice(index, 1);
        salvarCarrinho();
        renderizarCarrinho();
        atualizarContador();
    }

    btnFinalizar.addEventListener("click", () => {
        if (carrinho.length === 0) {
            alert("Carrinho vazio!");
            return;
        }

        let mensagem = "Olá! Tenho interesse nesses produtos:\n\n";
        carrinho.forEach(item => {
            mensagem += `• ${item}\n`;
        });
        mensagem += "\nPodemos continuar o atendimento?";
        const url = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagem)}`;
        window.open(url, "_blank");

        carrinho = [];
        salvarCarrinho();
        renderizarCarrinho();
        atualizarContador();
    });

    document.getElementById("fecharCarrinho").addEventListener("click", () => {
        painelCarrinho.classList.remove("aberto");
    });

    document.getElementById("fecharMenuMobile").addEventListener("click", () => {
        painelMenuMobile.classList.remove("abrir");
    });

    function atualizarContador() {
        const contador = document.getElementById("contadorCarrinho");
        contador.textContent = carrinho.length;
        contador.classList.remove("pop");
        void contador.offsetWidth;
        contador.classList.add("pop");
    }

    atualizarContador();
    renderizarCarrinho();

});