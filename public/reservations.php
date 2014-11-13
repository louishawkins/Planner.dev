<html>
<head>
	<title>Reservations</title>
</head>
<body>
<h1>Make a reservation.</h1>

<form id="reservations" class="form-horizontal" method="POST" action="/reservations.php" role="form">
	<label for="name">Name</label>
	<input type="text" name="name" id="name" value="Jon Doe">
	<label for="date">Date of Reservation</label>
	<input type="text" name="date" id="date" value="11/11/14">
	<label for="size">Size of Party</label>
	<input type="text" name="size" id="size" value="Jon Doe">
	<label for="location">Preferred Location</label>
	<input type="text" name="location" id="location" value="Any">
	<label for="comments">Comments</label>
	<input type="text" name="comments" id="comments" value="Birthday Party">
	<button type="submit" class="btn btn-primary">Submit Reservation</button>
</form>
</body>
</html>