// Get references to the form and ride list
const rideRequestForm = document.getElementById('rideRequestForm');
const availableRidesList = document.getElementById('availableRidesList');

// Array to hold ride data
let rides = [];

// Initialize Google Maps Autocomplete
function initAutocomplete() {
    const pickupInput = document.getElementById('pickupLocation');
    const destinationInput = document.getElementById('destination');

    // Create autocomplete objects for pickup and destination inputs
    const pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);
    const destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

    // Optional: Restrict search to a particular region
    // pickupAutocomplete.setComponentRestrictions({'country': ['us']});
    // destinationAutocomplete.setComponentRestrictions({'country': ['us']});
}

// Initialize Google Maps autocomplete on window load
window.onload = function () {
    initAutocomplete();
};

// Event listener for ride request form submission
rideRequestForm.addEventListener('submit', function (event) {
    event.preventDefault();

    // Get form values
    const pickupLocation = document.getElementById('pickupLocation').value;
    const destination = document.getElementById('destination').value;
    const passengerName = document.getElementById('passengerName').value;

    // Create a ride object
    const ride = {
        id: rides.length + 1,
        pickupLocation,
        destination,
        passengerName
    };

    // Add ride to the array of available rides
    rides.push(ride);

    // Display the updated list of rides
    displayRides();

    // Clear the form
    rideRequestForm.reset();
});

// Function to display the rides in the list
function displayRides() {
    availableRidesList.innerHTML = ''; // Clear existing rides

    rides.forEach(ride => {
        // Create list item for each ride
        const li = document.createElement('li');
        li.classList.add('list-group-item');

        li.innerHTML = `
            <strong>${ride.passengerName}</strong> requested a ride from 
            <strong>${ride.pickupLocation}</strong> to 
            <strong>${ride.destination}</strong>.
        `;

        availableRidesList.appendChild(li);
    });
}
