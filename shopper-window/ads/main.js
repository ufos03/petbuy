let ads_worker = null;


export async function build_ads(params, operation_type)
{
    if (ads_worker == null)
        ads_worker = new Worker('https://petbuy-local.ns0.it:8080/wp-content/shopper-window/ads/worker_ads.js', {type : 'module'});
    
    const container = document.getElementById('only-advertisements')
    const ul = container.firstElementChild;

    if (operation_type == 'first-time')
    {
        ads_worker.postMessage({});
        ads_worker.onmessage = (e) => {

            ul.insertAdjacentHTML('beforeend', e.data);

            container.setAttribute('data-first-time', 'false');
        };
    }
    else if (operation_type == 'filters')
    {
        ads_worker.postMessage(params);
        const button = document.getElementById('apply-filters');
        button.disabled = true;

        ul.innerHTML = '';
        ads_worker.onmessage = (e) => {
            ul.insertAdjacentHTML('beforeend', e.data);
            button.disabled = false;
        }
    }
    else if (operation_type == 'pagination' && container.getAttribute('data-first-time') === 'false')
    {
        ads_worker.postMessage(params);
        
        return new Promise((resolve, reject) => {
            ads_worker.onmessage = (e) => {
                if (!e.data) {
                    resolve(false); // Risolvi la promise con "false" in caso di errore o dati mancanti.
                } else {
                    ul.insertAdjacentHTML('beforeend', e.data);
                    resolve(true); // Risolvi la promise con "true" quando i dati sono disponibili.
                }
            };
    
            ads_worker.onerror = (err) => {
                reject(err); // Rigetta la promise in caso di errori nel worker.
            };
        });
    }

}