<?php
namespace pzs12z\zagoruiko\graphs\service;

use pzs12z\zagoruiko\graphs\model\Graph;

interface GraphService {
    function find($id);
    function getAll();
    function save(Graph $graph);
    function delete($id);
}
