<?php
class Mjornada extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='JOR_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
              
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"JOR_SECUENCIAL",
											"JOR_NOMBRE",
											"JOR_HORAINICIO",
											"JOR_HORAFIN",
											"JOR_ESTADO");
			  $datos->campos = array( "ROWNUM",
											"JOR_SECUENCIAL",
											"JOR_NOMBRE",
											"JOR_HORAINICIO",
											"JOR_HORAFIN",
											"JOR_ESTADO");
			  $datos->tabla="JORNADA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataJornada($numero){
	   $sql="select 
	   JOR_SECUENCIAL,
	   JOR_NOMBRE,
	   JOR_HORAINICIO,
	   JOR_HORAFIN,
	   JOR_ESTADO
			FROM JORNADA WHERE JOR_SECUENCIAL=$numero";
           
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select 
				JOR_SECUENCIAL,
				JOR_NOMBRE,
				JOR_HORAINICIO,
				JOR_HORAFIN,
				JOR_ESTADO
					FROM JORNADA WHERE JOR_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
          return $sol;
		}
    	
	//funcion para crear un nuevo reporte o cabecera
    function agrJornada(){
			

			
			//VARIABLES DE INGRESO
			
			$JOR_NOMBRE=$this->input->post('jornada');

			$HORA_INICIO=prepCampoAlmacenar($this->input->post('HORA_INICIO'));
            $MINUTO_INICIO=prepCampoAlmacenar($this->input->post('MINUTO_INICIO'));
            if(!empty($HORA_INICIO) and !empty($MINUTO_INICIO)){
                $JOR_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":".$MINUTO_INICIO);
            }elseif(!empty($HORA_INICIO)){
                $JOR_HORAINICIO = prepCampoAlmacenar("00:".$MINUTO_INICIO);
            }elseif(!empty($MINUTO_INICIO)){
                $JOR_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":00");
            }else{
                $JOR_HORAINICIO = prepCampoAlmacenar("00:00");
            }    
            $HORA_FIN=prepCampoAlmacenar($this->input->post('HORA_FIN'));
            $MINUTO_FIN=prepCampoAlmacenar($this->input->post('MINUTO_FIN'));
            if(!empty($HORA_FIN) and !empty($MINUTO_FIN)){
                $JOR_HORAFIN = prepCampoAlmacenar($HORA_FIN.":".$MINUTO_FIN);
            }elseif(!empty($HORA_FIN)){
                $JOR_HORAFIN = prepCampoAlmacenar("00:".$MINUTO_FIN);
            }elseif(!empty($MINUTO_FIN)){
                $JOR_HORAFIN = prepCampoAlmacenar($HORA_FIN.":00");
            }else{
                $JOR_HORAFIN = prepCampoAlmacenar("00:00");
            }	
        	$sqlJORNADAVALIDA="select count(*) NUM_JORNADA from JORNADA WHERE JOR_NOMBRE='{$JOR_NOMBRE }' and JOR_ESTADO=0";
			$NUM_JORNADA =$this->db->query($sqlJORNADAVALIDA)->row()->NUM_JORNADA ;

				//validación...
			$sqlREPETICION="select count(*) NUM_JORNADA 
			from jornada
			where upper(jor_nombre)=upper('{$JOR_NOMBRE}') 
			and jor_estado=0";
		$NUM_JORNADA=$this->db->query($sqlREPETICION)->row()->NUM_JORNADA;

		if($NUM_JORNADA==0){

				$sql="INSERT INTO JORNADA (
							JOR_NOMBRE,
							JOR_HORAINICIO,
							JOR_HORAFIN,
							JOR_ESTADO
							)VALUES (
							'$JOR_NOMBRE',
							'$JOR_HORAINICIO',
							'$JOR_HORAFIN',
							0)";
            $this->db->query($sql);
            //print_r($sql);
			$JOR_SECUENCIAL=$this->db->query("select max(JOR_SECUENCIAL) SECUENCIAL from JORNADA")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$JOR_SECUENCIAL,"numero"=>$JOR_SECUENCIAL,"mensaje"=>"Jornada: ".$JOR_SECUENCIAL.", insertado con éxito"));    
	}else {
		echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La jornada ingresada ya existe...!!!"));
	}
 }
    
	//funcion para editar un registro selccionado
    function editJornada(){
			$JOR_SECUENCIAL=$this->input->post('JOR_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$JOR_NOMBRE=$this->input->post('jornada');
			$HORA_INICIO=prepCampoAlmacenar($this->input->post('HORA_INICIO'));
            $MINUTO_INICIO=prepCampoAlmacenar($this->input->post('MINUTO_INICIO'));
            if(!empty($HORA_INICIO) and !empty($MINUTO_INICIO)){
                $JOR_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":".$MINUTO_INICIO);
            }elseif(!empty($HORA_INICIO)){
                $JOR_HORAINICIO = prepCampoAlmacenar("00:".$MINUTO_INICIO);
            }elseif(!empty($MINUTO_INICIO)){
                $JOR_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":00");
            }else{
                $JOR_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":".$MINUTO_INICIO);
            }    
            $HORA_FIN=prepCampoAlmacenar($this->input->post('HORA_FIN'));
            $MINUTO_FIN=prepCampoAlmacenar($this->input->post('MINUTO_FIN'));
            if(!empty($HORA_FIN) and !empty($MINUTO_FIN)){
                $JOR_HORAFIN = prepCampoAlmacenar($HORA_FIN.":".$MINUTO_FIN);
            }elseif(!empty($HORA_FIN)){
                $JOR_HORAFIN = prepCampoAlmacenar("00:".$MINUTO_FIN);
            }elseif(!empty($MINUTO_FIN)){
                $JOR_HORAFIN = prepCampoAlmacenar($HORA_FIN.":00");
            }else{
                $JOR_HORAFIN = prepCampoAlmacenar($HORA_FIN.":".$MINUTO_FIN);
            }


				$sql="UPDATE JORNADA SET
							JOR_NOMBRE='$JOR_NOMBRE',
							JOR_HORAINICIO='$JOR_HORAINICIO',
							JOR_HORAFIN='$JOR_HORAFIN'
                		WHERE JOR_SECUENCIAL=$JOR_SECUENCIAL";
         $this->db->query($sql);
		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$JOR_SECUENCIAL,"mensaje"=>"Jornada: ".$JOR_SECUENCIAL.", editado con éxito"));            
    }

}
?>