<?php
require_once '../Model/Autor.php';
// Carga la vista de la gestión de Autores
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['formularioEdit']) && $_POST['formularioEdit'] === "eliminarItem"){ //Se pide la eliminación de un autor
        Autor::eliminarAutor($_POST['idAutor']);
    }else if(isset($_POST['formulario']) || isset($_POST['formularioEdit'])){ // Alta o edición de un autor
        $id = isset($_POST['idAutor']) ? $_POST['idAutor']:''; // Si existe id, es una modificación, si no, es un alta
        $existe = Autor::validaAutor($id, $_POST['autorEdit']); //Comprobamos si existe ya el autor
        if($existe){
            echo "<script type='text/javascript'>alert('El autor aportado ya existe');</script>";
        }else{
            if(isset($_POST['idAutor']) && $_POST['idAutor'] !==''){
                Autor::modificarAutor($_POST['idAutor'], $_POST['autorEdit']);
            }else{
                Autor::setAutor($_POST['autorEdit']);
            }
            sleep(1);
        }
    }
}
$registros = Autor::getAutores();
include '../View/autores.php';