<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - City Taxi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="my-5 text-center">Login</h1>
        <form id="loginForm" class="form">
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button id="loginSubmit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="mt-4 text-center">
            <p>Don't have an account? Register below:</p>
            <a href="{{ route('transport.driver-register') }}" class="btn btn-secondary me-2">Driver Register</a>
            <a href="{{ route('transport.passenger-register') }}" class="btn btn-secondary">Passenger Register</a>
        </div>
    </div>

    <!-- <script src="{{ asset('public/transport/script.js') }}"></script> -->
    <!-- <script src="script.js"></script> -->
</body>

</html>
