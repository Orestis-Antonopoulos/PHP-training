<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta etc -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    <!-- Assing php values manually -->
        <?php
            $grid = [];
            $vertical = 8;
            $horizontal = 0; //Leave at zero if you want a square
            $horizontal = ($horizontal == 0) ? $vertical : $horizontal; 
        ?>
    <style>
        .grid-container {display:grid;
            grid-template-rows: repeat(<?php echo $vertical ?>, 50px);
            grid-template-columns: repeat(<?php echo $horizontal ?>, 50px);}
        .grid-item {
            width:50px;
            height:50px;
            border:solid 1px #999;}
    </style>
</head>

<body>

<div class="grid-container"> <!-- The Board +php -->
    <?php
        // Assign width & height values in <head>
        // Assign to grid zeros and ones
            for ($i = 0; $i < $vertical; $i++) {
                if ($i % 2 == 0) {
                    // For even rows
                    $grid[$i] = array_fill(0, $horizontal, 0);
                    for ($j = 0; $j < $horizontal; $j += 2) {
                        $grid[$i][$j] = 1;
                    }
                } else {
                    // For odd rows
                    $grid[$i] = array_fill(0, $horizontal, 0);
                    for ($j = 1; $j < $horizontal; $j += 2) {
                        $grid[$i][$j] = 1;
                    }
                }
            }        
        // Echo grid divs 
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                $bgcolor = $cell == 1 ? 'background-color:#AA0;' : 'background-color:#120;';
                echo '<div class="grid-item" style=' . $bgcolor . '></div>';
            }
        }
    ?>
</div>

</body>
</html>
