<?php 
//use GuzzleHttp\Client;
use QuickPay\QuickPay;

function wk_donation_quickpay_payment( $data ){
	global $api_key;

	try {
		$client = new QuickPay(":{$api_key}");

		$invoice_address = array(

		);

		// $invoice_address['name'] = 'Test name';
		// $invoice_address['phone_number'] = '+4590890980';
		// $invoice_address['email'] = 'test@test.com';
		// $invoice_address['vat_no'] = 'CVR 222'; //this is CVR

		$payment = $client->request->post('/payments', [
			'order_id' => uniqid("bbf"),
			'currency' => 'DKK',
			'invoice_address[name]' => $data['name'],
			'invoice_address[phone_number]' => $data['phone'],
			'invoice_address[email]' => $data['email'],
			'invoice_address[vat_no]' => $data['cvr']
		]);

		$status = $payment->httpStatus();

		if( $status === 201 ){
			$paymentObject = $payment->asObject();

			$endpoint = sprintf("/payments/%s/link", $paymentObject->id);

			$request_data = array(
				'amount' => $data['amount'] * 100 //amount in cents
			);

			//Enable mobile pay 
			if($data['payment_method_type'] == 'MobilePay'){
				
				$request_data['invoice_address_selection'] = true;
			}

			//Issue a put request to create payment link
	        $link = $client->request->put($endpoint, $request_data);

	        if ($link->httpStatus() === 200) {
	            //Get payment link url

	            return array(
	            	'status' => 200,
	            	'quickpay_url' => $link->asObject()->url
	            );
	        }
		}

	} catch ( Exception $e ){
		//
		return array(
			'status' => 504,
			'message' => $e->getMessage()
		);
		//echo $e->getMessage();
	}
}


add_action('wp_ajax_wk_donation_create_link','wk_donation_create_link');
add_action('wp_ajax_nopriv_wk_donation_create_link','wk_donation_create_link');


function wk_donation_create_link(){

	$data = array();
	
	$data['amount'] = !empty($_POST['amount']) ? $_POST['amount'] : 125; //default set to minimum
	$data['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
	$data['email'] = !empty($_POST['email']) ? $_POST['email'] : '';
	$data['phone'] = !empty($_POST['phone']) ? $_POST['phone'] : '';

	$data['donation_type'] = !empty($_POST['donation_type']) ? $_POST['donation_type'] : 'onetime';

	$data['payment_method_type'] = !empty($_POST['payment_method_type']) ? $_POST['payment_method_type'] : 'Card';


	$response = wk_donation_quickpay_payment($data);


	if($response['status'] == 200 ){
		wp_send_json_success($response);
	}else {
		wp_send_json_error($response);
	}

	wp_die();
}


