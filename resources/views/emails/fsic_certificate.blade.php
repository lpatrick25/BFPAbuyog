<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FSIC Certificate Issued</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #d9534f;
        }

        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .header img {
            max-width: 80px;
        }

        .header h2 {
            margin: 10px 0 5px;
            color: #d9534f;
            font-size: 20px;
            font-weight: bold;
        }

        .content {
            padding: 20px 10px;
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 13px;
            color: #777;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .footer a {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ asset('images/bfp-logo.png') }}" alt="BFP Logo">
            <h2>Bureau of Fire Protection - Abuyog</h2>
            <p>123 Fire Street, Abuyog, Leyte | Contact: (053) 555-1234</p>
        </div>

        <!-- Email Content -->
        <div class="content">
            <p>Dear <strong>{{ $clientName }}</strong>,</p>
            <p>Congratulations! Your application has been successfully completed, and we have issued your Fire Safety
                Inspection Certificate (FSIC).</p>

            <p><strong>📌 Establishment Name:</strong> {{ $establishmentName }}</p>

            <p>Please find the FSIC attached to this email. Keep it for your records.</p>

            <p>If you have any questions or need further assistance, feel free to contact us.</p>
            <p>Thank you.</p>

            <p>Best regards,</p>
            <p><strong>Bureau of Fire Protection - Abuyog</strong></p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bureau of Fire Protection - Abuyog. All rights reserved.</p>
            <p>Need help? <a href="mailto:support@bfp-abuyog.com">Contact Us</a></p>
        </div>
    </div>
</body>

</html>
