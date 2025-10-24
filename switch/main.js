// Switcher.js
export class Switcher {
    constructor(container, options = {}) {
        // Imposta il contenitore
        if (typeof container === 'string') {
            this.container = document.querySelector(container);
        } else if (container instanceof HTMLElement) {
            this.container = container;
        } else {
            throw new Error('Container deve essere un selettore CSS o un elemento HTMLElement');
        }

        // Imposta le opzioni con valori di default
        const defaultOptions = {
            states: [
                {
                    label: 'Stato 1',
                    icon: 'fa-circle',
                    color: '#e2442b',
                },
                {
                    label: 'Stato 2',
                    icon: 'fa-star',
                    color: '#f8bb39',
                },
                {
                    label: 'Stato 3',
                    icon: 'fa-heart',
                    color: '#4caf50',
                },
            ],
            onChange: null, // Funzione callback quando lo stato cambia
            initialState: 0, // Indice dello stato iniziale di default
            width: 300, // Larghezza in px
            height: 60, // Altezza in px
            background: '#f0f0f0', // Background dello switcher
        };
        this.options = { ...defaultOptions, ...options };

        // Validazione degli stati
        if (!Array.isArray(this.options.states) || this.options.states.length < 2) {
            throw new Error('Deve essere fornito un array di almeno due stati');
        }

        // Stato iniziale
        this.currentStateIndex = this.options.initialState >= 0 && this.options.initialState < this.options.states.length
            ? this.options.initialState
            : 0;

        // Inizializza il componente
        this.init();
    }

    init() {

        // Crea lo stile del componente
        this.createStyles();

        // Crea la struttura HTML
        this.createHTML();

        // Aggiungi gli event listener
        this.addEventListeners();

        // Aggiorna lo stato iniziale
        this.updateState();

        // Esegui la callback iniziale se fornita
        if (typeof this.options.onChange === 'function') {
            this.options.onChange(this.getState());
        }
    }

    createStyles() {
        const { width, height, states, background } = this.options;
        const numStates = states.length;
        const padding = 5; // Padding interno
        const sliderWidth = (width / numStates) - (padding * 2);
        const sliderHeight = height - (padding * 2);
        const iconFontSize = height / 3;
        const mediaWidth = width * 0.8;
        const mediaHeight = height * 0.8;
        const mediaSliderHeight = mediaHeight - (padding * 2);
        const mediaIconFontSize = mediaHeight / 3;

        const style = document.createElement('style');
        style.innerHTML = `
            .switcher-wrapper {
                display: flex;
                align-items: center;
                gap: 10px;
                width: 75%;
            }

            .switcher {
                position: relative;
                width: ${width}px;
                height: ${height}px;
                background: ${background};
                border-radius: ${height / 2}px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                display: flex;
                align-items: center;
                overflow: hidden;
                flex-shrink: 0;
                justify-content: space-around;
            }
            
            .switcher-slider {
                position: absolute;
                top: ${padding}px;
                left: ${padding}px;
                width: ${(width / numStates) - (padding * 2)}px;
                height: ${sliderHeight}px;
                background-color: ${states[0].color};
                border-radius: ${sliderHeight / 2}px;
                transition: transform 0.3s ease, background-color 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
            
            .switcher .icon {
                position: absolute;
                width: ${100 / numStates}%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: rgba(255, 255, 255, 0.6);
                font-size: ${iconFontSize}px;
                transition: color 0.3s ease;
                z-index: 1;
                pointer-events: none;
            }
            
            ${states.map((state, index) => `
                .switcher .icon.state-${index} {
                    left: ${(index * 100) / numStates}%;
                    pointer-events: auto; /* Permetti l'interazione */
                }
            `).join('')}
            
            .switcher .icon.active {
                color: white;
            }
            
            .switcher-label {
                font-size: ${height / 2}px;
                color: #333;
                white-space: nowrap;
                transition: color 0.3s ease;
            }

            /* Responsività */
            @media (max-width: ${mediaWidth}px) {
                .switcher-wrapper {
                    flex-direction: column;
                    align-items: center;
                }

                .switcher {
                    width: ${mediaWidth}px;
                    height: ${mediaHeight}px;
                }
                
                .switcher-slider {
                    height: ${mediaSliderHeight}px;
                }
                
                .switcher .icon {
                    font-size: ${mediaIconFontSize}px;
                }

                .switcher-label {
                    font-size: ${mediaHeight / 2}px;
                }
            }
        `;
        document.head.appendChild(style);
    }

    createHTML() {
        const { states } = this.options;
        const numStates = states.length;

        // Crea il wrapper
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('switcher-wrapper');

        // Crea lo switcher
        this.switcher = document.createElement('div');
        this.switcher.classList.add('switcher');

        // Crea lo slider
        this.slider = document.createElement('div');
        this.slider.classList.add('switcher-slider');

        // Crea le icone per ogni stato
        this.icons = states.map((state, index) => {
            const icon = document.createElement('div');
            icon.classList.add('icon', `state-${index}`);
            icon.innerHTML = `<i class="fas ${state.icon}"></i>`;
            icon.title = `Visualizza ${state.label}`;
            icon.setAttribute('aria-label', `Visualizza ${state.label}`);
            icon.setAttribute('role', 'button');
            icon.setAttribute('tabindex', '0');
            return icon;
        });

        // Crea l'etichetta dello stato attivo
        this.label = document.createElement('div');
        this.label.classList.add('switcher-label');
        this.label.textContent = states[this.currentStateIndex].label;

        // Assembla lo switcher
        this.icons.forEach(icon => this.switcher.appendChild(icon));
        this.switcher.appendChild(this.slider);

        // Aggiungi attributi ARIA per l'accessibilità
        this.switcher.setAttribute('role', 'switch');
        this.switcher.setAttribute('aria-checked', states[this.currentStateIndex].label);
        this.switcher.setAttribute('tabindex', '0');

        // Permetti l'interazione tramite tastiera per lo switcher
        this.switcher.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.nextState();
            }
            // Permetti la navigazione tra gli stati con frecce
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.nextState();
            }
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.previousState();
            }
        });

        // Permetti l'interazione tramite tastiera per ogni icona
        this.icons.forEach((icon, index) => {
            icon.addEventListener('click', (e) => {
                e.stopPropagation(); // Evita di attivare anche l'evento del switcher
                this.setState(index);
            });

            icon.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.setState(index);
                }
            });
        });

        // Inserisci lo switcher e l'etichetta nel wrapper
        this.wrapper.appendChild(this.switcher);
        this.wrapper.appendChild(this.label);

        // Inserisci il wrapper nel contenitore
        this.container.appendChild(this.wrapper);
    }

    addEventListeners() {
        // L'evento di click sullo switcher principale
        this.switcher.addEventListener('click', () => {
            this.nextState();
        });
    }

    nextState() {
        this.currentStateIndex = (this.currentStateIndex + 1) % this.options.states.length;
        this.updateState();
    }

    previousState() {
        this.currentStateIndex = (this.currentStateIndex - 1 + this.options.states.length) % this.options.states.length;
        this.updateState();
    }

    updateState() {
        const { states } = this.options;
        const currentState = states[this.currentStateIndex];

        // Aggiorna lo slider
        this.slider.style.transform = `translateX(${(this.switcher.clientWidth / states.length) * this.currentStateIndex}px)`;
        this.slider.style.backgroundColor = currentState.color;

        // Aggiorna le icone
        this.icons.forEach((icon, index) => {
            if (index === this.currentStateIndex) {
                icon.classList.add('active');
                icon.style.color = 'white';
            } else {
                icon.classList.remove('active');
                icon.style.color = 'rgba(255, 255, 255, 0.6)';
            }
        });

        // Aggiorna l'etichetta dello stato attivo
        this.label.textContent = currentState.label;

        // Aggiorna attributi ARIA
        this.switcher.setAttribute('aria-checked', currentState.label);

        // Esegui la callback se fornita
        if (typeof this.options.onChange === 'function') {
            this.options.onChange(this.getState());
        }

        this.switcher.setAttribute("data-active-state", currentState.label);
    }

    // Metodo per ottenere lo stato attuale
    getState() {
        return this.options.states[this.currentStateIndex].label;
    }

    // Metodo per impostare lo stato tramite indice o etichetta
    setState(state) {
        let targetIndex;
        if (typeof state === 'number') {
            targetIndex = state;
        } else if (typeof state === 'string') {
            targetIndex = this.options.states.findIndex(s => s.label.toLowerCase() === state.toLowerCase());
        } else {
            return;
        }

        if (targetIndex !== -1 && targetIndex !== this.currentStateIndex) {
            this.currentStateIndex = targetIndex;
            this.updateState();
        } else if (targetIndex === this.currentStateIndex) {
            return;
        } else {
            return;
        }
    }
}