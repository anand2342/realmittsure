@extends('admin.layouts.master')
@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quill Editor with LaTeX</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap');

        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }
    </style>
</head>

<body>

    <h2>Quill Editor with LaTeX Support</h2>

    <div id="toolbar">
        <button id="latex-button">Insert LaTeX</button>
    </div>

    <!-- Editor -->
    <div id="editor-container"></div>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const quill = new Quill("#editor-container", {
                theme: "snow",
                modules: {
                    toolbar: [
                        [{
                            script: "sub"
                        }, {
                            script: "super"
                        }],
                        ["bold", "italic", "underline"]
                    ],
                },
            });

            const languageSelector = document.getElementById("language");
            const insertButton = document.getElementById("insert-text");

            // Custom LaTeX Blot
            const BlockEmbed = Quill.import("blots/block/embed");

            class LatexBlot extends BlockEmbed {
                static create(value) {
                    const node = super.create();
                    katex.render(value, node, {
                        throwOnError: false
                    });
                    node.setAttribute("data-latex", value);
                    return node;
                }

                static value(node) {
                    return node.getAttribute("data-latex");
                }
            }

            LatexBlot.blotName = "latex";
            LatexBlot.tagName = "div";
            Quill.register(LatexBlot);

            // LaTeX Insert Button
            document.getElementById("latex-button").addEventListener("click", function() {
                const latex = prompt("Enter LaTeX code (e.g., E = mc^2):");
                if (latex) {
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, "latex", latex);
                }
            });


            insertButton.addEventListener("click", function() {
                const selectedLanguage = languageSelector.value;
                const range = quill.getSelection();
                let textToInsert = "";

                if (selectedLanguage === "hindi") {
                    textToInsert = "यह हिंदी टेक्स्ट क्विल एडिटर में जोड़ा गया है।";
                    quill.formatText(range.index, textToInsert.length, "font", "hindi-text");
                } else {
                    textToInsert = "This is English text added to the Quill editor.";
                }

                if (range) {
                    quill.insertText(range.index, textToInsert, "bold", true);
                }
            });
        });
    </script>
@endsection

