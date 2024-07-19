<?php
  // Carga la vista de la cesta de la compra
  session_start();
  $admin = false;
  if(true === array_key_exists('tienda', $_COOKIE) && strlen($_COOKIE['tienda']) > 0) {
      $admin = true;
  }
  if($_SERVER['REQUEST_METHOD'] === 'POST'){ // si es un post, viene de la misma página y hay que actualizar las cantidades
    $cuantos = 0;
    foreach($_POST as $key => $value) {
        if($key !== "formulario" && $key !== "rec"){ //obviamos las keys que no se corresponden con Productos
            $id= $key;
            if($value == 0){ //Si el usuario ha puesto cantidad 0, eliminamos de la sesión el libro
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
  }
  include '../View/cesta.php';