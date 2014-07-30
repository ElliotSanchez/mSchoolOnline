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

        var url = "/teacher/data/learning-points/grade-averages/"+ mclassId;

        var chartNumber = 0;

        var margin = {top: 20, right: 20, bottom: 30, left: 40},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        gradeRangeMax = 5;

        var rangeMap = {
            '1' : [[0,201.0], [gradeRangeMax, 403.0], [0,451.0], [gradeRangeMax, 499.0]], // K
            '2' : [[0,406.0], [gradeRangeMax, 425.0], [0,474.0], [gradeRangeMax, 523.0]], // 1
            '3' : [[0,428.0], [gradeRangeMax, 447.0], [0,494.5], [gradeRangeMax, 542.0]], // 2
            '4' : [[0,450.0], [gradeRangeMax, 469.0], [0,516.0], [gradeRangeMax, 563.0]], // 3
            '5' : [[0,471.5], [gradeRangeMax, 490.0], [0,532.0], [gradeRangeMax, 574.0]], // 4
            '6' : [[0,487.5], [gradeRangeMax, 501.0], [0,542.5], [gradeRangeMax, 584.0]], // 5
            '7' : [[0,497.0], [gradeRangeMax, 509.0], [0,518.0], [gradeRangeMax, 527.0]], // 6
            '8' : [[0,518.0], [gradeRangeMax, 528.0], [0,532.0], [gradeRangeMax, 536.0]], // 7
            '9' : [[0,532.0], [gradeRangeMax, 537.0], [0,546.0], [gradeRangeMax, 555.0]], // 8
            '10': [[0,546.0], [gradeRangeMax, 556.0], [0,591.0], [gradeRangeMax, 626.0]], // 9
            '11': [[0,571.0], [gradeRangeMax, 587.0], [0,612.0], [gradeRangeMax, 637.0]], // 10
            '12': [[0,586.5], [gradeRangeMax, 602.0], [0,627.0], [gradeRangeMax, 652.0]], // 11
            '13': [[0,601.5], [gradeRangeMax, 617.0], [0,708.5], [gradeRangeMax, 800.0]]  // 12
            };

        $.getJSON(url, function(data) {

            var assessment1DataSet = data[0];
            var assessment1Data = assessment1DataSet.data;

            $(assessment1Data).each(function(index,gradeData) {

                var gradeLevelId = gradeData.grade_level_id;
                var gradeLevelName = gradeData.grade_level_name;

                /**
                 * ORIGINAL EXAMPLE
                 * http://bl.ocks.org/mbostock/3887118
                 */

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
                    .attr("data-grade-level-id", gradeLevelId)
                    .attr("style", (chartNumber < 1) ? ('') : ('display: none')) // HIDES ALL CHARTS AFTER THE FIRST
                    .attr("class", "grade-avg-chart")
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

                svg.selectAll(".dot")
                    .data([gradeData])
                    .enter().append("circle")
                    .attr("class", "dot")
                    .attr("r", 15)
                    .attr("cx", function(d) { return x(1); })
                    .attr("cy", function(d) { return y(d.avg); })
                    .style("fill", function(d) { return "#f7990d"; });

                // PLOT RANGES
                var gradeRanges = rangeMap[gradeLevelId-1]; // TODO WE'RE GETTING LUCKY ON THIS MATH

                svg.selectAll(".dot2")
                    .data(gradeRanges)
                    .enter().append("circle")
                    .attr("class", "dot")
                    .attr("r", 10)
                    .attr("cx", function(d) { return x(d[0]); })
                    .attr("cy", function(d) { return y(d[1]); })
                    .style("fill", function(d) { return "gainsboro"; });

                // ADD CONTROLS
                var button = $('<button>').attr('class', 'grade-level-btn btn ' + ((chartNumber < 1) ? ('btn-info') : ('')))
                    .html(gradeLevelName)
                    .click(function() {
                        $('.grade-level-btn').removeClass('btn-info');
                        $(this).addClass('btn-info');
                        $('.grade-avg-chart').hide();
                        $('[data-grade-level-id="' + gradeLevelId + '"]').show();
                    });

                $('#grade-controls').append(button);


                chartNumber++; // HIDES ALL CHARTS AFTER THE FIRST
            });

        });

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

// ---------------------

//
//        var mclassId = $(this).data('mclass-id');
//
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

