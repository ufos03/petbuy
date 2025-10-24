<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
const url = window.location.href;

if(url.includes('negozio'))
{
	const script = document.createElement('script')
	script.type = 'module'
	script.src = 'http://petbuy.local/wp-content/shopper-window/main.js'
	
	const link = document.createElement('link');
    link.rel = 'stylesheet'; // Indica che è un foglio di stile
    link.href = 'http://petbuy.local/wp-content/shopper-window/style.css';         // URL del file CSS
    link.type = 'text/css';  // Tipo MIME (opzionale)

    // Aggiunge il <link> al <head> del documento
    document.head.appendChild(link);
	document.head.appendChild(script)
}
</script>
<!-- end Simple Custom CSS and JS -->
