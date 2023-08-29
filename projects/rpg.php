<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<style>
    .attribute {
        padding:10px;
        margin:5px;
        background-color:#ccc;
        border-radius:100%;
        justify-content:center;
        align-items:center;
        text-align:center;
        font-weight:bold;
    }
    .health_bar {
        color:white;
        text-align: center; 
        font-weight:bold; 
        padding:5px; 
        width:200px; 
        border:solid 1px #ccc; 
        border-radius:15px;
    }
</style>
</head>
<body>
<?php 
if (isset($_POST['level_test'])) {
    $jsonString = file_get_contents('rpgdata.json');
    $data = json_decode($jsonString, true);

    // var_dump();
    $data['level'] = $_POST['custom_level'];
    echo $data['level'];
    $newJsonString = json_encode($data);
    file_put_contents('rpgdata.json', $newJsonString);
}

if (isset($_POST['random_stats'])) { //Random stats button
    $jsonString = file_get_contents('rpgdata.json');
    $data = json_decode($jsonString, true);

    $data['DEX'] = rand(1, 20);  // Randomize Dexterity
    $data['STR'] = rand(1, 20);  // Randomize Strength
    $data['CON'] = rand(1, 20);  // Randomize Constitution
    $data['level'] = 1;
    $data['xp'] = 0;

    $newJsonString = json_encode($data);
    file_put_contents('rpgdata.json', $newJsonString);
}

$jsonString = file_get_contents('rpgdata.json');
$data = json_decode($jsonString, true);
$armor_class = 10+(($data['DEX']-10)/2);
$damage = 1+(($data['STR']-10)/2); if ($damage <= 0.5) {$damage = 0.5;};
$data['total_hp'] = 10+(($data['CON']-10)/2)+((($data['level']-1)*(6+(($data['CON']-10)/2))));
$data['current_hp'] = $data['total_hp'];
$hp_percentage = $data['current_hp']/$data['total_hp']*100;

$newJsonString = json_encode($data);
file_put_contents('rpgdata.json', $newJsonString);
?>
<form method="post" style="margin-bottom:20px;">
    <button type="submit" name="random_stats">New Game / Random stats</button>
</form>
<div class="health_bar" style="background-image: linear-gradient(90deg, #900 <?=$hp_percentage?>%, #000 <?=$hp_percentage?>%);"><?= $data['current_hp'] . '/' . $data['total_hp'] ?></div>
<div style="display:flex; flex-direction:row;">
    <div class="attribute">
        <div> DEX </div>
        <div> <?= $data['DEX'] ?> </div>
    </div>
    <div class="attribute">
        <div> STR </div>
        <div> <?= $data['STR'] ?> </div>
    </div>
    <div class="attribute">
        <div> CON </div>
        <div> <?= $data['CON'] ?> </div>
    </div>
</div>

<form method="post">
    <label for="level">Level</label>
    <input type="text" id="" name="custom_level" value="">
    <button type="submit" name="level_test">submit</button>

</form>





</body>
</html>