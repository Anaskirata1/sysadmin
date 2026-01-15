<x-app-layout>
    @section('content')
        <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">📊 Logs Dashboard</h1>

        <!-- Controls -->
        <div class="card mb-6">
            <h2 class="card-title">Controls</h2>
            <div class="controls">
                <!-- System -->
                <button id="syslogBtn" class="terminal-btn">System Logs</button>
                <button id="authBtn" class="terminal-btn">Auth Logs</button>
                <button id="kernBtn" class="terminal-btn">Kernel Logs</button>

                <!-- Apache -->
                <button id="apacheAccessBtn" class="terminal-btn">Apache Access</button>
                <button id="apacheErrorBtn" class="terminal-btn">Apache Error</button>

                <!-- Laravel -->
                <button id="laravelBtn" class="terminal-btn">Laravel Logs</button>
            </div>
        </div>

        <!-- Terminal -->
        <div class="card mb-6">
            <h2 class="card-title">Terminal Output</h2>
            <div id="terminal" class="terminal">Ready.</div>
        </div>
    @endsection
</x-app-layout>

<style>
.card { background:#f9fafb; border-radius:12px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px; }
.card-title { font-size:1.25rem; font-weight:700; color:#1f2937; margin-bottom:16px; border-bottom:2px solid #e5e7eb; padding-bottom:8px; }
.controls { display:flex; gap:16px; flex-wrap:wrap; justify-content:center; margin-bottom:16px; }
.terminal-btn { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; border:none; padding:12px 20px; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 4px 8px rgba(0,0,0,0.15); transition:background 0.3s,transform 0.2s; }
.terminal-btn:hover { background:linear-gradient(135deg,#2563eb,#1d4ed8); transform:translateY(-1px) scale(1.03); }
.terminal { background:#000; color:#0f0; padding:20px; border-radius:12px; font-family:monospace; height:300px; overflow-y:auto; box-shadow:inset 0 0 8px rgba(0,255,0,0.2); white-space:pre-wrap; }
</style>

<script>
async function callApi(url) {
    const res = await fetch(url, { headers: { "X-CSRF-TOKEN":"{{ csrf_token() }}" } });
    return res.json();
}
function showOutput(text) {
    const terminal = document.getElementById('terminal');
    const stamp = new Date().toLocaleString();
    terminal.textContent += `\n[${stamp}] ${text}\n`;
    terminal.scrollTop = terminal.scrollHeight;
}

// System
document.getElementById('syslogBtn').onclick = async () => { const data = await callApi("{{ route('logs.system') }}"); showOutput(data.output); };
document.getElementById('authBtn').onclick = async () => { const data = await callApi("{{ route('logs.auth') }}"); showOutput(data.output); };
document.getElementById('kernBtn').onclick = async () => { const data = await callApi("{{ route('logs.kern') }}"); showOutput(data.output); };

// Apache
document.getElementById('apacheAccessBtn').onclick = async () => { const data = await callApi("{{ route('logs.apache.access') }}"); showOutput(data.output); };
document.getElementById('apacheErrorBtn').onclick = async () => { const data = await callApi("{{ route('logs.apache.error') }}"); showOutput(data.output); };

// Laravel
document.getElementById('laravelBtn').onclick = async () => { const data = await callApi("{{ route('logs.laravel') }}"); showOutput(data.output); };
</script>