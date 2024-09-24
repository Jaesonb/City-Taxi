const host = "https://example.com/"; // Replace with your actual API host

// Driver Registration
document.getElementById('driverRegisterForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const name = document.getElementById('driverName').value;
    const email = document.getElementById('driverEmail').value;
    const phoneNumber = document.getElementById('driverPhone').value;
    const password = document.getElementById('driverPassword').value;
    const vehicleNumber = document.getElementById('vehicleNumber').value;
    const model = document.getElementById('driverModel').value;
    const brand = document.getElementById('driverBrand').value;
    const color = document.getElementById('driverColor').value;
    const latitude = document.getElementById('driverLatitude').value;
    const longitude = document.getElementById('driverLongitude').value;

    const response = await fetch(`${host}api/drivers/register`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name,
            email,
            phone_number: phoneNumber,
            password,
            vehicle_number: vehicleNumber,
            model,
            brand,
            color,
            latitude,
            longitude
        }),
    });

    const data = await response.json();
    if (response.ok) {
        alert('Driver Registration Successful!');
        // Optionally redirect or clear the form
    } else {
        alert(data.message || 'Driver Registration Failed!');
    }
});

// Driver Login
document.getElementById('driverLoginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const email = document.getElementById('driverLoginEmail').value;
    const password = document.getElementById('driverLoginPassword').value;

    const response = await fetch(`${host}api/drivers/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    if (response.ok) {
        alert('Driver Login Successful!');
        // Handle successful login, such as redirecting or storing tokens
    } else {
        alert(data.message || 'Driver Login Failed!');
    }
});

// Passenger Registration
document.getElementById('passengerRegisterForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const name = document.getElementById('passengerName').value;
    const email = document.getElementById('passengerEmail').value;
    const phoneNumber = document.getElementById('passengerPhone').value;
    const password = document.getElementById('passengerPassword').value;

    const response = await fetch(`${host}api/passengers/register`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name,
            email,
            phone_number: phoneNumber,
            password
        }),
    });

    const data = await response.json();
    if (response.ok) {
        alert('Passenger Registration Successful!');
        // Optionally redirect or clear the form
    } else {
        alert(data.message || 'Passenger Registration Failed!');
    }
});

// Passenger Login
document.getElementById('passengerLoginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const email = document.getElementById('passengerLoginEmail').value;
    const password = document.getElementById('passengerLoginPassword').value;

    const response = await fetch(`${host}api/passengers/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    if (response.ok) {
        alert('Passenger Login Successful!');
        // Handle successful login, such as redirecting or storing tokens
    } else {
        alert(data.message || 'Passenger Login Failed!');
    }
});
