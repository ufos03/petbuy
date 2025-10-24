import { FormValidator } from "../form-utils/main.js";

export class MultiPagePopup {
    constructor(
        pages,
        container = document.body,
        transition = 'none',
        styleConfig = {},
        resetOnClose = true,
        onCloseCallback = null,
        validateOnNext = false,
        autoCloseOnLastPage = false
    ) {
        this.pages = pages.map(page => ({
            ...page,
            onShow: page.onShow || (() => { }),
            onNext: page.onNext || (() => { }),
            maxWidth: page.maxWidth || null, // Aggiunto
            maxHeight: page.maxHeight || null // Aggiunto
        }));
        this.currentPage = 0;
        this.container = container;
        this.transition = transition;
        this.resetOnClose = resetOnClose;
        this.onCloseCallback = onCloseCallback;
        this.validateOnNext = validateOnNext;
        this.autoCloseOnLastPage = autoCloseOnLastPage;
        this.styleConfig = {
            width: styleConfig.width || '90%',
            height: styleConfig.height || '80%',
            maxWidth: styleConfig.maxWidth || '500px',
            maxHeight: styleConfig.maxHeight || '90vh',
            backgroundColor: styleConfig.backgroundColor || 'white',
            textColor: styleConfig.textColor || 'black',
            padding: styleConfig.padding || '20px',
            borderRadius: styleConfig.borderRadius || '10px',
            transitionDuration: styleConfig.transitionDuration || '0.5s',
            zIndex: styleConfig.zIndex || 9999,
            progressBar: {
                activeColor: styleConfig.progressBar?.activeColor || '#007bff',
                inactiveColor: styleConfig.progressBar?.inactiveColor || '#ccc',
                dotSize: styleConfig.progressBar?.dotSize || '10px',
                dotSpacing: styleConfig.progressBar?.dotSpacing || '5px',
            },
            buttons: {
                prevText: styleConfig.buttons?.prevText || 'Indietro',
                nextText: styleConfig.buttons?.nextText || 'Avanti',
                finishText: styleConfig.buttons?.finishText || 'Fine',
                prevColor: styleConfig.buttons?.prevColor || '#007bff',
                nextColor: styleConfig.buttons?.nextColor || '#007bff',
                finishColor: styleConfig.buttons?.finishColor || '#28a745',
                buttonPosition: styleConfig.buttons?.buttonPosition || 'center',
                prevClass: styleConfig.buttons?.prevClass || '',
                nextClass: styleConfig.buttons?.nextClass || '',
                finishClass: styleConfig.buttons?.finishClass || '',
            },
            closeButton: {
                show: styleConfig.closeButton?.show !== false,
                color: styleConfig.closeButton?.color || '#000',
                size: styleConfig.closeButton?.size || '24px',
            },
            ...styleConfig
        };

        this.overlay = null;
        this.formValidator = null;
        this.popup = null;
        this.handleNextButtonClickBound = this.handleNextButtonClick.bind(this);
    }

    createPopup() {
        if (!this.popup) {
            this.createPopupElements();
        }
    }

    createPopupElements() {
        // Creazione dell'overlay
        this.overlay = document.createElement('div');
        Object.assign(this.overlay.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0, 0, 0, 0.5)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: this.styleConfig.zIndex,
        });
        this.overlay.addEventListener('click', (e) => this.handleOutsideClick(e));
        this.container.appendChild(this.overlay);

        // Creazione del popup
        this.popup = document.createElement('div');
        Object.assign(this.popup.style, {
            backgroundColor: this.styleConfig.backgroundColor,
            color: this.styleConfig.textColor,
            padding: this.styleConfig.padding,
            borderRadius: this.styleConfig.borderRadius,
            position: 'relative',
            transition: `width ${this.styleConfig.transitionDuration}, height ${this.styleConfig.transitionDuration}`,
            width: this.styleConfig.width,
            height: this.styleConfig.height,
            maxWidth: this.styleConfig.maxWidth,
            maxHeight: this.styleConfig.maxHeight,
            display: 'flex',
            flexDirection: 'column',
            boxSizing: 'border-box',
            overflow: 'hidden',
        });
        this.popup.addEventListener('click', (e) => e.stopPropagation());
        this.overlay.appendChild(this.popup);

        // Bottone di chiusura (utilizzando <div> invece di <button>)
        if (this.styleConfig.closeButton.show) {
            this.closeButton = document.createElement('div');
            this.closeButton.innerText = '×';
            Object.assign(this.closeButton.style, {
                position: 'absolute',
                top: '10px',
                right: '10px',
                background: 'transparent',
                border: 'none',
                fontSize: this.styleConfig.closeButton.size,
                color: this.styleConfig.closeButton.color,
                cursor: 'pointer',
                zIndex: '10',
                lineHeight: '1',
            });
            this.closeButton.setAttribute('aria-label', 'Chiudi Popup');
            this.closeButton.setAttribute('role', 'button');
            this.closeButton.setAttribute('tabindex', '0');
            this.closeButton.addEventListener('click', () => this.handleCloseButtonClick());
            this.closeButton.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.handleCloseButtonClick();
                }
            });
            this.popup.appendChild(this.closeButton);
        }

        // Barra di progresso
        if (this.pages.length > 1) {
            this.progressBar = document.createElement('div');
            Object.assign(this.progressBar.style, {
                display: 'flex',
                justifyContent: 'center',
                marginBottom: '20px',
                flexShrink: '0',
            });
            this.popup.appendChild(this.progressBar);

            this.pages.forEach((_, index) => {
                const dot = document.createElement('div');
                Object.assign(dot.style, {
                    width: this.styleConfig.progressBar.dotSize,
                    height: this.styleConfig.progressBar.dotSize,
                    borderRadius: '50%',
                    backgroundColor: index === 0
                        ? this.styleConfig.progressBar.activeColor
                        : this.styleConfig.progressBar.inactiveColor,
                    margin: `0 ${this.styleConfig.progressBar.dotSpacing}`,
                });
                this.progressBar.appendChild(dot);
            });
        }

        // Contenitore delle pagine
        this.pageContainer = document.createElement('div');
        Object.assign(this.pageContainer.style, {
            flex: '1 1 auto',
            overflowY: 'auto',
            padding: '10px 0',
            boxSizing: 'border-box',
        });
        this.popup.appendChild(this.pageContainer);

        // Contenitore dei bottoni
        this.buttonContainer = document.createElement('div');
        this.buttonContainer.classList.add('btn-popup');
        Object.assign(this.buttonContainer.style, {
            display: 'flex',
            justifyContent: this.styleConfig.buttons.buttonPosition,
            gap: '10px',
            flexShrink: '0',
            marginTop: '20px',
        });
        this.popup.appendChild(this.buttonContainer);

        // Bottone "Indietro"
        this.prevButton = document.createElement('button');
        this.prevButton.innerText = this.styleConfig.buttons.prevText;
        Object.assign(this.prevButton.style, {
            backgroundColor: this.styleConfig.buttons.prevColor,
            color: '#fff',
            border: 'none',
            padding: '10px 20px',
            borderRadius: '5px',
            cursor: 'pointer',
            flex: '0 0 auto',
            display: 'none',
        });
        this.prevButton.addEventListener('click', () => this.showPage(this.currentPage - 1));

        // Applica la classe personalizzata se fornita
        if (this.styleConfig.buttons.prevClass) {
            const prevClasses = this.styleConfig.buttons.prevClass.split(' ');
            this.prevButton.classList.add(...prevClasses);
        }

        this.buttonContainer.appendChild(this.prevButton);

        // Bottone "Prossimo/Fine"
        this.nextButton = document.createElement('button');
        this.nextButton.innerText = this.styleConfig.buttons.nextText;
        Object.assign(this.nextButton.style, {
            backgroundColor: this.styleConfig.buttons.nextColor,
            color: '#fff',
            border: 'none',
            padding: '10px 20px',
            borderRadius: '5px',
            cursor: 'pointer',
            flex: '0 0 auto',
        });
        this.nextButton.addEventListener('click', this.handleNextButtonClickBound);

        // Applica la classe personalizzata se fornita
        if (this.styleConfig.buttons.nextClass) {
            const nextClasses = this.styleConfig.buttons.nextClass.split(' ');
            this.nextButton.classList.add(...nextClasses);
        }

        this.buttonContainer.appendChild(this.nextButton);
    }

    handleCloseButtonClick() {
        if (this.onCloseCallback) {
            this.onCloseCallback(() => this.closePopup());
        } else {
            this.closePopup();
        }
    }

    handleOutsideClick(event) {
        if (event.target === this.overlay) {
            this.handleCloseButtonClick();
        }
    }

    handleNextButtonClick() {
        if (this.validateOnNext) {
            const currentPageValidationRules = this.pages[this.currentPage].validationRules || {};
            this.formValidator = new FormValidator(currentPageValidationRules);
            const isValid = this.formValidator.validateInputs(this.pageContainer);

            if (!isValid) return; // Blocca l'avanzamento se il form non è valido
        }

        const result = this.pages[this.currentPage].onNext();

        if (result === false) return;

        if (this.currentPage === this.pages.length - 1) {
            this.finalizePopup();
        } else {
            this.showNextPage();
        }
    }

    showPage(index) {
        if (index < 0 || index >= this.pages.length) return;

        this.currentPage = index;

        const page = this.pages[index];

        // Applica le dimensioni globali o specifiche per pagina
        this.popup.style.width = page.width || this.styleConfig.width;
        this.popup.style.height = page.height || this.styleConfig.height;
        this.popup.style.maxWidth = page.maxWidth || this.styleConfig.maxWidth;
        this.popup.style.maxHeight = page.maxHeight || this.styleConfig.maxHeight;

        this.pageContainer.innerHTML = page.content;

        if (page.onShow) {
            page.onShow();
        }

        if (this.progressBar) {
            Array.from(this.progressBar.children).forEach((dot, dotIndex) => {
                dot.style.backgroundColor = dotIndex === index
                    ? this.styleConfig.progressBar.activeColor
                    : this.styleConfig.progressBar.inactiveColor;
            });
        }

        this.prevButton.style.display = index === 0 ? 'none' : 'inline-block';
        this.nextButton.innerText = index === this.pages.length - 1
            ? this.styleConfig.buttons.finishText
            : this.styleConfig.buttons.nextText;
        this.nextButton.style.backgroundColor = index === this.pages.length - 1
            ? this.styleConfig.buttons.finishColor
            : this.styleConfig.buttons.nextColor;

        if (index === this.pages.length - 1 && this.styleConfig.buttons.finishClass) {
            const finishClasses = this.styleConfig.buttons.finishClass.split(/\s+/).filter(cls => cls.trim() !== '');
            this.nextButton.classList.add(...finishClasses);
        } else {
            // Rimuove eventuali classi di fine se non è l'ultima pagina
            if (this.styleConfig.buttons.finishClass) {
                const finishClasses = this.styleConfig.buttons.finishClass.split(/\s+/).filter(cls => cls.trim() !== '');
                this.nextButton.classList.remove(...finishClasses);
            }
        }

    }

    showNextPage() {
        this.showPage(this.currentPage + 1);
    }

    finalizePopup() {
        if (this.autoCloseOnLastPage) {
            this.closePopup();
        }
    }

    openPopup() {
        document.body.style.overflow = "hidden";
        if (!this.overlay) {
            this.createPopup();
        } else {
            this.overlay.style.display = 'flex';
        }

        if (this.resetOnClose || this.currentPage === this.pages.length - 1) {
            this.currentPage = 0;
        }
        this.showPage(this.currentPage);
    }

    closePopup() {
        if (this.overlay && this.container.contains(this.overlay)) {
            this.container.removeChild(this.overlay);
            this.overlay = null;
            this.popup = null;
            document.body.style.overflow = "visible";
        }
    }

    getTransitionStyle() {
        switch (this.transition) {
            case 'fade':
                return 'opacity 0.5s ease-in-out';
            case 'slide':
                return 'transform 0.5s ease-in-out';
            default:
                return 'none';
        }
    }
}