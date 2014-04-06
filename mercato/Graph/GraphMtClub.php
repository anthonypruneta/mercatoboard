<script>
{

var zero = d3.format(",");

var width = 960,
    height = 600,
    radius = Math.min(width, height) / 3;

var color = d3.scale.ordinal()
    .range(["#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32","#9ACD32"]);

var arc = d3.svg.arc()
    .outerRadius(radius + 30)
    .innerRadius(radius - 70);

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) { return d.Montant; });

var svg2 = d3.select("#circlechart").append("svg")
    .attr("id", "graph2")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(480,350)");
      
d3.csv("Graph/data.csv", function(error, data) {

  data.forEach(function(d) {
    d.Montant = +d.Montant;
  });

  var g = svg2.selectAll(".arc")
      .data(pie(data))
    .enter().append("g")
      .attr("class", "arc");

  var cercle = g.append("path")
      .attr("d", arc)
      .style("fill", function(d) { return color(d.data.Club); })
      .on("mouseout", function(){d3.select(this).style("fill", "#9ACD32");})
      .on("mouseover", function(){d3.select(this)
          .style({"fill": "white"});});
      
    $('svg path').tipsy({ 
        gravity: 's', 
        html: true,
        title: function() {
          var d = this.__data__, n = zero(d.data.Montant);
          return "Montant dépensé: " + n +"."; 
        }
      })
      
  g.append("text")
      .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
      .attr("dy", ".35em")
      .style({"text-anchor":"middle" , "font-weight":"bold", "font-size":"9"})
      .text(function(d) { return d.data.Club; });
    
});
}

</script>