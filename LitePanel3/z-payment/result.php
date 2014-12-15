﻿<?php
/*
* @LitePanel
* @Version: 1.0.1
* @Date: 29.12.2012
* @Developed by QuickDevel
*/
class resultController extends Controller {
	public function index() {
		$this->load->model('users');
		$this->load->model('invoices');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$ammount = $this->request->post['LMI_PAYMENT_AMOUNT'];
				$invid = $this->request->post['LMI_PAYMENT_NO'];
				$signature = $this->request->post['ZP_SIGN'];
				
				$invoice = $this->invoicesModel->getInvoiceById($invid);
				$userid = $invoice['user_id'];
				
				$this->usersModel->upUserBalance($userid, $ammount);
				$this->invoicesModel->updateInvoice($invid, array('invoice_status' => 1));
				return "OK$invid\n";
			} else {
				return "Error: $errorPOST";
			}
		} else {
			return "Error: Invalid request!";
		}
	}
	
	private function validatePOST() {
		$result = null;
		
		$ammount = $this->request->post['LMI_PAYMENT_AMOUNT'];
		$invid = $this->request->post['LMI_PAYMENT_NO'];
		$signature = $this->request->post['ZP_SIGN'];
		
		$password2 = $this->config->rk_password2;
		
		if(!$this->invoicesModel->getTotalInvoices(array('invoice_id' => (int)$invid))) {
			$result = "Invalid invoice!";
		}
		elseif($signature != strtoupper(md5("$ammount:$invid:$password2"))) {
			$result = "Invalid signature!";
		}
		return $result;
	}
}
?>
