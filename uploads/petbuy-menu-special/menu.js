const configureOptions = {
    triggerOpen : "#click",
    triggerClose : "#close",
    catURL : "./categorie.json"   // file JSON con le categorie del menu
  }
  
  const animations = ["swipe-right 0.5s ease-in-out", "swipe-left 0.5s ease-in-out", "swipe-up 0.5s ease-in-out"];  // animazioni implementate
  let prevCatClicked = 0;  // usata per impedire il caricamento dello stesso sottomenu al click -> 0 all'avvio, in quanto nessun pannello mai aperto
  
  function isMobile() {
      if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i)
          || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i))
          return true
      return false
  }
  
  async function loadCategories() {  // carichiamo il file e lo restituiamo come oggetto JSON
      const response = await fetch(configureOptions.catURL);
      return response.json();
  }
  
  
  function loadSubCatPanel() {
      $(document).on("click", ".aside-list-item", function () {  // evento 'click' delegato al document
          const id = $(this).data("cat-id");  // prendiamo cat-id della categoria di cui mostare le sottocategorie
          if (id == prevCatClicked)
              return;  // controlliamo che la categoria cliccata, non abbia il pannello sottocategorie già in primo piano
          $('.aside-list-item').removeClass("active");  // rimuoviamo da tutti gli elementi 'active'
          $(this).addClass("active"); // blocchiamo la linea bianca per mettere in risalto quale categoria ha aperto l'utente
          $('#right-side-menu > div.panel').removeClass("visible");  // rimuoviamo la classe 'visible' al pannello aperto precedentemente
          prevCatClicked = id;  // salviamo il pannello aperto al momento
          const animation = Math.floor(Math.random() * 3);  // scegliamo tra le 3 'animations' disponibili
  
          $($("#right-side-menu").find(`[data-panel-id='${id}']`)).css("animation", animations[animation]);  // troviamo il pannello da aprire e aggiungiamo l'animazione
          $("#right-side-menu").find(`[data-panel-id='${id}']`).addClass("visible");  // aggiungiamo la classe visible -> pannello in primo piano
          setTimeout(() => {
              $($("#right-side-menu").find(`[data-panel-id='${id}']`)).css("animation", "");
          }, 500);  // rimuoviamo l'animazione dopo la terminazione, altrimenti il pannello 'n' non sarà animato al prossimo evento 
      });
  }
  
  function categoriesForDesktop(categories) {
      categories.forEach(category => {
          $("ul#left-list").append(`<li class="aside-list-item aside-anchor" data-cat-id="${category.id}">${category.name}</li>`);
          const image = `<div class="aside-image">
                              <img src="${category.image}" class="menu-image ">
                         </div>`;
          let subCategoriesList = `<ul class="aside-list grid-list subcategories">`;
          category.subcategories.forEach(subcategory => {
              subCategoriesList += `<li><a href="${subcategory.link}" class="aside-anchor">${subcategory.name}</a></li>`;
          })
          subCategoriesList += "</ul>";
          $("div#right-side-menu").append(`<div class="panel hidden" data-panel-id="${category.id}">` + image + subCategoriesList + "</div");
      });
  
  }
  
  function categoriesForMobile(params) {
      
  }
  
  function openMenu() {
      $(document).on("click", configureOptions.triggerOpen, function () {
          $(".aside-right").addClass("show-right");
          $(".aside-left").addClass("show-left");
      });
  }
  
  function closeMenu() {
      $(document).on("click", configureOptions.triggerClose, function () {
          $(".aside-right").removeClass("show-right");
          $(".aside-left").removeClass("show-left");
      });
  }