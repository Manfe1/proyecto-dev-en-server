<?php

require_once 'libreriaBD.php';

class autor{
    private $idCesta;
    private $idUsuario;
    private $totalProductos;
    private $totalPrecio;
    private $fecha;
    private $idLibro;
    private $cantidad;
    private $precio;
    private $idPedido;

    function __construct($idCesta,$idUsuario,$totalProductos,$totalPrecio,$fecha,$idLibro,$cantidad,$precio,$idPedido) {
        $this->$idCesta;
        $this->$idUsuario;
        $this->$totalProductos;
        $this->$totalPrecio;
        $this->$fecha;
        $this->$idLibro;
        $this->$cantidad;
        $this->$precio;
        $this->$idPedido;
      }

    /**
     * Get the value of idCesta
     */ 
    public function getIdCesta()
    {
        return $this->idCesta;
    }

    /**
     * Set the value of idCesta
     *
     * @return  self
     */ 
    public function setIdCesta($idCesta)
    {
        $this->idCesta = $idCesta;

        return $this;
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
     * Get the value of totalProductos
     */ 
    public function getTotalProductos()
    {
        return $this->totalProductos;
    }

    /**
     * Set the value of totalProductos
     *
     * @return  self
     */ 
    public function setTotalProductos($totalProductos)
    {
        $this->totalProductos = $totalProductos;

        return $this;
    }

    /**
     * Get the value of totalPrecio
     */ 
    public function getTotalPrecio()
    {
        return $this->totalPrecio;
    }

    /**
     * Set the value of totalPrecio
     *
     * @return  self
     */ 
    public function setTotalPrecio($totalPrecio)
    {
        $this->totalPrecio = $totalPrecio;

        return $this;
    }

    /**
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
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
     * Get the value of cantidad
     */ 
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

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
         * Get the value of idPedido
         */ 
        public function getIdPedido()
        {
                return $this->idPedido;
        }

        /**
         * Set the value of idPedido
         *
         * @return  self
         */ 
        public function setIdPedido($idPedido)
        {
                $this->idPedido = $idPedido;

                return $this;
        }


        function TotalCompra(){ // Función para saber el total a pagar en la cesta
            $conexion = LibreriaBD::connectDB();
            $totalAPagar=0;
            $variable = '';
            foreach($_SESSION as $clave=>$value){
                if($clave !=='cuantos' && $clave !=='nombre' && $clave !=='email' && $clave !=='idUsuario'){ // Excluimos las variables que no son de productos
                    $variable = $variable . "'".$clave."',";
                }
            }
            $varFinal = substr($variable,0,strlen($variable)-1);
            $smt = $conexion->prepare("SELECT au.idAutor, au.dcAutor, lib.codigo, lib.titulo, lib.descripcion,
                                        lib.img, lib.precio, lib.descripcion, lib.stock, lib.idLibro
                                        FROM tienda.autor au INNER JOIN tienda.libro lib ON au.idAutor = lib.idAutor 
                                        WHERE codigo IN($varFinal);");
            
            $smt->execute();
            $conexdb = null;
            while($row = $smt->fetch(PDO::FETCH_ASSOC)){
                $totalAPagar = $totalAPagar + $row['precio'];
            }
            return $totalAPagar;
        }


}