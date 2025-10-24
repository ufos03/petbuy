<?php
class Walker_Icon_Menu extends Walker_Nav_Menu {
  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $icon_url = get_post_meta($item->ID, '_menu_item_icon', true);
    $classes = implode(' ', $item->classes);

    // Verifica se la voce ha la classe 'modale-carrello'
    $is_modale_carrello = !empty($item->classes) && in_array('modale-carrello', $item->classes);

    // Verifica se siamo nella pagina del carrello WooCommerce
    $is_cart_page = function_exists('is_cart') && is_cart();

    // Costruisci gli attributi del link
    $link_atts = 'class="nav-link"';
    if ($is_modale_carrello && !$is_cart_page) {
      $link_atts .= ' data-bs-toggle="modal" data-bs-target="#carrelloPopup"';
    }

    $output .= '<li class="' . esc_attr($classes) . '">';
    $output .= '<a href="' . esc_url($item->url) . '" ' . $link_atts . '>';

    if ($icon_url) {
      $output .= '<img src="' . esc_url($icon_url) . '" alt="" style="min-width:24px!important;">';
    } else {
      $output .= esc_html($item->title);
    }

    $output .= '</a></li>';
  }
}
