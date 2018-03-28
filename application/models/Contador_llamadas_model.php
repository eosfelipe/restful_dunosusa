<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contador_llamadas_model extends CI_Model{

	public $id;
	public $centro;
	public $fecha_registro;
	public $bandera;
	public $ip;

	/*
		Felipe ::1
		Josue 	10.32.5.114
		Jesus 	10.32.5.113
		Carlo 	10.32.1.139
		Jose 	10.32.1.157
		Manuel 	10.32.1.100
		Nestor 	10.32.1.194
		Yosepth 10.32.5.111
		Eduardo 10.32.1.210
	*/

	public $usuarios = array(
		'Felipe'=>'::1',
		'José'=>'10.32.1.157',
		'Carlo'=>'10.32.1.139',
		'Nestor'=>'10.32.1.194',
		'Manuel'=>'10.32.1.100',
		'Eduardo'=>'10.32.1.210',
		'Josué'=>'10.32.5.114',
		'Jesús'=>'10.32.5.113',
		'Yosepth'=>'10.32.5.111',
		);

	public function get_llamadas_usuario($ip){
		$mes_actual = date('Y-m');
		$this->db->where( array('ip'=>$ip) );
		$this->db->like('fecha_registro', $mes_actual);	    
	    $this->db->from('contador_llamadas');
	    $query = $this->db->count_all_results();

	    return $query;
	}

	public function get_llamadas_mes(){
		$mes_actual = date('Y-m');
		$this->db->like('fecha_registro', $mes_actual);
		$this->db->from('contador_llamadas');
		$query = $this->db->count_all_results();

		return $query;
	}

	public function get_top_mes(){
		// 'select cll.centro, s.nombre, SUM(cll.bandera) as total_llamadas from contador_llamadas cll inner join sucursales s on cll.centro = s.centro where cll.fecha_registro like "%2017-12-%" GROUP BY centro ORDER BY `total_llamadas` DESC LIMIT 0,10'
		// $mes_actual = date('Y-m');
		$this->load->helper('utilidades');
		$mes_actual;

		$meses = array();

		for($i=1;$i<13;$i++){
			$mes_actual = '2018-0'.$i;

			if($i===10)
				$mes_actual = '2018-'.$i;
			if($i===11)
				$mes_actual = '2018-'.$i;
			if($i===12)
				$mes_actual = '2017-'.$i;
		

		$this->db->select('contador_llamadas.centro, sucursales.nombre, SUM(contador_llamadas.bandera) as total_llamadas', FALSE)
						->from('contador_llamadas')
						->join('sucursales', 'contador_llamadas.centro = sucursales.centro', 'inner')
						->like('contador_llamadas.fecha_registro', $mes_actual)
						->group_by('contador_llamadas.centro')
						->order_by('total_llamadas', 'DESC')
						->limit(5);

		$query = $this->db->get();

		$str = $this->db->last_query();

		$str = str_replace("\n", " ", $str);

		$query = $this->db->query($str);

		array_push($meses, $query->result_array());
		// array_push($meses, get_mes($i));
		$this->db->reset_query();
		}

		return $meses;
	}

	//SELECT u.nombre, sum(cll.bandera) as total_llamadas_diciembre from contador_llamadas cll INNER JOIN usuario u on u.ip = cll.ip where cll.fecha_registro BETWEEN '2017-12-01' and '2017-12-13' GROUP by cll.ip ORDER by total_llamadas_diciembre DESC

	public function get_top_usuarios(){
		$mes_actual = date('Y-m');

		$this->db->select('usuario.nombre, sum(contador_llamadas.bandera) as total_llamadas_mes', FALSE)
						->from('contador_llamadas')
						->join('usuario', 'usuario.ip = contador_llamadas.ip', 'inner')
						->like('contador_llamadas.fecha_registro', $mes_actual)
						->group_by('contador_llamadas.ip')
						->order_by('total_llamadas_mes', 'DESC');

		$query = $this->db->get();
		$str = $this->db->last_query();
		$str = str_replace("\n", " ", $str);

		$query = $this->db->query($str);

		return $query;
	}

	public function get_top_usuarios_mes_anterior(){
		$this->load->helper('utilidades');
		$mes_anterior = get_mes_menos();

		$this->db->select('usuario.nombre, sum(contador_llamadas.bandera) as total_llamadas_mes', FALSE)
						->from('contador_llamadas')
						->join('usuario', 'usuario.ip = contador_llamadas.ip', 'inner')
						->like('contador_llamadas.fecha_registro', $mes_anterior)
						->group_by('contador_llamadas.ip')
						->order_by('total_llamadas_mes', 'DESC');

		$query = $this->db->get();
		$str = $this->db->last_query();
		$str = str_replace("\n", " ", $str);

		$query = $this->db->query($str);

		return $query;
	}

	public function get_sucursales(){
		$yucatan;
		$campeche;
		$qroo;
		$puebla;
		$tabasco;
		$chiapas;

		$total;

		$sucursales = array();

		$total = $this->db->query('select count(*) as Total from sucursales where centro < 9000 and contrato_referencia!="baja"');
		$yucatan = $this->db->query('select count(*) as Yucatan from sucursales where centro > 1000 and centro < 2000 and contrato_referencia!="baja"');
		$campeche = $this->db->query('select count(*) as Campeche from sucursales where centro > 2000 and centro < 3000 and contrato_referencia!="baja"');
		$qroo = $this->db->query('select count(*) as QRoo from sucursales where centro > 3000 and centro < 4000 and contrato_referencia!="baja"');
		$puebla = $this->db->query('select count(*) as Puebla from sucursales where centro > 4000 and centro < 5000 and contrato_referencia!="baja"');
		$tabasco = $this->db->query('select count(*) as Tabasco from sucursales where centro > 5000 and centro < 6000 and contrato_referencia!="baja"');
		$chiapas = $this->db->query('select count(*) as Chiapas from sucursales where centro > 6000 and centro < 7000 and contrato_referencia!="baja"');

		if($yucatan){
			array_push($sucursales, $total->result_array());
			array_push($sucursales, $yucatan->result_array());
			array_push($sucursales, $campeche->result_array());
			array_push($sucursales, $qroo->result_array());
			array_push($sucursales, $puebla->result_array());
			array_push($sucursales, $tabasco->result_array());
			array_push($sucursales, $chiapas->result_array());

			return $sucursales;
		}else{
			return 'mal';
		}
	}

}