<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }

        .content {
            padding: 40px;
            color: #333;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        .greeting strong {
            color: #667eea;
        }

        .message {
            font-size: 14px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }

        .code-section {
            background: #f8f9ff;
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }

        .code-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #667eea;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .verification-code {
            font-size: 48px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            user-select: all;
        }

        .code-expiry {
            font-size: 12px;
            color: #888;
            margin-top: 10px;
            font-style: italic;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            border-radius: 5px;
            color: #856404;
            font-size: 13px;
            line-height: 1.6;
        }

        .warning-box strong {
            color: #856404;
        }

        .steps {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .steps h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .step {
            display: flex;
            margin-bottom: 12px;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .step-number {
    background: #667eea;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    text-align: center;
    line-height: 24px;
    font-weight: 700;
    font-size: 11px;
    margin-right: 12px;
    flex-shrink: 0;
    padding-left: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

        .help-text {
            background: #e8f4f8;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 25px 0;
            border-radius: 5px;
            color: #0d47a1;
            font-size: 13px;
            line-height: 1.6;
        }

        .help-text a {
            color: #2196F3;
            text-decoration: none;
            font-weight: 600;
        }

        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 12px;
            line-height: 1.8;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .footer p {
            margin: 5px 0;
        }

        .divider {
            height: 1px;
            background: #eee;
            margin: 20px 0;
        }

        .signature {
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }

        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }

            .verification-code {
                font-size: 36px;
                letter-spacing: 5px;
            }

            .header h1 {
                font-size: 24px;
            }

            .code-section {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🏥 Telemedicine</div>
            <h1>Password Reset</h1>
            <p>Secure your account with a new password</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <p class="greeting">
                Hello <strong>{{ $user->first_name }}</strong>,
            </p>

            <!-- Message -->
            <p class="message">
                We received a request to reset the password for your account. Use the verification code below to proceed
                with resetting your password.
            </p>

            <!-- Verification Code Section -->
            <div class="code-section">
                <div class="code-label">Your Verification Code</div>
                <div class="verification-code">{{ $verificationCode }}</div>
                <div class="code-expiry">⏱️ This code expires in 15 minutes</div>
            </div>

            <!-- Steps -->
            <div class="steps">
                <h3>How to reset your password:</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Enter your email address in the password reset form</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Enter the verification code shown above</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Create and confirm your new password</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Your password will be updated immediately</div>
                </div>
            </div>

            <!-- Warning -->
            <div class="warning-box">
                <strong>⚠️ Important:</strong> Never share this verification code with anyone. Our support team will
                never ask you for this code.
            </div>

            <!-- Help -->
            <div class="help-text">
                <strong>Didn't request this?</strong> If you didn't initiate a password reset, please ignore this email.
                Your account remains secure.
            </div>

            <div class="divider"></div>

            <!-- Additional Info -->
            <p style="font-size: 13px; color: #666; margin-bottom: 10px;">
                <strong>Account Details:</strong><br>
                Email: {{ $user->email }}<br>
                User ID: #{{ $user->id }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-weight: 600; color: #333; margin-bottom: 10px;">Telemedicine Platform</p>
            <p>
                <a href="{{ env('APP_URL') }}">Visit Our Website</a> |
                <a href="{{ env('APP_URL') }}/help">Help Center</a> |
                <a href="{{ env('APP_URL') }}/contact">Contact Us</a>
            </p>
            <div class="divider"></div>
            <p>© {{ date('Y') }} Telemedicine Platform. All rights reserved.</p>
            <p style="margin-top: 10px; color: #aaa;">
                This is an automated message. Please do not reply to this email.
            </p>
            <p class="signature">
                Best regards,<br>
                <strong>The Telemedicine Team</strong>
            </p>
        </div>
    </div>
</body>

</html>