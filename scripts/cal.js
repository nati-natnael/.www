function $(id) {
	var ele = document.getElementById(id);
	return ele;
}

function $$(class_name) {
	var eles = document.getElementsByClassName(class_name);
	return eles;
}
// -----------------------------------------------------------

var scrAmt;
// Get Event Values by day
class Table_Events {
	constructor (interval) {
		this.scrollInterval = parseInt(interval);
		this.intervalRef = null;
	}

	getUniqueEvents () {
		var uniqueEventLocations = [];

		var events = $$('cell_content');
		for (var i = 0; i < events.length; i++) {
			var event = events[i];
			var event_name = event.childNodes[0].innerText.trim();
			var location = event.childNodes[3];

			if (location.hasChildNodes()) {
				var location_name = location.innerText;
				var value = {name: event_name, loc: location_name.trim()};

				if (!uniqueEventLocations.includes(value)) {
					uniqueEventLocations.push(value);
				}
			}
		}	

		return uniqueEventLocations;
	}

	/**
	 * scroll animation initializer function
	 * starts the animation.
	 * @param ele - scroll element parent
	 * @param interval - time interval
	 * @param class_name - desired name of ele to be scrolled
	 */
	scrollInit (scroll_win, scrollable_name) {
		var parent_element = $(scroll_win);
		// Creates scrollable object in view [scroll_win]
		var date = Date(); 
		var split = date.split(" ");
		var day_of_week = split[0];

		var val;
		switch (day_of_week) {

			case "Mon":
				val = "mon_td";
				break;

			case "Tue":
				val = "tues_td";
				break;

			case "Wed":
				val = "wed_td";
				break;

			case "Thu":
				val = "thur_td";
				break;

			case "Fri":
				val = "fri_td";
				break;

			// case "Sat":
			// 	val = "sat_td";
			// 	break;

			// case "Sun":
			// 	val = "sun_td";
			// 	break;

			default:
				val = "Check During work Day";

		}

		// Added collected events to event animation div
		parent_element.innerHTML += this._getEvent(val, scrollable_name).trim();

		// var scrollable_ele = $(scrollable_name);
		// if (scrollable_ele.innerHTML == "") {
		// 	scrollable_ele.innerHTML += val;
		// }
		// initiate scroll of scrollable element
		this.startScroll(scrollable_name);
	}

	startScroll (class_name) {
		var element = $(class_name);
		var limit = element.clientWidth - 500;
		scrAmt = 0;

		try {
		    this.intervalRef = setInterval(function () {
		    	if (scrAmt < limit) {
					element.parentElement.scrollLeft = scrAmt;
					this.scrAmt++;
				} else {
					this.scrAmt = 0;
				}
		    }, this.scrollInterval);
		} catch(e) {
		  console.error(e);
		}
	}

	stopScroll () {
		clearInterval(this.intervalRef);
	}
	

	/**
	 * Order all event under all day columns.
	 */
	catagorize_table() {
		var days = ['mon_td', 'tues_td', 'wed_td', 'thur_td', 'fri_td'];

		days.forEach(function (element) {
			_reorder_by(element, 3);
		});
	}

	/**
	 * Reorder Events undera a single day column.
	 */
	_reorder_by (by, nth_child) {
		var eles = $$(by);
		var inner_lst = [];

		for (var i = 0; i < eles.length; i++) {
			var inner = eles[i].childNodes[1];
			inner_lst[i] = inner.cloneNode(true);
		}

		// sorted_eles = sorted_eles.sort(compare);
		inner_lst = _sort(inner_lst, nth_child);
		var sorted;

		for (i = 0; i < eles.length; i++) {
			sorted = inner_lst[i];
			eles[i].innerHTML = '';
			eles[i].appendChild(sorted);
		}
	}

	/**
	 * Sorts elements in the list.
	 * Each element is compared using
	 * its @param nth_child text value.
	 *
	 * Ascending lexicographic order
	 */
	_sort (list, nth_child) {
		var sorted = [];
		var unsorted = list;

		for (var i = 0; i < unsorted.length; i++) {
			if (sorted.length == 0) {
				sorted[i] = unsorted[i];
			} else {
				var len = sorted.length;
				var index = len;
				for (var j = len - 1; j >= 0; j--) {
					var u = unsorted[i].childNodes[nth_child].innerText.trim();
					var s = sorted[j].childNodes[nth_child].innerText.trim();

					if (u < s) {
						index = j;
					}
				}

				sorted.splice(index, 0, unsorted[i]);
			}
		}

		return sorted;
	}

	// Creates unordered list from the events list
	// with class name @param class_name
	_getEvent(day, class_name) {
		var events = $$(day);

		// start string by unorder list element
		var eventValue = "<ul id=" + class_name + ">";

		// Added the list items with breaks
		for (var i = 0; i < events.length; i++) {
			var val = events[i].innerHTML;
			val = val.trim();

			if (val.length !== 0) {
				eventValue += "<li>" + val + "</li>";
			} 
		}

		eventValue += "</ul>";  // Close unordered list

		return eventValue;
	}
}
// -----------------------------------------------------------
// hide instructions wrapper
function hideInstructions () {
	// hide instructions
	var inst_wrapper = $('path_inst_wrapper');
	inst_wrapper.style.display = 'none';

	// Remove list of instructions
	var inst_win = $('path_inst_win');
	inst_win.innerHTML = '';

	// Clear path from map
	gm.clearPaths();

	// Re-center map
	centerMap(gm);
}

// Google Map class and helpers
let google_that;

// Map class
class GoogleMaps {
	// Creates Map
	constructor (map_win, location, zoom_lvl) {
		this.window = $(map_win);
		this.location = location;
		this.zoom_lvl = parseInt(zoom_lvl);
		this.placeMarkersArray = [];
		this.eventMarkersArray = [];
		this.curLocation = null;

		this.map = new google.maps.Map(this.window, {
			center: this.location,
			zoom: this.zoom_lvl
		});

		this.info_view = new google.maps.InfoWindow();
		this.direction = null;

		this.renderer = null;
	}

	/**
	 * center has to be informat:
	 * {lat: latValue, lng: lngValue}
	 */
	changeCenter (location) {
		this.location = location;
		this.map.setZoom(this.zoom_lvl);
		this.map.setCenter(location);
	}

	// Search for places by type
	searchPlaceType (place_type, radius) {
		google_that = this;
		this.clearMarkers();
		
		var request = {
			location: this.location,
			radius: parseInt(radius),
			type: [place_type]
		};

		this.info_view = new google.maps.InfoWindow();
		var service = new google.maps.places.PlacesService(this.map);
		service.nearbySearch(request, (results, status) => {
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				for (var i = 0; i < results.length; i++) {
					var p = results[i];
					this.markMapPlace(p, google.maps.Animation.DROP);
				}
			}
		});
	}

	/**
	 * Draws path on the map and create turn by turn 
	 * instruction html list.
	 */
	searchPath (start, end, trans_mode) {
		google_that = this;

		this.clearPaths();
		this.clearMarkers();

		this.direction = new google.maps.DirectionsService();
		this.renderer = new google.maps.DirectionsRenderer({map: this.map});
		var request = {
			origin: start,
			destination: end,
			travelMode: trans_mode
		};

		this.direction.route (request, (response, status) => {
			if (status === 'OK') {
				this.renderer.setDirections(response);
				var route = response.routes[0].legs[0];

				// Get Parent element
				var parent = $('path_inst_win');
				// Add an unordered list
			  	var path_list = '';

			  	// Header
			  	path_list += '<div id="inst_header">';

			  	// hide/unhide button
			  	path_list += '<div id="header_hide_unhide" data-value="false" onclick="hideUnhidePathDescription()">';
			  	path_list += '<img id="hide_unhide_img" src="imgs/unhide.png" alt="Hide unhide image">';
			  	path_list += '</div>';

			  	path_list += '<div id="header_description">';
			  	path_list += route.start_address;

			  	path_list += '<div id="header_dist_time">';
			  	path_list += route.distance.text + ' (' + route.duration.text + ')';
			  	path_list += '</div>';

			  	path_list += '</div>';

			  	// Add an unordered list
			  	path_list += '<ul id="path_instructions">';

				for (var i = 0; i < route.steps.length; i++) {
					var instruction = route.steps[i].instructions;
					var dist = route.steps[i].distance.text;
					var time = route.steps[i].duration.text;

					path_list += '<li>';

					path_list += '<div class="instruction">';
					// path_list += '<div class="inst_img"><img src="" alt=""></div>';
					path_list += '<div class="inst_num">' + (i+1) + '. ' + '</div>';
					path_list += '<div class="inst_str">' + instruction + '</div>';
					path_list += '</div>';

					path_list += '<div class="inst_info"><span>' + time + ' (' + dist + ')</span><hr></div>';
					path_list += '</li>';
				}

			  	// Close unordered list
				path_list += '</ul>';

				// Footer
			  	path_list += '<div id="inst_footer">' + 
			  						route.end_address + 
  							 '</div>';

				parent.innerHTML = path_list;

				// Unhide parent element
				parent.parentElement.style.display = 'block';
			}
		});
	}

	/**
	 * takes list of locations (coordinates of lat and lng)
	 * returns their center.
	 */ 
	getCenterOfLocations (location_lst) {
		var w_total = 0;
		var x_total = 0;
		var y_total = 0;
		var z_total = 0;

		// precision value
		var dec_point = 3;

		for (var i = 0; i < location_lst.length; i++) {
			var lat = location_lst[i].lat;
			var lng = location_lst[i].lng;

			// Convert to radians
			var lat_rad = Number((lat * (Math.PI/180)).toFixed(dec_point));
			var lng_rad = Number((lng * (Math.PI/180)).toFixed(dec_point));

			// Convert to cartesian co-ordinate
			var x = Number((Math.cos(lat_rad) * Math.cos(lng_rad)).toFixed(dec_point));
			var y = Number((Math.sin(lat_rad) * Math.sin(lng_rad)).toFixed(dec_point));
			var z = Number((Math.sin(lat_rad)).toFixed(dec_point));

			x_total += x;
			y_total += y;
			z_total += z;

			w_total += 1;  // weight is not needed [reason weight is 1]
		}

		// weigted center [cartesian]
		var x_center = Number((x_total / w_total).toFixed(dec_point));
		var y_center = Number((y_total / w_total).toFixed(dec_point));
		var z_center = Number((z_total / w_total).toFixed(dec_point));

		// Convert to lat and lng [degree]
		var hyp = Number((Math.sqrt(x_center*x_center + y_center*y_center)).toFixed(dec_point));
		var lng_center = Number((Math.atan2(y_center, x_center)).toFixed(dec_point));
		var lat_center = Number((Math.atan2(z_center, hyp)).toFixed(dec_point));

		// Convert to degree
		lat_center = Number((lat_center * (180/Math.PI)).toFixed(dec_point));
		lng_center = Number((lng_center * (180/Math.PI)).toFixed(dec_point));

		return {lat: lat_center, lng: lng_center - 0.017};
	}

	// Get location (lat, lng) by name
	getLocationOf (searchName, callback) {
		google_that = this;
		var geocoder = new google.maps.Geocoder();

		var request = {
			address: searchName
		};

		geocoder.geocode (request, (results, status) => {
			if (status == 'OK') {
				callback(results[0]);
			} else {
				alert('Gecoder failed: ' + status);
			}
		});
	}

	// 
	getCurrentLocation (callback) {
		//google_that = this;  // save this object for call back function
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition ((pos) => {
				var lat = parseFloat(pos.coords.latitude);
				var lng = parseFloat(pos.coords.longitude);

				this.curLocation = new google.maps.LatLng(lat, lng);

				callback();
			}, function (error) {
				console.error(error);
			});
		} else {
			alert("browser doesn't have this functionality");
		}
	}

	clearMarkers () {
		for (var i = 0; i < this.placeMarkersArray.length; i++) {
			this.placeMarkersArray[i].setMap(null);
		}

		this.placeMarkersArray.length = 0;
	}

	clearPaths () {
		if (this.renderer != null) {
			this.renderer.setMap(null);
		}
	}

	/** 
	 * Put markers on map
	 *
	 * @param content - designed to transport 
	 * 					value to call back function
	 */
	markMapLocation (geoLoc, animation, content) {
		var mark = new google.maps.Marker({
					map: google_that.map,
					animation: animation,
					position: geoLoc.geometry.location,
				});

		// Add action listener to each marker
		google.maps.event.addListener(mark, 'click', () => {
			var cont = '<b>'+content+'</b>' + '<br>' + geoLoc.formatted_address;
			this.info_view.setContent(cont);
			this.info_view.open(google_that.map, this);
		});

		google_that.eventMarkersArray.push(mark);

		// Center Map
		centerMap(google_that);
	}

	// Put markers on map
	markMapPlace (place, animation) {
		var mark = new google.maps.Marker({
					map: google_that.map,
					animation: animation,
					position: place.geometry.location
				});

		// Add action listener to each marker
		google.maps.event.addListener(mark, 'click', () => {
			var content = '<b>'+place.name+'</b>' + '<br>' + place.vicinity;
			this.info_view.setContent(content);
			this.info_view.open(google_that.map, this);
		});

		this.placeMarkersArray.push(mark);
	}
}
// ----------------------------------------------------------

// --- Search functionalies ---------------------------------
document.addEventListener('keypress', (event) => {
	const key = event.keyCode;
	
	if (key === 13) {
		searchOnClick();
	}
});

function displayClearButton() {
	var ele = $('search_clear');
	
	if (ele.value !== '') {
		ele.style.display = 'inline';
	} else {
		ele.style.display = 'none';
	}
}

function searchOnClick() {
	var search = $('search_val');
	var serVal = search.value;
	
	var wordReg = /[a-zA-Z]+/;
	
	if (serVal !== '') {
		if (wordReg.test(serVal)) {
			gm.getCurrentLocation(() => {
				gm.getLocationOf(serVal, getLocOfCallback);
			});
		} else if (!isNaN(serVal)) {
			var radius = parseInt(serVal);
			if (!Number.isNaN(radius)) {
				hideInstructions ();
				gm.searchPlaceType('restaurant', radius);
			}
		} else {
			alert('Please Enter address or radius');
		}
	}
}
// ----------------------------------------------------------
// --- Mouse over callback function for table cells
function loadImage (ele, srcURL) {	
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("GET", srcURL, true);
	xmlHttp.responseType = 'blob';
	xmlHttp.send();
	
	xmlHttp.onreadystatechange = () => {
		var state = xmlHttp.readyState;
		var status = xmlHttp.status;
		if (state == 4 && status == 200) {
			var newImg = document.createElement('img');
			newImg.style.width = '100px';
			newImg.style.height = '100px';
			newImg.src = window.URL.createObjectURL(xmlHttp.response);
			ele.appendChild(newImg);
		}
	};
}

function moShowImage (event) {
	var target = event.srcElement || event.target;
	//var obj_name = target.tagName;
	var obj = target;

	// If hovered on child elements
	while (obj && obj.parentElement && obj != window) {
		if (obj.tagName === 'DIV') {
			// td -> div -> img
			var ele = obj.childNodes[9];
			var url = ele.getAttribute('data-url');
			
			if (url !== '') {
				// Ajax image loader
				loadImage(ele, url);
				ele.style.display = 'block';
			}
			
			return false;
		} else {
			if (obj.preventDefault) {
				obj.preventDefault();
			}
		}
		
		obj = obj.parentElement;
	}
}

// Mouse out callback function
function mtShowImage(event) {
	// event applied to
	var target = event.srcElement || event.target;
	var obj = target;

	// If hovered on child elements
	while (obj && obj.parentElement && obj != window) {
		if (obj.tagName === 'DIV') {
			// td -> div -> img
			var ele = obj.childNodes[9];
			
			if (ele.hasChildNodes()) {
				ele.removeChild(ele.lastChild);
			}
			ele.style.display = 'none';
			
			return false;
		} else {
			if (obj.preventDefault) {
				obj.preventDefault();
			}
		}
		
		obj = obj.parentElement;
	}
}

var cells = $$('cell_content');
for (var i = 0; i < cells.length; i++) {
	cells[i].addEventListener('mouseover', moShowImage, false);
	cells[i].addEventListener('mouseout', mtShowImage, true);	
}
// ----------------------------------------------------------

// Twitter Widget
!function (d, s, id){
	var js,fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location)?'http':'https';
	if(!d.getElementById(id)) {
		js = d.createElement(s);
		js.id = id;
		js.src = p + "://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js,fjs);
	}
} (document,"script","twitter-wjs");
// ----------------------------------------------------------

// ------ Bootstrap function -------------------------------
var gm;  // Google map object
var table;
function initMap () {
	gm = new GoogleMaps('map', {lat: 44.973894, lng: -93.2344463}, 14);
} 

function initTableEvents () {
	table = new Table_Events(10);
}
// -------------- End of Initialization ------------------------

// ------ Event Marker --------------------------------
function mapMarkEvent() {
	var event_vals = table.getUniqueEvents();
	for (var i = 0; i < event_vals.length; i++) {
		//
		gm.getLocationOf(event_vals[i].loc, (pos) => {
			gm.markMapLocation(pos, google.maps.Animation.BOUNCE, () => {return event_vals[i].name;});
		});
	} 
}
// ----------- End of Event Mark -------------------------

// --------- Center Map ------------------------------------
function centerMap (googleMap) {
	var markers = googleMap.eventMarkersArray;
	var coords = [];

	// get coords from markers
	for (var i = 0; i < markers.length; i++) {
		coords.push({lat: markers[i].position.lat(),
					 lng: markers[i].position.lng()});
	}

	var center = googleMap.getCenterOfLocations(coords);

	googleMap.changeCenter(center);
}
// ---------- End of map marking of event init -------------

// ----------------- restaurant Search --------------------
function searchRestaurants () {
	var rad_ele = $('radius_val');
	var rad_val = rad_ele.value;

	if (rad_val != "") {
		var radius = parseInt(rad_val);
		if (!Number.isNaN(radius)) {
			gm.searchPlaceType('restaurant', radius);
		}
	}
}
// ---------------- End -----------------------------------

// ---- Sequence of Search location callback functions ----
function onClickSearchDirection() {
	gm.getCurrentLocation(() => {
		var address_ele = $('address');

		if (address_ele.value !== '') {
			gm.getLocationOf(address_ele.value, () => {
				var address_ele = $('address');

				if (address_ele.value !== '') {
					gm.getLocationOf(address_ele.value, () => {
						searchDirection(dest_location);
					});
				}
			});
		} 
	});
}

function searchDirection (dest) {
	var travel_mode_ele = $$('trav_mode');

	var travel_mode = "";
	if (travel_mode_ele.length !== 0) {
		for (var i = 0; i < travel_mode_ele.length; i++) {
			if (travel_mode_ele[i].checked) {
				travel_mode = travel_mode_ele[i].value;
			}
		}
	} else {
		travel_mode = "DRIVING";
	}

	gm.searchPath(gm.curLocation, dest.geometry.location, travel_mode);
}
// -------------------- End of Location Search -----------------------


function hideUnhidePathDescription() {
	var ele = $('header_hide_unhide');
	var showen = ele.getAttribute('data-value');

	if (showen == 'true') {
		// Hide the path instruction
		$('path_instructions').style.display = 'none';
		$('hide_unhide_img').src = 'imgs/unhide.png';
		ele.setAttribute('data-value', 'false');
	} else {
		// unhide the path instruction
		$('path_instructions').style.display = 'block';
		$('hide_unhide_img').src = 'imgs/hide.png';
		ele.setAttribute('data-value', 'true');
	}
}

// Main Invoke Method
window.onload = function () { 
	initMap();
	initTableEvents();
	mapMarkEvent();
	//table.scrollInit('event_scroll', 'content_scroll');
};


