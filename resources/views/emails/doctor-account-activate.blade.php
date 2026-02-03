<!DOCTYPE html>
<html>
<head>
    <title>Your Account Has Been Activated</title>
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
            color: green;
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
        <h1>Your Account Has Been Activated</h1>
        <p>Dear Dr. {{ $doctor->first_name }} {{ $doctor->last_name }},</p>
        <p>We are pleased to inform you that your account on MediPulse has been successfully activated by the admin.</p>
        <p>You can now log in and start using your privileges, such as creating appointments, consultations, and prescriptions.</p>
        <p>If you need further assistance, please contact us.</p>
        <div class="footer">
            <p>&copy; 2025 MediPulse. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
