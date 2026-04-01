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
            width: 2.25in;
            height: 1.0in;
            background: white;
            border: 1px dashed #ccc;
            padding: 8px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
            text-transform: uppercase;
            overflow: hidden;
        }
        .label-content {
            flex-grow: 1;
            padding-right: 5px;
            min-width: 0;
        }
        .company-name {
            font-size: 7px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .asset-title {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            margin: 0 0 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .asset-code {
            font-size: 8px;
            color: #333;
            margin: 0;
            font-family: monospace;
            word-break: break-all;
        }
        .qr-wrapper {
            width: 0.7in;
            height: 0.7in;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-wrapper img {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        .print-btn {
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 16px;
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
                <div style="position: absolute; top: 4px; left: 8px; display: flex; align-items: center; gap: 4px;">
                    <img src="{{ asset('images/casureco-logo.png') }}" alt="logo" style="width: 14px; height: 14px;">
                    <div class="company-name" style="margin-bottom: 0;">CASURECO II IT ASSET</div>
                </div>
                
                <div class="label-content" style="margin-top: 10px;">
                    <h2 class="asset-title">{{ $deviceName }}</h2>
                    <p class="asset-code" style="font-weight: bold;">Tag: {{ $assetTag ?? 'N/A' }}</p>
                    <p class="asset-code" style="font-size: 6px; margin-top: 2px;">Assigned: {{ $dateAssigned }}</p>
                </div>
                <div id="qrcode" class="qr-wrapper"></div>
            </div>
        </div>
    </div>

    <div style="position: fixed; bottom: 30px; right: 30px; display: flex; gap: 10px; z-index: 1000;" data-html2canvas-ignore>
        <button class="print-btn" style="position: static;" onclick="window.print()">Print Label</button>
    </div>

    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var qrText = "{{ $assetTag }}";
            new QRCode(document.getElementById("qrcode"), {
                text: qrText,
                width: 76,
                height: 76,
                colorDark : "#000000",
                colorLight : "#ffffff",
                margin: 0,
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    </script>
</body>
</html>
