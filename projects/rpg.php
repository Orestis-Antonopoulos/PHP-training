<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<style>
/* ingame Bars and styles */
    .attribute {
        padding:10px;
        width:33%;
        background-color:#ffffff40;
        justify-content:center;
        align-items:center;
        text-align:center;
        font-weight:bold;
        font-size:14px;
    }
    .health_bar, .xp_bar {
        color:white;
        display:flex;
        align-items: center;
        justify-content: center;
        text-align: center; 
        font-weight:bold; 
        padding:5px; 
        width:50%; 
        height:25px;
        border:solid 1px #ccc; 
        border-radius:15px;
        font-size:14px;
    }
</style>
</head>
<body style="margin:0;">
<?php 
$data = json_decode(file_get_contents('rpgdata.json'), true);
if (isset($_POST['level_test'])) { //Set level test
    $data['character']['level'] = $_POST['custom_level'];
    file_put_contents('rpgdata.json', json_encode($data));}
if (isset($_POST['random_stats'])) { //Random stats button
    $data['stats']['DEX'] = rand(1, 20);  // Randomize Dexterity
    $data['stats']['STR'] = rand(1, 20);  // Randomize Strength
    $data['stats']['CON'] = rand(1, 20);  // Randomize Constitution
    $data['character']['level'] = 1;
    $data['character']['xp'] = 0;
    foreach ($data['equipment'] as $key => $value) { // Reset all equipment to null
        $data['equipment'][$key]['title'] = null;
        $data['equipment'][$key]['description'] = null;
        $data['equipment'][$key]['effect'] = null;
        $data['equipment'][$key]['tier'] = null;
        $data['equipment'][$key]['level'] = null;
    }
    $data['inventory']['gold'] = 0; // Reset inventory
    $data['inventory']['potions'] = 0;
    foreach ($data['bosses_defeated'] as $key => $value) { // Reset defeated bosses to false
        $data['bosses_defeated'][$key] = false;
    }
    file_put_contents('rpgdata.json', json_encode($data, JSON_PRETTY_PRINT));}
if (isset($_POST['random_xp'])) { //Random XP button
        $randomXp = rand(1, 10);
        $data['character']['xp'] += $randomXp;
        file_put_contents('rpgdata.json', json_encode($data));}
$xp_level_up = 3 * pow(($data['character']['level']+1), 2); //variable for leveing formula
if ($data['character']['xp'] >= $xp_level_up) { //Leveling formula
    $data['character']['xp'] = 0;
    $data['character']['level'] += 1;
    file_put_contents('rpgdata.json', json_encode($data));}
if (isset($_POST['easy_mode'])) { // Easy mode button
    do {
        $data['stats']['DEX'] = rand(1, 20);  // Randomize Dexterity
        $data['stats']['STR'] = rand(1, 20);  // Randomize Strength
        $data['stats']['CON'] = rand(1, 20);  // Randomize Constitution
    } while ($data['stats']['STR'] + $data['stats']['DEX'] + $data['stats']['CON'] <= 45);
    $data['character']['level'] = 1;
    $data['character']['xp'] = 0;
    foreach ($data['equipment'] as $key => $value) { // Reset all equipment to null
        $data['equipment'][$key]['title'] = null;
        $data['equipment'][$key]['description'] = null;
        $data['equipment'][$key]['effect'] = null;
        $data['equipment'][$key]['tier'] = null;
        $data['equipment'][$key]['level'] = null;
    }
    $data['inventory']['gold'] = 0; // Reset inventory
    $data['inventory']['potions'] = 0;
    foreach ($data['bosses_defeated'] as $key => $value) { // Reset defeated bosses to false
        $data['bosses_defeated'][$key] = false;
    }
    file_put_contents('rpgdata.json', json_encode($data));}
if (isset($_POST['medium_mode'])) { // Medium mode button
    do {
        $data['stats']['DEX'] = rand(1, 20);  // Randomize Dexterity
        $data['stats']['STR'] = rand(1, 20);  // Randomize Strength
        $data['stats']['CON'] = rand(1, 20);  // Randomize Constitution
    } while ($data['stats']['STR'] + $data['stats']['DEX'] + $data['stats']['CON'] > 45 || $data['stats']['STR'] + $data['stats']['DEX'] + $data['stats']['CON'] <= 35);
    $data['character']['level'] = 1;
    $data['character']['xp'] = 0;
    foreach ($data['equipment'] as $key => $value) { // Reset all equipment to null
        $data['equipment'][$key]['title'] = null;
        $data['equipment'][$key]['description'] = null;
        $data['equipment'][$key]['effect'] = null;
        $data['equipment'][$key]['tier'] = null;
        $data['equipment'][$key]['level'] = null;
    }
    $data['inventory']['gold'] = 0; // Reset inventory
    $data['inventory']['potions'] = 0;
    foreach ($data['bosses_defeated'] as $key => $value) { // Reset defeated bosses to false
        $data['bosses_defeated'][$key] = false;
    }
    file_put_contents('rpgdata.json', json_encode($data));}
if (isset($_POST['hard_mode'])) { // Hard mode button
    do {
        $data['stats']['DEX'] = rand(1, 20);  // Randomize Dexterity
        $data['stats']['STR'] = rand(1, 20);  // Randomize Strength
        $data['stats']['CON'] = rand(1, 20);  // Randomize Constitution
    } while ($data['stats']['STR'] + $data['stats']['DEX'] + $data['stats']['CON'] >= 35);
    $data['character']['level'] = 1;
    $data['character']['xp'] = 0;
    foreach ($data['equipment'] as $key => $value) { // Reset all equipment to null
        $data['equipment'][$key]['title'] = null;
        $data['equipment'][$key]['description'] = null;
        $data['equipment'][$key]['effect'] = null;
        $data['equipment'][$key]['tier'] = null;
        $data['equipment'][$key]['level'] = null;
    }
    $data['inventory']['gold'] = 0; // Reset inventory
    $data['inventory']['potions'] = 0;
    foreach ($data['bosses_defeated'] as $key => $value) { // Reset defeated bosses to false
        $data['bosses_defeated'][$key] = false;
    }
    file_put_contents('rpgdata.json', json_encode($data));}

$armor_class = 10+(($data['stats']['DEX']-10)/2);
$damage = 1+(($data['stats']['STR']-10)/2); if ($damage <= 0.5) {$damage = 0.5;};
$data['character']['total_hp'] = 10+(($data['stats']['CON']-10)/2)+((($data['character']['level']-1)*(6+(($data['stats']['CON']-10)/2))));
$data['character']['current_hp'] = $data['character']['total_hp'];
$hp_percentage = $data['character']['current_hp']/$data['character']['total_hp']*100;
$xp_percentage = $data['character']['xp'] ? round(($data['character']['xp'] / $xp_level_up) * 100, 2) : 0;
file_put_contents('rpgdata.json', json_encode($data));
?>

<div style="max-width:768px; height:100vh; background-color:#faa; border-radius:15px; border:solid 1px #00000040;">
    
    <p>Your level is <?= $data['character']['level']?></p>
    <form method="post" style="margin-bottom:20px;"><button type="submit" name="random_stats">New Game / Random stats</button></form>
    <div style="display:flex; flex-direction:row;">
        <form method="post" style="margin:5px;"><button type="submit" name="easy_mode">Easy Mode</button></form>
        <form method="post" style="margin:5px;"><button type="submit" name="medium_mode">Medium Mode</button></form>
        <form method="post" style="margin:5px;"><button type="submit" name="hard_mode">Hard Mode</button></form>
    </div>
    <div style="display:flex; flex-direction:column;">
        <div style="display:flex; flex-direction:row;">
            <div class="attribute"><div> STR </div><div> <?= $data['stats']['STR'] ?> </div></div>
            <div style="border:1px solid #00000040;"></div>
            <div class="attribute"><div> DEX </div><div> <?= $data['stats']['DEX'] ?> </div></div>
            <div style="border:1px solid #00000040;"></div>
            <div class="attribute"><div> CON </div><div> <?= $data['stats']['CON'] ?> </div></div>
        </div>
        <div style="display:flex; flex-direction:row;">
            <div class="xp_bar" style="position:relative; background-image: linear-gradient(90deg, #999 <?=$xp_percentage?>%, #000 <?=$xp_percentage?>%);"><?= $xp_percentage . '%' ?>
                <p style="position:absolute; background-color:#ffffff30;color:white;left:-1px;font-size:14px;padding:10px; border-radius: 10px 0 0 10px;">Lv: <?= $data['character']['level']?></p>
            </div>
            <div class="health_bar" style="background-image: linear-gradient(90deg, #900 <?=$hp_percentage?>%, #000 <?=$hp_percentage?>%);"><?= $data['character']['current_hp'] . '/' . $data['character']['total_hp'] . ' hp' ?></div>
        </div>
    </div>
    
    <form method="post">
        <label for="level">Level</label>
        <input type="text" id="" name="custom_level" value="">
        <button type="submit" name="level_test">submit</button>
    </form>
    <form method="post">
        <label for="xp">Random 1-10xp:</label>
        <button type="submit" name="random_xp">submit</button>
    </form>
    
    
    
    
</div>

</body>
</html>