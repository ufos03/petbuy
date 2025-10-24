import { Switcher } from "../switch/main.js"
import { CategoryList } from "../category-list/main.js";
import { build_ads } from "./ads/main.js";
import { build_mix_of_ads_and_prods } from "./ads_and_prods/main.js";
import { build_products } from "./prods/main.js";

import { pagination_observer } from "./paginations.js";
import { filters } from "./filters.js";

function build_page_for(state) {
  if (state === 'Annunci') {
    jQuery("#only-products").addClass('invisible');
    jQuery("#mix-products-advertisements").addClass('invisible');

    jQuery("#only-advertisements").removeClass("invisible");
    return;
  }
  else if (state == 'Prodotti') {
    jQuery("#mix-products-advertisements").addClass('invisible');
    jQuery("#only-advertisements").addClass('invisible');

    jQuery("#only-products").removeClass("invisible");
    return;
  }
  else if (state == 'Annunci e Prodotti') {
    jQuery("#only-advertisements").addClass('invisible');
    jQuery("#only-products").addClass("invisible");

    const container = document.getElementById('mix-products-advertisements');
    if (container.getAttribute('data-first-time') == 'false')
      jQuery("#mix-products-advertisements").removeClass('invisible');
    return;
  }
}

jQuery(document).ready(async function () {
  const container = document.getElementById('filters-container');
  const categoryList = new CategoryList(container);
  categoryList.init();

  const switcher = new Switcher('.switcher-container', {
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
      sessionStorage.clear();
    }
  });
  build_mix_of_ads_and_prods({}, 'first-time');
  build_ads({}, 'first-time');
  build_products({}, 'first-time');

  pagination_observer();
  filters();
})