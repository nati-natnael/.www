<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<Title>My Calendar</Title>
		<link rel="stylesheet" type="text/css" href="styles/common_style.css">
		<link rel="stylesheet" type="text/css" href="styles/cal_style.css">
		<script type="text/javascript" src="scripts/cal.js"></script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7RIsZSEBIlrc85IpIs8aeeb-EMyHh2YY&libraries=places">
		</script>
	</head>
	<body>
		<div id="main_wrapper">
			<!-- navigation -->
			<nav id="main_nav">
				<a href="#"><div id="nav_cal_id">MyCalendar</div></a>
				<a href="form.php"><div id="nav_form_id">Form Input</div></a>
			</nav>
			
			<div id="content_wrapper">				
				<!-- Page Content -->
				<div id="main_content">
					<!-- Heading -->
					<!-- <h2 id="heading">My Calendar</h2> -->

					<div id="table_wrapper">
						<!-- Calendar Table -->
						<div id="ct_div">
							<?php
								function readCalendarJson($filePath) {
									try {
										$jsonFile = fopen($filePath, "r") or die("Error on opening file");
										
										# Read Entire file
										$size = filesize($filePath);
										$jsonString = fread($jsonFile, $size);
										
										fclose($jsonFile);
										
										$jsonObject = json_decode($jsonString, true);
										
										return $jsonObject;
									} catch (Exception $e) {
										echo 'Error: ' . $e->getMessage();
										return null;
									}
								}
								
								function singleDayEvent($eName, $sTime, $eTime, $loc, $day) {
									$htmlElement  = "<div id='" . $day . "' style='border: 1px solid black;'>";
									$htmlElement .= "<span class='e_name'>" . $eName . "</span> <br>";
									$htmlElement .= "<span class='s_time'>" . $sTime . "</span>";
									$htmlElement .= "<span class='en_time'>" . $eTime . "</span> <br>";
									$htmlElement .= "<span class='location'>" . $loc   . "</span>";
									//$htmlElement .= "<span class='day'>" . $day   . "</span>";
									$htmlElement .= "</div>";
									
									return $htmlElement;
								}
								
								function getDayEvents($calJson, $day, $sortBy) {
									$dayEventArray = $calJson[$day];
									
									# returned value
									$eventDivArray = [];
									
									foreach ($dayEventArray as $dayEvent) {
										$eName = $dayEvent['event_name'];
										$sTime = $dayEvent['start_time'];
										$eTime = $dayEvent['end_time'];
										$loc   = $dayEvent['location'];
										$day   = $dayEvent['day'];										
									
										$eventDiv = singleDayEvent($eName, $sTime, $eTime, $loc, $day);
										array_push($eventDivArray, $eventDiv);
									}
									
									return $eventDivArray;
								}
								
								function createCalendar($calJson) {
									
								}
																
								$calJson = readCalendarJson("json/calendar.txt");
								$monEvents = getDayEvents($calJson, 'monday', '');
								
								print_r($monEvents);
							?>
						</div>
					</div>

					<!-- Map -->
					<div id="map_wrapper">
						<div id="map_form">
							<form>
								<!-- Search Radius -->
								<div class="map_input" id="radius">
									<!-- <label>Radius: </label> -->
									<input id="radius_val" 
										   type="number" 
										   name="radius"
										   placeholder="Radius">
									<input type="button" 
									       name="findRestaurants" 
									       value="Find Nearby Restaurants"
									       onclick="searchRestaurants()">
								</div>
								<!-- Destinations -->
								<div class="map_input" id="destination">
									<!-- <label>Direction: </label> -->
									<input id="address" 
										   type="text" 
										   name="address"
										   placeholder="Type address here">
									<input type="button" 
										   name="address_button" 
										   value="Get Direction" 
										   onclick="onClickSearchDirection()">
								</div>
								<!-- Travel Method -->
								<div class="map_input" id="trav_mode">
									<div class="map_radio_input">
										<input class="trav_mode" 
											   type="radio" 
											   name="trav_mode" 
											   value="DRIVING" 
											   onclick="onClickSearchDirection()"
											   checked>
										<span>Driving</span> 
									</div>

									<div class="map_radio_input">
										<input class="trav_mode" 
											   type="radio" 
											   name="trav_mode" 
											   value="WALKING"
											   onclick="onClickSearchDirection()">
										<span>Walking</span>
									</div>

									<div class="map_radio_input">
										<input class="trav_mode" 
											   type="radio" 
											   name="trav_mode" 
											   value="TRANSIT"
											   onclick="onClickSearchDirection()">
										<span>Transit</span> 
									</div>

									<div class="map_radio_input">
										<input class="trav_mode" 
											   type="radio" 
											   name="trav_mode" 
											   value="BICYCLING"
											   onclick="onClickSearchDirection()">
										<span>Bicycling</span> 
									</div>
								</div>
							</form>
						</div>
												
						<div id="map_win">
							<div id="map_inner_win">
								<div id="map">
								</div>
							</div>
						</div>
					</div>

					<!-- Info Image -->
					<!-- <div id="img_wrapper">
						<div id="img_win">
							<img id="image" src="imgs/umn_logo.jpg" alt="">
						</div>
					</div> -->
				</div>

				<!-- Side Content -->
				<div id="side_content">
					<div id="twitter_wrapper">
						<div id="twitter_win">
				            <a class="twitter-timeline" href="https://twitter.com/hashtag/UMN" data-widget-id="917013386151583744">
				            </a>
				        </div>
					</div>

					<div id="path_inst_wrapper">
						<input type="button" name="close" value="X" onclick="hideInstructions()">
						<div id="path_inst_win">
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>