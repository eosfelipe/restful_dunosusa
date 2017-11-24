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

  public function get_sucursal( $centro ){
    $this->db->where( array('centro'=>$centro) );
    $query = $this->db->get('sucursales');

    $row = $query->custom_row_object(0,'Sucursales_model');

    if(isset($row)){
      $row->id      =intval($row->id);
      $row->centro  =intval($row->centro);
    }

    return $row;
  }

  public function get_sucursales(){
    $this->db->where( array('contrato_referencia !='=>'baja') );
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

    }

    return $this;
  }

  public function insert(){
    return "insert";
  }

  public function update(){
    return "update";
  }
}
