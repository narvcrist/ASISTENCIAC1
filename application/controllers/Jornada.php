<?php
class Jornada extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mjornada');
        $this->load->model('mvarios');
    }
    
       function index(){
            $datos['aprobador']=$this->session->userdata('US_ADMINISTRADOR');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("jornada/js/index_jors_js",$datos);
            $this->load->view("jornada/index_jors_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mjornada->getdatosItems();
        }
        	
	//funcion para cear una nueva jornada
	public function nuevaJornada(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("jornada/js/jornada_js",$datos);
            $this->load->view("jornada/jornada_v",$datos);            
        }
        
        //funcion para ver la informacion de una jornada
        function verJornada($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mjornada->dataJornada($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("jornada/jornada_v",$datos);
                            $this->load->view("jornada/js/jornada_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar una jornada para continuar.");
            }
        }
        function datos($sol,$accion){
        if ($accion=='n') {
            //Caso para nueva jornada
            $datos['combo_jornada']=$this->cmb_jornada(null," style='width:200px;' id='JOR_NOMBRE'");

        }else{
 
			//Caso para la edicion de una jornada				
            $nombre=$sol->JOR_NOMBRE;
            $datos['combo_jornada']=$this->cmb_jornada($nombre,$sol->JOR_NOMBRE," style='width:200px;' id='JOR_NOMBRE'");				
            

            $HORAINICIO=$sol->JOR_HORAINICIO;
            $HORAINICIOARRAY=explode(':',$HORAINICIO);
            $sol->HORA_INICIO=$HORAINICIOARRAY[0];
            $sol->MINUTO_INICIO=$HORAINICIOARRAY[1];

            $HORAFIN=$sol->JOR_HORAFIN;
            $HORAFINARRAY=explode(':',$HORAFIN);
            $sol->HORA_FIN=$HORAFINARRAY[0];
            $sol->MINUTO_FIN=$HORAFINARRAY[1];
            //$datos=null;

        }
        return($datos);
    }

	
	//Combo para Jornada
    function  cmb_jornada($tipo = null, $attr = null) {
        $output = array();
        $output[null] = "Jornada";
        $output['Matutina'] = "Matutina";
        $output['Vespertina'] = "Vespertina";
        $output['Nocturna'] = "Nocturna";
        $output['Fin de Semana'] = "Fin de Semana";
        return form_dropdown('jornada', $output, $tipo, $attr);
    }
	
    
	//Administra las funciones de nuevo y editar en una jornada
    function admJornada($accion){
        switch($accion){
            case 'n':
                echo $this->mjornada->agrJornada();
                break;
            case 'e':
                echo $this->mjornada->editJornada();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un jornada	
    function anulartoda(){
         $JOR_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update JORNADA set JOR_ESTADO=1 where JOR_SECUENCIAL=$JOR_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Jornada ".$JOR_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>