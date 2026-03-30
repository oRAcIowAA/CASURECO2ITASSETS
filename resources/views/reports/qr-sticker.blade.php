<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset Label</title>
    <style>
        @page {
            size: 8.5in 11in;
            margin: 0.5in;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 0;
            margin: 0;
        }
        .page {
            width: 7.5in;
            min-height: 10in;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .label {
            width: 3.5in;
            height: 1.25in;
            background: white;
            border: 1px dashed #000;
            padding: 12px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .label-content {
            flex-grow: 1;
            padding-right: 15px;
            overflow: hidden;
        }
        .company-name {
            font-size: 8px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .asset-title {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0 0 4px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .asset-code {
            font-size: 12px;
            color: #333;
            margin: 0;
            font-family: monospace;
            word-break: break-all;
        }
        .qr-wrapper {
            width: 0.9in;
            height: 0.9in;
            flex-shrink: 0;
        }
        .qr-wrapper img {
            width: 100%;
            height: 100%;
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            font-size: 16px;
            z-index: 1000;
        }

        @media print {
            body { 
                background: white; 
            }
            .page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                min-height: 0;
                padding: 0;
            }
            .print-btn { display: none; }
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset Label</title>
    <style>
        @page {
            size: 8.5in 11in;
            margin: 0.5in;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 0;
            margin: 0;
        }
        .page {
            width: 7.5in;
            min-height: 10in;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .label {
            width: 3.5in;
            height: 1.25in;
            background: white;
            border: 1px dashed #000;
            padding: 12px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
            text-transform: uppercase;
        }
        .label-content {
            flex-grow: 1;
            padding-right: 15px;
            overflow: hidden;
        }
        .company-name {
            font-size: 8px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .asset-title {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0 0 4px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .asset-code {
            font-size: 12px;
            color: #333;
            margin: 0;
            font-family: monospace;
            word-break: break-all;
        }
        .qr-wrapper {
            width: 0.9in;
            height: 0.9in;
            flex-shrink: 0;
        }
        .qr-wrapper img {
            width: 100%;
            height: 100%;
        }
        /* New style for JS QR code container */
        .qr-code {
            width: 0.9in; /* Match qr-wrapper dimensions */
            height: 0.9in; /* Match qr-wrapper dimensions */
            flex-shrink: 0;
            display: flex; /* To center the QR code canvas */
            align-items: center;
            justify-content: center;
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            font-size: 16px;
            z-index: 1000;
        }

        @media print {
            body { 
                background: white; 
            }
            .page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                min-height: 0;
                padding: 0;
            }
            .print-btn { display: none; }
            .label { border: 1px dashed #ccc; }
        }
    </style>
</head>
<body>

    <div id="labels-container">
        <div class="page">
            <div class="label" style="position: relative;">
                <div style="position: absolute; top: 8px; left: 12px; display: flex; align-items: center; gap: 6px;">
                    <img src="{{ asset('images/casureco-logo.png') }}" alt="logo" style="width: 20px; height: 20px;">
                    <div class="company-name" style="margin-bottom: 0;">CASURECO II IT ASSET</div>
                </div>
                
                <div class="label-content" style="margin-top: 15px;">
                    <h2 class="asset-title">{{ $deviceName }}</h2>
                    <p class="asset-code" style="font-weight: bold;">{{ $deviceType }}: {{ $assetTag ?? 'N/A' }}</p>
                    <p class="asset-code" style="font-size: 8px; margin-top: 4px;">Assigned: {{ $dateAssigned }}</p>
                </div>
                <div id="qrcode" class="qr-code"></div>
            </div>
        </div>
    </div>

    <div style="position: fixed; bottom: 30px; right: 30px; display: flex; gap: 10px; z-index: 1000;" data-html2canvas-ignore>
        <button class="print-btn" style="position: static;" onclick="window.print()">Print Label</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var qrText = "{{ $assetTag }}";
            new QRCode(document.getElementById("qrcode"), {
                text: qrText,
                width: 86,
                height: 86,
                colorDark : "#000000",
                colorLight : "#ffffff",
                margin: 0,
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    </script>
</body>
</html>
