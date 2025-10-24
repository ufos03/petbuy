export function get_orders() {
    const select = document.getElementById('orders-page-shop');
    const selectedOptions = Array.from(select.selectedOptions); // Converte in array
    let orders = {
      order : selectedOptions[0].getAttribute('data-order'),
      order_by : selectedOptions[0].getAttribute('data-order-by'),
    };
  
    return orders;
}

export const objects_per_page = 10;