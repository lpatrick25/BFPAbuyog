<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
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
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
        }

        .header img {
            max-width: 150px;
        }

        .content-header {
            border: 3px solid green;
            border-radius: 5px;
            padding: 15px;
            margin-top: 15px;
            text-align: left;
        }

        .content-header h2 {
            color: green;
            margin: 0;
        }

        .content-body {
            margin-top: 20px;
        }

        .company-info {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
        }

        .company-image img {
            width: 100%;
            height: auto;
            max-width: 200px;
            border-radius: 8px;
        }

        .company-details {
            flex: 2;
            padding-left: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 15px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
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
                <p>Dear {{ $client->getFullName() }},</p>
                <p>
                    Regarding your application for the establishment
                    <strong>“{{ $establishment->name }}”</strong>, located at
                    <strong>“{{ $establishment->address_brgy }}”</strong>, the following remarks have been issued:
                </p>
                <p><strong>Remarks:</strong> {{ $remarks }}</p>
                <p>{{ $remarksMessage }}</p>
                <p>
                    If you have any questions or need assistance, please contact us at:
                    <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
                </p>
                <p>Thank you,<br>Fire Safety Compliance Management Team</p>
            </div>

            <!-- Fire Department Info -->
            <div class="content-body">
                <h4>Abuyog Fire Department</h4>
                <div class="company-info">
                    <div class="company-image">
                        <img src="https://streetviewpixels-pa.googleapis.com/v1/thumbnail?panoid=hMwjP8k_zMtSqGxZ9tlsPA&cb_client=search.gws-prod.gps&w=408&h=240&yaw=3.88431&pitch=0&thumbfov=100"
                            alt="Company Image">
                    </div>
                    <div class="company-details">
                        <p>
                            Brgy. Loyonsawang, Abuyog, Leyte, 6510 <br>
                            Province of Leyte
                        </p>
                        <a class="button"
                            href="https://www.google.com.ph/maps/place/Abuyog+Fire+Department/@10.7471663,125.012095,19z"
                            target="_blank">
                            Get Directions
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Fire Safety Compliance Management Team. All rights reserved.
        </div>
    </div>
</body>

</html>
