<?php

require_once 'LibreriaBD.php';

class Libro{
    private $idLibro;
    private $codigo;
    private $titulo;
    private $descripcion;
    private $img;
    private $precio;
    private $stock;
    private $idAutor;

    function __construct($idLibro,$codigo,$titulo,$descripcion,$img,$precio,$stock,$idAutor) {
        $this->$idLibro;
        $this->$codigo;
        $this->$titulo;
        $this->$descripcion;
        $this->$img;
        $this->$precio;
        $this->$stock;
        $this->$idAutor;
      }

    /**
     * Get the value of idLibro
     */ 
    public function getIdLibro()
    {
        return $this->idLibro;
    }

    /**
     * Set the value of idLibro
     *
     * @return  self
     */ 
    public function setIdLibro($idLibro)
    {
        $this->idLibro = $idLibro;

        return $this;
    }

    /**
     * Get the value of codigo
     */ 
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */ 
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get the value of titulo
     */ 
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */ 
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of descripcion
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */ 
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of img
     */ 
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set the value of img
     *
     * @return  self
     */ 
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get the value of precio
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */ 
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of stock
     */ 
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */ 
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

   
    /**
     * Get the value of idAutor
     */ 
    public function getIdAutor()
    {
        return $this->idAutor;
    }

    /**
     * Set the value of idAutor
     *
     * @return  self
     */ 
    public function setIdAutor($idAutor)
    {
        $this->idAutor = $idAutor;

        return $this;
    }

    public static function getProductos($busqueda, $busquedaAutor){ 
        /* Función para extraer todos los libros de la base de datos
        Si hay un filtro, se incluye en el where */
    
        //Hacemos un left join con lod pedidos para, en caso de estar asociado a un pedido, no mostrar el botón de Eliminar en la Administración
        $conexion = LibreriaBD::connectDB();
        $registros=[];
        $consulta = "SELECT DISTINCT au.idAutor, au.dcAutor, lib.codigo, lib.titulo, lib.descripcion,
         case when lib.img <> '' and lib.img is not null then lib.img else 'pordefcto.jpg' end as img, 
         lib.precio, lib.descripcion, lib.stock, lib.idLibro, cd.idLibro as pedido 
        FROM tienda.autor au 
        INNER JOIN tienda.libro lib ON au.idAutor = lib.idAutor
        LEFT OUTER JOIN tienda.cestaDetalle cd ON cd.idLibro = lib.idLibro";
        if($busqueda!=='' && $busquedaAutor!==''){
            $busqueda = '%'.$busqueda.'%';
            $consulta = $consulta." WHERE au.dcAutor like ? or lib.titulo like ? or lib.descripcion like ?
             AND au.idAutor = ?";
        }else if($busqueda!==''){
            $busqueda = '%'.$busqueda.'%';
            $consulta = $consulta." WHERE au.dcAutor like ? or lib.titulo like ? or lib.descripcion like ? ";
        }else if($busquedaAutor!==''){
            $consulta = $consulta." WHERE au.idAutor = ?";
        }
        $smt = $conexion->prepare($consulta);
        if($busqueda!=='' && $busquedaAutor!==''){
            $smt->bindParam(1,$busqueda, PDO::PARAM_STR); 
            $smt->bindParam(2,$busqueda, PDO::PARAM_STR); 
            $smt->bindParam(3,$busqueda, PDO::PARAM_STR); 
            $smt->bindParam(4,$busquedaAutor, PDO::PARAM_INT); 
        }else if($busqueda!==''){
            $smt->bindParam(1,$busqueda, PDO::PARAM_STR); 
            $smt->bindParam(2,$busqueda, PDO::PARAM_STR); 
            $smt->bindParam(3,$busqueda, PDO::PARAM_STR); 
        }else if($busquedaAutor!==''){
            $smt->bindParam(1,$busquedaAutor, PDO::PARAM_INT); 
        }
        $smt->execute();
        $conexdb = null;
        while($row = $smt->fetch(PDO::FETCH_ASSOC)){
            $registros[$row['idLibro']] = array('idAutor'=>$row['idAutor'], 'dcAutor'=>$row['dcAutor'], 'titulo'=>$row['titulo'], 
            'precio'=>$row['precio'], 'img'=>$row['img'], 'stock'=>$row['stock'], 'descripcion'=>$row['descripcion'], 'codigo'=>$row['codigo'],
            'pedido'=>$row['pedido']);
        }
        //var_dump($registros);
        return $registros;
    }

    public static function getDatosLibro($idLibro){ 
        /* Función simple para coger los autores */
        $conexion = LibreriaBD::connectDB();
        $registros=[];
        $consulta = "SELECT * FROM tienda.libro WHERE idLibro = ?";
        $smt = $conexion->prepare($consulta);
        $smt->bindParam(1,$idLibro, PDO::PARAM_INT);
        $smt->execute();
        $conexdb = null;
        while($row = $smt->fetch(PDO::FETCH_ASSOC)){
            $registros[$row['idLibro']] = array('idAutor'=>$row['idAutor'], 'titulo'=>$row['titulo'], 
                        'precio'=>$row['precio'], 'img'=>$row['img'], 'stock'=>$row['stock'], 'descripcion'=>$row['descripcion'], 
                        'codigo'=>$row['codigo']);
        }
        //var_dump($registros);
        return $registros;
    }   

    public static function eliminarLibro($idLibro){ // Función para eliminar libros
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("DELETE FROM tienda.libro WHERE idLibro = ?;");
        $smt->bindParam(1,$idLibro, PDO::PARAM_INT); 
        $smt->execute();
        $conexdb = null;
    }

    public static function altaLibro($codigo, $titulo, $descripcion, $img, $precio, $stock, $idAutor){ // Función para dar de alta libros
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("INSERT INTO tienda.libro(codigo, titulo, descripcion, img, precio, stock, idAutor)
                                    VALUES(?,?,?,?,?,?,?)");
        $smt->bindParam(1,$codigo, PDO::PARAM_STR);
        $smt->bindParam(2,$titulo, PDO::PARAM_STR);
        $smt->bindParam(3,$descripcion, PDO::PARAM_STR);
        $smt->bindParam(4,$img, PDO::PARAM_STR);
        $smt->bindParam(5,$precio, PDO::PARAM_STR);
        $smt->bindParam(6,$stock, PDO::PARAM_STR);
        $smt->bindParam(7,$idAutor,PDO::PARAM_INT);
        $smt->execute();
        $conexdb = null;
    }

    public static function modificarLibro($idLibro, $codigo, $titulo, $descripcion, $img, $precio, $stock, $idAutor){ // Función para modificar libros
        $conexion = LibreriaBD::connectDB();
        $smt = $conexion->prepare("UPDATE tienda.libro SET codigo = ?, titulo = ?, descripcion = ?, img = ?, precio = ?, stock = ?, idAutor = ? 
                                    WHERE idLibro = ?");
        $smt->bindParam(1,$codigo, PDO::PARAM_STR);
        $smt->bindParam(2,$titulo, PDO::PARAM_STR);
        $smt->bindParam(3,$descripcion, PDO::PARAM_STR);
        $smt->bindParam(4,$img, PDO::PARAM_STR);
        $smt->bindParam(5,$precio, PDO::PARAM_STR);
        $smt->bindParam(6,$stock, PDO::PARAM_STR);
        $smt->bindParam(7,$idAutor,PDO::PARAM_INT);
        $smt->bindParam(8,$idLibro, PDO::PARAM_INT); 
        $smt->execute();
        $conexdb = null;
    }

    public static function validaCodigo($claveLibro, $codigo){ // Función para validar que no introduzcan códigos de libros repetidos
        $conexion = LibreriaBD::connectDB();
        $existe = false;
        $query="SELECT * FROM tienda.libro WHERE codigo = ?";
        if($claveLibro && $claveLibro !== ''){
            $query = $query." AND idLibro <> ?";
        }
        $smt = $conexion->prepare($query);
        $smt->bindParam(1,$codigo, PDO::PARAM_STR); 
        if($claveLibro && $claveLibro !== ''){
            $smt->bindParam(2,$claveLibro, PDO::PARAM_INT); 
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