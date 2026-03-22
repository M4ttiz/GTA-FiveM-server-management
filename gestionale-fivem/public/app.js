let currentUser = null;

async function checkLogin() {
    const res = await fetch('/api/me');
    if (!res.ok) {
        if(window.location.pathname !== '/' && window.location.pathname !== '/index.html') {
            window.location.href = '/index.html';
        }
    } else {
        currentUser = await res.json();
    }
}