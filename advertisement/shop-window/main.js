import { Switcher } from "../../switch/main.js";
import { build_ads, build_mix_of_ads_and_prods } from "./build_shop_ad.js";

const menuLinks = document.querySelectorAll('#woocommerce_product_categories-1 li a');

// Itera su ciascun link e aggiungi un listener per l'evento 'click'
menuLinks.forEach(function (link) {
  link.addEventListener('click', function (event) {
    event.preventDefault();
  });

  link.addEventListener('contextmenu', function (event) {
    event.preventDefault(); // Impedisce l'apertura del menu contestuale
  });
});

function build_page_for(state) {
  console.log(state);
  if (state === 'Annunci') {
    jQuery("#products").css("display", "none");
    jQuery("#products").css("visibility", "hidden");
    jQuery("#advertisement-products-grid").css("display", "none");
    jQuery("#advertisement-products-grid").css("visibility", "hidden");

    jQuery("#advertisement-grid").css("display", "");
    jQuery("#advertisement-grid").css("visibility", "");
    build_ads()
    return;
  }
  else if (state == 'Prodotti') {
    jQuery("#advertisement-grid").css("display", "none");
    jQuery("#advertisement-grid").css("visibility", "hidden");
    jQuery("#advertisement-products-grid").css("display", "none");
    jQuery("#advertisement-products-grid").css("visibility", "hidden");

    jQuery("#products").css("display", "");
    jQuery("#products").css("visibility", "");
    return;
  }
  else if (state == 'Annunci e Prodotti') {

    jQuery("#products").css("display", "none");
    jQuery("#products").css("visibility", "hidden");
    jQuery("#advertisement-grid").css("display", "none");
    jQuery("#advertisement-grid").css("visibility", "hidden");

    jQuery("#advertisement-products-grid").css("display", "");
    jQuery("#advertisement-products-grid").css("visibility", "");
    build_mix_of_ads_and_prods();
    return;
  }
}

jQuery(document).ready(function () {
  jQuery(".products").attr("id", "products")

  const switcher = new Switcher('.shopic-sorting', {
    width: 128, // Larghezza personalizzata in px
    height: 38, // Altezza personalizzata in px
    states: [
      {
        label: 'Annunci',
        icon: 'fa-bullhorn',
        color: '#e2442b',
      },
      {
        label: 'Annunci e Prodotti',
        icon: 'fa-concierge-bell',
        color: '#4caf50',
      },
      {
        label: 'Prodotti',
        icon: 'fa-box',
        color: '#f8bb39',
      },
    ],
    initialState: 1,
    onChange: (newState) => {
      build_page_for(newState);
    }
  });
})