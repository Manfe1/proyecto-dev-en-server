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
        <h1 style="padding-left:30px;">Administración de Almacén</h1>
        <a href="../Controller/autores.php" style="float:right; color: green;margin-top: 10px;padding-right: 120px;">Gestión de Autores</a>
        <form name="alta" id="alta" method="POST" action="../Controller/gestion.php" style="float: right;padding-right: 120px;margin-top: 30px;">
            <input type="hidden" name="formulario" value="alta">
            <input type="submit" style="background-color:green; height:40px; color:white; width: 120px;" name="alta" id="alta" value="Alta de libro">
        </form>
    </div>
    <div class="cuerpo">
        <?php 
            foreach($registros as $llave=>$value){ // Formulario de productos
                $stockProcucto=$registros[$llave]['stock'];
                echo '<div class="caja">
                        <img style="height: 180px;" src="../View/imagenes/'.$registros[$llave]['img'].'">
                        <span style="position: absolute;padding-top: 20px;padding-left: 10px;">'.
                        $registros[$llave]['dcAutor'].'<br>'.
                        $registros[$llave]['titulo'].'</span> 
                        <span style="position: absolute;width: 20%;padding-left: 10px;padding-top: 50px;padding-top: 70px;">'.$registros[$llave]['descripcion'].'</span><br>
                        <span style="float: right;font-weight: bold;">Precio: '.$registros[$llave]['precio'].' €</span> <br>';
                        if($stockProcucto>0){
                            echo '<p style="float:right; marging-bottom:5px;">Stock: '.$stockProcucto.'</p>';
                        }else{
                            echo '<span style="float:right; padding-top:15px; font-weight:bold; color:red;">Sin Stock</span>';
                        }
                        echo '<div style="display: grid;margin-left: 50%;padding-top: 40px;">
                            <form action="../Controller/gestion.php" method="POST" id="edit">
                                <input style="width:100px; color:white; background-color:orange; margin-bottom: 15px;" type="submit" name="Modificar" value="Modificar">
                                <input type="hidden" name="idLibro" id="idLibro" value="'.$llave.'">
                                <input type="hidden" name="formulario" id="formulario" value="modificarItem">
                            </form>
                            <form action="../Controller/administracion.php" method="POST" id="eliminar">';
                            if(!$registros[$llave]['pedido']){
                                echo '<input style="width:100px; color:white; background-color:red;" type="button" name="Eliminar" value="Eliminar" onclick="eliminarItem('.$llave.')">';
                            }else{
                                echo '<input title="libro asociado a pedidos" style="width:100px; color:white; background-color:grey;" type="button" name="Eliminar" value="Eliminar" disabled>';
                            } 
                        echo '<input type="hidden" id="llave" name="idLibro" value="">
                              <input type="hidden" name="formulario" value="EliminarItem">
                        </form></div>
                </div>'; 
            }
        ?>
    </div>
    <script>
        function eliminarItem(idLibro){ 
            let eliminar = confirm("¿Deseas eliminar el libro?");
            if(eliminar){
                document.getElementById('llave').value = idLibro;
                document.getElementById('eliminar').submit();
            }
        }
    </script>
</body>
</html>
</body>
</html>
