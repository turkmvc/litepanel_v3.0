<?php
/*
* @LitePanel
* @Developed by QuickDevel
*/
class loginfooterController extends Controller {
	public function index() {
		$this->data['title'] = $this->config->title;
		$this->data['description'] = $this->config->description;
		
		return $this->load->view('common/loginfooter', $this->data);
	}
}
?>
