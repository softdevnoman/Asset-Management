<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ config('app.name', 'EQTRAK') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        .header {
            background: #2563eb;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 35px;
        }
        .content h2 {
            font-size: 20px;
            color: #1e293b;
            margin-top: 0;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
        }
        .otp-box {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: 6px;
            margin: 0;
        }
        .btn-wrapper {
            text-align: center;
            margin: 35px 0 25px 0;
        }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            padding: 14px 32px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'EQTRAK') }}</h1>
        </div>
        <div class="content">
            <h2>Welcome aboard, {{ $user->name }}! 🎉</h2>
            <p>Thank you for registering <strong>{{ $account->company_name }}</strong> with us. To activate your organization administrator account and secure your workspace, please verify your email address.</p>
            
            <div class="otp-box">
                <div class="otp-label">Your One-Time Password (OTP)</div>
                <div class="otp-code">{{ $user->otp }}</div>
            </div>

            <p>You can enter this verification code on the verification screen, or simply click the button below to instantly verify your organization account:</p>

            <div class="btn-wrapper">
                <a href="{{ route('verification.link', ['token' => $user->verification_token]) }}" class="btn">Verify Organization Now</a>
            </div>

            <p style="font-size: 13px; color: #64748b;">If you did not initiate this request, please disregard this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'EQTRAK') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
