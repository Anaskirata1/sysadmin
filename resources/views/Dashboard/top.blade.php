<button id="showTopBtn" class="terminal-btn">
        <i class=""></i> Show TOP
    </button>

    <!-- شاشة التيرمنال -->
    <div id="terminal" class="terminal hidden">
        Loading...
    </div>


<style>
    .terminal-btn {
        background: #2c3e50;
        color: #ecf0f1;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background 0.3s, transform 0.2s;
    }
    .terminal-btn i {
        margin-right: 8px;
    }
    .terminal-btn:hover {
        background: #34495e;
        transform: scale(1.05);
    }

    .terminal {
        background: #1e1e1e;
        color: #00ff00;
        padding: 20px;
        border-radius: 8px;
        font-family: monospace;
        height: 500px;
        overflow-y: scroll;
        box-shadow: inset 0 0 10px #000;
        margin-top: 20px;
        white-space: pre-wrap;
    }
    .hidden {
        display: none;
    }
</style>

<script>
    let intervalId = null;

    async function fetchTop() {
        try {
            const response = await fetch("{{ route('dashboard.top.live') }}");
            const data = await response.json();
            const terminal = document.getElementById('terminal');
            terminal.textContent = data.output;
            terminal.scrollTop = terminal.scrollHeight; // ينزل للأسفل تلقائيًا
        } catch (error) {
            document.getElementById('terminal').textContent = "Error fetching data...";
        }
    }

    document.getElementById('showTopBtn').addEventListener('click', () => {
        const terminal = document.getElementById('terminal');
        terminal.classList.remove('hidden');
        terminal.textContent = "Loading...";

        // أول تحميل
        fetchTop();

        // تحديث كل 5 ثواني
        if (intervalId) clearInterval(intervalId);
        intervalId = setInterval(fetchTop, 5000);
    });
</script>