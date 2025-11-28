// Seleciona todos os itens do menu lateral
const menuItems = document.querySelectorAll(".painel-info ul li");
const menuItemsChat = document.querySelectorAll(".msg-sec ul li");

// Seleciona todas as seções dentro do main.page
const sections = document.querySelectorAll("main.page section");

menuItems.forEach(item => {
    item.addEventListener("click", () => {

        // Remove active de todos
        menuItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");

        // Esconde todas as seções
        sections.forEach(sec => sec.classList.remove("active"));

        // Seleciona a aba correspondente
        const target = item.getAttribute("data-target");
        document.getElementById(target).classList.add("active");
    });
});
menuItemsChat.forEach(item => {
    item.addEventListener("click", () => {

        // Remove active de todos
        menuItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");

        // Esconde todas as seções
        sections.forEach(sec => sec.classList.remove("active"));

        // Seleciona a aba correspondente
        const target = item.getAttribute("data-target");
        document.getElementById(target).classList.add("active");
    });
});

// Ativar PRODUTOS ao abrir o painel
document.querySelector('[data-target="cog"]').click();

