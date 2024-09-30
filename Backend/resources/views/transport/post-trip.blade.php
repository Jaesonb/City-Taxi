<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Summary</title>

    <!-- Bootstrap for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .rating {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }
        .rating input:checked ~ label {
            color: gold;
        }
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Trip Summary</h2>
    
    <!-- Trip details will be populated here -->
    <div id="trip-details" class="alert alert-info"></div>

    <!-- Payment details -->
    <div id="payment-details" class="alert alert-success">
        <h4>Total Fare: $<span id="fare-amount"></span></h4>
    </div>

    <!-- Rating form -->
    <div class="mt-4">
        <h4>Rate Your Ride</h4>
        <form id="ratingForm">
            <div class="rating">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5">&#9733;</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">&#9733;</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">&#9733;</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">&#9733;</label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">&#9733;</label>
            </div>

            <!-- Comment for driver -->
            <div class="form-group mt-3">
                <label for="driverComment">Leave a comment for the driver:</label>
                <textarea class="form-control" id="driverComment" rows="3" placeholder="Write your feedback..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit Rating</button>
        </form>
    </div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
        const tripId = window.location.pathname.split('/').pop(); // Extract trip ID from URL
        const passengerId = 1; // Replace with actual logged-in user/passenger ID

        // Fetch trip details from the server
        axios.get(`/api/trips/${tripId}`)
            .then(function(response) {
                const trip = response.data;
                $('#trip-details').html(`
                    <p><strong>Pickup Location:</strong> ${trip.pickup_location}</p>
                    <p><strong>Dropoff Location:</strong> ${trip.dropoff_location}</p>
                    <p><strong>Driver:</strong> ${driver.name}</p>
                    <p><strong>Status:</strong> ${trip.status}</p>
                `);
                $('#fare-amount').text(trip.fare.toFixed(2));
            })
            .catch(function(error) {
                console.error('Error fetching trip details:', error);
                $('#trip-details').html('<p class="alert alert-danger">Failed to load trip details.</p>');
            });

        // Handle rating form submission
        $('#ratingForm').on('submit', function(e) {
            e.preventDefault();

            const rating = $('input[name="rating"]:checked').val();
            const comment = $('#driverComment').val();

            if (!rating) {
                alert('Please provide a rating.');
                return;
            }

            // Submit rating to the server
            axios.post(`/api/trips/${tripId}/rate`, {
                passenger_id: passengerId,
                rating: rating,
                comment: comment
            })
            .then(function(response) {
                alert('Rating submitted successfully!');
                // Optionally, redirect the user or show a success message
            })
            .catch(function(error) {
                console.error('Error submitting rating:', error);
                alert('Failed to submit rating.');
            });
        });
    });
</script>
</body>
</html>
