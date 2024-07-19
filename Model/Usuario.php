<?php

require_once 'LibreriaBD.php';

class Usuario{
    private $idUsuario;
    private $nombre;
    private $email;
    private $contrasena;
    private $ibAdmin;

    function __construct($idUsuario,$nombre,$email,$contrasena,$ibAdmin) {
        $this->$idUsuario;
        $this->$nombre;
        $this->$email;
        $this->$contrasena;
        $this->$ibAdmin;
      }

    /**
     * Get the value of idUsuario
     */ 
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */ 
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of contrasena
     */ 
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set the value of contrasena
     *
     * @return  self
     */ 
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get the value of ibAdmin
     */ 
    public function getIbAdmin()
    {
        return $this->ibAdmin;
    }

    /**
     * Set the value of ibAdmin
     *
     * @return  self
     */ 
    public function setIbAdmin($ibAdmin)
    {
        $this->ibAdmin = $ibAdmin;

        return $this;
    }

    public static function finalizarCompra(){
        $conexion = LibreriaBD::connectDB();
        $totalAPagar=0;
        $totalProductos=0;
        foreach($_SESSION['productos'] as $clave=>$value){ //Calculamos los totales
            $totalAPagar = $totalAPagar + intval($value['precio']);
            $totalProductos=$totalProductos+intval($value['cantidad']);
        }
        try {//Insertamos la cabecera del Pedido
            $smt = $conexion->prepare("INSERT INTO tienda.cestacabecera(idUsuario, TotalProductos, totalPrecio)
                                VALUES(?,?,?)");
            $smt->bindParam(1,$_SESSION['idUsuario'], PDO::PARAM_INT);
            $smt->bindParam(2,$totalProductos, PDO::PARAM_INT);
            $smt->bindParam(3,$totalAPagar, PDO::PARAM_STR);
            $smt->execute();
            $last_id = $conexion->lastInsertId(); // Cogemos el último id generado
    
            //Insertamos las líneas del Pedido en la tabla Detalle
            foreach($_SESSION['productos'] as $clave=>$value){ 
                $smt = $conexion->prepare("INSERT INTO tienda.cestadetalle(idCesta, idLibro, cantidad, precio)
                                            VALUES(?,?,?,?);");
                $smt->bindParam(1,$last_id, PDO::PARAM_INT);
                $smt->bindParam(2,$value['idLibro'], PDO::PARAM_INT);
                $smt->bindParam(3,$value['cantidad'], PDO::PARAM_INT);
                $smt->bindParam(4,$value['precio'], PDO::PARAM_STR);
                $smt->execute();
            }
            $conexdb = null;
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getPedidos(){ 
        /* Función para extraer todos los Pedidos de un usuario */
        $conexion = LibreriaBD::connectDB();
        $registros=[];
        $consulta = "SELECT tc.idCesta, tc.totalPrecio, tc.totalProductos, tc.fecha,
           tcd.cantidad, tcd.precio, au.dcAutor,  lib.titulo, tcd.idPedido
            FROM tienda.usuarios tu 
            INNER JOIN tienda.cestacabecera tc ON tc.idUsuario = tu.idUsuario
            INNER JOIN tienda.cestadetalle tcd ON tcd.idCesta = tc.idCesta
            INNER JOIN tienda.libro lib ON lib.idLibro = tcd.idLibro
            INNER JOIN tienda.autor au ON au.idAutor = lib.idAutor
            WHERE tu.idUsuario = ?";
        $smt = $conexion->prepare($consulta);
        $smt->bindParam(1,$_SESSION['idUsuario'], PDO::PARAM_INT); 
        $smt->execute();
        $conexdb = null;
        while($row = $smt->fetch(PDO::FETCH_ASSOC)){
            //echo $row['titulo'].'<br>';
             $registros[$row['idPedido']] = array('dcAutor'=>$row['dcAutor'], 'titulo'=>$row['titulo'], 
            'precio'=>$row['precio'], 'totalPrecio'=>$row['totalPrecio'],'totalProductos'=>$row['totalProductos'],'fecha'=>$row['fecha'],
            'cantidad'=>$row['cantidad'],'precio'=>$row['precio'], 'idCesta'=>$row['idCesta']); 
        }
        return $registros;
    }

    public static function existeUsuario($email){ // Función para saber si existe el usuario antes de darle de alta (GET)
        $conexion = LibreriaBD::connectDB();
        $existe = false;
        $smt = $conexion->prepare("SELECT * FROM tienda.usuarios WHERE email = ?;");
        $smt->bindParam(1,$email, PDO::PARAM_STR); 
        $smt->execute();
        $count = $smt->rowCount();
        if($count > 0){
            $existe = true;
        }
        $conexdb = null;
        return $existe;
    }

    public static function identificacion($email, $contrasenia){ //Función para ver si existe el usuario y crear la Sesión
        $conexion = LibreriaBD::connectDB();
        $usuario = [];
        $smt = $conexion->prepare("SELECT * FROM tienda.usuarios WHERE email = ?;");
        $smt->bindParam(1,$email, PDO::PARAM_STR); 
        //$smt->bindParam(2,$contrasenia, PDO::PARAM_STR); 
        $smt->execute();
        $count = $smt->rowCount();
        if($count > 0){
            while($row = $smt->fetch(PDO::FETCH_ASSOC)){
                if(self::coincidenContrasenias($contrasenia,$row['contrasena'])){ // Comparamos la contraseña aportada con la de la bbdd
                    $usuario = array('nombre'=>$row['nombre'],'email'=>$row['email'], 'admin'=>$row['ibAdmin'], 'idUsuario'=>$row['idUsuario']);    
                }
            }
        }    
        $conexdb = null;
        return $usuario;
    }
    
    public static function altaUsuario($nombre, $email, $contrasenia){ //Alta del usuario. Previamente se ha codificado la contraseña con Hash
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("INSERT INTO tienda.usuarios(nombre, email, contrasena) VALUES(?,?,?);");
        $smt->bindParam(1,$nombre, PDO::PARAM_STR); 
        $smt->bindParam(2,$email, PDO::PARAM_STR); 
        $smt->bindParam(3,$contrasenia, PDO::PARAM_STR); 
        $smt->execute();
        return $conexion->lastInsertId();
        $conexdb = null;
    }
    
    function hashContrasenia($contrasenia) // Encriptado de la contraseña
    {
        return password_hash($contrasenia, PASSWORD_BCRYPT);
    }
    
    public static function contrasenaValida($contrasenia, $contraseniaRep) //Función para saber si coinciden las contraseñas aportadas en el Alta
    {
        return $contrasenia === $contraseniaRep;
    }
    
    public static function coincidenContrasenias($contrasenia, $contraseniaBD) // Función para saber si coinciden las contraseñas para la identificación de usuario
    {
        return password_verify($contrasenia, $contraseniaBD);
    }
}