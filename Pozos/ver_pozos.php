<?php 
    include_once('functionality/queries.php');
    include_once('functionality/customs.php');

    if(is_set_empty(['ID','borrar'],$_GET)){
        $id = $_GET['ID'];
        make_query("DELETE FROM mediciones WHERE id_pozo=$id");
        make_query("DELETE FROM pozos WHERE id_pozo=$id");
        read_pozos();
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
                <a href="./index.php" class='btn btn-danger mb-5'>Volver</a>
                <?php if(count($pozos)<=0):?>
                    <div class="alert alert-danger col-6 alert-dismissible fade show text-center" role="alert">
                        <strong>Error!</strong> No hay ningun pozo creado
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="accordion" id="accordionPozos">
                <?php for($i = 0; $i<count($pozos);$i++): 
                    $pozo = $pozos[$i];
                    $num = $pozo->id;
                ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $num;?>">
                        <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $num;?>" aria-expanded="true" aria-controls="collapseOne">
                            <?php echo "#$num Pozo ".$pozo->nombre." - ".get_int($pozo->profundidad)."m";?>
                        </button>
                        </h2>
                        <div id="collapse<?php echo $num?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $num;?>" data-bs-parent="#accordionPozos">
                            <div class="accordion-body p-0">
                            <a href="./editar_pozo.php?ID=<?php echo $num?>" class='btn btn-success w-100 rounded-0 mb-1'>Editar</a>
                            <a href="#" onClick='eliminar(<?php echo $num;?>)' class='btn btn-danger w-100 rounded-0 mb-1'>Eliminar</a>
                            <a href="./ver_historico.php?ID=<?php echo $num;?>" class='btn btn-primary w-100 rounded-0'>Ver Histórico</a>
                            <h3 class='p-2'>Mediciones:</h3>
                            <?php if(count($pozo->mediciones)<=0):?>
                                <p class='text-danger'>No se han encontrado mediciones en este pozo</p>
                                <a href="./añadir_medicion.php?ID=<?php echo $num?>" class='btn btn-success'>Añadir Mediciones</a>
                            <?php endif ?>
                            <ul class="list-group p-2">
                                <?php for($j = 0; $j<count($pozo->mediciones); $j++): 
                                    $medicion = $pozo->mediciones[$j];
                                    $id_medicion = $medicion->id;
                                ?>  
                                    <li class="list-group-item list-group-item-primary"><?php echo "#".$id_medicion ?></li>
                                    <li class="list-group-item"><?php echo "Valor: ".$medicion->valor." psi" ?></li>
                                    <?php if($medicion->fecha): ?>
                                    <li class="list-group-item"><?php echo "Fecha: ".date("F j, Y",strtotime($medicion->fecha))?></li>
                                    <?php else:?>
                                    <li class="list-group-item text-danger"><?php echo "Fecha: No se ha encontrado fecha" ?></li>
                                    <?php endif;?>
                                <?php endfor; ?>
                            </ul>

                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
                </div>
            </div>
        </div>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        function eliminar(id){
            var id = id;
            confirmar = confirm('Deseas borrar el registro?');
            if (confirmar){
                url = './ver_pozos.php?ID='+id+'&borrar=si';
                location.href=url;
                alert('Eliminado!, El registro se eliminó completamente!');
            }
            else alert('Cancelado. No se ha eliminado nada');
            return confirmar;
            windows.refresh();
        }
    </script>
</body>
</html>