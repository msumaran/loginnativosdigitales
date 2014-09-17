<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_users extends CI_Model
{
	public $_table 		= "users";
	
	public $name, $email, $passwd, $roles_id, $activo, $cuota, $clientes_id, $menus_tipo_id, $sueldo, $seguro, $cargo, $sistema_pensiones_id,
	$fecha_ingreso, $dni, $direccion, $cuspp, $sexo;
	public $activo_ar = array('si'=>'Si','no'=>'No');
	public $sexo_ar = array('Hombre'=>'Hombre','Mujer'=>'Mujer');

	public function __construct()
	{
		parent::__construct();
		$this->cuota = 0;
	}
	

	public function get()
	{
		$this->db->select($this -> _table.'.*, roles.name as roles, areas.name as areas, clientes.name as clientes, menus_tipo.name as menus_tipo,
			sueldos.cargo as cargo, sistema_pensiones.name as sistema_pensiones');
		$this->db->from($this -> _table);
		$this->db->join('roles', 'roles.id = '.$this -> _table.'.roles_id','left');
		$this->db->join('users_areas', 'users_areas.users_id = users.id','left');
		$this->db->join('areas', 'areas.id = users_areas.areas_id','left');
		$this->db->join('clientes', 'clientes.id = '.$this -> _table.'.clientes_id','left');
		$this->db->join('menus_tipo', 'menus_tipo.id = '.$this -> _table.'.menus_tipo_id','left');
		$this->db->join('sueldos', 'sueldos.users_id = '.$this -> _table.'.id','left');
		$this->db->join('sistema_pensiones', 'sistema_pensiones.id = '.$this -> _table.'.sistema_pensiones_id','left');

		$this->db->group_by($this -> _table.'.id');
		$this->db->order_by($this -> _table.".name", "asc"); 
		$query = $this->db->get();
		return $query -> result();
	}
	public function get_randon()
	{
		$this->db->select($this -> _table.'.*');
		$this->db->from($this -> _table);
		$this->db->where('tipo','user');
		$this->db->where('activo','si');
		/*
		if (ENVIRONMENT ==  'development' ){

			$this->db->where('id',1 );

		}		
		*/

		$this->db->order_by('RAND()');
		$query = $this->db->get();
		return $query->row();
	}
	public function get_or_where( $where=array(), $todos=true )
	{
		$this->db->select($this -> _table.'.*, roles.name as roles, areas.name as areas, clientes.name as clientes, menus_tipo.name as menus_tipo,
			sueldos.cargo as cargo, sistema_pensiones.name as sistema_pensiones');
		$this->db->from($this -> _table);
		$this->db->join('roles', 'roles.id = '.$this -> _table.'.roles_id','left');
		$this->db->join('users_areas', 'users_areas.users_id = users.id','left');
		$this->db->join('areas', 'areas.id = users_areas.areas_id','left');
		$this->db->join('clientes', 'clientes.id = '.$this -> _table.'.clientes_id','left');
		$this->db->join('menus_tipo', 'menus_tipo.id = '.$this -> _table.'.menus_tipo_id','left');
		$this->db->join('sueldos', 'sueldos.users_id = '.$this -> _table.'.id','left');
		$this->db->join('sistema_pensiones', 'sistema_pensiones.id = '.$this -> _table.'.sistema_pensiones_id','left');
		foreach( $where as $row=>$val){
			
			if (strpos($row,'-')) {
				$row = str_replace('-', '.', $row);
				$this->db->or_where($row, $val);
			}else{
				$this->db->or_where($this -> _table.'.'.$row, $val);
			}
				
		}
		$this->db->group_by($this -> _table.'.id');
		$query = $this->db->get();
		
		if( $todos ){
			return $query->result() ;
		}else{
			return $query->row();
		}
	}
	public function get_where( $where=array(), $todos=true , $flag=true)
	{
		$this->db->select($this -> _table.'.*, roles.name as roles, areas.name as areas, clientes.name as clientes, menus_tipo.name as menus_tipo,
			sueldos.cargo as cargo, sistema_pensiones.name as sistema_pensiones');
		$this->db->from($this -> _table);
		$this->db->join('roles', 'roles.id = '.$this -> _table.'.roles_id','left');
		$this->db->join('users_areas', 'users_areas.users_id = users.id','left');
		$this->db->join('areas', 'areas.id = users_areas.areas_id','left');
		$this->db->join('clientes', 'clientes.id = '.$this -> _table.'.clientes_id','left');
		$this->db->join('menus_tipo', 'menus_tipo.id = '.$this -> _table.'.menus_tipo_id','left');
		$this->db->join('sueldos', 'sueldos.users_id = '.$this -> _table.'.id','left');
		$this->db->join('sistema_pensiones', 'sistema_pensiones.id = '.$this -> _table.'.sistema_pensiones_id','left');
		foreach( $where as $row=>$val){
			
			if (strpos($row,'-')) {
				$row = str_replace('-', '.', $row);
				$this->db->where($row, $val,$flag);
			}else{
				$this->db->where($this -> _table.'.'.$row, $val);
			}
				
		}
		$this->db->order_by($this -> _table.'.name', 'asc');
		$this->db->group_by($this -> _table.'.id');
		$query = $this->db->get();
		if( $todos ){
			return $query->result() ;
		}else{
			return $query->row();
		}
	}
	public function get_id($id){
		if( empty ($id )) return false;
		$this->db->select($this -> _table.'.*, roles.name as roles,sueldos.cargo as cargo');
		$this->db->from($this -> _table);
		$this->db->join('roles', 'roles.id = '.$this -> _table.'.roles_id','left');
		$this->db->join('sueldos', 'sueldos.users_id = '.$this -> _table.'.id','left');
		$this->db->where($this -> _table.'.id', $id);
		$query = $this->db->get();
		return $query->row();

	}
	public function get_id_campo($id,$campo){
		$this->db->select($campo);
		$this->db->from($this -> _table);
		$this->db->where($this -> _table.'.id', $id);
		$query = $this->db->get();
		$row = $query->row();
		return $row->$campo;

	}
	public function update($data , $id){
		$this->db->where('id',$id);
		$data['updated_at'] =  date('Y-m-d H:i:s'); 
		return $this -> db -> update( $this->_table , $data);	
	}
	public function del($id){
		return $this->db->delete( $this->_table, array('id' => $id)); 

	}
	public function add($data){
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}
	public function contar($where=array(), $search=array()){
		
		if( !empty ($search ) ){
			foreach( $search as $row=>$val){
				$this->db->like($row, $val);
			}
		}
		if( !empty ($where ) ){
			foreach( $where as $row=>$val){
				$this->db->where($row, $val);
			}
		}
		$this->db->from($this->_table);
		return $this->db->count_all_results();	
	}
	public function combo($name,$id='id',$multiple=false){
		$data = $this->get();
		$options = array();
		if(!$multiple){
			$options[] = 'Seleccionar...';
		}
		
		foreach($data as $row){
			 $options[$row->$id] = $row->$name;
		}
		return $options;
	}
	public function combo_where($where,$name='name',$id='id',$multiple=false){
		$data = $this->get_where($where,true,true);
		$options = array();
		if(!$multiple){
			$options[] = 'Seleccionar...';
		}
		foreach($data as $row){
			 $options[$row->$id] = $row->$name;
		}
		return $options;
	}
	public function buscar($string){
		$this->db->from($this->_table);
		$this->db->like('name', $string); 
		//$this->db->or_like('content', $string); 
		$query = $this->db->get();
		return $query -> result();
	}
	public function users_activos(){
		return $this->get_where( array('activo'=>'si','logeado !='=>'NULL','tipo'=>'user') );
	}
	public function users_tipo($roles_id=''){
		return $this->get_where( array('roles_id'=>$roles_id ) );
	}

}
/* End of file M_slider.php */
/* Location: ./application/models/M_slider.php */