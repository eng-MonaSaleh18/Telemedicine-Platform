<!DOCTYPE html>
<html>
<head>
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
            text-align: center;
        }
        .code {
            background-color: rgb(189, 239, 189);
            padding: 10px 20px;
            font-size: 24px;
            font-weight: bold;
            color: rgb(63, 122, 63);
            text-align: center;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Verification Code</h1>
        @if ($user->hasRole('patient') && $user->patient)
            <p>Dear {{ $user->patient->first_name }} {{ $user->patient->last_name }},</p>
        @elseif ($user->hasRole('doctor') && $user->doctor)
            <p>Dear {{ $user->doctor->first_name }} {{ $user->doctor->last_name }},</p>
        @else
            <p>Dear User,</p>
        @endif
        <p>Please use the following code to verify your email address:</p>
        <div class="code">{{ $code }}</div>
        <p>This code will expire in 5 minutes. Please verify your account promptly.</p>
        <div class="footer">
            <p>&copy; 2025 MediPulse. All rights reserved.</p>
        </div>
    </div>
</body>
</html>