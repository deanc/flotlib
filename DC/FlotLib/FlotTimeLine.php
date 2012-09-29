<?php

namespace DC\FlotLib;

class FlotTimeLine extends FlotLine
{
	
	function __construct($width = 1000, $height = 500)
	{
		parent::__construct($width, $height);
	}
	
	protected function drawPlot()
	{

		return '
		var options = {
				yaxes: [{min: 0}, {position: "right"}],
				xaxis: { 
					mode: "time",
					timeformat: "'.$this->settings['x']['timeformat'].'",
					monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					minTickSize: [1, "month"]
				},
				grid: { hoverable: true, clickable: true },
                series: {
                         lines: { show: true , shadowSize:0},
                         points: { show: false }
                },
				legend: {
					position: "nw"
				},
				selection: { mode: "x" }
			}
		
		var plot =	$.plot($("#' . $this->_id . '"), data, options);


		';	
	}
}

?>
