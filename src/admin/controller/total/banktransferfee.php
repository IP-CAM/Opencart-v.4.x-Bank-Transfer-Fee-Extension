<?php
namespace Opencart\Admin\Controller\Extension\BankTransferFee\Total;
/**
 * Class BankTransferFee
 *
 * @package Opencart\Admin\Controller\Extension\BankTransferFee\Total
 */
class BankTransferFee extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/banktransferfee/total/banktransferfee');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/banktransferfee/total/banktransferfee', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/banktransferfee/total/banktransferfee.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total');

		$data['total_banktransferfee_fee'] = $this->config->get('total_banktransferfee_fee');
		$data['total_banktransferfee_tax_class_id'] = $this->config->get('total_banktransferfee_tax_class_id');

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$data['total_banktransferfee_status'] = $this->config->get('total_banktransferfee_status');
		$data['total_banktransferfee_sort_order'] = $this->config->get('total_banktransferfee_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/banktransferfee/total/banktransferfee', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/banktransferfee/total/banktransferfee');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/banktransferfee/total/banktransferfee')) {
			$json['error'] = $this->language->get('error_permission');
		}

        if (isset($this->request->post['total_banktransferfee_status']) && $this->request->post['total_banktransferfee_status'] === '1') {
            if ((float)$this->request->post['total_banktransferfee_fee'] <= 0) {
                $json['error']['fee'] = $this->language->get('error_total_banktransferfee_fee');
            }
        }

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('total_banktransferfee', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}