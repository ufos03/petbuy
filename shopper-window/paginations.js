import { build_ads } from "./ads/main.js";
import { build_mix_of_ads_and_prods } from "./ads_and_prods/main.js";
import { build_products } from "./prods/main.js";
import { get_orders, objects_per_page } from "./utils.js";

const observer = new IntersectionObserver(paginate_to, {
    root: null,
    rootMargin: '0px 0px 300px 0px',
    threshold: 0.2
})

const only_products = document.getElementById('only-products-pagination');
const only_ads = document.getElementById('only-advertisements-pagination');
const mix_ads = document.getElementById('mix-pagination')

function paginate_to(entries)
{
    entries.forEach(entry => {
        
        if (entry.isIntersecting)
        {
            if (entry.target.id === 'mix-pagination')
            {
                const orders = get_orders();
                const new_page = parseInt(mix_ads.getAttribute('data-page')) + 1;
            
                build_mix_of_ads_and_prods({
                    min_price: sessionStorage.getItem('min_price') || '',
                    max_price: sessionStorage.getItem('max_price') || '',
                    category: sessionStorage.getItem('category') || '',
                    sub_category: sessionStorage.getItem('sub-category') || '',
                    page: new_page,
                    per_page: objects_per_page,
                    order_by: orders.order_by || '',
                    order: orders.order || ''
                    }, 'pagination').then((result) => {
                    if (result)
                        mix_ads.setAttribute('data-page', new_page);        
                    else 
                        return;
                })
            }

            if (entry.target.id === 'only-advertisements-pagination')
            {
                const orders = get_orders();
                const new_page = parseInt(only_ads.getAttribute('data-page')) + 1;

                build_ads({
                    min_price: sessionStorage.getItem('min_price') || '',
                    max_price: sessionStorage.getItem('max_price') || '',
                    category: sessionStorage.getItem('category') || '',
                    sub_category: sessionStorage.getItem('sub_category') || '',
                    sex: '', // TODO: implement UI
                    gift: '', // TODO: implement UI
                    page: new_page,
                    per_page: objects_per_page,
                    order_by: orders.order_by,
                    order: orders.order
                }, 'pagination').then((result) => {
                    if (result)
                        only_ads.setAttribute('data-page', new_page);
                    else
                        return;
                });
            }

            if (entry.target.id === 'only-products-pagination')
            {
                const orders = get_orders();
                const new_page = parseInt(only_products.getAttribute('data-page')) + 1;

                build_products({
                    min_price: sessionStorage.getItem('min_price') || '',
                    max_price: sessionStorage.getItem('max_price') || '',
                    category: sessionStorage.getItem('category') || '',
                    sub_category: sessionStorage.getItem('sub_category') || '',
                    page: new_page,
                    per_page: objects_per_page,
                    order_by: orders.order_by,
                    order: orders.order
                }, 'pagination').then((result) => {
                    if (result)
                        only_products.setAttribute('data-page', new_page);
                    else
                        return;
                });
            }
        }
    });
}

export function pagination_observer()
{
    observer.disconnect();
    observer.observe(only_products);
    observer.observe(only_ads);
    observer.observe(mix_ads);
}