<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maison Margiela OTP Verification</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #F4F0EA;
            margin: 0;
            padding: 40px 10px;
            color: #111111;
        }
        .email-card {
            max-width: 440px;
            margin: 0 auto;
            background-color: #FAF8F5;
            border: 1px solid #E0DDD7;
            padding: 35px 25px;
            text-align: center;
        }
        .brand-title {
            font-family: Georgia, serif;
            font-size: 22px;
            margin-bottom: 2px;
            color: #111111;
        }
        .brand-subtitle {
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #444444;
            margin-bottom: 35px;
        }
        .greeting {
            font-size: 13px;
            text-align: left;
            margin-bottom: 20px;
        }
        .message-body {
            font-size: 12px;
            line-height: 1.6;
            color: #333333;
            text-align: center;
            margin-bottom: 30px;
        }
        .otp-code {
            font-size: 32px;
            letter-spacing: 8px;
            font-weight: bold;
            color: #000000;
            margin: 30px 0;
            display: inline-block;
        }
        .footer-note {
            font-size: 11px;
            color: #777777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="brand-title">Maison Margiela</div>
        <div class="brand-subtitle">PARIS</div>

        <div class="greeting">Dear Customer,</div>

        <div class="message-body">
            You recently requested a One-Time Pin (OTP) for your Maison Margiela verification.
            Enter the OTP shown below to proceed.
        </div>

        <div class="otp-code">{{ $otp ?? '753166' }}</div>

        <div class="footer-note">
            This OTP is valid for 60 seconds.<br>
            Thank you
        </div>
    </div>
</body>
</html>
