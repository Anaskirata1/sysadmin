<x-app-layout>
    @section('content')
        <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">🔐 SSH Management Dashboard</h1>

        <!-- Card: Control buttons -->
        <div class="card mb-6">
            <h2 class="card-title">Controls</h2>
            <div class="controls">
                <button id="installBtn" class="terminal-btn"><i class="fas fa-download"></i> Install</button>
                <button id="enableBtn" class="terminal-btn"><i class="fas fa-play"></i> Enable</button>
                <button id="disableBtn" class="terminal-btn"><i class="fas fa-stop"></i> Disable</button>
                <button id="statusBtn" class="terminal-btn"><i class="fas fa-info-circle"></i> Status</button>
                <button id="listKeysBtn" class="terminal-btn"><i class="fas fa-key"></i> List Keys</button>
            </div>
        </div>

        <!-- Card: Terminal output -->
        <div class="card mb-6">
            <h2 class="card-title">Terminal Output</h2>
            <div id="terminal" class="terminal">Ready.</div>
        </div>

        <!-- Card: Keys table -->
        <div class="card mb-6">
            <h2 class="card-title">🔑 SSH Keys</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-700 text-gray-200">
                    <thead>
                        <tr class="bg-gray-800 text-gray-100">
                            <th class="border border-gray-600 px-6 py-3 text-center">Key Name</th>
                            <th class="border border-gray-600 px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="keysTable">
                        <tr>
                            <td colspan="2" class="border border-gray-600 px-6 py-3 text-center text-gray-400">
                                No keys yet. Click List Keys.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card: Create key -->
        <div class="card">
            <h2 class="card-title">➕ Create New Key</h2>
            <div class="w-full flex justify-center items-center gap-4 mt-4">
                <input type="text" id="keyNameInput"
                       placeholder="Enter Key Name"
                       class="port-input w-40 px-4 py-2 rounded border border-gray-600 bg-gray-900 text-green-400 focus:outline-none focus:ring-2 focus:ring-green-500">

                <button id="createKeyBtn"
                        class="terminal-btn bg-green-600 px-6 py-2 rounded shadow hover:bg-green-700">
                    <i class="fas fa-plus-circle"></i> Create Key
                </button>
            </div>
        </div>
    @endsection
</x-app-layout>

<style>
/* Card style */
.card {
    background: #f9fafb;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
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
    background: linear-gradient(135deg, #3b82f6, #2563eb);
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

/* Inputs */
.port-input {
    background:#fff;
    color:#111827;
    border:1px solid #d1d5db;
    padding:12px 16px;
    border-radius:8px;
    font-size: 1rem;
    min-width: 200px;
    transition: all 0.2s ease-in-out;
}
.port-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 6px #93c5fd;
    outline: none;
}
</style>

<script>
async function callApi(url, method="GET", body=null) {
    const res = await fetch(url, {
        method,
        headers: {
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
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

// زر Install
document.getElementById('installBtn').onclick = async () => {
    const data = await callApi("{{ route('ssh.install') }}","POST");
    showOutput(data.output || "SSH Installed");
};

// زر Enable
document.getElementById('enableBtn').onclick = async () => {
    const data = await callApi("{{ route('ssh.enable') }}","POST");
    showOutput(data.output || "SSH Enabled");
};

// زر Disable
document.getElementById('disableBtn').onclick = async () => {
    const data = await callApi("{{ route('ssh.disable') }}","POST");
    showOutput(data.output || "SSH Disabled");
};

// زر Status
document.getElementById('statusBtn').onclick = async () => {
    const data = await callApi("{{ route('ssh.status') }}");
    showOutput(data.output || "SSH Status");
};

// زر List Keys
document.getElementById('listKeysBtn').onclick = async () => {
    const data = await callApi("{{ route('ssh.listKeys') }}");
    showOutput(data.output || "Keys listed");
    refreshKeys(data.output);
};

// زر Create Key
document.getElementById('createKeyBtn').onclick = async () => {
    const keyName = document.getElementById('keyNameInput').value;
    if (!keyName) { showOutput("[ERROR] Enter key name"); return; }
    const data = await callApi("{{ route('ssh.createKey') }}","POST",{name:keyName});
    showOutput(data.output || "Key created");
    document.getElementById('keyNameInput').value = "";
    document.getElementById('listKeysBtn').click();
};

// تحديث جدول المفاتيح
function refreshKeys(output) {
    const tbody = document.getElementById('keysTable');
    tbody.innerHTML = "";
    const lines = output.split("\n").filter(l => l.includes(".pub"));
    if (lines.length === 0) {
        tbody.innerHTML = `<tr><td colspan="2" class="text-center text-gray-400">No keys found</td></tr>`;
        return;
    }
    lines.forEach(line => {
        const keyName = line.split("/").pop();
        tbody.innerHTML += `
            <tr>
                <td>${keyName}</td>
                <td>
                    <button class="terminal-btn bg-red-600" onclick="deleteKey('${keyName.replace('.pub','')}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        `;
    });
}

// زر Delete Key
async function deleteKey(name) {
    const data = await callApi("{{ route('ssh.deleteKey') }}","POST",{name});
    showOutput(data.output || "Key deleted");
    document.getElementById('listKeysBtn').click();
}
</script>