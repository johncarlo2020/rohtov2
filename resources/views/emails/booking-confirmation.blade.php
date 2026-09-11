<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px 0;
            color: #111111;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        .email-card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 0px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
        }
        .content-body {
            padding: 35px 30px 40px 30px;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #111111;
            margin-top: 0;
            margin-bottom: 25px;
        }
        .greeting {
            font-size: 14px;
            font-weight: 600;
            color: #222222;
            margin-bottom: 8px;
        }
        .intro-text {
            font-size: 12px;
            line-height: 1.6;
            color: #444444;
            max-width: 480px;
            margin: 0 auto 30px auto;
        }
        .detail-group {
            margin-bottom: 20px;
        }
        .detail-label {
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #555555;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #111111;
            line-height: 1.4;
        }
        .divider {
            border: none;
            border-top: 1px solid #d1d5db;
            width: 85%;
            margin: 30px auto;
        }
        .qr-instruction {
            font-size: 13px;
            font-weight: 600;
            color: #222222;
            line-height: 1.5;
            max-width: 340px;
            margin: 0 auto 20px auto;
        }
        .qr-image {
            width: 180px;
            height: 180px;
            display: block;
            margin: 0 auto 10px auto;
        }
        .modify-instruction {
            font-size: 12px;
            line-height: 1.5;
            color: #444444;
            margin-bottom: 18px;
        }
        .btn-modify {
            display: inline-block;
            background-color: #f26522;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-decoration: none;
            padding: 12px 48px;
            border-radius: 2px;
        }
        .footer-text {
            font-size: 11px;
            line-height: 1.6;
            color: #555555;
            max-width: 480px;
            margin: 0 auto 30px auto;
        }
        .bottom-logo {
            width: 70px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            <!-- Header Image Placeholder -->
            <img class="header-image" src="{{ $headerImage ?? 'https://placehold.co/600x240/f4efe9/555555?text=Header+Image+Placeholder' }}" alt="Longchamp Event Banner">

            <div class="content-body">
                <!-- Status Title -->
                <h1 class="title">YOUR BOOKING IS {{ $actionText }}!</h1>

                <!-- Greeting -->
                <div class="greeting">Dear {{ $customerName }},</div>
                <div class="intro-text">
                    We are delighted to confirm your booking for the Longchamp x Caroline Hélain event.<br>
                    Please find your booking details below for your reference.
                </div>

                <!-- Booking Details -->
                <div class="detail-group">
                    <div class="detail-label">DATE:</div>
                    <div class="detail-value">{{ $dateFormatted }}</div>
                </div>

                <div class="detail-group">
                    <div class="detail-label">TIME:</div>
                    <div class="detail-value">{{ $timeFormatted }}</div>
                </div>

                <div class="detail-group" style="margin-bottom: 10px;">
                    <div class="detail-label">VENUE:</div>
                    <div class="detail-value">
                        LONGCHAMP POP UP STORE<br>
                        THE GARDENS MALL
                    </div>
                </div>

                <!-- Divider -->
                <hr class="divider">

                <!-- QR Code Section -->
                <div class="qr-instruction">
                    Please present the QR code below upon arrival for registration.
                </div>
                <img class="qr-image" src="{{ $qrCodeUrl }}" alt="Booking QR Code">

                <!-- Divider -->
                <hr class="divider">

                <!-- Modify Booking Section -->
                <div class="modify-instruction">
                    If you need to update your booking details, please use the link below:<br>
                    Modify your booking:
                </div>
                <div style="margin-bottom: 10px;">
                    <a href="{{ $modifyUrl }}" class="btn-modify">MODIFY</a>
                </div>

                <!-- Footer Text -->
                <div class="footer-text" style="margin-top: 35px;">
                    Should you have any questions or require further assistance, please don't hesitate to contact us.<br>
                    We look forward to welcoming you to the Longchamp x Caroline Hélain event.
                </div>

                <!-- Bottom Logo -->
                <img class="bottom-logo" src="{{ $bottomLogo ?? asset('images/brand/bot_logo.webp') }}" alt="Longchamp Logo">
            </div>
        </div>
    </div>
</body>
</html>
