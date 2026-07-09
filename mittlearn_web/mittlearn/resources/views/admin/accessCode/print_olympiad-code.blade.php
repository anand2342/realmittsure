@php
    // Paper dimensions in mm
    $paperDimensions = [
        'A3' => ['width' => 297, 'height' => 420],
        'A4' => ['width' => 210, 'height' => 297],
        'Letter' => ['width' => 216, 'height' => 279],
        '13*19" Page' => ['width' => 330.2, 'height' => 482.6],
    ];
    $paperSize = $getSetting['paper_size'] ?? 'A4';

    $orientation = $getSetting['orientation'] ?? 'Portrait';
    if ($paperSize === 'Custom') {
        $width = $getSetting['custom_width'] ?? 210;
        $height = $getSetting['custom_height'] ?? 297;
    } else {
        $width = $paperDimensions[$paperSize]['width'] ?? 210;
        $height = $paperDimensions[$paperSize]['height'] ?? 297;
    }
    if (strtolower($orientation) === 'landscape') {
        [$width, $height] = [$height, $width];
    }

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Olympiad Access Code Print</title>
    <style>
        @page {
            size: {{ $width }}mm {{ $height }}mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: {{ $getSetting['font_family'] ?? 'Arial' }}, sans-serif;
        }

        .page {
            width: {{ $width }}mm;
            height: {{ $height }}mm;
            page-break-after: always;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* .grid {
            display: grid;
            grid-template-columns: repeat({{ $getSetting['blocks_per_row'] ?? 1 }}, 1fr);
            grid-template-rows: repeat({{ $getSetting['blocks_per_column'] ?? 1 }}, 1fr);
            gap: {{ $getSetting['block_padding'] ?? 0 }}px;
            width: calc(100% - {{ ($getSetting['margin_left'] ?? 0) + ($getSetting['margin_right'] ?? 0) }}mm);
            height: calc(100% - {{ ($getSetting['margin_top'] ?? 0) + ($getSetting['margin_bottom'] ?? 0) }}mm);
            padding: {{ $getSetting['margin_top'] ?? 0 }}mm {{ $getSetting['margin_right'] ?? 0 }}mm {{ $getSetting['margin_bottom'] ?? 0 }}mm {{ $getSetting['margin_left'] ?? 0 }}mm;
            box-sizing: border-box;
        }
            .block {
            border: {{ $getSetting['block_border'] ?? false ? '1px solid #000' : 'none' }};
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: {{ $getSetting['font_size'] ?? 14 }}pt;
            text-align: {{ $getSetting['text_align'] ?? 'center' }};
            width: {{ $getSetting['blocks_width'] }}mm;
            height: {{ $getSetting['blocks_height'] }}mm;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        } */
        .grid {
            display: grid;
            grid-template-columns: repeat({{ $getSetting['blocks_per_row'] ?? 1 }}, {{ $getSetting['blocks_width'] ?? 47 }}mm);
            grid-template-rows: repeat({{ $getSetting['blocks_per_column'] ?? 1 }}, {{ $getSetting['blocks_height'] ?? 29 }}mm);
            gap: {{ $getSetting['block_padding'] ?? 0 }}px;
            width: auto;
            height: auto;
            padding: {{ $getSetting['margin_top'] ?? 0 }}mm {{ $getSetting['margin_right'] ?? 0 }}mm {{ $getSetting['margin_bottom'] ?? 0 }}mm {{ $getSetting['margin_left'] ?? 0 }}mm;
            box-sizing: border-box;
        }

        .block {
            border: {{ $getSetting['block_border'] ?? false ? '0.5px solid rgba(0, 0, 0, 0.3)' : 'none' }};
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: {{ $getSetting['font_size'] ?? 14 }}pt;
            text-align: {{ $getSetting['text_align'] ?? 'center' }};
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .header-info {
            position: absolute;
            top: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 18pt;
            z-index: 10;
        }
    </style>
</head>

<body>
    @php
        $blocksPerPage = ($getSetting['blocks_per_row'] ?? 1) * ($getSetting['blocks_per_column'] ?? 1);
        $chunks = $accessCodes->chunk($blocksPerPage);
    @endphp

    @foreach ($chunks as $codesChunk)
        <div class="page">
            <div class="header-info">
                {{ $codesChunk->first()->class_name . ' - ' . $codesChunk->first()->subject_name }}
            </div>

            <div class="grid">
                @foreach ($codesChunk as $code)
                    <div class="block">
                        {{ $code->access_code }}
                    </div>
                @endforeach

                {{-- Fill empty blocks if needed --}}
                @for ($i = $codesChunk->count(); $i < $blocksPerPage; $i++)
                    <div class="block"></div>
                @endfor
            </div>
        </div>
    @endforeach

    <script>
        window.print();
    </script>
</body>

</html>
