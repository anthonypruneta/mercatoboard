<label style="margin: auto;margin-left: 60%;"><input type="radio" name="mode" value="grouped"><font color='#9ACD32'> Groupées</font></label>
<label style="position: relative;margin: auto"><input type="radio" name="mode" value="stacked" checked><font color='#9ACD32'> Stackées</font></label>
<script>
var tableau;
var yGroupMax;
var yStackMax;
var n = 2;
var margin3 = {top: 20, right: 20, bottom: 30, left: 40},
    width3 = 960 - margin3.left - margin3.right,
    height3 = 500 - margin3.top - margin3.bottom;

var zero = d3.format(",");

var x3 = d3.scale.ordinal()
    .domain(d3.range(31))
    .rangeRoundBands([0, width3], .08);

var ya = d3.scale.linear()
    .domain([0, yStackMax])
    .rangeRound([height3, 0]);

var color3 = d3.scale.ordinal()
    .range(["#9ACD32", "white"]);

var xAxis3 = d3.svg.axis()
    .scale(x3)
    .tickSize(0)
    .tickPadding(6)
    .orient("bottom");

var yAxis3 = d3.svg.axis()
    .scale(ya)
    .orient("left")
    .tickFormat(d3.format(".2s"));

var svg3 = d3.select("#stackgraph").append("svg")
    .attr("width", width3 + margin3.left + margin3.right)
    .attr("height", height3 + margin3.top + margin3.bottom)
  .append("g")
    .attr("transform", "translate(" + margin3.left + "," + margin3.top + ")");
    


d3.csv("Graph/data3.csv", function(error, data) {
  
  tableau = data;
  color3.domain(d3.keys(data[0]).filter(function(key) { return key !== "Date"; }));
   
  yGroupMax = "19";
  yStackMax = "37";

  data.forEach(function(d) {
    var y0 = 0;
    d.ages = color3.domain().map(function(name) { return {name: name, y0: y0, y1: y0 += +d[name]}; });
    d.total = d.ages[d.ages.length - 1].y1;
  });

  //data.sort(function(a, b) { return b.total - a.total; });

  x3.domain(data.map(function(d) { return d.Date; }));
  ya.domain([0, d3.max(data, function(d) { return d.total; })]);

  svg3.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height3 + ")")
      .call(xAxis3);

  svg3.append("g")
      .attr("class", "y axis")
      .call(yAxis3)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style({"text-anchor":"end", "fill":"white"})
      .text("Nombre Achats / Prets par jour");

  var date = svg3.selectAll(".date")
      .data(data)
    .enter().append("g")
      .attr("class", "g")
      .attr("transform", function(d) { return "translate(" + x3(d.Date) + ",0)"; });
      

  var rect3 = date.selectAll("rect")
      .data(function(d) { return d.ages; })
    .enter().append("rect")
      .attr("class", "inforect")
      .attr("width", x3.rangeBand()/2)
      .attr("y", function(d) { return ya(d.y1); })
      .attr("x", "6.5")
      .attr("height", function(d) { return ya(d.y0) - ya(d.y1); })
      .style("fill", function(d) { return color3(d.name); })
      .on("mouseout", function(){d3.select(this).style("fill", function(d) { return color3(d.name); });})
      .on("mouseover", function(){d3.select(this).style({"fill": "steelblue"});});
  
    $('.inforect').tipsy({ 
        gravity: 'n', 
        html: true,
        title: function() {
          var d = this.__data__, a = d.y1, c = color3(d.name), p = d.y1 - d.y0;
          if (c == "#9ACD32") {return "A ce jour, il y a eu<br>"+ a + " achat(s) effectué(s)"}
          else {return "A ce jour, il y a eu<br>"+ p +" prêt(s) effectué(s)"}; 
        }
      })
        
  var legend3 = svg3.selectAll(".legend")
      .data(color3.domain().slice().reverse())
    .enter().append("g")
      .attr("class", "legend")
      .attr("transform", function(d, i) { return "translate(-80," + i * 20 + ")"; });

  
  legend3.append("rect")
      .attr("x", width3 - 18)
      .attr("width", 18)
      .attr("height", 18)
      .style("fill", color3);

  legend3.append("text")
      .attr("x", width3 - 24)
      .attr("y", 9)
      .attr("dy", ".35em")
      .style({"text-anchor":"end", "fill":"white"})
      .text(function(d) { return d; });
      
d3.selectAll("input").on("change", change);

var timeout = setTimeout(function() {
  d3.select("input#mode[value=\"grouped\"]").property("checked", true).each(change);
}, 2000000);

function change() {
  clearTimeout(timeout);
  if (this.value === "grouped") transitionGrouped();
  else transitionStacked();
}

function transitionGrouped() {
        
  ya.domain([0, 37]);
  rect3.transition()
      .duration(500)
      .delay(function(d, i) { return i * 10; })
      .attr("x", function(d, i, j) {if ((x3(d.x3) * i-4)<0){return (x3(d.x3) * i+3);} else{return (x3(d.x3) * i-6)};})
      .attr("width", x3.rangeBand() / 3)
    .transition()
      .attr("y", function(d) { return ya(d.y1 - d.y0); })
      .attr("height", function(d) { return ya(d.y0) - ya(d.y1); })
      
      
}

function transitionStacked() {
  ya.domain([0, yStackMax]);

  rect3.transition()
      .duration(500)
      .delay(function(d, i) { return i * 10; })
      .attr("y", function(d) { return ya(d.y1); })
      .attr("height", function(d) { return ya(d.y0) - ya(d.y1); })
    .transition()
      .attr("x", "6.5")
      .attr("width", x3.rangeBand()/2);
}

d3.selectAll(".tick.major text").style("fill", "white");
});

</script>