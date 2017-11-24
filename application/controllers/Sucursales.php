<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require( APPPATH.'/libraries/REST_Controller.php');

class Sucursales extends REST_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->model('Sucursales_model');
  }

  public function sucursales_get(){

    if($this->uri->segment(3)){
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'Petición incorrecta',
        'total_registros'=>0,
        'sucursales'=>null
      );
      $this->response( $respuesta );
      return;
    }

    $sucursales = $this->Sucursales_model->get_sucursales();

    $respuesta = array(
      'err'=>FALSE,
      'mensaje'=>'Registros cargados correctamente.',
      'total_registros'=>$sucursales->num_rows(),
      'sucursales'=>$sucursales->result()
    );

    $this->response( $respuesta );
  }

  public function sucursal_get(){
    $sucursal_centro = $this->uri->segment(3);
    //validar $sucursal_centro
    if( !isset($sucursal_centro) ){
      $respuesta = array(
          'err'=>TRUE,
          'mensaje'=>'Es necesario el número de centro.'
        );
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
    }
    $sucursal = $this->Sucursales_model->get_sucursal( $sucursal_centro );
    if(isset($sucursal)){
      $respuesta = array(
          'err'=>FALSE,
          'mensaje'=>'Registro cargado correctamente',
          'sucursal'=>$sucursal
        );
        $this->response( $respuesta );
    }else{
      $respuesta = array(
          'err'=>TRUE,
          'mensaje'=>'El centro '.$sucursal_centro.' no existe',
          'sucursal'=>null
        );
        $this->response( $respuesta, REST_Controller::HTTP_NOT_FOUND );
    }
    $this->response( $sucursal );
  }

  public function sucursal_put(){
    $data = $this->put();

    $this->load->library('form_validation');
    $this->form_validation->set_data( $data );

    $this->form_validation->set_rules('centro','centro','trim|required|exact_length[4]|numeric');
    $this->form_validation->set_rules('ip','ip','trim|required|valid_ip[ipv4]');
    $this->form_validation->set_rules('nombre','nombre','trim|required');
    $this->form_validation->set_rules('afiliacion','afiliación','trim|required|exact_length[7]');
    $this->form_validation->set_rules('prefijo','prefijo','trim|required|exact_length[2]');

    if( $this->form_validation->run() ){

      $query = $this->db->get_where('sucursales',array('centro'=>$data['centro']));

      $sucursal_centro = $query->row();

      if( isset($sucursal_centro )){
        $respuesta = array(
          'err'=>TRUE,
          'mensaje'=>'El centro '.$data['centro'].' ya esta registrado'
        );
        $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
        return;
      }

      $sucursal = $this->Sucursales_model->set_data( $data );
      $hecho = $this->db->insert( 'sucursales',$sucursal );

      if($hecho){
        $respuesta = array(
          'err'=>FALSE,
          'mensaje'=>'Registro insertado correctamente',
          'sucursal_id'=>$this->db->insert_id()
        );
        $this->response( $respuesta );
      }else{
        $respuesta = array(
          'err'=>TRUE,
          'mensaje'=>'Error al insertar',
          'error'=>$this->db->_error_message(),
          'error_num'=>$this->db->_error_number()
        );
        $this->response( $respuesta, REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
      }

    }else{
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'Errores en el envío de información',
        'errores'=> $this->form_validation->get_errores_arreglo()
      );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
    }




    // $this->response( $data );

  }

  public function update(){

  }
}
