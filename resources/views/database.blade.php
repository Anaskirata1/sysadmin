<x-app-layout>
    @section('content')
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">MySQL Database Management</h1>

        <!-- Card: Control buttons -->
        <div class="card mb-6">
            <h2 class="card-title">Database Control</h2>
            <div class="controls">
                <button id="installBtn" class="terminal-btn"><i class="fas fa-download"></i> Install</button>
                <button id="enableBtn" class="terminal-btn"><i class="fas fa-play"></i> Enable</button>
                <button id="disableBtn" class="terminal-btn"><i class="fas fa-stop"></i> Disable</button>
                <button id="statusBtn" class="terminal-btn"><i class="fas fa-info-circle"></i> Status</button>
            </div>
            <div id="terminal" class="terminal">Ready...</div>
        </div>

        <!-- Card: Databases Table -->
        <div class="card mb-6">
            <h2 class="card-title">Databases</h2>
            <table class="min-w-full border border-gray-300 text-gray-700">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2 text-center">Name</th>
                        <th class="border px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="dbTable">
                    <tr>
                        <td colspan="2" class="border px-4 py-2 text-center text-gray-400">No databases yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Card: Create Database -->
        <div class="card">
            <h2 class="card-title">Create New Database</h2>
            <div class="flex items-center gap-4 mt-4">
                <input type="text" id="dbNameInput" placeholder="Enter Database Name"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm transition">
                <button id="createDbBtn"
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md text-base flex items-center gap-2 transition transform hover:scale-105">
                    <i class="fas fa-plus-circle"></i> Create
                </button>
            </div>
        </div>
    @endsection
</x-app-layout>

<style>
.card {
    background: #f9fafb;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 6px;
}
.controls { display:flex; gap:12px; flex-wrap:wrap; justify-content:center; margin-bottom:16px; }
.terminal-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display:flex; align-items:center; gap:8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: background 0.3s, transform 0.2s;
}
.terminal-btn:hover { background: linear-gradient(135deg, #2563eb, #1d4ed8); transform: translateY(-2px) scale(1.05); }
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

// Control buttons
document.getElementById('installBtn').onclick = async () => {
    const data = await callApi("{{ route('database.install') }}","POST");
    showOutput(data.output || "Install done");
};
document.getElementById('enableBtn').onclick = async () => {
    const data = await callApi("{{ route('database.enable') }}","POST");
    showOutput(data.output || "Enabled");
};
document.getElementById('disableBtn').onclick = async () => {
    const data = await callApi("{{ route('database.disable') }}","POST");
    showOutput(data.output || "Disabled");
};
document.getElementById('statusBtn').onclick = async () => {
    const data = await callApi("{{ route('database.status') }}");
    showOutput(data.output || "Status shown");
    refreshDatabases();
};

// Refresh table
async function refreshDatabases() {
    const data = await callApi("{{ route('database.list') }}");
    const tbody = document.getElementById('dbTable');
    tbody.innerHTML = "";
    if (!data.databases || data.databases.length === 0) {
        tbody.innerHTML = `<tr><td colspan="2" class="border px-4 py-2 text-center text-gray-400">No databases.</td></tr>`;
        return;
    }
    data.databases.forEach(db => {
        tbody.innerHTML += `
            <tr>
                <td class="border px-4 py-2">${db.name}</td>
                <td class="border px-4 py-2 flex justify-center gap-2">
                   <button class="btn btn-danger btn-sm shadow" onclick="deleteDb('${db.name}')">
    <i class="fas fa-trash text-white"></i> Delete
</button>
                </td>
            </tr>
        `;
    });
}

// Create DB
document.getElementById('createDbBtn').onclick = async () => {
    const dbName = document.getElementById('dbNameInput').value;
    if (!dbName) { showOutput("[ERROR] Enter database name"); return; }
    const data = await callApi("{{ route('database.create') }}","POST",{name: dbName});
    showOutput(data.output || "Database created");
    // تنفيذ أوامر GRANT و FLUSH
    await callApi("{{ route('database.grant') }}","POST",{user:"aya1"});
    showOutput("Privileges granted to aya1@localhost");
    refreshDatabases();
};

// Edit/Delete handlers
// async function editDb(name) {
 //   showOutput("Edit DB: " + name);
    // هنا تضع منطق التعديل لاحقًا
// }
async function deleteDb(name) {
    const data = await callApi("{{ route('database.delete') }}","POST",{name});
    showOutput(data.output || "Database deleted");
    refreshDatabases();
}

// Initial load
refreshDatabases();
</script>