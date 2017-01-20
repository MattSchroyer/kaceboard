
// queueID always comes before graphRange if there are both
function changeQueue(value){
	console.log("value changed");
	var url = window.location.href;
	
	// when there's already queueID (could be with or without &/?graphRange), need to replace the value
	if (url.indexOf('queueID=') > -1){
		// if there's also a graphRange, the new ID needs to go between queueID= and graphRange
		if(url.indexOf('graphRange') > -1){
			url = url.substring(0,url.indexOf('queueID=')) + 'queueID=' + value + url.substring(url.indexOf('&graphRange='));
		}
		// but when there's not a graphRange, simply replace the queueID at the end
		else{
			// add queueID to whatever was there
			url = url.substring(0,url.indexOf('queueID='));
			url += 'queueID=' + value
		}
	}
	
	// when there's no queueID at all, check if ?graphRange, add before
	else{
		// if there is already a graphRange, insert queueID after ?
		if (url.indexOf('graphRange') > -1){
			url = url.substring(0,url.indexOf('?')) + 'queueID=' + value + '&' + url.substring(url.indexOf('graphRange='));
		}
		// if there's no graphRange, add ?queueID=
		else{
			url += '?queueID=' + value;
		}
	}
	
	window.location.href = url;	
}


function changeRange(value){
	var url = window.location.href;
	
	// when there's already graphRange, need to replace the existing value
	if (url.indexOf('graphRange=') > -1){
			url = url.substring(0,url.indexOf('graphRange='));
			url += 'graphRange=' + value;
	}
	// when there's no graphRange
	else{
		// if there's already ? add & to whatever was there
		if (url.indexOf('queueID=') > -1){
			url += '&graphRange=' + value
		}
		// if there's no ? add ?
		else{
			url += '?graphRange=' + value;
		}
	}
	var mySelection = document.getElementById("graphRange").selectedIndex;
	window.location.href = url;
}

	var url = window.location.href;
	console.log("current queue: " + thisQueue);
	var mySelection = document.getElementById("selectService").selectedIndex;
	console.log("selectService index: " + mySelection);
	
	if (thisQueue == 25){
		mySelection = document.getElementById("selectService").selectedIndex;
		document.getElementById("selectService").selectedIndex = 1;
		console.log(mySelection);
		console.log("Amalgamated Services (Test Use Only!!)");
	}
	if (thisQueue == 12){
		document.getElementById("selectService").selectedIndex = 2;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Audio Visual Services - Classroom A/V & Meeting Room Support");
	}
	if (thisQueue == 21){
		document.getElementById("selectService").selectedIndex = 3;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Audio Visual Services - Recording (Audio, Speech, Lecture, Demonstration, & Presentation)");
	}
	if (thisQueue == 19){
		document.getElementById("selectService").selectedIndex = 4;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Audio Visual Services - Video Conferencing");
	}
	if (thisQueue == 27){
		document.getElementById("selectService").selectedIndex = 5;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("DatatelSupport");
	}
	if (thisQueue == 4){
		document.getElementById("selectService").selectedIndex = 6;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Desktop Support (Tech Support)");
	}
	if (thisQueue == 32){
		document.getElementById("selectService").selectedIndex = 7;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Digital Signage Request");
	}		
	if (thisQueue == 16){
		document.getElementById("selectService").selectedIndex = 8;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Enterprise Resource Planning - Colleague/Ellucian/Mineonline");
	}		
	if (thisQueue == 28){
		document.getElementById("selectService").selectedIndex = 9;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("E-Student Services");
	}		
	if (thisQueue == 18){
		document.getElementById("selectService").selectedIndex = 10;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Facilities Management");
	}		
	if (thisQueue == 29){
		document.getElementById("selectService").selectedIndex = 11;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Help Desk");
	}		
	if (thisQueue == 31){
		document.getElementById("selectService").selectedIndex = 12;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Moodle Support");
	}
	if (thisQueue == 30){
		document.getElementById("selectService").selectedIndex = 13;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Moodle Support for Students OLD");
	}
	if (thisQueue == 20){
		document.getElementById("selectService").selectedIndex = 14;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Moodle Support for Students OLD");
	}		
	if (thisQueue == 24){
		document.getElementById("selectService").selectedIndex = 15;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Streaming Media Request");
	}	
	if (thisQueue == 15){
		document.getElementById("selectService").selectedIndex = 16;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Systems Administration - Networking/Server support");
	}		
	if (thisQueue == 14){
		document.getElementById("selectService").selectedIndex = 17;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Telecomm & Network Operations - Phones/Network Connectivity");
	}
	if (thisQueue == 23){
		document.getElementById("selectService").selectedIndex = 18;
		mySelection = document.getElementById("selectService").selectedIndex;
		console.log(mySelection);
		console.log("Video Production Services");
	}
	
	if (thisRange == 7){
		document.getElementById("graphRange").selectedIndex = 0;
		mySelection = document.getElementById("graphRange").selectedIndex;
		console.log(mySelection);
		console.log("Video Production Services");
	}
	if (thisRange == 14){
		document.getElementById("graphRange").selectedIndex = 1;
		mySelection = document.getElementById("graphRange").selectedIndex;
		console.log(mySelection);
		console.log("Video Production Services");
	}
	if (thisRange == 30){
		document.getElementById("graphRange").selectedIndex = 2;
		mySelection = document.getElementById("graphRange").selectedIndex;
		console.log(mySelection);
		console.log("graphRange");
	}
	if (thisRange == 60){
		document.getElementById("graphRange").selectedIndex = 3;
		mySelection = document.getElementById("graphRange").selectedIndex;
		console.log(mySelection);
		console.log("graphRange");
	}
	if (thisRange == 90){
		document.getElementById("graphRange").selectedIndex = 4;
		mySelection = document.getElementById("graphRange").selectedIndex;
		console.log(mySelection);
		console.log("graphRange");
	}
	if (thisRange == 365){
		document.getElementById("graphRange").selectedIndex = 5;
		mySelection = document.getElementById("graphRange").selectedIndex;
		console.log(mySelection);
		console.log("graphRange");
	}
	
	// Calculating and displaying the timestamp
	
	var d = new Date();
    var x = document.getElementById("timestamp");
    var h = addZero(d.getHours());
    var m = addZero(d.getMinutes());
    var s = addZero(d.getSeconds());
    x.innerHTML = "Last updated at " + h + ":" + m + ":" + s;
	
	function addZero(i) {
		if (i < 10) {
			i = "0" + i;
		}
		return i;
	}
