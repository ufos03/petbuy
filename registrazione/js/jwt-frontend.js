const config = {
    encUrl : "https://petbuy-local.ns0.it:8080/wp-json/api/v1/encodedata/",
    decUrl : "https://petbuy-local.ns0.it:8080/wp-content/registrazione/decode.php"
}


function saveData(data, keyName)
{
    localStorage.setItem(keyName, JSON.stringify(data));
}

function getData(key)
{
    const d = localStorage.getItem(key)
    return JSON.parse(d);
}

function destroyData(key)
{
    localStorage.removeItem(key);
}


function encodeData(packet, key)
{
    if (key == "step1")
        saveData(JSON.parse(packet).mail, "user_email");
    jQuery.ajax({
        type: "POST",
        url: config.encUrl,
        dataType : "json",
        contentType : "application/json",
        async: false,
        data: JSON.stringify({"packet" : packet}),
        success: function (response) {
            saveData(response['data'], key);
        },
        error: function (error) {
            showError(error.responseJSON.status)
        }
    });
}