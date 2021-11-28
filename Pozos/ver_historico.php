<?php 
    include_once("functionality/queries.php");
    include_once("functionality/customs.php");
    $id = 0;
    $pozo = "";
    $dataPoints = [];
    if(is_set_empty(['ID'],$_GET)){
        $id = $_GET['ID'];
        $query = make_query("SELECT * FROM pozos WHERE id_pozo=$id");
        if(mysqli_num_rows($query)>0){
            $arr = mysqli_fetch_array($query);
            $pozo = new Pozo($arr['nombre'],$arr['profundidad']);
            $pozo->id = $id;
            $query = make_query("SELECT SUM(valor) AS valor,fecha,id_medicion FROM `mediciones` WHERE id_pozo=$id GROUP BY fecha ORDER BY fecha"); 
            $resMed = get_mediciones_from_query($query);
            if($resMed){
                $pozo->mediciones = $resMed;
                $dataPoints = toDataPoints($pozo->mediciones);
            }
        }
        else header("Location: index.php");
    }

    function toDataPoints($mediciones){
        $dataPoints = [];
        foreach($mediciones as $m) array_push($dataPoints,array("label"=>date("F j, Y",strtotime($m->fecha)),"y"=>$m->valor));
        return $dataPoints;   
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
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
    theme: "light2",
	title:{
		text: "Mediciones del Pozo <?php echo $pozo->nombre ;?>"
	},
	axisX:{
        title: "Fecha",
		crosshair: {
			enabled: true,
			snapToDataPoint: true,
            color: "#33558B",
			labelBackgroundColor: "#33558B"
		}
	},
	axisY:{
		title: "Valor",
        suffix: " psi",
		includeZero: true,
		crosshair: {
			enabled: true,
			snapToDataPoint: true
		}
	},
	toolTip:{
		enabled: false
	},
	data: [{
		type: "area",
        color: "<?php echo rand_color()?>",
		name: "Mediciones",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
<body>
    <div class='container'>
        
        <h2 class="text-white text-center mt-5">ID <?php echo $id ?></h2>
        <?php if(count($pozo->mediciones)<=0):?>
            <h2 class='text-white text-center'>No se han encontrado mediciones en este pozo!</h2>
            <a href="./añadir_medicion.php?ID=<?php echo $id?>" class='btn btn-success w-100'>Añadir Mediciones</a>
        <?php else: ?>
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        <?php endif ?>
        <a href="./ver_pozos.php" class='btn mt-2 btn-danger w-100'>Volver</a>
    </div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
