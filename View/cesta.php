<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../View//css/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Tienda Online</title>
</head>
<body>
    <?php
        $logado = isset($_SESSION['email']) ? 1 : 0;
        if($admin){ //Si está creada la cookie, se trata de un administrador y cambiamos el color de la cabecera
            echo '<div class="cabecera" style="background-color:green !important;">';
        }else{
            echo '<div class="cabecera">';
        } 
    ?>
    <h1 id="cab"><a href="../Controller/index.php">Tienda Online de Libros</a></h1>
        <div class="opciones"> <!-- Enlaces a las distintas páginas -->
            <img  src="../View/imagenes/icons/834526.png" title="cesta de la compra" class="imgCab">
            <?php 
                if(isset($_SESSION['cuantos'])){
                    echo ' <span style="padding-right: 35px; padding-left:5px;"> ('.$_SESSION['cuantos'].')</span>';
                }else{
                    echo ' <span style="padding-right: 35px; padding-left:5px;"> (0)</span>';
                } 
                ?>
            <a href="../Controller/usuario.php"><img src="../View/imagenes/icons/user-128.png" title="Usuario" class="imgCab"></a>
            <?php if($admin){ // Si está logado y es administrador, mostramos el acceso a la gestión de productos
                    echo '<a href="../Controller/administracion.php" style="color: wheat;margin-top: 5px;margin-left: 35px;">Administracion</a>';
                } 
            ?>
        </div>
    </div>
    <div style="margin-left:30px; width: 100%; display: contents;">
        <h1 style="padding-left:30px;">Cesta de la Compra</h1>
    </div>
    <?php
        $cont = 0;
        if(isset($_SESSION['cuantos']) && $_SESSION['cuantos'] > 0){ // Si hay algún registro dado de alta, mostramos la Tabla
            $total=0;
            $precioTotal=0;
            //Formulario de productos de la cesta
            echo '<form action="../Controller/cesta.php" method="POST" name="cesta" id="cesta">  
            <input type="hidden" id="formulario" name="formulario" value="cestaCompra">
                    <div class="cuerpo"><table border="1"  style="margin-top:20px; border: 1px solid black; border-collapse: collapse; width:800px; padding:10px;">
                    <tr>
                        <th>&nbsp;</th>
                        <th>Producto</th>
                        <th style="width=20%">Precio</th>
                        <th style="width=20%">Cantidad</th>
                    </tr>';
                    
                    foreach($_SESSION['productos'] as $llave => $value){
                        if($llave !== 'rec'){ // No es necesario, pero por si acaso se ha colado el formulario en el array de prodcutos, lo quitamos
                            $total = $total + intval($value['cantidad']);
                            $precioTotal = $precioTotal + (floatval($value['precio'])*intval($value['cantidad']));
                            $stock = intval($value['stock']);
                            if($stock===0){ //Si el Stock es 0, la cantidad máxima de libros permitida es la elegida por el usuario.
                                $stock=intval($value['cantidad']);
                            }else{
                                $stock = intval($value['stock']) + intval($value['cantidad']); //Nº máximo de cantidades de cada producto
                            }
                            echo '<tr>
                                    <td><img style="width:60px; padding-left:20px; padding-top:5px;" src="../View/imagenes/'.$value['img'].'"></td>';
                            echo  '<td>'.$value['titulo'].'</td></td>
                            <td style="text-align:center;">'.$value['precio'].' € </td>
                            <td style="text-align:center;"><input id="'.$llave.'" name="'.$llave.'" min="0" max="'.$stock.'" type="number"
                             size="3" value="'.intval($value['cantidad']).'"></td></tr>';
                        }
                    }
                    /* Totales */
                   echo '<tr>
                        <td style="text-align:right; padding-right:40px;" colspan="4"> '.$total.' Productos <br> Coste Final: '. $precioTotal .' €</td></tr></table>
                   <input type="submit" id="rec" name="rec" onclick="recalcular()" value="Recalcular" style="margin-top:15px;"/>
                   <input type="submit" id="comprar" name="comprar" value="Finalizar Compra" onclick="finalizarCompra('.$logado.')" style="margin-left:20px; margin-top:15px;"/>
                   <input type="submit" id="seguir" name="seguir" onclick="seguirComprando()" value="Seguir Comprando"  style="margin-left:20px; margin-top:15px;"/>
                   </div>
                   </form>';
        }else{
            echo '<h2>No has añadido ningún artículo a la cesta</h2>';
        }
    ?>
</body>
<script>
    $('input').keypress(function(event){
    event.preventDefault();
});
    function recalcular(){ /* mostrar la página con los cambios realizados por el usuario */
        document.cesta.action = '../Controller/cesta.php';
        document.getElementById('cesta').submit()
    }

    function seguirComprando(){ /* Redirigir a la página de inicio */
        document.cesta.action = '../Controller/index.php';
        document.getElementById('cesta').submit()
    }

    function finalizarCompra(donde){ /* ir al alta/identificación para finalizar la compra */
        let redireccion = '../Controller/usuario.php';
        if(donde === 1){
            redireccion = '../Controller/pago.php';
        }
        document.cesta.action = redireccion;
        document.getElementById('cesta').submit()
    }

</script>
</html>