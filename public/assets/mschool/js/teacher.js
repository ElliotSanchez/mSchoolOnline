$(function() {
    // CLASS SWITCHER
    $('select.teacher-class-switcher').change(function() {
        var url = $(this).data('url');
        var id = $(this).find('option:selected').data('mclassid');
        var host = location.host;

        window.location = 'http://' + host + url + '/' + id;
    });

    // ASSESSMENT : CLASS AVERAGE
    $('div.assessment-class-average').each(function() {

        var mclassId = $(this).data('mclass-id');

        /**
         * ORIGINAL EXAMPLE
         * http://bl.ocks.org/mbostock/3887118
         */
        var margin = {top: 20, right: 20, bottom: 30, left: 40},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        var x = d3.scale.linear()
            .range([0, width]);

        var y = d3.scale.linear()
            .range([height, 0]);

        var color = d3.scale.category10();

        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom");

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left");

        // SETUP SVG
        var svg = d3.select("div.assessment-class-average").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        x.domain([0, 5]);
        y.domain([100, 1000]);

        xAxis.ticks(4)
            .tickFormat(function(d) {
                if (d < 1 || d > 4) return null;
                else return d;
            })
            .innerTickSize(0)
            .outerTickSize(0);

        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis)
            .append("text")
            .attr("class", "label")
            .attr("x", width)
            .attr("y", -6)
            .style("text-anchor", "end");
            //.text("Sepal Width (cm)");

        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
            .append("text")
            .attr("class", "label")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", ".71em")
            .style("text-anchor", "end");
            //.text("Sepal Length (cm)")

//            svg.selectAll(".dot")
//                .data(data)
//                .enter().append("circle")
//                .attr("class", "dot")
//                .attr("r", 3.5)
//                .attr("cx", function(d) { return x(d.sepalWidth*100); })
//                .attr("cy", function(d) { return y(d.sepalLength*100); })
//                .style("fill", function(d) { return color(d.species); });

//            var legend = svg.selectAll(".legend")
//                .data(color.domain())
//                .enter().append("g")
//                .attr("class", "legend")
//                .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });
//
//            legend.append("rect")
//                .attr("x", width - 18)
//                .attr("width", 18)
//                .attr("height", 18)
//                .style("fill", color);
//
//            legend.append("text")
//                .attr("x", width - 24)
//                .attr("y", 9)
//                .attr("dy", ".35em")
//                .style("text-anchor", "end")
//                .text(function(d) { return d; });

        var url = "/teacher/data/learning-points/grade-averages/"+ mclassId;

        $.getJSON(url, function(data) {

            var assessment1DataSet = data[0];
            var assessment1Data = assessment1DataSet.data;

            console.log(assessment1DataSet);
            console.log(assessment1Data);

            svg.selectAll(".dot")
                .data(assessment1Data)
                .enter().append("circle")
                .attr("class", "dot")
                .attr("r", 15)
                .attr("cx", function(d) { console.log('x:'+"1"); return x(1); })
                .attr("cy", function(d) { console.log('y:'+d.avg); return y(d.avg); })
                .style("fill", function(d) { return "#f7990d"; });

        });

//
//        var mclassId = $(this).data('mclass-id');
//
//        var rangeMap = {
//            'K' : { 'lower': {'min':201 ,	'max':403}, 'upper': {'min': 451, 	'max': 499}},
//            '1' : { 'lower': {'min':406 ,	'max':425}, 'upper': {'min': 474, 	'max': 523}},
//            '2' : { 'lower': {'min':428 ,	'max':447}, 'upper': {'min': 494.5,	'max': 542}},
//            '3' : { 'lower': {'min':450 ,	'max':469}, 'upper': {'min': 516, 	'max': 563}},
//            '4' : { 'lower': {'min':471.5,	'max':490}, 'upper': {'min': 532, 	'max': 574}},
//            '5' : { 'lower': {'min':487.5,	'max':501}, 'upper': {'min': 542.5, 'max': 584}},
//            '6' : { 'lower': {'min':497 ,	'max':509}, 'upper': {'min': 518, 	'max': 527}},
//            '7' : { 'lower': {'min':518 ,	'max':528}, 'upper': {'min': 532, 	'max': 536}},
//            '8' : { 'lower': {'min':532 ,	'max':537}, 'upper': {'min': 546, 	'max': 555}},
//            '9' : { 'lower': {'min':546 ,	'max':556}, 'upper': {'min': 591, 	'max': 626}},
//            '10': { 'lower': {'min':571	,	'max':587}, 'upper': {'min': 612,	'max': 637}},
//            '11': { 'lower': {'min':586.5 ,	'max':602}, 'upper': {'min': 627, 	'max': 652}},
//            '12': { 'lower': {'min':601.5 ,	'max':617}, 'upper': {'min': 708.5,	'max': 800}}
//            };
//
//        var rangeBounds = rangeMap['3']; // TESTING 3rd GRADE
//
////        var rangeMin = Math.floor(rangeBounds.lower.min/100)*100;
////        var rangeMax = Math.ceil(rangeBounds.upper.max/100)*100;
//
//        var height = 600;
//
//        var svg = d3.select("div.assessment-class-average").append("svg")
//                .attr("width", height)
//                .attr("height", height);
//
//        var yScale = d3.scale.linear();
//
//        yScale.domain([rangeBounds.lower.min, rangeBounds.upper.max]);
//        yScale.range([height, 0]); // INVERTED
//
//        var xScale = d3.scale.ordinal().domain([1, 2, 3, 4]).range([0, height]);
//
//        var maxLine = svg.append("line")
//            .attr("x1", 0)
//            .attr("y1", yScale(rangeBounds.lower.max))
//            .attr("x2", height)
//            .attr("y2", yScale(rangeBounds.upper.max))
//            .attr("stroke-width", 1)
//            .attr("stroke", "gray");
//
//        var minLine = svg.append("line")
//            .attr("x1", 0)
//            .attr("y1", yScale(rangeBounds.lower.min))
//            .attr("x2", height)
//            .attr("y2", yScale(rangeBounds.upper.min))
//            .attr("stroke-width", 1)
//            .attr("stroke", "gray");
//
//        var url = "/teacher/data/learning-points/grade-averages/"+ mclassId;
//
//        $.getJSON(url, function(data) {
//            //console.log(data);
//            //console.log(data['4']);
//            var gradeData = data['4'];
//            //console.log(gradeData);
//            //console.log(gradeData.avg);
//
//            var square = 600;
//
//            var gdata = [
//                {'x':square *.2, 'y':gradeData.avg}
//            ];
//
//            var margin = {top: 20, right: 20, bottom: 30, left: 40},
//                width = square - margin.left - margin.right,
//                height = square - margin.top - margin.bottom;
//
//            // THIS SHOULD REALLY USE AN ORDINAL RANGE
//            var x = d3.scale.linear()
//                .range([0, height]);
//
//            var y = d3.scale.linear()
//                .range([height, 0]);
//
//            var color = d3.scale.category10();
//
//            var xAxis = d3.svg.axis()
//                .scale(x)
//                .orient("bottom");
//
//            var yAxis = d3.svg.axis()
//                .scale(y)
//                .orient("left");
//
//            svg.append("g");
////                .attr("width", width + margin.left + margin.right)
////                .attr("height", height + margin.top + margin.bottom)
////                .append("g")
////                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
//
//            x.domain([0,square]).nice();
//            y.domain([0,square]).nice();
//
//            y.domain([rangeBounds.lower.min, rangeBounds.upper.max]);
//            //y.range([height, 0]); // INVERTED
//
//            svg.append("g")
//                .attr("class", "y axis")
//                .call(yAxis)
//                .append("text")
//                .attr("class", "label")
//                .attr("transform", "rotate(-90)")
//                .attr("y", 6)
//                .attr("dy", ".71em")
//                .style("text-anchor", "end")
//                .text("Sepal Length (cm)");
//
//            svg.selectAll(".dot")
//                .data(gdata)
//                .enter().append("circle")
//                .attr("class", "dot")
//                .attr("r", 20)
//                .attr("cx", function(d) { return x(d.x); })
//                .attr("cy", function(d) { return y(d.y); })
//                .style("fill", "#379e51");
//
//            var legend = svg.selectAll(".legend")
//                .data(color.domain())
//                .enter().append("g")
//                .attr("class", "legend")
//                .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });
//
//            legend.append("rect")
//                .attr("x", width - 18)
//                .attr("width", 18)
//                .attr("height", 18)
//                .style("fill", color);
//
//            legend.append("text")
//                .attr("x", width - 24)
//                .attr("y", 9)
//                .attr("dy", ".35em")
//                .style("text-anchor", "end")
//                .text(function(d) { return d; });
//
//        });

    });

    // STUDENT PLACEMENTS GRAPH
    $('div.assessment-student-placement').each(function() {

        var mclassId = $(this).data('mclass-id');

        var url = "/teacher/data/student/placement/"+ mclassId;

        $.getJSON(url, function(data) {

            chartWidth = 600;
            chartHeight = 500; // MODIFIED BELOW BASED ON DATA
            numRecords = 24;

            //chartHeight = 20 * data.length;

            // TIME ON TASK
            (function(){

                var canvasSelector = 'div.assessment-student-placement'; // TODO MERGE WITH ABOVE USAGE

                /* CUSTOM */
                var fillByValue = function (d) {

                    if (d.score >= 200)
                        return "#3eb05b"; // FLATTY GREEN 62,176,91
                    else if (d.score >= 100)
                        return "#dfef34"; // SIMULATED FLATTY YELLOW 223 239 52 (9BA534)
                    else if (d.score >= 0)
                        return "#f7990d"; // FLATTY ORANGE 247, 153, 13
                    else
                        return "#f12e29"; // FLATTY RED 241, 46, 41
                };
                /* END CUSTOM */

                var margin = {top: 50, bottom: 50, left:175, right: 40};
                var width = chartWidth - margin.left - margin.right;
                var height = chartHeight - margin.top - margin.bottom;

                var xScale = d3.scale.linear().range([0, width]);
                var yScale = d3.scale.ordinal().rangeRoundBands([0, height], 1.8,0);

                var numTicks = 5;
                var xAxis = d3.svg.axis().scale(xScale)
                    .orient("top")
                    .tickSize((-height))
                    .ticks(numTicks);

                var svg = d3.select(canvasSelector).append("svg")
                    .attr("width", width+margin.left+margin.right)
                    .attr("height", height+margin.top+margin.bottom)
                    .attr("class", "base-svg");

                var barSvg = svg.append("g")
                    .attr("transform", "translate("+margin.left+","+margin.top+")")
                    .attr("class", "bar-svg");

                var x = barSvg.append("g")
                    .attr("class", "x-axis");

                var xMax = d3.max(data, function(d) { return d.score; } );
                var xMin = 0;
                xScale.domain([xMin, xMax]);
                yScale.domain(data.map(function(d) { return d.student; }));

                d3.select(".base-svg").append("text")
                    .attr("x", margin.left)
                    .attr("y", (margin.top)/2)
                    .attr("text-anchor", "start")
                    .text("")
                    .attr("class", "title");

                var groups = barSvg.append("g").attr("class", "labels")
                    .selectAll("text")
                    .data(data)
                    .enter()
                    .append("g");

                    groups.append("text")
                        .attr("x", "0")
                        .attr("y", function(d) { return yScale(d.student); })
                        .text(function(d) { return d.student; })
                        .attr("text-anchor", "end")
                        .attr("dy", "0.7em")
                        .attr("dx", "-.32em")
                        .attr("id", function(d,i) { return "label"+i; });

                var bars = groups
                    .attr("class", "bars")
                    .append("rect")
                    .attr("width", function(d) { return xScale(d.score); })
                    //.attr("height", height/numRecords)
                    .attr("height", 12)
                    .attr("x", xScale(xMin))
                    .attr("y", function(d) { return yScale(d.student); })
                    .attr("id", function(d,i) { return "bar"+i; })
                    .style("fill", fillByValue);

                    groups.append("text")
                        .attr("x", function(d) { return xScale(d.score); })
                        .attr("y", function(d) { return yScale(d.student); })
                        .text(function(d) { return d.score; })
                        .attr("text-anchor", "end")
                        .attr("dy", "0.9em")
                        .attr("dx", "-.32em")
                        .attr("id", "precise-value");

                    bars
                        .on("mouseover", function() {
                            var currentGroup = d3.select(this.parentNode);
                            currentGroup.select("rect").style("fill", "brown");
                            currentGroup.select("text").style("font-weight", "bold");
                        })
                        .on("mouseout", function() {
                            var currentGroup = d3.select(this.parentNode);
                            //currentGroup.select("rect").style("fill", "steelblue");
                            currentGroup.select("rect").style("fill", fillByValue);
                            currentGroup.select("text").style("font-weight", "normal");
                        })
//                        .on("click", function(d) {
//                            window.location = "<?php echo $this->url('mschool/teacher_student_progress', ['s_id' => '10000159', 'm_id' => $this->mclass->id]); ?>";
//                            //window.location = 'http://' + location.hostname + '/teacher/student/progress';
//                        })
                    ;

                x.call(xAxis);
                var grid = xScale.ticks(numTicks);
                barSvg.append("g").attr("class", "grid")
                    .selectAll("line")
                    .data(grid, function(d) { return d; })
                    .enter().append("line")
                    .attr("y1", 0)
                    .attr("y2", height+margin.bottom)
                    .attr("x1", function(d) { return xScale(d); })
                    .attr("x2", function(d) { return xScale(d); })
                    .attr("stroke", "white");

            })();

        });
    });

    // PROGRESS : TIME ON MATH
    $('div.progress-time-on-math').each(function() {

        var mclassId = $(this).data('mclass-id');

        var url = "/teacher/data/time-on-math/"+ mclassId;

        $.getJSON(url, function(data) {

            // SORT DATA
            data.sort(function(a,b) {

                if (a['time'] < b['time']) {
                    return -1;
                } else if (a['time'] > b['time']) {
                    return 1;
                }

                return 0;

            });

            data.reverse();

            chartWidth = 600;
            chartHeight = 500; // MODIFIED BELOW BASED ON DATA
            numRecords = 24;

            //chartHeight = 20 * data.length;

            // TIME ON MATH
            (function(){

                var canvasSelector = 'div.progress-time-on-math'; // TODO MERGE WITH ABOVE USAGE

                /* CUSTOM */
                var fillByValue = function (d) {

                    return "#3eb05b";

//                    if (d.score >= 200)
//                        return "#3eb05b"; // FLATTY GREEN 62,176,91
//                    else if (d.score >= 100)
//                        return "#dfef34"; // SIMULATED FLATTY YELLOW 223 239 52 (9BA534)
//                    else if (d.score >= 0)
//                        return "#f7990d"; // FLATTY ORANGE 247, 153, 13
//                    else
//                        return "#f12e29"; // FLATTY RED 241, 46, 41
                };
                /* END CUSTOM */

                var margin = {top: 50, bottom: 50, left:175, right: 100};
                var width = chartWidth - margin.left - margin.right;
                var height = chartHeight - margin.top - margin.bottom;

                var xScale = d3.scale.linear().range([0, width]);
                var yScale = d3.scale.ordinal().rangeRoundBands([0, height], 1.8,0);

                var numTicks = 5;
                var xAxis = d3.svg.axis().scale(xScale)
                    .orient("top")
                    .tickSize((-height))
                    .ticks(numTicks);

                var svg = d3.select(canvasSelector).append("svg")
                    .attr("width", width+margin.left+margin.right)
                    .attr("height", height+margin.top+margin.bottom)
                    .attr("class", "base-svg");

                var barSvg = svg.append("g")
                    .attr("transform", "translate("+margin.left+","+margin.top+")")
                    .attr("class", "bar-svg");

                var x = barSvg.append("g")
                    .attr("class", "x-axis");

                var xMax = d3.max(data, function(d) { return d.time; } );
                var xMin = 0;
                xScale.domain([xMin, xMax]);
                yScale.domain(data.map(function(d) { return d.student; }));

                d3.select(".base-svg").append("text")
                    .attr("x", margin.left)
                    .attr("y", (margin.top)/2)
                    .attr("text-anchor", "start")
                    .text("")
                    .attr("class", "title");

                var groups = barSvg.append("g").attr("class", "labels")
                    .selectAll("text")
                    .data(data)
                    .enter()
                    .append("g");

                groups.append("text")
                    .attr("x", "0")
                    .attr("y", function(d) { return yScale(d.student); })
                    .text(function(d) { return d.student; })
                    .attr("text-anchor", "end")
                    .attr("dy", "0.7em")
                    .attr("dx", "-.32em")
                    .attr("id", function(d,i) { return "label"+i; });

                var bars = groups
                    .attr("class", "bars")
                    .append("rect")
                    .attr("width", function(d) { return xScale(d.time); })
                    //.attr("height", height/numRecords)
                    .attr("height", 12)
                    .attr("x", xScale(xMin))
                    .attr("y", function(d) { return yScale(d.student); })
                    .attr("id", function(d,i) { return "bar"+i; })
                    .style("fill", fillByValue)
                    ;

                groups.append("text")
                    .attr("x", function(d) { return xScale(d.time); })
                    .attr("y", function(d) { return yScale(d.student); })
                    .text(function(d) { return d.time_display; })
                    //.attr("text-anchor", "end")
                    .attr("dy", "0.9em")
                    .attr("dx", "0.4em")
                    .attr("id", "time");

                bars
                    .on("mouseover", function() {
                        var currentGroup = d3.select(this.parentNode);
                        currentGroup.select("rect").style("fill", "brown");
                        currentGroup.select("text").style("font-weight", "bold");
                    })
                    .on("mouseout", function() {
                        var currentGroup = d3.select(this.parentNode);
                        //currentGroup.select("rect").style("fill", "steelblue");
                        currentGroup.select("rect").style("fill", fillByValue);
                        currentGroup.select("text").style("font-weight", "normal");
                    })
//                        .on("click", function(d) {
//                            window.location = "<?php echo $this->url('mschool/teacher_student_progress', ['s_id' => '10000159', 'm_id' => $this->mclass->id]); ?>";
//                            //window.location = 'http://' + location.hostname + '/teacher/student/progress';
//                        })
                ;

                x.call(xAxis);
                var grid = xScale.ticks(numTicks);
                barSvg.append("g").attr("class", "grid")
                    .selectAll("line")
                    .data(grid, function(d) { return d; })
                    .enter().append("line")
                    .attr("y1", 0)
                    .attr("y2", height+margin.bottom)
                    .attr("x1", function(d) { return xScale(d); })
                    .attr("x2", function(d) { return xScale(d); })
                    .attr("stroke", "white");

            })();

        });
    });

    // PROGRESS : LEARNING POINTS
    $('div.progress-learning-points').each(function() {

        var mclassId = $(this).data('mclass-id');

        var url = "/teacher/data/learning-points/"+ mclassId;

        $.getJSON(url, function(data) {

            // SORT DATA
            data.sort(function(a,b) {

                if (a['learning_points'] < b['learning_points']) {
                    return -1;
                } else if (a['learning_points'] > b['learning_points']) {
                    return 1;
                }

                return 0;

            });

            data.reverse();


            chartWidth = 600;
            chartHeight = 500; // MODIFIED BELOW BASED ON DATA
            numRecords = 24;

            // LEARNING POINTS
            (function(){

                var canvasSelector = 'div.progress-learning-points'; // TODO MERGE WITH ABOVE USAGE

                /* CUSTOM */
                var fillByValue = function (d) {

                    return "#3eb05b";

//                    if (d.score >= 200)
//                        return "#3eb05b"; // FLATTY GREEN 62,176,91
//                    else if (d.score >= 100)
//                        return "#dfef34"; // SIMULATED FLATTY YELLOW 223 239 52 (9BA534)
//                    else if (d.score >= 0)
//                        return "#f7990d"; // FLATTY ORANGE 247, 153, 13
//                    else
//                        return "#f12e29"; // FLATTY RED 241, 46, 41
                };
                /* END CUSTOM */

                var margin = {top: 50, bottom: 50, left:175, right: 100};
                var width = chartWidth - margin.left - margin.right;
                var height = chartHeight - margin.top - margin.bottom;

                var xScale = d3.scale.linear().range([0, width]);
                var yScale = d3.scale.ordinal().rangeRoundBands([0, height], 1.8,0);

                var numTicks = 5;
                var xAxis = d3.svg.axis().scale(xScale)
                    .orient("top")
                    .tickSize((-height))
                    .ticks(numTicks);

                var svg = d3.select(canvasSelector).append("svg")
                    .attr("width", width+margin.left+margin.right)
                    .attr("height", height+margin.top+margin.bottom)
                    .attr("class", "base-svg");

                var barSvg = svg.append("g")
                    .attr("transform", "translate("+margin.left+","+margin.top+")")
                    .attr("class", "bar-svg");

                var x = barSvg.append("g")
                    .attr("class", "x-axis");

                var xMax = d3.max(data, function(d) { return d.learning_points; } );
                var xMin = 0;
                xScale.domain([xMin, xMax]);
                yScale.domain(data.map(function(d) { return d.student; }));

                d3.select(".base-svg").append("text")
                    .attr("x", margin.left)
                    .attr("y", (margin.top)/2)
                    .attr("text-anchor", "start")
                    .text("")
                    .attr("class", "title");

                var groups = barSvg.append("g").attr("class", "labels")
                    .selectAll("text")
                    .data(data)
                    .enter()
                    .append("g");

                groups.append("text")
                    .attr("x", "0")
                    .attr("y", function(d) { return yScale(d.student); })
                    .text(function(d) { return d.student; })
                    .attr("text-anchor", "end")
                    .attr("dy", "0.7em")
                    .attr("dx", "-.32em")
                    .attr("id", function(d,i) { return "label"+i; });

                var bars = groups
                        .attr("class", "bars")
                        .append("rect")
                        .attr("width", function(d) { return xScale(d.learning_points); })
                        //.attr("height", height/numRecords)
                        .attr("height", 12)
                        .attr("x", xScale(xMin))
                        .attr("y", function(d) { return yScale(d.student); })
                        .attr("id", function(d,i) { return "bar"+i; })
                        .style("fill", fillByValue)
                    ;

                groups.append("text")
                    .attr("x", function(d) { return xScale(d.learning_points); })
                    .attr("y", function(d) { return yScale(d.student); })
                    .text(function(d) { return d.learning_points; })
                    //.attr("text-anchor", "end")
                    .attr("dy", "0.9em")
                    .attr("dx", "0.4em")
                    .attr("id", "learning_points");

                bars
                    .on("mouseover", function() {
                        var currentGroup = d3.select(this.parentNode);
                        currentGroup.select("rect").style("fill", "brown");
                        currentGroup.select("text").style("font-weight", "bold");
                    })
                    .on("mouseout", function() {
                        var currentGroup = d3.select(this.parentNode);
                        //currentGroup.select("rect").style("fill", "steelblue");
                        currentGroup.select("rect").style("fill", fillByValue);
                        currentGroup.select("text").style("font-weight", "normal");
                    })
//                        .on("click", function(d) {
//                            window.location = "<?php echo $this->url('mschool/teacher_student_progress', ['s_id' => '10000159', 'm_id' => $this->mclass->id]); ?>";
//                            //window.location = 'http://' + location.hostname + '/teacher/student/progress';
//                        })
                ;

                x.call(xAxis);
                var grid = xScale.ticks(numTicks);
                barSvg.append("g").attr("class", "grid")
                    .selectAll("line")
                    .data(grid, function(d) { return d; })
                    .enter().append("line")
                    .attr("y1", 0)
                    .attr("y2", height+margin.bottom)
                    .attr("x1", function(d) { return xScale(d); })
                    .attr("x2", function(d) { return xScale(d); })
                    .attr("stroke", "white");

            })();

        });
    });

    // PROGRESS : RESOURCE MAP
    $('.student-resource-map').each(function() {

        var studentId = $(this).data('student-id');

        var planGroupsDiv = $(this).find('.plan-groups');

        var loadindDiv = $(this).find('.loading-resource-map');

        var noResourceDiv = $(this).find('.no-resource-map');

        var url = '/teacher/data/map-visual-data/' + studentId;
        var imagePath = '/assets/mschool/images/resources';

        $.getJSON(url, function(data) {

            loadindDiv.hide();

            if (!data.length) {
                noResourceDiv.show();
                return;
            }

            planGroupsDiv.show();

            var planGroups = data[0]['plan_groups'];

            $.each(planGroups, function(planGroupIndex, planGroupValue) {

                var planGroupRow = $('<div>', {
                    'data-plan-group' : planGroupIndex,
                    'class' : 'plan-group',
                });

                var planGroupCol = $('<div>', {
                    'class' : '',
                });

                $.each(planGroupValue, function(stepIndex, stepsValue) {

                    if (stepsValue['image']) {

                        var img = $('<img>', {
                            'class' : 'resource-box',
                            'data-placement' : 'bottom',
                        });

                        img.attr('src', imagePath + '/' + stepsValue['image']);

                        if (stepsValue['description'] != null) {
                            img.attr('data-original-title', stepsValue['description']);
                            img.addClass('resource-tooltip');
                        }

                        planGroupCol.append(img);

                    }

                });

                planGroupRow.append(planGroupCol);

                planGroupsDiv.append(planGroupRow);

            });

            // INIT TOOL TIPS
            $('.resource-tooltip').tooltip();

        });

    });

});

