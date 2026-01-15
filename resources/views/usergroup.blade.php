<x-app-layout>
    @section('content')
        <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">👥 Users & Groups Management</h1>

        <!-- Controls -->
        <div class="card mb-6">
            <h2 class="card-title">Controls</h2>
            <div class="controls">
                <button id="listBtn" class="terminal-btn"><i class="fas fa-list"></i> List Users & Groups</button>
            </div>
        </div>

        <!-- Terminal -->
        <div class="card mb-6">
            <h2 class="card-title">Terminal Output</h2>
            <div id="terminal" class="terminal">Ready.</div>
        </div>

        <!-- Users Table -->
        <div class="card mb-6">
            <h2 class="card-title">📋 Current Users</h2>
            <table class="w-full border border-gray-700 text-gray-200">
                <thead>
                    <tr class="bg-gray-800 text-gray-100">
                        <th class="border px-6 py-3 text-center">User</th>
                        <th class="border px-6 py-3 text-center">UID</th>
                        <th class="border px-6 py-3 text-center">GID</th>
                        <th class="border px-6 py-3 text-center">Home</th>
                        <th class="border px-6 py-3 text-center">Shell</th>
                        <!-- <th class="border px-6 py-3 text-center">Action</th> -->
                    </tr>
                </thead>
                <tbody id="usersTable">
                    <tr><td colspan="6" class="text-center text-gray-400">No data yet.</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Groups Table -->
        <div class="card mb-6">
            <h2 class="card-title">📂 Current Groups</h2>
            <table class="w-full border border-gray-700 text-gray-200">
                <thead>
                    <tr class="bg-gray-800 text-gray-100">
                        <th class="border px-6 py-3 text-center">Group</th>
                        <th class="border px-6 py-3 text-center">GID</th>
                        <th class="border px-6 py-3 text-center">Members</th>
                        <!-- <th class="border px-6 py-3 text-center">Action</th> -->
                    </tr>
                </thead>
                <tbody id="groupsTable">
                    <tr><td colspan="4" class="text-center text-gray-400">No data yet.</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Create User -->
        <div class="card mb-6">
            <h2 class="card-title">➕ Create User</h2>
            <div class="grid grid-cols-2 gap-4">
                <input type="text" id="usernameInput" placeholder="Username" title="اسم المستخدم الجديد" class="port-input">
                <input type="number" id="uidInput" placeholder="UID" title="معرّف المستخدم (اختياري)" class="port-input">
                <input type="number" id="gidInput" placeholder="GID" title="معرّف المجموعة الأساسية (اختياري)" class="port-input">
                <input type="text" id="homeInput" placeholder="Home Directory" title="مسار مجلد المنزل (اختياري)" class="port-input">
                <input type="text" id="shellInput" placeholder="Shell" title="الـ Shell الافتراضي مثل /bin/bash" class="port-input">
                <input type="text" id="groupInputUser" placeholder="Secondary Group" title="مجموعة إضافية (اختياري)" class="port-input">
                <label title="إنشاء مجلد المنزل تلقائيًا"><input type="checkbox" id="createHome"> Create Home Dir</label>
            </div>
            <button id="createUserBtn" class="terminal-btn bg-green-600 mt-4"><i class="fas fa-user-plus"></i> Create User</button>
        </div>

        <!-- Create Group -->
        <div class="card mb-6">
            <h2 class="card-title">➕ Create Group</h2>
            <div class="flex gap-4 justify-center items-center">
                <input type="text" id="groupInput" placeholder="Group Name" title="اسم المجموعة الجديدة" class="port-input">
                <input type="number" id="gidGroupInput" placeholder="GID" title="معرّف المجموعة (اختياري)" class="port-input">
                <button id="createGroupBtn" class="terminal-btn bg-green-600"><i class="fas fa-users"></i> Create Group</button>
            </div>
        </div>

        <!-- Modal for root password -->
      <!--  <div id="passwordModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-lg font-bold mb-4">Enter root password</h2>
        <input type="password" id="rootPassword" class="border px-4 py-2 w-full mb-4" placeholder="Password">
        <div class="flex justify-end gap-4">
            <button onclick="closeModal()" class="terminal-btn bg-gray-500">Cancel</button>
            <button id="confirmDeleteBtn" class="terminal-btn bg-red-600">Confirm Delete</button>
        </div>
    </div>
    </div> -->
        
    @endsection
</x-app-layout>

<style>
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
.terminal {
    background:#000;
    color:#0f0;
    padding:20px;
    border-radius:12px;
    font-family:monospace;
    height:300px;
    overflow-y:auto;
    box-shadow: inset 0 0 8px rgba(0,255,0,0.2);
    white-space: pre-wrap;
}
.port-input {
    background:#fff;
    color:#111827;
    border:1px solid #d1d5db;
    padding:12px 16px;
    border-radius:8px;
    font-size: 1rem;
    min-width: 200px;
}
#passwordModal.hidden {
  display: none !important;
}
#passwordModal {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: rgba(0,0,0,0.5);
  z-index: 9999;
  justify-content: center;
  align-items: center;
}
#passwordModal.show {
  display: flex;
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

// زر List
document.getElementById('listBtn').onclick = async () => {
    const data = await callApi("{{ route('users.groups.list') }}");
    showOutput("Users:\n" + JSON.stringify(data.users,null,2) + "\nGroups:\n" + JSON.stringify(data.groups,null,2));

    // تعبئة جدول المستخدمين
    const usersTable = document.getElementById('usersTable');
    usersTable.innerHTML = "";
    if (data.users.length === 0) {
        usersTable.innerHTML = `<tr><td colspan="6" class="text-center text-gray-400">No users found</td></tr>`;
    } else {
        data.users.forEach(user => {
            usersTable.innerHTML += `
                <tr>
                    <td>${user.username}</td>
                    <td>${user.uid}</td>
                    <td>${user.gid}</td>
                    <td>${user.home}</td>
                    <td>${user.shell}</td>
                    
                </tr>
            `;
        });
    }

    // تعبئة جدول المجموعات
    const groupsTable = document.getElementById('groupsTable');
    groupsTable.innerHTML = "";
    if (data.groups.length === 0) {
        groupsTable.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400">No groups found</td></tr>`;
    } else {
        data.groups.forEach(group => {
            groupsTable.innerHTML += `
                <tr>
                    <td>${group.groupname}</td>
                    <td>${group.gid}</td>
                    <td>${group.members}</td>
                   
                </tr>
            `;
        });
    }
};

// زر Create User
document.getElementById('createUserBtn').onclick = async () => {
    const payload = {
        username: document.getElementById('usernameInput').value,
        uid: document.getElementById('uidInput').value,
        gid: document.getElementById('gidInput').value,
        home: document.getElementById('homeInput').value,
        shell: document.getElementById('shellInput').value,
        group: document.getElementById('groupInputUser').value,
        createHome: document.getElementById('createHome').checked
    };
    if (!payload.username) { alert("Username required"); return; }
    const data = await callApi("{{ route('users.groups.createUser') }}","POST",payload);
    showOutput(data.output);
    document.getElementById('listBtn').click();
};

// زر Create Group
document.getElementById('createGroupBtn').onclick = async () => {
    const payload = {
        groupname: document.getElementById('groupInput').value,
        gid: document.getElementById('gidGroupInput').value
    };
    if (!payload.groupname) { alert("Group name required"); return; }
    const data = await callApi("{{ route('users.groups.createGroup') }}","POST",payload);
    showOutput(data.output);
    document.getElementById('listBtn').click();
};

</script>