<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  var $orgOptions;

  public function __construct()
  {
    parent:: __construct();
    $this->load->model('User_model');
    $this->check_session();
    $this->load->model('Org_model');
    $this->orgOptions = $this->orgDisplay();
  }

  private function check_session()
  {
    if ($this->session->userdata('user_session') == '') 
    {
      header('location: /main/index');
    }
  }

  public function index()
  { 
    $data = array(
      'title' => 'Willow Tree Address Book - Dashboard',
      'options' => $this->orgOptions,
      );

    $this->load->view('head', $data);
    $this->load->view('navbar');
    $this->load->view('dashboard', $data);
    $this->load->view('loginRegisterModals', $data);
    $this->load->view('addEditModals');
    $this->load->view('bottom', $data);
  }

  public function orgDisplay()
  {
    $orgData = $this->Org_model->get_org();
    if ($orgData == null) 
    {
      $html = '';
      return $html;
    }
    else
    {
      $html = null;
      foreach ($orgData as $key) 
      {
        $html .= "
          <option value=" . $key->id . " >" . $key->org_name . "</option>
        ";
      }
      return $html;
    }
  }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */