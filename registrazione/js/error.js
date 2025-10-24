function showError(text)
{
    jQuery(".forward").attr("data-valid", "false");
    jQuery(".text-error").text(text);
    jQuery(".error-box").addClass("show-error");
}