<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursales_model extends CI_Model{

  public $id;
  public $centro;
  public $ip;
  public $nombre;
  public $afiliacion;
  public $prefijo;
  public $contrato_referencia;
  public $proveedor;
  public $estado;

  public function get_sucursal( $centro ){
    $this->db->where( array('centro'=>$centro,'estado ='=>'1') );
    $query = $this->db->get('sucursales');

    $row = $query->custom_row_object(0,'Sucursales_model');

    if(isset($row)){
      $row->id      =intval($row->id);
      $row->centro  =intval($row->centro);
    }

    return $row;
  }

  public function get_sucursales(){
    $this->db->where( array('estado ='=>'1') );
    $query = $this->db->get('sucursales');

    return $query;
  }

  public function set_data( $data ){
    foreach ($data as $nombre_campo => $valor_campo) {
      if( property_exists('Sucursales_model',$nombre_campo) ){
        $this->$nombre_campo = $valor_campo;
      }

      if($this->afiliacion == NULL) $this->afiliacion = "";
      if($this->prefijo == NULL) $this->prefijo = "";
      if($this->contrato_referencia == NULL) $this->contrato_referencia = "";
      if($this->proveedor == NULL) $this->proveedor = "";

      $this->prefijo = strtoupper($this->prefijo); 

    }

    return $this;
  }

  public function insert(){
    //validar centro
    $query = $this->db->get_where('sucursales',array('centro'=>$this->centro));
    $sucursal_centro = $query->row();
    if( isset($sucursal_centro )){
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'El centro '.$data['centro'].' ya esta registrado'
      );
      return $respuesta;
    }
    // $sucursal = $this->Sucursales_model->set_data( $data );
    $hecho = $this->db->insert( 'sucursales',$this );
    if($hecho){
      $respuesta = array(
        'err'=>FALSE,
        'mensaje'=>'Registro insertado correctamente',
        'sucursal_id'=>$this->db->insert_id()
      );
    }else{
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'Error al insertar',
        'error'=>$this->db->_error_message(),
        'error_num'=>$this->db->_error_number()
      );
    }
    return $respuesta;
  }

  public function update(){
    //validar centro
    $this->db->where( 'centro =',$this->centro );
    // $this->db->where( 'id !=',$this->id );

    $query = $this->db->get('sucursales');

    $sucursal_centro = $query->row();

    if( !isset( $sucursal_centro )){
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'El centro no existe'
      );
      return $respuesta;
    }

    $this->id = $sucursal_centro->id;
    

    $this->db->reset_query();
    $this->db->where( 'id', $this->id );
    $hecho = $this->db->update( 'sucursales',$this );
    if($hecho){
      $respuesta = array(
        'err'=>FALSE,
        'mensaje'=>'Registro actualizado correctamente',
        'sucursal_id'=>$this->id
      );
    }else{
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'Error al actualizar',
        'error'=>$this->db->_error_message(),
        'error_num'=>$this->db->_error_number()
      );
    }
    return $respuesta;
  }

  public function delete( $sucursal_centro ){
    $this->db->set('contrato_referencia','baja');
    $this->db->where('centro',$sucursal_centro);
    $hecho = $this->db->update('sucursales');

    if($hecho){
      //Borrado
      $respuesta = array(
        'err'=>FALSE,
        'mensaje'=>'Registro eliminado correctamente'
      );
    }else{
      $respuesta = array(
        'err'=>TRUE,
        'mensaje'=>'Error al borrar',
        'error'=>$this->db->_error_message(),
        'error_num'=>$this->db->_error_number()
      );
    }
    return $respuesta;
  }
}
