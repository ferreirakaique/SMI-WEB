document.getElementById('voltar').addEventListener('click', () => {
    history.back()
})

const cards = document.querySelectorAll('.cards_identificacao .imagem_maquina');

cards.forEach(card => {
    const img = card.querySelector('img');
    let isDragging = false;
    let startX = 0;
    let startY = 0;

    // Impede que a imagem seja arrastável
    img.setAttribute('draggable', 'false');

    card.addEventListener('mousedown', e => {
        e.preventDefault(); // evita seleção de texto ou drag padrão
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        card.style.transition = 'transform 0s, box-shadow 0s';
        img.style.transition = 'transform 0s, filter 0.1s';
    });

    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;

        // Rotação proporcional ao movimento do mouse
        const rotateY = dx / 25;
        const rotateX = -dy / 25;
        card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;

        // Box-shadow do card acompanha o mouse
        const shadowX = -dx / 40; // ajuste a intensidade
        const shadowY = -dy / 40;
        card.style.boxShadow = `${shadowX}px ${shadowY}px 5px rgba(0,0,0,0.7)`;

        // Sombra da imagem acompanha o mouse
        img.style.transform = `translateZ(60px) scale(1.05)`;
        img.style.filter = `drop-shadow(${-dx / 80}px ${-dy / 80}px 5px rgba(0,0,0,0.7))`;

        card.style.animation = 'none'
    });

    document.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;

        // Retorna o card e a imagem ao estado inicial
        card.style.transition = 'transform 0.5s ease, box-shadow 0.5s ease';
        img.style.transition = 'transform 0.6s ease, filter 0.1s ease';
        card.style.transform = 'rotateX(0deg) rotateY(0deg)';
        card.style.boxShadow = 'none';
        img.style.transform = 'translateZ(40px) scale(1)';
        img.style.filter = 'drop-shadow(0 0 0 rgba(0,0,0,0.7))';
        card.style.animation = 'flutuar 2s ease-in-out infinite'

    });
});