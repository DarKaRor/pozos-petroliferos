<?php 
    include_once('functionality/queries.php');
    $res_type = 0;
    $message = '';

    # Recibiendo el método post, colocandolo en una variable de sesión, redireccionando. Para no poder volver a enviar los datos por error al refrescar.
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $_SESSION['postdata'] = $_POST;
        header('Location: '.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
        exit;
    }

    # Si se recibe una variable de sesión con los datos de post.
    if(isset($_SESSION['postdata'])){
        $_POST = $_SESSION['postdata'];
        $nombre = $_POST['nombre'];
        $profundidad = $_POST['profundidad'];
        $result = create_pozo($nombre,$profundidad);
        $res_type = $result[0] ? 1 : 2;
        $message = $result[1];
        unset($_SESSION['postdata']);
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
                    <div class="form-group py-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Escribe el nombre" required>
                    </div>
                    <div class="form-group py-4">
                        <label for="profundidad">Profundidad</label>
                        <input type="number" min=1 step="any" class="form-control" name="profundidad" placeholder="Escribe la profundidad" required>
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