<style>
  #gridTable {
    border-collapse: collapse;
  }
  #gridTable td {
    width: 10px;
    height: 13px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #000;
  }
  .empty {
    background-color: #444;
    color:#444;
    font-size:5px;
  }
  .npc {
    background-color: white;
    color:white;
    font-size:5px;
  }
  .target {
    background-color: yellow;
    color:yellow;
    font-size:5px;
  }
  .wall {
    background-color: black;
    color: black;
    font-size:5px;
  }
</style>


<?php
global $frames;
$frames = []; // to store each grid state
$parents = []; // to store parent of each cell

// Define the grid size
$size = 90;
$walls = 3000;
// Create an empty grid
$grid = [];
for ($i = 0; $i < $size; $i++) {
    $grid[$i] = array_fill(0, $size, 0);
}

// Place NPC
$npc_x = rand(0, $size - 1);
$npc_y = rand(0, $size - 1);
$grid[$npc_x][$npc_y] = 1; // 1 represents the NPC

// Place destination
do {
    $destination_x = rand(0, $size - 1);
    $destination_y = rand(0, $size - 1);
} while ($grid[$destination_x][$destination_y] != 0); // Ensure destination doesn't overlap with NPC or walls
$grid[$destination_x][$destination_y] = 2; // 2 represents the destination

$start = [$npc_x, $npc_y];
$destination = [$destination_x, $destination_y];

// Place walls (if any)
for ($i = 0; $i < $walls; $i++) { // Placing 10 walls randomly
    do {
        $wall_x = rand(0, $size - 1);
        $wall_y = rand(0, $size - 1);
    } while ($grid[$wall_x][$wall_y] != 0); // Ensure wall doesn't overlap with NPC, destination, or other walls
    $grid[$wall_x][$wall_y] = -1; // -1 represents a wall
}

function neighbors($grid, $position) {
    $neighbors = [];
    $rows = count($grid);
    $cols = count($grid[0]);
    $directions = [[-1, 0], [1, 0], [0, -1], [0, 1]]; // Up, Down, Left, Right

    foreach ($directions as $direction) {
        $newRow = $position[0] + $direction[0];
        $newCol = $position[1] + $direction[1];

        if ($newRow >= 0 && $newRow < $rows && $newCol >= 0 && $newCol < $cols && $grid[$newRow][$newCol] != -1 && $grid[$newRow][$newCol] != 1) {
            $neighbors[] = [$newRow, $newCol];
        }
    }

    return $neighbors;
}

function bfs($start, $destination, $grid) {
    global $frames, $parents;
    $queue = [$start];
    $visited = [];
    $visited[$start[0]][$start[1]] = true;
    $parents[$start[0]][$start[1]] = null;

    while (!empty($queue)) {
        $current = array_shift($queue);
        $row = $current[0];
        $col = $current[1];
        
        // Check if we reached the destination
        if ($row == $destination[0] && $col == $destination[1]) {
            $path = [];
            $node = $destination;
            while ($node !== null) {
                $path[] = $node;
                $node = $parents[$node[0]][$node[1]];
            }
            $path = array_reverse($path); // Reverse the path to start from the NPC
            
            // Generate frames only for the path
            $tempGrid = $grid;
            foreach ($path as $step) {
                $tempGrid[$step[0]][$step[1]] = 1;
                $frames[] = $tempGrid;
                $tempGrid[$step[0]][$step[1]] = 0;
            }
            
            return "Found the path!";
        }

        $neighbors = neighbors($grid, $current);

        foreach ($neighbors as $neighbor) {
            $nRow = $neighbor[0];
            $nCol = $neighbor[1];

            if (!isset($visited[$nRow][$nCol])) {
                $visited[$nRow][$nCol] = true;
                $parents[$nRow][$nCol] = $current;
                $queue[] = $neighbor;
            }
        }
    }

    return "No path found!";
}

$result = bfs($start, $destination, $grid);
echo $result;

?>

<!-- Create an empty 10x10 table for the grid -->
<table id="gridTable">
  <?php for ($i = 0; $i < $size; $i++): ?>
    <tr>
      <?php for ($j = 0; $j < $size; $j++): ?>
        <td id="cell-<?php echo $i; ?>-<?php echo $j; ?>"></td>
      <?php endfor; ?>
    </tr>
  <?php endfor; ?>
</table>

<script>
let frames = JSON.parse('<?php echo json_encode($frames); ?>');
let index = 0;

function updateGrid() {
  if (index < frames.length) {
    let frame = frames[index];
    for (let row = 0; row < frame.length; row++) {
      for (let col = 0; col < frame[row].length; col++) {
        let cellValue = frame[row][col];
        let cell = document.getElementById(`cell-${row}-${col}`);
        cell.innerText = cellValue;
        
        cell.className = '';  // Reset any existing classes
        if (cellValue === 0) {
          cell.classList.add('empty');
        } else if (cellValue === 1) {
          cell.classList.add('npc');
        } else if (cellValue === 2) {
          cell.classList.add('target');
        } else if (cellValue === -1) {
          cell.classList.add('wall');
        }
      }
    }
    index++;
    setTimeout(updateGrid, 50); // 0.05-second delay
  }
}


updateGrid();
</script>