<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
	public $vista = array();

	// Cambiar por donde quieres que este el administrador
	public $url_admin = '/master'
	
	 public function __construct()
    {
		parent::__construct();
		$this->vista['base'] = base_url();
	}
	/*****

		Esta es la opcion para olvido contraseña
	****/
	public function oldpasswd(){
	
		if( $this->input->post() ){
			$this->m_login->recovery_passwd( $this->input->post('correo') );
			$this->vista['msj'] = '';
		}

		$this->load->view("master/oldpasswd",$this->vista);
	}
	/*****

		Esta es la opcion para olvido contraseña
	****/
	public function changepassword(){

		$this->load->model('m_users');

		

		$user = $this->m_users->get_where( array( 'id'=>$this->input->get('i') ) ,false);
		if( $this->input->get('p') != $user->passwd ){
			show_404();

		}
		if( $this->input->post() ){
			$data = array();
			$data['passwd'] = md5( $this->input->post('password') );


			$this->m_users->update($data, $this->input->get('i') );
			redirect($this->url_admin, 'refresh');
		}

		$this->load->view("master/changepassword",$this->vista);
	}
	/*****

		Esta es la función para hacer el login
	****/
	public function login(){
		$this->vista['desactivar'] = array();
		$this->vista['desactivar']['jquery_ui'] = 'si';
		$this->vista['desactivar']['css'] = 'si';
		$this->vista['desactivar']['header'] = 'si';
		$this->vista['desactivar']['footer'] = 'si';
		$this->vista['css'] = array("login.css");
		if( $this->input->post('username')){
			if( !$this->m_login->Authenticate( $this->input->post('username'),$this->input->post('password') ) ){
				$this->vista['error'] = 'El correo o contraseña no son correctos!';	
			}else{
			
				
				
				if( $this->input->get('ref') ){
					redirect($this->url_admin);
				}else{
					redirect($this->url_admin);
				}
					
			}
		}
		$this->load->view("/master/login",$this->vista);
	}
	/*****

		Esta es la función para salir
	****/
	public function salir(){
		$this->m_login->Logout();	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */