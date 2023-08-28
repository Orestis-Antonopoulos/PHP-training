<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
if (isset($_POST['randomize_dex'])) {
    $jsonString = file_get_contents('rpgdata.json');
    $data = json_decode($jsonString, true);

    $data['DEX'] = rand(1, 20);  // Randomize Dexterity
    $data['STR'] = rand(1, 20);  // Randomize Strength
    $data['CON'] = rand(1, 20);  // Randomize Constitution

    $newJsonString = json_encode($data);
    file_put_contents('rpgdata.json', $newJsonString);
}

$jsonString = file_get_contents('rpgdata.json');
$data = json_decode($jsonString, true);
$armor_class = (5+($data['DEX']/2));
$damage = ($data['level']*($data['STR']/5));
$data['total_hp'] = 10+(($data['level']*5)*($data['CON']/5));

echo ("<p> Your Dex is " . $data['DEX'] . ", ");
echo ("and your AC is $armor_class </p>");
echo ("<p> Your STR is " . $data['STR'] . " and damage is $damage! </p>");
echo ("<p> Your CON is " . $data['CON'] . " and hp is " . $data['total_hp'] ."! </p>");


$newJsonString = json_encode($data);
file_put_contents('rpgdata.json', $newJsonString);
?>

<form method="post">
    <button type="submit" name="randomize_dex">Randomize DEX</button>
</form>

<?php

?>





</body>
</html>