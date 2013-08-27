<?php

class User extends CI_Controller
{

  //initalization
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('User_model');
    $this->load->library('form_validation');
    // $this->output->enable_profiler(TRUE);
  } 
  
  public function process_login()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
    $this->form_validation->set_rules('password0', 'Password', 'min_length[6]|required');
    if ($this->form_validation->run() == FALSE) 
    {
      $errors = "<div class='alert-box alert' id='error-box'>" . validation_errors() . "</div>";

      echo json_encode($errors);
    }
    else
    {
      $this->load->library('encrypt');
      
      $data = array();
      $data['email'] = $this->input->post('email');

      $user = $this->User_model->get_user($data);

      if (count($user) > 0) 
      {
        $decrypted_password = $this->encrypt->decode($user->password);

        if ($decrypted_password == $this->input->post('password0')) 
        {
          $this->session->set_userdata('user_session', $user);
          echo json_encode("success");
        }
        else
        {
          $errors = "<div class='alert-box alert' id='error-box'><p>Your login information did not match our reccords. Try again</p></div>";
          echo json_encode($errors);
        }
      }
      else
      {
        $errors = "<div class='alert-box alert' id='error-box'><p>Your login information did not match our reccords. Try again</p></div>";
        echo json_encode($errors);
      }
      

          
    }
  }

  public function process_registration()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('first_name', "First Name", 'alpha|required');
    $this->form_validation->set_rules('last_name', "Last Name", 'alpha|required');
    $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
    $this->form_validation->set_rules('phone', 'Phone', 'is_natural|required');
    // $this->form_validation->set_rules('org', 'Organization', 'required');
    $this->form_validation->set_rules('street1', 'Street 1', 'required');
    $this->form_validation->set_rules('street2', 'Street 2', '');
    $this->form_validation->set_rules('city', 'City', 'required|alpha');
    $this->form_validation->set_rules('state', 'State', 'required|alpha|max_length[3]');
    $this->form_validation->set_rules('zip', 'Zip Code', 'required|numeric');
    $this->form_validation->set_rules('password1', 'Password', 'min_length[6]|required');
    $this->form_validation->set_rules('password2', 'Password', 'matches[password1]|required');

    if ($this->form_validation->run() == FALSE) 
    {
      $errors = "<div class='alert-box alert' id='error-box'>" . validation_errors() . "</div>";
      echo json_encode($errors);
    }
    else
    {
      $this->load->library('encrypt');
      $encrypted_password = $this->encrypt->encode($this->input->post('password1'));
      $data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name'),
        'email' => $this->input->post('email'),
        'phone' => $this->input->post('phone'),
        'street1' => $this->input->post('street1'),
        'street2' => $this->input->post('street2'),
        'city' => $this->input->post('city'),
        'state' => $this->input->post('state'),
        'zip' => $this->input->post('zip'),
        'password' => $encrypted_password
        );

      $user = $this->User_model->register_user($data);
      $success = "<div class='alert-box success' id='success-box'><p>Thank you for submitting your data. You may now log in.</p></div>";
        echo json_encode($success);
    }
  }

  

  public function logout()
  {
    $this->session->sess_destroy();
    header('location: /welcome/index');
  }

  public function delete_user()
  {
    $input = $this->input->post('user_id');
    $delete_comment = $this->User_model->delete_user_comment($input);
    $delete_post = $this->User_model->delete_user_post($input);
    $delete_user = $this->User_model->delete_user($input);

    $deleted = "<div class='alert-box success' id='success-box'><p>User deleted.</p></div>";
    echo json_encode($deleted); 
  }

  public function edit_user()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('first_name', "First Name", 'alpha|required');
    $this->form_validation->set_rules('last_name', "Last Name", 'alpha|required');
    $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
    $this->form_validation->set_rules('phone', 'Phone', 'is_natural|required');
    $this->form_validation->set_rules('org', 'Organization', 'required');
    $this->form_validation->set_rules('street1', 'Street 1', 'required');
    $this->form_validation->set_rules('street2', 'Street 2', 'required');
    $this->form_validation->set_rules('city', 'City', 'required|alpha');
    $this->form_validation->set_rules('state', 'State', 'required|alpha|less_than[2]');
    $this->form_validation->set_rules('zip', 'Zip Code', 'required|numeric');
    $this->form_validation->set_rules('password1', 'Password', 'min_length[6]|required');
    $this->form_validation->set_rules('password2', 'Password', 'matches[password1]|required');

    if ($this->form_validation->run() == FALSE) 
    {
      $errors = "<div class='alert-box alert' id='error-box'>" . validation_errors() . "</div>";
      echo json_encode($errors);
    }
    else
    {
      $this->load->library('encrypt');
      $encrypted_password = $this->encrypt->encode($this->input->post('password1'));
      $user_id = $this->input->post('user_id');
      $data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name'),
        'email' => $this->input->post('email'),
        'password' => $encrypted_password
        );
      $user = $this->User_model->edit_user($data, $user_id);
      $success = "<div class='alert-box success' id='success-box'><p>The account info has been updated.</p></div>";
      echo json_encode($success);
    }

  }
}
