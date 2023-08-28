<?php

require 'vendor/autoload.php';
use Phpml\Regression\LeastSquares;

$samples = [[100,2,4,300000], [2], [3]]; // Features
$targets = [2, 4, 6];       // Corresponding values

$regression = new LeastSquares();
$regression->train($samples, $targets);

$prediction = $regression->predict([4]);
echo 'Predicted value: ' . $prediction; // Output: 8
