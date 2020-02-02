<?php

	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Login extends MX_Controller {

		public function __construct()
        {
            parent::__construct();
            //Load Dependencies
			$this->load->model('m_login'); 
			$this->load->library('Form_validation');
        }
    
	
		public function index()
		{
			$this->load->view('login');
		}

		public function cek_login()
		{
			$uname = $this->input->post('username');
			$pwd = strtolower($this->input->post('password'));
			$pass = md5($pwd);

			// form validation
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
	
			// set message error
			$this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
			$this->form_validation->set_message('min_length', "\"%s\" minimal 6 karakter");
	
			if ($this->form_validation->run() == FALSE)
			{
				$this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
				//die(validation_errors());
				echo json_encode(array('status' => 301, 'message' => validation_errors()));
			}else{

				$where = array(
					'user.username' => $uname,
					'user.password' => $pass,
					'user.is_active' => 'Y'
				);

				$cek = $this->m_login->ceklogin("user",$where)->num_rows();
				$fetch = $this->m_login->ceklogin("user",$where);

				if($cek > 0){
					foreach ($fetch->result() as $val) {
						$data_session = array(
							'username' => $val->username,
							'nama' => $val->nama_lengkap,
							'leveluser' => $val->level_id,
							'unit' => $val->unit_id,
							'loc' => (string)$val->loc_id,
							'logged' => TRUE
							);
					}	 
					$this->session->set_userdata($data_session);
					//print_r($data_session);die;
							
					echo json_encode(array('status' => 200, 'message' => 'Login berhasil'));
		
				}else{
					echo json_encode(array('status' => 301, 'message' => 'Username dan Password tidak sesuai'));
				}
			}

		}

		public function logout()
		{
			$sess_data = array (
				'username' => NULL,
				'nama' => NULL,
				'leveluser' => NULL,
				'unit' => NULL,
				'loc' => NULL,
				'logged' => FALSE
			);
			$this->session->unset_userdata($sess_data);
			$this->session->sess_destroy();
			redirect('login');	
		}

		
	}
	
	/* End of file Login.php */
	

?>