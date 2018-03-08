<?php
?>
<html>
	<head>
		<title>Dalton Dashboard</title>

		<meta charset="utf-8">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style type="text/css">
			body {
				background-color: #f0f0f2;
				margin: 0;
				padding: 0;
				font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;

			}
			div.main {
				width: 75%;
				margin: 5em auto;
				padding: 50px;
				background-color: #fff;
				border-radius: 1em;
			}
            div#gauges{
                text-align: center;
            }
            div.gauge {
                display: inline-block;
/*                border: 1px solid #f0f0f2;
                border-radius: 4px;*/
                padding: 5px;
            }
            .sensor-value, .sensor-name {
                font-weight: 800;
            }
            .sensor-name {

            }
            .sensor-value{

            }

			a:link, a:visited {
				color: #38488f;
				text-decoration: none;
			}
			@media (max-width: 700px) {
				body {
					background-color: #fff;
				}
				div {
					width: auto;
					margin: 0 auto;
					border-radius: 0;
					padding: 1em;
				}
			}
		</style>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="js/gauge.min.js"></script>
	</head>

	<body>
		<div class="main">
			<h1>Dalton Dashboard</h1>
			<p>The very first version of our magnificent dashboard</p>
            <div id="gauges">

            </div>
		</div>
	</body>
</html>
<script>
	let opts = {};

	opts.temperature = {
		angle: 0.15, // The span of the gauge arc
		lineWidth: 0.44, // The line thickness
		radiusScale: 1, // Relative radius
		pointer: {
			length: 0.6, // // Relative to gauge radius
			strokeWidth: 0.035, // The thickness
			color: '#000000' // Fill color
		},
		limitMax: false,     // If false, max value increases automatically if value > maxValue
		limitMin: false,     // If true, the min value of the gauge will be fixed
		percentColors: [[0.0, "#a9d70b" ], [0.50, "#f9c802"], [1.0, "#ff0000"]],
		strokeColor: '#E0E0E0',  // to see which ones work best for you
		generateGradient: true,
		highDpiSupport: true,     // High resolution support
        max: 60000,
        min: 0

	};
	opts.TEMT6000 = {
		angle: 0.15, // The span of the gauge arc
		lineWidth: 0.44, // The line thickness
		radiusScale: 1, // Relative radius
		pointer: {
			length: 0.6, // // Relative to gauge radius
			strokeWidth: 0.035, // The thickness
			color: '#000000' // Fill color
		},
		limitMax: false,     // If false, max value increases automatically if value > maxValue
		limitMin: false,     // If true, the min value of the gauge will be fixed
		percentColors: [[0.0, "#5150D7" ], [0.50, "#77F93A"], [1.0, "#EAD425"]],
		strokeColor: '#E0E0E0',  // to see which ones work best for you
		generateGradient: true,
		highDpiSupport: true,     // High resolution support
		max: 1000,
		min: 0
    };

	opts.HR202L = {
		angle: 0.15, // The span of the gauge arc
		lineWidth: 0.44, // The line thickness
		radiusScale: 1, // Relative radius
		pointer: {
			length: 0.6, // // Relative to gauge radius
			strokeWidth: 0.035, // The thickness
			color: '#000000' // Fill color
		},
		limitMax: false,     // If false, max value increases automatically if value > maxValue
		limitMin: false,     // If true, the min value of the gauge will be fixed
		percentColors: [[0.0, "#5150D7" ], [0.50, "#77F93A"], [1.0, "#EAD425"]],
		strokeColor: '#E0E0E0',  // to see which ones work best for you
		generateGradient: true,
		highDpiSupport: true,     // High resolution support
		max: 1000,
		min: 0
	};

	opts.ph = {
		angle: 0.15, // The span of the gauge arc
		lineWidth: 0.44, // The line thickness
		radiusScale: 1, // Relative radius
		pointer: {
			length: 0.6, // // Relative to gauge radius
			strokeWidth: 0.035, // The thickness
			color: '#000000' // Fill color
		},
		limitMax: false,     // If false, max value increases automatically if value > maxValue
		limitMin: false,     // If true, the min value of the gauge will be fixed
		percentColors: [[0.0, "#5150D7" ], [0.50, "#77F93A"], [1.0, "#EAD425"]],
		strokeColor: '#E0E0E0',  // to see which ones work best for you
		generateGradient: true,
		highDpiSupport: true,     // High resolution support
		max: 0,
		min: 14
	};

	opts.YFS201 = {
		angle: 0.15, // The span of the gauge arc
		lineWidth: 0.44, // The line thickness
		radiusScale: 1, // Relative radius
		pointer: {
			length: 0.6, // // Relative to gauge radius
			strokeWidth: 0.035, // The thickness
			color: '#000000' // Fill color
		},
		limitMax: false,     // If false, max value increases automatically if value > maxValue
		limitMin: false,     // If true, the min value of the gauge will be fixed
		percentColors: [[0.0, "#a9d70b" ], [0.50, "#f9c802"], [1.0, "#ff0000"]],
		strokeColor: '#E0E0E0',  // to see which ones work best for you
		generateGradient: true,
		highDpiSupport: true,     // High resolution support
		max: 1000,
		min: 0
	};

	let gauges = {};
	$.ajaxSetup({
		// Disable caching of AJAX responses
		cache: false
	});
	let parse_value = function (value) {
		let str = value.toString();

		return str.indexOf('.') === -1 ? str.slice(0,2) + '.' + (parseInt(str.slice(2)) ? str.slice(2).slice(0, 3) : '000') : value.toFixed(3);
	};
	let factor_value = function(value, type)
    {
    	switch(type)
        {
            case 'TEMT6000':
                return value * 0.9765625;
            case 'HR202L':
				return -47.65 * ((value/ 1023) * 5) + 290.63;
            case 'ph':
            	return (14 / 1023) * value;
            default:
            	return value;
        }
    };
    $(function(){
    	$.get('data.php', function(data){
    		let sensors = JSON.parse(data);
    		for (sensor of sensors)
			{
				sensor.value = factor_value(sensor.value, sensor.sensor_type);
				if (!opts[sensor.sensor_type])
					throw 'Unknown type:' + sensor.sensor_type;
				let canvas =
					$('<canvas/>',{id: 'sensor_' + sensor.sensor_id, 'class':'sensor_gauge sensor_gauge_' + sensor.sensor_type})
						.width(400)
						.height(280);
				let div = $('<div></div>', {class: 'gauge gauge-type-' + sensor.sensor_type, id: 'gauge_' + sensor.sensor_id});
				div.append($('<span></span>', {class: 'sensor-name'}).append(sensor.sensor_name));
				div.append($('<br/>'));
				div.append(canvas);
				div.append($('<br/>'));
				$('#gauges').append(div);
				div.append($('<span></span>', {class: 'sensor-value'}).append(parse_value(sensor.value)));
				let canvas_elem = canvas.get(0);
				let gauge = new Gauge(canvas_elem).setOptions(opts[sensor.sensor_type]); // create sexy gauge!
				gauge.maxValue = opts[sensor.sensor_type].max; // set max gauge value
				gauge.setMinValue(opts[sensor.sensor_type].min || 0);  // Prefer setter over gauge.minValue = 0
				gauge.animationSpeed = 32; // set animation speed (32 is default value)
				gauge.set(sensor.value); // set actual value
				gauges[sensor.sensor_id] = gauge;
                setInterval(function(){
                	$.get('data.php', function(data){
						let sensors = JSON.parse(data);
						    for (sensor of sensors)
							{
								sensor.value = factor_value(sensor.value, sensor.sensor_type);
								gauges[sensor.sensor_id].set(sensor.value);
								$('#gauge_' + sensor.sensor_id).find('span.sensor-value').html(parse_value(sensor.value));
							}
                    });
                },5000)
			}
        });
    })
</script>