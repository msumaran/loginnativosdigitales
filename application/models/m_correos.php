<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_correos extends CI_Model
{
	public $_table 		= "correos";
	
	public $name, $subject, $mensaje;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get()
	{
		$this->db->select($this -> _table.'.*');
		$this->db->from($this -> _table);
		$query = $this->db->get();
		return $query -> result();
	}
	
	public function get_where( $where=array(), $todos=true )
	{
		$this->db->select($this -> _table.'.*');
		$this->db->from($this -> _table);
		foreach( $where as $row=>$val){
				$this->db->where($this -> _table.'.'.$row, $val);
		}
		$query = $this->db->get();
		if( $todos ){
			return $query->result() ;
		}else{
			return $query->row();
		}
	}
	public function get_id($id){
		if( empty ($id )) return false;
		$this->db->select('*');
		$this->db->from($this -> _table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row();

	}
	public function get_id_campo($id,$campo,$default="Top"){
		if( empty ($id )) return $default;
		$this->db->select($campo);
		$this->db->from($this -> _table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		$row = $query->row();
		return $row->$campo;

	}
	public function update($data , $id){
		$this->db->where('id',$id);
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
	public function combo($name,$id='id'){
		$data = $this->get();
		$options = array();
		$options[''] = 'Seleccionar...';
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

}
/* End of file M_correos.php */
/* Location: ./application/models/M_correos.php */