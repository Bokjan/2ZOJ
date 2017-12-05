<?php
/**
 * EPDB - Enterprise Database Class
 * Copyright (c) 2013 - 2014 Bokjan Chan
 * For more information, visit bokjan.com
 * @author Bokjan Chan
 * @copyright Copyright (c) 2013 - 2014, Bokjan Chan
 * @link https://bokjan.com
 * @version 0.2.1
 */
class epdb{
	private $dbhost='localhost'; //YOUR DATABASE HOST, LOCALHOST FOR UNIX SOCKET
	private $dbname='oj'; //YOUR DATABASE NAME
	private $dbuser='root'; //A USER ACCESS THE DB YOU INPUTED
	private $dbpw='root';   //THE ABOVE USER'S PASSWORD
	private $conn;	    //A PRIVATE PROPERTY, FOR CLASS TO SAVE A DB LINK
	public  $prefix='oj_'; //LEAVE BLANK IF YOU DON'T NEED PREFIX
	public  $lastSql;   //A PUBLIC PROPERTY, DEBUGGING
	public  $table;		//CURRENT TABLE 
	
	function __construct($table){
		$this->table=$this->prefix.$table;
		$this->conn=new mysqli($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname);
		$this->execute('SET NAMES UTF8');
	}

	function __destruct(){
		$this->conn->close();
	}

	public function table($table){
		$this->table=$this->prefix.$table;
	}

	public function execute($query){
		return $this->conn->query($query);
	}

	public function data($data){
		$this->data=$data;
		$key=array();
		$value=array();
		foreach ($data as $k => $v) {
			$key[]=$k;
			$value[]=$v;
		}
		$this->key=$key;
		$this->value=$value;
		return $this;
	}

	public function where($data){
		$this->data=$data;
		if (is_array($data)) {
			$dc=count($data);
			$where='';
			$i=1;
			foreach ($data as $k => $v) {
				$i++;
				$where.='`'.$k.'`=\''.$v.'\'';
				if($i<$dc){
					$where.=' AND ';
				}
				$this->where=$where;
			}
		} else {
			$this->where=$data;
		}
		return $this;
	}

	public function order($order,$type='ASC'){
		$this->order=$order.'|'.$type;
		return $this;
	}

	public function limit($limit='1'){
		$this->limit=$limit;
		return $this;
	}

	public function select(){
		$query='SELECT * FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->lastSql=$query;
		$res=$this->execute($query);
		if($res!=false){
			return $res->fetch_all(MYSQLI_ASSOC);
		}
		else{
			return NULL;
		}
	}

	public function findArr(){
		$query='SELECT * FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		$query.=' LIMIT 1';
		unset($this->limit);
		$this->lastSql=$query;
		$res=$this->execute($query);
		if($res!=false){
			return $res->fetch_assoc();
		}
		else{
			return NULL;
		}
	}

	public function find(){
		$query='SELECT * FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		$query.=' LIMIT 1';
		unset($this->limit);
		$this->lastSql=$query;
		$res=$this->execute($query);
		if($res!=false){
			return $res->fetch_object();
		}
		else{
			return NULL;
		}
	}

	public function getField($columns){
		if($columns==''){
			return NULL;
		}
		$query='SELECT '.$columns.' FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->lastSql=$query;
		$res=$this->execute($query);
		if($res!=false){
			return $res->fetch_all(MYSQLI_ASSOC);
		}
		else{
			return NULL;
		}
	}

	public function count(){
		$query='SELECT count(*) FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->lastSql=$query;
		$res=$this->execute($query);
		if($res!=false){
			$res=$res->fetch_assoc();
			return $res['count(*)'];
		}
		else{
			return NULL;
		}
	}

	public function add(){
		$i=0;
		$j=0;
		$kc=count($this->key);
		$vc=count($this->value);
		$query='INSERT INTO `'.$this->table.'` (';
		foreach ($this->key as $k) {
			$query.='`'.$k.'`';
			$i++;
			if ($i<$kc) {
				$query.=',';
			}
		}
		$query.=') VALUES (';
		foreach($this->value as $v){
			$query.='\''.$v.'\'';
			$j++;
			if ($j<$vc) {
				$query.=',';
			}
		}
		$query.=')';
		$this->lastSql=$query;
		return $this->execute($query);
	}

	public function save(){
		$i=0;
		$dc=count($this->data);
		$query='UPDATE '.$this->table.' SET ';
		foreach ($this->data as $k => $v) {
			$i++;
			$query.='`'.$k.'`=\''.$v.'\'';
			if($i<$dc){
				$query.=',';
			}
		}
		$query.=' WHERE '.$this->where;
		unset($this->where);
		$this->lastSql=$query;
		return $this->execute($query);
	}

	public function setInc($field,$step='1',$math='+'){
		$query='UPDATE '.$this->table.' SET '.$field.'='.$field;
		$query.=$math.$step;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->lastSql=$query;
		return $this->execute($query);
	}

	public function setDec($field,$step='1'){
		return $this->setInc($field,$step,'-');
	}

	public function delete(){
		if(!isset($this->where)){
			return false;
		}
		$query='DELETE FROM '.$this->table.' WHERE ';
		$query.=$this->where;
		unset($this->where);
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->lastSql=$query;
		return $this->execute($query);
	}
}
