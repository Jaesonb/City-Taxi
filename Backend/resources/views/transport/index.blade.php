<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Taxi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container text-center">
        <h1 class="my-5">City Taxi</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <button id="driverBtn" class="btn btn-primary btn-lg w-100 mb-3">Driver</button>
            </div>
            <div class="col-md-6">
                <button id="passengerBtn" class="btn btn-success btn-lg w-100 mb-3">Passenger</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/transport/script.js') }}"></script>

    <!-- <script src="script.js"></script> -->
</body>

</html>