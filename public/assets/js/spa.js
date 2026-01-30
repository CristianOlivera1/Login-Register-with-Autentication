document.addEventListener('click', e => {
    const link = e.target.closest('a');

    if (!link || !link.getAttribute('href')) return;

    const href = link.getAttribute('href');

    if (href.startsWith('/') && !link.hasAttribute('data-reload')) {
        // Excluir las rutas de CV de SPA (ambas mayúscula y minúscula)
        if (href.match(/^\/(CV|cv)\/[a-z0-9\-]+$/)) {
            return; 
        }
        
        e.preventDefault();
        const url = link.href;
        loadPage(url);
    }
});

async function loadPage(url) {
    window.history.pushState({}, '', url);

    const response = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    
    const html = await response.text();

    document.getElementById('app-content').innerHTML = html;
}


window.addEventListener('popstate', () => loadPage(window.location.href));
