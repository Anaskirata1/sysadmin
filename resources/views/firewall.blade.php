<x-app-layout>
    @section('content')
        <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">🔥 Firewall Management Dashboard</h1>

        <!-- Card: Control buttons -->
        <div class="card mb-6">
            <h2 class="card-title">Controls</h2>
            <div class="controls">
                <button id="installBtn" class="terminal-btn"><i class="fas fa-download"></i> Install</button>
                <button id="enableBtn" class="terminal-btn"><i class="fas fa-play"></i> Enable</button>
                <button id="disableBtn" class="terminal-btn"><i class="fas fa-stop"></i> Disable</button>
                <button id="statusBtn" class="terminal-btn"><i class="fas fa-info-circle"></i> Status</button>
                <button id="resetBtn" class="terminal-btn"><i class="fas fa-sync-alt"></i> Reset</button>
            </div>
        </div>

        <!-- Card: Terminal output -->
        <div class="card mb-6">
            <h2 class="card-title">Terminal Output</h2>
            <div id="terminal" class="terminal">Ready.</div>
        </div>

        <!-- Card: Ports table -->
        <div class="card mb-6">
            <h2 class="card-title">📊 Firewall Open Ports</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-700 text-gray-200">
                    <thead>
                        <tr class="bg-gray-800 text-gray-100">
                            <th class="border border-gray-600 px-6 py-3 text-center">Port</th>
                            <th class="border border-gray-600 px-6 py-3 text-center">Protocol</th>
                            <th class="border border-gray-600 px-6 py-3 text-center">Status</th>
                            <th class="border border-gray-600 px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="portsTable">
                        <tr>
                            <td colspan="4" class="border border-gray-600 px-6 py-3 text-center text-gray-400">
                                No data yet. Click Status.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card: Add port -->
        <div class="card">
            <h2 class="card-title">➕ Add New Port</h2>
            <div class="w-full flex justify-center items-center gap-4 mt-4">
                <input type="number" id="portInput" min="1" max="65535"
                       placeholder="Enter Port"
                       class="port-input w-40 px-4 py-2 rounded border border-gray-600 bg-gray-900 text-green-400 focus:outline-none focus:ring-2 focus:ring-green-500">

                <select id="protoSelect"
                        class="port-select w-32 px-4 py-2 rounded border border-gray-600 bg-gray-900 text-green-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="tcp">TCP</option>
                    <option value="udp">UDP</option>
                </select>

                <button id="allowBtn"
                        class="terminal-btn bg-green-600 px-6 py-2 rounded shadow hover:bg-green-700">
                    <i class="fas fa-plus-circle"></i> Allow Port
                </button>
            </div>
        </div>
    @endsection
</x-app-layout>
 

<style>


/* Card style */
.card {
    background: #f9fafb; /* خلفية فاتحة */
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937; /* رمادي غامق للنص */
    margin-bottom: 16px;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 8px;
}

/* Buttons */
.controls {
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    justify-content:center;
    margin-bottom:16px;
}
.terminal-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb); /* أزرق فاتح */
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display:flex;
    align-items:center;
    gap:8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: background 0.3s, transform 0.2s;
}
.terminal-btn:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-1px) scale(1.03);
}

/* Terminal */
.terminal {
    background:#000000;       /* أسود */
    color:#00ff00;            /* أخضر */
    padding:20px;
    border-radius:12px;
    font-family:monospace;
    height:300px;
    overflow-y:auto;
    box-shadow: inset 0 0 8px rgba(0,255,0,0.2); /* ظل داخلي أخضر خفيف */
    white-space: pre-wrap;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}
th, td {
    border: 1px solid #d1d5db;
    padding: 14px;
    text-align: center;
}
thead tr {
    background-color: #e5e7eb;
}
tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

/* Inputs (form style) */
.port-form {
    display:flex;
    gap:16px;
    justify-content:center;
    align-items:center;
    margin-top:20px;
    flex-wrap:wrap;
}

.port-input, .port-select {
    background:#fff;
    color:#111827;
    border:1px solid #d1d5db;
    padding:12px 16px;
    border-radius:8px;
    font-size: 1rem;
    min-width: 200px; /* تكبير الحقل */
    transition: all 0.2s ease-in-out;
}

.port-input:focus, .port-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 6px #93c5fd;
    outline: none;
}

</style>

<script>
async function callApi(url, method="GET", body=null) {
    const res = await fetch(url, {
        method,
        headers: {"Content-Type":"application/json","X-CSRF-TOKEN":"{{ csrf_token() }}"},
        body: body ? JSON.stringify(body) : null
    });
    return res.json();
}

function showOutput(text) {
    const terminal = document.getElementById('terminal');
    const stamp = new Date().toLocaleString();
    terminal.textContent += `\n[${stamp}] ${text}\n`;
    terminal.scrollTop = terminal.scrollHeight;
}

async function refreshPorts() {
    const data = await callApi("{{ route('firewall.ports') }}");
    const tbody = document.getElementById('portsTable');
    tbody.innerHTML = "";
    if (!data.ports || data.ports.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-gray-400">No open ports.</td></tr>`;
        return;
    }
    data.ports.forEach(p => {
        tbody.innerHTML += `
            <tr>
                <td class="px-4 py-2">${p.port}</td>
                <td class="px-4 py-2">${p.protocol.toUpperCase()}</td>
                <td class="px-4 py-2">${p.status}</td>
                <td class="px-4 py-2">
                    <button class="terminal-btn bg-red-600" onclick="denyPort(${p.port}, '${p.protocol}')">
                        <i class="fas fa-ban"></i> Deny
                    </button>
                </td>
            </tr>
        `;
    });
}

document.getElementById('installBtn').onclick = async () => {
    const data = await callApi("{{ route('firewall.install') }}","POST");
    showOutput(data.output || "Install done");
};

document.getElementById('enableBtn').onclick = async () => {
    const data = await callApi("{{ route('firewall.enable') }}","POST");
    showOutput(data.output || "Enabled");
};

document.getElementById('disableBtn').onclick = async () => {
    const data = await callApi("{{ route('firewall.disable') }}","POST");
    showOutput(data.output || "Disabled");
};

document.getElementById('statusBtn').onclick = async () => {
    const data = await callApi("{{ route('firewall.status') }}");
    showOutput(data.output || "Status shown");
    await refreshPorts();
};

document.getElementById('resetBtn').onclick = async () => {
    const data = await callApi("{{ route('firewall.reset') }}","POST");
    showOutput(data.output || "Reset done");
    await refreshPorts();
};

document.getElementById('allowBtn').onclick = async () => {
    const port = document.getElementById('portInput').value;
    const protocol = document.getElementById('protoSelect').value;
    if (!port) { showOutput("[ERROR] Enter port"); return; }
    const data = await callApi("{{ route('firewall.allow') }}","POST",{port, protocol});
    showOutput(data.output || "Allowed");
    await refreshPorts();
};

async function denyPort(port, protocol) {
    const data = await callApi("{{ route('firewall.deny') }}","POST",{port, protocol});
    showOutput(data.output || "Denied");
    await refreshPorts();
}

// Initial load
refreshPorts();
</script>