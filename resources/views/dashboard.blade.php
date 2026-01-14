<x-app-layout>
    @section('content')
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Welcome to SysAdmin Dashboard</h1>

        <!-- Card: Dashboard Buttons -->
        <div class="card mb-6">
            <h2 class="card-title">System Commands</h2>
            <div class="dashboard-buttons">
                <button id="showTopBtn" class="terminal-btn"><i class="fas fa-tachometer-alt"></i> TOP</button>
                <button id="showLsblkBtn" class="terminal-btn"><i class="fas fa-hdd"></i> LSBLK</button>
                <button id="showDfBtn" class="terminal-btn"><i class="fas fa-database"></i> DF -h</button>
                <button id="showFreeBtn" class="terminal-btn"><i class="fas fa-memory"></i> FREE -h</button>
                <button id="showUptimeBtn" class="terminal-btn"><i class="fas fa-clock"></i> UPTIME</button>
                <button id="showMemBtn" class="terminal-btn"><i class="fas fa-tasks"></i> TOP MEM</button>
                <button id="showCpuBtn" class="terminal-btn"><i class="fas fa-microchip"></i> TOP CPU</button>
                <button id="showNetstatBtn" class="terminal-btn"><i class="fas fa-network-wired"></i> NETSTAT/SS</button>
                <button id="showDmesgBtn" class="terminal-btn"><i class="fas fa-bug"></i> DMESG</button>
                <button id="showJournalBtn" class="terminal-btn"><i class="fas fa-book"></i> JOURNALCTL</button>
                <button id="showWhoBtn" class="terminal-btn"><i class="fas fa-users"></i> WHO/W</button>
                <button id="showIpBtn" class="terminal-btn"><i class="fas fa-sitemap"></i> HOSTNAME -I</button>
            </div>
        </div>

        <!-- Card: Description -->
        <div class="card mb-6 hidden" id="descriptionBox">
            <h2 class="card-title">Command Description</h2>
            <p class="text-gray-700 text-center">اضغط على زر لعرض وصف الأمر هنا...</p>
        </div>

        <!-- Card: Terminal -->
        <!-- Card: Terminal -->
        <div class="card mb-6" id="terminalCard">
            <h2 class="card-title">Terminal Output</h2>
            <div id="terminal" class="terminal">Ready...</div>
        </div>  

        <!-- Card: Kill Form -->
        <div class="card hidden" id="killForm">
            <h2 class="card-title">Kill Process</h2>
            <div class="flex items-center justify-center space-x-4 mt-4">
                <input type="number" id="killUid" placeholder="Enter PID"
                       class="w-64 px-4 py-3 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 shadow-sm transition">
                <button id="killBtn"
                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md text-base flex items-center gap-2 transition transform hover:scale-105">
                    <i class="fas fa-skull-crossbones"></i> Kill -9
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
.dashboard-buttons {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    margin: 20px 0;
}
.terminal-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: background 0.3s, transform 0.2s;
}
.terminal-btn:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px) scale(1.05);
}
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
.hidden { display: none; }
</style>

<script>
let intervalId = null;

const descriptions = {
    showTopBtn: "Displays running processes and real-time CPU and memory usage.",
    showLsblkBtn: "Shows information about disks and partitions connected to the system.",
    showDfBtn: "Displays disk usage and available space.",
    showFreeBtn: "Shows memory (RAM) and swap usage in a simple format.",
    showUptimeBtn: "Displays system uptime and average CPU load.",
    showMemBtn: "Lists processes consuming the most memory.",
    showCpuBtn: "Lists processes consuming the most CPU.",
    showNetstatBtn: "Shows open ports and active services (netstat or ss).",
    showDmesgBtn: "Displays the latest kernel messages (useful for debugging).",
    showJournalBtn: "Shows the latest system logs (journalctl).",
    showWhoBtn: "Displays currently logged-in users (who or w).",
    showIpBtn: "Shows the system's IP addresses (hostname -I)."
};

function updateDescription(buttonId) {
    const box = document.getElementById('descriptionBox');
    box.classList.remove('hidden');
    box.querySelector("p").textContent = descriptions[buttonId] || "لا يوجد وصف.";
}

async function fetchCommand(routeName, errorMsg) {
    try {
        const response = await fetch(routeName);
        const data = await response.json();
        const terminal = document.getElementById('terminal');
        terminal.textContent = data.output;
        terminal.scrollTop = terminal.scrollHeight;
    } catch {
        document.getElementById('terminal').textContent = errorMsg;
    }
}

const buttons = [
    {id:'showTopBtn', route:"{{ route('dashboard.top.live') }}", error:"Error fetching TOP...", interval:true, kill:true},
    {id:'showLsblkBtn', route:"{{ route('dashboard.lsblk.live') }}", error:"Error fetching LSBLK..."},
    {id:'showDfBtn', route:"{{ route('dashboard.df.live') }}", error:"Error fetching DF..."},
    {id:'showFreeBtn', route:"{{ route('dashboard.free.live') }}", error:"Error fetching FREE..."},
    {id:'showUptimeBtn', route:"{{ route('dashboard.uptime.live') }}", error:"Error fetching UPTIME..."},
    {id:'showMemBtn', route:"{{ route('dashboard.mem.live') }}", error:"Error fetching MEM..."},
    {id:'showCpuBtn', route:"{{ route('dashboard.cpu.live') }}", error:"Error fetching CPU..."},
    {id:'showNetstatBtn', route:"{{ route('dashboard.netstat.live') }}", error:"Error fetching NETSTAT..."},
    {id:'showDmesgBtn', route:"{{ route('dashboard.dmesg.live') }}", error:"Error fetching DMESG..."},
    {id:'showJournalBtn', route:"{{ route('dashboard.journal.live') }}", error:"Error fetching JOURNAL..."},
    {id:'showWhoBtn', route:"{{ route('dashboard.who.live') }}", error:"Error fetching WHO..."},
    {id:'showIpBtn', route:"{{ route('dashboard.ip.live') }}", error:"Error fetching IP..."},
];

buttons.forEach(btn=>{
    document.getElementById(btn.id).addEventListener('click', ()=>{
        updateDescription(btn.id);
        document.getElementById('terminalCard').classList.remove('hidden');
        const terminal = document.getElementById('terminal');
        terminal.textContent = "Loading...";

        fetchCommand(btn.route, btn.error);

        if(intervalId) clearInterval(intervalId);
        if(btn.interval){
            intervalId = setInterval(()=>fetchCommand(btn.route, btn.error), 5000);
        }

        if(btn.kill){
            document.getElementById('killForm').classList.remove('hidden');
        } else {
            document.getElementById('killForm').classList.add('hidden');
        }
    });
});

// تنفيذ أمر Kill
document.getElementById('killBtn').addEventListener('click', () => {
    const uid = document.getElementById('killUid').value;
    if (!uid) {
        alert("Please enter a PID");
        return;
    }
    fetch("{{ route('dashboard.kill') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ uid: uid })
    })
    .then(res => res.json())
    .then(data => { alert(data.message); })
    .catch(() => { alert("Error executing kill command"); });
});
</script>