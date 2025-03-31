<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 2px solid #b22222;
        }

        /* Header */
        .header {
            text-align: center;
            padding: 20px 0;
            background: #b22222;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .header img {
            max-width: 130px;
        }

        /* Main Content */
        .content {
            padding: 25px;
        }

        .content-header {
            background-color: #ffeeba;
            border-left: 6px solid #b22222;
            border-right: 6px solid #b22222;
            padding: 18px;
            border-radius: 6px;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .content-header h2 {
            color: #b22222;
            margin: 0;
            font-size: 22px;
        }

        .content-header p {
            margin: 12px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Establishment Details */
        .details {
            background: #f9f9f9;
            padding: 18px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #b22222;
            border-right: 4px solid #b22222;
        }

        .details p {
            font-size: 15px;
            margin: 10px 0;
        }

        .details strong {
            color: #b22222;
        }

        /* Footer */
        .footer {
            padding: 15px;
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #8b0000;
            background: #fff3cd;
            border-radius: 6px;
        }

        .footer p {
            margin: 5px 0;
            color: #8b0000;
        }

        .footer a {
            color: #8b0000;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .footer a:hover {
            text-decoration: underline;
            color: #ff4500;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .content-header h2 {
                font-size: 20px;
            }

            .content-header p,
            .details p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="https://i.pinimg.com/originals/70/5e/28/705e28511d21d46038a6c2f3cf245eb4.png" alt="BFP Logo">
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="content-header">
                <h2>{{ $subject }}</h2>
                <p>Dear <strong>{{ $client->getFullName() }}</strong>,</p>

                <p>
                    We are pleased to inform you that the Fire Safety Inspection Certificate (FSIC) has been successfully issued for your property located at <strong>{{ $address }}</strong>.
                </p>
                <p>{{ $remarks }}</p>
                <p>{{ $remarksMessage }}</p>
            </div>

            <!-- Contact Info -->
            <p>
                If you have any questions or need assistance, please contact us at:
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>

            <p>Thank you, <br><strong>Fire Safety Compliance Management Team</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>📞 Contact: (053) 555-1234</p>
            <p>📍 Address: Brgy. Loyonsawang, Abuyog, Leyte, 6510</p>
            <p>🔗 Follow us on Facebook: <a href="https://www.facebook.com/AbuyogFireDepartment" target="_blank">Abuyog
                    Fire Department</a></p>
        </div>
    </div>
</body>

</html>
