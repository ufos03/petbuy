
let ads_and_prod_worker = null;

export async function build_mix_of_ads_and_prods(params, operation_type) 
{
    if (ads_and_prod_worker == null)
        ads_and_prod_worker = new Worker('https://petbuy-local.ns0.it:8080/wp-content/shopper-window/ads_and_prods/worker_ads_and_prods.js', { type: 'module' });
    
    const container = document.getElementById("mix-products-advertisements");
    const ul = container.firstElementChild;

    if (operation_type == 'first-time') 
    {
        ads_and_prod_worker.postMessage({});
        ads_and_prod_worker.onmessage = (e) => {
            // Append e.data to the <ul> element
            ul.insertAdjacentHTML('beforeend', e.data);

            const skeleton = document.getElementById('skeletons-cards');
            skeleton.remove();

            // Remove 'invisible' class using JavaScript
            container.classList.remove('invisible');
            container.setAttribute('data-first-time', 'false');
        };
    }
    else if (operation_type == 'filters')
    {
        ads_and_prod_worker.postMessage(params);

        ul.innerHTML = '';

        ads_and_prod_worker.onmessage = (e) => {
            ul.insertAdjacentHTML('beforeend', e.data);
        };
    }

    else if (operation_type == 'pagination' && container.getAttribute('data-first-time') === 'false')
    {
        ads_and_prod_worker.postMessage(params);

        return new Promise((resolve, reject) => {
            ads_and_prod_worker.onmessage = (e) => {
                if (!e.data) {
                    resolve(false); // Risolvi la promise con "false" in caso di errore o dati mancanti.
                } else {
                    ul.insertAdjacentHTML('beforeend', e.data);
                    resolve(true); // Risolvi la promise con "true" quando i dati sono disponibili.
                }
            };
    
            ads_and_prod_worker.onerror = (err) => {
                reject(err); // Rigetta la promise in caso di errori nel worker.
            };
        });
        
    }
}