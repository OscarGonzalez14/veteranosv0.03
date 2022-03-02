<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Laboratorios.php");

$ordenes = new Laboratorios();

switch ($_GET["op"]){

case 'get_ordenes_pendientes_lab':
  
  $data = Array();
  $i=0;
  $datos = $ordenes->get_ordenes_filter_date($_POST["inicio"],$_POST["hasta"]);
  foreach ($datos as $row) { 
  $sub_array = array();

  $sub_array[] = $row["id_orden"];
  $sub_array[] = $row["codigo"];  
  $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
  $sub_array[] = '<input type="checkbox"class="form-check-input ordenes_recibir_lab" value="'.$row["id_orden"].'" name="'.$row["codigo"].'" id="orden_env'.$i.'">'."Rec.".'';
  $sub_array[] = strtoupper($row["paciente"]);
  $sub_array[] = $row["tipo_lente"];
  $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\''.$row["codigo"].'\',\''.$row["paciente"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';  
  $sub_array[] = '<i class="fas fa-image fa-2x" aria-hidden="true" style="color:blue" onClick="verImg(\''.$row["img"].'\',\''.$row["codigo"].'\',\''.$row["paciente"].'\')">';               
  $i++;                                             
  $data[] = $sub_array;
  }
  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;

  case 'recibir_ordenes_laboratorio':
    $ordenes->recibirOrdenesLab();
    $mensaje = "Ok";
  echo json_encode($mensaje);    
  break;

 case 'get_ordenes_procesando_lab':
  $data = Array();
  $i=0;
  $datos = $ordenes->get_ordenes_procesando_lab();
  foreach ($datos as $row) { 
  $sub_array = array();

  $sub_array[] = $row["id_orden"];
  $sub_array[] = $row["codigo"];  
  $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
   $sub_array[] = '<input type="checkbox"class="form-check-input ordenes_procesando_lab" value="'.$row["id_orden"].'" name="'.$row["codigo"].'" id="orden_enviar'.$i.'">'."Rec.".'';
  $sub_array[] = strtoupper($row["paciente"]);
  $sub_array[] = $row["tipo_lente"];
  $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\''.$row["codigo"].'\',\''.$row["paciente"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';  
  $sub_array[] = '<i class="fas fa-image fa-2x" aria-hidden="true" style="color:blue" onClick="verImg(\''.$row["img"].'\',\''.$row["codigo"].'\',\''.$row["paciente"].'\')">';               
  $i++;                                             
  $data[] = $sub_array;
  }
  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;

  case 'get_ordenes_procesando_lab_envios':
  $data = Array();
  $i=0;
  $datos = $ordenes->get_ordeOrdenesFinalizadasEnviar();
  foreach ($datos as $row) { 
  $sub_array = array();

  $sub_array[] = $row["id_orden"];
  $sub_array[] = $row["codigo"];  
  $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
   $sub_array[] = strtoupper($row["paciente"]);
  $sub_array[] = $row["tipo_lente"];
  $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\''.$row["codigo"].'\',\''.$row["paciente"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';  
  $sub_array[] = '<i class="fas fa-image fa-2x" aria-hidden="true" style="color:blue" onClick="verImg(\''.$row["img"].'\',\''.$row["codigo"].'\',\''.$row["paciente"].'\')">';               
  $i++;                                             
  $data[] = $sub_array;
  }
  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;

  case 'finalizar_ordenes_laboratorio':
    $ordenes->finalizarOrdenesLab();
    $mensaje = "Ok";
    echo json_encode($mensaje); 
    
    break;
////////////////// 
    case 'get_ordenes_finalizadas_lab':
      $data = Array();
      $datos = $ordenes->get_ordeOrdenesFinalizadas();
      foreach ($datos as $row) { 
      $sub_array = array();

      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];  
      $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\''.$row["codigo"].'\',\''.$row["paciente"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';  
      $sub_array[] = '<i class="fas fa-image fa-2x" aria-hidden="true" style="color:blue" onClick="verImg(\''.$row["img"].'\',\''.$row["codigo"].'\',\''.$row["paciente"].'\')">';                                       
      $data[] = $sub_array;
      
      }
      
      $results = array(
        "sEcho"=>1, //Información para el datatables
        "iTotalRecords"=>count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);

  break;

  case 'get_data_orden_barcode':

    $datos=$ordenes->get_ordenes_barcode_lab($_POST["cod_orden_act"]);

    if(is_array($datos)==true and count($datos)){
      foreach($datos as $row){
      $output["codigo"] = $row["codigo"];
      $output["fecha"] = date("d-m-Y",strtotime($row["fecha"]));
      $output["paciente"] = $row["paciente"];   
      }
    }else{
      $output = 'Error';
    }
    
    echo json_encode($output);

    break;

    ///////////////BARCODE PROCESOS //////////
    case 'get_correlativo_accion_vet':

    $correlativo = $ordenes->get_correlativo_accion_veteranos();

    if (is_array($correlativo)==true and count($correlativo)>0) {
      foreach($correlativo as $row){                  
        $codigo = $row["correlativo_accion"];
        $cod = (substr($codigo,2,11))+1;
        $output["correlativo"] = "A-".$cod;
      }
    }else{
        $output["correlativo"] = "A-1";
    }
    echo json_encode($output);
    break;
//////////////////PROCESAR ORDENES BARCODE /////////////
    case 'procesar_ordenes_barcode':

    if ($_POST['tipo_accion']=='ing_lab') {
      $ordenes->recibirOrdenesLabBarcode();
      $mensaje = "Ok";
    }elseif ($_POST['tipo_accion']=='finalizar_lab') {///FINALIZAR LAB
      $ordenes->finalizarOrdenesLab();
      $mensaje = "Ok";
    }elseif ($_POST['tipo_accion']=='recibir_veteranos' or $_POST['tipo_accion']=='entregar_veteranos') {
      $comprobar_correlativo = $ordenes->compruebaCorrelativo($_POST['correlativo_accion']);
      if(is_array($comprobar_correlativo)==true and count($comprobar_correlativo)==0){
         $ordenes->recibirOrdenesVeteranos();
         $mensaje = "Ok";
      }else{
         $mensaje = 'Error';
      }     
    }elseif($_POST['tipo_accion']=='finalizar_orden_lab_completo') {
      $ordenes->finalizarOrdenesLabEnviar();
      $mensaje = "Ok";
    }

    echo json_encode($mensaje);    
    break;

    case 'listar_ordenes_recibidas_veteranos':
    $data = Array();
    $i=0;
    $datos = $ordenes->listarOrdenesRecibidasVeteranos();
    foreach ($datos as $row) { 
    $sub_array = array();

    $sub_array[] = $row["id_detalle_accion"];
    $sub_array[] = date("d-m-Y",strtotime($row["fecha"]))." ".$row["hora"];
    $sub_array[] = $row["codigo_orden"];
    $sub_array[] = $row["usuario"];
    $sub_array[] = $row["paciente"];
    $sub_array[] = $row["dui"];
    $sub_array[] = $row["tipo_lente"];
    $sub_array[] = $row["ubicacion"];
    $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\''.$row["codigo_orden"].'\',\''.$row["paciente"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';  
    //$sub_array[] = '<i class="fas fa-image fa-2x" aria-hidden="true" style="color:blue" onClick="verImg(\''.$row["img"].'\',\''.$row["codigo"].'\',\''.$row["paciente"].'\')">';               
                                          
    $data[] = $sub_array;
    }
    
    $results = array(
        "sEcho"=>1, //Información para el datatables
        "iTotalRecords"=>count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);
    break;

    case 'listar_ordenes_entregadas_veteranos':
    $data = Array();
    $i=0;
    $datos = $ordenes->listarOrdenesEntregadasVeteranos();
    foreach ($datos as $row) { 
    $sub_array = array();

    $sub_array[] = $row["id_detalle_accion"];
    $sub_array[] = date("d-m-Y",strtotime($row["fecha"]))." ".$row["hora"];
    $sub_array[] = $row["codigo_orden"];
    $sub_array[] = $row["usuario"];
    $sub_array[] = $row["paciente"];
    $sub_array[] = $row["dui"];
    $sub_array[] = $row["tipo_lente"];
    $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\''.$row["codigo_orden"].'\',\''.$row["paciente"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';  
    //$sub_array[] = '<i class="fas fa-image fa-2x" aria-hidden="true" style="color:blue" onClick="verImg(\''.$row["img"].'\',\''.$row["codigo"].'\',\''.$row["paciente"].'\')">';               
                                          
    $data[] = $sub_array;
    }
    
    $results = array(
        "sEcho"=>1, //Información para el datatables
        "iTotalRecords"=>count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);
    break;

    case 'listar_ordenes_de_envio':
    $data = Array();
    $i=0;
    $datos = $ordenes->listarOrdenesEnvio();
    foreach ($datos as $row) { 
    $sub_array = array();

    $sub_array[] = $row["id_orden_rec"];
    $sub_array[] = $row["correlativo_accion"];
    $sub_array[] = date("d-m-Y",strtotime($row["fecha"]))." ".$row["hora"];
    $sub_array[] = $row["usuario"];
    $sub_array[] = $row["cant"]." ordenes";
    $sub_array[] = '<form action="imprimirDespachoLabPdf.php" method="POST" target="_blank">
    <input type="hidden" name="correlativos_acc" value="'.$row['correlativo_accion'].'">
    <button type="submit"  class="btn btn-sm" style="background:#6d0202;color:white"><i class="fas fa-file-pdf"></i></button>
    </form>';  
            
                                          
    $data[] = $sub_array;
    }
    
    $results = array(
        "sEcho"=>1, //Información para el datatables
        "iTotalRecords"=>count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
        "aaData"=>$data);
      echo json_encode($results);
    break;



}