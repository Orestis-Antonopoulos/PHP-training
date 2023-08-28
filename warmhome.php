<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organism Simulation</title>
    <style>
        .grid-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .block {
            width: 50px;
            height: 50px;
            border: 1px solid black;
            display: inline-block;
            text-align: center;
            line-height: 50px;
        }
        .occupied {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="grid-container" id="grid-container">
        <!-- Blocks will be generated here by JavaScript -->
    </div>
    <div id="time">Time: 00:00</div>

    <script>
    const blocks = ["warm house", "block 2", "block 3", "block 4", "cool place"];
    let position = 0; // Starting position
    let hour = 0; // Starting hour

    function updateGrid() {
        const container = document.getElementById('grid-container');
        container.innerHTML = ''; // Clear existing blocks

        // Create and append blocks to the container
        blocks.forEach((block, index) => {
            const div = document.createElement('div');
            div.className = 'block';
            if (index === position) {
                div.classList.add('occupied');
                div.textContent = blocks[index];
            }
            container.appendChild(div);
        });

        // Update time display
        document.getElementById('time').textContent = `Time: ${hour.toString().padStart(2, '0')}:00`;

        // Move organism based on time
        if (hour >= 12) {
            position = Math.min(position + 1, 4);
        } else {
            position = Math.max(position - 1, 0);
        }

        // Increment hour and reset after 24 hours
        hour = (hour + 1) % 24;
    }

    // Initial update
    updateGrid();

    // Update the grid every second (1000 milliseconds)
    setInterval(updateGrid, 1000);


    </script>
</body>
</html>

