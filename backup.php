<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Training website</title>
</head>
<!-- <body>
    <?php 
        $songs = [ 
            ["title" => "Voodoo People", "artist" => "Prodigy", "album" => "Music for the Jilted Generation", "releaseYear" => "1994", "like" => True],
            ["title" => "Cemetery Gates", "artist" => "Pantera", "album" => "Cowboys from Hell", "releaseYear" => "1990", "like" => False], 
            ["title" => "Firestarter", "artist" => "Prodigy", "album" => "The Fat of the Land", "releaseYear" => "1997", "like" => True], 
            ["title" => "Down With the Sickness", "artist" => "Disturbed", "album" => "The Sickness", "releaseYear" => "2000", "like" => True],
            ["title" => "Sweet Child o' Mine", "artist" => "Guns N' Roses", "album" => "Appetite for Destruction", "releaseYear" => "1987", "like" => True],
            ["title" => "Every Breath You Take", "artist" => "The Police", "album" => "Synchronicity", "releaseYear" => "1983", "like" => True],
            ["title" => "With or Without You", "artist" => "U2", "album" => "The Joshua Tree", "releaseYear" => "1987", "like" => True],
            ["title" => "Jump", "artist" => "Van Halen", "album" => "1984", "releaseYear" => "1984", "like" => True],
            ["title" => "Don't Stop Believin'", "artist" => "Journey", "album" => "Escape", "releaseYear" => "1981", "like" => True],
            ["title" => "Eye of the Tiger", "artist" => "Survivor", "album" => "Eye of the Tiger", "releaseYear" => "1982", "like" => False],
            ["title" => "Under Pressure", "artist" => "Queen & David Bowie", "album" => "Hot Space", "releaseYear" => "1981", "like" => True],
            ["title" => "Pour Some Sugar on Me", "artist" => "Def Leppard", "album" => "Hysteria", "releaseYear" => "1987", "like" => False],
            ["title" => "Back in Black", "artist" => "AC/DC", "album" => "Back in Black", "releaseYear" => "1980", "like" => True],
            ["title" => "Money for Nothing", "artist" => "Dire Straits", "album" => "Brothers in Arms", "releaseYear" => "1985", "like" => True]
        ];
    ?>
    <form method="post">
        <input type="text" name="formData" placeholder="Artist / Song / Album" />
        <button type="submit"> SUBMIT </button>
    </form>
    <?php $filter = $_POST['formData']; ?>

    <ul>
        <?php foreach ($songs as $song) : ?>
            <?php if ((stripos($song['artist'], $filter) !== false) || (stripos($song['title'], $filter) !== false) || (stripos($song['album'], $filter) !== false)) : ?>

                <li>
                    You <?= $song['like'] ? "like " : "don't like "?> 
                    the song <strong>" <?=$song['title']?>"</strong> 
                    by <span style="font-style: italic;"> <?=$song['artist']?> </span>,
                    which was released in <?= $song['releaseYear']?>, 
                    album: "<?= $song['album']?>".
                </li>
        
            <?php endif ?>
        <?php endforeach ?>
    </ul>


</body> -->

<body>
<style>
    .world-grid {
        border-collapse: collapse;
    }

    .world-grid td {
        width: 10px;
        height: 10px;
        border: 1px solid #ddd;
    }

    .world-grid .N {
        background-color: red; /* NPCs */
    }

    .world-grid .T {
        background-color: green; /* Trees */
    }

    .world-grid {
        background-color: #FEE; /* Ground */
    }
</style>

<?php 
$worldSize = 80;
$world = array_fill(0, $worldSize, array_fill(0, $worldSize, ' '));

$numNPCs = 10;
$numTrees = 100;

for ($i = 0; $i < $numNPCs; $i++) {
    $x = rand(0, $worldSize - 1);
    $y = rand(0, $worldSize - 1);
    $world[$x][$y] = 'N'; // Representing NPC
}

for ($i = 0; $i < $numTrees; $i++) {
    $x = rand(0, $worldSize - 1);
    $y = rand(0, $worldSize - 1);
    $world[$x][$y] = 'T'; // Representing Tree
}
foreach ($world as $row) {
    echo implode(' ', $row) . PHP_EOL;
}

?>

<table class="world-grid">
    <?php foreach ($world as $row): ?>
        <tr>
            <?php foreach ($row as $cell): ?>
                <td class="<?php echo $cell; ?>"></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>


</body>

</html>