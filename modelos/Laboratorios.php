<?php

require_once("../config/conexion.php");  

  class Laboratorios extends Conectar{
   
    public function get_ordenes_filter_date($inicio,$fin){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab where (fecha between ? and ?) and estado_aro < 2 order by id_orden DESC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $inicio);
    $sql->bindValue(2, $fin);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function recibirOrdenesLab(){
    $conectar= parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $detalle_recibidos = array();
    $detalle_recibidos = json_decode($_POST["arrayRecibidos"]);
    $usuario = $_POST["usuario"];

    foreach ($detalle_recibidos as $k => $v) {
      
      $codigoOrden = $v->codigo;
      $accion = "Recibir Lab";
      $destino = "-";

      $sql2 = "update orden_lab set estado_aro='2' where codigo=?;";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();

      $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $hoy);
      $sql->bindValue(2, $usuario);
      $sql->bindValue(3, $codigoOrden);
      $sql->bindValue(4, $accion);
      $sql->bindValue(5, $destino);
      $sql->execute();
    }
  }

  public function get_ordenes_procesando_lab(){
    $conectar= parent::conexion();
    parent::set_names();
    $sql= "select*from orden_lab where estado_aro = 2 order by id_orden DESC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

    public function finalizarOrdenesLab(){
    $conectar= parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $detalle_finalizados = array();
    $detalle_finalizados = json_decode($_POST["arrayOrdenesBarcode"]);
    $usuario = $_POST["usuario"];

    foreach ($detalle_finalizados as $k => $v) {
      
      $codigoOrden = $v->n_orden;
      $accion = "Finalizar Lab";
      $destino = "-";

      $sql2 = "update orden_lab set estado_aro='3' where codigo=?;";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();

      $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $hoy);
      $sql->bindValue(2, $usuario);
      $sql->bindValue(3, $codigoOrden);
      $sql->bindValue(4, $accion);
      $sql->bindValue(5, $destino);
      $sql->execute();
    }
  }

////////////////////////////ORDENES FINALIZADAS  LABORATORIOS////////////////////
    public function finalizarOrdenesLabEnviar(){
    $conectar= parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador');
    $hoy = date("Y-m-d");
    $hora = date('H:i:s');
    $detalle_finalizados = array();
    $detalle_finalizados = json_decode($_POST["arrayOrdenesBarcode"]);
    $usuario = $_POST["usuario"];
    $accion = 'Despacho lab';
    $ubicacion = '';

    $correlativo = $_POST["correlativo_accion"];
    $sql = 'insert into acciones_ordenes_veteranos values(null,?,?,?,?,?,?);';
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $correlativo);
    $sql->bindValue(2, $hoy);
    $sql->bindValue(3, $hora);
    $sql->bindValue(4, $usuario);
    $sql->bindValue(5, $accion);
    $sql->bindValue(6, $ubicacion);
    $sql->execute();

    foreach ($detalle_finalizados as $k => $v) {
      
      $codigoOrden = $v->n_orden;
      $accion = "Envio Lab";
      $destino = "-";

      $sql2 = "update orden_lab set estado_aro='4' where codigo=?;";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();

      $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $hoy);
      $sql->bindValue(2, $usuario);
      $sql->bindValue(3, $codigoOrden);
      $sql->bindValue(4, $accion);
      $sql->bindValue(5, $destino);
      $sql->execute();
      
      $est_det = 'Despachado lab';
      $sql2 = "insert into detalle_acciones_veteranos values(null,?,?,?);";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->bindValue(2, $correlativo);
      $sql2->bindValue(3, $est_det);
      $sql2->execute();


    }
  }


  public function get_ordeOrdenesFinalizadasEnviar(){
    $conectar= parent::conexion();
    parent::set_names();
    $sql= "select o.id_orden,o.codigo,a.fecha,o.paciente,o.tipo_lente,o.img from orden_lab as o INNER JOIN acciones_orden as a on a.codigo=o.codigo where o.estado_aro = '4' and a.tipo_accion='Envio Lab';";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_ordeOrdenesFinalizadas(){
    $conectar= parent::conexion();
    parent::set_names();
    $sql= "select o.id_orden,o.codigo,a.fecha,o.paciente,o.tipo_lente,o.img from orden_lab as o INNER JOIN acciones_orden as a on a.codigo=o.codigo where a.`tipo_accion` = 'Finalizar Lab' and o.estado_aro='3';";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

    public function get_ordenes_barcode_lab($codigo){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab where codigo = ?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

    public function recibirOrdenesLabBarcode(){
    $conectar= parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $detalle_recibidos = array();
    $detalle_recibidos = json_decode($_POST["arrayOrdenesBarcode"]);
    $usuario = $_POST["usuario"];

    foreach ($detalle_recibidos as $k => $v) {
      
      $codigoOrden = $v->n_orden;
      $accion = "Recibir Lab";
      $destino = "A proceso";

      $sql2 = "update orden_lab set estado_aro='2' where codigo=?;";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();

      $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $hoy);
      $sql->bindValue(2, $usuario);
      $sql->bindValue(3, $codigoOrden);
      $sql->bindValue(4, $accion);
      $sql->bindValue(5, $destino);
      $sql->execute();
    }
  }


  
  public function get_correlativo_accion_veteranos(){
    $conectar= parent::conexion();
    parent::set_names();
    
    $sql = 'select correlativo_accion from acciones_ordenes_veteranos order by id_orden_rec DESC limit 1;';
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
      
  }

  public function compruebaCorrelativo($correlativo_accion){
    $conectar= parent::conexion();
    parent::set_names();
    $sql = 'select correlativo_accion from acciones_ordenes_veteranos where correlativo_accion=?;';
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $correlativo_accion);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function recibirOrdenesVeteranos(){
    $conectar= parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $hoy = date("Y-m-d");
    $hora = date('H:i:s');
    $detalle_recibidos = array();
    $detalle_recibidos = json_decode($_POST["arrayOrdenesBarcode"]);

    $usuario = $_POST["usuario"];
    $tipo_accion = $_POST['tipo_accion'];
    $ubicacion = $_POST['ubicacion_orden'];
    $correlativo = $_POST["correlativo_accion"];

    //$tipo_accion == 'recibir_veteranos'? ($estado ='Recibido'; $accion = 'Ingreso'): ($estado='Entregado'; $accion='Entrega');
    if ($tipo_accion=='recibir_veteranos') {
      $estado ='Recibido'; $accion = 'Ingreso';
    }elseif ($tipo_accion=='entregar_veteranos') {
      $estado='Entregado'; $accion='Entrega';
    }
    

    $sql = 'insert into acciones_ordenes_veteranos values(null,?,?,?,?,?,?);';
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $correlativo);
    $sql->bindValue(2, $hoy);
    $sql->bindValue(3, $hora);
    $sql->bindValue(4, $usuario);
    $sql->bindValue(5, $accion);
    $sql->bindValue(6, $ubicacion);
    $sql->execute();

    foreach ($detalle_recibidos as $k => $v) {      
      $codigoOrden = $v->n_orden;

      $sql2 = "insert into detalle_acciones_veteranos values(null,?,?,?);";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->bindValue(2, $correlativo);
      $sql2->bindValue(3, $estado);
      $sql2->execute();
    }
    if ($tipo_accion=='entregar_veteranos') {
      $sql3 = 'update detalle_acciones_veteranos set estado="Entregado" where codigo_orden=?;';
      $sql3=$conectar->prepare($sql3);
      $sql3->bindValue(1, $codigoOrden);
      $sql3->execute();
    }
  }
  
  public function listarOrdenesRecibidasVeteranos(){
       $conectar= parent::conexion();
    parent::set_names();
    $sql = 'select o.paciente,o.dui,d.codigo_orden,a.fecha,a.hora,a.usuario,a.ubicacion,o.tipo_lente,d.id_detalle_accion from orden_lab as o inner join detalle_acciones_veteranos as d on  o.codigo=d.codigo_orden INNER join acciones_ordenes_veteranos as a on a.correlativo_accion=d.correlativo_accion where d.estado="Recibido";';
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC); 
  }

  public function listarOrdenesEntregadasVeteranos(){
       $conectar= parent::conexion();
    parent::set_names();
    $sql = 'select o.paciente,o.dui,d.codigo_orden,a.fecha,a.hora,a.usuario,a.ubicacion,o.tipo_lente,d.id_detalle_accion from orden_lab as o inner join detalle_acciones_veteranos as d on  o.codigo=d.codigo_orden INNER join acciones_ordenes_veteranos as a on a.correlativo_accion=d.correlativo_accion where a.tipo_acccion="Entrega" and d.estado="Entregado";';
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC); 
  }

}

/***************** REPORTERIA LABORATORIO **********************************
select o.paciente,o.estado,o.tipo_lente,o.fecha as fechaexp,a.fecha,a.tipo_accion,a.observaciones from orden_lab as o inner join acciones_orden as a on a.codigo=o.codigo where a.fecha like "%06-12-2021%"*/
