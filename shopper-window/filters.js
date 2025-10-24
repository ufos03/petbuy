import { pagination_observer } from "./paginations.js";


function apply_filters(switch_state)
{

    if (switch_state == 'Annunci e Prodotti')
    {
        const container = document.getElementById('mix-products-advertisements');
        const ul = container.firstElementChild;
        ul.innerHTML = '';

        const counter = document.getElementById('mix-pagination');
        counter.setAttribute('data-page', 0);

        pagination_observer() 
    }
    
    if(switch_state == 'Prodotti')
    {
        const container = document.getElementById('only-products');
        const ul = container.firstElementChild;
        ul.innerHTML = '';
        
        const counter = document.getElementById("only-products-pagination");
        counter.setAttribute('data-page', 0);

        pagination_observer()   
    }
    
    if (switch_state == 'Annunci')
    {
        const container = document.getElementById('only-advertisements');
        const ul = container.firstElementChild;
        ul.innerHTML = '';
        
        const counter = document.getElementById("only-advertisements-pagination");
        counter.setAttribute('data-page', 0);

        pagination_observer()   
    }
       
}

function clear_filters(switch_state)
{
    sessionStorage.clear();
    const select = document.getElementById('orders-page-shop');
    select.value = 'default';

    if (switch_state == 'Annunci e Prodotti')
    {
        const container = document.getElementById('mix-products-advertisements');
        const ul = container.firstElementChild;
        ul.innerHTML = '';

        const counter = document.getElementById('mix-pagination');
        counter.setAttribute('data-page', 0);

        pagination_observer() 
    }
    
    if(switch_state == 'Prodotti')
    {
        const container = document.getElementById('only-products');
        const ul = container.firstElementChild;
        ul.innerHTML = '';
        
        const counter = document.getElementById("only-products-pagination");
        counter.setAttribute('data-page', 0);

        pagination_observer()   
    }
    
    if (switch_state == 'Annunci')
    {
        const container = document.getElementById('only-advertisements');
        const ul = container.firstElementChild;
        ul.innerHTML = '';
        
        const counter = document.getElementById("only-advertisements-pagination");
        counter.setAttribute('data-page', 0);

        pagination_observer()   
    }
}

export function filters()
{
    document.addEventListener('click', function (event) {
        const switch_state = document.getElementsByClassName("switcher")[0].getAttribute('data-active-state');

        if (event.target && event.target.id === 'apply-filters')
            apply_filters(switch_state);

        else if (event.target && event.target.id === 'clear-filters')
            clear_filters(switch_state);

    });
}