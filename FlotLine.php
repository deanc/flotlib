<?php

class FlotLine
{
	private $sets = array();
	private $_id;
	
	function __construct($width = 1000, $height = 500)
	{
		$this->_id = md5(rand(1,999999));
		
		$this->width = $width;
		$this->height = $height;
	}
	
	public function addSet($setKey, $label, $extra = null)
	{
		$this->sets["$setKey"] = array(
			'label' => $label
			, 'data' => array()
		);
		
		if(!empty($extra))
		{
			$this->sets["$setKey"]['extra'] = $extra;
		}
	}
	
	public function addData($setKey, $k, $v)
	{
		$this->sets["$setKey"]['data'][] = array($k, $v);
	}
	
	public function draw()
	{
		echo '<div id="' . $this->_id . '" style="width:1200;height:700px"></div>';
		echo '<script type="text/javascript">';
		echo 'var datasets = {';
		
		foreach($this->sets AS $key => $meta)
		{
			echo '"' . $key . '": {';
			echo '	label:"' . $meta['label'] . '",';
			echo '	data: [';
			$bits = array();
			foreach($meta['data'] AS $dk => $dval)
			{
				$bits[] = '[' . implode(',', $dval) . ']';
			}
			echo implode(',', $bits);
			echo ']';
			if(isset($meta['extra']))
			{
				echo ',' . $meta['extra'];
			}
			echo '},';
		}
		
		echo '};';
		
		echo '
			var data = [];
			$.each(datasets, function (k, v) {
				data.push(datasets[k]);
			});
		 
			$.plot($("#' . $this->_id . '"), data, {
				yaxes: [{min: -100}, {position: "right"}],
				xaxis: { 
					mode: "time",
					timeformat: "%b-%y",
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
				}
			});		
		';
		
		echo "
		
		 function showTooltip(x, y, contents) {
				$('<div id=\"tooltip\">' + contents + '</div>').css( {
					position: 'absolute',
					display: 'none',
					top: y + 5,
					left: x + 5,
					border: '1px solid #fdd',
					padding: '2px',
					'background-color': '#fee',
					opacity: 0.80
				}).appendTo('body').fadeIn(200);
			}	
		";
		
		echo '
			$("#' . $this->_id . '").bind("plothover", function (event, pos, item) {
				if (item) {
					if (previousPoint != item.dataIndex) {
						previousPoint = item.dataIndex;
						
						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
							y = item.datapoint[1].toFixed(2);
						
						showTooltip(item.pageX, item.pageY, y);
					}
				}
				else {
					$("#tooltip").remove();
					previousPoint = null;            
				}
			});
		';
		echo '</script>';
	}
}

?>