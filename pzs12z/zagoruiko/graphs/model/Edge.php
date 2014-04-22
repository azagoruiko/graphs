<?php
namespace pzs12z\zagoruiko\graphs\model;
class Edge {
    private $vertex1;
    private $vertex2;
    private $weight;
    public function __construct(Vertex $vertex1, Vertex $vertex2) {
        $this->vertex1 = $vertex1;
        $this->vertex2 = $vertex2;
        
        if (!$vertex1->hasEdgeWith($vertex2)) {
            $this->vertex1->addEdge($this);
            $this->vertex2->addEdge($this);
        }
    }
    
    function getWeight() {
        return $this->weight;
    }
    
    function setWeight($weight) {
        $this->weight = $weight;
    }
    
    /**
     * 
     * @return Vertex
     */
    function getVertex1() {
        return $this->vertex1;
    }
    
    /**
     * 
     * @return Vertex
     */
    function getVertex2() {
        return $this->vertex2;
    }
    
    function __toString() {
        return $this->getName();
    }
    
    function getName() {
        return '(' . $this->vertex1->getName() . ',' . $this->vertex2->getName() . ')';
    }
    
    function getJsonData(){
        $edg = new \stdClass();
        $edg->name = $this->getName();
        $edg->weight = $this->weight;
        $edg->v1 = $this->getVertex1()->getName();
        $edg->v2 = $this->getVertex2()->getName();
        return $edg;
     }
}
