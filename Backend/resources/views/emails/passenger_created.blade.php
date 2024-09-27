<h1>Welcome, {{ $passenger->name }}!</h1>

<p>You have successfully registered with the City Taxi Service. Below are your account details:</p>

<ul>
    <li><strong>Username:</strong> {{ $passenger->email }}</li>
    <li><strong>Password:</strong> {{ $plainPassword }}</li>
</ul>

<p>You can log in using the provided credentials. We are excited to have you on board!</p>

<p>Thank you for choosing our service!</p>
