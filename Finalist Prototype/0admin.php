<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Control - Bat Cave Cafe</title>
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Inter:wght@300;400;600&family=Playball&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'coffee-dark': 'var(--bg-primary)',
                        'coffee-panel': 'var(--bg-panel)',
                        'coffee-accent': 'var(--text-accent)',
                        'coffee-text': 'var(--text-main)',
                        'coffee-muted': 'var(--text-muted)',
                        'coffee-border': 'var(--border-color)',
                        'leather': '#4A332A',
                        'paper': '#F5E6D3',
                        'paper-dark': '#E6D2B5',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Cinzel', 'serif'],
                        script: ['Playball', 'cursive'],
                    },
                    backgroundImage: {
                        'leather-texture': "url('https://www.transparenttextures.com/patterns/black-scales.png')",
                        'paper-texture': "url('https://www.transparenttextures.com/patterns/cream-paper.png')",
                        'spine-texture': "url('https://www.transparenttextures.com/patterns/dark-leather.png')",
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.2s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: { '0%': { opacity: '0', transform: 'translateY(10px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-primary: #1C1613;
            --bg-panel: #2A221E;
            --text-accent: #C59D60;
            --text-main: #D6C0A0;
            --text-muted: #9CA3AF;
            --border-color: #382D24;
        }
        .light-mode {
            --bg-primary: #F3F4F6;
            --bg-panel: #FFFFFF;
            --text-accent: #B45309;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
        }
        body { background-color: var(--bg-primary); color: var(--text-main); transition: background-color 0.3s, color 0.3s; }
        
        /* Sidebar & Navigation */
        .sidebar-link { transition: all 0.3s; border-left: 3px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active { background-color: var(--bg-panel); border-left-color: var(--text-accent); color: var(--text-accent); }
        
        /* Common UI Elements */
        .card { background-color: var(--bg-panel); border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .input-field { background-color: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-main); }
        .input-field:focus { outline: none; border-color: var(--text-accent); }
        
        /* Badges */
        .badge-pending { background-color: #F59E0B; color: #000; }
        .badge-approved { background-color: #10B981; color: #000; }
        .badge-rejected { background-color: #EF4444; color: #fff; }
        .badge-done { background-color: #6B7280; color: #fff; }
        .badge-archived { background-color: #374151; color: #9CA3AF; border: 1px solid #4B5563; }
        .status-badge { cursor: pointer; transition: all 0.2s; user-select: none; }
        .status-badge:hover { transform: scale(1.05); opacity: 0.9; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--text-accent); border-radius: 3px; }
        
        #login-overlay { z-index: 9999; background-color: var(--bg-primary); transition: opacity 0.5s ease; }
        #toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 9000; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
        #toast-container > div { pointer-events: auto; }
        
        /* Calendar Specifics */
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background-color: var(--border-color); border: 1px solid var(--border-color); }
        .calendar-day { background-color: var(--bg-panel); min-height: 100px; padding: 8px; position: relative; transition: background-color 0.2s; }
        .calendar-day:hover { background-color: var(--bg-primary); }

        /* --- 3D BOOK STYLES --- */
        .book-perspective { 
            perspective: 2500px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 700px; 
            width: 100%; 
            padding: 20px 0; 
            position: relative; 
        }
        .book { 
            position: relative; 
            width: 90%; max-width: 900px; 
            height: 100%; max-height: 600px; 
            transform-style: preserve-3d; 
            transition: transform 1.0s cubic-bezier(0.2, 0.1, 0.3, 1);
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));
            transform: translateX(0);
        }
        
        .book-spine { 
            position: absolute; left: 50%; top: 2px; bottom: 2px;
            width: 40px; 
            transform: translateX(-50%) translateZ(-2px) rotateY(0deg); 
            background-color: #4A332A; 
            background-image: var(--tw-backgroundImage-spine-texture); 
            border-radius: 4px; 
            z-index: 0; 
        }
        
        .paper { 
            position: absolute; width: 50%; height: 100%; 
            top: 0; left: 50%; 
            transform-origin: left center; 
            transform-style: preserve-3d; 
            transition: transform 1.4s cubic-bezier(0.15, 0.25, 0.25, 1); 
            z-index: 1; 
        }
        .paper.flipped { transform: rotateY(-180deg); z-index: 50 !important; }
        
        .front, .back { 
            position: absolute; width: 100%; height: 100%; 
            top: 0; left: 0; backface-visibility: hidden; 
            box-sizing: border-box; overflow-y: auto; overflow-x: hidden; 
            background-color: #F5E6D3; 
            background-image: var(--tw-backgroundImage-paper-texture);
            color: #1f2937; border: 1px solid #D7C4A5;
            box-shadow: inset 3px 0px 5px -2px rgba(0,0,0,0.1);
        }
        .front { z-index: 2; transform: rotateY(0deg); border-radius: 0 5px 5px 0; }
        .back { z-index: 1; transform: rotateY(180deg); border-radius: 5px 0 0 5px; box-shadow: inset -3px 0px 5px -2px rgba(0,0,0,0.1); }
        
        .paper.flipped .front { visibility: hidden; transition: visibility 0s linear 0.7s; } 
        .paper:not(.flipped) .back { visibility: hidden; transition: visibility 0s linear 0.7s; }

        .cover-front { background: linear-gradient(to right, #83181b, #A41E22); border: none !important; border-left: 10px solid #83181b !important; color: #FFFFFF; box-shadow: inset -4px 0 8px rgba(0,0,0,0.2), 0 0 0 2px #C00000, 0 0 0 4px #83181b !important; }
        .back-cover-texture { background-color: #A41E22; border: none !important; border-right: 10px solid #83181b !important; box-shadow: inset 2px 0 5px rgba(0,0,0,0.1) !important; color: #FFFFFF; }

        .nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(28, 22, 19, 0.9); color: #C59D60; border: 1px solid #C59D60; width: 3.5rem; height: 3.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 100; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .nav-btn:hover { background: #C59D60; color: #1C1613; box-shadow: 0 0 20px #C59D60; }
        #book-prev-btn { left: 5%; } #book-next-btn { right: 5%; }
        
        .menu-item { border-bottom: 1px dashed #d1ccc0; padding: 8px 0; transition: background-color 0.2s; cursor: pointer; }
        .menu-item:last-child { border-bottom: none; }
        .magic-text { background: linear-gradient(45deg, #C59D60, #FFE5B4, #C59D60); -webkit-background-clip: text; background-clip: text; color: transparent; text-shadow: 0 0 20px rgba(197, 157, 96, 0.3); animation: shimmer 3s infinite; }
    </style>
</head>
<body class="font-sans antialiased h-screen flex overflow-hidden">

    <div id="toast-container"></div>

    <div id="login-overlay" class="fixed inset-0 flex items-center justify-center flex-col space-y-6">
        <div class="text-4xl font-serif font-bold text-coffee-accent animate-pulse">BATCOMPUTER ACCESS</div>
        <div class="bg-coffee-panel p-8 rounded-xl shadow-2xl border border-coffee-border w-96">
            <label class="block text-xs font-bold uppercase tracking-widest text-coffee-muted mb-2">Enter Passcode</label>
            <input type="password" id="admin-pass" class="w-full input-field rounded p-3 text-center tracking-[0.5em] mb-4" placeholder="******">
            <button id="auth-btn" type="button" class="w-full bg-coffee-accent text-white font-bold py-3 rounded hover:bg-opacity-80 transition cursor-pointer">AUTHENTICATE</button>
            <p id="login-msg" class="text-red-500 text-xs text-center mt-3 hidden font-bold">ACCESS DENIED. INVALID PASSCODE.</p>
        </div>
    </div>

    <div id="status-context-menu" class="fixed hidden bg-coffee-panel border border-coffee-border rounded-lg shadow-2xl z-[999] w-48 overflow-hidden animate-fade-in-up">
        <div class="px-4 py-2 text-[10px] font-bold text-coffee-muted uppercase bg-coffee-dark/50">Update Status</div>
        <button class="w-full text-left px-4 py-3 hover:bg-coffee-dark text-xs font-bold text-yellow-500 transition flex items-center gap-2 border-b border-coffee-border/10" data-val="pending" data-class="badge-pending" data-text="PENDING"><span class="w-2 h-2 rounded-full bg-yellow-500"></span>PENDING</button>
        <button class="w-full text-left px-4 py-3 hover:bg-coffee-dark text-xs font-bold text-green-500 transition flex items-center gap-2 border-b border-coffee-border/10" data-val="approved" data-class="badge-approved" data-text="CONFIRMED"><span class="w-2 h-2 rounded-full bg-green-500"></span>CONFIRMED</button>
        <button class="w-full text-left px-4 py-3 hover:bg-coffee-dark text-xs font-bold text-red-500 transition flex items-center gap-2 border-b border-coffee-border/10" data-val="rejected" data-class="badge-rejected" data-text="DECLINED"><span class="w-2 h-2 rounded-full bg-red-500"></span>DECLINED</button>
        <div class="order-only hidden">
            <button class="w-full text-left px-4 py-3 hover:bg-coffee-dark text-xs font-bold text-blue-400 transition flex items-center gap-2 border-b border-coffee-border/10" data-val="preparing" data-class="bg-blue-500 text-white" data-text="PREPARING"><span class="w-2 h-2 rounded-full bg-blue-400"></span>PREPARING</button>
            <button class="w-full text-left px-4 py-3 hover:bg-coffee-dark text-xs font-bold text-teal-400 transition flex items-center gap-2 border-b border-coffee-border/10" data-val="completed" data-class="bg-teal-500 text-white" data-text="COMPLETED"><span class="w-2 h-2 rounded-full bg-teal-400"></span>COMPLETED</button>
        </div>
        <button class="w-full text-left px-4 py-3 hover:bg-coffee-dark text-xs font-bold text-gray-400 transition flex items-center gap-2" data-val="archived" data-class="badge-archived" data-text="ARCHIVED"><span class="w-2 h-2 rounded-full bg-gray-500"></span>ARCHIVE</button>
        <div class="border-t border-coffee-border/30 my-1"></div>
        <button class="w-full text-left px-4 py-3 hover:bg-red-900/20 text-xs font-bold text-red-600 transition flex items-center gap-2" id="btn-delete-record">
            <i class="ph ph-trash text-lg"></i> DELETE RECORD
        </button>
    </div>

    <div id="day-details-modal" class="fixed inset-0 hidden flex items-center justify-center z-50 bg-black/50 backdrop-blur-sm">
        <div class="bg-coffee-panel border border-coffee-accent p-6 rounded-xl w-full max-w-md shadow-2xl animate-fade-in-up">
            <div class="flex justify-between items-center mb-4 border-b border-coffee-border pb-2">
                <h3 class="text-xl font-serif font-bold text-coffee-text" id="day-modal-title">Date</h3>
                <button id="close-day-modal-btn" class="text-coffee-muted hover:text-coffee-accent"><i class="ph ph-x text-xl"></i></button>
            </div>
            <div id="day-modal-content" class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar"></div>
        </div>
    </div>

    <div id="announcement-modal" class="fixed inset-0 hidden flex items-center justify-center z-[100] bg-black/80 backdrop-blur-sm">
        <div class="bg-coffee-panel border border-coffee-accent p-6 rounded-xl w-full max-w-md shadow-2xl animate-fade-in-up">
            <h3 class="text-xl font-serif font-bold text-coffee-accent mb-4">New Mission Update</h3>
            <form id="announcement-form" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-coffee-muted uppercase">Headline</label>
                    <input type="text" name="title" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none" placeholder="e.g. System Update" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-coffee-muted uppercase">Type</label>
                    <select name="type" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none">
                        <option value="general">General Intel</option>
                        <option value="wifi">System/Wi-Fi</option>
                        <option value="promo">Special Offer</option>
                        <option value="alert">High Alert</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-coffee-muted uppercase">Details</label>
                    <textarea name="content" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none h-24" placeholder="Enter message..." required></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-4 py-2 text-coffee-muted hover:text-white text-sm" onclick="document.getElementById('announcement-modal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-coffee-accent text-coffee-dark font-bold rounded hover:bg-white transition text-sm">Post Update</button>
                </div>
            </form>
        </div>
    </div>

    <aside class="w-64 bg-coffee-dark border-r border-coffee-border flex flex-col absolute md:relative h-full z-40 sidebar-closed md:transform-none" id="main-sidebar">
        <div class="h-20 flex items-center justify-center border-b border-coffee-border relative">
            <span class="text-xl font-serif font-bold text-coffee-accent">MISSION CONTROL</span>
            <button id="close-sidebar-btn" class="absolute right-4 top-1/2 -translate-y-1/2 md:hidden text-coffee-muted"><i class="ph ph-x text-xl"></i></button>
        </div>
        <nav class="flex-1 py-6 space-y-1">
            <button data-tab="dashboard" class="sidebar-link active w-full text-left px-6 py-4 flex items-center gap-3"><i class="ph ph-chart-bar text-xl"></i> Dashboard</button>
            <button data-tab="reservations" class="sidebar-link w-full text-left px-6 py-4 flex items-center gap-3"><i class="ph ph-calendar text-xl"></i> Reservations <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full" id="sidebar-pending-count">0</span></button>
            <button data-tab="orders" class="sidebar-link w-full text-left px-6 py-4 flex items-center gap-3"><i class="ph ph-shopping-cart text-xl"></i> Orders</button>
            <button data-tab="announcements" class="sidebar-link w-full text-left px-6 py-4 flex items-center gap-3"><i class="ph ph-megaphone text-xl"></i> Announcement</button>
            <button data-tab="customize" class="sidebar-link w-full text-left px-6 py-4 flex items-center gap-3"><i class="ph ph-book-open text-xl"></i> Menu Manager</button>
            <button id="nav-settings-btn" data-tab="settings" class="sidebar-link w-full text-left px-6 py-4 flex items-center gap-3"><i class="ph ph-gear text-xl"></i> Settings</button>
        </nav>
        <div class="p-4 border-t border-coffee-border space-y-2">
            <button id="logout-btn" class="flex items-center gap-2 text-sm text-coffee-muted hover:text-coffee-accent transition w-full text-left px-2 py-2 rounded hover:bg-coffee-panel"><i class="ph ph-sign-out text-lg"></i> Logout</button>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-coffee-dark p-8 hidden" id="main-content">
        
        <header class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <button id="mobile-menu-btn" class="md:hidden text-coffee-text text-2xl"><i class="ph ph-list"></i></button>
                <div>
                    <h1 class="text-3xl font-serif font-bold text-coffee-text" id="page-title">Dashboard</h1>
                    <p class="text-sm text-coffee-muted" id="welcome-message">Welcome back, Master Wayne.</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <div class="text-[10px] text-coffee-muted font-bold uppercase tracking-wider mb-0.5">PHT (GMT+8)</div>
                    <div class="text-xl font-mono text-coffee-text" id="live-clock">--:--:--</div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-coffee-accent font-bold uppercase tracking-wider mb-0.5">SYSTEM STATUS</div>
                    <div class="text-sm font-bold text-green-500">ONLINE</div>
                </div>
            </div>
        </header>

        <div id="view-dashboard" class="space-y-8">
            <div>
                <h3 class="text-xl font-serif font-bold text-coffee-text mb-4 flex items-center gap-2"><i class="ph ph-chart-line-up text-coffee-accent"></i> Financial Performance</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card p-6 rounded-xl border-l-4 border-l-coffee-accent relative overflow-hidden group">
                        <div class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-coffee-accent/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="flex justify-between items-start relative z-10">
                            <div><div class="text-coffee-muted text-xs uppercase tracking-wider font-bold">Today's Revenue</div><div class="text-4xl font-bold text-coffee-accent mt-2" id="stat-revenue-today">₱0.00</div></div>
                            <div class="p-3 bg-coffee-dark rounded-lg text-coffee-accent border border-coffee-border"><i class="ph ph-coins text-2xl"></i></div>
                        </div>
                        <div class="mt-2 text-xs text-coffee-muted">Confirmed orders only.</div>
                    </div>
                    <div class="card p-6 rounded-xl border-l-4 border-l-green-600 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-green-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="flex justify-between items-start relative z-10">
                            <div><div class="text-coffee-muted text-xs uppercase tracking-wider font-bold">Monthly Revenue</div><div class="text-4xl font-bold text-white mt-2" id="stat-revenue-month">₱0.00</div></div>
                            <div class="p-3 bg-coffee-dark rounded-lg text-green-500 border border-coffee-border"><i class="ph ph-calendar-check text-2xl"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card p-6 rounded-xl mt-6 border border-coffee-border">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-coffee-text" id="chart-title">Weekly Revenue Trend</h4>
                    <select id="revenue-timeframe" class="bg-coffee-dark border border-coffee-border text-xs text-coffee-muted rounded px-2 py-1 focus:outline-none focus:border-coffee-accent">
                        <option value="week">Last 7 Days</option>
                        <option value="month">Last 6 Months</option>
                    </select>
                </div>
                <div class="h-64 w-full"><canvas id="revenueChart"></canvas></div>
            </div>

            <div>
                <h3 class="text-xl font-serif font-bold text-coffee-text mb-4 flex items-center gap-2"><i class="ph ph-activity text-blue-400"></i> Live Operations</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="card p-6 rounded-xl hover:border-yellow-500 transition border border-coffee-border">
                        <div class="text-coffee-muted text-xs uppercase tracking-wider font-bold">Pending Reservations</div>
                        <div class="text-3xl font-bold text-coffee-text mt-2" id="stat-pending-count">0</div>
                        <div class="text-xs text-yellow-500 mt-1 font-bold">Requires Action</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="view-reservations" class="hidden space-y-6">
            <div class="bg-coffee-panel border border-coffee-accent/30 p-4 rounded-lg text-sm text-coffee-muted flex items-start gap-3 mb-6 shadow-lg">
                <i class="ph ph-info text-xl text-adaptive-blue mt-0.5"></i>
                <div class="w-full">
                    <div class="flex justify-between items-center"><strong class="text-coffee-text">Protocol:</strong></div>
                    <ul class="list-disc list-inside mt-2 space-y-1 text-xs text-coffee-muted">
                        <li><span class="text-adaptive-blue font-bold">Study First:</span> Day is EXCLUSIVE.</li>
                        <li><span class="text-adaptive-purple font-bold">Gathering First:</span> Day is OPEN.</li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between gap-4 mb-4 items-end md:items-center">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-xs text-coffee-muted uppercase mr-2 font-bold">View:</span>
                    <button class="view-switch-btn active bg-coffee-accent text-white px-3 py-1 rounded-full text-xs font-bold" data-view="list"><i class="ph ph-list"></i> List</button>
                    <button class="view-switch-btn bg-coffee-panel text-coffee-muted border border-coffee-border px-3 py-1 rounded-full text-xs font-bold hover:text-white" data-view="calendar"><i class="ph ph-calendar"></i> Calendar</button>
                </div>
                <div class="relative w-full md:w-auto" id="reservation-search-wrapper">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-coffee-muted"></i>
                    <input type="text" id="search-reservations" placeholder="Search by guest name..." class="input-field rounded-full pl-10 pr-4 py-2 text-sm w-full md:w-64 border border-coffee-border focus:border-coffee-accent">
                </div>
            </div>

            <div class="mb-6"></div>

            <div id="reservations-list-view" class="card rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-coffee-panel text-coffee-muted text-xs uppercase border-b border-coffee-border">
                                <th class="p-4">Codename</th>
                                <th class="p-4">Date & Mode</th>
                                <th class="p-4">Reserved On</th>
                                <th class="p-4 text-center">Guests</th>
                                <th class="p-4">Equipment & Orders</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Payment Info</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-coffee-border text-sm" id="reservation-table-body">
                            </tbody>
                    </table>
                </div>
            </div>

            <div id="reservations-calendar-view" class="hidden">
                <div class="card rounded-xl overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-xl text-coffee-text font-serif" id="calendar-month-title">November 2024</h3>
                        <div class="flex gap-2">
                            <button id="cal-prev-btn" class="bg-coffee-dark text-coffee-muted hover:text-white p-2 rounded transition hover:bg-coffee-accent"><i class="ph ph-caret-left"></i></button>
                            <button id="cal-next-btn" class="bg-coffee-dark text-coffee-muted hover:text-white p-2 rounded transition hover:bg-coffee-accent"><i class="ph ph-caret-right"></i></button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-1 mb-1">
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Sun</div>
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Mon</div>
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Tue</div>
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Wed</div>
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Thu</div>
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Fri</div>
                        <div class="text-coffee-muted text-xs font-bold uppercase text-center py-2">Sat</div>
                    </div>
                    <div class="calendar-grid rounded-lg overflow-hidden bg-coffee-border border border-coffee-border" id="calendar-grid-container"></div>
                </div>
            </div>
        </div>

        <div id="view-orders" class="hidden space-y-6">
            <div class="card rounded-xl overflow-hidden">
                <div class="p-6 border-b border-coffee-border">
                    <h3 class="font-bold text-coffee-text">Walk-in & Online Orders</h3>
                    <p class="text-xs text-coffee-muted">Orders placed directly through the menu page.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="walkin-orders-table">
                        <thead>
                            <tr class="bg-coffee-panel text-coffee-muted text-xs uppercase border-b border-coffee-border">
                                <th class="p-4">Customer</th>
                                <th class="p-4">Reservation Date</th>
                                <th class="p-4">Ordered Items</th>
                                <th class="p-4">Payment</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-coffee-border text-sm" id="walkin-orders-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="view-announcements" class="hidden space-y-6">
             <div class="card rounded-xl overflow-hidden">
                <div class="p-6 border-b border-coffee-border flex justify-between items-center">
                    <h3 class="font-bold text-coffee-text">Manage Announcements</h3>
                    <button id="create-announcement-btn" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded transition text-sm cursor-pointer" onclick="openAnnouncementModal()">
                        <i class="ph ph-plus"></i> Create New
                    </button>
                </div>
                <div class="p-6 space-y-4" id="admin-announcements-list">
                    <div class="text-center text-coffee-muted text-xs">Loading announcements...</div>
                </div>
            </div>
        </div>

        <div id="view-customize" class="hidden space-y-6">
            <div class="flex justify-between items-center bg-coffee-panel p-4 rounded-xl border border-coffee-border mb-6">
                <div>
                    <h2 class="text-xl font-bold text-coffee-text">Menu Manager</h2>
                    <p class="text-xs text-coffee-muted">Grimoire Visualization & Editing</p>
                </div>
                <div class="flex gap-3">
                    <div class="bg-coffee-dark p-1 rounded-lg border border-coffee-border flex">
                        <button id="btn-view-customer" class="px-4 py-2 rounded text-xs font-bold bg-coffee-accent text-coffee-dark transition shadow-lg" onclick="setMenuMode('view')">
                            <i class="ph ph-eye"></i> Customer View
                        </button>
                        <button id="btn-view-edit" class="px-4 py-2 rounded text-xs font-bold text-coffee-muted hover:text-white transition" onclick="setMenuMode('edit')">
                            <i class="ph ph-pencil-simple"></i> Edit Mode
                        </button>
                    </div>
                    <button id="btn-add-item" class="hidden bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded text-xs font-bold transition items-center gap-2" onclick="openItemModal()">
                        <i class="ph ph-plus"></i> Add Product
                    </button>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8">
                <div class="lg:col-span-9">
                    <div class="book-perspective pb-10" id="visual-menu-wrapper">
                        <div class="book" id="book-container"></div>
                        <button id="book-prev-btn" class="nav-btn left-4 absolute top-1/2 -translate-y-1/2 hidden" onclick="flipBackward()"><i class="ph ph-caret-left"></i></button>
                        <button id="book-next-btn" class="nav-btn right-4 absolute top-1/2 -translate-y-1/2" onclick="flipForward()"><i class="ph ph-caret-right"></i></button>
                    </div>
                </div>
                <div class="lg:col-span-3">
                    <div class="card p-4 rounded-xl">
                        <h4 class="font-bold text-coffee-accent text-sm border-b border-coffee-border pb-2 mb-3 flex items-center gap-2"><i class="ph ph-fire"></i> Bestsellers</h4>
                        <div id="popular-items-list" class="space-y-2 max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                            <p class="text-xs text-coffee-muted text-center py-4">No order data available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="view-settings" class="hidden max-w-2xl space-y-6">
            <div class="card p-8 rounded-xl">
                <h3 class="text-xl font-serif font-bold text-coffee-text mb-4">Interface Theme</h3>
                <div class="flex items-center justify-between">
                    <div><div class="font-bold text-coffee-text">Bat Computer Mode</div><div class="text-xs text-coffee-muted">Standard dark interface.</div></div>
                    <button id="theme-toggle" class="bg-coffee-primary border border-coffee-border p-2 rounded-lg text-coffee-accent hover:bg-coffee-border transition"><i class="ph ph-moon text-2xl" id="theme-icon"></i></button>
                </div>
            </div>
            <div class="card p-8 rounded-xl space-y-4">
                <h3 class="text-xl font-serif font-bold text-coffee-text mb-4">Change Password</h3>
                <div>
                    <label class="block text-xs font-bold uppercase text-coffee-muted mb-1">Current Passcode</label>
                    <input type="password" id="current-pass-input" class="w-full input-field rounded p-3" placeholder="Enter current passcode">
                </div>
                <div><label class="block text-xs font-bold uppercase text-coffee-muted mb-1">New Passcode</label>
                    <input type="password" id="new-pass-input" class="w-full input-field rounded p-3" placeholder="Enter new passcode"></div>
                <div class="flex justify-end"><button id="update-pass-btn" class="bg-coffee-accent text-white font-bold py-2 px-6 rounded text-sm hover:bg-opacity-80">Update Passcode</button></div>
            </div>
        </div>

    </main>

    <div id="menu-item-modal" class="fixed inset-0 hidden flex items-center justify-center z-[100] bg-black/80 backdrop-blur-sm">
        <div class="bg-coffee-panel border border-coffee-accent p-6 rounded-xl w-full max-w-lg shadow-2xl animate-fade-in-up">
            <h3 class="text-xl font-serif font-bold text-coffee-accent mb-4" id="modal-title">Add New Item</h3>
            <form id="menu-item-form" class="space-y-4" enctype="multipart/form-data">
                <input type="hidden" id="edit-id" name="id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-coffee-muted uppercase">Name</label>
                        <input type="text" name="name" id="edit-name" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-coffee-muted uppercase">Category</label>
                        <select name="category" id="edit-category" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none" onchange="toggleSizeField()">
                            <option value="uniforms">Uniforms</option>
                            <option value="accessories">Accessories</option>
                            <option value="school_supplies">School Supplies</option>
                            <option value="merchandise">Merchandise</option>
                        </select>
                    </div>
                    <div id="size-input-container" class="hidden">
                        <label class="text-xs font-bold text-coffee-muted uppercase">Sizes (comma-separated)</label>
                        <input type="text" name="sizes" id="edit-sizes" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none" placeholder="e.g. S, M, L, XL">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-coffee-muted uppercase">Solo Price</label>
                        <input type="number" name="price_solo" id="edit-price-solo" step="0.01" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-coffee-muted uppercase">Group Price (Optional)</label>
                        <input type="number" name="price_group" id="edit-price-group" step="0.01" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-coffee-muted uppercase">Product Image</label>
                    <div class="flex items-center gap-4 mt-1">
                        <div id="image-preview-container" class="w-16 h-16 bg-coffee-dark border border-coffee-border rounded flex items-center justify-center overflow-hidden hidden relative group">
                            <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <label class="cursor-pointer bg-coffee-dark hover:bg-coffee-border text-white px-3 py-2 rounded border border-coffee-border text-xs font-bold transition flex items-center gap-2">
                            <i class="ph ph-upload-simple"></i> Choose Photo
                            <input type="file" name="image" id="edit-image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </label>
                        <button type="button" id="remove-image-btn" class="hidden text-red-500 hover:text-red-400 text-xs font-bold" onclick="removeImage()">Remove</button>
                        <input type="hidden" name="existing_image" id="edit-existing-image">
                        <span id="file-name-display" class="text-xs text-coffee-muted italic truncate max-w-[150px]">No file chosen</span>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-coffee-muted uppercase">Description</label>
                    <textarea name="description" id="edit-desc" class="w-full bg-coffee-dark border border-coffee-border p-2 rounded text-white focus:border-coffee-accent outline-none h-20"></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-4 py-2 text-coffee-muted hover:text-white text-sm" onclick="document.getElementById('menu-item-modal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-coffee-accent text-coffee-dark font-bold rounded hover:bg-white transition text-sm">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-red-600' : 'bg-coffee-accent';
            const textColor = type === 'error' ? 'text-white' : 'text-coffee-dark';
            toast.className = `${bgColor} ${textColor} px-4 py-3 rounded-lg shadow-2xl flex items-center gap-3 min-w-[250px] animate-slideIn border border-white/10 pointer-events-auto`;
            toast.innerHTML = `<i class="ph ph-check-circle text-xl"></i><span class="font-bold text-sm">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => { toast.remove(); }, 3000);
        }

        let dbMenuItems = []; 
        let isEditMode = false;
        let bookSheetsData = []; 

        const categoryMap = {
            'Page 1: Uniforms': ['uniforms'],
            'Page 2: Accessories': ['accessories'],
            'Page 3: School Supplies': ['school_supplies'],
            'Page 4: Merchandise': ['merchandise']
        };

        // HELPER TO PARSE SPECIAL REQUEST ORDERS
        function parseOrders(requestString) {
            if (!requestString) return { note: '', orders: [] };
            
            // Check for the "[PRE-ORDER]:" tag
            const parts = requestString.split('[PRE-ORDER]:');
            const note = parts[0].trim();
            let orders = [];
            
            if (parts.length > 1) {
                // The order string is usually formatted as: " 1x Burger, 2x Fries"
                const orderStr = parts[1].trim();
                if(orderStr) {
                    orders = orderStr.split(',').map(item => item.trim()).filter(i => i);
                }
            }
            return { note, orders };
        }

        document.addEventListener('DOMContentLoaded', () => {
            const authBtn = document.getElementById('auth-btn');
            const passInput = document.getElementById('admin-pass');
            const logoutBtn = document.getElementById('logout-btn');
            
            const PASSWORD_KEY = 'batcave_admin_pass';
            let ownerPassword = localStorage.getItem(PASSWORD_KEY) || 'alfred';

            const performLogin = () => {
                const pass = passInput.value;
                if (pass === ownerPassword) {
                    document.getElementById('login-overlay').style.display = 'none';
                    document.getElementById('main-sidebar').classList.remove('hidden');
                    document.getElementById('main-content').classList.remove('hidden');
                    showToast('Access Granted', 'success');
                    loadDashboardData();
                    loadMenuData(); 
                    loadAdminAnnouncements(); // Load Announcements
                } else {
                    document.getElementById('login-msg').classList.remove('hidden');
                }
            };

            if(logoutBtn) logoutBtn.addEventListener('click', () => location.reload());
            if(authBtn) authBtn.addEventListener('click', performLogin);
            if(passInput) passInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') performLogin(); });

            function formatTime(hour) {
                const h = parseInt(hour);
                const ampm = h >= 12 ? 'PM' : 'AM';
                const formattedH = h % 12 || 12;
                return `${formattedH}:00 ${ampm}`;
            }

            function formatMoney(amount) {
                return '₱' + parseFloat(amount).toLocaleString('en-US', {minimumFractionDigits: 2});
            }

            function getBadgeClass(status) {
                switch(status.toLowerCase()) {
                    case 'pending': return 'badge-pending';
                    case 'approved': case 'confirmed': return 'badge-approved';
                    case 'done': return 'badge-done';
                    case 'archived': return 'badge-archived';
                    // Order specific statuses
                    case 'preparing': return 'bg-blue-500 text-white';
                    case 'completed': return 'bg-teal-500 text-white';
                    case 'cancelled': return 'badge-rejected';
                    case 'rejected': return 'badge-rejected';
                    default: return 'bg-gray-500 text-white';
                }
            }

            function loadDashboardData() {
                fetch('admin_api.php')
                    .then(response => response.json())
                    .then(data => { // `data` now contains `reservations` and `walkin_orders`
                        // renderOrders(data.reservations); // This is now replaced
                        renderReservations(data.reservations);
                        renderCalendar(data.reservations);
                        updateStats(data.stats);
                        renderPopularItems(data.stats.popular_items);
                        renderWalkinOrders(data.walkin_orders); // New function call
                initializeCharts(data.stats); // New function to handle charts
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function updateStats(stats) {
                if(stats) {
                    document.getElementById('stat-revenue-today').innerText = formatMoney(stats.revenue_today);
                    document.getElementById('stat-revenue-month').innerText = formatMoney(stats.revenue_month);
                    document.getElementById('stat-pending-count').innerText = stats.pending;
                    document.getElementById('sidebar-pending-count').innerText = stats.pending;
                }
            }

            function renderPopularItems(items) {
                const container = document.getElementById('popular-items-list');
                container.innerHTML = '';
                if (Object.keys(items).length === 0) {
                    container.innerHTML = '<p class="text-xs text-coffee-muted text-center py-4">No order data available.</p>';
                    return;
                }

                Object.entries(items).forEach(([name, count]) => {
                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex justify-between items-center bg-coffee-dark p-2 rounded-md border border-coffee-border text-xs';
                    itemEl.innerHTML = `
                        <span class="font-bold text-coffee-text">${name}</span>
                        <span class="bg-coffee-accent text-coffee-dark font-bold text-[10px] px-2 py-0.5 rounded-full">${count} sold</span>
                    `;
                    container.appendChild(itemEl);
                });
            }

            function renderWalkinOrders(orders) {
                const tbody = document.getElementById('walkin-orders-table-body');
                tbody.innerHTML = ''; // Clear existing content to prevent duplication

                if (!orders || orders.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-coffee-muted text-sm">No walk-in orders found.</td></tr>`;
                    return;
                }

                // Sort by creation date, newest first
                orders.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                orders.forEach(order => {
                    const orderDetails = JSON.parse(order.order_items || '[]');
                    const itemsHtml = orderDetails.map(item => {
                        let name = item.name;
                        if (item.size) {
                            name += ` (${item.size})`;
                        }
                        return `<li>${item.qty}x ${name}</li>`;
                    }).join('');

                    const paymentProof = order.proof_path ? `<a href="${order.proof_path}" target="_blank" class="text-blue-400 text-xs underline hover:text-blue-300">View Proof</a>` : 'N/A';

                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-coffee-panel/50 transition';
                    tr.dataset.id = order.id;
                    tr.dataset.status = order.order_status.toLowerCase();
                    tr.dataset.source = 'product_orders'; // Identify the table

                    tr.innerHTML = `
                        <td class="p-4 font-bold text-coffee-text">${order.customer_name}<br><span class="text-xs font-normal text-coffee-muted">${order.customer_email}</span></td>
                        <td class="p-4 text-xs">${new Date(order.created_at).toLocaleString()}</td>
                        <td class="p-4 text-xs">
                            <ul class="list-disc list-inside space-y-1">${itemsHtml}</ul>
                        </td>
                        <td class="p-4 text-xs">
                            <div class="font-bold text-coffee-accent">${formatMoney(order.total_price)}</div>
                            <div class="text-coffee-muted uppercase text-[10px]">${order.payment_method}</div>
                            <div class="mt-1">${paymentProof}</div>
                        </td>
                        <td class="p-4"><span class="px-2 py-1 rounded-full text-xs font-bold status-badge ${getBadgeClass(order.order_status)}" onclick="openStatusMenu(this, ${order.id})">${order.order_status.toUpperCase()}</span></td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            function renderReservations(reservations) {
                const tbody = document.getElementById('reservation-table-body');
                tbody.innerHTML = '';

                // Sort by creation date, newest first
                reservations.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                reservations.forEach(res => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-coffee-panel/50 transition reservation-row';
                    tr.dataset.id = res.id;
                    tr.dataset.status = res.status.toLowerCase();
                    tr.dataset.source = res.source_table; // Store which table it came from
                    tr.dataset.date = res.mission_date;
                    
                    // Store full reservation object for later use (e.g., toast notifications)
                    tr.dataset.reservation = JSON.stringify(res);


                    const modeColor = res.reservation_type === 'study' ? 'blue' : 'purple';
                    const modeLabel = res.reservation_type === 'study' ? 'Study Mission' : 'Social Gathering';
                    
                    let methodKey = 'cash';
                    if (res.payment_method.includes('gcash')) methodKey = 'gcash';
                    else if (res.payment_method.includes('maya')) methodKey = 'maya';
                    else if (res.payment_method.includes('bank')) methodKey = 'bank';

                    const methodIcons = {
                        'gcash': { icon: 'ph-device-mobile', color: 'text-blue-400', label: 'GCash' },
                        'maya': { icon: 'ph-wallet', color: 'text-green-400', label: 'Maya' },
                        'bank': { icon: 'ph-bank', color: 'text-yellow-400', label: 'Bank' },
                        'cash': { icon: 'ph-money', color: 'text-green-500', label: 'Cash' }
                    };
                    const pDetails = methodIcons[methodKey];

                    const balance = parseFloat(res.total_bill) - parseFloat(res.payable_amount);
                    let balanceHtml = '';
                    if (balance > 1) {
                        balanceHtml = `<div class="text-[10px] text-red-400 font-bold bg-red-400/10 px-1 rounded mt-1 border border-red-400/30">Collect: ${formatMoney(balance)}</div>`;
                    } else {
                        balanceHtml = `<div class="text-[10px] text-green-500 font-bold mt-1">Fully Paid</div>`;
                    }

                    let proofsHtml = '';
                    if (res.proof_image) proofsHtml += `<a href="uploads/${res.proof_image}" target="_blank" class="block text-[10px] underline text-blue-400 hover:text-blue-300 mt-1">View Payment</a>`;
                    if (res.valid_id_image) proofsHtml += `<a href="uploads/${res.valid_id_image}" target="_blank" class="block text-[10px] underline text-purple-400 hover:text-purple-300 mt-1">View ID</a>`;

                    const refHtml = res.reference_number ? `<div class="text-[10px] font-mono text-coffee-muted mb-1 border border-coffee-border rounded px-1 inline-block bg-black/20 cursor-pointer hover:text-white" title="Copy Reference" onclick="navigator.clipboard.writeText('${res.reference_number}'); showToast('Reference Copied')">${res.reference_number}</div>` : '';

                    // Conditional Payment Info
                    let paymentInfoHtml = '';
                    if (res.payment_method === 'cash') {
                        if (res.status === 'approved' || res.status === 'confirmed') {
                             paymentInfoHtml = `<div class="text-xs text-yellow-400 font-bold mb-2">To be Paid at Counter</div><button class="bg-green-600 hover:bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded" onclick="markAsPaid(${res.id}, this)">Mark as Paid</button>`;
                        } else if (res.status === 'done') {
                            paymentInfoHtml = `<div class="text-xs text-green-400 font-bold">Paid at Counter</div><div class="text-xs text-coffee-accent font-bold">${formatMoney(res.total_bill)}</div>`;
                        }
                        
                    } else {
                        paymentInfoHtml = `
                            ${refHtml}
                            <div class="text-xs text-coffee-accent font-bold">${formatMoney(res.payable_amount)} Paid</div>
                            ${balanceHtml}
                        `;
                    }

                    // PARSE ORDERS
                    const { note, orders } = parseOrders(res.special_request);
                    let orderHtml = '';
                    if (orders.length > 0) {
                        orderHtml = `<div class="mt-2 pt-2 border-t border-coffee-border/50">
                            <div class="text-[10px] font-bold text-coffee-accent uppercase mb-1 flex items-center gap-1"><i class="ph ph-fork-knife"></i> Pre-Orders:</div>
                            <ul class="text-[10px] text-coffee-text list-disc list-inside space-y-0.5">
                                ${orders.map(o => `<li>${o}</li>`).join('')}
                            </ul>
                        </div>`;
                    }

                    tr.innerHTML = `
                        <td class="p-4 font-bold text-coffee-text guest-name align-top">
                            ${res.full_name}
                            <br><span class="text-xs text-coffee-muted font-normal">${res.email}</span>
                            <br><span class="text-xs text-coffee-muted font-normal">${res.phone_number}</span>
                        </td>
                        <td class="p-4 align-top">
                            <div class="font-bold">${new Date(res.mission_date).toLocaleDateString()}</div>
                            <div class="text-[10px] bg-${modeColor}-500/20 text-${modeColor}-400 px-2 py-0.5 rounded inline-block mt-1 border border-${modeColor}-500/30 uppercase">${modeLabel}</div>
                            <div class="text-xs text-coffee-muted mt-1">${formatTime(res.start_time)} - ${formatTime(res.end_time)}</div>
                        </td>
                        <td class="p-4 text-xs text-coffee-muted align-top">${new Date(res.created_at).toLocaleDateString()}</td>
                        <td class="p-4 text-center font-bold align-top text-lg">${res.guest_count}</td>
                        <td class="p-4 text-xs align-top">
                            <div class="text-coffee-muted font-bold mb-1">Equipment:</div>
                            <div class="text-white mb-2">${res.equipment_addons || 'None'}</div>
                            ${orderHtml}
                            ${note ? `<div class="text-[10px] text-gray-500 italic mt-2 border-t border-coffee-border/30 pt-1">Note: "${note}"</div>` : ''}
                        </td>
                        <td class="p-4 align-top">
                            <span class="px-2 py-1 rounded-full text-xs font-bold status-badge ${getBadgeClass(res.status)}" onclick="openStatusMenu(this, ${res.id})">${res.status.toUpperCase()}</span>
                        </td>
                        <td class="p-4 text-center align-top">
                            <div class="flex items-center justify-center gap-1 text-xs font-bold ${pDetails.color} mb-1">
                                <i class="ph ${pDetails.icon}"></i> ${pDetails.label}
                            </div>
                            ${paymentInfoHtml}
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Calendar Logic
            let calendarDate = new Date();
            let allReservationsCache = [];

            function renderCalendar(reservations = []) {
                if (reservations.length > 0) allReservationsCache = reservations;
                else reservations = allReservationsCache;

                const year = calendarDate.getFullYear();
                const month = calendarDate.getMonth();
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                document.getElementById('calendar-month-title').innerText = `${monthNames[month]} ${year}`;

                const grid = document.getElementById('calendar-grid-container');
                grid.innerHTML = '';

                const firstDayIndex = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                for (let i = 0; i < firstDayIndex; i++) {
                    const padDiv = document.createElement('div');
                    padDiv.className = 'bg-coffee-panel/50 min-h-[100px] border-r border-b border-coffee-border';
                    grid.appendChild(padDiv);
                }

                for (let i = 1; i <= daysInMonth; i++) {
                    const currentMonthStr = String(month + 1).padStart(2, '0');
                    const currentDayStr = String(i).padStart(2, '0');
                    const fullDateStr = `${year}-${currentMonthStr}-${currentDayStr}`;

                    const dayDiv = document.createElement('div');
                    dayDiv.className = 'calendar-day bg-coffee-panel min-h-[100px] p-2 hover:bg-coffee-dark/50 transition cursor-pointer relative group border-r border-b border-coffee-border';
                    dayDiv.innerHTML = `<div class="text-right text-xs text-coffee-muted font-bold mb-1">${i}</div>`;

                    const daysEvents = reservations.filter(res => 
                        res.mission_date === fullDateStr && 
                        (res.status.toLowerCase() === 'approved' || res.status.toLowerCase() === 'confirmed')
                    );

                    daysEvents.slice(0, 2).forEach(ev => {
                        const isStudy = ev.reservation_type === 'study';
                        const colorClass = isStudy ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' : 'bg-purple-500/20 text-purple-300 border-purple-500/30';
                        const pill = document.createElement('div');
                        pill.className = `text-[9px] ${colorClass} px-1.5 py-0.5 rounded border mb-1 truncate font-bold`;
                        pill.innerText = ev.full_name;
                        dayDiv.appendChild(pill);
                    });

                    if (daysEvents.length > 2) {
                        const more = document.createElement('div');
                        more.className = 'text-[9px] text-coffee-accent font-bold mt-1 pl-1';
                        more.innerText = `+${daysEvents.length - 2} more`;
                        dayDiv.appendChild(more);
                    }

                    dayDiv.addEventListener('click', () => openDayDetails(fullDateStr, daysEvents));
                    grid.appendChild(dayDiv);
                }
            }

            document.getElementById('cal-prev-btn').addEventListener('click', () => { calendarDate.setMonth(calendarDate.getMonth() - 1); renderCalendar(); });
            document.getElementById('cal-next-btn').addEventListener('click', () => { calendarDate.setMonth(calendarDate.getMonth() + 1); renderCalendar(); });

            function openDayDetails(dateStr, events) {
                const modal = document.getElementById('day-details-modal');
                const container = document.getElementById('day-modal-content');
                const title = document.getElementById('day-modal-title');
                const dateObj = new Date(dateStr);
                title.innerText = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                container.innerHTML = '';

                if (events.length === 0) {
                    container.innerHTML = `<div class="text-center text-coffee-muted py-8">No approved missions on this day.</div>`;
                } else {
                    events.forEach(res => {
                        const isStudy = res.reservation_type === 'study';
                        const colorClass = isStudy ? 'text-blue-400' : 'text-purple-400';
                        const typeLabel = isStudy ? 'Study Mission' : 'Social Gathering';
                        
                        // Parse Orders for Modal
                        const { note, orders } = parseOrders(res.special_request);
                        let orderHtml = '';
                        if(orders.length > 0) {
                             orderHtml = `<div class="mt-2 pt-2 border-t border-coffee-border/30"><div class="text-[10px] text-coffee-accent font-bold">PRE-ORDERS:</div><ul class="text-[10px] text-coffee-text list-disc list-inside">${orders.map(o=>`<li>${o}</li>`).join('')}</ul></div>`;
                        }

                        const card = document.createElement('div');
                        card.className = 'bg-coffee-dark p-4 rounded border border-coffee-border mb-3';
                        card.innerHTML = `<div class="flex justify-between items-start"><div><div class="font-bold text-coffee-text">${res.full_name}</div><div class="text-xs text-coffee-muted">${formatTime(res.start_time)} - ${formatTime(res.end_time)}</div><div class="text-xs ${colorClass} font-bold mt-1">${typeLabel}</div></div><div class="text-right"><div class="text-xs font-bold text-coffee-accent mb-1">${formatMoney(res.total_bill)}</div><div class="text-[10px] text-green-500 border border-green-500/50 px-2 rounded">CONFIRMED</div></div></div>${orderHtml}<div class="mt-2 text-xs text-coffee-muted italic border-t border-coffee-border pt-2">"${note || 'No special requests'}"</div>`;
                        container.appendChild(card);
                    });
                }
                modal.classList.remove('hidden');
            }
            const closeDayBtn = document.getElementById('close-day-modal-btn');
            if(closeDayBtn) closeDayBtn.addEventListener('click', () => document.getElementById('day-details-modal').classList.add('hidden'));

            // === STATUS UPDATE LOGIC ===
            const statusMenu = document.getElementById('status-context-menu');
            let activeResId = null;
            let activeBadge = null;

            window.openStatusMenu = function(badgeElement, id) {
                event.stopPropagation();
                activeResId = id;
                activeBadge = badgeElement;
                statusMenu.classList.remove('hidden');

                const isOrder = activeBadge.closest('tr').dataset.source === 'product_orders';
                document.querySelectorAll('.order-only').forEach(el => {
                    el.classList.toggle('hidden', !isOrder);
                });

                
                const rect = badgeElement.getBoundingClientRect();
                const menuHeight = statusMenu.offsetHeight;
                const windowHeight = window.innerHeight;
                
                if (rect.bottom + menuHeight + 10 > windowHeight) {
                    statusMenu.style.top = `${rect.top - menuHeight - 5}px`;
                } else {
                    statusMenu.style.top = `${rect.bottom + 5}px`;
                }
                statusMenu.style.left = `${rect.left}px`;
            }

            document.addEventListener('click', (e) => {
                if (!statusMenu.contains(e.target)) statusMenu.classList.add('hidden');
            });

            document.querySelectorAll('#status-context-menu button[data-val]').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (!activeResId) return;
                    const newStatus = btn.dataset.val;
                    const newClass = btn.dataset.class;
                    const newText = btn.dataset.text;
                    
                    fetch('admin_api.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: activeResId, status: newStatus, source_table: activeBadge.closest('tr').dataset.source })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            activeBadge.className = `px-2 py-1 rounded-full text-xs font-bold status-badge ${newClass}`;
                            activeBadge.innerText = newText;
                            activeBadge.closest('tr').dataset.status = newStatus; 
                            showToast(`Status updated to ${newText}`, 'success');

                            // Reminder for cash payments
                            const reservationData = JSON.parse(activeBadge.closest('tr').dataset.reservation);
                            if (newStatus === 'approved' && reservationData.payment_method === 'cash') {
                                setTimeout(() => showToast('Reminder: Customer will pay at the counter.', 'success'), 500);
                            }

                            loadDashboardData(); 
                        } else {
                            showToast('Failed to update database', 'error');
                        }
                        statusMenu.classList.add('hidden');
                    });
                });
            });

            // Mark as Paid at Counter
            window.markAsPaid = function(id, buttonElement) {
                if(!confirm("Confirm that the customer has paid the full amount at the counter?")) return;

                fetch('admin_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id, status: 'done' })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showToast("Payment Confirmed!", 'success');
                        // Update the UI immediately without a full reload
                        const row = buttonElement.closest('tr');
                        row.dataset.status = 'done';
                        const statusBadge = row.querySelector('.status-badge');
                        statusBadge.className = 'px-2 py-1 rounded-full text-xs font-bold status-badge badge-done';
                        statusBadge.innerText = 'DONE';
                        loadDashboardData(); // Reload stats and re-apply filters/sorting
                    } else {
                        showToast('Failed to update status', 'error');
                    }
                });
            }

            // DELETE RECORD LOGIC
            document.getElementById('btn-delete-record').addEventListener('click', () => {
                if (!activeResId) return;
                if(!confirm("Are you sure you want to permanently DELETE this reservation record?")) return;

                fetch('admin_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: activeResId, action: 'delete' })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showToast("Record Deleted Successfully", 'success');
                        const row = document.querySelector(`tr[data-id='${activeResId}']`);
                        if(row) row.remove();
                        loadDashboardData(); 
                    } else {
                        showToast("Delete Failed: " + (data.error || 'Unknown error'), 'error');
                    }
                    statusMenu.classList.add('hidden');
                });
            });

            // --- ANNOUNCEMENT MANAGER LOGIC ---
            window.openAnnouncementModal = function() {
                document.getElementById('announcement-modal').classList.remove('hidden');
            }

            function loadAdminAnnouncements() {
                fetch('announce_api.php')
                    .then(res => res.json())
                    .then(data => {
                        const container = document.getElementById('admin-announcements-list');
                        container.innerHTML = '';

                        if(data.length === 0) {
                            container.innerHTML = '<div class="text-center text-coffee-muted text-xs">No announcements yet.</div>';
                            return;
                        }

                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = "bg-coffee-panel p-4 rounded-lg border border-coffee-border flex justify-between items-start";
                            div.innerHTML = `
                                <div>
                                    <h4 class="font-bold text-coffee-accent mb-1">${item.title} <span class="text-[9px] bg-coffee-dark px-2 rounded ml-2 border border-coffee-border text-coffee-muted">${item.type.toUpperCase()}</span></h4>
                                    <p class="text-coffee-text text-sm">"${item.content}"</p>
                                    <div class="text-[10px] text-coffee-muted mt-2">Posted: ${new Date(item.created_at).toLocaleDateString()}</div>
                                </div>
                                <div class="flex-shrink-0 ml-4 text-right">
                                    <button class="text-red-500 hover:text-white text-xs font-bold" onclick="deleteAnnouncement(${item.id})"><i class="ph ph-trash"></i> Delete</button>
                                </div>
                            `;
                            container.appendChild(div);
                        });
                    });
            }

            document.getElementById('announcement-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const payload = {
                    title: this.title.value,
                    type: this.type.value,
                    content: this.content.value
                };

                fetch('announce_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showToast("Announcement Posted");
                        document.getElementById('announcement-modal').classList.add('hidden');
                        this.reset();
                        loadAdminAnnouncements();
                    } else {
                        showToast("Error posting announcement", 'error');
                    }
                });
            });

            window.deleteAnnouncement = function(id) {
                if(!confirm("Delete this announcement?")) return;
                
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('announce_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showToast("Announcement Deleted");
                        loadAdminAnnouncements();
                    } else {
                        showToast("Error deleting", 'error');
                    }
                });
            };

            // --- MENU MANAGER LOGIC ---
            function loadMenuData() {
                fetch('menu_api.php').then(res=>res.json()).then(data=>{ dbMenuItems=data; constructBookFromDB(); });
            }
            function constructBookFromDB() {
                bookSheetsData = [
                    { front: { type: 'cover', title: "BatStateU Store" }, back: { type: 'featured', title: "Featured Item", itemId: 'featured' } }
                ];
                const pages = Object.entries(categoryMap);
                for (let i = 0; i < pages.length; i += 2) {
                    const frontCat = pages[i];
                    const backCat = pages[i+1];
                    const sheet = { front: null, back: null };
                    if (frontCat) {
                        const items = dbMenuItems.filter(item => frontCat[1].includes(item.category));
                        sheet.front = { type: 'list', title: frontCat[0], items: items };
                    } else { sheet.front = { type: 'end', title: 'Notes' }; }
                    if (backCat) {
                        const items = dbMenuItems.filter(item => backCat[1].includes(item.category));
                        sheet.back = { type: 'list', title: backCat[0], items: items };
                    } else { sheet.back = { type: 'cover-back', title: 'End' }; }
                    bookSheetsData.push(sheet);
                }
                bookSheetsData.push({ front: { type: 'end', title: 'The End' }, back: { type: 'cover-back', title: '' } });
                renderBook(); 
            }
            function renderPageContent(page) {
                // Helper function to set menu mode
                window.setMenuMode = function(mode) {
                    isEditMode = (mode === 'edit');
                    
                    const btnAddItem = document.getElementById('btn-add-item');
                    const btnViewCustomer = document.getElementById('btn-view-customer');
                    const btnViewEdit = document.getElementById('btn-view-edit');

                    btnAddItem.classList.toggle('hidden', !isEditMode);
                    
                    btnViewCustomer.classList.toggle('bg-coffee-accent', !isEditMode);
                    btnViewCustomer.classList.toggle('text-coffee-dark', !isEditMode);
                    btnViewCustomer.classList.toggle('text-coffee-muted', isEditMode);
                    
                    btnViewEdit.classList.toggle('bg-coffee-accent', isEditMode);
                    btnViewEdit.classList.toggle('text-coffee-dark', isEditMode);
                    btnViewEdit.classList.toggle('text-coffee-muted', !isEditMode);

                    renderBook(); // Re-render the book to show/hide controls
                }
                if (page.type === 'cover') {
                    return `<div class="h-full flex flex-col items-center justify-center p-8 text-center text-white"><img src="https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png" alt="BatStateU Logo" class="w-32 h-32 mb-6"><h1 class="text-5xl font-serif font-bold text-center mb-4">${page.title}</h1><p class="text-sm font-serif uppercase tracking-widest">Official Merchandise</p></div>`;
                } else if (page.type === 'cover-back') {
                    return `<div class="h-full flex flex-col items-center justify-center p-8 text-center"><h1 class="text-3xl font-serif mb-4">The End</h1><p class="text-xs">Batangas State University</p></div>`;
                } else if (page.type === 'end') {
                    return `<div class="h-full flex flex-col items-center justify-center p-8 text-center paper-content"><h2 class="text-2xl font-serif text-gray-800">Thank you!</h2><div class="mt-8 space-y-2 text-sm text-gray-600"><p>Leading Innovations, Transforming Lives</p></div></div>`;
                } else if (page.type === 'featured') {
                    const item = dbMenuItems.find(i => i.id == page.itemId) || { name: "University Hoodie", description: "Official Red-Tailed Hawk Hoodie", price_solo: "850", icon: "ph-t-shirt" };
                    return `<div class="h-full p-8 flex flex-col paper-content"><div class="border-b-2 border-coffee-accent pb-4 mb-6"><h2 class="text-3xl font-serif font-bold title text-center text-gray-900">${page.title}</h2></div><div class="flex-1 flex flex-col items-center justify-center space-y-6"><div class="w-48 h-48 rounded-full shadow-xl border-4 border-coffee-accent bg-coffee-dark flex items-center justify-center"><i class="ph ph-t-shirt text-6xl text-coffee-accent"></i></div><div class="text-center group menu-item w-full p-4 rounded-lg"><h3 class="text-2xl font-bold title mb-2 text-gray-900">${item.name}</h3><p class="subtitle italic mb-4 text-gray-600">${item.description}</p><span class="text-xl font-bold text-coffee-accent">₱${item.price_solo}</span></div></div></div>`;
                } else if (page.type === 'list') {
                    const listHtml = page.items.map(item => {
                        let priceDisplay = item.price_solo;
                        if (parseFloat(item.price_group) > 0) {
                            priceDisplay = `${item.price_solo} (S) / ${item.price_group} (G)`;
                        }
                        let imageHtml = '';
                        if (item.image_path && item.image_path !== '') {
                            imageHtml = `<img src="${item.image_path}" class="w-8 h-8 rounded object-cover border border-coffee-border">`;
                        }
                        let sizeHtml = '';
                        if (item.sizes && item.sizes.trim() !== '') {
                            sizeHtml = `<div class="text-[10px] text-coffee-muted mt-1">Sizes: ${item.sizes}</div>`;
                        }

                        const editControls = isEditMode ? `
                            <div class="absolute right-0 top-0 flex gap-1 bg-coffee-panel border border-coffee-accent rounded px-1 -mt-2 z-10">
                                <button onclick="openItemModal(${item.id})" class="text-xs text-blue-400 hover:text-white p-1"><i class="ph ph-pencil-simple"></i></button>
                                <button onclick="deleteItem(${item.id})" class="text-xs text-red-500 hover:text-white p-1"><i class="ph ph-trash"></i></button>
                            </div>
                        ` : '';
                        return `<div class="menu-item group mb-4 relative flex gap-3 items-start" data-item-id="${item.id}">
                            ${editControls}
                            <div class="flex-shrink-0 pt-1">${imageHtml}</div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-baseline"><h4 class="font-bold title text-sm text-gray-900">${item.name}</h4><span class="text-coffee-accent font-bold text-xs">₱${priceDisplay}</span></div>
                                <p class="text-xs subtitle mt-1 leading-tight text-gray-600">${item.description}</p>
                                ${sizeHtml}
                            </div>
                        </div>`;
                    }).join('');
                    return `<div class="h-full p-8 overflow-y-auto custom-scrollbar paper-content"><h2 class="text-2xl font-serif font-bold title text-center mb-2 text-gray-900">${page.title}</h2><div class="menu-title-underline"></div><div class="space-y-4 mt-6">${listHtml}</div></div>`;
                }
            }
            function renderBook() {
                const bookContainer = document.getElementById('book-container');
                bookContainer.innerHTML = '';
                bookSheetsData.forEach((sheetData, index) => {
                    const paper = document.createElement('div');
                    paper.className = 'paper';
                    paper.style.zIndex = bookSheetsData.length - index;
                    const frontFace = document.createElement('div');
                    frontFace.className = 'front';
                    if(sheetData.front.type === 'cover') frontFace.classList.add('cover-front');
                    frontFace.innerHTML = renderPageContent(sheetData.front);
                    const backFace = document.createElement('div');
                    backFace.className = 'back';
                    if(sheetData.back.type === 'cover-back') backFace.classList.add('back-cover-texture');
                    backFace.innerHTML = renderPageContent(sheetData.back);
                    paper.appendChild(frontFace);
                    paper.appendChild(backFace);
                    bookContainer.appendChild(paper);
                });
                updateBookState();
            }
            // ... (Rest of modal/image logic) ...
            window.openItemModal = function(id = null) {
                const modal = document.getElementById('menu-item-modal');
                const form = document.getElementById('menu-item-form');
                const title = document.getElementById('modal-title');
                form.reset(); 
                document.getElementById('edit-id').value = '';
                document.getElementById('image-preview-container').classList.add('hidden');
                document.getElementById('remove-image-btn').classList.add('hidden');
                if (id) {
                    // Reset and hide size field initially
                    document.getElementById('edit-sizes').value = '';
                    document.getElementById('size-input-container').classList.add('hidden');

                    const item = dbMenuItems.find(i => i.id == id);
                    if(item) {
                        title.innerText = "Edit Product";
                        document.getElementById('edit-id').value = item.id;
                        document.getElementById('edit-name').value = item.name;
                        document.getElementById('edit-category').value = item.category;
                        document.getElementById('edit-price-solo').value = item.price_solo;
                        document.getElementById('edit-price-group').value = item.price_group;
                        document.getElementById('edit-desc').value = item.description;
                        if (item.category === 'uniforms') {
                            document.getElementById('size-input-container').classList.remove('hidden');
                            document.getElementById('edit-sizes').value = item.sizes || '';
                        }

                        if (item.image_path) {
                            document.getElementById('image-preview').src = item.image_path;
                            document.getElementById('image-preview-container').classList.remove('hidden');
                            document.getElementById('remove-image-btn').classList.remove('hidden');
                            document.getElementById('edit-existing-image').value = item.image_path;
                        }
                    }
                } else { 
                    title.innerText = "Add New Product"; 
                    // Reset and hide size field for new item
                    document.getElementById('edit-sizes').value = '';
                    toggleSizeField(); // Check if default category needs it
                }
                modal.classList.remove('hidden');
            };
            window.toggleSizeField = function() {
                const category = document.getElementById('edit-category').value;
                const sizeContainer = document.getElementById('size-input-container');
                sizeContainer.classList.toggle('hidden', category !== 'uniforms');
            };
            window.previewImage = function(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('image-preview').src = e.target.result;
                        document.getElementById('image-preview-container').classList.remove('hidden');
                        document.getElementById('remove-image-btn').classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            };
            window.removeImage = function() {
                document.getElementById('edit-image').value = ''; 
                document.getElementById('edit-existing-image').value = ''; 
                document.getElementById('image-preview').src = '';
                document.getElementById('image-preview-container').classList.add('hidden');
                document.getElementById('remove-image-btn').classList.add('hidden');
            };
            document.getElementById('menu-item-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('menu_api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.success) { showToast("Menu Item Saved"); document.getElementById('menu-item-modal').classList.add('hidden'); loadMenuData(); } 
                    else { showToast("Error saving", 'error'); }
                });
            });
            window.deleteItem = function(id) {
                if(!confirm("Delete item?")) return;
                fetch('menu_api.php', { method: 'DELETE', body: JSON.stringify({ id: id }) })
                .then(res => res.json())
                .then(data => { if(data.success) { showToast("Deleted"); loadMenuData(); } });
            };

            // Sidebar Toggle
            const toggleSidebar = () => {
                const sidebar = document.getElementById('main-sidebar');
                if (sidebar.classList.contains('sidebar-closed')) { sidebar.classList.remove('sidebar-closed'); sidebar.classList.add('sidebar-open'); }
                else { sidebar.classList.remove('sidebar-open'); sidebar.classList.add('sidebar-closed'); }
            };
            document.getElementById('mobile-menu-btn').addEventListener('click', toggleSidebar);
            document.getElementById('close-sidebar-btn').addEventListener('click', toggleSidebar);
            document.querySelectorAll('.sidebar-link').forEach(button => {
                button.addEventListener('click', () => {
                    document.querySelectorAll('[id^="view-"]').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
                    const tabName = button.dataset.tab;
                    document.getElementById('view-' + tabName).classList.remove('hidden');
                    document.getElementById('page-title').innerText = button.innerText.trim();
                    button.classList.add('active');
                    
                    // NEW: Refresh announcements when that specific tab is clicked
                    if(tabName === 'announcements') loadAdminAnnouncements();
                    if(tabName === 'orders' || tabName === 'reservations') loadDashboardData(); // Reload data for orders and reservations
                });
            });
            
            // --- DYNAMIC CHART LOGIC ---
            let revenueChart;
            let chartDataCache;

            function initializeCharts(stats) {
                chartDataCache = {
                    week: stats.weekly_revenue,
                    month: stats.monthly_revenue
                };
                const ctx = document.getElementById('revenueChart').getContext('2d');
                revenueChart = new Chart(ctx, {
                    type: 'line',
                    data: { labels: chartDataCache.week.labels, datasets: [{ label: 'Revenue (₱)', data: chartDataCache.week.data, backgroundColor: 'rgba(197, 157, 96, 0.1)', borderColor: '#C59D60', borderWidth: 2, fill: true, tension: 0.4 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { color: '#382D24', borderDash: [5, 5] } } } }
                });

                document.getElementById('revenue-timeframe').addEventListener('change', (e) => {
                    const timeframe = e.target.value; // 'week' or 'month'
                    revenueChart.data.labels = chartDataCache[timeframe].labels;
                    revenueChart.data.datasets[0].data = chartDataCache[timeframe].data;
                    revenueChart.update();
                });
            }
            
            // Add other logical pieces
            const bCont = document.getElementById('venue-booking-container');
            if(bCont) {
                bCont.innerHTML = `<div class="max-w-4xl mx-auto mb-12"><div class="bg-coffee-panel border border-coffee-accent rounded-xl overflow-hidden shadow-2xl"><div class="bg-coffee-dark p-4 border-b border-coffee-accent flex justify-between items-center"><h2 class="text-xl font-bold text-coffee-text">Venue Reservation Protocols</h2></div><div class="p-8"><h3 class="text-5xl font-bold text-coffee-accent">₱50<span class="text-sm text-coffee-muted">/hr</span></h3><p class="text-coffee-muted mt-2">Base hourly rate.</p></div></div></div>`;
            }
            setInterval(() => { document.getElementById('live-clock').innerText = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit' }); }, 1000);
            
            // Book pagination helpers
            window.flipForward = function() { if (currentSheetIndex < bookSheetsData.length) { currentSheetIndex++; updateBookState(); } };
            window.flipBackward = function() { if (currentSheetIndex > 0) { currentSheetIndex--; updateBookState(); } };
            function updateBookState() {
                const sheets = document.querySelectorAll('.paper');
                const prevBtn = document.getElementById('book-prev-btn');
                const nextBtn = document.getElementById('book-next-btn');
                sheets.forEach((sheet, index) => {
                    if (index < currentSheetIndex) { sheet.classList.add('flipped'); sheet.style.zIndex = index; } 
                    else { sheet.classList.remove('flipped'); sheet.style.zIndex = bookSheetsData.length - index; }
                });
                if(prevBtn) prevBtn.classList.toggle('hidden', currentSheetIndex === 0);
                if(nextBtn) nextBtn.classList.toggle('hidden', currentSheetIndex === bookSheetsData.length);
            }
            let currentSheetIndex = 0;
            
            // View Toggles
            const listBtn = document.querySelector('.view-switch-btn[data-view="list"]');
            const calendarBtn = document.querySelector('.view-switch-btn[data-view="calendar"]');
            const listView = document.getElementById('reservations-list-view');
            const calendarView = document.getElementById('reservations-calendar-view');
            const searchWrapper = document.getElementById('reservation-search-wrapper');
            if(listBtn && calendarBtn) {
                listBtn.addEventListener('click', () => {
                    listView.classList.remove('hidden'); calendarView.classList.add('hidden');
                    listBtn.classList.add('active', 'bg-coffee-accent', 'text-white'); listBtn.classList.remove('bg-coffee-panel', 'text-coffee-muted');
                    calendarBtn.classList.remove('active', 'bg-coffee-accent', 'text-white'); calendarBtn.classList.add('bg-coffee-panel', 'text-coffee-muted');
                    searchWrapper.classList.remove('invisible'); 
                });
                calendarBtn.addEventListener('click', () => {
                    listView.classList.add('hidden'); calendarView.classList.remove('hidden');
                    calendarBtn.classList.add('active', 'bg-coffee-accent', 'text-white'); calendarBtn.classList.remove('bg-coffee-panel', 'text-coffee-muted');
                    listBtn.classList.remove('active', 'bg-coffee-accent', 'text-white'); listBtn.classList.add('bg-coffee-panel', 'text-coffee-muted');
                    searchWrapper.classList.add('invisible'); 
                });
            }
            
            let isLightMode = false;
            const toggleTheme = () => {
                document.body.classList.toggle('light-mode');
                isLightMode = !isLightMode;
                const stIcon = document.getElementById('theme-icon');
                if(stIcon) stIcon.className = isLightMode ? "ph ph-sun text-2xl" : "ph ph-moon text-2xl";
                showToast(isLightMode ? 'Switched to Wayne Enterprise Mode' : 'Switched to Bat Computer Mode');
            };
            const stThemeBtn = document.getElementById('theme-toggle');
            if(stThemeBtn) stThemeBtn.addEventListener('click', toggleTheme);
            
            // Attach listener for the settings tab button
            const settingsBtn = document.getElementById('nav-settings-btn');
            if (settingsBtn) {
                settingsBtn.addEventListener('click', () => {
                    // This logic runs when the settings tab is opened, ensuring the elements exist.
                    const updatePassBtn = document.getElementById('update-pass-btn');
                    if (updatePassBtn && !updatePassBtn.dataset.listenerAttached) {
                        updatePassBtn.addEventListener('click', () => {
                            const currentPassInput = document.getElementById('current-pass-input');
                            const newPassInput = document.getElementById('new-pass-input');
                            if (currentPassInput.value === ownerPassword) {
                                if (newPassInput.value.length > 0) {
                                    ownerPassword = newPassInput.value;
                                    localStorage.setItem(PASSWORD_KEY, ownerPassword); // Save the new password
                                    showToast('Security protocols updated. New passcode set.', 'success');
                                    currentPassInput.value = '';
                                    newPassInput.value = '';
                                } else { showToast('New passcode cannot be empty.', 'error'); }
                            } else { showToast('Authorization failed. Incorrect current passcode.', 'error'); }
                        });
                        updatePassBtn.dataset.listenerAttached = 'true'; // Prevent re-attaching
                    }
                });
            }
        });
    </script>
</body>
</html>