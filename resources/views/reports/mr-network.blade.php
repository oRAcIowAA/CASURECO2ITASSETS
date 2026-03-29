<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Memorandum Receipt</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; position: relative; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px; }
        .logo { position: absolute; top: 0; left: 0; width: 60px; }

        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 130px; }

        .section-title { font-weight: bold; text-transform: uppercase; margin: 10px 0 4px; font-size: 11px; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .items-table th { background-color: #f0f0f0; }

        .signatures { width: 100%; margin-top: 40px; }
        .signatures td { width: 50%; vertical-align: top; padding-right: 20px; }
        .line { border-bottom: 1px solid #000; margin-top: 40px; margin-bottom: 5px; }
        .signatory-name { font-weight: bold; text-transform: uppercase; }

        .note { font-size: 11px; line-height: 1.4; text-align: justify; }
    </style>
</head>
<body>
    @php
        $employee = $networkDevice->employee;
        $department = $employee ? $employee->department : ($networkDevice->department ?? 'N/A');
        // Handle branch if it's available on device or employee
        $branch = $networkDevice->branch ?? ($employee->branch ?? 'N/A');
    @endphp

    <div class="header">
        <!-- Company Logo -->
        <img src="{{ public_path('images/casureco-logo.png') }}" class="logo" alt="Casureco Logo">

        <h1>Camarines Sur II Electric Cooperative, Inc.</h1>
        <p>Del Gallego, Camarines Sur</p>
        <br>
        <h2>MEMORANDUM RECEIPT FOR EQUIPMENT</h2>
        <p>MR No: {{ date('Y') }}-ND-{{ str_pad($networkDevice->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Date Issued:</td>
            <td>{{ date('F d, Y') }}</td>
            <td class="label">Branch:</td>
            <td>{{ $branch }}</td>
        </tr>
        <tr>
            <td class="label">Issued To:</td>
            <td style="font-weight: bold; text-transform: uppercase;">
                {{ optional($employee)->full_name ?? 'N/A' }}
            </td>
            <td class="label">Employee ID:</td>
            <td>{{ optional($employee)->employee_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Position:</td>
            <td>{{ optional($employee)->position ?? 'N/A' }}</td>
            <td class="label">Department:</td>
            <td>{{ $department }}</td>
        </tr>
    </table>

    <div class="section-title">Equipment Details</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%;">Qty</th>
                <th style="width: 10%;">Unit</th>
                <th style="width: 47%;">Description / Specification</th>
                <th style="width: 20%;">Property No. / Serial</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Unit</td>
                <td>
                    <b>{{ $networkDevice->brand }} {{ $networkDevice->model }}</b><br>
                    Type: {{ $networkDevice->device_type }}<br>
                    Ports: {{ $networkDevice->network_ports }} ({{ $networkDevice->network_speed }})<br>
                    @if($networkDevice->ip_address || $networkDevice->mac_address)
                        Network: IP {{ $networkDevice->ip_address ?? 'N/A' }} / MAC {{ $networkDevice->mac_address ?? 'N/A' }}<br>
                    @endif
                    @if($networkDevice->serial_number)
                        Serial: {{ $networkDevice->serial_number }}<br>
                    @endif
                </td>
                <td>{{ $networkDevice->serial_number ?? $networkDevice->id }}</td>
                <td>{{ ucfirst($networkDevice->status) }}</td>
            </tr>
        </tbody>
    </table>

    <p class="note">
        I hereby acknowledge receipt of the above-described property/equipment which I shall use
        in the performance of my official duties. I promise to be fully accountable for the same
        and to return it in good condition upon demand or upon separation from the service.
    </p>

    <table class="signatures">
        <tr>
            <td>
                Issued by:
                <div class="line"></div>
                <div class="signatory-name">Admin / Property Custodian</div>
                <div>Authorized Signatory</div>
            </td>
            <td>
                Received by:
                <div class="line"></div>
                <div class="signatory-name">{{ optional($employee)->full_name ?? 'N/A' }}</div>
                <div>{{ optional($employee)->position ?? 'N/A' }}</div>
            </td>
        </tr>
    </table>

    <div style="font-size: 10px; margin-top: 40px; text-align: center; color: #666;">
        Generated by Casureco DMS on {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>
