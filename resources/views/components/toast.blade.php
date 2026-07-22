<!-- SweetAlert2 Toast Integration -->
<script type="module">
    @if(session()->has('success'))
        if(window.Toast) {
            window.Toast.fire({ icon: 'success', title: '{!! addslashes(session()->get("success")) !!}' });
        }
    @endif
    @if(session()->has('error'))
        if(window.Toast) {
            window.Toast.fire({ icon: 'error', title: '{!! addslashes(session()->get("error")) !!}' });
        }
    @endif
    @if(session()->has('info'))
        if(window.Toast) {
            window.Toast.fire({ icon: 'info', title: '{!! addslashes(session()->get("info")) !!}' });
        }
    @endif
    @if(session()->has('warning'))
        if(window.Toast) {
            window.Toast.fire({ icon: 'warning', title: '{!! addslashes(session()->get("warning")) !!}' });
        }
    @endif

    // Listen for custom Alpine.js / Livewire events
    window.addEventListener('notify', (e) => {
        if(window.Toast) {
            window.Toast.fire({
                icon: e.detail.type || 'success',
                title: e.detail.message
            });
        }
    });
</script>
