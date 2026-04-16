document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav-toggle');
    const panel = document.querySelector('.nav-panel');

    if (!toggle || !panel) {
        return;
    }

    toggle.addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });
});
