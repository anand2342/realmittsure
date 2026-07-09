<div>
    <span id="otp-timer" class="timing mt-4">
        Resend OTP in <b id="timer-display">{{ $timeRemaining }}</b> seconds
    </span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let timeRemaining = @json($timeRemaining); 

        const timerDisplay = document.getElementById('timer-display');

        const interval = setInterval(() => {
            if (timeRemaining > 0) {
                timeRemaining--;
                timerDisplay.textContent = timeRemaining;
                Livewire.emit('decrementTimer');
            } else {
                clearInterval(interval);
            }
        }, 1000);
    });
</script>
