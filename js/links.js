function admlogin() {

    const inputS = document.getElementById('search').value;

    if (inputS == "enter") {

        location.href = "login.php"

    }
}

function chatShopUser(){

    window.open("http://localhost/marcos_lojavirtual/chatVendedor.php", "_blank")
}

function homePage(){

    location.href = "index.php"
}

function carrinho(){

    window.open("carrinho.php", "_blank")
    
}