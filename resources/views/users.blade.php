<x-app-layout>
    @section('content')
        <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">👤 Users Dashboard</h1>

        <!-- Create User -->
        <div class="card mb-6">
            <h2 class="card-title">Create User</h2>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="controls">
                    <input type="text" name="name" class="port-input" placeholder="Name" required>
                    <input type="email" name="email" class="port-input" placeholder="Email" required>
                    <input type="password" name="password" class="port-input" placeholder="Password" required>
                    <select name="role" class="port-input">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="terminal-btn">
                        <i class="fas fa-user-plus"></i> Create
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card mb-6">
            <h2 class="card-title">Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td style="display:flex; gap:8px; justify-content:center;">
                                <!-- Delete -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="terminal-btn">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                <!-- (Optional) Edit -->
                                {{-- <a href="{{ route('users.edit', $user->id) }}" class="terminal-btn"><i class="fas fa-edit"></i> Edit</a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Terminal Output (optional) -->
        @if(session('success') || session('error'))
        <div class="card mb-6">
            <h2 class="card-title">Terminal Output</h2>
            <div id="terminal" class="terminal">
                Ready.
                @if(session('success')){{ "\n" . session('success') }}@endif
                @if(session('error')){{ "\n" . session('error') }}@endif
            </div>
        </div>
        @endif
    @endsection
</x-app-layout>

<style>
/* Card */
.card { background:#f9fafb; border-radius:12px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:20px; }
.card-title { font-size:1.25rem; font-weight:700; color:#1f2937; margin-bottom:16px; border-bottom:2px solid #e5e7eb; padding-bottom:8px; }

/* Controls & Buttons */
.controls { display:flex; gap:16px; flex-wrap:wrap; justify-content:center; margin-bottom:16px; }
.terminal-btn { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; border:none; padding:12px 20px; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow:0 4px 8px rgba(0,0,0,0.15); transition:background 0.3s,transform 0.2s; }
.terminal-btn:hover { background:linear-gradient(135deg,#2563eb,#1d4ed8); transform:translateY(-1px) scale(1.03); }

/* Inputs */
.port-input { background:#fff; color:#111827; border:1px solid #d1d5db; padding:12px 16px; border-radius:8px; font-size:1rem; min-width:200px; transition:all 0.2s ease-in-out; }
.port-input:focus { border-color:#3b82f6; box-shadow:0 0 6px #93c5fd; outline:none; }

/* Table */
table { width:100%; border-collapse:collapse; background:#fff; }
th, td { border:1px solid #d1d5db; padding:14px; text-align:center; vertical-align:middle; }
thead tr { background-color:#e5e7eb; }
tbody tr:nth-child(even) { background-color:#f9fafb; }

/* Terminal */
.terminal { background:#000; color:#0f0; padding:20px; border-radius:12px; font-family:monospace; height:300px; overflow-y:auto; box-shadow:inset 0 0 8px rgba(0,255,0,0.2); white-space:pre-wrap; }
</style>