<div>
    <div class="row g-3">
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::label('name', 'Name', ['class' => 'form-label required']) !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::hidden('type', $type) !!}

            {!! Form::label('subject', 'Subject', ['class' => 'form-label required']) !!}
            {!! Form::text('subject', null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::label('cc', 'CC', ['class' => 'form-label ']) !!}
            {!! Form::text('cc', null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::label('bcc', 'BCC', ['class' => 'form-label ']) !!}
            {!! Form::text('bcc', null, ['class' => 'form-control']) !!}
        </div>
        @if (!$emailTemplateId)
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::label('action', 'Action', ['class' => 'form-label ']) !!}
                {!! Form::select('action', $actionOptions, $selectedAction, [
                    'class' => 'form-control',
                    'wire:model' => 'selectedAction',
                    'wire:change' => 'loadConstants($event.target.value)',
                    'id' => 'action',
                    'placeholder' => '--select--',
                ]) !!}
            </div>
        @endif
        @if ($type === 'email')
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::label('constants', 'Constant', ['class' => 'form-label ']) !!}
                {!! Form::select('constants', $options, $selectedConstant, [
                    'class' => 'form-control',
                    'wire:model' => 'selectedConstant',
                    'id' => 'constants',
                    'placeholder' => 'Select a constant',
                ]) !!}
            </div>
            <div class="mt-4 ">
                {!! Form::button('Insert Constant', [
                    'type' => 'button',
                    'class' => 'btn btn-primary',
                    'onclick' => 'insertConstant()',
                ]) !!}
            </div>
            <div class="col-lg-12" wire:ignore>
                {!! Form::label('body', 'Body', ['class' => 'form-label required']) !!}
                <div class="quill-editor-full" id="quill-editor" style="height: 200px;"></div>
                {!! Form::hidden('body', null, ['id' => 'editor-content', 'required' => true]) !!}
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const editor = document.querySelector('.quill-editor-full');
                    const initialContent = @json($body ?? ''); // Retrieve initial data from the backend

                    if (editor && window.Quill) {
                        const quillInstance = Quill.find(editor); // Get initialized instance from main.js

                        // Set initial content once Quill instance is ready
                        if (quillInstance) {
                            quillInstance.root.innerHTML = initialContent;

                            quillInstance.on('text-change', function() {
                                document.getElementById('editor-content').value = quillInstance.root.innerHTML;
                            });
                        }
                    }

                    // Sync content to hidden input on form submission
                    document.querySelector('form').addEventListener('submit', function() {
                        document.getElementById('editor-content').value = quillInstance.root.innerHTML;
                    });
                });

                function insertConstant() {
                    const selectedConstant = document.getElementById('constants').value;
                    const editor = document.querySelector('.quill-editor-full');

                    if (selectedConstant && editor && window.Quill) {
                        const quillInstance = Quill.find(editor);
                        const formattedConstant = `{${selectedConstant}}`;
                        const range = quillInstance.getSelection();

                        if (range) {
                            quillInstance.insertText(range.index, formattedConstant);
                        } else {
                            quillInstance.insertText(0, formattedConstant);
                        }
                    } else {
                        alert('Please select a constant to insert or ensure the editor is loaded.');
                    }
                }
            </script>
        @else
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::label('constants', 'Constants', ['class' => 'form-label']) !!}
                {!! Form::select('constants', ['' => 'Select a constant'] + $options, null, [
                    'class' => 'form-control',
                    'wire:model' => 'selectedConstant',
                ]) !!}
            </div>
            <div class="mt-4">
                {!! Form::button('Insert Constant', [
                    'type' => 'button',
                    'class' => 'btn btn-primary',
                    'wire:click' => 'insertConstant',
                ]) !!}
            </div>
            <div class="col-md-12 col-sm-6 col-xs-12">
                {!! Form::label('body', 'Body', ['class' => 'form-label']) !!}
                {!! Form::textarea('body', null, ['class' => 'form-control', 'wire:model' => 'body']) !!}
            </div>
        @endif
    </div>

</div>
