const list = document.querySelectorAll('.list');
const conteudo = document.querySelector("#conteudo")

function carregarPagina(url, aba) {
    fetch(url)
        .then(res => res.text())
        .then(html => {
            conteudo.innerHTML = html;

            list.forEach((el) => el.classList.remove("active"));
            aba.classList.add("active");
        })
        .catch(err => {
            conteudo.innerHTML = "<p>Erro ao carregar a página</p>";
            console.error(err)
        });
}

list.forEach((item) => {
    item.addEventListener('click', function (e) {
        e.preventDefault();

        let destino = this.querySelector("a").id;

        let rotas = {
            "INICIO": "inicio.php",
            "LISTAR_MAQUINAS": "listar_maquinas.php",
            "QR_CODE": "qr_code.php",
            "NOTIFICACOES": "notificacoes.php",
            "PERFIL": "perfil.php",
        };

        carregarPagina(rotas[destino], this)
    });
});
