<script>

var margin4 = {top: 30, right: 20, bottom: 20, left: 40},
    width4 = 960 - margin4.left - margin4.right,
    height4 = 600 - margin4.top - margin4.bottom;

var x4 = d3.scale.linear()
    .range([0, width4]);

var y4 = d3.scale.linear()
    .range([height4, 0]);

var color4 = d3.scale.category20();

var xAxis4 = d3.svg.axis()
    .scale(x4)
    .orient("bottom");

var yAxis4 = d3.svg.axis()
    .scale(y4)
    .orient("left");

var svg4 = d3.select("#bubblechart").append("svg:svg")
    .attr('id','bubblesvg')
    .attr("width", width4 + margin4.left + margin4.right)
    .attr("height", height4 + margin4.top + margin4.bottom)
  .append("g")
    .attr("transform", "translate(" + margin4.left + "," + margin4.top + ")");

d3.tsv("Graph/data4.tsv", function(error, data) {
  data.forEach(function(d) {
    d.sepalLength = +d.sepalLength;
    d.sepalWidth = +d.sepalWidth;
  });

  x4.domain(d3.extent(data, function(d) { return d.sepalWidth; })).nice();
  y4.domain(d3.extent(data, function(d) { return d.sepalLength; })).nice();

  svg4.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height4 + ")")
      .call(xAxis4)
    .append("text")
      .attr("class", "label")
      .attr("x", width4)
      .attr("y", -6)
      .style({"text-anchor":"end", "fill":"white"})
      .text("Nombre de ventes");

  svg4.append("g")
      .attr("class", "y axis")
      .attr("transform", "translate(-10,0)")
      .call(yAxis4)
    .append("text")
      .attr("class", "label")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style({"text-anchor":"end", "fill":"white"})
      .text("Nombre d'achats")

  var dot = svg4.selectAll(".dot")
      .data(data)
    .enter().append("svg:circle")
      .attr("class", "dot")
      .attr("r", function(d) {return (d.sepalWidth + d.sepalLength)*1.5 ;})
      .attr("cx", function(d) { return x4(d.sepalWidth); })
      .attr("cy", function(d) { return y4(d.sepalLength); })
      .style("fill", function(d) { return color4(d.species); })
      
      $('svg circle').tipsy({ 
        gravity: 'n', 
        html: true,
        delayIn: 50, 
        delayOut: 1000,
        title: function() {
          var d = this.__data__, n= d.species, v=d.sepalWidth , a=d.sepalLength;
          return n + ",<br> Nombre d'achats: " + a + ",<br> Nombre de ventes: " + v +"."; 
        }
      })
      
  var mouseOn = function() { 
        var dot = d3.select(this);
      dot.transition()
		.duration(800).style("opacity", 1)
		.attr("r", 16).ease("elastic");          
      svg4.append("g")
		.attr("class", "guide")
	.append("line")
		.attr("x1", dot.attr("cx"))
		.attr("x2", dot.attr("cx"))
		.attr("y1", dot.attr("cy"))
		.attr("y2", height4 - margin4.top - margin4.bottom+60)
                .style("stroke", dot.style("fill"))
		.transition().delay(200).duration(400).styleTween("opacity", 
		function() { return d3.interpolate(1, 1); })
      svg4.append("g")
		.attr("class", "guide2")
	.append("line")
                .attr("x1", +dot.attr("cx"))
                .attr("x2", -10)
                .attr("y1", dot.attr("cy"))
                .attr("y2", dot.attr("cy"))
                .style("stroke", dot.style("fill"))
                .transition().delay(200).duration(400).styleTween("opacity", 
                 function() { return d3.interpolate(1, 1); })};
          
            
      var mouseOff = function() {
		var circle = d3.select(this);
		// go back to original size and opacity
		circle.transition()
                    .attr("r", function(d) {return (d.sepalWidth + d.sepalLength)*1.5 ;})
                    .style("fill", function(d) { return color4(d.species); })
		// fade out guide lines, then remove them
		d3.selectAll(".guide").transition().duration(100)
                    .styleTween("opacity",function() { return d3.interpolate(.5, 0); })
                    .remove()
                d3.selectAll(".guide2").transition().duration(100)
                    .styleTween("opacity",function() { return d3.interpolate(.5, 0); })
                    .remove()
	};
        
      dot.on("mouseover", mouseOn);
      dot.on("mouseout", mouseOff);
        
//  var legend = svg4.selectAll(".legend")
//      .data(color4.domain())
//    .enter().append("g")
//      .attr("class", "legend")
//      .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });
//
//  legend.append("rect")
//      .attr("x", width4 - 18)
//      .attr("width", 18)
//      .attr("height", 18)
//      .style("fill", color4);
//
//  legend.append("text")
//      .attr("x", width4 - 24)
//      .attr("y", 9)
//      .attr("dy", ".35em")
//      .style({"text-anchor":"end", "fill":"white"})
//      .text(function(d) { return d; });


  d3.selectAll(".tick.major text").style("fill", "white");
});

</script>
