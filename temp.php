<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
    .grid-container {
        display:grid;
        gap:10px;
    }
    .grid-item {
        background-color:#ccc;
        width:30px;height:30px;
    }
</style>
</head>
<body>
    
<?php 

$array = (array_fill(2, 3, 0));
foreach ($array as $key=>$value) {
    $array[$key] = $key;
}
print_r ($array);
?>

</body>
</html>