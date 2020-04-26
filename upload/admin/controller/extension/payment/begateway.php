<?php
class ControllerExtensionPaymentBeGateway extends Controller {
  private $error = array();

  public function index() {
    $this->load->language('extension/payment/begateway');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('payment_begateway', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
    }

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');

    $data['text_live_mode'] = $this->language->get('text_live_mode');
    $data['text_test_mode'] = $this->language->get('text_test_mode');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');

    $data['entry_email'] = $this->language->get('entry_email');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_order_status_completed_text'] = $this->language->get('entry_order_status_completed_text');
    $data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
    $data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
    $data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
    $data['entry_order_status_failed_text'] = $this->language->get('entry_order_status_failed_text');
    $data['entry_order_status_processing'] = $this->language->get('entry_order_status_processing');
    $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['entry_companyid'] = $this->language->get('entry_companyid');
    $data['entry_companyid_help'] = $this->language->get('entry_companyid_help');
    $data['entry_encyptionkey'] = $this->language->get('entry_encyptionkey');
    $data['entry_encyptionkey_help'] = $this->language->get('entry_encyptionkey_help');
    $data['entry_domain_payment_page'] = $this->language->get('entry_domain_payment_page');
    $data['entry_domain_payment_page_help'] = $this->language->get('entry_domain_payment_page_help');
    $data['entry_payment_type'] = $this->language->get('entry_payment_type');
    $data['entry_payment_type_card'] = $this->language->get('entry_payment_type_card');
    $data['entry_payment_type_halva'] = $this->language->get('entry_payment_type_halva');
    $data['entry_payment_type_erip'] = $this->language->get('entry_payment_type_erip');
    $data['entry_test_mode'] = $this->language->get('entry_test_mode');
    $data['entry_test_mode_help'] = $this->language->get('entry_test_mode_help');
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['tab_general'] = $this->language->get('tab_general');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['companyid'])) {
      $data['error_companyid'] = $this->error['companyid'];
    } else {
      $data['error_companyid'] = '';
    }

    if (isset($this->error['encyptionkey'])) {
      $data['error_encyptionkey'] = $this->error['encyptionkey'];
    } else {
      $data['error_encyptionkey'] = '';
    }

    if (isset($this->error['domain_payment_page'])) {
      $data['error_domain_payment_page'] = $this->error['domain_payment_page'];
    } else {
      $data['error_domain_payment_page'] = '';
    }

    if (isset($this->error['payment_type'])) {
      $data['error_payment_type'] = $this->error['payment_type'];
    } else {
      $data['error_payment_type'] = '';
    }

    if (isset($this->error['erip_service_no'])) {
      $data['error_erip_service_no'] = $this->error['erip_service_no'];
    } else {
      $data['error_erip_service_no'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      =>  $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/payment/begateway', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/payment/begateway', 'user_token=' . $this->session->data['user_token'], true);

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=payment', true);


    if (isset($this->request->post['payment_begateway_companyid'])) {
      $data['payment_begateway_companyid'] = $this->request->post['payment_begateway_companyid'];
    } else {
      $data['payment_begateway_companyid'] = $this->config->get('payment_begateway_companyid');
    }

    if (isset($this->request->post['payment_begateway_encyptionkey'])) {
      $data['payment_begateway_encyptionkey'] = $this->request->post['payment_begateway_encyptionkey'];
    } else {
      $data['payment_begateway_encyptionkey'] = $this->config->get('payment_begateway_encyptionkey');
    }

    if (isset($this->request->post['payment_begateway_domain_payment_page'])) {
      $data['payment_begateway_domain_payment_page'] = $this->request->post['payment_begateway_domain_payment_page'];
    } else {
      $data['payment_begateway_domain_payment_page'] = $this->config->get('payment_begateway_domain_payment_page');
    }

		if (isset($this->request->post['payment_begateway_payment_type'])) {
			$data['payment_begateway_payment_type'] = $this->request->post['payment_begateway_payment_type'];
		} else {
			$data['payment_begateway_payment_type'] = $this->config->get('payment_begateway_payment_type');
		}

		if (isset($this->request->post['payment_begateway_erip_service_no'])) {
			$data['payment_begateway_erip_service_no'] = $this->request->post['payment_begateway_erip_service_no'];
		} else {
			$data['payment_begateway_erip_service_no'] = $this->config->get('payment_begateway_erip_service_no');
		}

    if (isset($this->request->post['payment_begateway_completed_status_id'])) {
      $data['payment_begateway_completed_status_id'] = $this->request->post['payment_begateway_completed_status_id'];
    } else {
      $data['payment_begateway_completed_status_id'] = $this->config->get('payment_begateway_completed_status_id');
    }

    if (isset($this->request->post['payment_begateway_failed_status_id'])) {
      $data['payment_begateway_failed_status_id'] = $this->request->post['payment_begateway_failed_status_id'];
    } else {
      $data['payment_begateway_failed_status_id'] = $this->config->get('payment_begateway_failed_status_id');
    }

    $this->load->model('localisation/order_status');

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    if (isset($this->request->post['payment_begateway_status'])) {
      $data['payment_begateway_status'] = $this->request->post['payment_begateway_status'];
    } else {
      $data['payment_begateway_status'] = $this->config->get('payment_begateway_status');
    }

    if (isset($this->request->post['payment_begateway_test_mode'])) {
      $data['payment_begateway_test_mode'] = $this->request->post['payment_begateway_test_mode'];
    } else {
      $data['payment_begateway_test_mode'] = $this->config->get('payment_begateway_test_mode');
    }

    if (isset($this->request->post['payment_begateway_geo_zone_id'])) {
      $data['payment_begateway_geo_zone_id'] = $this->request->post['payment_begateway_geo_zone_id'];
    } else {
      $data['payment_begateway_geo_zone_id'] = $this->config->get('payment_begateway_geo_zone_id');
    }

    $this->load->model('localisation/geo_zone');

    $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    if (isset($this->request->post['payment_begateway_sort_order'])) {
      $data['payment_begateway_sort_order'] = $this->request->post['payment_begateway_sort_order'];
    } else {
      $data['payment_begateway_sort_order'] = $this->config->get('payment_begateway_sort_order');
    }

    $data['user_token'] = $this->session->data['user_token'];

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/payment/begateway', $data));
  }

  private function validate() {
    if (!$this->user->hasPermission('modify', 'extension/payment/begateway')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->request->post['payment_begateway_companyid']) {
      $this->error['companyid'] = $this->language->get('error_companyid');
    }

    if (!$this->request->post['payment_begateway_encyptionkey']) {
      $this->error['encyptionkey'] = $this->language->get('error_encyptionkey');
    }

    if (!$this->request->post['payment_begateway_domain_payment_page']) {
      $this->error['domain_payment_page'] = $this->language->get('error_domain_payment_page');
    }

		if (!isset($this->request->post['payment_begateway_payment_type'])) {
			$this->error['payment_type'] = $this->language->get('error_payment_type');
		} else {
      $sum = 0;
      foreach($this->request->post['payment_begateway_payment_type'] as $k => $v) {
        $sum = $sum + $this->request->post['payment_begateway_payment_type'][$k];
      }
      if ($sum == 0)
  			$this->error['payment_type'] = $this->language->get('error_payment_type');
    }

    if (isset($this->request->post['payment_begateway_payment_type']) &&
        $this->request->post['payment_begateway_payment_type']['erip'] == 1 &&
        empty($this->request->post['payment_begateway_erip_service_no'])) {
      $this->error['erip_service_no'] = $this->language->get('error_erip_service_no');
    }

    return !$this->error;
  }
}
