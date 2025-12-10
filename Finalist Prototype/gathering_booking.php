<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Reservation - BSU</title>
    
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
        .dark-theme .form-container { background-color: var(--bg-panel) !important; border: 1px solid var(--border-color); }
        .dark-theme .form-container input, .dark-theme .form-container select { background-color: var(--bg-primary) !important; border-color: var(--border-color) !important; color: var(--text-main) !important; }
        .dark-theme .form-container input::placeholder { color: var(--text-muted) !important; }
        .dark-theme .form-container .text-gray-500 { color: var(--text-muted) !important; }
        .dark-theme .form-container .bg-black\/20 { background-color: rgba(0,0,0,0.2) !important; }
        .dark-theme .form-container .border-coffee-bean { border-color: var(--border-color) !important; }
        .dark-theme .form-container .bg-coffee-dark { background-color: var(--bg-primary) !important; }
        .dark-theme .form-container .text-gray-400 { color: var(--text-muted) !important; }
        .dark-theme .form-container .pay-term-radio:checked + div { background-color: var(--text-accent); color: var(--bg-primary); border-color: var(--text-accent); }
        .dark-theme .form-container .equipment-checkbox:checked + div { border-color: var(--text-accent); background-color: rgba(197, 157, 96, 0.1); }
        .dark-theme .protocol-box { background-color: var(--bg-panel) !important; border-color: var(--border-color) !important; }
        .dark-theme #success-modal > div { background-color: var(--bg-panel) !important; border-color: var(--text-accent) !important; }
        
        /* FORM STYLES */
        .equipment-checkbox:checked + div { border-color: var(--text-accent); background-color: var(--text-accent); color: var(--bg-primary); }
        .pay-term-radio:checked + div { background-color: var(--text-accent); color: var(--bg-primary); border-color: var(--text-accent); font-weight: bold; }
        .input-error { color: #EF4444 !important; border-color: #EF4444 !important; }
    </style>
</head>
<body class="text-coffee-text font-sans antialiased flex flex-col min-h-screen" style="background-color: var(--bg-primary);">

    <header class="sticky top-0 z-50 bg-coffee-dark/90 backdrop-blur-sm shadow-xl h-20">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="1homepage.php" class="flex items-center gap-2 text-xl font-bold text-coffee-light tracking-wider font-serif hover:text-coffee-accent transition">
                <img src="https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png" alt="Batangas State University Logo" class="w-8 h-8">
                <span>Batangas State <span class="text-coffee-accent">University</span></span>
            </a>
            
            <div class="hidden lg:flex space-x-8 text-sm font-medium">
                <a href="1homepage.php" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">Home</a>
                <a href="1homepage.php#about" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">About</a>
                <a href="2menu_walkin.php" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">Menu</a>
            </div>

            <div class="flex items-center space-x-4">
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-coffee-panel transition">
                    <i id="sun-icon" class="ph ph-sun text-xl text-coffee-accent" style="display:none;"></i>
                    <i id="moon-icon" class="ph ph-moon text-xl text-coffee-accent"></i>
                </button>
                <a href="1homepage.php" class="hidden sm:inline-block px-5 py-2 text-sm font-bold rounded-full border-2 border-coffee-accent text-coffee-accent hover:bg-coffee-accent hover:text-coffee-dark transition duration-300 transform hover:scale-105 font-serif">
                    ← BACK TO HOME
                </a>
            </div>
        </nav>
    </header>

    <main class="flex-grow hero-bg">
        <div class="container mx-auto px-6 py-12">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-8">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl md:text-5xl font-serif font-bold text-coffee-text mb-2">Library Seat Reservation</h1>
                        <p class="text-coffee-muted text-sm">Book your spot. First come, first served.</p>
                    </div>

                    <div class="bg-coffee-panel p-6 md:p-8 rounded-2xl shadow-2xl border border-coffee-border form-container relative overflow-hidden">
                        <form action="process_gathering_booking.php" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10" id="reservationForm">

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-bold text-coffee-muted mb-2">Full Name (Lead)</label>
                                    <input type="text" id="full_name" name="full_name" class="w-full bg-coffee-dark border border-coffee-border rounded-lg p-3 text-coffee-text focus:border-coffee-accent outline-none text-sm" placeholder="Juan Dela Cruz" required>
                                </div>
                                <div>
                                    <label for="sr_code" class="block text-sm font-bold text-coffee-muted mb-2">SR-Code</label>
                                    <input type="text" id="sr_code" name="sr_code" class="w-full bg-coffee-dark border border-coffee-border rounded-lg p-3 text-coffee-text focus:border-coffee-accent outline-none text-sm" placeholder="e.g., 21-12345" required>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="email" class="block text-sm font-bold text-coffee-muted mb-2">Email</label>
                                    <input type="email" id="email" name="email" class="w-full bg-coffee-dark border border-coffee-border rounded-lg p-3 text-coffee-text focus:border-coffee-accent outline-none text-sm" placeholder="e.g., juan.delacruz@g.batstate-u.edu.ph" required>
                                </div>
                                <div>
                                    <label for="phone_number" class="block text-sm font-bold text-coffee-muted mb-2">Phone Number</label>
                                    <input type="tel" id="phone_number" name="phone_number" class="w-full bg-coffee-dark border border-coffee-border rounded-lg p-3 text-coffee-text focus:border-coffee-accent outline-none text-sm" placeholder="e.g., 09123456789" required>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <label for="reservation_date" class="block text-sm font-bold text-coffee-muted mb-2">Reservation Date</label>
                                    <input type="date" name="mission_date" id="reservation_date" class="w-full bg-coffee-dark border border-coffee-border rounded-lg p-3 text-coffee-text focus:border-coffee-accent outline-none text-sm" required>
                                    <style>.dark-theme input[type="date"] { color-scheme: dark; }</style>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label for="id_photo" class="block text-sm font-bold text-coffee-muted mb-2">Valid ID Photo</label>
                                    <input type="file" name="valid_id_image" id="id_photo" accept="image/*" class="w-full text-sm text-coffee-muted file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-coffee-accent file:text-coffee-dark hover:file:bg-coffee-panel hover:file:text-coffee-accent" required>
                                </div>
                            </div>

                            <div id="availability-display" class="hidden mt-4 text-center border-t border-coffee-border pt-4">
                                <!-- Capacity message will be populated by JS -->
                                <p id="daily-capacity-message" class="text-base font-bold"></p>
                            </div>

                            <div class="mt-6 border-t border-coffee-border pt-6">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="hidden" name="reservation_type" value="study">
                                    <input type="checkbox" id="terms-agree" name="terms_agree" class="mt-1 bg-coffee-dark border-coffee-border rounded text-coffee-accent focus:ring-coffee-accent" required>
                                    <span class="text-xs text-coffee-muted">
                                        I confirm that the information provided is accurate. I agree to the library's terms and conditions, including the first-come, first-serve policy and adherence to the daily capacity limit.
                                    </span>
                                </label>
                            </div>

                            <button type="submit" id="submit-btn" class="w-full py-4 bg-coffee-accent text-coffee-dark font-bold font-serif text-lg rounded-xl shadow-lg hover:bg-coffee-panel hover:text-coffee-accent transition hover:-translate-y-1 mt-6">SUBMIT RESERVATION</button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    
                    <div class="bg-coffee-panel p-6 rounded-xl border border-coffee-border shadow-lg protocol-box">
                        <div class="flex items-center gap-2 mb-4 border-b border-coffee-border pb-2">
                            <i class="ph ph-shield-warning text-xl text-red-400"></i>
                            <h3 class="font-serif font-bold text-coffee-text text-sm">Library Policies</h3>
                        </div>
                        <ul class="text-xs text-coffee-muted space-y-2 list-disc list-inside">
                            <li><span class="text-coffee-accent">First Come, First Serve:</span> Seats are allocated based on submission time until the daily limit is reached.</li>
                            <li><span class="text-coffee-accent">One Reservation Per Day:</span> Each student is allowed one reservation per day.</li>
                            <li>Maximum capacity is strictly enforced.</li>
                            <li>Your reservation is subject to approval.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="success-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm hidden">
        <div class="bg-coffee-panel border border-coffee-accent p-8 rounded-2xl shadow-2xl max-w-sm w-full text-center m-4">
            <h3 class="text-2xl font-serif font-bold text-coffee-text mb-2">Reservation Sent!</h3>
            <p class="text-coffee-muted text-sm mb-6">Your reservation request has been submitted for approval. Please check your email for confirmation.</p>
            <button onclick="window.location.href='1homepage.php'" class="w-full py-3 bg-coffee-accent text-coffee-dark font-bold rounded hover:bg-coffee-panel hover:text-coffee-accent transition">RETURN TO HOME</button>
        </div>
    </div>

    <script>
        // === 1. GLOBAL VARIABLES ===
        let dailySchedule = {};
        let dailyReservationsCount = 0;
        let dailyLimit = 0;

        // === 2. INITIALIZATION & EVENT LISTENERS ===
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('reservation_date');
            
            // Set min date and default value to today
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
            dateInput.value = today;

            // Theme setup
            const savedTheme = localStorage.getItem('theme') || 'bsu';
            applyTheme(savedTheme === 'dark');
            document.getElementById('theme-toggle').addEventListener('click', toggleTheme);

            // Event listener for date changes
            dateInput.addEventListener('input', handleDateChange);

            // Show success modal if redirected
            if (new URLSearchParams(window.location.search).get('status') === 'success') {
                document.getElementById('success-modal').classList.remove('hidden');
            } 
            
            // Initial check on page load
            handleDateChange({ target: dateInput });
        });

        // === 3. AVAILABILITY & VALIDATION LOGIC ===
        async function fetchDailyAvailability() {
            const date = document.getElementById('reservation_date').value;
            const availabilityDisplay = document.getElementById('availability-display');
            const dailyCapacityMessage = document.getElementById('daily-capacity-message');
            
            if (!date) {
                availabilityDisplay.classList.add('hidden');
                validateDailyBooking(); // Reset button state
                return;
            }
            
            availabilityDisplay.classList.remove('hidden');
            dailyCapacityMessage.innerText = 'Checking available spots...';
            dailyCapacityMessage.className = 'text-base font-bold text-coffee-accent animate-pulse';

            try {
                const response = await fetch(`check_availability.php?date=${date}&t=${new Date().getTime()}`);
                const data = await response.json();
                if (data.daily_limit !== undefined) {
                    // Pass the fetched data directly to the validation function
                    validateDailyBooking(false, data.daily_reservations_count, data.daily_limit);
                } else {
                    dailyCapacityMessage.innerText = 'Error loading daily capacity.';
                    validateDailyBooking(true); // Force disable on error
                }
            } catch (e) {
                dailyCapacityMessage.innerText = 'Network error loading daily capacity.';
                console.error('Fetch Error:', e);
                validateDailyBooking(true); // Force disable on error
            }
        }

        function handleDateChange(event) {
            const dateInput = event.target;
            const date = new Date(dateInput.value);
            const day = date.getUTCDay(); // Use getUTCDay to avoid timezone issues

            if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
                dateInput.classList.add('border-red-500');
                dateInput.value = ''; // Clear the invalid date
                validateDailyBooking(); // Update UI to show "SELECT DATE"
                alert('Reservations are not available on weekends. Please select a weekday.');
            } else {
                dateInput.classList.remove('border-red-500');
                // Only fetch availability if the date is valid (not a weekend)
                fetchDailyAvailability();
            }
        }

        function validateDailyBooking(forceDisable = false, currentBookings, limit) {
            const submitBtn = document.getElementById('submit-btn'); 
            const dailyCapacityMessage = document.getElementById('daily-capacity-message'); 
            const dateInput = document.getElementById('reservation_date'); 

            // Helper to set button state
            const setButtonState = (text, enabled, isError) => {
                submitBtn.innerText = text;
                submitBtn.disabled = !enabled;
                submitBtn.classList.toggle('cursor-not-allowed', !enabled);
                submitBtn.classList.toggle('opacity-50', !enabled);
                submitBtn.classList.toggle('bg-red-600', isError);
                submitBtn.classList.toggle('text-white', isError);
                submitBtn.classList.toggle('bg-coffee-accent', !isError);
                submitBtn.classList.toggle('text-coffee-dark', !isError);
            };

            if (forceDisable || !dateInput.value) {
                setButtonState("SELECT DATE", false, true);
                dailyCapacityMessage.parentElement.classList.add('hidden');
            } else if (currentBookings >= limit) {
                setButtonState("DAILY CAPACITY REACHED", false, true);
                dailyCapacityMessage.innerText = `No spots available for this date.`; 
                dailyCapacityMessage.className = 'text-base font-bold text-red-500'; 
            } else {
                setButtonState("SUBMIT RESERVATION", true, false);
                dailyCapacityMessage.innerText = `Available for booking: ${limit - currentBookings} spots remaining today.`;
                dailyCapacityMessage.className = 'text-base font-bold text-green-500';
            }
        }

        // === 4. UTILS & THEME ===
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
            sunIcon.style.display = isDark ? 'block' : 'none'; // Sun icon shows when it's dark, to switch to light
            moonIcon.style.display = isDark ? 'none' : 'block';
        };

        function formatTime(h) {
            if (h === 0) return "12 AM"; // Midnight
            if (h === 12) return "12 PM"; // Noon
            if (h > 12) return `${h - 12} PM`;
            return `${h} AM`;
        }

        const toggleTheme = () => {
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'bsu' : 'dark');
            applyTheme(!isDark);
        };
    </script>
</body>
</html>