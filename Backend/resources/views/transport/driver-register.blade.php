<!-- driver-register.html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">

    <style>
        /* Custom styles for form */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .form-container {
            margin-top: 50px;
            max-width: 600px;
            background-color: white;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="form-container">
                    <h2>Driver Registration</h2>
                    <form id="driverRegisterForm">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number"
                                placeholder="Enter your phone number" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" id="vehicle_number"
                                placeholder="Enter your vehicle number" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="model" class="form-label">Vehicle Model</label>
                            <input type="text" class="form-control" id="model" placeholder="Enter vehicle model"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="brand" class="form-label">Vehicle Brand</label>
                            <input type="text" class="form-control" id="brand" placeholder="Enter vehicle brand"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="color" class="form-label">Vehicle Color</label>
                            <input type="text" class="form-control" id="color" placeholder="Enter vehicle color"
                                required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register as Driver</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('public/transport/script.js') }}"></script>

    <!-- <script src="script.js"></script> -->

</body>

</html>