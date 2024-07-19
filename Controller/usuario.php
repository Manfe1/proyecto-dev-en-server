<?php
  // Carga la vista del Usuario
  session_start();
  $admin = false;
  if(true === array_key_exists('tienda', $_COOKIE) && strlen($_COOKIE['tienda']) > 0) {
      $admin = true;
  }
  require_once '../Model/Usuario.php';
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['cesta'])){$origen = 'cesta';} // Cogemos el origen desde el alta por si hay que redirigir al Pago
        if($_POST['formulario'] === "pago"){ // Viene de Pagar
            $tipoAcceso="identificado";
            Usuario::finalizarCompra(); // Guardamos la compra en bbde
            unset($_SESSION['productos']); // eliminamos los productos de la Sesión
            unset($_SESSION['cuantos']); // eliminamos la cantidad de la sesión
        }
        if($_POST['formulario'] === "cestaCompra"){ // Cogemos el origen desde la identificación por si hay que redirigir al Pago
            $origen = 'cesta';
            $cuantos = 0;
            foreach($_POST as $key => $value) {
                if($key !== "formulario" && $key !== "comprar"){ //obviamos las keys que no se corresponden con Productos
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
        if($_POST['formulario'] === "deslogarse"){ // El usuario quiere deslogarse
            session_destroy();//Eliminamos la sesión
            if(true === array_key_exists('tienda', $_COOKIE) && strlen($_COOKIE['tienda']) > 0) {
                //Si el usuario que se loga es Admin, destruimos la cookie asignándole fecha de caducidad anterior a la hora actual.
                setcookie('tienda','',time()-100);
                $admin=false;
            }
        }else if($_POST['formulario'] === "registrarme"){ // Login
            $usuario = Usuario::identificacion($_POST['email'], $_POST['password']);
            if(count($usuario)>0){
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                if($usuario['admin']){ //Si el usuario es administrador, creamos la cookie
                    setcookie('tienda', 'Administrador', time()+3600);
                    $admin = true;
                }
                
            }else{
                echo "<script type='text/javascript'>alert('Usuario No localizado');</script>";
            }
        }else if($_POST['formulario'] === "alta"){ // Alta de usuario
            if(!Usuario::contrasenaValida($_POST['password'],$_POST['passwordRep'])){
                echo "<script type='text/javascript'>alert('las contraseñas no coinciden');</script>";
                $tipoAcceso="darseDeAlta";
            }else if(Usuario::existeUsuario($_POST['email'])){ //Si el email existe en bbdd damos una alerta de error
                echo "<script type='text/javascript'>alert('El usuario ya existe');</script>";
                $tipoAcceso="logarse";
            }else{
                $nuevoUsuario = Usuario::altaUsuario($_POST['nombre'],$_POST['email'],Usuario::hashContrasenia($_POST['password'])); // Si todo está bien, damos de alta al usuario y le logamos
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['nombre'] = $_POST['nombre'];
                $_SESSION['idUsuario'] = $nuevoUsuario;
            }
        }
    }
    $tipoAcceso='';
    $origen = 'directo';
    
    if($tipoAcceso === ''){ //Tipo de acceso por defecto
        $tipoAcceso="logarse";
    }
    if(isset($_SESSION['email'])){ // Cambio de tipo de acceso si está identificado
        $tipoAcceso="identificado";
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){ //cambio de tipo de acceso si el usuario quiere darse de alta
        if($_POST['formulario'] === "darAlta"){
            $tipoAcceso="alta";
        }
    }
    if($tipoAcceso==="identificado"){
        $pedidos = Usuario::getPedidos(); //Consultamos si tiene pedidos
    }
    if($origen === 'cesta' && isset($_SESSION['email'])){ // Si se loga y viene de la cesta para comprar, redirigimos al Pago
        include '../View/pago.php';
    }else{
        //header('Location: '.$_SERVER["PHP_SELF"], true, 303);
        include '../View/usuario.php';
    }