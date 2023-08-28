<?php
require 'vendor/autoload.php';

use Rubix\ML\PersistentModel;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Regressors\MLPRegressor;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\Optimizers\RMSProp;
use Rubix\ML\Persisters\Filesystem;

$samples = [[140,3,3,1,1980], [240,0,3,3,2004], [114,1,3,2,2023], [148,3,3,1,1979], [83,4,2,1,1976], [80,0,1,1,1982], [85,0,2,1,1965], [126,2,3,1,1994], [134,4,3,1,1979], [295,0,4,3,1999], [182,1,5,4,2009]];
$targets = [395000, 495000, 550000, 305000, 198000, 250000, 170000, 320000, 340000, 660000, 780000];

$trainingData = new Labeled($samples, $targets); // Create a Labeled dataset for training

$estimator = new PersistentModel(
    new MLPRegressor([
        new Dense(100),
        new PReLU(),
        new Dense(100),
        new PReLU(),
        new Dense(50),
    ], 100000, new RMSProp(0.001)),
    new Filesystem('OrestModels/model04.rbx')
);

$estimator->train($trainingData); // Train the model with the labeled data

$sampleToPredict = [96,1,3,2,1989];
$datasetToPredict = new Unlabeled([$sampleToPredict]); // Create an Unlabeled dataset for prediction

$prediction = $estimator->predict($datasetToPredict); // Predict the target value for the sample

echo 'Predicted value: ' . $prediction[0]; // Print the predicted value
