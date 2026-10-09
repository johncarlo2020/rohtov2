<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body, table, td, p, div, h1, h2, span {
            font-family: 'Courier New', Courier, monospace !important;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #F4F0EA;
            margin: 0;
            padding: 40px 10px;
            color: #111111;
            -webkit-font-smoothing: antialiased;
        }
        .email-card {
            max-width: 440px;
            margin: 0 auto;
            background-color: #FAF8F5;
            border: 1px solid #E0DDD7;
            padding: 35px 25px;
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
        }
        .brand-logo {
            max-width: 160px;
            height: auto;
            margin: 0 auto 25px auto;
            display: block;
        }
        .greeting {
            font-size: 13px;
            text-align: left;
            margin-bottom: 20px;
            font-family: 'Courier New', Courier, monospace;
        }
        .message-body {
            font-size: 12px;
            line-height: 1.6;
            color: #333333;
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Courier New', Courier, monospace;
        }
        .otp-code {
            font-size: 32px;
            letter-spacing: 8px;
            font-weight: bold;
            color: #000000;
            margin: 25px 0;
            display: inline-block;
            font-family: 'Courier New', Courier, monospace;
        }
        .footer-note {
            font-size: 11px;
            color: #777777;
            margin-top: 30px;
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>
    <div class="email-card">
        <img src="{{ $logoUrl ?? asset('images/brand/logo.webp') }}" alt="Site Logo" class="brand-logo" />

        <div class="greeting">Dear Customer,</div>

        <div class="message-body">
            You recently requested a One-Time Pin (OTP) for verification.
            Enter the OTP shown below to proceed.
        </div>

        <div class="otp-code">{{ $otp ?? '753166' }}</div>

        <div class="footer-note">
            This OTP is valid for 10 minutes.<br>
            Thank you
        </div>
    </div>
</body>
</html>
