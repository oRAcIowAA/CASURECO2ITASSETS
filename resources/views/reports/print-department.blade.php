<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Department Assets Matrix Report</title>
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
            padding: 6px 6px;
            font-size: 8pt;
            color: #000;
        }
        
        /* Specific column alignments for Matrix */
        th, td.col-right {
            text-align: right;
        }
        th.col-left, td.col-left {
            text-align: left;
        }
        th.col-center, td.col-center {
            text-align: center;
        }

        th {
            background-color: transparent;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .totals-row td {
            font-weight: bold;
            background-color: transparent;
        }
        .text-red {
            color: #000;
        }
        .text-blue {
            color: #000;
        }
        
        /* Summary Section at the bottom */
        .summary {
            margin-top: 20px;
            font-size: 9pt;
            line-height: 1.5;
        }
        .summary-item {
            display: flex;
            width: 250px;
            justify-content: space-between;
        }
        .summary b {
            font-size: 10pt;
        }
        .summary td {
            border: none;
            padding: 2px 5px 2px 0;
        }

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
                SEARCH: {{ strtoupper($request->search ?: 'None') }} | 
                LOCATION: {{ strtoupper($request->group ?: 'All') }} | 
                DEPT: {{ strtoupper($request->department ?: 'All') }} |
                DIV: {{ strtoupper($request->division ?: 'All') }} | 
                TYPE: {{ strtoupper(is_array($request->type) ? implode(', ', $request->type) : ($request->type ?: 'All')) }} |
                STATUS: {{ strtoupper($request->status ?: 'All') }}
            </div>
            <div class="footer-right">
                Generated on: {{ now()->format('M d, Y h:i A') }}
            </div>
        </div>
    </footer>

    <div class="header">
        <h1>CASURECO II - IT Inventory Report </h1>
        <h3>INVENTORY OF {{ $request->status && strtoupper($request->status) != 'ALL' ? strtoupper(str_replace('_', ' ', $request->status)) . ' ' : '' }}  IT ASSETS</h3>
        <h4>As of {{ now()->format('F d, Y') }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-left" style="width: 25%">DEPARTMENT & AREA OFFICES</th>
                @foreach($deviceColumns as $colType)
                    <th class="col-center" style="font-size: 8pt">{{ $colType }}</th>
                @endforeach
                @if(count($deviceColumns) > 1)
                    <th class="col-center">TOTAL</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($reportMatrix as $row)
                <tr>
                    <td class="col-left">{{ strtoupper($row['department']) }}</td>
                    
                    @foreach($deviceColumns as $colType)
                        <td class="col-center {{ $row['types'][$colType] > 0 ? 'text-blue' : '' }}">
                            {{ $row['types'][$colType] > 0 ? $row['types'][$colType] : '-' }}
                        </td>
                    @endforeach

                    @if(count($deviceColumns) > 1)
                        <td class="col-center font-bold">
                            {{ $row['row_total'] }}
                        </td>
                    @endif
                </tr>
            @endforeach
            
            <tr class="totals-row">
                <td class="col-left">TOTAL</td>
                @foreach($deviceColumns as $colType)
                    <td class="col-center {{ $totals['col_totals'][$colType] > 0 ? 'text-blue' : '' }}">
                        {{ $totals['col_totals'][$colType] > 0 ? $totals['col_totals'][$colType] : '0' }}
                    </td>
                @endforeach
                @if(count($deviceColumns) > 1)
                    <td class="col-center font-bold">{{ $totals['total_issued'] }}</td>
                @endif
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <table style="width: 300px; margin: 0;">
            @foreach($deviceColumns as $colType)
                <tr>
                    <td style="width: 200px">ISSUED {{ strtoupper($colType) }}:</td>
                    <td style="text-align: right"><b>{{ $totals['col_totals'][$colType] ?? 0 }}</b></td>
                </tr>
@endforeach
            <tr>
                <td style="padding-top: 5px; border-top: 1px solid #000;"><b>TOTAL DEVICES ASSESSED:</b></td>
                <td style="text-align: right; padding-top: 5px; border-top: 1px solid #000;"><b>{{ $totals['total_issued'] }}</b></td>
            </tr>
        </table>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("sans-serif", "normal");
            $size = 8;
            $color = array(0,0,0);
            
            $w = $pdf->get_width();
            $h = $pdf->get_height();
            
            $pdf->page_text($w - 80, $h - 30, $text, $font, $size, $color);
        }
    </script>
</body>
</html>
