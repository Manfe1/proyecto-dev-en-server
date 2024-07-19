<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../View/css/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Tienda Online</title>
</head>
<body>
    <?php
        echo '<div class="cabecera" style="background-color:green !important;">';
    ?>
        <h1 id="cab">Tienda Online de Libros</h1>
        <div class="opciones">
            <a href="../Controller/cesta.php"><img  src="../View/imagenes/icons/834526.png" title="cesta de la compra" class="imgCab"></a>
            <?php 
           
                if(isset($_SESSION['cuantos'])){ //Si ya hay productos en la Cesta, mostramos el número, si no, mostramos 0.
                    echo ' <span style="padding-right: 35px; padding-left:5px;"> ('.$_SESSION['cuantos'].')</span>';
                }else{
                    echo ' <span style="padding-right: 35px; padding-left:5px;"> (0)</span>';
                } 
                echo '<a href="../Controller/usuario.php"><img src="../View/imagenes/icons/user-128.png" title="Usuario" class="imgCab"></a>
                <a href="../Controller/administracion.php" style="color: wheat;margin-top: 5px;margin-left: 35px;">Administracion</a>';
            ?>
        </div>
    </div>
    <div style="margin-left:30px; width: 100%; display: ruby;">
        <h1 style="padding-left:30px;">GESTIÓN DE AUTORES</h1>
        <a href="../Controller/administracion.php" style="float:right; color: green;margin-top: 10px;padding-right: 120px;">Volver a Administración</a>
        <!-- Form de nuevo Autor -->
    </div>
    <div class="cuerpo">
        <form name="alta" id="alta" method="POST" action="../Controller/autores.php" style="margin-top: 30px;">
            <input type="hidden" name="formulario" value="alta">
            <label style="margin-top: 20px;" for="nuevo">Nuevo Autor <span><em>(requerido)</em></span></label>
            <input type="text" size="40" name="autorEdit" required value="" />
            <input type="submit" style="background-color:green; height:40px; color:white; width: 120px;" name="alta" id="alta" value="Alta de Autor">
        </form>
        <?php 
        echo '<div style=width: 600px;>
            <form action="../Controller/autores.php" method="POST" id="edit" name="edit" style="float:left; position:absolute;">';
            foreach($registros as $llave=>$value){ // Formulario de Edición y eliminación de Autores
                echo '<label style="margin-top: 5px;" for="autor">autor </label>
                    <input type="text" size="40" name="autorEdit" required value="'.$registros[$llave]['dcAutor'].'" id="'.$llave.'" />
                    <input style="color:white; background-color:orange; margin-bottom: 15px;" type="button" name="Modificar" value="Modificar"
                    onclick="editarItem('.$llave.')">
                    <input type="hidden" name="idAutor" id="idAutor" value="'.$llave.'">
                    <input type="hidden" name="formularioEdit" id="formularioEdit" value="">';
                          
                if(!$registros[$llave]['idLibro']){
                    echo '<input style="color:white; background-color:red;" type="button" name="Eliminar" value="Eliminar" onclick="eliminarItem('.$llave.')">';
                }else{
                    echo '<input title="Autor Asociado a Libros" style="width:100px; color:white; background-color:grey;" type="button" name="Eliminar" value="Eliminar" disabled>';
                } 
                        
            }
            echo '</form>
                </div>'; 
        ?>
    </div>
    <script>
        function eliminarItem(idAutor){ 
            let eliminar = confirm("¿Deseas eliminar el Autor?");
            if(eliminar){
                $('input[name="formularioEdit"]').val('eliminarItem');
                document.getElementById('edit').submit();
            }
        }
        function editarItem(idAutor){ 
            $('input[name="formularioEdit"]').val('modificarItem');
            document.getElementById('edit').submit();
        }
    </script>
</body>
</html>
</body>
</html>
