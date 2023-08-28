<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta etc -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
<style>
    .grid-container {
        display:grid;
        grid-template-columns: repeat(3, 300px);
        gap:20px;
    }
    .grid-item {
        width:300px;
        height:300px;
        border:solid 1px #ccc;
        border-radius:10px;
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color:#aaa;
    }
</style>
</head>

<body>

<div class="grid-container">
    <div class="grid-item">
        <p>Projects</p>
        <a href="projects/pathfinder.php"><button style="padding:10px; background-color:#eee; border-radius:10px;">pathfinder project</button></a>
        <a href="projects/simple_2D_checkerboard.php"><button style="padding:10px; background-color:#eee; border-radius:10px;">checkerboard</button></a>
        <a href="projects/rpg.php"><button style="padding:10px; background-color:#eee; border-radius:10px;">RPG</button></a>
    </div>
    <div class="grid-item">
        <p>Temp</p>
        <a href="temp.php"><button style="padding:10px; background-color:#eee; border-radius:10px;">Temp project</button></a>
    </div>
</div>

</body>
</html>
