<!DOCTYPE html>
<html>
<head>
    <title>New Consultation Created</title>
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
        .button {
            background-color: rgb(63, 122, 63);
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
            margin: 20px 0;
        }
        .footer {
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
        <h1>New Consultation Created</h1>
        <p>Dear {{ $patient->first_name }} {{ $patient->last_name }},</p>
        <p>We are pleased to inform you that Dr. {{ $doctor->first_name }}  {{ $doctor->last_name }} has created a new consultation for you.</p>
        <p>You can join the session using the link below:</p>
        <a href="{{ $consultation->meet_url }}" class="button">Join the Session</a>
        <p>If you need any further assistance, please contact us.</p>
        <div class="footer">
            <p>&copy; 2025 MediPulse. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
?>