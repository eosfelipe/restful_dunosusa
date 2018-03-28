<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require( APPPATH.'/libraries/REST_Controller.php');

class Contador_llamadas extends REST_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('Contador_llamadas_model');
	}

	public function record_get(){
		$ip=$_SERVER['REMOTE_ADDR'];
		if($this->uri->segment(3)){
	      $respuesta = array(
	        'err'=>TRUE,
	        'mensaje'=>'Petición incorrecta',
	        'total_registros'=>0,
	        'ip_cliente'=>$_SERVER['HTTP_CLIENT_IP'],
	        'forwarded'=>$_SERVER['HTTP_X_FORWARDED_FOR'],
	        'remote'=>$ip,
	      );
	      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
	      return;
	    }else{
	    	$respuesta = array(
	    		'err'=>FALSE,
	    		'mensaje'=>'Reporte generado',
	    		'mis_llamadas'=>$this->Contador_llamadas_model->get_llamadas_usuario($ip),
	    		'total_mes'=>$this->Contador_llamadas_model->get_llamadas_mes(),
	    		'top_mes'=>$this->Contador_llamadas_model->get_top_mes(),
	    		'top_usuarios'=>$this->Contador_llamadas_model->get_top_usuarios()->result_array(),
	    		'top_usuarios_anterior'=>$this->Contador_llamadas_model->get_top_usuarios_mes_anterior()->result_array(),
	    		'sucursales'=>$this->Contador_llamadas_model->get_sucursales()
	    		);
	    }
		$this->response( $respuesta );

	}
}