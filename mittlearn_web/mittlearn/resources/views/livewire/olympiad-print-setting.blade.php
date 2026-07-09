<div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title pb-0">Olympiad Print Settings</h5>
                        <hr class="form-divider">

                        {{ Form::open(['wire:submit.prevent' => 'saveSettings', 'class' => 'row g-3']) }}
                        {{-- Paper Settings --}}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('paper_size', 'Paper Size', ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'paper_size',
                                [
                                    'A3' => 'A3',
                                    'A4' => 'A4',
                                    'Letter' => 'Letter',
                                    '13*19" Page' => 'Mittsure 13*19" Page',
                                    'Custom' => 'Custom Width & Height Page',
                                ],
                                $olympiadSetting['paper_size'] ?? '',
                                [
                                    'class' => 'form-control',
                                    'wire:model.defer' => 'paper_size',
                                    'wire:change' => 'handlePaperSizeChange', // manually trigger Livewire method
                                    'placeholder' => '--select--',
                                ],
                            ) !!}
                            @error('paper_size')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($paper_size === 'Custom')
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('custom_width', 'Page Width (mm)', ['class' => 'form-label']) !!}
                                {!! Form::number('custom_width', $custom_width, [
                                    'class' => 'form-control',
                                    'wire:model.defer' => 'custom_width',
                                ]) !!}
                                @error('custom_width')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('custom_height', 'Page Height (mm)', ['class' => 'form-label']) !!}
                                {!! Form::number('custom_height', $custom_height, [
                                    'class' => 'form-control',
                                    'wire:model.defer' => 'custom_height',
                                ]) !!}
                                @error('custom_height')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('orientation', 'Orientation (Layout)', ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'orientation',
                                ['Portrait' => 'Portrait', 'Landscape' => 'Landscape'],
                                $olympiadSetting['orientation'] ?? '',
                                [
                                    'class' => 'form-control',
                                    'wire:model.defer' => 'orientation',
                                    'placeholder' => '--select--',
                                ],
                            ) !!}
                            @error('orientation')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Margin Settings --}}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('margin_top', 'Margin Top (mm)', ['class' => 'form-label']) !!}
                            {!! Form::number('margin_top', $olympiadSetting['margin_top'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'margin_top',
                            ]) !!}
                            @error('margin_top')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('margin_bottom', 'Margin Bottom (mm)', ['class' => 'form-label']) !!}
                            {!! Form::number('margin_bottom', $olympiadSetting['margin_bottom'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'margin_bottom',
                            ]) !!}
                            @error('margin_bottom')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('margin_left', 'Margin Left (mm)', ['class' => 'form-label']) !!}
                            {!! Form::number('margin_left', $olympiadSetting['margin_left'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'margin_left',
                            ]) !!}
                            @error('margin_left')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('margin_right', 'Margin Right (mm)', ['class' => 'form-label']) !!}
                            {!! Form::number('margin_right', $olympiadSetting['margin_right'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'margin_right',
                            ]) !!}
                            @error('margin_right')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Block Settings --}}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('blocks_per_row', 'Blocks Per Row', ['class' => 'form-label']) !!}
                            {!! Form::number('blocks_per_row', $olympiadSetting['margin_from_left'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'blocks_per_row',
                            ]) !!}
                            @error('blocks_per_row')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('blocks_per_column', 'Blocks Per Column', ['class' => 'form-label']) !!}
                            {!! Form::number('blocks_per_column', $olympiadSetting['blocks_per_column'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'blocks_per_column',
                            ]) !!}
                            @error('blocks_per_column')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('blocks_width', 'Blocks Width (mm)', ['class' => 'form-label']) !!}
                            {!! Form::number('blocks_width', $olympiadSetting['blocks_width'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'blocks_width',
                            ]) !!}
                            @error('blocks_width')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('blocks_height', 'Blocks Height (mm)', ['class' => 'form-label']) !!}
                            {!! Form::number('blocks_height', $olympiadSetting['blocks_height'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'blocks_height',
                            ]) !!}
                            @error('blocks_height')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('block_padding', 'Block Padding', ['class' => 'form-label']) !!}
                            {!! Form::number('block_padding', $olympiadSetting['block_padding'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'block_padding',
                            ]) !!}
                            @error('block_padding')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('block_border', 'Block Border', ['class' => 'form-label d-block']) !!}
                            {!! Form::checkbox('block_border', 1, $olympiadSetting['block_border'] ?? false, [
                                'wire:model.defer' => 'block_border',
                                'class' => 'form-check-input',
                            ]) !!}
                            @error('block_border')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Text Settings --}}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('font_family', 'Font Family', ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'font_family',
                                ['Arial' => 'Arial', 'Roboto' => 'Roboto', 'Times New Roman' => 'Times New Roman'],
                                $olympiadSetting['font_family'] ?? '',
                                ['class' => 'form-control', 'wire:model.defer' => 'font_family', 'placeholder' => '--select--'],
                            ) !!}
                            @error('font_family')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('font_size', 'Font Size', ['class' => 'form-label']) !!}
                            {!! Form::number('font_size', $olympiadSetting['font_size'] ?? '', [
                                'class' => 'form-control',
                                'wire:model.defer' => 'font_size',
                            ]) !!}
                            @error('font_size')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('text_align', 'Text Align', ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'text_align',
                                ['center' => 'Center', 'left' => 'Left', 'right' => 'Right'],
                                $olympiadSetting['text_align'] ?? '',
                                [
                                    'class' => 'form-control',
                                    'wire:model.defer' => 'text_align',
                                    'placeholder' => '--select--',
                                ],
                            ) !!}
                            @error('text_align')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Preview Button --}}
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" wire:click="previewSettings">Preview
                                Settings</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>


                        {{ Form::close() }}


                        {{-- Modal Preview --}}
                        <!-- Bootstrap Modal -->
                        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel"
                            aria-hidden="true" wire:ignore.self>
                            <div class="modal-dialog modal-fullscreen modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="previewModalLabel">Preview Settings</h5>
                                        <button type="button" class="text-end btn btn-dark"
                                            data-bs-dismiss="modal">Close Preview</button>
                                    </div>
                                    @php
                                        $paperDimensions = [
                                            'A3' => ['width' => 297, 'height' => 420],
                                            'A4' => ['width' => 210, 'height' => 297],
                                            'Letter' => ['width' => 216, 'height' => 279],
                                            '13*19" Page' => ['width' => 330.2, 'height' => 482.6],
                                        ];

                                        $defaultSize = $paperDimensions[$paper_size] ?? [
                                            'width' => 210,
                                            'height' => 297,
                                        ];

                                        // Apply orientation
                                        if ($paper_size === 'Custom') {
                                            $width = $custom_width;
                                            $height = $custom_height;
                                        } else {
                                            $defaultSize = $paperDimensions[$paper_size] ?? [
                                                'width' => 210,
                                                'height' => 297,
                                            ];

                                            if ($orientation === 'Landscape') {
                                                $width = $defaultSize['height'];
                                                $height = $defaultSize['width'];
                                            } else {
                                                $width = $defaultSize['width'];
                                                $height = $defaultSize['height'];
                                            }
                                        }
                                    @endphp

                                    <div class="modal-body">
                                        <div
                                            style="
                                                            width: {{ $width }}mm;
                                                            height: {{ $height }}mm;
                                                            border: 2px dashed #999;
                                                            background-color: #f9f9f9;
                                                            margin: 0 auto;
                                                            padding: {{ $margin_top }}mm {{ $margin_right }}mm {{ $margin_bottom }}mm {{ $margin_left }}mm;
                                                        ">

                                            <h6 class="mb-3 text-center">Page: {{ $paper_size }}</h6>
                                            <div
                                                style="
                                                            display: grid;
                                                            grid-template-columns: repeat({{ $blocks_per_row }}, 1fr);
                                                            grid-template-rows: repeat({{ $blocks_per_column }}, 1fr);
                                                            gap: {{ $block_padding }}px;
                                                            border: 1px solid #000;
                                                            padding: 20px;
                                                            background: #fff;
                                                            width: 100%;
                                                            height: 100%;
                                                        ">
                                                @for ($i = 1; $i <= $blocks_per_row * $blocks_per_column; $i++)
                                                    <div
                                                        style="
                                                                    border: {{ $block_border ? '1px solid black' : 'none' }};
                                                                    font-family: {{ $font_family }};
                                                                    font-size: {{ $font_size }}pt;
                                                                    text-align: {{ $text_align }};
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    width: {{ $blocks_width }}mm;
                                                                    height: {{ $blocks_height }}mm;
                                                                    white-space: nowrap;
                                                                    overflow: hidden;   
                                                                    box-sizing: border-box;  
                                                                ">
                                                        Block {{ $i }}
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        window.addEventListener('show-preview-modal', () => {
            var myModal = new bootstrap.Modal(document.getElementById('previewModal'));
            myModal.show();
        });
    </script>

</div>
