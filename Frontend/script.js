// Base URL for the API
const apiBaseUrl = 'http://localhost:8000/api';

// Handle redirections from the main page
document.addEventListener("DOMContentLoaded", function () {

    // Event listeners for main page buttons
    const driverBtn = document.getElementById('driverBtn');
    const passengerBtn = document.getElementById('passengerBtn');

    // Redirect to driver page
    if (driverBtn) {
        driverBtn.addEventListener('click', () => {
            window.location.href = 'driver.html';
        });
    }

    // Redirect to passenger page
    if (passengerBtn) {
        passengerBtn.addEventListener('click', () => {
            window.location.href = 'passenger.html';
        });
    }

    // Handle driver page buttons
    const driverLoginBtn = document.getElementById('driverLoginBtn');
    const driverRegisterBtn = document.getElementById('driverRegisterBtn');

    if (driverLoginBtn) {
        driverLoginBtn.addEventListener('click', () => {
            window.location.href = 'login.html?type=driver';
        });
    }

    if (driverRegisterBtn) {
        driverRegisterBtn.addEventListener('click', () => {
            window.location.href = 'driver-register.html?type=driver';
        });
    }

    // Handle passenger page buttons
    const passengerLoginBtn = document.getElementById('passengerLoginBtn');
    const passengerRegisterBtn = document.getElementById('passengerRegisterBtn');

    if (passengerLoginBtn) {
        passengerLoginBtn.addEventListener('click', () => {
            window.location.href = 'login.html?type=passenger';
        });
    }

    if (passengerRegisterBtn) {
        passengerRegisterBtn.addEventListener('click', () => {
            window.location.href = 'passenger-register.html?type=passenger';
        });
    }

    // Handle Passenger Registration Form
    const passengerRegisterForm = document.getElementById('passengerRegisterForm');
    if (passengerRegisterForm) {
        passengerRegisterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Collect Passenger Form Data
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const phoneNumber = document.getElementById('phone_number').value;

            const payload  = {
                name: name,
                email: email,
                password: password,
                phone_number: phoneNumber
            };

            // POST request to Passenger Register API
            fetch(`${"http://localhost:8000/api"}/passengers/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to login after successful registration
                    window.location.href = 'login.html?type=passenger';
                } else {
                    alert('Registration failed: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Handle Driver Registration Form
    const driverRegisterForm = document.getElementById('driverRegisterForm');
    if (driverRegisterForm) {
        driverRegisterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Collect Driver Form Data
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const phoneNumber = document.getElementById('phone_number').value;
            const vehicleNumber = document.getElementById('vehicle_number').value;
            const model = document.getElementById('model').value;
            const brand = document.getElementById('brand').value;
            const color = document.getElementById('color').value;

            const requestBody = {
                name: name,
                email: email,
                password: password,
                phone_number: phoneNumber,
                vehicle_number: vehicleNumber,
                model: model,
                brand: brand,
                color: color
            };

            // POST request to Driver Register API
            fetch(`${"http://localhost:8000/api"}/drivers/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to login after successful registration
                    window.location.href = 'login.html?type=driver';
                } else {
                    alert(data.message || 'Driver registration failed!');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
