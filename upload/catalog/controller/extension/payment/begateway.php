<?php
class ControllerExtensionPaymentBeGateway extends Controller {
  const API_VERSION = 2;

  public function index() {
    $this->language->load('extension/payment/begateway');
    $this->load->model('checkout/order');

    $data['action'] = 'https://' . $this->config->get('payment_begateway_domain_payment_page') . '/v' . self::API_VERSION . '/checkout';
    $data['button_confirm'] = $this->language->get('button_confirm');
    $data['token'] = $this->generateToken();
    $data['token_error'] = $this->language->get('token_error');
    $data['order_id'] = $this->session->data['order_id'];

    return $this->load->view('extension/payment/begateway', $data);
  }

  public function generateToken(){

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $orderAmount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
    $orderAmount = (float)$orderAmount * pow(10,(int)$this->currency->getDecimalPlace($order_info['currency_code']));
    $orderAmount = intval(strval($orderAmount));

    $customer_array =  array (
      'address' => strlen($order_info['payment_address_1']) > 0 ? $order_info['payment_address_1'] : null,
      'first_name' => strlen($order_info['payment_firstname']) > 0 ? $order_info['payment_firstname'] : null,
      'last_name' => strlen($order_info['payment_lastname']) > 0 ? $order_info['payment_lastname'] : null,
      'country' => strlen($order_info['payment_iso_code_2']) > 0 ? $order_info['payment_iso_code_2'] : null,
      'city'=> strlen($order_info['payment_city']) > 0 ? $order_info['payment_city'] : null,
      'phone' => strlen($order_info['telephone']) > 0 ? $order_info['telephone'] : null,
      'email'=> strlen($order_info['email']) > 0 ? $order_info['email'] : null,
      'zip' => strlen($order_info['payment_postcode']) > 0 ? $order_info['payment_postcode'] : null,
      'ip' => $this->request->server['REMOTE_ADDR']
    );

    if (in_array($order_info['payment_iso_code_2'], array('US','CA'))) {
      $customer_array['state'] = $order_info['payment_zone_code'];
    }

    $order_array = array ( 'currency'=> $order_info['currency_code'],
      'amount' => $orderAmount,
      'description' => $this->language->get('text_order'). ' ' .$order_info['order_id'],
      'tracking_id' => $order_info['order_id']);

    $callback_url = $this->url->link('extension/payment/begateway/callback1', '', 'SSL');
    $callback_url = str_replace('carts.local', 'webhook.begateway.com:8443', $callback_url);

    $setting_array = array ( 'success_url'=>$this->url->link('extension/payment/begateway/callback', '', 'SSL'),
      'decline_url'=> $this->url->link('checkout/checkout', '', 'SSL'),
      'cancel_url'=> $this->url->link('checkout/checkout', '', 'SSL'),
      'fail_url'=>$this->url->link('checkout/checkout', '', 'SSL'),
      'language' => $this->_language($this->session->data['language']),
      'notification_url'=> $callback_url);

    $transaction_type='payment';

    $payment_methods_array = array(
      'types' => array(
      )
    );

    $pm_type = $this->config->get('payment_begateway_payment_type');
    if ($pm_type['card'] == 1) {
      $payment_methods_array['types'][] = 'credit_card';
    }

    if ($pm_type['halva'] == 1) {
      $payment_methods_array['types'][] = 'halva';
    }

    if ($pm_type['erip'] == 1) {
      $payment_methods_array['types'][] = 'erip';
      $payment_methods_array['erip'] = array(
        'order_id' => $order_info['order_id'],
        'account_number' => $order_info['order_id'],
        'service_no' => $this->config->get('payment_begateway_erip_service_no'),
        'service_info' => array($order_array['description'])
      );
    }

    $checkout_array = array(
      'version' => '2.1',
      'transaction_type' => $transaction_type,
      'test' => intval($this->config->get('payment_begateway_test_mode')) == 1,
      'settings' =>$setting_array,
      'order' => $order_array,
      'customer' => $customer_array,
      'payment_method' => $payment_methods_array
      );

    $token_json =  array('checkout' =>$checkout_array );

    $this->load->model('checkout/order');

    $post_string = json_encode($token_json);

    $username=$this->config->get('payment_begateway_companyid');
    $password=$this->config->get('payment_begateway_encyptionkey');
    $ctp_url = 'https://' . $this->config->get('payment_begateway_domain_payment_page') . '/ctp/api/checkouts';

    $curl = curl_init($ctp_url);
    curl_setopt($curl, CURLOPT_PORT, 443);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: '.strlen($post_string))) ;
    curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);

    $response = curl_exec($curl);
    curl_close($curl);

    if (!$response) {
      $this->log->write('Payment token request failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
      return false;
    }

    $token = json_decode($response,true);

    if ($token == NULL) {
      $this->log->write("Payment token response parse error: $response");
      return false;
    }

    if (isset($token['errors'])) {
      $this->log->write("Payment token request validation errors: $response");
      return false;
    }

    if (isset($token['response']) && isset($token['response']['message'])) {
      $this->log->write("Payment token request error: $response");
      return false;
    }

    if (isset($token['checkout']) && isset($token['checkout']['token'])) {
      return $token['checkout']['token'];
    } else {
      $this->log->write("No payment token in response: $response");
      return false;
    }
  }

  public function callback() {

    if (isset($this->session->data['order_id'])) {
      $order_id = $this->session->data['order_id'];
    } else {
      $order_id = 0;
    }

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($order_id);

    $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
  }

  public function callback1() {

    $postData =  (string)file_get_contents("php://input");

    $post_array = json_decode($postData, true);

    $order_id = $post_array['transaction']['tracking_id'];

    $order_id = $post_array['transaction']['tracking_id'];
    $status = $post_array['transaction']['status'];

    $transaction_id = $post_array['transaction']['uid'];
    $transaction_message = $post_array['transaction']['message'];
    $three_d = $post_array['transaction']['three_d_secure_verification']['pa_status'];
    if (isset($three_d)) {
      $three_d = '3-D Secure: ' . $three_d . '.';
    } else {
      $three_d = '';
    }

    $this->log->write("Webhook received: $postData");

    $this->load->model('checkout/order');

    $order_info = $this->model_checkout_order->getOrder($order_id);

    if ($order_info) {
      $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));

      if(isset($status) && $status == 'successful'){
        $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_begateway_completed_status_id'), "UID: $transaction_id. $three_d Processor message: $transaction_message", true);
      }
      if(isset($status) && ($status == 'failed')){
        $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_begateway_failed_status_id'), "UID: $transaction_id. Fail reason: $transaction_message", true);
      }
    }
  }

  private function _language($lang_id) {
    $lang = substr($lang_id, 0, 2);
    $lang = strtolower($lang);
    return $lang;
  }
}
