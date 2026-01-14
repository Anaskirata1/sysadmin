<button id="showLsblkBtn" class="terminal-btn">
    <i class=""></i> Show LSBLK
</button>

<div id="terminal" class="terminal hidden">
    Loading...
</div>

<script>
    async function fetchLsblk() {
        try {
            const response = await fetch("{{ route('dashboard.lsblk.live') }}");
            const data = await response.json();
            const terminal = document.getElementById('terminal');
            terminal.textContent = data.output;
            terminal.scrollTop = terminal.scrollHeight;
        } catch (error) {
            document.getElementById('terminal').textContent = "Error fetching data...";
        }
    }

    document.getElementById('showLsblkBtn').addEventListener('click', () => {
        const terminal = document.getElementById('terminal');
        terminal.classList.remove('hidden');
        terminal.textContent = "Loading...";
        fetchLsblk(); // تحميل مرة واحدة فقط
    });
</script>