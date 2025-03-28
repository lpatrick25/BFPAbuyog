<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 2px solid #b22222;
        }

        /* Header */
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 5px solid #b22222;
            background: #b22222;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .header img {
            max-width: 120px;
        }

        /* Main Content */
        .content {
            padding: 25px;
            margin-top: 20px;
        }

        .content-header {
            background-color: #ffeeba;
            border-left: 6px solid #b22222;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
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
            color: #333;
        }

        /* Details Section */
        .details {
            background: #f9f9f9;
            padding: 18px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #b22222;
        }

        .details p {
            font-size: 15px;
            margin: 10px 0;
        }

        .details strong {
            color: #b22222;
        }

        /* Reinspection Notice */
        .inspector {
            margin-top: 20px;
            padding: 18px;
            background: #fff3cd;
            border-left: 6px solid #d9534f;
            border-radius: 6px;
        }

        .inspector p {
            margin: 0;
            font-size: 15px;
            color: #b22222;
        }

        /* Centered CTA Button */
        .button-container {
            text-align: center;
            margin: 25px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #b22222;
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 15px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .button:hover {
            background: #8b1a1a;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #fff;
            padding: 15px;
            background: #8b1a1a;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .footer p {
            margin: 5px 0;
            color: #ffd700;
        }

        .footer a {
            color: #ffd700;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
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

            .button {
                font-size: 14px;
                padding: 10px 18px;
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
                    Your application for the establishment
                    <strong>{{ $establishment->name }}</strong>,
                    located at <strong>{{ $establishment->address_brgy }}</strong>,
                    has been scheduled for a
                    <strong>{{ strtolower($scheduleType) }}</strong>.
                </p>
            </div>

            <!-- Inspection Details -->
            <div class="details">
                <p><strong>📅 Inspection Date:</strong> {{ date('F j, Y', strtotime($scheduleDate)) }}</p>
                <p><strong>📝 Schedule Type:</strong> {{ $scheduleType }}</p>
                <p><strong>👨‍🚒 Inspector Assigned:</strong> {{ $inspector->getFullName() }}</p>
            </div>

            <!-- Reinspection Notice -->
            @if ($scheduleType == 'Reinspection')
                <div class="inspector">
                    <p><strong>⚠️ Important Notice:</strong> This is a reinspection due to previous remarks. Please
                        ensure compliance before the scheduled date.</p>
                </div>
            @endif

            <!-- Contact Info -->
            <p>
                If you have any questions, feel free to contact us at:
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>

            <p>Thank you, <br><strong>Fire Safety Compliance Management Team</strong></p>

            <!-- Centered CTA Button -->
            <div class="button-container">
                <a class="button"
                    href="https://www.google.com.ph/maps/place/Abuyog+Fire+Department/@10.7471663,125.012095,19z"
                    target="_blank">
                    📍 Get Directions
                </a>
            </div>
        </div>

        <!-- Footer with Contact & Social Media -->
        <div class="footer">
            <p>📞 Contact: (053) 555-1234</p>
            <p>📍 Address: Brgy. Loyonsawang, Abuyog, Leyte, 6510</p>
            <p>🔗 Follow us on Facebook: <a href="https://www.facebook.com/AbuyogFireDepartment" target="_blank">Abuyog
                    Fire Department</a></p>
        </div>
    </div>
</body>

</html>
