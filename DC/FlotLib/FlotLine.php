<?php

namespace DC\FlotLib;

class FlotLine
{
	protected $sets = array();
	protected $_id;
	protected $settings = array();
	
	function __construct($width = 1000, $height = 500)
	{
		$this->_id = md5(rand(1,999999));
		
		$this->width = $width;
		$this->height = $height;
		$this->settings['x']['timeformat'] = '%b-%y';

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
	public function addSettings($setting = NULL) {
		if (is_array($setting)) {
			foreach($setting as $key => $value) {
				$this->settings[$key] = $value;
			}
		}
	}
	public function addData($setKey, $k, $v)
	{
		$this->sets["$setKey"]['data'][] = array($k, $v);
	}	
	
	public function drawDiv()
	{
		return '<div id="' . $this->_id . '" style="width:' . $this->width . 'px;height:' . $this->height .'px"></div>';
	}
	
	private function drawDataSets()
	{
		$str = 'var datasets = {';
		
		$c = 0;
		foreach($this->sets AS $key => $meta)
		{
			$str .= '"' . $key . '": {';
			$str .= isset($meta['label']) ? '	label:"' . $meta['label'] . '",' : '';
			$str .= '	data: [';
			$bits = array();
			foreach($meta['data'] AS $dk => $dval)
			{
				$bits[] = '[' . implode(',', $dval) . ']';
			}
			$str .= implode(',', $bits);
			$str .= ']';
			if(isset($meta['extra']))
			{
				$str .= ',' . $meta['extra'];
			}
//			$str .= ',color: ' . $c;
			$str .= '},';
		}
		
		$str .= '};';

		$str .= '
			var data = [];
			$.each(datasets, function (k, v) {
				data.push(datasets[k]);
			});		
		';
		
		return $str;
	}
	
	protected function drawPlot()
	{
		return '

		 
			$.plot($("#' . $this->_id . '"), data, {
				yaxes: [{min: -100}, {position: "right"}],
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
	}
	
	public function draw($div = true, $scriptTags = true)
	{
		$str = '';
		if($div)
		{
			$str .= $this->drawDiv();
		}
		
		if($scriptTags) {
			$str .= '<script type="text/javascript">';
			}
		$str .= '$(document).ready(function () { ';
		$str .= $this->drawDataSets();
		
		$str .= $this->drawPlot();
		
		$str .= $this->drawExtra();
		$str .= ' })';
		if($scriptTags) {
			$str .= '</script>';
		}
		return $str;
	}
	
	protected function drawExtra()
	{
		$str = "

			// Thanks: http://stackoverflow.com/questions/1990512/add-comma-to-numbers-every-three-digits-using-jquery
			function digits(number){
				number = \"\" + number;
				return number.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, \"$1,\");
			}

		 function showTooltip(x, y, contents) {

			
				$('<div id=\"tooltip\">' + contents + '</div>').css( {
					position: 'absolute',
					display: 'none',
					top: y-50,
					left: x,
					border: '0',
					color: '#ddd',
					padding: '4px',
					'background-color': '#000',
					opacity: 0.70,
					'font-size': '14px',
					'border-radius': '4px',
				}).appendTo('body').fadeIn(200);
			}	
		";
		
		$str .= '
			$("#' . $this->_id . '").bind("plothover", function (event, pos, item) {
				if (item) {
					if (previousPoint != item.dataIndex) {
						previousPoint = item.dataIndex;
						
						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
							y = item.datapoint[1].toFixed(2);
						
						if(y.match(/\.00$/)) {
							y = digits(parseInt(y))
						}
						';
						if (isset($this->settings['xTooltipDate']) && $this->settings['xTooltipDate'] == true) {
							$str .= '
							var d = new Date(parseInt(x));
							showTooltip(item.pageX, item.pageY, d.getDate()+"-"+(d.getMonth()+1)+"-"+d.getFullYear()+"</br>"+y);
							';
						} else {
							
							$str .= 'showTooltip(item.pageX, item.pageY, y);';
							
						}
					$str .='
					}
				}
				else {
					$("#tooltip").remove();
					previousPoint = null;            
				}
			});
		';	
		return $str;
	}
}

?>
