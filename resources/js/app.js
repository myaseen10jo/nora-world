import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Search Component for Navigation
window.searchComponent = () => ({
    query: '',
    results: [],
    open: false,
    loading: false,
    highlightedIndex: -1,
    mobileSearchOpen: false,
    mobileMenuOpen: false,

    init() {
        // nothing needed
    },

    async search() {
        if (this.query.length < 2) {
            this.results = [];
            this.open = false;
            return;
        }

        this.loading = true;
        this.open = true;

        try {
            const response = await fetch(`/search?search=${encodeURIComponent(this.query)}`);
            const data = await response.json();
            this.results = data.products || [];
        } catch (e) {
            this.results = [];
        }

        this.loading = false;
    },

    clearSearch() {
        this.query = '';
        this.results = [];
        this.open = false;
        this.highlightedIndex = -1;
    },

    close() {
        this.open = false;
        this.highlightedIndex = -1;
    },

    highlightNext() {
        if (this.highlightedIndex < this.results.length - 1) {
            this.highlightedIndex++;
        }
    },

    highlightPrev() {
        if (this.highlightedIndex > 0) {
            this.highlightedIndex--;
        }
    },

    goToHighlighted() {
        if (this.highlightedIndex >= 0 && this.results[this.highlightedIndex]) {
            window.location.href = this.results[this.highlightedIndex].url;
        }
    },
});

// Scroll Reveal Component (legacy — for pages using .reveal/.reveal-scale)
window.scrollReveal = () => ({
    init() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal, .reveal-scale, .reveal-left, .reveal-right').forEach((el) => {
            observer.observe(el);
        });
    },
});

// ═══════════════════════════════════════════════════════════════
// ANIMATION SYSTEM — Initializes .anim-item scroll animations
// ═══════════════════════════════════════════════════════════════

function initAnimations() {
    const animItems = document.querySelectorAll('.anim-item');
    if (animItems.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('anim-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
    });

    animItems.forEach((el) => observer.observe(el));
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAnimations);
} else {
    initAnimations();
}

// Re-initialize after Alpine updates (for dynamic content)
document.addEventListener('alpine:initialized', () => {
    setTimeout(initAnimations, 100);
});

Alpine.start();
