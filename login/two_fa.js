import { call_2fa_api } from "./api.js";
import { logout_handler } from "./logout.js";
import { get_user } from "./utils.js";

function two_fa_logic()
{
    let in1 = document.getElementById('otc-1'),
    ins = document.querySelectorAll('input[type="number"]'),
	 splitNumber = function(e) {
		let data = e.data || e.target.value; 
		if ( ! data ) return; 
		if ( data.length === 1 ) return; 
		
		popuNext(e.target, data);

	},
	popuNext = function(el, data) {
		el.value = data[0];
		data = data.substring(1);
		if ( el.nextElementSibling && data.length ) {

			popuNext(el.nextElementSibling, data);
		}
	};

	ins.forEach(function(input) {

		input.addEventListener('keyup', function(e){
			
			if (e.keyCode === 16 || e.keyCode == 9 || e.keyCode == 224 || e.keyCode == 18 || e.keyCode == 17) {
				return;
			}
			
			if ( (e.keyCode === 8 || e.keyCode === 37) && this.previousElementSibling && this.previousElementSibling.tagName === "INPUT" ) {
				this.previousElementSibling.select();
			} else if (e.keyCode !== 8 && this.nextElementSibling) {
				this.nextElementSibling.select();
			}
			
			if ( e.target.value.length > 1 ) {
				splitNumber(e);
			}
		});
		
		input.addEventListener('focus', function(e) {
			if ( this === in1 ) return;
			
			if ( in1.value == '' ) {
				in1.focus();
			}
			
			if ( this.previousElementSibling.value == '' ) {
				this.previousElementSibling.focus();
			}
		});
	});


	in1.addEventListener('input', splitNumber);
}

function verify_two_fa(callback_success, callback_error)
{
	jQuery(document).on("click", "#verify-twofa", function () {
		
		let code = "";
		for (let index = 1; index <= 6; index++)
			code += jQuery(`#otc-${index}`).val();

		call_2fa_api(
			callback_success,
			callback_error,
			code,
			get_user()
		)
	})
}

function stop_two_fa()
{
	jQuery(document).on("click", "#stop-twofa", function () {
		logout_handler();
	})
}


export function initTWOFA(success, error, container_gui)
{
    jQuery(container_gui).empty();
    jQuery(container_gui).append(`		
        <div class = "otc">
            <fieldset>
                <legend>Inserisci il codice OTP inviato alla tua email</legend>
                <label for="otc-1">Number 1</label>
                <label for="otc-2">Number 2</label>
                <label for="otc-3">Number 3</label>
                <label for="otc-4">Number 4</label>
                <label for="otc-5">Number 5</label>
                <label for="otc-6">Number 6</label>

                <div>
                    <input type="number" class="single-digit input-petbuy" pattern="[0-9]*"  value="" inputtype="numeric" autocomplete="one-time-code" id="otc-1" required>
                    <input type="number" class="single-digit input-petbuy" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-2" required>
                    <input type="number" class="single-digit input-petbuy" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-3" required>
                    <input type="number" class="single-digit input-petbuy" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-4" required>
                    <input type="number" class="single-digit input-petbuy" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-5" required>
                    <input type="number" class="single-digit input-petbuy" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-6" required>
                </div>
            </fieldset>
        </div>
        
        <div class="section row">
			<input type="button" value="Annulla" class="input-petbuy button secondary-button" id="stop-twofa">
            <input type="button" value="Verifica" class="input-petbuy button primary-button" id="verify-twofa">
        </div>`
    
    );

    two_fa_logic();
	stop_two_fa();
	verify_two_fa(success, error);
}