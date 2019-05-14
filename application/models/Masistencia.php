    <?php
class Masistencia extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='ASIS_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
           /* if (!empty($numero)){
                  $datos->econdicion .=" AND ASIS_SECUENCIAL=$numero";              
				  */
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
                                            "ASIS_SECUENCIAL",
                                            "(SELECT CONCAT(CONCAT(PER_APELLIDOS,' '), PER_NOMBRES)
                                            FROM PERSONA 
                                            WHERE PER_SECUENCIAL = ASIS_SEC_PERSONA)
                                            ASIS_SEC_PERSONA",
                                            "(SELECT CONCAT(CONCAT(CONCAT(HOR_DIA,' '), HOR_HORAINICIO),HOR_HORAFIN)
                                            FROM HORARIO 
                                            WHERE HOR_SECUENCIAL = ASIS_SEC_HORARIO)
                                            ASIS_SEC_HORARIO",
                                            "ASIS_HORAINICIO",
                                            "ASIS_HORAFIN",
                                            "ASIS_FECHAINGRESO",
											"ASIS_RESPONSABLE",
											"ASIS_ESTADO");
			  $datos->campos = array( "ROWNUM",
                                      "ASIS_SECUENCIAL",
                                      "ASIS_SEC_PERSONA",
                                      "ASIS_SEC_HORARIO",
                                      "ASIS_HORAINICIO",
                                      "ASIS_HORAFIN",
                                      "ASIS_FECHAINGRESO",
                                      "ASIS_RESPONSABLE",
                                      "ASIS_ESTADO");
			  $datos->tabla="ASISTENCIA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataAsistencia($numero){
	   $sql="select
       ASIS_SECUENCIAL,
       ASIS_SEC_PERSONA,
       ASIS_SEC_HORARIO,
       ASIS_HORAINICIO,
	   ASIS_HORAFIN,
       ASIS_FECHAINGRESO,
       ASIS_RESPONSABLE,
       ASIS_ESTADO
          FROM ASISTENCIA WHERE ASIS_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
                ASIS_SECUENCIAL,
                ASIS_SEC_PERSONA,
                ASIS_SEC_HORARIO,
                ASIS_HORAINICIO,
                ASIS_HORAFIN,
                ASIS_FECHAINGRESO,
                ASIS_RESPONSABLE,
                ASIS_ESTADO
                      FROM ASISTENCIA WHERE ASIS_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
	      return $sol;
		}

	//funcion para crear un nuevo reporte o cabecera
    function agrAsistencia(){
			$sql="select to_char(SYSDATE,'DD/MM/YYYY HH24:MI:SS') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
            $ASIS_RESPONSABLE=$this->session->userdata('US_CODIGO');
			$ASIS_FECHAINGRESO="TO_DATE('".$nsol[0]."','DD/MM/YYYY HH24:MI:SS')";
			
			//VARIABLES DE INGRESO
            $ASIS_SEC_PERSONA=$this->input->post('persona');						
            $ASIS_SEC_HORARIO=$this->input->post('horario');
            $HORA_INICIO=prepCampoAlmacenar($this->input->post('HORA_INICIO'));
            $MINUTO_INICIO=prepCampoAlmacenar($this->input->post('MINUTO_INICIO'));
            if(!empty($HORA_INICIO) and !empty($MINUTO_INICIO)){
                $ASIS_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":".$MINUTO_INICIO);
            }elseif(!empty($HORA_INICIO)){
                $ASIS_HORAINICIO = prepCampoAlmacenar("00:".$MINUTO_INICIO);
            }elseif(!empty($MINUTO_INICIO)){
                $ASIS_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":00");
            }else{
                $ASIS_HORAINICIO = prepCampoAlmacenar("00:00");
            }    
            $HORA_FIN=prepCampoAlmacenar($this->input->post('HORA_FIN'));
            $MINUTO_FIN=prepCampoAlmacenar($this->input->post('MINUTO_FIN'));
            if(!empty($HORA_FIN) and !empty($MINUTO_FIN)){
                $ASIS_HORAFIN = prepCampoAlmacenar($HORA_FIN.":".$MINUTO_FIN);
            }elseif(!empty($HORA_FIN)){
                $ASIS_HORAFIN = prepCampoAlmacenar("00:".$MINUTO_FIN);
            }elseif(!empty($MINUTO_FIN)){
                $ASIS_HORAFIN = prepCampoAlmacenar($HORA_FIN.":00");
            }else{
                $ASIS_HORAFIN = prepCampoAlmacenar("00:00");
            }
            
            $sqlASISTENCIAVALIDA="select count(*) NUM_ASISTENCIA from asistencia
            WHERE ASIS_sec_persona='{$ASIS_SEC_PERSONA }'
            and ASIS_SEC_HORARIO='{$ASIS_SEC_HORARIO}'
            and ASIS_ESTADO=0";
			$NUM_ASISTENCIA =$this->db->query($sqlASISTENCIAVALIDA)->row()->NUM_ASISTENCIA ;
			if($NUM_ASISTENCIA ==0){

				$sql="INSERT INTO ASISTENCIA (
					  ASIS_SEC_PERSONA,
                      ASIS_SEC_HORARIO,
                      ASIS_HORAINICIO,
                      ASIS_HORAFIN,
                      ASIS_FECHAINGRESO,
                      ASIS_RESPONSABLE,
                      ASIS_ESTADO) VALUES(
                            $ASIS_SEC_PERSONA,
                            $ASIS_SEC_HORARIO,
                            '$ASIS_HORAINICIO',
                            '$ASIS_HORAFIN',
                            $ASIS_FECHAINGRESO,
                            '$ASIS_RESPONSABLE',
						    0)";
        $this->db->query($sql);
        //print_r($sql);
        $ASIS_SECUENCIAL=$this->db->query("select max(ASIS_SECUENCIAL) SECUENCIAL from ASISTENCIA")->row()->SECUENCIAL;
        echo json_encode(array("cod"=>$ASIS_SECUENCIAL,"numero"=>$ASIS_SECUENCIAL,"mensaje"=>"Asistencia: ".$ASIS_SECUENCIAL.", insertada con éxito"));    
    }else{     
        echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La asistencia Ya Existe...!!!"));
    }
}
    
	//funcion para editar un registro selccionado
    function editAsistencia(){
			$ASIS_SECUENCIAL=$this->input->post('ASIS_SECUENCIAL');
			
			//VARIABLES DE INGRESO
			$ASIS_SEC_PERSONA=$this->input->post('persona');
            $ASIS_SEC_HORARIO=$this->input->post('horario');
            $HORA_INICIO=prepCampoAlmacenar($this->input->post('HORA_INICIO'));
            $MINUTO_INICIO=prepCampoAlmacenar($this->input->post('MINUTO_INICIO'));
            if(!empty($HORA_INICIO) and !empty($MINUTO_INICIO)){
                $ASIS_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":".$MINUTO_INICIO);
            }elseif(!empty($HORA_INICIO)){
                $ASIS_HORAINICIO = prepCampoAlmacenar("00:".$MINUTO_INICIO);
            }elseif(!empty($MINUTO_INICIO)){
                $ASIS_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":00");
            }else{
                $ASIS_HORAINICIO = prepCampoAlmacenar($HORA_INICIO.":".$MINUTO_INICIO);
            }    
            $HORA_FIN=prepCampoAlmacenar($this->input->post('HORA_FIN'));
            $MINUTO_FIN=prepCampoAlmacenar($this->input->post('MINUTO_FIN'));
            if(!empty($HORA_FIN) and !empty($MINUTO_FIN)){
                $ASIS_HORAFIN = prepCampoAlmacenar($HORA_FIN.":".$MINUTO_FIN);
            }elseif(!empty($HORA_FIN)){
                $ASIS_HORAFIN = prepCampoAlmacenar("00:".$MINUTO_FIN);
            }elseif(!empty($MINUTO_FIN)){
                $ASIS_HORAFIN = prepCampoAlmacenar($HORA_FIN.":00");
            }else{
                $ASIS_HORAFIN = prepCampoAlmacenar($HORA_FIN.":".$MINUTO_FIN);
            }

				$sql="UPDATE ASISTENCIA SET
							ASIS_SEC_PERSONA=$ASIS_SEC_PERSONA,
							ASIS_SEC_HORARIO=$ASIS_SEC_HORARIO,
                            ASIS_HORAINICIO='$ASIS_HORAINICIO',
							ASIS_HORAFIN='$ASIS_HORAFIN'
							WHERE ASIS_SECUENCIAL=$ASIS_SECUENCIAL";
		$this->db->query($sql);
               //print_r($sql);
		 $ASIS_SECUENCIAL=$this->db->query("select max(ASIS_SECUENCIAL) SECUENCIAL from ASISTENCIA")->row()->SECUENCIAL;
		 echo json_encode(array("cod"=>$ASIS_SECUENCIAL,"numero"=>$ASIS_SECUENCIAL,"mensaje"=>"Asistencia: ".$ASIS_SECUENCIAL.", editado con éxito"));    
	
   }    
}
?>