
function exclude_and_count_chars(element_id, output_id, limit, exclude_regex)
{
    const inputField = document.getElementById(element_id);
    const counter = document.getElementById(output_id);

    inputField.addEventListener('input', function() {
        jQuery(".msg-error").remove();
        
        if (exclude_regex.test(this.value)) 
        {
            const value = this.value;
            this.value = this.value.replace(exclude_regex, '');
            jQuery(".descr-label").append("<span class='msg-error'> Carattere non consentito ( " + value.charAt(value.length - 1) + " )<span>");
        }
        else
        {
            const currentLength = this.value.length;
            counter.textContent = `Caratteri: ${currentLength}/${limit}`;
        }
    });
    
}

function show_image_uploaded_to_user()
{
    document.getElementById('image').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
    
        reader.onload = function(e) {
            var imgElement = document.getElementById('display-image');
            imgElement.src = e.target.result;
        };
    
        reader.readAsDataURL(file);
    });
    
}

function build_content_popup()
{
    const content_popup = `<main id="petbuy-forms-container" class="flex-center-center contest">
                                <div id="petbuy-formx" class="flex-center-center new-post-popup">

                                    <div class="sagoma-petbuy">
                                        <img src="https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/05/sagoma.png" id="display-image">
                                    </div>

                                    <form id="new-post-form" class="petbuy-form flex-center-center" autocomplete="off">
                                        
                                        <div class="section">
                                            <div class="container-input">
                                                <label for="image" class="form-label">Immagine</label>
                                                <input type="file" name="image" id="image" class="input-petbuy upload-button" accept="image/jpeg, image/png, image/webp" required>
                                            </div>
                                        </div>

                                        <div class="section">
                                            <div class="container-input">
                                                <label for="descr" class="form-label descr-label">Descrizione</label>
                                                <textarea id="descr" name="descr" rows="4" cols="50" placeholder="Scrivi qui..." required class="input-petbuy" maxlength="300"></textarea>
                                                <div id="descr-length"></div>
                                            </div>
                                        </div>

                                        <div class="section">
                                            <div class="container-input">
                                                <label for="name_animal" class="form-label">Nome</label>
                                                <input type="text" name="name_animal" id="name_animal" class="input-petbuy" maxlength="20" required>
                                                <div id="name-length"></div>
                                            </div>
                                        </div>

                                        <div class="section row button-place">
                                            <input class="input-petbuy button new-post-button" type="button" value="Invia">
                                        </div>    
                                    </form>
                                </div>
    
                          </main>`
    jQuery(".new-post-mask").append(content_popup);
    show_image_uploaded_to_user();
    exclude_and_count_chars("descr", "descr-length", 300, /[|_]/g);
    exclude_and_count_chars('name_animal', 'name-length', 20, /[]/g)
}


export function close_popup()
{
    jQuery(".contest").remove();
    setTimeout(() => {
        jQuery("#pt-header").css("z-index", "");
        jQuery(".new-post-mask").removeClass("new-post-active");
    }, 50);
}

function close_popup_on_document()
{
    
    document.getElementById('new-post-mask-id').addEventListener('click', function(event) {
        if (!event.target.closest('#petbuy-formx')) {
            close_popup()
        }
    });
}


function show_popup()
{

    build_content_popup()
    jQuery("#pt-header").css("z-index", "0");
    jQuery(".new-post-mask").addClass("new-post-active");
}

export function popup_handler()
{
    jQuery(document).on("click touch", "#new-post", function () {
        show_popup()
        close_popup_on_document()
    });
}