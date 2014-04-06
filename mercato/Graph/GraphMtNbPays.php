<label style="position: absolute; right: 5;"><input type="checkbox" name="Checkbox" id ="Checkbox"> <font color='#9ACD32'> Trier par montant/nombre d'achat</font></label>
<script>
{
var margin = {top: 50, right: 20, bottom: 30, left: 40},
    width = 960 - margin.left - margin.right,
    height = 600 - margin.top - margin.bottom;

var formatPercent = d3.format(".0%");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1, 1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(formatPercent);

var svg = d3.select("#bargraph").append("svg")
    .attr("id", "graph1")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .style('float','left')
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

//var zero = d3.format(",");

d3.tsv("Graph/data2.tsv", function(error, data) {

  data.forEach(function(d) {
    d.frequency = +d.frequency;
  });

  x.domain(data.map(function(d) { return d.letter; }));
  y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style({"text-anchor":"end","fill":"white"})
      .text("% présence sur le marché des transferts");


   svg.selectAll(".bar")
      .data(data)
    .enter().append('g').append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.letter); })
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d.frequency); })
      .attr("height", function(d) { return height - y(d.frequency); })
      .on("mouseout", function(){d3.select(this).style("fill", "#9ACD32");})
      .on("mouseover", function(){d3.select(this)
          .style({"fill": "white"});});
      
      $('.bar').tipsy({ 
        gravity: 'se', 
        offset: 10,
        html: true,
        title: function() {
          var d = this.__data__, n= d.depense, v=d.legend;
          return n+"€ en "+v+" transferts."
        }
      })
      
  d3.select("input#Checkbox").on("change", change);

  var sortTimeout = setTimeout(function() {
    d3.select("input#Checkbox").property("checked", true).each(change);
  }, 2000);
  
  d3.selectAll(".tick.major text").style("fill", "white");
  
  function change() {
    clearTimeout(sortTimeout);

    // Copy-on-write since tweens are evaluated after a delay.
    var x0 = x.domain(data.sort(this.checked
        ? function(a, b) { return b.nombre - a.nombre; }
        : function(a, b) { return b.frequency - a.frequency; })
        .map(function(d) { return d.letter; }))
        .copy();

    var transition = svg.transition().duration(750),
        delay = function(d, i) { return i * 50; };

    transition.selectAll(".bar")
        .delay(delay)
        .attr("x", function(d) { return x0(d.letter); })
        .attr("height", (this.checked
        ? function(d) { return (height - y(d.nombre)); }
        : function(d) { return height - y(d.frequency); }))
        .attr("y", (this.checked
        ? function(d) { return (y(d.nombre)); }
        : function(d) { return y(d.frequency); }));

    transition.select(".x.axis")
        .call(xAxis)
      .selectAll("g")
        .delay(delay);
  }
  
});
}
</script>