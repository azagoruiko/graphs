<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        
        <script>
            var obj = <?=json_encode($graph->getJsonData());?>;            
        </script>
        <script src="graphs.js"></script>
        <style>
            .table {
                width: 300px;
            }
            #graph, #matrix {
                float: left;
                margin-right: 20px;
            }
            #panel {
                clear: both;
            }
            #log {
                overflow-y: scroll;
                height: 350px;
                max-height: 350px;
            }
            
        </style>
    </head>
    <body>
        <div id="form">
            <form action="" method="post">
                <label for="graph">Название</label>
                <input type="text" name="id" id="id" />
                <label for="sequence">Последовательность цифр через запятую</label>
                <input type="text" name="sequence" id="sequence" />
                <input type="submit" value="Сохранить последовательность и загрузить граф" />
            </form>
        </div>
        <div id="graph_list">
            <h5>Сохраненные графы</h5>
            <ul>
            <?php
            foreach ($all as $graph) {
                echo "<li><a href=\"?graph=$graph\">$graph</a> <br />";
            }
            ?>
            </ul>
        </div>
        <div id="main">
            <div id="graph">
                <div>Граф:</div>
                <canvas width="500" height="500" id="draw"></canvas>
            </div>
            <div id="matrix">
                <div>Матрица смежности:</div>
                <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <?php
                    for ($i = 1; $i <= count($matrix); $i++) {
                        echo "<th><a id=\"vertex_$i\" href=\"javascript:void(0)\">$i</a></th>";
                    }
                    ?>
                </tr>


                <?php
                $i = 1;
                foreach ($matrix as $item) {
                    echo '<tr>';
                    echo "<td>$i</td>";
                    $i++;
                    $j=1;
                    foreach ($item as $subitem) {
                        $ii = $i-1;
                        if ($subitem % 2 == 0 || $subitem % 3 == 0) {
                            echo "<td><a id=\"edge_{$ii}_$j\" href=\"javascript:void(0)\">$subitem</a></td>";
                        } else {
                            echo "<td><a id=\"edge_{$ii}_$j\" href=\"javascript:void(0)\">-</a></td>";
                        }
                        $j++;
                    }
                    echo '</tr>';
                }
                echo '</table>';
                ?>
            </div>
            <div id="log"></div>
        </div>
        <div id="panel">
            <div>
                <label for="new_edge">Ребро</label>
                <input size="3" name="new_edge" type="text" id="new_edge" value="4,7" />
                <input name="new_edge_btn" type="button" id="new_edge_btn" value="добавить ребро" />
            </div>
            <div>
                <label for="new_vertex">Метка вершины</label>
                <input size="3" name="new_vertex" type="text" id="new_vertex" value="8" />
                <input name="new_vertex_btn" type="button" id="new_vertex_btn" value="добавить вершину" />
            </div>
            <div>
                <label for="start_vertex">Начинать с вершины</label>
                <input size="3" name="start_vertex" type="text" id="start_vertex" value="1" />
                <input name="walk_width_btn" type="button" id="walk_width_btn" value="обойти в ширину" />
                <input name="walk_depth_btn" type="button" id="walk_depth_btn" value="обойти в глубину" />
            </div>
            <div>
                <label for="start_vertex_eulerian">Начинать с вершины</label>
                <input size="3" name="start_vertex_eulerian" type="text" id="start_vertex_eulerian" value="1" />
                <input name="eulerian_path_btn" type="button" id="eulerian_path_btn" value="построить Эйлеров цикл" />
            </div>
        </div>
    </body>
</html>