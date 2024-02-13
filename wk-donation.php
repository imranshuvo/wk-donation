<?php 
/*
Plugin Name: Donation for bornebasketfonden.dk
Plugin URI: https://bornebasketfonden.dk/
Description: Donation form for https://bornebasketfonden.dk/
Author: Webkonsulenterne, Imran Khan
Version: 1.0.0
Author URI: https://webkonsulenterne.dk/
*/

require __DIR__.'/bootstrap.php';


add_action('wp_enqueue_scripts','wk_donation_form_assets_enque');

function wk_donation_form_assets_enque(){
	wp_enqueue_style('wk-donation-style', plugin_dir_url( __FILE__ ).'/css/style.css');
}

add_shortcode('wk_donation_form','wk_donation_form_callback');

function wk_donation_form_callback(){
	ob_start();
	?>
	<div class="wk-donation-form-wrapper">
		<div class="donation-form-container">
			<form id="wk-donation-form" class="form-horizontal">
				<label for="payment_type" style="display:none;">Betalingstype</label>
				<div class="form-group payment-type-container">
					<a href="#" id="onetime" class="selected"><span>Enkelt donation</span></a>
					<a href="#" id="recurring" class=""><span>Støt månedligt</span></a>
				</div>
				<!-- Payment amount options -->
				<div class="form-group donation-amount-container">
					<label for="field_amount" class="control-label"><strong>Beløb</strong></label>
					<div class="onetime-fields">
						<div class="form-field" id="onefirst">125 DKK
							<input type="radio" name="one_donation_amount" value="125" checked="checked">
							<span class="checkmark"></span>
						</div>
						<div class="form-field">450 DKK 
							<input type="radio" name="one_donation_amount" value="450">
							<span class="checkmark"></span>
						</div>
						<div class="form-field">1000 DKK
							<input type="radio" name="one_donation_amount" value="1000">
							<span class="checkmark"></span>
						</div>
						<div class="form-field"> ANDET
							<input type="radio" name="one_donation_amount" value="onetimecustom" id="onetimecustom">
							<span class="checkmark"></span>
						</div>
					</div>
					<div class="recurring-fields" style="display: none;">
						<div class="form-field" id="recurringfirst">100 DKK
							<input type="radio" name="recurring_donation_amount" value="100" checked="checked">
							<span class="checkmark"></span>
						</div>
						<div class="form-field">150 DKK
							<input type="radio" name="recurring_donation_amount" value="150">
							<span class="checkmark"></span>
						</div>
						<div class="form-field">300 DKK
							<input type="radio" name="recurring_donation_amount" value="300">
							<span class="checkmark"></span>
						</div>
						<div class="form-field">ANDET
							<input type="radio" name="recurring_donation_amount" value="recurringcustom" id="recurringcustom">
							<span class="checkmark"></span>
						</div>
					</div>
				</div>
				<div class="form-group custom-amount-container" style="display: none;">
					<div class="form-field">
						<input type="number" name="donation_amount_custom" id="donation_amount_custom" value="">
					</div>
				</div>

				<!-- Payment method options --> 
				<div class="donation-payment-providers">
					<ul>
						<li id="mobilepay">
							<label class="form-radio-label" for="field_paymentMethodType_mobilepay">
								<div class="form-field"> MOBILEPAY
									<input type="radio" name="paymentMethodType" value="MobilePay" id="field_paymentMethodType_mobilepay" class="form-radio-input valid" title="Vælg venligst" required="" checked="" aria-invalid="false"> 
									<span class="checkmark"></span>
								</div>
								<span class="payment-item">
									<div class="pay-icons pay-mobilepay" bis_skin_checked="1">
										<div class="pay-icon icon-mobilepay" bis_skin_checked="1"></div>
									</div>
								</span>
							</label>
						</li>
						<li id="card_payment">
							<label class="form-radio-label" for="field_paymentMethodType_card"> 
								<div class="form-field">BETALINGSKORT
									<input type="radio" name="paymentMethodType" value="Card" id="field_paymentMethodType_card" class="form-radio-input valid" title="Vælg venligst" required="" aria-invalid="false">
									<span class="checkmark"></span>
								</div>
								 <span>
								 	<div class="pay-icons pay-card" bis_skin_checked="1">
								 		<div title="Dankort" class="pay-icon pay-icon-card icon-dankort icon-available-single" bis_skin_checked="1"></div>
								 		<div title="Visa" class="pay-icon pay-icon-card icon-visa icon-available-single" bis_skin_checked="1"></div>
								 		<div title="MasterCard" class="pay-icon pay-icon-card icon-mastercard icon-available-single" bis_skin_checked="1"></div>
								 	</div>
								 </span>
							</label>
						</li>
					</ul>
				</div>

				<!-- Customer details -->
				<div class="customer-details">
					<div class="customer-field" id="fullname">
						<div class="first-name">
							<label>Fornavn</label>
							<input type="text" name="firstname" id="firstname" placeholder="" required>
						</div>
						<div class="last-name">
							<label>Efternavn</label>
							<input type="text" name="lastname" id="lastname" placeholder="" required>
						</div>
					</div>
					<div class="customer-field">
						<div>
							<label>Telefon</label>
							<input type="text" name="phone" id="phone" required>
						</div>
					</div>
					<div class="customer-field">
						<div>
							<label>Email</label>
							<input type="email" name="email" id="email" required>
						</div>
					</div>
					<div class="customer-field">
						<div>
							<input type="checkbox" name="cvrenable" id="cvrenable"> <span>Ønsker du skattefradrag?</span>
						</div>
					</div>
					<div class="customer-field cprfield" style="display: none;">
						<div>
							<label>CPR-NR.</label>
							<input type="text" name="cprnr" id="cprnr">
						</div>
					</div>
				</div>

				<!-- Submit button -->

				<div class="submit-form">
					<input type="hidden" name="donation_amount" id="donation_amount" value="">
					<input type="hidden" name="donation_type" id="donation_type" value="onetime">
					<input type="hidden" name="donation_amount_type" id="donation_amount_type" value="fixed">
					<button type="submit" id="wk-donation-form-submit">Støt nu</button>
				</div>

			</form>
		</div>
	</div>


	<script>
		jQuery(document).ready(function($){

			$('#donation_amount').val($('.onetime-fields input[type="radio"]:checked').val());

			//Onetime payment 
			$('a#onetime').on('click', function(e){
				e.preventDefault();

				//Selection change 
				$('a#recurring').removeClass('selected');
				$(this).removeClass('selected').addClass('selected');

				//show/hide the options ( payment and method )
				$('.recurring-fields').hide();
				$('.onetime-fields').show();
				$('li#card_payment').show();

				//change the payment_type field value 
				$('#donation_type').val('onetime');


				//set the value to default 
				$('#onefirst input[type="radio"]').prop('checked', true );
				$('#donation_amount_type').val('fixed');
				$('#donation_amount').val($('.onetime-fields input[type="radio"]:checked').val());

			});

			//Recurring payment 
			$('a#recurring').on('click', function(e){
				e.preventDefault();

				//Selection change 
				$('a#onetime').removeClass('selected');
				$(this).removeClass('selected').addClass('selected');


				//show/hide the options ( payment and method )
				$('.onetime-fields').hide();
				$('.recurring-fields').show();
				$('li#card_payment').hide();

				//change the payment_type field value 
				$('#donation_type').val('recurring');

				$('#recurringfirst input[type="radio"]').prop('checked', true );
				$('#donation_amount_type').val('fixed');
				$('#donation_amount').val($('.recurring-fields input[type="radio"]:checked').val());


				//Make sure mobilepay is selected as default 
				$('input#field_paymentMethodType_mobilepay').prop('checked', true );

			});


			//One time fields 
			$('.onetime-fields input[type="radio"]').on('change', function(){
				$('#donation_amount').val($(this).val());

				if($(this).val() === 'onetimecustom'){
					$('.custom-amount-container').show();
					$('#donation_amount_type').val('custom');
				}else {
					$('.custom-amount-container').hide();
					$('#donation_amount_type').val('fixed');
				}
			});

			//Recurring fields
			$('.recurring-fields input[type="radio"]').on('change', function(){
				$('#donation_amount').val($(this).val());

				if($(this).val() === 'recurringcustom'){
					$('.custom-amount-container').show();
					$('#donation_amount_type').val('custom');
				}else {
					$('.custom-amount-container').hide();
					$('#donation_amount_type').val('fixed');
				}

			});


			//CVR field 
			$('#cvrenable').on("change", function(){
				if($(this).is(':checked')){
					//console.log('checked');
					$('.cprfield').show();

				}else {
					$('.cprfield').hide();
					$('#cprnr').val('');
				}
			})

			//Ajax call 
			$('.submit-form').on('click','button#wk-donation-form-submit', function(e){
				e.preventDefault();
				let amount_type = $('#donation_amount_type').val();
				let amount = $('input#donation_amount').val();

				if(amount_type == 'custom'){
					amount = $('input#donation_amount_custom').val();
				}
				let type = $('input#donation_type').val();
				let name = $('input#firstname').val() + ' ' + $('input#lastname').val();
				let phone = $('input#phone').val();
				let email = $('input#email').val();
				let cvr = $('input#cprnr').val();

				let payment_method_type = $('input[name="paymentMethodType"]:checked').val();


				console.log(payment_method_type);


				$.post({
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					data: {
						action: 'wk_donation_create_link',
						name: name,
						phone: phone,
						email: email,
						cvr: cvr,
						amount: amount,
						donation_type: type,
						payment_method_type: payment_method_type

					},
					beforeSend: function(){

					},
					success: function(response){
						console.log(response);
						//Redirect to the url 
						result = JSON.parse(response);

						if(result.success == 'success' ){
							redirect_url = result.data.quickpay_url;
							window.location.replace(redirect_url);
						}
					}
				});

			})

		});
	</script>

	<?php 
	return ob_get_clean();
}