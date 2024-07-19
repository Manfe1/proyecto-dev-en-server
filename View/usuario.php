<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Formulario de Registro</title>
<link rel="stylesheet" type="text/css" href="../View/css/estilos.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
 
<body>
<?php
    if($admin){ //Si está creada la cookie, se trata de un administrador y cambiamos el color de la cabecera
        echo '<div class="cabecera" style="background-color:green !important;">';
    }else{
        echo '<div class="cabecera">';
    } 
    ?>
    <h1 id="cab"><a href="../Controller/index.php">Tienda Online de Libros</a></h1>
    <div class="opciones">
        <a href="../Controller/cesta.php"><img  src="../View/imagenes/icons/834526.png" title="cesta de la compra" class="imgCab"></a>
        <?php 
            if(isset($_SESSION['cuantos'])){
                echo ' <span style="padding-right: 35px; padding-left:5px;"> ('.$_SESSION['cuantos'].')</span>';
            }else{
                echo ' <span style="padding-right: 35px; padding-left:5px;"> (0)</span>';
            } 
            if($admin){ // Si está logado y es administrador, mostramos el acceso a la gestión de productos
                    echo '<a href="../Controller/administracion.php" style="color: wheat;margin-top: 5px;margin-left: 35px;">Administracion</a>';
                } 
            ?>
    </div>
</div>
<div style="margin-left:30px; width: 100%; display: contents;">
        <h1 style="padding-left:30px;">Área de usuario</h1>
    </div>
<?php
    if($tipoAcceso==="identificado"){ //El usuario está identificado
        echo '<div class="group" style="margin-top: 50px;">
        <h2><em>Usuario Registrado</em></h2>  
            <label for="nombre">'.$_SESSION['nombre'].' <span><em></em></span></label>
            <label for="nombre">'.$_SESSION['email'].' <span><em></em></span></label>';
            if($origen === 'cesta'){
                echo '<input type="hidden" name="cesta" value="cesta" />';
            }
            if(count($pedidos)===0){
                echo '<p>Todavía no has realizado ningún pedido</p>';
            }else{
                $idPed = -1; //Nos creamos esta variable para mostrar solo una vez cada cabecera
                foreach($pedidos as $llave=>$value){
                    if($value['idCesta']  !== $idPed){
                        echo '<fieldset>
                        <legend style="background-color:blueviolet; color:white;">Información General</legend>
                            Nº Pedido: '.$value['idCesta'].'  
                            Fecha: '.$value['fecha'].'
                            Total Productos: '.$value['totalProductos'].'
                            Coste Total: '.$value['totalProductos'].'
                       </fieldset>';
                    }
                    echo '<fieldset>
                        <legend>Detalle del Pedido(libro)</legend>
                            Titulo: '.$value['titulo'].'  
                            Autor: '.$value['dcAutor'].'
                            Cantidad: '.$value['cantidad'].'
                            Precio: '.$value['precio'].'
                       </fieldset>';
                    $idPed = $value['idCesta'];
                }
            }
            echo '
        </form>
        <div style="padding-top: 29px; padding-left: 145px;">
            <span style="cursor:pointer; color:red;" onclick="desrgistroa()" >[Cerrar Sesión]</span>
        </div>
      </div>';
    }
    else if($tipoAcceso==="logarse"){ //Formulario para identificarse
        echo '<div class="group" style="margin-top: 50px;">
        <h2><em>Introduce tus datos de acceso</em></h2>  
        <form action="" method="POST">
            <input type="hidden" name="formulario" value="registrarme">
            ';
            if($origen === 'cesta'){
                echo '<input type="hidden" name="cesta" value="cesta" />';
            }
            echo '
            <label for="email">Email <span><em>(requerido)</em></span></label>
            <input type="email" name="email" class="form-input" required />
            <label for="password">Contraseña <span><em>(requerido)</em></span></label>
            <input type="password" name="password" class="form-input" required/>    
            <p><center> <input class="form-btn" name="logarse" type="submit" value="Logarme" /></center>
            </p>
        </form>
        <span style="cursor:pointer;" onclick="darAlta()" >No estoy registrado, ir a Alta</span>
        </div>';
    }else{ // Formulario para darse de alta
        echo '<div class="group" style="margin-top: 50px;">
        <h2><em>Formulario de Registro</em></h2>  
        <form action="'.$_SERVER['PHP_SELF'].'" method="POST">
            <input type="hidden" name="formulario" value="alta">
            ';
            if($origen === 'cesta'){
                echo '<input type="hidden" name="cesta" value="cesta" />';
            }
            echo '
            <label for="nombre">Nombre <span><em>(requerido)</em></span></label>
            <input type="text" name="nombre" class="form-input" required/>   
            <label for="email">Email <span><em>(requerido)</em></span></label>
            <input type="email" name="email" class="form-input" required />
            <label for="password">Contraseña <span><em>(requerido)</em></span></label>
            <input type="password" name="password" class="form-input" required/>    
            <label for="passwordRep">Repetir Contraseña <span><em>(requerido)</em></span></label>
            <input type="password" name="passwordRep" class="form-input" required/>    
            <p><center> <input class="form-btn" name="submit" type="submit" value="Suscribirse" /></center>
            </p>
        </form>
        <span style="cursor:pointer;" onclick="acceso()" >Ya estoy Registrado</span>
        </div>';
    }
    //Formularios ocultos para conservar las acciones y redirigir correctamente
    echo '<form action="" method="post" id="alta"> 
    <input type="hidden" name="formulario" value="darAlta" />';
    if($origen === 'cesta'){
        echo '<input type="hidden" name="cesta" value="cesta" />';
    }
    echo '</form>
    <form action="" method="post" id="registro"> 
        <input type="hidden" name="formulario" value="logarse" />';
        if($origen === 'cesta'){
            echo '<input type="hidden" name="cesta" value="cesta" />';
        }
        echo '
    </form>
    <form action="" method="post" id="deslogarse"> 
        <input type="hidden" name="formulario" value="deslogarse" />';
        if($origen === 'cesta'){
            echo '<input type="hidden" name="cesta" value="cesta" />';
        }
        echo '
    </form>';
        
?>
</body>
<script>
    function darAlta(){
        $.ajaxSetup({
                cache: false
            });
            $.ajax({
                type: "POST",
                url: "..Controller/usuario.php",
                data: { 
                    formulario: 'darAlta'
                },
                /* dataType: "json", */
                success: function(data) {              
                  $('#alta').submit();
                },
                error: function(data, textStatus, XMLHttpRequest)
                {
                        alert(textStatus);
                }
            });
        }

        function acceso(){
        $.ajaxSetup({
                cache: false
            });
            $.ajax({
                type: "POST",
                url: "..Controller/usuario.php",
                data: { 
                    formulario: 'darAlta'
                },
                /* dataType: "json", */
                success: function(data) {              
                  $('#registro').submit();
                },
                error: function(data, textStatus, XMLHttpRequest)
                {
                        alert(textStatus);
                }
            });
        }

        function desrgistroa(){
            $.ajaxSetup({
                cache: false
            });
            $.ajax({
                type: "POST",
                url: "../Controller/usuario.php",
                data: { 
                    formulario: 'deslogarse'
                },
                /* dataType: "json", */
                success: function(data) {              
                  $('#deslogarse').submit();
                },
                error: function(data, textStatus, XMLHttpRequest)
                {
                        alert(textStatus);
                }
            });
        }
</script>
</html>