<?php
    require_once '../Model/Libro.php';
    require_once '../Model/Autor.php';
    // Carga la vista del administrador
    session_start();

    $libro=[];
    $claveLibro='';
    $archivo = '';
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formulario'])){
        if($_POST['formulario'] === "modificarItem"){ 
            $libro=Libro::getDatosLibro($_POST['idLibro']); // Recuperamos los datos del libro si es una edición
            $claveLibro=$_POST['idLibro']; //Guardamos la clave del libro
        }
    }
    //Si se quiere subir una imagen
    else if (isset($_POST['subir'])) {
        //Recogemos el archivo enviado por el formulario
        $archivo = $_FILES['archivo']['name'];
        //Si el archivo contiene algo y es diferente de vacio
        if (isset($archivo) && $archivo != "") {
            //Obtenemos algunos datos necesarios sobre el archivo
            $tipo = $_FILES['archivo']['type'];
            $tamano = $_FILES['archivo']['size'];
            $temp = $_FILES['archivo']['tmp_name'];
            //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
            if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
                echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
                - Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.</b></div>';
            }
            else {
                //Si la imagen es correcta en tamaño y tipo
                //Se intenta subir al servidor
                if (move_uploaded_file($temp, '../View/imagenes/'.$archivo)) {
                    //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                    //chmod('images/'.$archivo, 0777);
                    //Mostramos el mensaje de que se ha subido co éxito
                    //echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
                    //Mostramos la imagen subida
                    //echo '<p><img src="images/'.$archivo.'"></p>';
                }
                else {
                //Si no se ha podido subir la imagen, mostramos un mensaje de error
                echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
                }
            }
        }
    }
    $autor = Autor::getAutores(); // cogemos los Autores para mostrarlos en el Desplegable
include '../View/gestion.php';