<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Asset Labels</title>
    <style>
        @page {
            size: 8.5in 11in;
            margin: 0.5in;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }
        .controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
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
        .page {
            width: 7.5in; /* 8.5 - 0.5 - 0.5 */
            min-height: 10in; /* 11 - 0.5 - 0.5 */
            padding: 0;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1.1in; /* Slightly more than 1in for spacing */
            gap: 0.1in;
            box-sizing: border-box;
            padding: 0.2in;
        }
        .label {
            width: 2.25in;
            height: 1.0in;
            border: 1px dashed #ccc;
            padding: 8px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
            text-transform: uppercase;
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
            background: #eee;
        }
        .qr-wrapper img {
            width: 100%;
            height: 100%;
        }

        @media print {
            body {
                background: white;
            }
            .controls {
                display: none;
            }
            .page {
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }
            .label {
                border: 1px dashed #eee; /* Light cutting guide */
            }
        }
    </style>
</head>
<body>

    <div class="controls" data-html2canvas-ignore>
        <div style="display: flex; gap: 10px;">
            <button class="print-btn" onclick="window.print()">Print all labels</button>
        </div>
    </div>

    <div id="labels-container">
        @php
            $assetsPerPage = 24; // 3 columns * 8 rows
            $pages = array_chunk($assets, $assetsPerPage);
        @endphp

        @foreach($pages as $pageAssets)
        <div class="page">
            @foreach($pageAssets as $index => $asset)
            <div class="label">
                <div class="label-content">
                    <div class="company-name">CASURECO II IT ASSET</div>
                    <h2 class="asset-title">{{ $asset['deviceName'] }}</h2>
                    <p class="asset-code">Tag: {{ $asset['assetTag'] }}</p>
                </div>
                <div id="qr-{{ $loop->parent->index }}-{{ $index }}" class="qr-wrapper" data-url="{{ $asset['assetTag'] }}"></div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const wrappers = document.querySelectorAll('.qr-wrapper');
            wrappers.forEach(wrapper => {
                const url = wrapper.getAttribute('data-url');
                new QRCode(wrapper, {
                    text: url,
                    width: 76,
                    height: 76,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    margin: 0,
                    correctLevel : QRCode.CorrectLevel.H
                });
            });
        });
    </script>
</body>
</html>
