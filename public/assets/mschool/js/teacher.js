$(function() {
    // CLASS SWITCHER
    $('select.teacher-class-switcher').change(function() {
        var url = $(this).data('url');
        var id = $(this).find('option:selected').data('mclassid');
        var host = location.host;

        window.location = 'http://' + host + url + '/' + id;
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

                    var img = $('<img>', {
                        'class' : 'resource-tooltip',
                        'data-placement' : 'bottom',
                        'title' : '',
                        'data-original-title' : stepsValue['description'],
                    });

                    if (stepsValue['image']) {
                        img.attr('src', imagePath + '/' + stepsValue['image']);
                    } else {
                        img.attr('src', imagePath + '/mschool.jpg');
                    }

                    img.css('width', '100px');
                    img.css('padding', '8px');

                    planGroupCol.append(img);

                });

                planGroupRow.append(planGroupCol);

                planGroupsDiv.append(planGroupRow);

            });

            // INIT TOOL TIPS
            $('.resource-tooltip').tooltip();

        });

    });

});

