<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Device Inventory Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
        }
        .header h3 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }
        .header h4 {
            margin: 5px 0;
            font-size: 10pt;
            font-weight: normal;
        }
        .filters {
            font-size: 8pt;
            color: #000;
        }
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 100px;
            padding-top: 5px;
            font-size: 7.5pt;
        }
        .footer-left { float: left; width: 65%; text-align: left; }
        .footer-right { float: right; width: 35%; text-align: right; }
        .footer-top { margin-bottom: 5px; }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto; /* Allow table to break across pages */
        }
        tr { 
            page-break-inside: avoid; /* Avoid breaking inside a row */
            page-break-after: auto; 
        }
        thead { display: table-header-group; } /* Repeat header on new page */
        tfoot { display: table-footer-group; }
        
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            font-size: 8pt;
            color: #000;
        }
        th {
            background-color: transparent;
            font-weight: bold;
        }
        .status-assigned { color: #000; }
        .status-defective { color: #000; }
        .status-condemned { color: #000; }
        .status-available { color: #000; }
        @page {
            margin: 20px 40px 70px 40px;
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-top clearfix" style="margin-bottom: 10px;">
            <div style="float: left; width: 50%; text-align: left; line-height: 1.4;">
                Prepared by:<br><br>
                <strong>{{ strtoupper(auth()->user()->name) }}</strong><br>
                IT Technician
            </div>
            <div style="float: right; width: 50%; text-align: right; line-height: 1.4;">
                Noted by:<br><br>
                <strong>ELMER CLARETE</strong><br>
                OIC IT Supervisor
            </div>
        </div>
        <div class="clearfix" style="border-top: 1px solid #000; padding-top: 2px;">
            <div class="footer-left">
                <strong>FILTERS APPLIED:</strong>
                CATEGORY: {{ strtoupper($request->category ?? 'All') }} |
                SEARCH: {{ strtoupper($request->search ?: 'None') }} | 
                BRANCH: {{ strtoupper($request->branch ?: 'All') }} | 
                DEPT: {{ strtoupper($request->department ?: 'All') }} |
                TYPE: {{ strtoupper(is_array($request->type) ? implode(', ', $request->type) : ($request->type ?: 'All')) }} |
                STATUS: {{ strtoupper($request->status ?: 'All') }}
                <br>
                <strong>Total Units:</strong> {{ $items->count() }}
            </div>
            <div class="footer-right">
                Generated on: {{ now()->format('M d, Y h:i A') }}
            </div>
        </div>
    </footer>

    <div class="header">
        <h1>CASURECO II - Device Report</h1>
        <h3>INVENTORY OF {{ $request->status && strtoupper($request->status) != 'ALL' ? strtoupper(str_replace('_', ' ', $request->status)) . ' ' : '' }}  IT ASSETS</h3>
        <h4>As of {{ now()->format('F d, Y') }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">ASSET TAG</th>
                <th style="width: 25%">TYPE / BRAND / MODEL</th>
                <th style="width: 12%">IP ADDRESS</th>
                <th style="width: 13%">CATEGORY</th>
                <th style="width: 20%">LOCATION</th>
                <th style="width: 20%">ASSIGNED TO</th>
                <th style="width: 10%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->asset_tag }}</td>
                    <td>{{ str_replace($item->asset_tag . ' - ', '', $item->type_model) }}</td>
                    <td>{{ $item->ip_address }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ strtoupper($item->location) }}</td>
                    <td>{{ $item->assigned_to }}</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("sans-serif", "normal");
            $size = 8;
            $color = array(0,0,0);
            
            // Get dimensions
            $w = $pdf->get_width();
            $h = $pdf->get_height();
            
            // Right align
            $pdf->page_text($w - 80, $h - 30, $text, $font, $size, $color);
        }
    </script>
</body>
</html>


