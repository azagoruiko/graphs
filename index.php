<?php
require_once 'autoload.php';

use pzs12z\zagoruiko\graphs\service\MatrixGraphService;

$srv = new MatrixGraphService();
if ($id = filter_input(INPUT_GET, 'graph')) {
    $seq = $srv->loadSequence($id);
    $matrix = $srv->loadMatrixFromSequence($seq);
    $graph = $srv->loadGraphFromMatrix($matrix, $id);
} else {
    $matrix = $srv->loadMatrixFromSequence([9, 1, 4, 16, 18, 21, 11, ]);
    $graph = $srv->loadGraphFromMatrix($matrix, 10);
}
if ($seq = filter_input(INPUT_POST, 'sequence')) {
    $seqArray = split(',',$seq);
    $id = filter_input(INPUT_POST, 'id');
    $srv->saveSequence($seqArray, $id);
    
    $matrix = $srv->loadMatrixFromSequence($seqArray);
    $graph = $srv->loadGraphFromMatrix($matrix, $id);
}

$all = $srv->getAll();

include 'views/main.php';
