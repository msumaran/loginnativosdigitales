<?php  
class m_ui extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		

	}
	
	public function send_email($users_id, $name,$data=array(),$roles=true){

		$this->load->model('m_users');
		$this->load->model('m_correos');
		$this->load->library('parser');
		$user = $this->m_users->get_id( $users_id );
		
		$user->hora = date("h:i a");
		// Juntamos las variables user con los datos
		if(!empty($data)){
			foreach($data as $row=>$val){
				$user->$row = $val;
			}
		}
		$ret = $this->m_correos->get_where( array( 'name'=> $name ), false );
		$data = array();
		$data['base'] = base_url();
		$data['mensaje'] =  $this->parser->parse_string($ret->mensaje, $user, TRUE);
		$mensaje = $this->load->view('/correos/mensaje',$data,true);
		
		$this->email($user->name, $user->email, $cc, $ret->subject, $mensaje);
	}
	function email($name,$to,$cc,$subject, $mensaje){
		$this->load->library('email');

	
		$config['protocol'] = 'mail';
		//$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'utf8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';

		$this->email->initialize($config);


		$this->load->library('email', $config); 
		$config['charset'] = 'utf8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html'; 
		$this->email->initialize($config);
		
		$this->email->to($to, $name);
		
		$this->email->from('erp@nativosdigitales.pe','ERP Nativos Digitales');
		if( !empty($cc)){
			$this->email->cc($cc);
		}
		$this->email->subject($subject);
		$this->email->message($mensaje);	
		if( ENVIRONMENT == 'development'){
			echo $subject.'<br />';
			echo $mensaje;

			//echo $this->email->print_debugger();
			
		}else{
			$this->email->send();
			//echo $this->email->print_debugger();
		}

	}
	
}
