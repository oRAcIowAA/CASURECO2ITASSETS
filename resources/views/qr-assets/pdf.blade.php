<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Asset QR Labels</title>
    <style>
        @page {
            margin: 0.25in;
        }
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .label-cell {
            width: 33.33%;
            padding: 0.05in;
            vertical-align: top;
        }
        .label {
            width: 100%;
            height: 1.0in;
            border: 1px dashed #ccc;
            padding: 8px;
            box-sizing: border-box;
            background: white;
            overflow: hidden;
            text-transform: uppercase;
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
        }
        .asset-code {
            font-size: 8px;
            color: #333;
            margin: 0;
            font-family: 'Courier', monospace;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
        $assetsPerPage = 24;
        $pages = array_chunk($assets, $assetsPerPage);
    @endphp

    @foreach($pages as $pageIndex => $pageAssets)
        <div class="container {{ $pageIndex < count($pages) - 1 ? 'page-break' : '' }}">
            <table class="main-table">
                @php
                    $rows = array_chunk($pageAssets, 3);
                @endphp
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $asset)
                            <td class="label-cell">
                                <div class="label" style="position: relative;">
                                    <div style="position: absolute; top: 4px; left: 8px;">
                                        <img src="{{ public_path('images/casureco-logo.png') }}" style="width: 14px; height: 14px; vertical-align: middle;">
                                        <span class="company-name" style="vertical-align: middle; margin-left: 2px;">CASURECO II IT ASSET</span>
                                    </div>
                                    
                                    <table style="width: 100%; border: none; margin-top: 16px; padding: 0; table-layout: fixed;">
                                        <tr>
                                            <td style="width: 65%; border: none; padding: 0; vertical-align: middle; overflow: hidden;">
                                                <div class="asset-title">{{ $asset['deviceName'] }}</div>
                                                <div class="asset-code" style="font-weight: bold;">Tag: {{ $asset['assetTag'] }}</div>
                                                <div class="asset-code" style="font-size: 6px; margin-top: 2px;">Issued: {{ $asset['dateIssued'] }}</div>
                                            </td>
                                            <td style="width: 35%; border: none; padding: 0; text-align: right; vertical-align: middle;">
                                                <img src="{{ $asset['qrBase64'] }}" style="width: 0.75in; height: 0.75in; display: block; margin-left: auto;">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        @endforeach
                        @if(count($row) < 3)
                            @for($i = 0; $i < 3 - count($row); $i++)
                                <td class="label-cell"></td>
                            @endfor
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach
</body>
</html>


