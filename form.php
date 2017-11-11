<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<Title>My Calendar</Title>
		<link rel="stylesheet" type="text/css" href="styles/common_style.css">
		<link rel="stylesheet" type="text/css" href="styles/form_style.css">
		<script type="text/javascript" src="scripts/form.js"></script>
	</head>
	<body>
		<div id="main_wrapper">
			<!-- Nav -->
			<nav id="main_nav">
				<a href="calendar.php"><div id="nav_cal_id">MyCalendar</div></a>
				<a href="#"><div id="nav_form_id">Form Input</div></a>
			</nav>

			<div id="content_wrapper">
				<!-- Heading -->
				<div id="main_content">
					<?php
						/**
						 * Validate posted values
						 *
						 * All Values need to be alpha numeric
						 */
						function validate($string) {
							$pattern = "/^[a-z0-9]+$/";
							$lowerString = strtolower($string);
							
							$pieces = explode(" ", $lowerString);
							
							foreach ($pieces as $piece) {
								$match = preg_match($pattern, $piece);
								//echo "String: $piece | Match: $match <br>";
								
								# If any string piece doesn't match stop
								if (!$match) {
									return false;
								}
							}
							
							return $match;
						}
												
						/**
						 * Reads file containing json object.
						 * file must only contain one JSon object.
						 *
						 * returns json object
						 */
						function readJSonFile ($filePath) {
							try {
								$jsonFile = fopen($filePath, "r") or die("Error on opening file");
								
								# Read Entire file
								$size = filesize($filePath);
								if ($size > 0) {
									$jsonString = fread($jsonFile, $size);
								} else {
									# Create empty json if file is empty
									$jsonString = '{"monday": [], "tuesday": [],
												    "wednesday": [], "thursday": [], "friday": []}';
								}
								
								fclose($jsonFile);
								
								$jsonObject = json_decode($jsonString, true);
								
								return $jsonObject;
							} catch (Exception $e) {
								echo 'Error: ' . $e->getMessage();
								return null;
							}
						}
						
						/**
						 * Write json to file
						 */
						function writeJSonToFile ($filePath, $json) {
							try {
								$file = fopen($filePath, "w") or die("Error on opening file");
								$encodedJson = json_encode($json);
								fwrite($file, $encodedJson);
								fclose($file);
								
								return TRUE;
							} catch (Exception $e) {
								echo 'Error: ' . $e->getMessage();
								return FALSE;
							}
						}
						
						/**
						 * Adde new Event to calendar json
						 */
						function addEvent (&$json, $eventName, $startTime, $endTime, $location, $day, $imgURL) {
							$newEvent = new \stdClass();
							$newEvent -> event_name = $eventName;
							$newEvent -> start_time = $startTime;
							$newEvent -> end_time = $endTime;
							$newEvent -> location = $location;
							$newEvent -> img_url = $imgURL;
							
							$newEventJson = json_encode($newEvent);
							$newEventJson = json_decode($newEventJson, true);
							
							switch ($day) {
								case "Monday":
									array_push($json['monday'], $newEventJson);
									break;
									
								case "Tuesday":
									array_push($json['tuesday'], $newEventJson);
									break;
								
								case "Wednesday":
									array_push($json['wednesday'], $newEventJson);
									break;
								
								case "Thursday":
									array_push($json['thursday'], $newEventJson);
									break;
								
								case "Friday":
									array_push($json['friday'], $newEventJson);
									break;
									
								default:
									echo "Not Valid day";
							}
						}
						
						/**
						 * Redirect to calendar page if event add was successful 
						 */
						function redirect () {
							header('Location: http://localhost/.www/calendar.php', true, 301);
							die();
						}
					
						# Handle POST request
						function postHandle() {
							if($_SERVER['REQUEST_METHOD'] == 'POST') {								
								$eventName = $_POST['eventname'];
								$startTime = $_POST['starttime'];
								$endTime   = $_POST['endtime'];
								$location  = $_POST['location'];
								$day 	   = $_POST['day'];
								$imgURL	   = $_POST['img_url'];
								$btn	   = $_POST['submit'];
								
								# Clearing calendar events
								if ($btn === "Clear") {
									$jsonString = json_decode('{"monday": [], "tuesday": [],
																"wednesday": [], "thursday": [],
																"friday": []}');
									
									$status = writeJSonToFile("json/calendar.txt", $jsonString);
									
									if ($status) {
										$clearMsg  = "<p style='color: green;'>";
										$clearMsg .= "<img src='imgs/check_mark.png'
														   alt='Check Mark Image'
														   style='width: 1.1em; height: 1.1em;'> ";
										$clearMsg .= "<span style='vertical-align: top'>Calendar events cleared.</span>";
										$clearMsg .= "</p>";
									
										echo $clearMsg;
									} else {
										$clearMsg  = "<p style='color: red;'>";
										$clearMsg .= "<img src='imgs/err_img.png'
														   alt='Check Mark Image'
														   style='width: 1.1em; height: 1.1em;'> ";
										$clearMsg .= "<span style='vertical-align: top'>Unable to clear events.</span>";
										$clearMsg .= "</p>";
										
										echo $clearMsg;
									}
								} else {
									if (validate($eventName)) {
										if (validate($location)) {
											$eventJSonFilePath = "json/calendar.txt";
											$eventJson = readJSonFile($eventJSonFilePath);
											
											if ($eventJson != null) {
												addEvent($eventJson, $eventName, $startTime, $endTime, $location, $day, $imgURL);																		
												writeJSonToFile($eventJSonFilePath, $eventJson);
												redirect();
											}
										} else {
											echo "<span style='color: red;'>Location must be alpha-numeric!!!</span><br>";
										}
									} else {
										echo "<span style='color: red;'>Event Name must be alpha-numeric!!!</span><br>";
									}	
								}
							}
						}
						
						postHandle();
					?>
					<!-- <h2 id="heading">Calendar Input</h2> -->
					<div id="event_form_wrapper">
						<div id="event_form">
							<form method="post" action="form.php">
								<div id="f_elements">
									<div id="e_id">
										<div class="label_ele">
											<label>Event Name:</label>
										</div>
										<div class="input_ele">
											<input id="event_name_in" type="text" name="eventname">
										</div>
									</div>

									<div id="st_id">
										<div class="label_ele">
											<label>Start Time:</label>
										</div>
										<div class="input_ele">
											<input type="time" name="starttime">
										</div>
									</div>

									<div id="dt_id">
										<div class="label_ele">
											<label>End Time:</label>
										</div> 
										<div class="input_ele">
											<input type="time" name="endtime">
										</div>
									</div>

									<div id="l_id">
										<div class="label_ele">
											<label>Location:</label>
										</div>
										<div class="input_ele">
											<input id="location_in" type="text" name="location">
										</div>
									</div>
									
									<div id="img_id">
										<div class="label_ele">
											<label>Image url:</label>
										</div>
										<div class="input_ele">
											<input id="img_in" type="text" name="img_url">
										</div>
									</div>

									<div id="dotw_id">
										<div class="label_ele">
											<label>Day of the Week:</label>
										</div>
										<div class="input_ele">
											<select name="day">
												<option value="">No Selection</option>
												<option value="Monday">Monday</option>
												<option value="Tuesday">Tuesday</option>
												<option value="Wednesday">Wednesday</option>
												<option value="Thursday">Thursday</option>
												<option value="Friday">Friday</option>
											</select>
										</div>
									</div>

									<div id="s_id">
										<input type="submit" name="submit" value="Submit">
										<input type="submit" name="submit" value="Clear">
									</div>
								</div>		
							</form>
						</div>
					</div>
				</div>

				<!-- Error Dialog -->
				<div id="error_dialog" onclick="remove_error ();">
					<div id="error_content">
						<div id="error_img"><img src="imgs/err_img.png" alt=""></div>
						<div id="error_msg">
							<b>Invalid Input:</b><br>
							Event Name and Location must be alphanumeric
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>