<?php
require_once '../Model/Libro.php';
require_once '../Model/Autor.php';
// Carga la vista del administrador
session_start();
$existe = false;
$admin = false;
if(true === array_key_exists('tienda', $_COOKIE) && strlen($_COOKIE['tienda']) > 0) {
    $admin = true;
}
//var_dump($_POST);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if($_POST['formulario'] === "EliminarItem"){ 
        Libro::eliminarLibro($_POST['idLibro']);
    }else if($_POST['formulario'] === "altaEdicion"){
        //var_dump($_POST['claveLibro']);
        $existe = Libro::validaCodigo($_POST['claveLibro'], $_POST['codigo']);
        if($existe){
            $libro = [];
            $claveLibro = '';
            $archivo='';
            echo "<script type='text/javascript'>alert('El código aportado ya existe');</script>";
            if(isset($_POST['claveLibro']) && $_POST['claveLibro'] !==''){
                $libro=Libro::getDatosLibro($_POST['claveLibro']); // Recuperamos los datos del libro si es una edición
                $claveLibro=$_POST['claveLibro']; //Guardamos la clave del libro
            }
            $autor = Autor::getAutores();
            include '../View/gestion.php';
            die();            
        }else{
            if(isset($_POST['claveLibro']) && $_POST['claveLibro'] !==''){
                Libro::modificarLibro($_POST['claveLibro'], $_POST['codigo'], $_POST['titulo'], $_POST['descripcion'], $_POST['img'], $_POST['precio'], $_POST['stock'], $_POST['autores']);
            }else{
                Libro::altaLibro($_POST['codigo'], $_POST['titulo'], $_POST['descripcion'], $_POST['img'], $_POST['precio'], $_POST['stock'], $_POST['autores']);
            }
            sleep(1);
        }
    }
}
$busqueda = '';
$registros = Libro::getProductos($busqueda);
//var_dump($registros);
include '../View/administracion.php';