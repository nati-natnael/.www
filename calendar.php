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
				<a href="form.html"><div id="nav_form_id">Form Input</div></a>
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
							
								echo "<p>it works</>";

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