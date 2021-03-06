<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller 
{

  var $orgOptions;

  public function __construct()
  {
    parent:: __construct();
    $this->load->model('Org_model');
    $this->orgOptions = $this->orgDisplay();

  }

  public function index()
  {

    $data = array(
      'title' => 'Willow Tree Address Book',
      'options' => $this->orgOptions
      );

    $this->load->view('head', $data);
    $this->load->view('index', $data);
    $this->load->view('loginRegisterModals');
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

  public function logout()
  {
    $this->session->sess_destroy();
    $this->index();
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */