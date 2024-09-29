<p>Dear {{ $driver->name }},</p>

<p>Your driver account has been created/updated. Here are your login details:</p>

<ul>
    <li>Email: {{ $driver->email }}</li>
    <li>Password: {{ $plainPassword }}</li>
</ul>

<p>Please make sure to keep this information secure.</p>

<p>Best regards,</p>
<p>City Taxi Service</p>
