<?php

namespace pzs12z\zagoruiko\graphs\service;

use pzs12z\zagoruiko\graphs\model\Edge;
use pzs12z\zagoruiko\graphs\model\Graph;
use pzs12z\zagoruiko\graphs\model\Vertex;
use RuntimeException;

class MatrixGraphService implements GraphService {
    function find($id) {
        $items = $this->loadSequence($id);
        $matrix = $this->loadMatrixFromSequence($items);
        return $this->loadGraphFromMatrix($matrix, $id);
    }
    
    function loadSequence($id) {
        $filename = "data/matrix/$id.dat";
        if (file_exists($filename)) {
            $data = trim(file_get_contents($filename));
            return split(',', $data);
        } else {
            throw new RuntimeException("File $filename does not exist");
        }
    }
    
    function loadMatrixFromSequence(array $sequence) {
        $matrix = array();
        $i=1;
        foreach ($sequence as $item) {
            $item = trim($item);
            $matrix[$i] = array();
            $j=1;
            foreach ($sequence as $_item) {
                $matrix[$i][$j++] = abs($item - $_item);
            }
            $i++;
        }
        return $matrix;
    }
    
    function loadGraphFromMatrix(array $matrix, $id) {
        $graph = new Graph($id);
        $verts = array();
        for ($i=1; $i<=count($matrix); $i++) {
            $verts[$i] = new Vertex($i);
            $graph->addVertex($verts[$i]);
        }
        for ($i=1; $i<=count($matrix); $i++) {
            $j=1;
            foreach ($matrix[$i] as $item) {
                if ($item != 0 && ($item %2 == 0 || $item %3 == 0)) {
                    $edge = new Edge($verts[$i], $verts[$j]);
                    $edge->setWeight($item);
                } 
                $j++;
            }
                        
        }
        return $graph;
    }
    
    function getAll() {
        $dh = opendir("data/matrix");
        while ($file = readdir($dh)) {
            if ($file === '.' or $file === '..') {continue;}
            $items[] = str_replace('.dat', '', $file);
        }
        return $items;
    }
    
    function save(Graph $graph) {
        throw new Exception("Not implemented here");
    }
    
    function saveSequence(array $seq, $id) {
        $filename = "data/matrix/$id.dat";
        if (!file_put_contents($filename, implode(',', $seq))) {
            throw new RuntimeException("Could not save $filename");
        }
    }
    
    function delete($id) {
        unlink("data/matrix/$id.dat");
    }
}
