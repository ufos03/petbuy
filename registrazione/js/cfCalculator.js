const CFOptions = 
{
    birthdayDate : "#birthday",
    name : "#name",
    surname : "#surname",
    gender : "#sex", 
    birthplace : "#city",
    birthplacePR : "#provincia",
    dataset : {
        provincie : "https://petbuy-local.ns0.it:8080/wp-content/registrazione/js/province.json",
        comunni : "https://petbuy-local.ns0.it:8080/wp-content/registrazione/js/comuni.json",
        nazioni : "https://petbuy-local.ns0.it:8080/wp-content/registrazione/js/world.json"
    }
}

function calculateCF()
{
    const date = jQuery(CFOptions.birthdayDate).val();
    const splitDate = date.split("-")
    const cf = new CodiceFiscale({
        name: jQuery(CFOptions.name).val(),
        surname: jQuery(CFOptions.surname).val(),
        gender: jQuery(CFOptions.gender).find(":selected").attr("value"),
        day: splitDate[2],
        month: splitDate[1],
        year: splitDate[0],
        birthplace: jQuery(CFOptions.birthplace).find(":selected").attr("value"), 
        birthplaceProvincia: jQuery(CFOptions.birthplacePR).find(":selected").attr("value")
    });
    jQuery("#codice_fiscale").val(cf.code.toString());
    closePopup()
}

function openPopup() 
{ 
    jQuery(".overlay").addClass("overlay-visible");
    insertProvinceSelect()
    triggerChangeProvincia()
}

function closePopup()
{
    jQuery(".overlay").removeClass("overlay-visible");
}

function closePopupOnDocument()
{
    jQuery(document).on('click touch', ".overlay", function(e) {
        var container = jQuery('.modal-popup');
    
        if (jQuery(e.target).closest(container).length == 0) {
          closePopup()
        }
    });
}

function popupHandler()
{
    jQuery("#codice_fiscale").click(function (e) { 
        e.preventDefault();
        openPopup()
    });
    jQuery(".mp-close").click(function (e) { 
        e.preventDefault();
        closePopup()
    });
    closePopupOnDocument()
}

function triggerCalculator()
{
    jQuery("#calc-cf").click(function (e) { 
        e.preventDefault();
        checkLenghtOfParamsCF()
        const errors = jQuery(".inner-mp-popup > .error").length
        if (errors > 0)
            return
        calculateCF()
    });
}

function insertProvinceSelect()
{
    const regioni = ["Abruzzo", "Basilicata", "Calabria", "Campania", "Emilia-Romagna", "Friuli-Venezia Giulia", "Lazio", "Liguria", "Lombardia", "Marche", "Molise", "Piemonte", "Puglia", "Sardegna", "Sicilia", "Toscana", "Trentino-Alto Adige", "Umbria", "Valle d'Aosta/Vallée d'Aoste", "Veneto"]
    fetch(CFOptions.dataset.provincie)
    .then(function (response) {
        return response.json()
    })
    .then(function (data) {
        for (let i = 0; i < data.length; i++) 
        {
            for (let index = 0; index < data[i][regioni[i]].length; index++) {
                jQuery(CFOptions.birthplacePR).append(`<option value="${data[i][regioni[i]][index].sigla}">${data[i][regioni[i]][index].nome}</option>`);
            }
        }
    })
    .catch(function (err) {
        console.log(err);
    });
}

function getComuni(idProvincia, dataset = CFOptions.dataset.comunni)
{
    fetch(dataset)
    .then(function (response) {
        return response.json()
    })
    .then(function (data) {
        jQuery(CFOptions.birthplace + "> option").remove();
        jQuery(CFOptions.birthplace).append(`<option value="-1" selected="selected">Città</option>`);

        if (idProvincia == -1)
            return;

        if (idProvincia == "EE")
        {
            jQuery(CFOptions.birthplace).append(`<option value="-1" selected="selected">Stato</option>`);
            for (let index = 0; index < data.length; index++) {
                jQuery(CFOptions.birthplace).append(`<option value="${data[index].code}">${data[index].name}</option>`);
            }
            return
        }

        data[idProvincia].forEach(citta =>{
            jQuery(CFOptions.birthplace).append(`<option value="${citta.nome}">${citta.nome}</option>`);
        })
    })
    .catch(function (err) {
        console.log(err);
    });
}


function triggerChangeProvincia()
{
    jQuery(CFOptions.birthplacePR).change(function (e) { 
        e.preventDefault();
        const idProvincia = jQuery(CFOptions.birthplacePR).find(":selected").attr("value");
        if (idProvincia == "EE")
            getComuni(idProvincia, CFOptions.dataset.nazioni)
        else
            getComuni(idProvincia)
    });
}

function checkLenghtOfParamsCF() {

    if (isEmpty(jQuery(CFOptions.name).val()))
        throwErrorTo(CFOptions.name)
    else
        removeErrorFrom(CFOptions.name)

    if (isEmpty(jQuery(CFOptions.surname).val()))
        throwErrorTo(CFOptions.surname)
    else
        removeErrorFrom(CFOptions.surname)

    if (jQuery(CFOptions.gender).find(":selected").attr("value") == -1)
        throwErrorTo(CFOptions.gender)
    else
        removeErrorFrom(CFOptions.gender)

    if (isEmpty(jQuery(CFOptions.birthdayDate).val()))
        throwErrorTo(CFOptions.birthdayDate)
    else
        removeErrorFrom(CFOptions.birthdayDate)

    if (jQuery(CFOptions.birthplacePR).find(":selected").attr("value") == -1)
        throwErrorTo(CFOptions.birthplacePR)
    else
        removeErrorFrom(CFOptions.birthplacePR)

    if (jQuery(CFOptions.birthplace).find(":selected").attr("value") == -1)
        throwErrorTo(CFOptions.birthplace)
    else
        removeErrorFrom(CFOptions.birthplace)
}