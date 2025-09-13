document.querySelectorAll('.editar').forEach(botao => {
    botao.addEventListener('click', () => {
        document.getElementById('modal_overlay').style.display = 'flex';
        document.getElementById('modal_content').style.display = 'flex';

    })
})

document.getElementById('sair_modal').addEventListener('click', () => {
    document.getElementById('modal_overlay').style.display = 'none';
    document.getElementById('modal_content').style.display = 'none';
})