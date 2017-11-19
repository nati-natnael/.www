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
			<!-- Heading -->
			<h2 id="heading">My Calendar</h2>
			
			<!-- Check if logged in -->
			<div id="wel_logout">
				<?php
					include 'util/string_utils.php';
					session_start();
					if (isset($_SESSION['username'])) {
						// welcome message
						$welcomeMsg  = '<div id="welcome">Welcome ';
						$welcomeMsg .= capitalizeWords($_SESSION['username']);
						$welcomeMsg .= '</div>';
						
						echo $welcomeMsg;
					} else {
						header('Location: login.php', true, 301);
						die();
					}
				?>
			</div>
			
			<!-- navigation -->
			<nav id="main_nav">
				<div id="inner_nav">
					<a href="#"><div id="nav_cal_id">MyCalendar</div></a>
					<a href="form.php"><div id="nav_form_id">Form Input</div></a>
					<a href="logout.php"><div id="logout">Logout</div></a>
				</div>
			</nav>

			<div id="content_wrapper">
				<!-- Page Content -->
				<div id="main_content">
					<div id="search_wrapper">
						<div id="search">
							<input id="search_val" type="text" placeholder="Address or Radius">
							<input type="button" value="Search" onclick="searchOnClick()">
						</div>
					</div>
					
					<div id="table_wrapper">
						<!-- Calendar Table -->
						<div id="ct_div">
							<?php
								include 'util/io.php';
								
								/**
								 * Get time from div and compare.
								 *
								 * time has to be wrapped in a span tag and
								 * have class name of s_time.
								 *
								 * for more info check variable $pat
								 *
								 * return: 0 if equal,
								 * 		  -1 if first less than second arg
								 * 		   1 if first greater than second arg
								 */
								function timeComparator ($div1, $div2) {
									$pat = '/<span class=\'s_time\'>(.*?)<\/span>/';

									$match1 = preg_match($pat, $div1, $matches1);
									$match2 = preg_match($pat, $div2, $matches2);

									if ($match1 && $match2) {
										$time1 = strtotime($matches1[1]);
										$time2 = strtotime($matches2[1]);

										if ($time1 === $time2) {
											return 0;
										} else {
											return ($time1 < $time2) ? -1 : 1;
										}
									} else {
										return 0;
									}
								}

								/**
								 * Created div for a single event
								 *
								 * return: Div containing event detail
								 */
								function singleEvent($eName, $sTime, $eTime, $loc, $imgURL) {
									$htmlElement  = "<div class='cell_content'";
									$htmlElement .= "onmouseover='moShowImage(event)'";
									$htmlElement .= "onmouseout='mtShowImage(event)'";
									$htmlElement .= ">";

									$htmlElement .= "<span class='e_name'>" . capitalizeWords($eName) . "</span> <br>";
									$htmlElement .= "<span class='location'>" . capitalizeWords($loc)   . "</span> <br>";
									# Converting time
									$htmlElement .= "<span class='s_time'>";
									$htmlElement .= $sTime;
									$htmlElement .= "</span>";

									# Time separator
									$htmlElement .= " - ";

									$htmlElement .= "<span class='e_time'>";
									$htmlElement .= $eTime;
									$htmlElement .= "</span>";

									$htmlElement .= "<span class='hidden' style='display: none;'";
									$htmlElement .= "data-url='" . $imgURL . "'";
									$htmlElement .= "></span>";

									$htmlElement .= "</div>";

									return $htmlElement;
								}

								/**
								 * Get all events under $day and sort using
								 * $comparator function.
								 *
								 * return: sorted array of events under same $day
								 */
								function getDayEvents($calJson, $day, $comparator) {
									$dayEventArray = $calJson[$day];

									# returned value
									$eventDivArray = array();

									if (!empty($dayEventArray)) {
										foreach ($dayEventArray as $dayEvent) {
											$eName    = $dayEvent['event_name'];
											$sTime    = $dayEvent['start_time'];
											$eTime    = $dayEvent['end_time'];
											$loc      = $dayEvent['location'];
											$imgURL   = $dayEvent['img_url'];

											$eventDiv = singleEvent($eName, $sTime, $eTime, $loc, $imgURL);
											array_push($eventDivArray, $eventDiv);
										}

										usort($eventDivArray, $comparator);
									}

									return $eventDivArray;
								}

								/**
								 * Creates table construct in table format
								 */
								function createCalendar($calJson) {
									$eventsPresent = FALSE;
									$dayNames = array('monday', 'tuesday', 'wednesday',
												 'thursday', 'friday');

									# get all days with events
									$daysPerWeek = 5;
									$maxRow = 0;
									$days = array();
									foreach ($dayNames as $day) {
										$events = getDayEvents($calJson, $day, "timeComparator");

										if (!empty($events)) {
											# Getting the maximum number of events per day
											$maxRow = count($events) > $maxRow ? count($events) : $maxRow;
											$eventsPresent = TRUE;
										}

										array_push($days, $events);
									}

									if (!$eventsPresent) {
										echo "<p style='color: red;'>Calendar has no events.
											  Please use Input page to enter events.</p>";
									} else {
										# Table headers
										$table  = "<table>";
										$table .= "<thead>";
										$table .= "<tr>";
										$table .= "<th>Monday</th>";
										$table .= "<th>Tuesday</th>";
										$table .= "<th>Wednesday</th>";
										$table .= "<th>Thursday</th>";
										$table .= "<th>Friday</th>";
										$table .= "</tr>";
										$table .= "</thead>";

										# Add table data
										$table .= "<tbody>";
										for ($i = 0; $i < $maxRow; $i++) {
											$table .= "<tr>";
											for ($j = 0; $j < $daysPerWeek; $j++) {
												$size = count($days[$j]);

												$table .= "<td>";
												if ($i < $size) {
													$table .= $days[$j][$i];
												}
												$table .= "</td>";
											}
											$table .= "</tr>";
										}
										$table .= "</tbody>";

										$table .= "</table>";

										echo $table;
									}
								}

								$calJson = readJSonFile("json/calendar.txt");
								createCalendar($calJson);
							?>
						</div>
					</div>

					<!-- Map -->
					<div id="map_wrapper">
						<div id="map_win">
							<div id="map_inner_win">
								<div id="map">
								</div>
							</div>
						</div>
					</div>
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
