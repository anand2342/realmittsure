@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">My Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tests</li>
            </ol>
        </nav>
        <!-- Test Instructions Modal -->
        
        <div class="modal fade" id="testInstructionsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h3 class="modal-title">Test Instructions</h3>
                    </div>
                    <div class="modal-body">
                        <div class="instruction-content">
                            <h5 class="mb-3">Please read the instructions carefully before starting the test:</h5>
                            <div style="max-height: 60vh; overflow-y: auto;" id="instructionContent">
                                <div class="mb-3">
                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">⏳</span>
                                            <span><strong>Test Duration:</strong> {{ $testPaper->duration }}
                                                minutes</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">✅</span>
                                            <span><strong>Passing Score:</strong> {{ $testPaper->min_passing_percentage }}%</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🧾</span>
                                            <span><strong>Question Types:</strong> The test consists of online type
                                                questions.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">✍️</span>
                                            <span><strong>All Questions Are Mandatory:</strong> You must answer every
                                                question
                                                before submitting. Unanswered questions will prevent final
                                                submission.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">➕</span>
                                            <span><strong>No Negative Marking:</strong> There is no penalty for incorrect
                                                answers, so attempt all questions confidently.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🌐</span>
                                            <span><strong>Stable Internet Required:</strong> Any network disconnection may
                                                automatically submit your test and log the activity.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🧭</span>
                                            <span><strong>Stay on Test Page:</strong> Switching to another tab or minimizing
                                                the
                                                browser will trigger warnings. Multiple violations will auto-submit your
                                                test.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🚫</span>
                                            <span><strong>Do Not Refresh:</strong> Reloading, pressing F5, or clicking the
                                                back
                                                button will end your test immediately.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🎥</span>
                                            <span><strong>Webcam Must Stay On:</strong> Your webcam must detect your face
                                                during
                                                the entire test. Obstructed or absent face = test terminated.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🎤</span>
                                            <span><strong>Mic Access May Be Monitored:</strong> Your microphone may be used
                                                to
                                                monitor ambient noise levels to detect suspicious behavior.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">📵</span>
                                            <span><strong>No Mobile Devices:</strong> Using your phone, smartwatches, or
                                                other
                                                digital devices is strictly prohibited during the test.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🧠</span>
                                            <span><strong>No External Help:</strong> This is an individual assessment.
                                                Collaboration or help from others will result in disqualification.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">🕵️‍♂️</span>
                                            <span><strong>AI Surveillance Active:</strong> Face, tab, and activity
                                                monitoring
                                                tools are in use to ensure test integrity. Every action is logged.</span>
                                        </li>
                                       
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">👁️</span>
                                            <span><strong>System Focus Monitoring:</strong> Unusual mouse movements or
                                                inactivity may be flagged as suspicious.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">⚠️</span>
                                            <span><strong>Zero Tolerance Policy:</strong> Any attempt to bypass restrictions
                                                will result in immediate test submission and logging of the attempt.</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <span class="me-2">📈</span>
                                            <span><strong>Result Review:</strong> Results will be reviewed before
                                                finalization.
                                                Suspicious attempts may be invalidated.</span>
                                        </li>
                                    </ul>

                                </div>

                                @if (!empty($testPaper->description))
                                    <div class="mb-3">
                                        <h5>Test Title:</h5>
                                        <div class="instructions">
                                            {!! $testPaper->title !!}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h5>📚 Test Description:</h5>
                                        <div class="instructions">
                                            {!! $testPaper->description !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="text-center mt-3">
                                    <p class="text-danger fw-bold">
                                        🚨 You are being monitored. Any kind of malpractice will lead to auto-submission,
                                        disqualification, and improper behavior will result in automatic test termination.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="startTestBtn" class="btn btn-success" disabled>✅ I’ve Read & Start
                            Test</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Only show Livewire when testStarted is true --}}
        @if (session('testStarted'))
            @livewire('user-test-paper-question', ['testId' => $id])
        @else
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    const modal = new bootstrap.Modal(document.getElementById('testInstructionsModal'));
                    modal.show();
                });

                // Enable start button after scroll
                document.addEventListener('DOMContentLoaded', () => {
                    const content = document.getElementById('instructionContent');
                    const button = document.getElementById('startTestBtn');
                    content.addEventListener('scroll', () => {
                        if (content.scrollTop + content.clientHeight >= content.scrollHeight) {
                            button.disabled = false;
                        }
                    });
                });

                // On Start Test button click, call server to set session
                document.getElementById('startTestBtn')?.addEventListener('click', () => {
                    fetch("{{ route('start.test.session', ['id' => $id]) }}")
                        .then(() => window.location.reload());
                });
            </script>
        @endif
    </div>
    </div>
    {{-- // // cheating restrict scripts --}}
    <script>
        // //  Prevent Page Refresh (F5 / Ctrl+R / Right-click Reload)
        // window.addEventListener("keydown", function(e) {
        //     if ((e.key === "F5") || (e.ctrlKey && e.key === "r")) {
        //         e.preventDefault();
        //     }
        // });
        // window.addEventListener("beforeunload", function(e) {
        //     e.preventDefault();
        //     e.returnValue = '';
        // });

        // Block Tab / Window Switching\
        let tabSwitchCount = 0;

        document.addEventListener("visibilitychange", function() {
            if (document.hidden) {
                tabSwitchCount++;

                if (tabSwitchCount === 1) {
                    alert(
                        "Tab switch detected!\nYou've left the test tab. This has been recorded.\nIf you switch tabs again, your test will be at risk."
                    );
                } else if (tabSwitchCount === 2) {
                    alert(
                        "Tab switch detected!\nYou've left the test tab. This has been recorded.\nIf you switch tabs again, your test will be at risk."
                    );
                } else if (tabSwitchCount >= 3) {
                    alert(
                        "Tab switch detected!\nYou've left the test tab. This has been recorded.\nIf you switch tabs again, your test will be at risk."
                    );
                    // Auto-submit the test form
                    // document.getElementById('test-form').submit(); // Replace 'test-form' with your actual form ID
                }
            }
        });


        // Disable Right Click and Keyboard Shortcuts (Copy/Paste, View Source)
        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
        });
        document.onkeydown = function(e) {
            if (
                e.ctrlKey && (e.key === "c" || e.key === "v" || e.key === "u") ||
                e.key === "F12"
            ) {
                return false;
            }
        };
    </script>
@endsection
