<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bat Cave Cafe - Home</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Inter:wght@300;400;600&family=Playball&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                container: { center: true, padding: '1.5rem', screens: { sm: '640px', md: '768px', lg: '1024px', xl: '1280px', '2xl': '1440px' } },
                extend: {
                    colors: {
                        'coffee-dark': 'var(--bg-primary)', 'coffee-panel': 'var(--bg-panel)',
                        'coffee-accent': 'var(--text-accent)', 'coffee-text': 'var(--text-main)',
                        'coffee-muted': 'var(--text-muted)', 'coffee-border': 'var(--border-color)',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Cinzel', 'serif'] },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-20px)' } },
                    }
                }
            }
        }
    </script>
    <style>
        :root { --bg-primary: #FFFFFF; --bg-panel: #F9FAFB; --text-accent: #C00000; --text-main: #1F2937; --text-muted: #6B7280; --border-color: #E5E7EB; transition: background-color 0.4s, color 0.4s; }
        .dark-theme { --bg-primary: #1C1613; --bg-panel: #2A221E; --text-accent: #C59D60; --text-main: #D6C0A0; --text-muted: #9CA3AF; --border-color: #382D24; }
        
        .hero-bg { background-color: var(--bg-primary); background-image: radial-gradient(at 0% 100%, rgba(192, 0, 0, 0.05) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(192, 0, 0, 0.1) 0px, transparent 70%); }
        .dark-theme .hero-bg { background-color: var(--bg-primary) !important; background-image: radial-gradient(at 0% 100%, rgba(200, 150, 100, 0.1) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(56, 45, 36, 0.5) 0px, transparent 70%); }
        .dark-theme header { background-color: var(--bg-primary) !important; }
        .dark-theme header a, .dark-theme .text-coffee-light, .dark-theme .text-white { color: var(--text-main) !important; }
        .dark-theme .text-coffee-accent { color: var(--text-accent) !important; }
        .dark-theme .product-card { background-color: var(--bg-panel) !important; border: 1px solid var(--border-color); }
        .dark-theme footer { background-color: var(--bg-panel) !important; border-top-color: var(--border-color) !important; }
        .scroll-fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .scroll-fade-in.visible { opacity: 1; transform: translateY(0); }
        #mobile-menu { transition: transform 0.3s ease-in-out; background-color: var(--bg-primary) !important; }
        #mobile-menu.open { transform: translateX(0); }
        #mobile-menu.closed { transform: translateX(100%); }
    </style>
</head>
<body class="text-coffee-text font-sans antialiased relative" style="background-color: var(--bg-primary);">
    
    <div id="app" class="min-h-screen flex flex-col">

        <header class="sticky top-0 z-50 bg-coffee-dark/90 backdrop-blur-sm shadow-xl h-20">
            <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
                <a href="1homepage.php" class="flex items-center gap-2 text-xl font-bold text-coffee-light tracking-wider font-serif hover:text-coffee-accent transition">
                    <img src="https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png" alt="Batangas State University Logo" class="w-8 h-8">
                    <span>Batangas State <span class="text-coffee-accent">University</span></span>
                </a>
                
                <div class="hidden lg:flex space-x-8 text-sm font-medium">
                    <a href="1homepage.php" class="nav-link text-coffee-accent transition duration-300">Home</a>
                    <a href="#about" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">About</a>
                    <a href="2menu_walkin.php" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">Menu</a>
                </div>

                <div class="flex items-center space-x-4">
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-coffee-panel transition">
                        <i id="sun-icon" class="ph ph-sun text-xl text-coffee-accent" style="display:none;"></i>
                        <i id="moon-icon" class="ph ph-moon text-xl text-coffee-accent"></i>
                    </button>
                    <a href="gathering_booking.php" class="hidden sm:inline-block px-5 py-2 text-sm font-bold rounded-full border-2 border-coffee-accent text-coffee-accent hover:bg-coffee-accent hover:text-coffee-dark transition duration-300 transform hover:scale-105 font-serif">
                        RESERVE A SPOT
                    </a>
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-full hover:bg-coffee-panel transition text-coffee-light">
                        <i class="ph ph-list text-2xl"></i>
                    </button>
                </div>
            </nav>
        </header>

        <div id="mobile-menu" class="fixed inset-0 z-[60] bg-coffee-dark closed lg:hidden flex flex-col items-center justify-center space-y-8 shadow-2xl">
            <button id="close-menu-btn" class="absolute top-6 right-6 p-2 text-coffee-accent hover:text-coffee-text">
                <i class="ph ph-x text-3xl"></i>
            </button>
            <a href="index.php" class="text-2xl font-serif text-coffee-accent hover:text-coffee-text mobile-link">Home</a>
            <a href="#about" onclick="toggleMenu()" class="text-2xl font-serif text-coffee-text hover:text-coffee-accent mobile-link">About</a>
            <a href="2menu_walkin.php" class="text-2xl font-serif text-coffee-text hover:text-coffee-accent mobile-link">Menu</a>
        </div>

        <main class="flex-grow">
            
            <section id="announcements" class="hidden py-8 bg-coffee-panel border-b border-coffee-border relative overflow-hidden">
                <div class="container mx-auto px-6 relative z-10">
                    <div class="flex items-center gap-4 mb-6 justify-center md:justify-start">
                        <div class="bg-coffee-dark p-2 rounded-full border border-coffee-accent animate-pulse-slow">
                            <i class="ph ph-megaphone text-xl text-coffee-accent"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-serif font-bold text-coffee-text uppercase tracking-widest">Mission Updates</h2>
                        </div>
                    </div>

                    <div id="announcements-container" class="grid md:grid-cols-2 gap-6">
                        </div>
                </div>
            </section>

            <section class="hero-bg relative py-20 overflow-hidden">
                <div class="absolute top-20 left-10 animate-float opacity-20 pointer-events-none">
                    <i class="ph ph-coffee-bean text-4xl text-coffee-accent"></i>
                </div>
                <div class="absolute bottom-40 right-20 animate-float-delayed opacity-20 pointer-events-none">
                    <i class="ph ph-coffee-bean text-5xl text-coffee-accent"></i>
                </div>

                <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center relative z-10">
                    <div class="space-y-8 scroll-fade-in" data-delay="0">
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black font-serif leading-tight text-coffee-text">
                            Library Reservation & <br>
                            <span class="text-coffee-accent">Utility Ordering System</span>
                        </h1>
                        
                        <div class="flex space-x-4 pt-4">
                            <a href="gathering_booking.php" class="flex items-center space-x-2 px-8 py-4 bg-coffee-accent text-coffee-dark font-bold rounded-xl shadow-lg transition duration-300 transform hover:-translate-y-1 hover:scale-[1.02] font-serif uppercase tracking-wider">
                                <span>Reserve a Spot</span>
                                <i class="ph ph-calendar-blank text-lg"></i>
                            </a>
                            <a href="2menu_walkin.php" class="flex items-center space-x-2 px-8 py-4 border-2 border-coffee-text text-coffee-text font-bold rounded-xl hover:bg-coffee-panel transition duration-300 transform hover:scale-[1.02]">
                                <span>Order Now</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="relative flex justify-center lg:justify-end scroll-fade-in" data-delay="100">
                        <img src="pics/unif.png" alt="Hand holding caramel macchiato" class="w-full max-w-xs md:max-w-sm lg:max-w-md xl:max-w-lg h-full object-contain rounded-3xl shadow-[0_35px_60px_-15px_rgba(0,0,0,0.7)] transform transition duration-500 hover:scale-[1.02]" onerror="this.src='https://placehold.co/500x500/382d24/d6c0a0?text=Coffee+Placeholder'">
                        <div class="absolute top-0 -right-4 md:right-4 p-4 bg-coffee-panel/90 backdrop-blur-md rounded-xl shadow-2xl text-xs flex flex-col items-center transform rotate-2 border border-coffee-accent/20">
                            <div class="flex text-yellow-400 mb-1 text-sm">★★★★★</div>
                            <span class="text-coffee-accent font-bold font-serif uppercase tracking-widest">Best Seller</span>
                        </div>
                    </div>
                </div>
                
                <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0]">
                    <svg class="relative block w-full h-[60px] lg:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-coffee-dark theme-fill" fill="currentColor" />
                    </svg>
                </div>
            </section>
            
            <section id="about" class="py-20 bg-coffee-dark relative about-section">
                <div class="container mx-auto px-6">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="order-2 md:order-1 scroll-fade-in" data-delay="100">
                            <img src="pics/library.png" alt="Cafe Interior" class="rounded-2xl shadow-2xl border border-coffee-border transition hover:scale-[1.02] duration-500" onerror="this.src='https://placehold.co/600x400/2a221e/d6c0a0?text=Interior'">
                        </div>
                        <div class="order-1 md:order-2 space-y-6 scroll-fade-in" data-delay="0">
                            <h2 class="text-3xl md:text-4xl font-serif font-bold text-coffee-text">Welcome to <span class="text-coffee-accent">The Library</span></h2>
                            <p class="text-coffee-muted leading-relaxed">
                                A system created for the students to book a reservation to library to solve the over population, also student can now order basic utilities through the system to solve the long line of waiting.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div class="bg-coffee-panel/80 p-4 rounded-lg border-l-2 border-coffee-accent shadow-lg">
                                    <h4 class="text-coffee-accent font-bold font-serif text-sm mb-2 uppercase tracking-wider">Our Mission</h4>
                                    <p class="text-xs text-coffee-muted leading-relaxed">A university committed to producing leaders by providing a 21st century learning environment through innovations in education, multidisciplinary research, and community and industry partnerships in order to nurture the spirit of nationhood, propel the national economy, and engage the world for sustainable development</p>
                                </div>
                                <div class="bg-coffee-panel/80 p-4 rounded-lg border-l-2 border-coffee-accent shadow-lg">
                                    <h4 class="text-coffee-accent font-bold font-serif text-sm mb-2 uppercase tracking-wider">Our Vision</h4>
                                    <p class="text-xs text-coffee-muted leading-relaxed">A premier national university that develops leaders in the global knowledge economy.</p>
                                </div>
                            </div>

                            <div class="pt-2">
                                <h4 class="text-coffee-accent font-bold font-serif text-sm mb-3 uppercase tracking-wider">Core Values</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2 text-coffee-muted">
                                    <span class="flex items-center gap-2 text-sm"><i class="ph ph-check-circle text-lg text-coffee-accent"></i> Patriotism</span>
                                    <span class="flex items-center gap-2 text-sm"><i class="ph ph-check-circle text-lg text-coffee-accent"></i> Service</span>
                                    <span class="flex items-center gap-2 text-sm"><i class="ph ph-check-circle text-lg text-coffee-accent"></i> Integrity</span>
                                    <span class="flex items-center gap-2 text-sm"><i class="ph ph-check-circle text-lg text-coffee-accent"></i> Resilience</span>
                                    <span class="flex items-center gap-2 text-sm"><i class="ph ph-check-circle text-lg text-coffee-accent"></i> Excellence</span>
                                    <span class="flex items-center gap-2 text-sm"><i class="ph ph-check-circle text-lg text-coffee-accent"></i> Faith</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

        </main>
        
        <footer class="bg-coffee-panel py-12 border-t border-coffee-border">
            <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <div class="text-2xl font-serif font-bold text-coffee-text mb-2">BATANGAS STATE UNIVERSITY</div>
                    <p class="text-coffee-muted text-sm">LIBRARY RESERVATION AND UTILITY ORDERING SYSTEM</p>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-coffee-muted hover:text-coffee-accent transition"><i class="ph ph-instagram-logo text-xl"></i></a>
                    <a href="#" class="text-coffee-muted hover:text-coffee-accent transition"><i class="ph ph-twitter-logo text-xl"></i></a>
                    <a href="#" class="text-coffee-muted hover:text-coffee-accent transition"><i class="ph ph-facebook-logo text-xl"></i></a>
                </div>
            </div>
            <div class="text-center mt-8 text-coffee-muted text-xs">
                &copy; Batangas State University Library Reservation and Utility Ordering System. All rights reserved. | Not affiliated with Wayne Enterprises.
            </div>
        </footer>
    </div>

    <script>
        // --- THEME LOGIC ---
        const applyTheme = (isDark) => {
            const root = document.documentElement;
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            // Theme Variables
            const themes = {
                bsu: { '--bg-primary': '#FFFFFF', '--bg-panel': '#F9FAFB', '--text-accent': '#C00000', '--text-main': '#1F2937', '--text-muted': '#6B7280', '--border-color': '#E5E7EB' },
                dark: { '--bg-primary': '#1C1613', '--bg-panel': '#2A221E', '--text-accent': '#C59D60', '--text-main': '#D6C0A0', '--text-muted': '#9CA3AF', '--border-color': '#382D24' }
            };

            const theme = isDark ? themes.dark : themes.bsu;
            Object.entries(theme).forEach(([key, val]) => root.style.setProperty(key, val));
            
            document.body.classList.toggle('dark-theme', isDark);
            sunIcon.style.display = isDark ? 'none' : 'block';
            moonIcon.style.display = isDark ? 'block' : 'none';
            
            // Fix svg divider color dynamically
            const divider = document.querySelector('.theme-fill');
            if(divider) divider.style.color = theme['--bg-primary'];
        };

        const toggleTheme = () => {
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'bsu' : 'dark');
            applyTheme(!isDark);
        };

        // --- MOBILE MENU ---
        const menuBtn = document.getElementById('mobile-menu-btn');
        const closeMenuBtn = document.getElementById('close-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const toggleMenu = () => {
            if (mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open'); mobileMenu.classList.add('closed');
            } else {
                mobileMenu.classList.remove('closed'); mobileMenu.classList.add('open');
            }
        };
        if(menuBtn) menuBtn.addEventListener('click', toggleMenu);
        if(closeMenuBtn) closeMenuBtn.addEventListener('click', toggleMenu);

        // --- ANNOUNCEMENT FETCH LOGIC (UPDATED) ---
        function loadAnnouncements() {
            const section = document.getElementById('announcements');
            const container = document.getElementById('announcements-container');

            fetch('announce_api.php')
                .then(response => {
                    if (!response.ok) { throw new Error("Server Error"); }
                    return response.text();
                })
                .then(text => {
                    try { return JSON.parse(text); } 
                    catch (e) { throw new Error("Invalid Data"); }
                })
                .then(data => {
                    container.innerHTML = ''; 
                    
                    // IF NO DATA: Ensure section stays hidden
                    if(!data || data.length === 0) {
                        section.classList.add('hidden');
                        return;
                    }

                    // IF DATA EXISTS: Remove hidden class
                    section.classList.remove('hidden');

                    data.forEach(item => {
                        let icon = 'ph-megaphone';
                        let colorClass = 'text-coffee-accent';
                        let borderClass = 'border-coffee-accent';
                        let bgClass = 'bg-coffee-accent/5';

                        if(item.type === 'wifi') { icon = 'ph-wifi-high'; } 
                        else if (item.type === 'promo') { icon = 'ph-sparkle'; colorClass = 'text-blue-400'; borderClass = 'border-blue-500'; bgClass = 'bg-blue-500/5'; } 
                        else if (item.type === 'alert') { icon = 'ph-warning-circle'; colorClass = 'text-red-400'; borderClass = 'border-red-500'; bgClass = 'bg-red-500/5'; }

                        const dateStr = new Date(item.created_at).toLocaleDateString();

                        const card = document.createElement('div');
                        card.className = `bg-coffee-dark/50 p-5 rounded-xl border-l-4 ${borderClass} border-y border-r border-coffee-border/50 hover:bg-coffee-dark transition group relative overflow-hidden scroll-fade-in`;
                        card.innerHTML = `
                            <div class="absolute top-0 right-0 w-16 h-16 ${bgClass} rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-opacity-20"></div>
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="bg-coffee-panel p-3 rounded-lg ${colorClass} border border-coffee-border flex-shrink-0">
                                    <i class="ph ${icon} text-2xl"></i>
                                </div>
                                <div class="w-full">
                                    <h3 class="font-bold text-coffee-text text-base mb-1">${item.title}</h3>
                                    <p class="text-xs text-coffee-muted leading-relaxed">"${item.content}"</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold ${colorClass} opacity-70">
                                            <i class="ph ph-calendar-check"></i> Posted: ${dateStr}
                                        </div>
                                        <span class="text-[9px] bg-coffee-panel px-2 py-0.5 rounded border border-coffee-border text-coffee-muted uppercase">${item.type}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.appendChild(card);
                        setTimeout(() => card.classList.add('visible'), 100);
                    });
                })
                .catch(err => {
                    // ON ERROR: Hide the section so user sees nothing broken
                    section.classList.add('hidden');
                    console.error("Announcement Error:", err);
                });
        }

        // --- INIT ---
        window.onload = function () {
            // Theme Init
            const savedTheme = localStorage.getItem('theme') || 'bsu';
            applyTheme(savedTheme === 'dark');
            document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
            
            // Scroll Animation Observer
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }, parseInt(entry.target.getAttribute('data-delay') || '0', 10));
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.scroll-fade-in').forEach(el => observer.observe(el));

            // Load Database Data
            loadAnnouncements();
        };
    </script>
</body>
</html>