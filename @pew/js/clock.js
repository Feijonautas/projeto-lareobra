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

	clocks.each(function(){
		var clockField = $(this);
		var totalSeconds = clockField.attr("js-total-seconds");
		var objDays = clockField.children(".js-days");
		var objHours = clockField.children(".js-hours");
		var objSeconds = clockField.children(".js-seconds");
		var objMinutes = clockField.children(".js-minutes");
		var expiredTime = totalSeconds <= 0 ? true : false;

		function update_clock(){
			if(expiredTime == false){
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

				update_times(totalSeconds);
				var a_seconds = totalSeconds;

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

				objDays.text(f_days)
				objHours.text(f_hours)
				objMinutes.text(f_minutes)
				objSeconds.text(f_seconds)

				totalSeconds--;

				expiredTime = totalSeconds < 0 ? true : expiredTime;
			}else{
				clockField.html("Expirado");
			}
		}

		update_clock();

		setInterval(function(){
			update_clock();
		}, 1000);
	});
});