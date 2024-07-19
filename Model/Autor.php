<?php

require_once 'LibreriaBD.php';

class autor{
    private $id;
    private $dcAutor;

    function __construct($id, $dcAutor) {
        $this->$id;
        $this->$dcAutor;
      }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of dcAutor
     */ 
    public function getDcAutor()
    {
        return $this->dcAutor;
    }

    /**
     * Set the value of dcAutor
     *
     * @return  self
     */ 
    public function setDcAutor($dcAutor)
    {
        $this->dcAutor = $dcAutor;

        return $this;
    }
    
    public static function getAutores(){ 
        /* Función simple para coger los autores */
        $conexion = LibreriaBD::connectDB();
        $registros=[];
        $consulta = "SELECT ta.*, lib.idLibro 
        FROM tienda.autor ta
        LEFT OUTER JOIN tienda.libro lib ON lib.idAutor = ta.idAutor";
        $smt = $conexion->prepare($consulta);
        $smt->execute();
        $conexdb = null;
        while($row = $smt->fetch(PDO::FETCH_ASSOC)){
            $registros[$row['idAutor']] = array('dcAutor'=>$row['dcAutor'], 'idLibro'=>$row['idLibro']);
        }
        //var_dump($registros);
        return $registros;
    }

    public static function eliminarAutor($idAutor){ // Función para eliminar autores
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("DELETE FROM tienda.autor WHERE idAutor = ?;");
        $smt->bindParam(1,$idAutor, PDO::PARAM_INT); 
        $smt->execute();
        $conexdb = null;
    }

    public static function modificarAutor($idAutor, $dcAutor){ // Función para modificar Autores
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("UPDATE tienda.autor SET dcAutor = ? 
                                    WHERE idAutor = ?");
        $smt->bindParam(1,$dcAutor, PDO::PARAM_STR);
        $smt->bindParam(2,$idAutor, PDO::PARAM_INT); 
        $smt->execute();
        $conexdb = null;
    }

    public static function setAutor($dcAutor){ // Función para modificar Autores
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("INSERT INTO tienda.autor (dcAutor)  
                                    VALUES (?)");
        $smt->bindParam(1,$dcAutor, PDO::PARAM_STR);
        $smt->execute();
        $conexdb = null;
    }

    public static function validaAutor($idAutor, $dcAutor){ // Función para validar que no introduzcan códigos de libros repetidos
        $conexion = LibreriaBD::connectDB();
        $existe = false;
        $query="SELECT * FROM tienda.autor WHERE dcAutor = ?";
        if($idAutor && $idAutor !== ''){
            $query = $query." AND idAutor <> ?";
        }
        $smt = $conexion->prepare($query);
        $smt->bindParam(1,$dcAutor, PDO::PARAM_STR); 
        if($idAutor && $idAutor !== ''){
            $smt->bindParam(2,$idAutor, PDO::PARAM_INT); 
        }
        $smt->execute();
        $count = $smt->rowCount();
        if($count > 0){
            $existe = true;
        }
        $conexdb = null;
        return $existe;
    }
}
?>