function calcCoords(drw) {
    var centerX = drw.width / 2;
    var centerY = drw.height / 2;
    var angleDelta = 2*Math.PI/Object.keys(obj.vertices).length;
    var radius = Math.min(centerX, centerY) - 20;
    var currentAngle = 0;

    $.each(obj.vertices, function (index, value){
        var x = centerX + (Math.cos(currentAngle) * radius);
        var y = centerY + (Math.sin(currentAngle) * radius);
        var moveRight = value.name <= 9 ? 5 : 10;
        currentAngle+=angleDelta;
        value.location = {
            "centX":x, 
            "centY":y,
            "textX":x - moveRight, 
            "textY":y + 8,
            "text":value.name
        };
    });
}

function drawGraph(ctx) {
    ctx.clearRect(0, 0, 1000, 1000);

    ctx.font = "20px Arial";
    ctx.fillStyle = "#FF3300";
    ctx.strokeStyle = "#FF3300";

    var strokeColors = ['red', 'blue', 'green', 'grey', 'yellow', 'purple'];
    var strokeColor = 0;
    $.each(obj.edges, function (index, value){
        ctx.strokeStyle = value.visited == 1 || value.visited == 2 ? 'black' : strokeColors[strokeColor];
        ctx.fillStyle = ctx.strokeStyle;
        strokeColor++;
        if (strokeColor === strokeColors.length) {
            strokeColor = 0;
        }
        ctx.beginPath();
        ctx.moveTo(obj.vertices[value.v1].location.centX, obj.vertices[value.v1].location.centY);
        ctx.lineTo(obj.vertices[value.v2].location.centX, obj.vertices[value.v2].location.centY);
        ctx.stroke();
        ctx.closePath();

        var lineCentX = (obj.vertices[value.v1].location.centX)/2 + (obj.vertices[value.v2].location.centX)/2;
        var lineCentY = (obj.vertices[value.v1].location.centY)/2 + (obj.vertices[value.v2].location.centY)/2;
        ctx.fillText(value.weight, lineCentX + 5, lineCentY - 8);

    });

    $.each(obj.vertices, function (index, value){
        ctx.beginPath();
        ctx.arc(value.location.centX, value.location.centY, 20, 0, 2*Math.PI);
        if (value.visited === 0 || !("visited" in value)) {
            ctx.fillStyle = "green";
        } else if (value.visited === 1) {
            ctx.fillStyle = "grey";
        } else {
            ctx.fillStyle = "black";
        }
        ctx.fill();
        ctx.fillStyle = "red";
        ctx.fillText(value.location.text, value.location.textX, value.location.textY);
        ctx.closePath();
    });
}

function calcDegrees() {
    var hasOdd = false;
    $.each(obj.vertices, function (index, value) {
        var n = findNeighbours(value);
        value.degree = Object.keys(n).length;
        value.even = !(value.degree & 1 !== 0);
        if (!value.even) hasOdd = true;
        $('#log').append("Degree of: " + value.name + " is " + value.degree + " which is " + (value.even ? "even" : "odd") + "<br />");
    });
    return hasOdd;
}

function redraw() {
    var drw = document.getElementById("draw");
    calcCoords(drw);

    var ctx = drw.getContext("2d");
    drawGraph(ctx);
}

function findNeighbours(vertex) {
    var neighbours = {};
    $.each(obj.edges, function (index, value){
        if (value.v1 == vertex.name) {
            neighbours[value.v2] = obj.vertices[value.v2];
        }
        if (value.v2 == vertex.name) {
            neighbours[value.v1] = obj.vertices[value.v1];
        }
    });
    return neighbours;
}

function findEdges(vertex) {
    var edges = {};
    $.each(obj.edges, function (index, value){
        if (value.v1 == vertex.name) {
            edges[value.v2] = value;
        }
        if (value.v2 == vertex.name) {
            edges[value.v1] = value;
        }
    });
    return edges;
}

function clearAnimation() {
    $('#log').text('');
    $.each(obj.vertices, function (index, value) {
        value.visited = 0;
    });
    $.each(obj.edges, function (index, value) {
        value.visited = 0;
    });
}

function animateWalk(animation) {
    var step = animation.shift();
    var vert = obj.vertices[step.vert];

    switch (vert['visited']) {
        case 1:
            $('#log').append("Visiting: " + vert.name + "<br />");
            break;
        case 2:
            $('#log').append("Visited: " + vert.name + "<br />");
            break;
        case 0:
            $('#log').append("Not visited yet: " + vert.name + "<br />");
            break;
    }

    var log = document.getElementById('log');

    log.scrollTop = log.scrollHeight;

    vert['visited'] = step.status;
    redraw();

    if (animation.length > 0) {
        setTimeout(function () {animateWalk(animation);}, 700);
    }
}

function animateWalkWithEdges(animation) {
    var step = animation.shift();
    var edge = obj.edges[step.edge];
    var vert1 = obj.vertices[edge.v1];
    var vert2 = obj.vertices[edge.v2];
    var vert = obj.vertices[step.vert];

    var log = document.getElementById('log');

    log.scrollTop = log.scrollHeight;

    vert1['visited'] = step.visiting_status;
    vert2['visited'] = step.visiting_status;
    vert['visited'] = step.status;
    edge['visited'] = step.edge_status;
    redraw();

    if (animation.length > 0) {
        setTimeout(function () {animateWalkWithEdges(animation);}, 700);
    }
}

function walkDepth(vertex, animation) {
    animation.push({'vert':vertex.name, 'status':1});
    animation.push({'vert':vertex.name, 'status':2});
    vertex.visited = 2;
    var neighbours = findNeighbours(vertex);
    $.each(neighbours, function (index, value) {
        if (value.visited !== 2) walkDepth(value, animation);
    });
}

$().ready(function() {
    $('#matrix a').mousedown(function () {
        var split = this.id.split('_');
        if (split[0] === "edge") {
            var id1 = "("+split[1] + "," + split[2]+")";
            var id2 = "("+split[2] + "," + split[1] + ")";
            var id1_ = "#egde_"+split[1] + "_" + split[2];
            var id2_ = "#edge_"+split[2] + "_" + split[1];

            if (id1 in obj.edges || id2 in obj.edges) {
                delete obj.edges[id1];
                delete obj.edges[id2];
                $(id1_).text("-");
                $(id2_).text("-");
                $(this).text("-");
            }
        }
        redraw();
    });

    $('#new_edge_btn').click(function () {
        var id="(" + $('#new_edge').val() + ")";
        var split = $('#new_edge').val().split(',');

        edge = {
            "name":id,
            "weight":Math.floor(Math.random() * 50, 2),
            "v1":split[0],
            "v2":split[1]
        };
        obj.edges[id] = edge;
        redraw();

    });

    $('#new_vertex_btn').click(function () {
        var id = $('#new_vertex').val();
        vertex = {"name":id};
        obj.vertices[id] = vertex;
        redraw();

    });

    $('#walk_width_btn').click(function () {
        clearAnimation();
        var animation = [];
        var visitedCount = 0;

        var first = $('#start_vertex').val();

        var queue = [obj.vertices[first]];
        while (queue.length > 0) {
            if (visitedCount === Object.keys(obj.vertices).length) break;
            vert = queue.pop();
            animation.push({"vert":vert.name, "status":1});
            if (vert.visited !== 2) {
                var neighbours = findNeighbours(vert);
                for (n in neighbours) {
                    if (n.visited !== 2) {
                        queue.push(neighbours[n]);
                    }
                }
                vert.visited = 2;
                visitedCount++;
                animation.push({"vert":vert.name, "status":2});
            } else {
                animation.push({"vert":vert.name, "status":vert.visited});
            }
        }
        clearAnimation();
        animateWalk(animation);
    });



    $('#walk_depth_btn').click(function () {
        clearAnimation();
        var animation = [];
        var first = $('#start_vertex').val();
        walkDepth(obj.vertices[first], animation);
        $.each(obj.vertices, function (index, value) {
            if (value.visited !== 2) {
                walkDepth(value, animation);
            }/* else {
                animation.push({"vert":value.name, "status":1});
                animation.push({"vert":value.name, "status":2});
            }*/
        });

        clearAnimation();
        animateWalk(animation);
    });

    function walkEdges(vertex, animation) {
        var edges = findEdges(vertex);
        $.each(edges, function (i, v) {
            if (v.visited !== 1) {
                v.visited = 1;
                walkEdges(obj.vertices[i], animation);
                $('#log').append(v.name + ' goes to ' + i + ' <br />');
                animation.push({"vert":vertex.name, "edge":v.name, 'visiting_status':1, 'status':2, 'edge_status':1});
                animation.push({"vert":vertex.name, "edge":v.name, 'visiting_status':0, 'status':2, 'edge_status':1});
            }
        });
    }

    $('#eulerian_path_btn').click(function () {
        clearAnimation();
        if (calcDegrees()) {
            $('#log').append('Has odd! Please add some edges to make the graph Eulerian!<br />');
            return;
        }
        var start = obj.vertices[$('#start_vertex_eulerian').val()];
        var animation = [];
        walkEdges(start, animation);
        clearAnimation();
        $('#log').append('Starting Eulerian path...<br />');
        animateWalkWithEdges(animation);
    });

    redraw();
});


