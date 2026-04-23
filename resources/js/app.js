import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.nav-toggle');
    const panel = document.querySelector('.nav-panel');

    if (toggle && panel) {
        toggle.addEventListener('click', () => {
            panel.classList.toggle('hidden');
        });
    }

    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const closeButton = toast.querySelector('.toast-close');
        const timeout = Number(toast.dataset.timeout || 4500);

        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-3', 'sm:translate-x-6', 'sm:translate-y-0');
        });

        const dismiss = () => {
            toast.classList.add('opacity-0', 'translate-y-2', 'sm:translate-x-6');

            window.setTimeout(() => {
                toast.remove();
            }, 250);
        };

        if (closeButton) {
            closeButton.addEventListener('click', dismiss);
        }

        if (timeout > 0) {
            window.setTimeout(dismiss, timeout);
        }
    });
});

