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
        //session_start();
        $busqueda='';
        //session_destroy();
        if($admin){ //Si está creada la cookie, se trata de un administrador y cambiamos el color de la cabecera
            echo '<div class="cabecera" style="background-color:green !important;">';
        }else{
            echo '<div class="cabecera">';
        } 
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
                ?>
            <a href="usuario.php"><img src="../View/imagenes/icons/user-128.png" title="Usuario" class="imgCab"></a>
            <?php if($admin){ // Si está logado y es administrador, mostramos el acceso a la gestión de productos
                    echo '<a href="../Controller/administracion.php" style="color: wheat;margin-top: 5px;margin-left: 35px;">Administracion</a>';
                } 
            ?>
        </div>
    </div>
    <div style="margin-left:30px; width: 100%; display: contents;">
        <h1 style="padding-left:30px;">Selecciona los libros que quieres comprar</h1>
        <form name="busqueda" method="GET" action="?" style="padding-left: 30px;"> <!-- Formulario de busqueda -->
            <input type="text" name="busqueda" size="100" placeholder="búsqueda de libros">
            <select name="autores" id="autores">
                <option value="">selecciona Autor</option>;
                    <?php foreach($autor as $llave=>$value){ 
                        echo '<option value="'.$llave.'">'.$value['dcAutor'].'</option>';
                    }?>
            </select>
            <input type="submit" value="buscar" style="background-color: green;color: white;">
            <span>(Para buscar sin filtros, vacía la caja de búsqueda y pulsa buscar)</span>
            <input type="hidden" name="formulario" value="busqueda">
            <input type="hidden" name="pagina" value="catalogo">
        </form>
    </div>
    <div class="cuerpo">
        <?php 
            //$registros = getProductos($busqueda);
            foreach($registros as $llave=>$value){ // Formulario de productos
                $stockProcucto=$registros[$llave]['stock'];
                if (isset($_SESSION['productos']) && array_key_exists($registros[$llave]['codigo'],$_SESSION['productos'])) {
                    $stockProcucto=$_SESSION['productos'][$registros[$llave]['codigo']]["stock"];
                }
                echo '<div class="caja">
                     <img style="height: 180px;" src="../View/imagenes/'.$registros[$llave]['img'].'">
                     <span style="position: absolute;padding-top: 20px;padding-left: 10px;">'.
                    $registros[$llave]['dcAutor'].'<br>'.
                    $registros[$llave]['titulo'].'</span> 
                    <span style="position: absolute;width: 20%;padding-left: 10px;padding-top: 50px;padding-top: 70px;">'.$registros[$llave]['descripcion'].'</span><br>
                    <span style="float: right;font-weight: bold;">Precio: '.$registros[$llave]['precio'].' €</span> <br>';
                    if($stockProcucto>0){
                        $arr = htmlspecialchars(json_encode($registros[$llave]));
                        echo '<p style="float:right; marging-bottom:5px;">Stock: '.$stockProcucto.'</p>
                        <img class="imgCab imgCesta" src="../View/imagenes/icons/4379542.png" title="Añadir a la Cesta" Onclick="aniadirProducto('.$arr.','.$stockProcucto.','.$llave.')">';
                    }else{
                        echo '<span class="imgCab imgCesta" style="font-weight:bold; color:red;">Sin Stock</span>';
                    }
                    
                echo '</div>'; 
            }
            echo '<form action="" method="post" id="myform"> 
                <input type="hidden" name="formulario" value="recargar" />
            </form>';
        ?>
    </div>
    <script>
        function aniadirProducto(id, stock, idLibro){ // Función para añadir productos a la cesta de la compra
            //console.log('fasdf',idLibro);
            codigo = id.codigo;
            img = id.img;
            titulo = id.titulo;
            precio = id.precio;
            data= { 
                    codigo: id.codigo,
                    img: id.img,
                    titulo: id.titulo,
                    precio: id.precio,
                    stock:stock,
                    idLibro: idLibro,
                    formulario: 'aniadirItem'
                }
            $.ajaxSetup({
                cache: false
            });
            dataType: "json",
            $.ajax({
                type: "POST",
                url: ".",
                data: data,
                success: function(data) {              
                    $('#myform').submit();
                },
                error: function(data, textStatus, XMLHttpRequest)
                {
                        alert(textStatus);
                }
            });
        }
                /* dataType: "json", */
    </script>
</body>
</html>
</body>
</html>
