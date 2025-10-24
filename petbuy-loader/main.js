export class AnimalLoader {
    constructor(options = {}) {
        this.loaderId = options.id || 'animal-loader';
        this.styleId = options.styleId || 'animal-loader-styles';
        this.width = options.width || '60px'; // Larghezza del spinner
        this.height = options.height || '60px'; // Altezza del spinner
        this.containerId = options.containerId || null; // Contenitore personalizzato

        // HTML del loader: Overlay con spinner
        this.loaderHTML = `
            <div id="${this.loaderId}" class="animal-loader-overlay" role="status" aria-live="polite">
                <div class="animal-loader-spinner" style="width: ${this.width}; height: ${this.height};" aria-label="Caricamento..."></div>
            </div>
        `;

        // Stili CSS per il loader
        this.styles = `
            /* Overlay */
            .animal-loader-overlay {
                position: absolute; /* Cambiato da fixed ad absolute */
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8); /* Trasparenza */
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                display: none; /* Nascosto di default */
                backdrop-filter: blur(10px); /* Sfocatura per effetto */
                z-index: 1;
            }

            /* Spinner */
            .animal-loader-spinner {
                border: 8px solid #f3f3f3; /* Bordo chiaro */
                border-top: 8px solid #3498db; /* Bordo superiore blu */
                border-radius: 50%;
                width: 60px;
                height: 60px;
                animation: animal-spin 2s linear infinite;
            }

            /* Animazione di rotazione */
            @keyframes animal-spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
    }

    /**
     * Costruisce e inserisce il loader nel DOM.
     */
    build() {
        // Aggiungi gli stili se non sono già presenti
        if (!document.getElementById(this.styleId)) {
            const styleTag = document.createElement('style');
            styleTag.id = this.styleId;
            styleTag.innerHTML = this.styles;
            document.head.appendChild(styleTag);
        }

        // Inserisci il loader nel contenitore specificato o nel body
        if (!document.getElementById(this.loaderId)) {
            const container = document.createElement('div');
            container.innerHTML = this.loaderHTML;
            const targetContainer = document.getElementById(this.containerId);
            targetContainer.appendChild(container.firstElementChild);
        }
    }

    /**
     * Mostra il loader.
     */
    show() {
        const loader = document.getElementById(this.loaderId);
        if (loader) {
            loader.style.display = 'flex';
        }
    }

    /**
     * Nasconde il loader.
     */
    hide() {
        const loader = document.getElementById(this.loaderId);
        if (loader) {
            loader.style.display = 'none';
        }
    }

    /**
     * Rimuove il loader e gli stili associati dal DOM.
     */
    remove() {
        const loader = document.getElementById(this.loaderId);
        if (loader) {
            loader.parentNode.removeChild(loader);
        }

        const styleTag = document.getElementById(this.styleId);
        if (styleTag) {
            styleTag.parentNode.removeChild(styleTag);
        }
    }
}