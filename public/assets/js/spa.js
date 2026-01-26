document.addEventListener('click', e => {
    if (e.target.matches('a') && e.target.getAttribute('href').startsWith('/')) {
        e.preventDefault();
        const url = e.target.href;
        
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
