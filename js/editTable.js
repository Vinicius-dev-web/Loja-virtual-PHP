function editarProduto(id, nome, preco) {
    // Abre o modal
    const modal = document.getElementById("edit-produto-table");
    modal.classList.add("abrir");

    // Preenche os campos
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-nome").value = nome;
    document.getElementById("edit-preco").value = preco;
}

// Fechar modal ao clicar em cancelar
document.getElementById("cancel").addEventListener("click", function() {
    document.getElementById("edit-produto-table").classList.remove("abrir");
});

// Excluir produto
document.getElementById("delete-produto").addEventListener("click", function() {
    const id = document.getElementById("edit-id").value;
    if (confirm("Deseja realmente excluir este produto?")) {
        window.location.href = `php/excluirProduto.php?id=${id}`;
    }
});

// Salvar alterações
document.getElementById("save-table").addEventListener("click", function() {
    const id = document.getElementById("edit-id").value;
    const nome = document.getElementById("edit-nome").value;
    const preco = document.getElementById("edit-preco").value;
    const imagem = document.getElementById("edit-imagem").files[0];

    const formData = new FormData();
    formData.append("id", id);
    formData.append("nome", nome);
    formData.append("preco", preco);
    if (imagem) formData.append("imagem", imagem);

    fetch("php/editarProduto.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(res => {
        alert(res);
        window.location.reload();
    })
    .catch(err => console.error(err));
});
