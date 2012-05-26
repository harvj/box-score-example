function calc_radius(made, missed) {
	var total = made + missed;
	var min_radius = 7;
	if (total == 0) {
		return 1;
	}
	else {
		return (min_radius - (total - 2)) * total;
	}
}

function create_chart(pid, cid) {

	$.getJSON('chart.php?id=' + pid, function(data) {
		var chartHeight = 245;
		var chartWidth  = 350;
		var xC    = d3.scale.linear().domain([-245,245]).range([0,chartWidth]); 
		var yC    = d3.scale.linear().domain([250,-5]).range([chartHeight,0]);
		var color = d3.scale.linear().domain([0, 0.5, 1]).range(["red", "#a8a8a8", "green"]);
		
		var view = d3.select(cid)
							.append("svg:svg")
							.attr("width", chartWidth)
							.attr("height", chartHeight)

		view.selectAll('circle')
							.data(data)
							.enter().append("svg:circle")
							.attr("cx", function(d) { return xC(d.x) })
					    .attr("cy", function(d) { return yC(d.y) })
							.attr("stroke", function(d) { return color(d.made / (d.made + d.missed)) })
							.attr("fill", function(d) { return color(d.made / (d.made + d.missed)) })
							.attr("r", function (d) { return calc_radius(d.made, d.missed) })
							.on("mouseover", function(d) {						
		d3.select(this).append("text")
		     .attr("x", function(d) { return xC(d.x) })
		     .attr("y", function(d) { return yC(d.y) })
		     .attr("dx", -3) // padding-right
		     .attr("dy", ".35em") // vertical-align: middle
		     .attr("text-anchor", "end") // text-align: right
		     .text(function(d) { return d.made })
	})

		// overlays the zone grid on the shot chart, set drawGrid to false to skip the grid
		// THIS IS NOT THE MASTER GRID used to determine which region a shot was taken in. That is controlled on the server side.
		var drawGrid = false;
		if (drawGrid) {
			var gridColor = "#bbbbbb";
			var endX  = 245;
			var midX1 = 220;
			var midX2 = 160;
			var midX3 =  65;
			var hiY   = 250;
			var midY1 = 195;
			var midY2 = 150;
			var midY3 =  60;
			var lowY  =  -5;
			view.append("svg:line").attr("x1", xC(-endX)).attr("y1", yC(midY3)).attr("x2", xC(endX)).attr("y2", yC(midY3))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(-midX1)).attr("y1", yC(lowY)).attr("x2", xC(-midX1)).attr("y2", yC(hiY))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(-midX2)).attr("y1", yC(lowY)).attr("x2", xC(-midX2)).attr("y2", yC(hiY))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(-midX3)).attr("y1", yC(lowY)).attr("x2", xC(-midX3)).attr("y2", yC(hiY))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(0)).attr("y1", yC(lowY)).attr("x2", xC(0)).attr("y2", yC(hiY))
					      .style("stroke", gridColor).style("stroke-width", 1);
	  	view.append("svg:line").attr("x1", xC(midX3)).attr("y1", yC(lowY)).attr("x2", xC(midX3)).attr("y2", yC(hiY))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(midX2)).attr("y1", yC(lowY)).attr("x2", xC(midX2)).attr("y2", yC(hiY))
		  		      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(midX1)).attr("y1", yC(lowY)).attr("x2", xC(midX1)).attr("y2", yC(hiY))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(-midX1)).attr("y1", yC(midY3)).attr("x2", xC(-midX2)).attr("y2", yC(midY2))
			  	      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(-midX2)).attr("y1", yC(midY2)).attr("x2", xC(-midX3)).attr("y2", yC(midY1))
					      .style("stroke", gridColor).style("stroke-width", 1);
			view.append("svg:line").attr("x1", xC(-midX3)).attr("y1", yC(midY1)).attr("x2", xC(midX3)).attr("y2", yC(midY1))
			  	      .style("stroke", gridColor).style("stroke-width", 1);
	  	view.append("svg:line").attr("x1", xC(midX3)).attr("y1", yC(midY1)).attr("x2", xC(midX2)).attr("y2", yC(midY2))
					      .style("stroke", gridColor).style("stroke-width", 1);
	  	view.append("svg:line").attr("x1", xC(midX2)).attr("y1", yC(midY2)).attr("x2", xC(midX1)).attr("y2", yC(midY3))
			  	      .style("stroke", gridColor).style("stroke-width", 1);
		 	view.append("svg:line").attr("x1", xC(-midX2)).attr("y1", yC(midY2)).attr("x2", xC(midX2)).attr("y2", yC(midY2))
					      .style("stroke", gridColor).style("stroke-width", 1);
		}
	});
}

$(document).ready(function() {
	$('span.show-more').click(function() {
		if ($(this).parent().next().is(':visible')) {
			$(this).parent().next().slideUp(100);
		} else {
			$("[id^=more]:visible").slideUp(100);
			$(this).parent().next().slideDown(100);
			if ($(this).hasClass('fgp')) {
				$('svg').remove();
				var player_id = $(this).data('id');
				var chart_id = '#more-shotchart-' + player_id;
				create_chart(player_id, chart_id);
			}
		}
	});	
	
	
	$('#all-off_reb').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-off-reb').delay(150).fadeIn();
		}, 
		function() {
			$('#show-off-reb').show();
		}
	);
 	$('#all-fgp').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-fgp').delay(150).fadeIn();
		}, 
		function() {
			$('#show-fgp').show();
		}
	);
	$('#all-ftp').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-ftp').delay(150).fadeIn();
		}, 
		function() {
			$('#show-ftp').show();
		}
	);
	$('#all-three-p').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-three-p').delay(150).fadeIn();
		}, 
		function() {
			$('#show-three-p').show();
		}
	);
	$('#all-def_reb').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-def-reb').delay(150).fadeIn();
		}, 
		function() {
			$('#show-def-reb').show();
		}
	);
	$('#all-steals').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-steals').delay(150).fadeIn();
		},
		function() {
			$('#show-steals').show();
		}
	);
	$('#all-blocks').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-blocks').delay(150).fadeIn();
		},
		function() {
			$('#show-blocks').show();
		}
	);
	$('#all-turnovers').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-turnovers').delay(150).fadeIn();
		},
		function() {
			$('#show-turnovers').show();
		}
	);
	$('#all-points').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-points').delay(150).fadeIn();
		},
		function() {
			$('#show-points').show();
		}
	);
	$('#all-assists').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-assists').delay(150).fadeIn();
		},
		function() {
			$('#show-assists').show();
		}
	);
	$('#all-fouls').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-fouls').delay(150).fadeIn();
		},
		function() {
			$('#show-fouls').show();
		}
	);
	$('#all-ft_attempted').hoverIntent(
		function() {
			$("[id^=show]").hide();
			$('#show-foul-shots').delay(150).fadeIn();
		},
		function() {
			$('#show-foul-shots').show();
		}
	);
	
});