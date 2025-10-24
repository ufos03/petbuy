
let prod_worker = null;

export async function build_products(params, operation_type)
{
    if (prod_worker == null)
        prod_worker = new Worker('https://petbuy-local.ns0.it:8080/wp-content/shopper-window/prods/worker_prod.js', {type : 'module'});
    
    const container = document.getElementById('only-products');
    const ul = container.firstElementChild;
    
    if (operation_type == 'first-time')
    {
        prod_worker.postMessage({});
        prod_worker.onmessage = (e) => {

            ul.insertAdjacentHTML('beforeend', e.data);
            container.setAttribute('data-first-time', 'false');
        };
    }

    else if (operation_type == 'filter')
    {
        prod_worker.postMessage(params);

        ul.innerHTML = '';

        prod_worker.onmessage = (e) => {
            ul.insertAdjacentHTML('beforeend', e.data);
        }
    }

    else if (operation_type == 'pagination' && container.getAttribute('data-first-time') === 'false')
    {
        prod_worker.postMessage(params);
        
        return new Promise((resolve, reject) => {
            prod_worker.onmessage = (e) => {
                if (!e.data) {
                    resolve(false); // Risolvi la promise con "false" in caso di errore o dati mancanti.
                } else {
                    ul.insertAdjacentHTML('beforeend', e.data);
                    resolve(true); // Risolvi la promise con "true" quando i dati sono disponibili.
                }
            };
    
            prod_worker.onerror = (err) => {
                reject(err); // Rigetta la promise in caso di errori nel worker.
            };
        });
    }
}