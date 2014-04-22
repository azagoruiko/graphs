<?php
namespace pzs12z\zagoruiko\graphs\model;

class Vertex {
    public function __construct($name) {
        $this->name = $name;
        $this->edges = new \SplObjectStorage();
    }

    private $name;
    private $edges;
    function getName() {
        return $this->name;
    }
    
    function setName($name) {
        $this->name = $name;
    }
    
    function addEdge(Edge $edge) {
        $this->edges->attach($edge);
    }
    
    function removeEdge(Edge $edge) {
        $this->edges->detach($edge);
    }
    
    function getEdges() {
        return $this->edges;
    }
    
    function getDegree() {
        return $this->edges->count();
    }
    
    function isDegreeEven() {
        return ($this->edges->count() & 1) == 0;
    }
    
    function isDegreeOdd() {
        return !$this->isDegreeEven();
    }
    
    function hasEdgeWith(Vertex $vertex) {
        foreach ($this->edges as $edge) {
            if ($edge->getVertex1()->getName() === $vertex->getName() 
                    || $edge->getVertex2()->getName() === $vertex->getName()) {
                    return true;
            }
        }
        return false;
    }
    
    function getJsonData(){
        $vert = new \stdClass();
        $vert->name = $this->getName();
        $vert->degree = $this->getDegree();
        $vert->isEven = $this->isDegreeEven();
        $vert->isOdd = $this->isDegreeOdd();
        return $vert;
     }
    
}
