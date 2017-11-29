<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<Title>My Calendar</Title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
		<link rel="stylesheet" type="text/css" href="styles/form.css">
		<script type="text/javascript" src="scripts/form.js"></script>
	</head>
	<body>
		<div id="main_wrapper">
			<!-- Heading -->
			<h2 id="heading">Calendar Input</h2>

			<!-- Check if logged in -->
			<div id="wel_logout">
				<?php
					error_reporting(E_ALL);
					ini_set('display_errors', 1);
					ini_set('display_startup_errors', 1);

					include 'util/string_utils.php';
					session_start();
					if (isset($_SESSION['username'])) {
						$fullName   = $_SESSION['username'];
						$namePieces = explode(",", $fullName);

						// welcome message
						$welcomeMsg  = '<div id="welcome">Welcome ';
						$welcomeMsg .= capitalizeWords($namePieces[1]);
						$welcomeMsg .= '</div>';

						echo $welcomeMsg;
					} else {
						header('Location: login.php', true, 301);
						die();
					}
				?>
			</div>

			<!-- Nav -->
			<nav id="main_nav">
				<div id="inner_nav">
					<a href="calendar.php"><div id="nav_cal_id">MyCalendar</div></a>
					<a href="#"><div id="nav_form_id">Form Input</div></a>
					<div id="right_menu">
	    			<a href="admin.php"><div id="admin">Admin</div></a>
	    			<a href="logout.php"><div id="logout">Logout</div></a>
	    		</div>
				</div>
			</nav>

			<div id="content_wrapper">
				<!-- Heading -->
				<div id="main_content">
					<!-- Creating Event calendar -->
					<?php
						include "util/io.php";
						include "util/msg_handlers.php";

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
							header('Location: calendar.php', true, 301);
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
										echo successMsg("Calendar events cleared.");
									} else {
										echo errMsg("Unable to clear events");
									}
								} else {
									$validationPassed = TRUE;

									$errMsgs = "";
									if (!validate($eventName)) {
										$errMsgs .= errMsg("Event Name must be alpha-numeric");
										$validationPassed = FALSE;
									}

									if (empty($startTime)) {
										$errMsgs .= errMsg("Start Time cannot be empty");
										$validationPassed = FALSE;
									}

									if (empty($endTime)) {
										$errMsgs .= errMsg("End time cannot be empty");
										$validationPassed = FALSE;
									}

									if (!validate($location)) {
										$errMsgs .= errMsg("Location must be alpha-numeric");
										$validationPassed = FALSE;
									}

									if (!validate($day)) {
										$errMsgs .= errMsg("Please select day");
										$validationPassed = FALSE;
									}

									if ($validationPassed) {
										$eventJSonFilePath = "json/calendar.txt";
										$eventJson = readJSonFile($eventJSonFilePath);

										if ($eventJson != null) {
											addEvent($eventJson, $eventName, $startTime,
													 $endTime, $location, $day, $imgURL);

											$status = writeJSonToFile($eventJSonFilePath, $eventJson);

											if ($status) {
												redirect();
											} else {
												echo errMsg("Unable to update Calendar");
											}
										} else {
											echo errMsg("Error Reading File");
										}
									} else {
										$errDiv  = "<div id='form_err'>";
										$errDiv .= "<div id='form_err_header'>";
										$errDiv .= "<span style='font-size: 1.5em;'>Error</span>";
										$errDiv .= "<input id='form_err_input'
														   type='button'
														   value='X'
														   onclick='remove_error()'>";
										$errDiv .= "</div>";
										$errDiv .= $errMsgs;
										$errDiv .= "</div>";

										echo $errDiv;
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
											<label>Event Name</label>
										</div>
										<div class="input_ele">
											<input id="event_name_in" type="text" name="eventname">
										</div>
									</div>

									<div id="st_id">
										<div class="label_ele">
											<label>Start Time</label>
										</div>
										<div class="input_ele">
											<input type="time" name="starttime">
										</div>
									</div>

									<div id="dt_id">
										<div class="label_ele">
											<label>End Time</label>
										</div>
										<div class="input_ele">
											<input type="time" name="endtime">
										</div>
									</div>

									<div id="l_id">
										<div class="label_ele">
											<label>Location</label>
										</div>
										<div class="input_ele">
											<input id="location_in" type="text" name="location">
										</div>
									</div>

									<div id="img_id">
										<div class="label_ele">
											<label>Image url</label>
										</div>
										<div class="input_ele">
											<input id="img_in" type="text" name="img_url">
										</div>
									</div>

									<div id="dotw_id">
										<div class="label_ele">
											<label>Day of the Week</label>
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
