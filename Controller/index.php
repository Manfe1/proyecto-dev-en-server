<?php
  require_once '../Model/Libro.php';
  require_once '../Model/Autor.php';
  session_start();
  $admin = false;
  if(true === array_key_exists('tienda', $_COOKIE) && strlen($_COOKIE['tienda']) > 0) {
      $admin = true;
  }
  // Obtiene el listado de Libros
    $autor = Autor::getAutores(); // Para el desplegable de búsqueda de autores
    $busqueda='';
    $busquedaAutor='';
    if($_SERVER['REQUEST_METHOD'] === 'GET'){ //EL usuario ha introducido un parámetro de búsqueda
      if(isset($_GET['formulario']) && $_GET['formulario'] === "busqueda"){
          $busqueda = $_GET['busqueda'];
          $busquedaAutor = $_GET['autores'];
      }
      }else if($_SERVER['REQUEST_METHOD'] === 'POST'){
          if($_POST['formulario'] === "cestaCompra"){ // Si viene de la cesta de la compra, actualizamos los valores de la cesta
              $cuantos = 0;
              foreach($_POST as $key => $value) {
                  if($key !== "formulario" && $key !== "seguir"){
                      $id= $key;
                      if($value == 0){
                          unset($_SESSION['productos'][$id]);
                      }else{
                          $stockFinal = intval($_SESSION['productos'][$id]['stock']) - (intval($value) - $_SESSION['productos'][$id]['cantidad']);
                          $_SESSION['productos'][$id]['cantidad'] = $value;
                          $_SESSION['productos'][$id]['stock'] = $stockFinal;
                          $cuantos = $cuantos + $value;
                      }
                  }
              }
          $_SESSION['cuantos'] = $cuantos;
        }else if($_POST['formulario'] === "aniadirItem"){ // Si viene de la misma página, creamos o aumentamos el número de pedidos
            $codViene=$_POST['codigo'];
            if (isset($_SESSION['productos']) && array_key_exists($_POST['codigo'],$_SESSION['productos'])) {
                $cant = intval($_SESSION['productos'][$_POST['codigo']]['cantidad'])+1; //Actualizamos la cantidad de productos
                $stockProd = intval($_POST['stock'])-1; // Actualizamos el Stock real de cada libro.
                $_SESSION['productos'][$_POST['codigo']]= array('idLibro'=>$_POST['idLibro'], 'precio'=>$_POST['precio'],'cantidad'=>$cant, 'titulo'=>$_POST['titulo'], 
                'img'=>$_POST['img'], 'stock'=>$stockProd);
            }else{
                $stockProd = intval($_POST['stock'])-1;
                $_SESSION['productos'][$_POST['codigo']]= array('idLibro'=>$_POST['idLibro'], 'precio'=>$_POST['precio'],'cantidad'=>1, 'titulo'=>$_POST['titulo'], 
                'img'=>$_POST['img'], 'stock'=>$stockProd);
            }
            if(isset($_SESSION['cuantos'])){
                $_SESSION['cuantos']++;
            }else{
                $_SESSION['cuantos'] = 1;
            }
        }
    }
    $registros = Libro::getProductos($busqueda, $busquedaAutor);

    // Carga la vista de listado
    include '../View/catalogo.php';

