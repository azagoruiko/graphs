<?php
namespace pzs12z\zagoruiko\graphs\model;

class Graph {
    function __construct($id) {
        $this->id = $id;
        $this->vertices = new \SplObjectStorage();
    }
    /**
     *
     * @var \SplObjectStorage 
     */
    private $id;
    private $vertices;
    
    public function getId() {
        return $this->id;
    }
    
    function addVertex(Vertex $vertex) {
        $this->vertices->attach($vertex);
    }
    
    function removeVertex(Vertex $vertex) {
        $this->vertices->detach($vertex);
    }
    
    /**
     *
     * @return \SplObjectStorage 
     */
    function getVertices() {
        return $this->vertices;
    }
    
    /**
     *
     * @return \SplObjectStorage 
     */
    function getEdges() {
        $return = new \SplObjectStorage();
        foreach($this->vertices as $vertex) {
            foreach ($vertex->getEdges() as $edge) {
                $return->attach($edge);
            }
        }
        return $return;
    }
    
    function getJsonData(){
        $obj = new \stdClass();
        $obj->vertices = array();
        $obj->edges = array();

        foreach ($this->getEdges() as $edge) {
            $edg = $edge->getJsonData();
            $obj->edges[$edge->getName()] = $edg;
        }

        foreach ($this->getVertices() as $vertex) {
            $vert = $vertex->getJsonData();
            $obj->vertices[$vert->name] = $vert;
        }
        return $obj;
     }
}
