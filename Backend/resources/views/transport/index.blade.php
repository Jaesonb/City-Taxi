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

        <!-- Display error messages if they exist -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login form -->
        <form action="{{ route('login.submit') }}" method="POST" class="form">
            @csrf
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>

            <div class="form-group mb-3">
                <label for="user_type">Login As</label>
                <select name="user_type" id="user_type" class="form-control" required>
                    <option value="driver">Driver</option>
                    <option value="passenger">Passenger</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="mt-4 text-center">
            <p>Don't have an account? Register below:</p>
            <a href="{{ route('transport.driver-register') }}" class="btn btn-secondary me-2">Driver Register</a>
            <a href="{{ route('transport.passenger-register') }}" class="btn btn-secondary">Passenger Register</a>
        </div>
    </div>
</body>

</html>
