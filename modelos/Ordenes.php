<?php

require_once("../config/conexion.php");  

  class Ordenes extends Conectar{
/*SELECT o.paciente,o.fecha_creacion,s.nombre,s.direccion,op.nombre from orden as o inner join sucursal_optica as s on o.id_sucursal = s.id_sucursal INNER join optica as op on s.id_optica= op.id_optica*/
    ///////////GET SUCURSALES ///////////
    public function get_opticas(){
      $conectar=parent::conexion();
      parent::set_names();
      $sql="select id_optica,nombre from optica;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
    }

    ///////////GET SUCURSALES ///////////
    public function get_sucursales_optica($id_optica){
      $conectar=parent::conexion();
      parent::set_names();
      $sql="select id_sucursal,direccion from sucursal_optica where id_optica=?;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$id_optica);
      $sql->execute();
      return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //////////////////  GET CODIGO DE ORDEN ////////////////////////

    public function get_correlativo_orden($fecha){
    $conectar = parent::conexion();
    $fecha_act = $fecha.'%';         
    $sql= "select codigo from orden_lab where fecha_correlativo like ? order by id_orden DESC limit 1;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$fecha_act);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  /////////////////  COMPROBAR SI EXISTE CORRELATIVO ///////////////
  public function validar_correlativo_orden($codigo){
    $conectar = parent::conexion();
    parent::set_names();
    $sql="select*from orden_lab where codigo=?;";
    $sql= $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll();
  }

    public function validar_existe_correlativo($dui){
    $conectar = parent::conexion();
    parent::set_names();
    $sql="select*from orden_lab where dui=?;";
    $sql= $conectar->prepare($sql);
    $sql->bindValue(1, $dui);
    $sql->execute();
    return $resultado=$sql->fetchAll();
  }
  //////////////CREAR  BARCODE///////////////////////////////////
  public function crea_barcode($codigo){
    include 'barcode.php';       
    barcode('../codigos/' . $codigo . '.png', $codigo, 50, 'horizontal', 'code128', true);
  }
  /////////////   REGISTRAR ORDEN ///////////////////////////////
  public function registrar_orden($correlativo_op,$paciente,$fecha_creacion,$od_pupilar,$oipupilar,$odlente,$oilente,$marca_aro_orden,$modelo_aro_orden,$horizontal_aro_orden,$vertical_aro_orden,$puente_aro_orden,$id_usuario,$observaciones_orden,$dui,$od_esferas,$od_cilindros,$od_eje,$od_adicion,$oi_esferas,$oi_cilindros,$oi_eje,$oi_adicion,$tipo_lente,$color_varilla,$color_frente,$imagen,$edad,$usuario,$ocupacion,$avsc,$avfinal,$avsc_oi,$avfinal_oi,$telefono,$genero,$user){

    $conectar = parent::conexion();
    date_default_timezone_set('America/El_Salvador'); 
    $hoy = date("d-m-Y H:i:s");
    $estado = 0;
    $categoria_lente = "-";
    $laboratorio = "";
    $estado_aro = '0';
    $dest_aro = '0';
    $sql = "insert into orden_lab values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $correlativo_op);
    $sql->bindValue(2, $paciente);
    $sql->bindValue(3, $fecha_creacion);
    $sql->bindValue(4, $od_pupilar);
    $sql->bindValue(5, $oipupilar);
    $sql->bindValue(6, $odlente);
    $sql->bindValue(7, $oilente);
    $sql->bindValue(8, $marca_aro_orden);
    $sql->bindValue(9, $modelo_aro_orden);
    $sql->bindValue(10, $horizontal_aro_orden);
    $sql->bindValue(11, $vertical_aro_orden);
    $sql->bindValue(12, $puente_aro_orden);
    $sql->bindValue(13, $id_usuario);
    $sql->bindValue(14, $observaciones_orden);
    $sql->bindValue(15, $dui);
    $sql->bindValue(16, $estado);
    $sql->bindValue(17, $hoy);
    $sql->bindValue(18, $tipo_lente);
    $sql->bindValue(19, $color_varilla);
    $sql->bindValue(20, $color_frente);
    $sql->bindValue(21, $imagen);
    $sql->bindValue(22, $laboratorio);
    $sql->bindValue(23, $categoria_lente);
    $sql->bindValue(24, $estado_aro);
    $sql->bindValue(25, $dest_aro);
    $sql->bindValue(26, $edad);
    $sql->bindValue(27, $usuario);
    $sql->bindValue(28, $ocupacion);
    $sql->bindValue(29, $avsc);
    $sql->bindValue(30, $avfinal);
    $sql->bindValue(31, $avsc_oi);
    $sql->bindValue(32, $avfinal_oi);
    $sql->bindValue(33, $telefono);
    $sql->bindValue(34, $genero);
    $sql->execute();

    $sql2 = "insert into rx_orden_lab value(null,?,?,?,?,?,?,?,?,?);";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $correlativo_op);
    $sql2->bindValue(2, $od_esferas);
    $sql2->bindValue(3, $od_cilindros);
    $sql2->bindValue(4, $od_eje);
    $sql2->bindValue(5, $od_adicion);
    $sql2->bindValue(6, $oi_esferas);
    $sql2->bindValue(7, $oi_cilindros);
    $sql2->bindValue(8, $oi_eje);
    $sql2->bindValue(9, $oi_adicion);
    $sql2->execute();

    $accion = "Digitación orden";

    $sql7 = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql7 = $conectar->prepare($sql7);
    $sql7->bindValue(1, $hoy);
    $sql7->bindValue(2, $user);
    $sql7->bindValue(3, $correlativo_op);
    $sql7->bindValue(4, $accion);
    $sql7->bindValue(5, $accion);
    $sql7->execute();

  }
   ////////////////////LISTAR ORDENES///////////////
public function editar_orden($correlativo_op,$paciente,$fecha_creacion,$od_pupilar,$oipupilar,$odlente,$oilente,$marca_aro_orden,$modelo_aro_orden,$horizontal_aro_orden,$vertical_aro_orden,$puente_aro_orden,$id_usuario,$observaciones_orden,$dui,$od_esferas,$od_cilindros,$od_eje,$od_adicion,$oi_esferas,$oi_cilindros,$oi_eje,$oi_adicion,$tipo_lente,$color_varilla,$color_frente,$categoria_lente,$imagen,$edad,$usuario,$ocupacion,$avsc,$avfinal,$avsc_oi,$avfinal_oi,$telefono,$genero){
  $conectar = parent::conexion();
  $edit_ord = "update orden_lab set
    paciente = ?,
    fecha = ?,
    pupilar_od = ?,                                            
    pupilar_oi = ?,
    lente_od = ?,
    lente_oi = ?,
    marca_aro = ?,
    modelo_aro = ?,
    horizontal_aro = ?,
    vertical_aro = ?,
    puente_aro = ?,
    observaciones = ?,
    dui = ?,
    tipo_lente = ?,
    color_varilla=?,
    color_frente=?,
    categoria=?,
    edad=?,
    usuario_lente=?,
    ocupacion = ?,
    avsc =?,
    avfinal =?,
    avsc_oi=?,
    avfinal_oi=?,
    telefono = ?,
    genero = ?



    where codigo = ?;";

  $edit_ord = $conectar->prepare($edit_ord);
  $edit_ord->bindValue(1, $paciente);
  $edit_ord->bindValue(2, $fecha_creacion);
  $edit_ord->bindValue(3, $od_pupilar);
  $edit_ord->bindValue(4, $oipupilar);
  $edit_ord->bindValue(5, $odlente);
  $edit_ord->bindValue(6, $oilente);
  $edit_ord->bindValue(7, $marca_aro_orden);
  $edit_ord->bindValue(8, $modelo_aro_orden);
  $edit_ord->bindValue(9, $horizontal_aro_orden);
  $edit_ord->bindValue(10, $vertical_aro_orden);
  $edit_ord->bindValue(11, $puente_aro_orden);
  $edit_ord->bindValue(12, $observaciones_orden);
  $edit_ord->bindValue(13, $dui);
  $edit_ord->bindValue(14, $tipo_lente);
  $edit_ord->bindValue(15, $color_varilla);
  $edit_ord->bindValue(16, $color_frente);
  $edit_ord->bindValue(17, $categoria_lente);
  $edit_ord->bindValue(18, $edad);
  $edit_ord->bindValue(19, $usuario);
  $edit_ord->bindValue(20, $ocupacion);
  $edit_ord->bindValue(21, $avsc);
  $edit_ord->bindValue(22, $avfinal);
  $edit_ord->bindValue(23, $avsc_oi);
  $edit_ord->bindValue(24, $avfinal_oi);
  $edit_ord->bindValue(25, $telefono);
  $edit_ord->bindValue(26, $genero);
  $edit_ord->bindValue(27, $correlativo_op);

  $edit_ord->execute();

  $sql2 = "update rx_orden_lab set
  od_esferas=?,
  od_cilindros=?,
  od_eje=?,
  od_adicion=?,
  oi_esferas=?,
  oi_cilindros=?,
  oi_eje=?,
  oi_adicion=?
  where codigo=?";
  $sql2 = $conectar->prepare($sql2);  
  $sql2->bindValue(1, $od_esferas);
  $sql2->bindValue(2, $od_cilindros);
  $sql2->bindValue(3, $od_eje);
  $sql2->bindValue(4, $od_adicion);
  $sql2->bindValue(5, $oi_esferas);
  $sql2->bindValue(6, $oi_cilindros);
  $sql2->bindValue(7, $oi_eje);
  $sql2->bindValue(8, $oi_adicion);
  $sql2->bindValue(9, $correlativo_op);
  $sql2->execute();
  
}


  public function get_ordenes(){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab order by id_orden DESC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_ordenes_filter_date($inicio,$fin){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab where fecha between ? and ? order by fecha DESC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $inicio);
    $sql->bindValue(2, $fin);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_data_orden($codigo,$paciente){

    $conectar = parent::conexion();
    $sql = "select o.genero,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color_varilla,o.color_frente,o.img,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->bindValue(2,$codigo);
    $sql->bindValue(3,$paciente);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function eliminar_orden($codigo){
    $conectar= parent::conexion();
    $sql ="delete from rx_orden_lab where codigo=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();

    $sql2 ="delete from orden_lab where codigo=?;";
    $sql2=$conectar->prepare($sql2);
    $sql2->bindValue(1,$codigo);
    $sql2->execute();
  }

  public function show_create_order($codigo){
    $conectar= parent::conexion();
    $sql="select u.nombres,o.fecha_correlativo from orden_lab as o inner join usuarios as u on u.id_usuario=o.id_usuario where o.codigo=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function enviar_orden($codigo){
    $conectar= parent::conexion();
    $sql="update orden_lab set estado='1' where codigo=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
  }
  
   public function get_ordenes_enviadas(){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab where estado='1' order by id_orden ASC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }
 ////////////////////////////////flitrar por fecha    ////
  public function get_ordenes_por_enviar($inicio,$fin){
      $conectar = parent::conexion();
      $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' and fecha between ? and ?  order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$inicio);
      $sql->bindValue(2,$fin);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
/////////////////////////////FILTRAR POR LENTE ///////////////////
  public function ordenEnviarLente($lente){
      $conectar = parent::conexion();
      $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' and o.tipo_lente=?  order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$lente);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
//////////////////////////FILTRAR POR FECHA Y LENTE ///////////////////
public function ordenEnviarFechaLente($inicio,$fin,$lente){
  $conectar = parent::conexion();
  $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' and o.tipo_lente=? and fecha between ? and ?  order by o.fecha ASC;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$lente);
  $sql->bindValue(2,$inicio);
  $sql->bindValue(3,$fin);
  $sql->execute();
  return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
}


    public function get_ordenes_enviar_general(){
      $conectar = parent::conexion();
      $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->execute();

      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_ordenes_env($laboratorio,$cat_lente,$inicio,$fin,$tipo_lente){
    $conectar = parent::conexion();
    $sql = "select o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE o.tipo_lente=? and a.tipo_accion='Envio' and o.estado='1' and o.laboratorio=? and o.categoria=? and o.fecha between ? and ? group by o.id_orden order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$tipo_lente);
    $sql->bindValue(2,$laboratorio);
    $sql->bindValue(3,$cat_lente);
    $sql->bindValue(4,$inicio);
    $sql->bindValue(5,$fin);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

 ################FILTRAR POR BASE #############
    public function getOrdenesEnvBase($laboratorio,$cat_lente){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.categoria=? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$cat_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
###################FILTRAR LENTE###############
  public function getOrdenesEnvLente($laboratorio,$tipo_lente){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.tipo_lente=? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$tipo_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
################## FILTRAR POR BASE Y LENTE #####################
  public function getOrdenesBaseLente($laboratorio,$cat_lente,$tipo_lente){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.categoria=? and o.tipo_lente=? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$cat_lente);
    $sql->bindValue(3,$tipo_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
#####################FILTRAR PR FECHA#################
  public function getOrdenesEnvFechas($laboratorio,$inicio,$hasta){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.fecha between ? and ? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$inicio);
    $sql->bindValue(3,$hasta);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }


  public function get_ordenes_env_general(){
    $conectar = parent::conexion();
    $sql = "select o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha,a.observaciones from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE a.tipo_accion='Envio' and o.estado='1' group by o.id_orden order by a.id_accion desc;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  ////////////////LENTES ENVIADOS LABORATORIO
    public function getOrdenesEnviadasLab($laboratorio,$cat_lente,$inicio,$fin,$tipo_lente){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE (o.estado='2' or o.estado='3') and o.tipo_lente=? and o.laboratorio=? and o.categoria=? and o.fecha between ? and ? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$tipo_lente);
    $sql->bindValue(2,$laboratorio);
    $sql->bindValue(3,$cat_lente);
    $sql->bindValue(4,$inicio);
    $sql->bindValue(5,$fin);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function getEnviosGeneral(){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo  WHERE o.estado='2' or o.estado='3' order by o.fecha DESC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);

  }

  public function enviarOrdenes(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $detalle_envio = array();
    $detalle_envio = json_decode($_POST["arrayEnvio"]);
    $user = $_POST["user"];
    $destino = $_POST["destino"];
    $categoria = $_POST["categoria_len"];
    $accion = "Envio";
    foreach ($detalle_envio as $k => $v) {
      $codigoOrden = $v->id_item;
      /////////////////Validar si existe orden en tabla acciones
      $sql2 = "select codigo from acciones_orden where codigo=?;";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();
      $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
      ############REGISTRAR ACCION#############
      if(is_array($resultado) and count($resultado)==0){
        $sql3 = "update orden_lab set estado='1',laboratorio=?,categoria=? where codigo=?;";
        $sql3=$conectar->prepare($sql3);
        $sql3->bindValue(1,$destino);
        $sql3->bindValue(2,$categoria);
        $sql3->bindValue(3,$codigoOrden);
        $sql3->execute();
        ###########################################################
        $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $hoy);
        $sql->bindValue(2, $user);
        $sql->bindValue(3, $codigoOrden);
        $sql->bindValue(4, $accion);
        $sql->bindValue(5, $destino);
        $sql->execute();
      }
      
    }//////////////FIN FOREACH 

    }//////////fin metodo enviar ordenes

  public function get_ordenes_procesando(){
    $conectar = parent::conexion();      
    $sql = "select o.codigo,o.paciente,o.laboratorio,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE a.tipo_accion='Recibido' and o.estado='2';";
    $sql=$conectar->prepare($sql);
    $sql->execute();

    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function enviarLente($codigo,$destino,$usuario){
    $conectar = parent::conexion();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $accion = "Envio";
    $sql3 = "update orden_lab set estado='1',laboratorio=? where codigo=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$destino);
    $sql3->bindValue(2,$codigo);
    $sql3->execute();

    $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $hoy);
    $sql->bindValue(2, $usuario);
    $sql->bindValue(3, $codigo);
    $sql->bindValue(4, $accion);
    $sql->bindValue(5, $destino);
    $sql->execute();

  }

  public function editarEnvio($codigo,$dest,$cat,$paciente){
    $conectar = parent::conexion();
    $sql3 = "update orden_lab set laboratorio=?,categoria=? where codigo=? and paciente=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$dest);
    $sql3->bindValue(2,$cat);
    $sql3->bindValue(3,$codigo);
    $sql3->bindValue(4,$paciente);
    $sql3->execute();

  }

    public function resetTables(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $accion = "Envio laboratorio";
    $arrayReset = array();
    $arrayReset = json_decode($_POST["array_restart"]);
    $laboratorio = $_POST["laboratorio"];
    $tipo_lente = $_POST["tipo_lente"];
    $base = $_POST["base"];
    $usuario = "Andvas";
    foreach ($arrayReset as $k) {
    $codigoOrden = $k;      
    $sql3 = "update orden_lab set estado='2' where codigo=? and laboratorio=? and tipo_lente=? and categoria=? and estado='1';";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$codigoOrden);
    $sql3->bindValue(2,$laboratorio);
    $sql3->bindValue(3,$tipo_lente);
    $sql3->bindValue(4,$base);

    $sql3->execute();

    $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $hoy);
    $sql->bindValue(2, $usuario);
    $sql->bindValue(3, $codigoOrden);
    $sql->bindValue(4, $accion);
    $sql->bindValue(5, $laboratorio);
    $sql->execute();

      
    }//////////////FIN FOREACH 

    }//////////fin metodo enviar ordenes

     public function resetTablesPrint(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $accion = "Envio laboratorio - Imprimir";
    $arrayReset = array();
    $arrayReset = json_decode($_POST["array_restart_print"]);
    $usuario = "Andvas";
    foreach ($arrayReset as $k) {
    $codigoOrden = $k;      
    $sql3 = "update orden_lab set estado='3' where codigo=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$codigoOrden);
    $sql3->execute();

    $sql2 = "select laboratorio from orden_lab where codigo=?;";
    $sql2=$conectar->prepare($sql2);
    $sql2->bindValue(1,$codigoOrden);
    $sql2->execute();

    $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultado as $value) {
      $laboratorio = $value["laboratorio"];
    }


    $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $hoy);
    $sql->bindValue(2, $usuario);
    $sql->bindValue(3, $codigoOrden);
    $sql->bindValue(4, $accion);
    $sql->bindValue(5, $laboratorio);
    $sql->execute();
      
    }//////////////FIN FOREACH 

    }//////////fin metodo enviar ordenes


    public function getCorrelativoRectificacion(){
      $conectar = parent::conexion();
      parent::set_names();

      $sql = "select codigo_rectifi from rectificacion order by id_rectifi DESC limit 1;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);      

    }

    public function registrarRectificacion($motivo,$estado_aro,$usuario,$codigoOrden,$correlativo_rc){
      
      $conectar = parent::conexion();
      parent::set_names();

      date_default_timezone_set('America/El_Salvador');
      $hoy = date("Y-m-d");
      $hora = date(" H:i:s");

      $cr = "select codigo_rectifi from rectificacion where codigo_rectifi = ?;";
      $cr=$conectar->prepare($cr);
      $cr->bindValue(1, $codigoOrden);
      $cr->execute();
     
      if($cr->rowCount() == 0) {

      $accion = "Rectificacion";
      $observaciones = '<i class="fa fa-eye" aria-hidden="true" style="color:blue" onClick="detRecti(\''.$codigoOrden.'\')"></i>';
      $sql7 = "insert into acciones_orden values(null,?,?,?,?,?);";
      $sql7 = $conectar->prepare($sql7);
      $sql7->bindValue(1, $hoy."  ".$hora);
      $sql7->bindValue(2, $usuario);
      $sql7->bindValue(3, $codigoOrden);
      $sql7->bindValue(4, $accion);
      $sql7->bindValue(5, $observaciones."<span>"." ".$motivo."</spa>");
      $sql7->execute();

      $sql = 'insert into rectificacion values(null,?,?,?,?,?,?);';
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $correlativo_rc);
      $sql->bindValue(2, $hoy);
      $sql->bindValue(3, $hora);
      $sql->bindValue(4, $usuario);
      $sql->bindValue(5, $motivo);
      $sql->bindValue(6, $estado_aro);
      $sql->execute();

      $sql2 = "select*from orden_lab where codigo=?;";
      $sql2 = $conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();

      $dataOrden = $sql2->fetchAll(PDO::FETCH_ASSOC);
      
      foreach ($dataOrden as $value) {
          $id_orden = $value["id_orden"];
          $codigo = $value["codigo"];
          $paciente = $value["paciente"];
          $fecha = $value["fecha"];
          $pupilar_od = $value["pupilar_od"];
          $pupilar_oi = $value["pupilar_oi"];
          $lente_od = $value["lente_od"];
          $lente_oi = $value["lente_oi"];
          $marca_aro = $value["marca_aro"];
          $modelo_aro = $value["modelo_aro"];
          $horizontal_aro = $value["horizontal_aro"];
          $vertical_aro = $value["vertical_aro"];
          $puente_aro = $value["puente_aro"];
          $id_usuario = $value["id_usuario"];
          $observaciones = $value["observaciones"];
          $dui = $value["dui"];
          $estado = $value["estado"];
          $fecha_correlativo = $value["fecha_correlativo"];
          $tipo_lente = $value["tipo_lente"];
          $color_varilla = $value["color_varilla"];
          $color_frente = $value["color_frente"];
          $img = $value["img"];
          $laboratorio = $value["laboratorio"];
          $categoria = $value["categoria"];
          $estado_aro = $value["estado_aro"];
          $dest_aro = $value["dest_aro"];
          $edad = $value["edad"];
          $usuario_lente = $value["usuario_lente"];
          $ocupacion = $value["ocupacion"];
          $avsc = $value["avsc"];
          $avfinal = $value["avfinal"];
          $avsc_oi = $value["avsc_oi"];
          $avfinal_oi = $value["avfinal_oi"];
          $telefono = $value["telefono"];
          $genero = $value["genero"];
      }

          $sql4 = "insert into detalle_orden_rectificicacion values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
          $sql4 = $conectar->prepare($sql4);
          $sql4->bindValue(1, $correlativo_rc);
          $sql4->bindValue(2, $codigo);
          $sql4->bindValue(3, $paciente);
          $sql4->bindValue(4, $fecha);
          $sql4->bindValue(5, $pupilar_od);
          $sql4->bindValue(6, $pupilar_oi);
          $sql4->bindValue(7, $lente_od);
          $sql4->bindValue(8, $lente_oi);
          $sql4->bindValue(9, $marca_aro);
          $sql4->bindValue(10, $modelo_aro);
          $sql4->bindValue(11, $horizontal_aro);
          $sql4->bindValue(12, $vertical_aro);
          $sql4->bindValue(13, $puente_aro);
          $sql4->bindValue(14, $id_usuario);
          $sql4->bindValue(15, $observaciones);
          $sql4->bindValue(16, $dui);
          $sql4->bindValue(17, $estado);
          $sql4->bindValue(18, $fecha_correlativo);
          $sql4->bindValue(19, $tipo_lente);
          $sql4->bindValue(20, $color_varilla);
          $sql4->bindValue(21, $color_frente);
          $sql4->bindValue(22, $img);
          $sql4->bindValue(23, $laboratorio);
          $sql4->bindValue(24, $categoria);
          $sql4->bindValue(25, $estado_aro);
          $sql4->bindValue(26, $dest_aro);
          $sql4->bindValue(27, $edad);
          $sql4->bindValue(28, $usuario_lente);
          $sql4->bindValue(29, $ocupacion);
          $sql4->bindValue(30, $avsc);
          $sql4->bindValue(31, $avfinal);
          $sql4->bindValue(32, $avsc_oi);
          $sql4->bindValue(33, $avfinal_oi);
          $sql4->bindValue(34, $telefono);
          $sql4->bindValue(35, $genero);

          $sql4->execute();


          $sql5 = "select*from rx_orden_lab where codigo = ?;";
          $sql5 = $conectar->prepare($sql5);
          $sql5->bindValue(1, $codigoOrden);
          $sql5->execute();
          $dataOrdenRx = $sql5->fetchAll(PDO::FETCH_ASSOC);

          foreach ($dataOrdenRx as $key) {
              $codigo = $key["codigo"];
              $od_esferas = $key["od_esferas"];
              $od_cilindros = $key["od_cilindros"];
              $od_eje = $key["od_eje"];
              $od_adicion = $key["od_adicion"];
              $oi_esferas = $key["oi_esferas"];
              $oi_cilindros = $key["oi_cilindros"];
              $oi_eje = $key["oi_eje"];
              $oi_adicion = $key["oi_adicion"];
          }

          $sql6 = "insert into rx_det_orden_recti values(null,?,?,?,?,?,?,?,?,?,?);";
          $sql6 = $conectar->prepare($sql6);
          $sql6->bindValue(1, $correlativo_rc);
          $sql6->bindValue(2, $codigo);
          $sql6->bindValue(3, $od_esferas);
          $sql6->bindValue(4, $od_cilindros);
          $sql6->bindValue(5, $od_eje);
          $sql6->bindValue(6, $od_adicion);
          $sql6->bindValue(7, $oi_esferas);
          $sql6->bindValue(8, $oi_cilindros);
          $sql6->bindValue(9, $oi_eje);
          $sql6->bindValue(10, $oi_adicion);
          $sql6->execute();          
      }

      if ($sql->rowCount() > 0 and $sql4->rowCount() > 0 and $sql6->rowCount() > 0){
        echo "Insert!";
      }elseif($cr->rowCount() == 0){
        echo "Error!";
      }
}

/*-------------- GET ACCIONES ORDEN ----------------------*/
public function getAccionesOrden($codigo){
    $conectar = parent::conexion();
    parent::set_names();

    $sql = "select u.nombres,u.codigo_emp,a.codigo,a.fecha,a.tipo_accion,a.observaciones from usuarios as u inner join acciones_orden as a on u.usuario=a.usuario where a.codigo=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);

} 

/*-------------------LISTAR DETALLE RECTIFICACIONES ----------------*/
public function getTablasRectificaciones($codigoOrden){

  $conectar = parent::conexion();
  parent::set_names();

  $sql = "select o.id_det_recti,o.codigo_recti,o.fecha,o.paciente,o.dui,o.edad,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.avsc,o.avfinal,o.modelo_aro,o.marca_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.tipo_lente,o.codigo_orden from detalle_orden_rectificicacion as o inner join rx_det_orden_recti as rx on o.codigo_orden=rx.codigo where o.codigo_orden=? GROUP BY o.id_det_recti ORDER by o.id_det_recti ASC;;";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $codigoOrden);
  $sql->execute();
  $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  $tabla = "";
  $cont = 0; 
  foreach ($resultado as $key) {
    $correlativo_rc = $key["codigo_recti"];

    $sql2 = "select *from rectificacion where codigo_rectifi=?;";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $correlativo_rc);
    $sql2->execute();
    $correlativo=$sql2->fetchAll(PDO::FETCH_ASSOC);

    foreach ($correlativo as $cr) {
      $motivo = $cr["motivo"];
      $est_aro = $cr["estado_aro"];
    }    
    $cont == 0 ? $titulo = "<b>ORDEN ORIGINAL</b>" : $titulo="RECTIFICACION";
    $cont%2 == 0 ? $background = "#eaf7f8" : $background="";

    $tabla .= "
      <table width='100%'  class='table2' style='background:".$background."'>
      <tr>
      <td class='stilot1' colspan='100' style='text-align:center'><b>".$titulo."</b></td>
      </tr>
      <tr>
      <td class='stilot1' colspan='30' style='text-align:center'>".$key["codigo_orden"]."</td>
      <td class='stilot1' colspan='30' style='text-align:center'><b>Lente:</b> ".$key["tipo_lente"]."</td>
      <td class='stilot1' colspan='40' style='text-align:center'><b>Fecha</b> ".date("d-m-Y",strtotime($key["fecha"]))."</td>
      </tr>
            <tr style='height: 14px'>
        <td class='stilot1 encabezado' colspan='65'><b style='padding: 0px'>Paciente:</b></td>
        <td class='stilot1 encabezado' colspan='20'><b style='padding: 0px'>DUI</b></td>
        <td class='stilot1 encabezado' colspan='15'><b style='padding: 0px'>Edad:</b></td>
      </tr>
      <tr>
        <td class='stilot1' colspan='65' style='text-transform:uppercase;font-size:10px'>".$key["paciente"]."</td>
        <td class='stilot1' colspan='20'>".$key["dui"]."</td>
        <td class='stilot1' colspan='15'>".$key["edad"]."</td>
      </tr>
      <tr>
        <td colspan='100' class='stilot1 encabezado' style='text-align: center'><b>Rx final</b></td>
      </tr>
      <tr>
      <th style='text-align: center;' colspan='20' class='stilot1'><b>OJO</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Esfera</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Cilindro</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Eje</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Adición</b></th>
      </tr>
      <tr>
        <td colspan='20' class='stilot1'><b>OD</b></td>
        <td colspan='20' class='stilot1'>".$key["od_esferas"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_cilindros"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_eje"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_adicion"]."</td>
      </tr>
    <tr>
      <td colspan='20' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'>".$key["oi_esferas"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_cilindros"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_eje"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_adicion"]."</td>
    </tr>
    <tr>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Dist. Pupilar</td>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Altura de lente</td>
    <td colspan='40' class='stilot1 encabezado' style='height:10px'>Agudeza visual</td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'><b>AVsc</b></td>
      <td colspan='20' class='stilot1'><b>AVfinal</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'>".$key["pupilar_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["pupilar_oi"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_oi"]." mm</td>
      <td colspan='20' class='stilot1'>".$key["avsc"]."</td>
      <td colspan='20' class='stilot1'>".$key["avfinal"]."</td>
    </tr>
    <tr>
      <td colspan='100' class='stilot1 encabezado'><b>ARO</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>Mod.</b></td>
      <td colspan='30' class='stilot1'><b>Marca</b></td>
      <td colspan='15' class='stilot1'><b>Horiz.</b></td>
      <td colspan='20' class='stilot1'><b>Vertical</b></td>
      <td colspan='20' class='stilot1'><b>Puente</b></td>
    </tr>
    <tr>
      <td colspan='15' class='stilot1'>".$key["modelo_aro"]."</td>
      <td colspan='30' class='stilot1' style='font-size:10px'>".$key["marca_aro"]."</td>
      <td colspan='15' class='stilot1'>".$key["horizontal_aro"]."</td>
      <td colspan='20' class='stilot1'>".$key["vertical_aro"]."</td>
      <td colspan='20' class='stilot1'>".$key["puente_aro"]."</td>
    </tr>

    <tr>
      <td colspan='100' class='stilot1' style='text-align: left'><b>Motivo: </b>".$motivo."</td>     
    </tr>
    <tr> <td colspan='100' class='stilot1' style='text-align: left'><b>Estado aro: </b>".$est_aro."</td></tr>
    </table><br>
    ";
    $cont++;

  }

  
echo $tabla;

}

public function getDetOrdenActRec($codigoOrden){

  $conectar = parent::conexion();
  parent::set_names();

  $sql = "select o.fecha,o.paciente,o.dui,o.edad,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.avsc,o.avfinal,o.modelo_aro,o.marca_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.tipo_lente,o.codigo from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.codigo=?;";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $codigoOrden);
  $sql->execute();
  $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  $tabla = "";
  
  foreach ($resultado as $key) {
   
    $tabla .= "
      <tr>
      <td class='stilot1' colspan='100' style='text-align:center'><b>ORDEN ACTUAL</b></td>
      </tr>
      <tr>
      <td class='stilot1' colspan='30' style='text-align:center'>".$key["codigo"]."</td>
      <td class='stilot1' colspan='30' style='text-align:center'><b>Lente:</b> ".$key["tipo_lente"]."</td>
      <td class='stilot1' colspan='40' style='text-align:center'><b>Fecha</b> ".date("d-m-Y",strtotime($key["fecha"]))."</td>
      </tr>
            <tr style='height: 14px'>
        <td class='stilot1 encabezado' colspan='65'><b style='padding: 0px'>Paciente:</b></td>
        <td class='stilot1 encabezado' colspan='20'><b style='padding: 0px'>DUI</b></td>
        <td class='stilot1 encabezado' colspan='15'><b style='padding: 0px'>Edad:</b></td>
      </tr>
      <tr>
        <td class='stilot1' colspan='65' style='text-transform:uppercase;font-size:10px'>".$key["paciente"]."</td>
        <td class='stilot1' colspan='20'>".$key["dui"]."</td>
        <td class='stilot1' colspan='15'>".$key["edad"]."</td>
      </tr>
      <tr>
        <td colspan='100' class='stilot1 encabezado' style='text-align: center'><b>Rx final</b></td>
      </tr>
      <tr>
      <th style='text-align: center;' colspan='20' class='stilot1'><b>OJO</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Esfera</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Cilindro</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Eje</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Adición</b></th>
      </tr>
      <tr>
        <td colspan='20' class='stilot1'><b>OD</b></td>
        <td colspan='20' class='stilot1'>".$key["od_esferas"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_cilindros"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_eje"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_adicion"]."</td>
      </tr>
    <tr>
      <td colspan='20' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'>".$key["oi_esferas"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_cilindros"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_eje"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_adicion"]."</td>
    </tr>
    <tr>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Dist. Pupilar</td>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Altura de lente</td>
    <td colspan='40' class='stilot1 encabezado' style='height:10px'>Agudeza visual</td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'><b>AVsc</b></td>
      <td colspan='20' class='stilot1'><b>AVfinal</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'>".$key["pupilar_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["pupilar_oi"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_oi"]." mm</td>
      <td colspan='20' class='stilot1'>".$key["avsc"]."</td>
      <td colspan='20' class='stilot1'>".$key["avfinal"]."</td>
    </tr>
    <tr>
      <td colspan='100' class='stilot1 encabezado'><b>ARO</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>Mod.</b></td>
      <td colspan='30' class='stilot1'><b>Marca</b></td>
      <td colspan='15' class='stilot1'><b>Horiz.</b></td>
      <td colspan='20' class='stilot1'><b>Vertical</b></td>
      <td colspan='20' class='stilot1'><b>Puente</b></td>
    </tr>
    <tr>
      <td colspan='15' class='stilot1'>".$key["modelo_aro"]."</td>
      <td colspan='30' class='stilot1' style='font-size:10px'>".$key["marca_aro"]."</td>
      <td colspan='15' class='stilot1'>".$key["horizontal_aro"]."</td>
      <td colspan='20' class='stilot1'>".$key["vertical_aro"]."</td>
      <td colspan='20' class='stilot1'>".$key["puente_aro"]."</td>
    </tr>

    ";

  }

  
echo $tabla;

}



}//Fin de la Clase



