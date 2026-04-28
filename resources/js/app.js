import Alpine from 'alpinejs'
import {
    createIcons,
    Home,
    Package,
    ShoppingCart,
    ClipboardList,
    User,
    FileText,
    Search
} from 'lucide'

// =========================
// INIT ICONS
// =========================
const icons = {
    Home,
    Package,
    ShoppingCart,
    ClipboardList,
    User,
    FileText,
    Search
}

window.renderIcons = () => createIcons({ icons })

// =========================
// INIT ALPINE
// =========================
window.Alpine = Alpine

document.addEventListener('alpine:init', () => {
    Alpine.effect(() => {
        // re-render icon setelah DOM berubah
        queueMicrotask(() => {
            window.renderIcons()
        })
    })
})

Alpine.start()

// =========================
// DOM READY (GLOBAL INIT)
// =========================
document.addEventListener('DOMContentLoaded', () => {

    // render icons pertama kali
    window.renderIcons()

    // =========================
    // NAV TOGGLE
    // =========================
    const toggle = document.querySelector('.nav-toggle')
    const panel = document.querySelector('.nav-panel')

    if (toggle && panel) {
        toggle.addEventListener('click', () => {
            panel.classList.toggle('hidden')
        })
    }

    // =========================
    // TOAST HANDLER
    // =========================
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const closeButton = toast.querySelector('.toast-close')
        const timeout = Number(toast.dataset.timeout || 4500)

        // show animation
        requestAnimationFrame(() => {
            toast.classList.remove(
                'opacity-0',
                'translate-y-3',
                'sm:translate-x-6',
                'sm:translate-y-0'
            )
        })

        const dismiss = () => {
            toast.classList.add(
                'opacity-0',
                'translate-y-2',
                'sm:translate-x-6'
            )

            setTimeout(() => toast.remove(), 250)
        }

        if (closeButton) {
            closeButton.addEventListener('click', dismiss)
        }

        if (timeout > 0) {
            setTimeout(dismiss, timeout)
        }
    })

})