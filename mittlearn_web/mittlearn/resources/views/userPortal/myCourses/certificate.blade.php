<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            border-spacing: 0;
        }
    </style>
    
</head>

<body>
    <table
        style="width:900px;
        margin: 10px auto;
        border-spacing: 0;
        page-break-inside: avoid;
        page-break-after: auto;
        padding: 20px 200px;
        background-image: url({{ public_path('frontend/images/certificate/bg-img.png') }});
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;"
        cellspacing="0">

        <tr style="border-bottom: 1px solid #70707030;">
            <th>
                <table style="width: 100%">
                    <tr>
                        <td width="50%" style="padding: 15px;text-align:left;vertical-align: baseline;">
                            <img src="{{ public_path('frontend/images/mittlearn-logo.svg') }}" width="130">
                        </td>
                        <td width="50%" style="padding: 15px;text-align:right;vertical-align: baseline;">
                            <img src="{{ public_path('frontend/images/certificate/mittsure-tech-logo.png') }}"
                                width="130">
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td style="padding: 15px;font-weight: normal;font-size:14px;color: #0546FF;" align="center">
                            <img src="{{ public_path('frontend\images\certificate\certificate-img.png') }}"
                                width="240">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td style="padding: 15px 15px 0 15px;font-weight: normal;font-size:14px;color: #0546FF;"
                            align="center">
                            <p
                                style="font-size: 14px;font-weight: 600;font-family: Arial, Helvetica, sans-serif;color: #003E58;">
                                This Certificate is awarded to</p>
                            <p
                                style="font-size: 40px;font-weight: normal;font-family: fangsong;color: #003E58;margin-top: 30px;border-bottom: 1px dashed #7b7b7b ;padding-bottom: 10px ;">
                                {{ Auth::user()->name }}</p>
                            <p
                                style="font-size: 14px;font-weight: 600;font-family: Arial, Helvetica, sans-serif;color: #003E58;margin: 20px;margin-bottom: 0;">
                                For successfully completing the following course</p>
                            <p
                                style="font-size: 14px;font-weight: 600;font-family: Arial, Helvetica, sans-serif;color: #003E58;margin: 20px;margin-bottom: 0;margin-top: 5px;">
                                Of</p>
                            <p
                                style="font-size: 16px;font-weight:900;font-family: Arial, Helvetica, sans-serif;color: #003E58;margin: 20px;margin-top: 0;">
                                {{ $courseName }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td style="padding: 0;font-weight: normal;font-size:14px;color: #0546FF;" align="center">
                            <p
                                style="font-size: 14px;font-weight: normal;font-family: Arial, Helvetica, sans-serif;color: #003E58;margin-bottom: 30px;">
                                On this date : {{ $issueDate }}</p>
                        </td>
                        <td style="padding: 0;font-weight: normal;font-size:14px;color: #0546FF;" align="center">
                            <p
                                style="font-size: 14px;font-weight: normal;font-family: Arial, Helvetica, sans-serif;color: #003E58;margin-bottom: 30px;">
                                Course Duration : {{ $courseDuration }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td width="50%"
                            style="padding: 15px 15px 0 15px;font-weight: normal;font-size:14px;color: #0546FF;"
                            align="left">
                            <p
                                style="font-size: 14px;font-weight: bold;font-family: Arial, Helvetica, sans-serif;color: #003E58;">
                                <span style="font-weight: 600;display: block;font-size: 12px;margin-bottom: 10px;">
                                    {{ $issueDate }} </span> Issue Date
                            </p>
                        </td>
                        <td style="padding: 15px 15px 0 15px;font-weight: normal;font-size:14px;color: #0546FF;"
                            align="right">
                            <p
                                style="font-size: 14px;font-weight: bold;font-family: Arial, Helvetica, sans-serif;color: #003E58;">
                                <img src="{{ public_path('frontend/images/certificate/signature.png') }}" alt=""
                                    width="40" style="display: block;margin-bottom: 10px;">Mittlearn
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td style="padding: 15px;font-weight: normal;font-size:14px;color: #0546FF;" align="center">
                            <p
                                style="font-size: 20px;font-weight: normal;font-family:fangsong;color: #003E58;margin-top: 8px;">
                                An initiative by Mittsure Technologies </p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
