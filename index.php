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
            div.gauge {
                display: inline-block;
            }
            .sensor-value, .sensor-name {
                font-weight: 800;
            }
            .sensor-name {
                margin-left: 33%;
            }
            .sensor-value{
                margin-left: 45%;
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
	let opts = {
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

	};
	let gauges = {};
	$.ajaxSetup({
		// Disable caching of AJAX responses
		cache: false
	});
	let parse_value = function (value) {
		let str = value.toString();
		return str.slice(0,2) + '.' + str.slice(2);
	};
    $(function(){
    	$.get('data.php', function(data){
    		let sensors = JSON.parse(data);
    		for (sensor of sensors)
			{
				let canvas =
					$('<canvas/>',{id: 'sensor_' + sensor.sensor_id, 'class':'sensor_gauge sensor_gauge_' + sensor.sensor_type})
						.width(400)
						.height(400);
				let div = $('<div></div>', {class: 'gauge gauge-type-' + sensor.sensor_type, id: 'gauge_' + sensor.sensor_id});
				div.append($('<span></span>', {class: 'sensor-name'}).append(sensor.sensor_name));
				div.append($('<br/>'));
				div.append(canvas);
				div.append($('<br/>'));
				$('#gauges').append(div);
				div.append($('<span></span>', {class: 'sensor-value'}).append(parse_value(sensor.value)));
				let canvas_elem = canvas.get(0);
				let gauge = new Gauge(canvas_elem).setOptions(opts); // create sexy gauge!
				gauge.maxValue = 60000; // set max gauge value
				gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
				gauge.animationSpeed = 32; // set animation speed (32 is default value)
				gauge.set(sensor.value); // set actual value
				gauges[sensor.sensor_id] = gauge;
                setInterval(function(){
                	$.get('data.php?rand=' + Math.random(), function(data){
						let sensors = JSON.parse(data);
						    for (sensor of sensors)
							{
								gauges[sensor.sensor_id].set(sensor.value);
								$('#gauge_' + sensor.sensor_id).find('span.sensor-value').html(parse_value(sensor.value));
							}
                    });
                },5000)
			}
        });
    })
</script>