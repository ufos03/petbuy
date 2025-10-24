export class OutputMessage {
    // Proprietà statiche per gestire i contenitori e gli stili
    static containers = {};
    static stylesAdded = false;
    static maxMessagesPerPosition = 5; // Limite di messaggi per posizione
  
    constructor(options = {}) {
      // Impostazioni predefinite
      const defaultOptions = {
        text: '',
        imageUrl: null, // URL dell'immagine opzionale
        position: 'top-right', // Posizioni: top-left, top-right, bottom-left, bottom-right, center
        autoClose: false, // Chiudere automaticamente dopo un certo tempo
        duration: 3000, // Durata in millisecondi per autoClose
        maxImageWidth: 200, // Larghezza massima dell'immagine
        maxImageHeight: 200, // Altezza massima dell'immagine
        animation: 'fade', // Animazioni: fade, slide-left, slide-right, slide-up, slide-down, zoom, bounce
        animationDuration: 500, // Durata dell'animazione in millisecondi
        type: 'info', // Tipi: success, error, warning, info
      };
  
      // Unisci le opzioni fornite con quelle predefinite
      this.options = { ...defaultOptions, ...options };
  
      // Crea l'elemento DOM del messaggio
      this.messageElement = document.createElement('div');
      this.messageElement.classList.add('output-message', `output-message-${this.options.type}`);
  
      // Imposta la proprietà di animazione in entrata
      const animationInName = this.getAnimationInName(this.options.animation);
      this.messageElement.style.animation = `${animationInName} ${this.options.animationDuration}ms ease-out forwards`;
  
      // Applica lo stile di base e le animazioni
      this.applyStyles();
  
      // Aggiungi l'icona in base al tipo
      this.addIcon();
  
      // Aggiungi il contenuto del messaggio
      if (this.options.imageUrl) {
        const img = document.createElement('img');
        img.src = this.options.imageUrl;
        img.alt = 'Message Image';
        img.classList.add('message-image');
        this.messageElement.appendChild(img);
      }
  
      const textNode = document.createElement('span');
      textNode.textContent = this.options.text;
      textNode.classList.add('message-text');
      this.messageElement.appendChild(textNode);
  
      // Aggiungi il pulsante di chiusura se necessario
      if (!this.options.autoClose) {
        const closeButton = document.createElement('button');
        closeButton.textContent = '×';
        closeButton.classList.add('close-button');
        closeButton.onclick = () => this.close();
        this.messageElement.appendChild(closeButton);
      }
  
      // Ottieni il contenitore appropriato
      const container = OutputMessage.getContainer(this.options.position);
  
      // Controlla il numero di messaggi nel contenitore
      if (container.childElementCount >= OutputMessage.maxMessagesPerPosition) {
        // Rimuovi il primo messaggio (più vecchio)
        const oldestMessage = container.firstElementChild;
        if (oldestMessage) {
          const oldestInstance = oldestMessage.__outputMessageInstance;
          if (oldestInstance) {
            oldestInstance.close();
          } else {
            oldestMessage.dispatchEvent(new Event('closeMessage'));
          }
        }
      }
  
      // Aggiungi una proprietà per collegare l'elemento al suo istanza
      this.messageElement.__outputMessageInstance = this;
  
      // Aggiungi il messaggio al contenitore
      container.appendChild(this.messageElement);
  
      // Gestisci la chiusura automatica se richiesto
      if (this.options.autoClose) {
        this.autoCloseTimeout = setTimeout(() => this.close(), this.options.duration);
      }
    }
  
    // Metodo per ottenere o creare il contenitore per una posizione
    static getContainer(position) {
      if (!this.containers[position]) {
        // Crea il contenitore se non esiste
        const container = document.createElement('div');
        container.classList.add('output-message-container', position);
        document.body.appendChild(container);
        this.containers[position] = container;
      }
      return this.containers[position];
    }
  
    // Applica gli stili CSS necessari
    applyStyles() {
      if (!OutputMessage.stylesAdded) {
        const style = document.createElement('style');
        style.textContent = `
          /* Contenitori per posizioni */
          .output-message-container {
            position: fixed;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none; /* Permette di cliccare attraverso i contenitori se necessario */
          }
  
          .output-message-container.top-left {
            top: 20px;
            left: 20px;
            align-items: flex-start;
          }
  
          .output-message-container.top-right {
            top: 20px;
            right: 20px;
            align-items: flex-end;
          }
  
          .output-message-container.bottom-left {
            bottom: 20px;
            left: 20px;
            align-items: flex-start;
          }
  
          .output-message-container.bottom-right {
            bottom: 20px;
            right: 20px;
            align-items: flex-end;
          }
  
          .output-message-container.center {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            align-items: center;
          }
  
          /* Messaggi */
          .output-message {
            background-color: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            max-width: 300px;
            box-sizing: border-box;
            opacity: 0; /* Inizialmente nascosto, l'animazione lo rende visibile */
            animation-fill-mode: forwards;
            pointer-events: auto; /* Permette di interagire con il messaggio */
            position: relative; /* Per posizionare il pulsante di chiusura */
          }
  
          /* Tipi di messaggio */
          .output-message-success {
            background-color: #28a745;
          }
  
          .output-message-error {
            background-color: #dc3545;
          }
  
          .output-message-warning {
            background-color: #ffc107;
            color: #212529;
          }
  
          .output-message-info {
            background-color: #17a2b8;
          }
  
          .output-message .message-image {
            max-width: 200px;
            max-height: 200px;
            margin-right: 10px;
            object-fit: contain;
            border-radius: 4px;
          }
  
          .output-message .message-text {
            flex: 1;
            font-size: 1rem;
          }
  
          /* Icone per i tipi */
          .output-message .message-icon {
            margin-right: 10px;
            font-size: 1.2rem;
          }
  
          /* Pulsante di chiusura */
          .output-message .close-button {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.2rem;
            margin-left: 10px;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 10px;
          }
  
          /* Animazioni */
          /* Fade */
          @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
          }
          @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
          }
  
          /* Slide Left */
          @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
          }
          @keyframes slideOutLeft {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(-100%); opacity: 0; }
          }
  
          /* Slide Right */
          @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
          }
          @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
          }
  
          /* Slide Up */
          @keyframes slideInUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
          }
          @keyframes slideOutUp {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(-100%); opacity: 0; }
          }
  
          /* Slide Down */
          @keyframes slideInDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
          }
          @keyframes slideOutDown {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(100%); opacity: 0; }
          }
  
          /* Zoom */
          @keyframes zoomIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
          }
          @keyframes zoomOut {
            from { transform: scale(1); opacity: 1; }
            to { transform: scale(0.5); opacity: 0; }
          }
  
          /* Bounce */
          @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); }
          }
          @keyframes bounceOut {
            from { transform: scale(1); opacity: 1; }
            to { transform: scale(0.3); opacity: 0; }
          }
  
          /* Responsività */
          @media (max-width: 600px) {
            .output-message-container {
              width: 90%;
              max-width: 300px;
            }
  
            .output-message {
              padding: 10px 15px;
              max-width: 100%;
            }
  
            .output-message .message-image {
              max-width: 100px;
              max-height: 100px;
              margin-right: 5px;
            }
  
            .output-message .message-text {
              font-size: 0.9rem;
            }
  
            .output-message .close-button {
              font-size: 1rem;
              top: 5px;
              right: 10px;
            }
  
            .output-message .message-icon {
              font-size: 1rem;
              margin-right: 5px;
            }
          }
        `;
        document.head.appendChild(style);
        OutputMessage.stylesAdded = true;
      }
  
      // Aggiungi l'animazione di chiusura
      this.addCloseAnimation();
    }
  
    // Metodo per ottenere il nome dell'animazione in entrata
    getAnimationInName(animation) {
      switch (animation) {
        case 'fade':
          return 'fadeIn';
        case 'slide-left':
          return 'slideInLeft';
        case 'slide-right':
          return 'slideInRight';
        case 'slide-up':
          return 'slideInUp';
        case 'slide-down':
          return 'slideInDown';
        case 'zoom':
          return 'zoomIn';
        case 'bounce':
          return 'bounceIn';
        default:
          return 'fadeIn';
      }
    }
  
    // Metodo per ottenere il nome dell'animazione in uscita
    getAnimationOutName(animation) {
      switch (animation) {
        case 'fade':
          return 'fadeOut';
        case 'slide-left':
          return 'slideOutLeft';
        case 'slide-right':
          return 'slideOutRight';
        case 'slide-up':
          return 'slideOutUp';
        case 'slide-down':
          return 'slideOutDown';
        case 'zoom':
          return 'zoomOut';
        case 'bounce':
          return 'bounceOut';
        default:
          return 'fadeOut';
      }
    }
  
    // Aggiungi l'animazione di chiusura
    addCloseAnimation() {
      const animationOutName = this.getAnimationOutName(this.options.animation);
      const duration = this.options.animationDuration;
  
      // Ascolta l'evento di chiusura per applicare l'animazione di uscita
      this.messageElement.addEventListener('closeMessage', () => {
        // Imposta l'animazione di uscita
        this.messageElement.style.animation = `${animationOutName} ${duration}ms ease-out forwards`;
  
        // Rimuovi l'elemento dopo l'animazione
        setTimeout(() => {
          if (this.messageElement.parentNode) {
            this.messageElement.parentNode.removeChild(this.messageElement);
          }
        }, duration);
      });
    }
  
    // Aggiungi icona in base al tipo
    addIcon() {
      const iconElement = document.createElement('span');
      iconElement.classList.add('message-icon');
  
      switch (this.options.type) {
        case 'success':
          iconElement.innerHTML = '&#10004;'; // ✔
          break;
        case 'error':
          iconElement.innerHTML = '&#10060;'; // ❌
          break;
        case 'warning':
          iconElement.innerHTML = '&#9888;'; // ⚠
          break;
        case 'info':
        default:
          iconElement.innerHTML = '&#8505;'; // ℹ
          break;
      }
  
      this.messageElement.appendChild(iconElement);
    }
  
    // Imposta la posizione del messaggio
    setPosition(position) {
      const positions = ['top-left', 'top-right', 'bottom-left', 'bottom-right', 'center'];
      const positionClass = positions.includes(position) ? position : 'top-right';
      this.messageElement.classList.add(positionClass);
    }
  
    // Chiude e rimuove il messaggio dal DOM con animazione
    close() {
      // Se autoClose è attivo, cancella il timeout per evitare chiusure premature
      if (this.options.autoClose && this.autoCloseTimeout) {
        clearTimeout(this.autoCloseTimeout);
      }
  
      const closeEvent = new Event('closeMessage');
      this.messageElement.dispatchEvent(closeEvent);
    }
  }