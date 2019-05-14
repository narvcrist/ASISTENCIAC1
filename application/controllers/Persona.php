<?php
class Persona extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('mpersona');
        $this->load->model('mvarios');
    }
    
       function index(){
            $datos['aprobador']=$this->session->userdata('US_ADMINISTRADOR');
			$datos['usuario']=$this->session->userdata('US_USUARIO');
            $this->load->view("persona/js/index_pers_js",$datos);
            $this->load->view("persona/index_pers_v",$datos);
        }
        
        function getdatosItems(){
            echo  $this->mpersona->getdatosItems();
        }
        	
	//funcion para cear una nuevo estudiante
	public function nuevaPersona(){     
            $datos=$this->datos(null,'n');
            $datos['accion'] = 'n';
			$this->load->view("persona/js/persona_js",$datos);
            $this->load->view("persona/persona_v",$datos);            
        }
        
        //funcion para ver la informacion de un Estudiante
        function verPersona($accion=null){
            $numero = $this->input->post('NUMERO');
            if(!empty($numero)){
                $sol = $this->mpersona->dataPersona($numero);
                      $USER=$this->session->userdata('US_CODIGO');
                      if ($accion=='v'|$accion=='e'|$accion=='x'|$accion=='a'){
                            $datos=$this->datos($sol,$accion);
                            $datos['sol']=$sol;
                            $datos['accion'] = $accion;
                            $this->load->view("persona/persona_v",$datos);
                            $this->load->view("persona/js/persona_js",$datos);
                      } else {
                            echo alerta("La acción no es reconocida");
                      }
            }else{
                echo alerta("No se puede mostrar el formulario, debe seleccionar un estudiante para continuar.");
            }
        }
              
	//funcion para dar los valores a la cabecera tanto en nuevo, como al momento de editar
	function datos($sol,$accion){
        if ($accion=='n') {
			
			$datos=null;	
		} else {
			
			$datos=null;
        }
        return($datos);
     }
	
	    
	//Administra las fonciones de nuevo y editar en un Estudiante
    function admPersona($accion){
        switch($accion){
            case 'n':
                echo $this->mpersona->agrPersona();
                break;
            case 'e':
                echo $this->mpersona->editPersona();
                break;
        }        
    }
    
	//Cambia de estado a pasivo a un estudiante	
    function anulartoda(){
         $PER_SECUENCIAL=$this->input->post('NUMERO');
            $SQL="update PERSONA set PER_ESTADO=1 where PER_SECUENCIAL=$PER_SECUENCIAL"; 
            $this->db->query($SQL);
            echo json_encode(array("cod"=>1,"mensaje"=>highlight("Estudiante ".$PER_SECUENCIAL." Eliminado, correctamente"))); 
		} 
}
?>