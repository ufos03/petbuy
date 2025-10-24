const configurazione = {
    selectRegione : "#region",
    selectProvincia : "#provincia",
    selectCitta : "#citta",
}

const datasetLinks = {
    provincie : "https://petbuy-local.ns0.it:8080/wp-content/registrazione/js/province.json",
    comuni : "https://petbuy-local.ns0.it:8080/wp-content/registrazione/js/comuni.json"
}

const regioni = ["Abruzzo", "Basilicata", "Calabria", "Campania", "Emilia-Romagna", "Friuli-Venezia Giulia", "Lazio", "Liguria", "Lombardia", "Marche", "Molise", "Piemonte", "Puglia", "Sardegna", "Sicilia", "Toscana", "Trentino-Alto Adige", "Umbria", "Valle d'Aosta/Vallée d'Aoste", "Veneto"]


function printRegioneSelect()
{
    let i = 0;
    regioni.forEach(regione => {
        jQuery(configurazione.selectRegione).append(`<option value="${i}">${regione}</option>`);
        i++;
    });
}

function triggerChangeRegione()
{
    jQuery(configurazione.selectRegione).change(function (e) { 
        e.preventDefault();
        const idRegione = jQuery(configurazione.selectRegione).find(":selected").attr("value");
        getProvince(idRegione);
    });
}

function printProvinceSelect(regione, province)
{
    jQuery(configurazione.selectProvincia + "> option").remove();
    jQuery(configurazione.selectProvincia).append(`<option value="-1" selected="selected">Provincia</option>`);

    if (regione < 0)
        return;
    
    province[regione][regioni[regione]].forEach(provincia =>{
        jQuery(configurazione.selectProvincia).append(`<option value="${provincia.sigla}">${provincia.nome}</option>`);
    })
}

function getProvince(idRegione)
{
    fetch(datasetLinks.provincie)
    .then(function (response) {
        return response.json()
    })
    .then(function (data) {
        printProvinceSelect(idRegione, data)
    })
    .catch(function (err) {
        console.log(err);
    });
}


function printCittaSelect(idProvincia,data)
{
   jQuery(configurazione.selectCitta + "> option").remove();
   jQuery(configurazione.selectCitta).append(`<option value="-1" selected="selected">Città</option>`);

   if (idProvincia == -1)
        return;

    data[idProvincia].forEach(citta =>{
        jQuery(configurazione.selectCitta).append(`<option value="${citta.nome}">${citta.nome}</option>`);
    })
}

function getComuni(idProvincia)
{
    fetch(datasetLinks.comuni)
    .then(function (response) {
        return response.json()
    })
    .then(function (data) {
        printCittaSelect(idProvincia, data)
    })
    .catch(function (err) {
        console.log(err);
    });
}


function triggerChangeProvincia()
{
    jQuery(configurazione.selectProvincia).change(function (e) { 
        e.preventDefault();
        const idProvincia = jQuery(configurazione.selectProvincia).find(":selected").attr("value");
        getComuni(idProvincia)
    });
}

function checkLengthOfParamsAddress()
{
    if (isEmpty(jQuery("#indirizzo").val()))
        throwErrorTo("#indirizzo")
    else
        removeErrorFrom("#indirizzo")

    if (isEmpty(jQuery("#cap").val()))
        throwErrorTo("#cap")
    else
        removeErrorFrom("#cap")

    if (jQuery(configurazione.selectRegione).find(":selected").attr("value") == -1)
        throwErrorTo(configurazione.selectRegione)
    else
        removeErrorFrom(configurazione.selectRegione)

    if (jQuery(configurazione.selectProvincia).find(":selected").attr("value") == -1)
        throwErrorTo(configurazione.selectProvincia)
    else
        removeErrorFrom(configurazione.selectProvincia)

    if (jQuery(configurazione.selectCitta).find(":selected").attr("value") == -1)
        throwErrorTo(configurazione.selectCitta)
    else
        removeErrorFrom(configurazione.selectCitta)
}

function getAddress()
{
    const data = {
        region: jQuery("#region").find(":selected").attr("value"),
        state: jQuery("#provincia").find(":selected").attr("value"),
        city: jQuery("#citta").find(":selected").attr("value"),
        address: jQuery("#indirizzo").val(),
        zip: jQuery("#cap").val(),
    };
    return data;
}

function registerUser()
{
    jQuery(".forward").on("click", function() {
        jQuery.ajax({
            type: "GET",
            url: "https://petbuy-local.ns0.it:8080/wp-json/api/v1/createuserpt",
            async: false,
            data: {"step1" : getData("step1"), "step2" : getData("step2"), "step3" : getData("step3"), "role" : localStorage.getItem("role")},
            success: function (response) {
                destroyData("step1");
                destroyData("step2");
                destroyData("step3");
                destroyData("role");
            },
            error: function (error) { 
                showError(error.responseJSON.status)
            }
        });
    })
}

jQuery(document).ready(function () {
    printRegioneSelect();
    triggerChangeRegione();
    triggerChangeProvincia();
    verifyData(checkLengthOfParamsAddress)
    cryptAndSaveData(getAddress, "step3")
    registerUser()
});