<?php

class Admin extends Controlador{

    /**
     * Constructor por defecto
     *
     * 
     */
    public function __construct(){
        Sesion::iniciarSesion($this->datos);

        /* Define los roles autorizados y controla el acceso */
        $this->datos['rolesPermitidos'] = [10];
        if (!tienePrivilegios($this->datos['usuarioSesion']->rol,$this->datos['rolesPermitidos'])) {
            redireccionar('/inicio?res=unauthorized');
        }

        $this->comunModelo = $this->modelo('ComunModelo');
        
    }
    
    /**
     * Carga la vista por defecto
     *
     * @return [type]
     * 
     */
    public function index(){
        
    }
    
    public function descargarLogs() {
        $logs = $this->comunModelo->cargarLogs();

        $this->vista('common/descargarLogs',$logs);
    }

   
}
