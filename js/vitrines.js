$(document).ready(function(){
	var clocks = $(".js-clock");

	function transform(time, type, s_type = null){
		var returnVal = 0;

		switch(type){
			case "days":
				returnVal = time * 60 * 60 * 24; 
				break;
			case "hours":
				returnVal = time * 60 * 60;
				break;
			case "minutes":
				returnVal = time * 60;
				break;
			case "seconds":
				if(s_type == "days"){
					returnVal = (time / 60) / 60 / 24;
				}else if(s_type == "hours"){
					returnVal = (time / 60) / 60;
				}else if(s_type == "minutes"){
					returnVal = time / 60;
				}
				break;
		}

		returnVal = parseInt(returnVal);
		return returnVal;
	}

	function update_clock(total_clock_seconds){
		var in_days = 0;
		var in_hours = 0;
		var in_minutes = 0;

		var f_days = 0;
		var f_hours = 0;
		var f_minutes = 0;
		var f_seconds = 0;

		function update_times(secs){
			in_days = transform(secs, "seconds", "days");
			in_hours = transform(secs, "seconds", "hours");
			in_minutes = transform(secs, "seconds", "minutes");
		}

		update_times(total_clock_seconds);
		var a_seconds = total_clock_seconds;

		if(in_days > 0){
			var time_added = transform(in_days, "days");
			f_days = transform(time_added, "seconds", "days");
			a_seconds = a_seconds - time_added;
			update_times(a_seconds);
		}

		if(in_hours > 0){
			var time_added = transform(in_hours, "hours");
			f_hours = transform(time_added, "seconds", "hours");
			a_seconds = a_seconds - time_added;
			update_times(a_seconds);
		}

		if(in_minutes > 0){
			var time_added = transform(in_minutes, "minutes");
			f_minutes = transform(time_added, "seconds", "minutes");
			a_seconds = a_seconds - time_added;
		}

		f_seconds = a_seconds;

		f_days = f_days < 10 ? "0"+f_days : f_days;
		f_hours = f_hours < 10 ? "0"+f_hours : f_hours;
		f_minutes = f_minutes < 10 ? "0"+f_minutes : f_minutes;
		f_seconds = f_seconds < 10 ? "0"+f_seconds : f_seconds;

		total_clock_seconds--;

		var returnArray = new Array();
		returnArray[0] = {"days": f_days, "hours": f_hours, "minutes": f_minutes, "seconds": f_seconds, "total_seconds": total_clock_seconds};

		return returnArray[0];
	}

	var $CLOCK_OBJETCS = new Array();
	$CLOCK_OBJETCS.displays = [];
	$CLOCK_OBJETCS.array_displays = [];

	function set_clock_config(){
		$CLOCK_OBJETCS.displays = $(".js-clock");
		$CLOCK_OBJETCS.array_displays = [];

		$CLOCK_OBJETCS.displays.each(function(){
			// SET HTML OBJECT
			var insert_array = [];
			var clk_html_object = $(this);
			insert_array.clock_html_object = clk_html_object;
			insert_array.calc_total_seconds = clk_html_object.attr("js-total-seconds");
			insert_array.obj_days = clk_html_object.children(".js-days");
			insert_array.obj_hours = clk_html_object.children(".js-hours");
			insert_array.obj_seconds = clk_html_object.children(".js-seconds");
			insert_array.obj_minutes = clk_html_object.children(".js-minutes");

			$CLOCK_OBJETCS.array_displays.push(insert_array);
		});
	}
	set_clock_config();

	function set_clock_values(array_index, total_seconds, days, hours, seconds, minutes){
		$CLOCK_OBJETCS.array_displays[array_index].obj_days.text(days);
		$CLOCK_OBJETCS.array_displays[array_index].obj_hours.text(hours);
		$CLOCK_OBJETCS.array_displays[array_index].obj_seconds.text(seconds);
		$CLOCK_OBJETCS.array_displays[array_index].obj_minutes.text(minutes);
		$CLOCK_OBJETCS.array_displays[array_index].clock_html_object.attr("js-total-seconds", total_seconds);
	}

	function loop_trigger(){
		$CLOCK_OBJETCS.array_displays.forEach(function(obj, array_index){
			var totalSeconds = obj.calc_total_seconds;

			function clock_trigger(){
				var clock_calc = update_clock(totalSeconds);
				totalSeconds = clock_calc.total_seconds;

				if(totalSeconds <= 0){
					obj.html("Expirado");
				}else{
					set_clock_values(array_index, totalSeconds, clock_calc.days, clock_calc.hours, clock_calc.seconds, clock_calc.minutes);
				}
				set_clock_config(); // RESET ALL CLOCKS
			}

			clock_trigger();
		});
	}

	var refreshing_timer = 1000; // Every 1 Second

	loop_trigger(); // Start
	setInterval(function(){
		loop_trigger();
	}, refreshing_timer);
});