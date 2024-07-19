<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../View/css/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Feedback Unidad 5 - Tienda Online</title>
</head>
<body>
    <?php
        echo '<div class="cabecera" style="background-color:green !important;">';
    ?>
        <h1 id="cab">Tienda Online de Libros</h1>
        <div class="opciones">
            <a href="cesta.php"><img  src="../View/imagenes/icons/834526.png" title="cesta de la compra" class="imgCab"></a>
            <?php 
           
                if(isset($_SESSION['cuantos'])){ //Si ya hay productos en la Cesta, mostramos el número, si no, mostramos 0.
                    echo ' <span style="padding-right: 35px; padding-left:5px;"> ('.$_SESSION['cuantos'].')</span>';
                }else{
                    echo ' <span style="padding-right: 35px; padding-left:5px;"> (0)</span>';
                } 
                echo '<a href="usuario.php"><img src="../View/imagenes/icons/user-128.png" title="Usuario" class="imgCab"></a>
                <a href="administracion.php" style="color: wheat;margin-top: 5px;margin-left: 35px;">Administracion</a>';
            ?>
        </div>
    </div>
    <div style="margin-left:30px; width: 100%; display: ruby;">
        <h1 style="padding-left:30px;">Gestión de Libros</h1>
    </div>
    <div class="cuerpo">
        <?php 
            $img='';
            $titulo='';
            $descripcion='';
            $precio=1;
            $stock=1;
            $idAutor='';
            if(count($libro)>0){ //Si es una edición, asignamos los valores a las variables
                foreach($libro as $llave=>$value){ 
                    $stock=$libro[$llave]['stock'];
                    $img=$libro[$llave]['img'];
                    $titulo=$libro[$llave]['titulo'];
                    $descripcion=$libro[$llave]['descripcion'];
                    $idAutor=$libro[$llave]['idAutor'];
                    $precio=$libro[$llave]['precio'];
                    $codigo=$libro[$llave]['codigo'];
                }
            }
            //Formulario de Alta/Edición
            echo '<div class="group" style="margin-top: 50px;">
                <h2><em>Alta/Edición de Libro</em></h2> 
                <div style="padding:10px;">
                    <h5><em>Alta/Edición de foto (si no pones foto, se mostrará una por defecto)</em></h5>  
                    <form action="gestion.php" method="POST" enctype="multipart/form-data"/>
                        <input name="archivo" id="archivo" type="file"/>
                        <input type="submit" name="subir" value="Subir imagen"/>
                        <input type="hidden" name="idAutor" value="'.$idAutor.'">
                    </form>
                </div>
                <div>
                    <label for="Autor">Autor <span>
                    <form action="../Controller/administracion.php" method="POST" name="formEdit">';
                        if($img || $archivo){
                            if($archivo){
                                echo '<img name="img" style="width:60px; padding-left:20px; padding-top:5px;" src="../View/imagenes/'.$archivo.'">
                                <input type="hidden" name="img" value="'.$archivo.'">';
                            }else{
                                echo '<img name="img" style="width:60px; padding-left:20px; padding-top:5px;" src="../View/imagenes/'.$img.'">
                                <input type="hidden" name="img" value="'.$img.'">';
                            }
                        }else{
                            echo '<input type="hidden" name="img" value="">';
                        }
                        echo '<select name="autores" id="autores">';
                                foreach($autor as $llave=>$value){ 
                                    echo '<option '; if($idAutor && $idAutor === $llave){echo ' selected="selected "';} echo ' value="'.$llave.'">'.$value.'</option>';
                                }
                        echo '</select>
                        <input type="hidden" name="formulario" value="altaEdicion">
                        <input type="hidden" name="claveLibro" value="'.$claveLibro.'">
                        <label style="margin-top: 20px;" for="codigo">codigo <span><em>(requerido)</em></span></label>
                        <input type="text" class="form-input" name="codigo" required value="'.$codigo.'" />
                        <label style="margin-top: 20px;"for="titulo">Título <span><em>(requerido)</em></span></label>
                        <input type="text" name="titulo" class="form-input" required value="'.$titulo.'" />
                        <label style="margin-top: 20px;" for="precio">Precio <span><em>(requerido)</em></span></label>
                        <input type="number" class="form-input" min="0" step="0.01" name="precio" value="'.$precio.'" />
                        <label style="margin-top: 20px;" for="Stock">Stock <span><em>(requerido)</em></span></label>
                        <input type="number" class="form-input" min="0" name="stock" required value="'.$stock.'" /> 
                        <label style="margin-top: 20px;" for="descripcion">Descripción <span><em>(requerido)</em></span></label>
                        <textarea id="descripcion" name="descripcion" rows="4" cols="50" required>'.$descripcion.'</textarea>   
                        <p><center> <input class="form-btn" name="aceptar" type="submit" value="Aceptar" /> 
                        <input  style="background-color: red;float: right;height: 32px;width: 71px;
                        color: wheat;" name="cancelar" type="button" value="Cancelar" onclick="window.location.href=\'administracion.php\';"/></center>
                        </p>
                    </form>
                </div>
            </div>';
        ?>
    </div>
</body>
</html>
</body>
</html>
