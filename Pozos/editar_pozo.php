<?php
    include_once('functionality/queries.php');
    include_once('functionality/customs.php');
    $res_type = 0;
    $message = '';
    $id = 0;
    $nombre = '';
    $profundidad = '';
    $mediciones = [];

    function load_pozo($id){
        global $nombre;
        global $profundidad;
        global $mediciones;
        $query = make_query("SELECT * FROM pozos WHERE id_pozo=$id");
        if(mysqli_num_rows($query)<=0) header('Location: index.php');
        else{
            $arr = mysqli_fetch_array($query);
            $nombre = $arr['nombre'];
            $profundidad = $arr['profundidad'];
            $resMed = read_mediciones($id);
            if($resMed) $mediciones = $resMed;
        }
    }

    if(is_set_empty(['ID'],$_GET)){
        $id = $_GET['ID'];
        load_pozo($id);
        
    }

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $query = make_query("UPDATE pozos SET nombre='{$_POST['nombre']}',profundidad='{$_POST['profundidad']}' WHERE id_pozo=$id");
        if(isset($_POST['mediciones'])){
            foreach($_POST['mediciones'] as $id_medicion => $key){
                $fecha = $key['fecha'];
                if (empty($fecha)) $fecha = date($fmt_d);
                $query = make_query("UPDATE mediciones SET valor='{$key['valor']}',fecha='$fecha' WHERE id_medicion=$id_medicion");
            }
        }
        $res_type = 1;
        $message = "Editado correctamente.";
        load_pozo($id);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Pozos Petrolíferos</title>
</head>
<body>
    <div class='container'>
        <div class='row justify-content-center  mt-5'>
            <div class='col-6'>
                <a href="./index.php" class='btn btn-danger'>Volver</a>
                <form class='text-white' method='post'>
                    <h2>Id: <?php echo $id ?></h2>
                    <div class="form-group py-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Escribe el nombre" value="<?php echo $nombre ?>" required>
                    </div>
                    <div class="form-group py-4">
                        <label for="profundidad">Profundidad</label>
                        <input type="number" min=1 step="any" class="form-control" name="profundidad"  value="<?php echo $profundidad ?>" placeholder="Escribe la profundidad" required>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Mediciones
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionPozos">
                            <div class="accordion-body p-0">
                            <?php if(count($mediciones)<=0):?>
                                <p class='text-danger'>No se han encontrado mediciones en este pozo</p>
                                <a href="./añadir_medicion.php?ID=<?php echo $id?>" class='btn btn-success'>Añadir Mediciones</a>
                            <?php endif ?>
                            <ul class="list-group p-2">
                                <?php for($j = 0; $j<count($mediciones); $j++): 
                                    $medicion = $mediciones[$j];
                                    $id_medicion = $medicion->id;
                                ?>  
                                  <li class="list-group-item list-group-item-primary"><?php echo "#".$id_medicion ?></li>
                                    <div class="form-group py-4">
                                        <label for="valor" class='text-black'>Valor en PSI</label>
                                        <input type="number" min=1 step="any" class="form-control" name="mediciones[<?php echo $id_medicion?>][valor]" value="<?php echo $medicion->valor?>" placeholder="Escribe el valor" required>
                                    </div>
                                    <div class="form-group py-4">
                                        <label for="fecha" class='text-black'>Fecha (Opcional)</label>
                                        <input type="date" class="form-control" name="mediciones[<?php echo $id_medicion?>][fecha]" value='<?php echo $medicion->fecha?>'>
                                    </div>
                                <?php endfor; ?>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary my-4" name='btn' value='Enviar'>
                </form>
            </div>
            <?php if($res_type>0):?>
            <div class="w-100"></div>
            <?php 
                if($res_type == 1){
                    $color = 'success';
                    $exclamation = 'Todo correcto!';}
                else{
                    $color = 'danger';
                    $exclamation = 'Error';}
            ?>
                <div class="alert alert-<?php echo $color ?> col-6 alert-dismissible fade show text-center" role="alert">
                    <strong><?php echo $exclamation ?> </strong><?php echo $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>
        </div>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>