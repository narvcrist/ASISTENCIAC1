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
                                            "(SELECT CONCAT(CONCAT(CONCAT(HOR_DIA,' '), HOR_HORAINICIO),HOR_FIN)
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
       to_char(ASIS_HORAINICIO,'DD-MM-YYY HH24:MI:SS') ASIS_HORAINICIO,
	   to_char(ASIS_HORAFIN,'DD-MM-YYY HH24:MI:SS') ASIS_HORAFIN,
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
			$ASIS_HORAINICIO="TO_DATE('".$HORAINICIO."','DD/MM/YYYY HH24:MI:SS')";
			$ASIS_HORAFIN="TO_DATE('".$HORAFIN."','DD/MM/YYYY HH24:MI:SS')";
			
			//VARIABLES DE INGRESO
            $ASIS_SEC_PERSONA=$this->input->post('persona');						
            $ASIS_SEC_HORARIO=$this->input->post('horario');
            $HORAINICIO =prepCampoAlmacenar($this->input->post('ASIS_HORAINICIO'));	
            $HORAFIN =prepCampoAlmacenar($this->input->post('ASIS_HORAFIN'));	
            
            				
			
			/*if (!empty($HORAINICIO) and !empty($HORAFIN)){
				$ASIS_HORAINICIO ="TO_DATE('$HORAINICIO 00:00:00', 'dd/mm/yy HH24:MI:SS')";
				$ASIS_HORAFIN ="TO_DATE('$HORAFIN 23:59:59', 'dd/mm/yy HH24:MI:SS')";              
			}else{
				$ASIS_HORAINICIO =null;
				$ASIS_HORAFIN = null;
			}*/

            $sqlASISTENCIAVALIDA="select count(*) NUM_ASISTENCIA from asistencia
            WHERE ASIS_sec_persona='{$ASIS_SEC_PERSONA }'
            and ASIS_sec_matricula='{$ASIS_SEC_HORARIO}'
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
                            $ASIS_HORAINICIO,
                            $ASIS_HORAFIN,
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
            $HORAINICIO =prepCampoAlmacenar($this->input->post('ASIS_HORAINICIO'));			
            $ASIS_HORAINICIO="TO_DATE('".$HORAINICIO."','DD/MM/YYYY HH24:MI:SS')";
            $HORAFIN =prepCampoAlmacenar($this->input->post('ASIS_HORAFIN'));
            $ASIS_HORAFIN="TO_DATE('".$HORAFIN."','DD/MM/YYYY HH24:MI:SS')";		
			
			
			$sqlREPETICION1="select ASIS_SECUENCIAL,ASIS_SEC_HORARIO 
							from asistencia
							where ASIS_SECUENCIAL='{$ASIS_SECUENCIAL}'
							and ASIS_estado=0";
			$repe1 =$this->db->query($sqlREPETICION1)->row();
			
			$sqlREPETICION2="select ASIS_SECUENCIAL,ASIS_SEC_HORARIO 
							from asistencia
							where ASIS_SEC_MATRICULA='{$ASIS_SEC_HORARIO}'
							and ASIS_estado=0";
			$repe2 =$this->db->query($sqlREPETICION2)->row();

			$sqlREPETICION="select count(*) NUM_REPETICION
							from asistencia
							where ASIS_SEC_MATRICULA='{$ASIS_SEC_HORARIO}'
							and ASIS_estado=0";
			$NUM_REPETICION =$this->db->query($sqlREPETICION)->row()->NUM_REPETICION;
			
		if(($repe1->ASIS_SECUENCIAL==$repe2->ASIS_SECUENCIAL) or ($NUM_REPETICION==0)){
            
				$sql="UPDATE ASISTENCIA SET
							ASIS_SEC_PERSONA=$ASIS_SEC_PERSONA,
							ASIS_SEC_MATRICULA=$ASIS_SEC_HORARIO,
                            ASIS_HORAINICIO=$ASIS_HORAINICIO,
							ASIS_HORAFIN=$ASIS_HORAFIN
							WHERE ASIS_SECUENCIAL=$ASIS_SECUENCIAL";
	
		$this->db->query($sql);
               //print_r($sql);
		 $ASIS_SECUENCIAL=$this->db->query("select max(ASIS_SECUENCIAL) SECUENCIAL from ASISTENCIA")->row()->SECUENCIAL;
		 echo json_encode(array("cod"=>$ASIS_SECUENCIAL,"numero"=>$ASIS_SECUENCIAL,"mensaje"=>"Asistencia: ".$ASIS_SECUENCIAL.", editado con éxito"));    
	}else{     
		 echo json_encode(array("cod"=>1,"numero"=>1,"mensaje"=>"!!!...La asistencia ya existe ...!!!"));
                
	}
   }    
}
?>