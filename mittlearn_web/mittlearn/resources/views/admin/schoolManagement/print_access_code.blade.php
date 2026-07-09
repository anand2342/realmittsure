@extends('admin.layouts.master')

@section('content')
    <title>Print Access Codes</title>
    <style>
        /* Add some basic print styles */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
    </head>
    <h2>Access Code</h2>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>School</th>
                <th>Board</th>
                <th>Medium</th>
                <th>Class</th>
                <th>Access Code</th>
                <th>Access Code Type</th>
                <th>Start Date</th>
                <th>Expired Date</th>
                <th>Status</th>
                <th>Used By</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accessCodes as $index => $data)
                @php
                    $accessCodeType = 'Embibe'; // Default value
                    if ($data->book_series_id == 1) {
                        $accessCodeType = 'Digital Content';
                    } elseif ($data->book_series_id == 3) {
                        $accessCodeType = 'Luma Learn';
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->school->name ?? 'N/A' }}</td>
                    <td>{{ $data->board->name ?? 'N/A' }}</td>
                    <td>{{ $data->medium->name ?? 'N/A' }}</td>
                    <td>{{ $data->class->name ?? 'N/A' }}</td>
                    <td>{{ $data->access_code }}</td>
                    <td>{{ $accessCodeType }}</td>
                    <td>{{ $data->start_date ?? 'N/A' }}</td>
                    <td>{{ $data->end_date ?? 'N/A' }}</td>
                    <td>{{ ucwords($data->status) }}</td>
                    <td>{{ $data->usedBy->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print(); // Trigger the print dialog
    </script>
@endsection
