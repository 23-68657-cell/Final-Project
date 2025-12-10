<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bat Cave Cafe - Menu</title>
    
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
                    fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Cinzel', 'serif'], script: ['Playball', 'cursive'] },
                    backgroundImage: {
                        'leather-texture': "url('https://www.transparenttextures.com/patterns/black-scales.png')",
                        'paper-texture': "url('https://www.transparenttextures.com/patterns/cream-paper.png')",
                        'spine-texture': "url('https://www.transparenttextures.com/patterns/dark-leather.png')",
                    },
                }
            }
        }
    </script>
    <style>
        :root { --bg-primary: #FFFFFF; --bg-panel: #F9FAFB; --text-accent: #C00000; --text-main: #1F2937; --text-muted: #6B7280; --border-color: #E5E7EB; transition: background-color 0.4s, color 0.4s; }
        .dark-theme { --bg-primary: #1C1613; --bg-panel: #2A221E; --text-accent: #C59D60; --text-main: #D6C0A0; --text-muted: #9CA3AF; --border-color: #382D24; }
        
        .dark-theme header { background-color: var(--bg-primary) !important; }
        .dark-theme header a, .dark-theme .text-coffee-light, .dark-theme .text-white { color: var(--text-main) !important; }
        .dark-theme .text-coffee-accent { color: var(--text-accent) !important; }
        .dark-theme footer { background-color: var(--bg-panel) !important; border-top-color: var(--border-color) !important; }
        #mobile-menu { transition: transform 0.3s ease-in-out; background-color: var(--bg-primary) !important; }
        #mobile-menu.open { transform: translateX(0); }
        #mobile-menu.closed { transform: translateX(100%); }
        
        body { overflow: hidden; }

        /* --- 3D BOOK STYLES --- */
        .menu-page-bg {
            background-color: var(--bg-primary);
            display: grid;
            grid-template-columns: 1fr 380px;
            height: calc(100vh - 80px);
            overflow: hidden;
        }
        
        .book { 
            position: relative; 
            width: 80vw; max-width: 900px; 
            height: 90%; max-height: 700px; 
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
        
        /* THE PAPER */
        .paper { 
            position: absolute; width: 50%; height: 100%; 
            top: 0; left: 50%; 
            transform-origin: left center; 
            transform-style: preserve-3d; 
            transition: transform 1.4s cubic-bezier(0.15, 0.25, 0.25, 1); 
            z-index: 1; 
        }
        
        .paper.flipped { 
            transform: rotateY(-180deg); 
            z-index: 50 !important; 
        }

        /* --- PAPER FACES (FRONT/BACK) --- */
        .front, .back { 
            position: absolute; width: 100%; height: 100%; 
            top: 0; left: 0;
            backface-visibility: hidden; 
            box-sizing: border-box; 
            overflow-y: auto; overflow-x: hidden; 
            
            /* SOLID COLORS (No Transparency) */
            background-color: #F5E6D3; 
            background-image: var(--tw-backgroundImage-paper-texture);
            
            color: #1f2937;
            border: 1px solid #D7C4A5;
            /* Add inner shadow for depth */
            box-shadow: inset 3px 0px 5px -2px rgba(0,0,0,0.1);
        }

        .front { 
            z-index: 2; 
            transform: rotateY(0deg);
            border-radius: 0 5px 5px 0;
        }
        
        .back { 
            z-index: 1; 
            transform: rotateY(180deg); 
            border-radius: 5px 0 0 5px;
            box-shadow: inset -3px 0px 5px -2px rgba(0,0,0,0.1);
        }

        /* Visibility Fixes */
        .paper.flipped .front { visibility: hidden; transition: visibility 0s linear 0.7s; } 
        .paper:not(.flipped) .back { visibility: hidden; transition: visibility 0s linear 0.7s; }

        /* --- CONTENT CONTAINER --- */
        .paper-content {
            height: 100%;
            width: 100%;
            overflow-y: auto;
            padding: 2rem;
        }

        /* --- COVER STYLES --- */
        .cover-front {
            background: linear-gradient(to right, #83181b, #A41E22);
            border: none !important;
            border-left: 10px solid #83181b !important;
            color: #FFFFFF;
            box-shadow: inset -4px 0 8px rgba(0,0,0,0.2), 0 0 0 2px #C00000, 0 0 0 4px #83181b !important;
            /* Reset the default page shadow */
            box-shadow: inset -4px 0 8px rgba(0,0,0,0.2), 0 0 0 2px #C00000, 0 0 0 4px #83181b !important;
        }
        
        .back-cover-texture {
            background-color: #A41E22;
            border: none !important;
            border-right: 10px solid #83181b !important;
            box-shadow: inset 2px 0 5px rgba(0,0,0,0.1) !important;
            /* Text Color Fix for Back Cover */
            color: #FFFFFF;
        }

        /* Typography & Scrollbar */
        .magic-text { background: linear-gradient(45deg, #C59D60, #FFE5B4, #C59D60); -webkit-background-clip: text; background-clip: text; color: transparent; text-shadow: 0 0 20px rgba(197, 157, 96, 0.3); animation: shimmer 3s infinite; }
        .category-title { font-family: 'Cinzel', serif; font-weight: 900; color: #1F1916; font-size: 1.5rem; border-bottom: 2px solid rgba(197, 157, 96, 0.5); padding-bottom: 0.5rem; margin-bottom: 1.5rem; text-align: center; text-transform: uppercase; letter-spacing: 0.1em; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; } 
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #C59D60; border-radius: 2px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
        
        .menu-item-dynamic { transition: all 0.2s; border-bottom: 1px dashed rgba(197, 157, 96, 0.3); padding-bottom: 12px; margin-bottom: 12px; }
        .menu-item-dynamic:last-child { border-bottom: none; }
        .menu-item-dynamic:hover { background-color: rgba(197, 157, 96, 0.1); transform: translateX(2px); border-radius: 4px; padding-left: 4px; }
        
        .price-tag { color: #C59D60; font-weight: 700; }

        /* Navigation Buttons */
        .nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(28, 22, 19, 0.9); color: #C59D60; border: 1px solid #C59D60; width: 3.5rem; height: 3.5rem; border-radius: 50%; display: flex; align-items: center; cursor: pointer; transition: all 0.3s ease; opacity: 0; pointer-events: none; z-index: 50; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .nav-btn:hover { background-color: #C59D60; color: #1C1613; transform: translateY(-50%) scale(1.1); box-shadow: 0 0 25px #C59D60; }
        #prev-btn { left: 5%; } #next-btn { right: 5%; }
        
        @media (max-width: 768px) {
            .book { width: 95vw; height: 85vh; transform: translateX(0) !important; }
            .paper { width: 100%; left: 0; }
            .book-spine { display: none; } 
            .nav-btn { width: 3rem; height: 3rem; left: 1rem !important; right: 1rem !important; }
            .paper.flipped { transform: rotateY(0); } 
            .paper.flipped .front { display: none; visibility: hidden; }
            .paper.flipped .back { display: block; visibility: visible; transform: rotateY(0); }
        }
    </style>
    <style>
        .input-field { background-color: var(--bg-panel); border: 1px solid var(--border-color); color: var(--text-main); }
        .input-field:focus { outline: none; border-color: var(--text-accent); }
        .pay-term-radio:checked + div { background-color: var(--text-accent); color: var(--bg-primary); border-color: var(--text-accent); font-weight: bold; }
    </style>
</head>
<body class="text-coffee-text font-sans antialiased relative" style="background-color: var(--bg-primary);">
    
    <div id="image-modal" class="hidden fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-[90vh] w-full flex items-center justify-center">
            <img id="modal-img" src="" alt="Food Preview" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl border-2 border-coffee-accent" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-coffee-accent transition text-4xl font-bold">&times;</button>
            <h3 id="modal-caption" class="absolute -bottom-12 left-0 w-full text-center text-coffee-accent font-serif text-xl tracking-wider bg-black/50 p-2 rounded"></h3>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-coffee-panel rounded-lg shadow-2xl p-8 max-w-sm w-full text-center relative border-2 border-coffee-accent transform scale-95 transition-all duration-300">
            <div class="text-green-500 mb-4">
                <i class="ph-check-circle text-6xl"></i>
            </div>
            <h2 class="text-2xl font-serif font-bold text-coffee-text mb-2">Order Placed!</h2>
            <p class="text-coffee-muted mb-6">Your order has been successfully submitted. You will receive an email confirmation shortly.</p>
            <button onclick="closeSuccessModal(true)" class="w-full py-2 bg-coffee-accent text-coffee-dark font-bold rounded-lg hover:bg-white transition">
                OK
            </button>
        </div>
    </div>

    <div id="app" class="min-h-screen flex flex-col">

        <header class="sticky top-0 z-[60] bg-coffee-dark/90 backdrop-blur-sm shadow-xl h-20">
            <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
                <a href="1homepage.php" class="flex items-center gap-2 text-xl font-bold text-coffee-text tracking-wider font-serif hover:text-coffee-accent transition">
                    <img src="https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png" alt="Batangas State University Logo" class="w-8 h-8">
                    <span>Batangas State <span class="text-coffee-accent">University</span></span>
                </a>
                
                <div class="hidden lg:flex space-x-8 text-sm font-medium">
                    <a href="1homepage.php" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">Home</a>
                    <a href="1homepage.php#about" class="nav-link text-coffee-text hover:text-coffee-accent transition duration-300">About</a>
                    <a href="2menu_walkin.php" class="nav-link text-coffee-accent transition duration-300">Menu</a>
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
            <a href="1homepage.php" class="text-2xl font-serif text-coffee-text hover:text-coffee-accent mobile-link">Home</a>
            <a href="1homepage.php#about" class="text-2xl font-serif text-coffee-text hover:text-coffee-accent mobile-link">About</a>
            <a href="2menu_walkin.php" class="text-2xl font-serif text-coffee-accent hover:text-coffee-text mobile-link">Menu</a>
        </div>

        <main class="flex-grow">
            <form id="walkin-order-form" action="process_walkin_order.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="order_details" id="order_details_input" value="[]">
                <div id="view-menu" class="menu-page-bg">
                    <div class="relative flex items-center justify-center h-full">
                        <div class="book" id="book">
                            <div class="book-spine"></div>
                        </div>
                        <button id="prev-btn" type="button" class="nav-btn">
                            <i class="ph ph-caret-left text-3xl"></i>
                        </button>
                        <button id="next-btn" type="button" class="nav-btn">
                            <i class="ph ph-caret-right text-3xl"></i>
                        </button>
                    </div>

                    <div class="bg-coffee-panel h-full flex flex-col border-l border-coffee-border">
                        <div class="p-6 border-b border-coffee-border">
                            <h2 class="text-2xl font-serif font-bold text-coffee-text">Your Order</h2>
                            <p class="text-xs text-coffee-muted">Review items and place your order.</p>
                        </div>
                        <div id="cart-items" class="flex-grow p-6 space-y-3 overflow-y-auto custom-scrollbar">
                            <div id="cart-empty-msg" class="text-center text-coffee-muted py-10">Your cart is empty.</div>
                        </div>
                        <div class="p-6 border-t border-coffee-border space-y-4 bg-coffee-dark">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-coffee-muted">Total</span>
                                <span id="cart-total" class="text-2xl font-bold text-coffee-accent">₱0.00</span>
                            </div>
                            <div class="space-y-3">
                                <input type="text" name="full_name" placeholder="Full Name" class="w-full p-2 rounded-md input-field text-sm" required>
                                <input type="email" name="email" placeholder="Email Address" class="w-full p-2 rounded-md input-field text-sm" required>
                                
                                <select name="payment_method" id="payment_method" class="w-full p-2 rounded-md input-field text-sm" required onchange="handlePaymentChange()">
                                    <option value="" disabled selected>Select Payment Method</option>
                                    <option value="cash">Cash at Counter</option>
                                    <option value="gcash">G-Cash</option>
                                    <option value="maya">Maya</option>
                                </select>

                                <div id="online-payment-fields" class="hidden space-y-2 pt-2 border-t border-coffee-border">
                                     <div class="flex items-center gap-2">
                                        <div class="bg-white p-1 rounded w-16 h-16 flex-shrink-0 flex items-center justify-center">
                                            <img id="qr-image" src="" alt="QR" class="w-full h-full object-contain">
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white text-xs" id="qr-label">Scan to Pay</h4>
                                            <p class="text-[10px] text-gray-400" id="bank-details"></p>
                                        </div>
                                    </div>
                                    <input type="text" name="reference_number" id="ref_number" placeholder="Reference Number" class="w-full p-2 rounded-md input-field text-sm">
                                    <div>
                                        <label class="block text-[10px] text-coffee-accent mb-1">Proof of Transaction</label>
                                        <input type="file" name="proof_image" id="proof_upload" accept="image/*" class="w-full text-xs text-gray-400 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-coffee-accent file:text-coffee-dark hover:file:bg-white">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 bg-coffee-accent text-coffee-dark font-bold rounded-lg shadow-lg hover:bg-white transition flex items-center justify-center gap-2">
                                <i class="ph ph-paper-plane-tilt text-lg"></i>
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        // --- Lightbox Logic ---
        function openImageModal(src, caption) {
            if(!src || src === 'undefined' || src === '') return;
            const modal = document.getElementById('image-modal');
            const modalImg = document.getElementById('modal-img');
            const modalCaption = document.getElementById('modal-caption');
            
            modalImg.src = src;
            modalCaption.innerText = caption;
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 10);
        }

        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('modal-img').src = '';
            }, 300);
        }

        // --- Success Modal Logic ---
        function showSuccessModal() {
            const modal = document.getElementById('success-modal');
            const modalContent = modal.querySelector('div');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
        }

        function closeSuccessModal(reload = false) {
            const modal = document.getElementById('success-modal');
            const modalContent = modal.querySelector('div');
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                // Redirect to the clean page without the status parameter
                if (reload) window.location.href = window.location.pathname;
            }, 300);
        }

        // --- Data & Category Structure (Connected to DB Logic) ---
        let dbMenuItems = [];
        let bookSheetsData = [];
        let orderCart = {}; // { itemId: quantity }
        
        const categoryMap = {
            'Page 1: Uniforms': ['uniforms'],
            'Page 2: Accessories': ['accessories'],
            'Page 3: School Supplies': ['school_supplies'],
            'Page 4: Merchandise': ['merchandise']
        };

        function loadMenuData() {
            fetch('menu_api.php')
                .then(res => res.json())
                .then(data => {
                    dbMenuItems = data; 
                    constructBookFromDB(); 
                })
                .catch(err => console.error("Failed to load menu:", err));
        }

        function constructBookFromDB() {
            bookSheetsData = [
                { front: { type: 'cover', title: "BatStateU<br>Store" }, back: { type: 'featured', title: "Featured Item", itemId: 'featured' } }
            ];

            const pages = Object.entries(categoryMap);
            for (let i = 0; i < pages.length; i += 2) {
                const frontCat = pages[i]; 
                const backCat = pages[i+1]; 

                const sheet = { front: null, back: null };

                if (frontCat) {
                    const items = dbMenuItems.filter(item => frontCat[1].includes(item.category));
                    sheet.front = { type: 'list', title: frontCat[0], items: items };
                } else {
                    sheet.front = { type: 'end', title: 'Notes' };
                }

                if (backCat) {
                    const items = dbMenuItems.filter(item => backCat[1].includes(item.category));
                    sheet.back = { type: 'list', title: backCat[0], items: items };
                } else {
                    sheet.back = { type: 'cover-back', title: 'End' };
                }
                
                bookSheetsData.push(sheet);
            }
            
            bookSheetsData.push({ front: { type: 'end', title: 'The End' }, back: { type: 'cover-back', title: '' } });

            initializeMenu(); 
        }

        function handlePaymentChange() {
            const method = document.getElementById('payment_method').value;
            const onlineFields = document.getElementById('online-payment-fields');
            const refInput = document.getElementById('ref_number');
            const proofInput = document.getElementById('proof_upload');

            if (method === 'gcash' || method === 'maya') {
                onlineFields.classList.remove('hidden');
                refInput.required = true;
                proofInput.required = true;

                const qrImg = document.getElementById('qr-image');
                const label = document.getElementById('qr-label');
                const details = document.getElementById('bank-details');
                
                if(method === 'gcash') { 
                    qrImg.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=GCash'; 
                    label.innerText='GCash'; 
                    details.innerText='0917-123-4567'; 
                }
                if(method === 'maya') { 
                    qrImg.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Maya'; 
                    label.innerText='Maya'; 
                    details.innerText='0918-123-4567'; 
                }

            } else {
                onlineFields.classList.add('hidden');
                refInput.required = false;
                proofInput.required = false;
            }
        }

        // --- HTML RENDERING ---
        function renderPageContent(page, is_front) {
            const inner_content_class = page.type === 'list' 
                ? 'paper-content custom-scrollbar' 
                : 'h-full flex flex-col justify-center items-center text-center p-6 w-full';

            if (page.type === 'cover') {
                return `<div class="cover-front ${inner_content_class}">
                    <div class="h-full flex flex-col justify-center items-center text-white">
                        <img src="https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png" alt="BatStateU Logo" class="w-32 h-32 mb-6">
                        <h1 class="text-5xl md:text-6xl font-serif font-bold text-center mb-4">${page.title}</h1>
                        <p class="uppercase tracking-[0.3em] text-xs">Official Merchandise</p>
                        <div class="mt-12 text-white text-sm animate-bounce">Click to Open</div>
                    </div></div>`;
            } 
            else if (page.type === 'featured') {
                const item = dbMenuItems.find(i => i.category === "merchandise") || dbMenuItems[0] || { name: 'University Hoodie', description: 'Official Red-Tailed Hawk Hoodie', price_solo: 850 };
                return `<div class="back inside-cover ${inner_content_class} p-8" style="background-color: #F5E6D3;">
                    <div class="w-full h-full flex flex-col justify-center items-center text-center text-coffee-dark">
                    <h2 class="text-2xl font-serif font-bold text-[#1C1613] mb-4 uppercase tracking-widest">${page.title}</h2>
                    <div class="w-full flex justify-center my-6">
                        <div class="w-48 h-48 rounded-full bg-white border-4 border-red-800 flex items-center justify-center p-4 shadow-lg">
                            <img src="${item.image_path || 'https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png'}" class="w-full h-full object-contain">
                        </div>
                    </div>
                    <h3 class="text-3xl font-serif font-bold text-[#1C1613] mb-2">${item.name}</h3>
                    <p class="text-gray-600 italic mb-4 max-w-xs">${item.description}</p>
                    <div class="text-4xl font-bold text-red-800 mt-2">₱${item.price_solo}</div>
                    </div></div>`;
            }
            else if (page.type === 'list') {
                const displayTitle = page.title.split(': ')[1] || page.title;
                const listHtml = page.items.map(item => {
                    let priceDisplay = `₱${item.price_solo}`;
                    if (parseFloat(item.price_group) > 0) {
                        priceDisplay = `₱${item.price_solo} (S) / ₱${item.price_group} (G)`;
                    }
                    
                    let sizeHtml = '';
                    let cartControls = '';

                    if (item.sizes && item.sizes.trim() !== '') {
                        const sizes = item.sizes.split(',').map(s => s.trim());
                        const sizeOptions = sizes.map(s => `<option value="${s}">${s}</option>`).join('');
                        sizeHtml = `
                            <div class="mt-2">
                                <select id="size-select-${item.id}" class="w-full text-xs p-1 rounded border-coffee-border bg-coffee-panel text-coffee-text focus:border-coffee-accent focus:ring-0">
                                    ${sizeOptions}
                                </select>
                            </div>`;
                        cartControls = `<div class="flex items-center justify-between mt-auto bg-coffee-dark p-1 rounded-lg border border-coffee-accent/50 w-24 flex-shrink-0">
                            <button type="button" onclick="updateCartItem(${item.id}, -1, true)" class="w-7 h-7 flex items-center justify-center bg-coffee-panel text-coffee-accent hover:bg-coffee-accent hover:text-white rounded transition font-bold">-</button>
                            <span class="font-bold text-white text-sm" id="qty-${item.id}">0</span>
                            <button type="button" onclick="updateCartItem(${item.id}, 1, true)" class="w-7 h-7 flex items-center justify-center bg-coffee-panel text-coffee-accent hover:bg-coffee-accent hover:text-white rounded transition font-bold">+</button>
                        </div>`;
                    } else {
                        cartControls = `<div class="flex items-center justify-between mt-auto bg-coffee-dark p-1 rounded-lg border border-coffee-accent/50 w-24 flex-shrink-0">
                            <button type="button" onclick="updateCartItem(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center bg-coffee-panel text-coffee-accent hover:bg-coffee-accent hover:text-white rounded transition font-bold">-</button>
                            <span class="font-bold text-white text-sm" id="qty-${item.id}">0</span>
                            <button type="button" onclick="updateCartItem(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center bg-coffee-panel text-coffee-accent hover:bg-coffee-accent hover:text-white rounded transition font-bold">+</button>
                        </div>`;
                    }

                    return `
                    <div class="menu-item-dynamic flex items-center justify-between gap-3 group">
                        <div class="flex-grow">
                            <div class="flex justify-between items-baseline">
                                <h4 class="font-bold text-gray-900 group-hover:text-red-800 transition text-base leading-tight">${item.name}</h4>
                                <span class="price-tag text-sm ml-2 whitespace-nowrap">${priceDisplay}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 leading-snug">${item.description}</p>
                            ${sizeHtml}
                        </div>
                        ${cartControls}
                    </div>`;
                }).join('');

                return `<div class="${inner_content_class}">
                    <h2 class="category-title">${displayTitle}</h2>
                    <div class="space-y-2">
                        ${listHtml}
                    </div>
                </div>`;
            }
            else if (page.type === 'cover-back') {
                return `<div class="back-cover-texture ${inner_content_class}"><div class="h-full flex flex-col justify-center items-center text-center p-6 text-white">
                    <img src="https://batstate-u.edu.ph/wp-content/uploads/2022/05/BatStateU-NEU-Logo.png" alt="BatStateU Logo" class="w-24 h-24 mb-4 opacity-80">
                    <h2 class="text-2xl font-serif font-bold">End of Catalogue</h2>
                    <div class="mt-12"><p class="text-xs opacity-70">Batangas State University</p></div>
                </div></div>`;
            }
            else if (page.type === 'end') {
                 return `<div class="${inner_content_class}">
                    <div class="h-full flex flex-col items-center justify-center p-8 text-center text-gray-800">
                        <h2 class="text-2xl font-serif">Leading Innovations, Transforming Lives.</h2>
                    </div>
                 </div>`;
            }
            return '';
        }

        // --- Book Logic ---
        let book, prevBtn, nextBtn, papers;
        let currentLocation = 1;
        let numOfPapers = 0;
        let maxLocation = 1;
        
        function initializeMenu() {
            const bookContainer = document.getElementById('book');
            const spineHtml = `<div class="book-spine"></div>`;
            
            let papersHtml = '';
            bookSheetsData.forEach((sheetData, index) => {
                const zIndex = bookSheetsData.length - index;
                const frontContent = renderPageContent(sheetData.front, true);
                const backContent = renderPageContent(sheetData.back, false);
                
                papersHtml += `
                <div class="paper" id="p${index + 1}" style="z-index: ${zIndex}">
                    <div class="front">${frontContent}</div>
                    <div class="back">${backContent}</div>
                </div>`;
            });

            bookContainer.innerHTML = spineHtml + papersHtml;

            book = document.querySelector('#book');
            prevBtn = document.querySelector('#prev-btn');
            nextBtn = document.querySelector('#next-btn');
            papers = Array.from(document.querySelectorAll('.paper'));
            numOfPapers = papers.length;
            maxLocation = numOfPapers + 1;
            currentLocation = 1; 
            
            attachMenuControls();
            
            if (papers && papers.length > 0) {
                closeBook(true); 
                papers.forEach((paper, index) => {
                    paper.classList.remove("flipped"); 
                });
            }
        }

        function openBook() {
             book.style.transform = "translateX(0) scale(1)";
             prevBtn.style.opacity = '1'; nextBtn.style.opacity = '1';
             prevBtn.style.pointerEvents = 'auto'; nextBtn.style.pointerEvents = 'auto';
             prevBtn.style.display = 'flex'; 
        }

        function closeBook(isAtBeginning) {
            if (isAtBeginning) {
                book.style.transform = "translateX(-25%) scale(0.8)"; // Move left when closed at the front
            } else {
                book.style.transform = "translateX(25%) scale(0.8)"; // Move right when closed at the back
            }
            prevBtn.style.opacity = '0'; nextBtn.style.opacity = '0';
            prevBtn.style.pointerEvents = 'none'; nextBtn.style.pointerEvents = 'none';
        }

        function goNextPage() {
            if(currentLocation < maxLocation) {
                if(currentLocation === 1) openBook();
                const paperIndex = currentLocation - 1;
                const paper = papers[paperIndex];
                paper.classList.add("flipped");
                paper.style.zIndex = currentLocation + 10; 
                if(currentLocation === numOfPapers) closeBook(false);
                currentLocation++;
                updateNavButtons();
            }
        }

        function goPrevPage() {
            if(currentLocation > 1) {
                currentLocation--;
                if(currentLocation === 1) closeBook(true);
                if(currentLocation === numOfPapers) openBook();
                const paperIndex = currentLocation - 1;
                const paper = papers[currentLocation - 1];
                paper.classList.remove("flipped");
                paper.style.zIndex = numOfPapers - (currentLocation - 1);
                updateNavButtons();
            }
        }
        
        function updateNavButtons() {
            if (currentLocation <= 1) {
                prevBtn.style.display = 'none'; nextBtn.style.display = 'flex';
            } else if (currentLocation > 1 && currentLocation < numOfPapers) {
                prevBtn.style.display = 'flex'; nextBtn.style.display = 'flex';
            } else if (currentLocation >= numOfPapers) {
                prevBtn.style.display = 'none'; nextBtn.style.display = 'none';
            }
        }

        function attachMenuControls() {
            if (nextBtn) nextBtn.onclick = goNextPage;
            if (prevBtn) prevBtn.onclick = goPrevPage;
            
            if (papers && papers.length > 0) {
                papers.forEach((paper, index) => {
                    paper.onclick = (e) => {
                        if (e.target.closest('button') || e.target.closest('img') || e.target.closest('select')) return;
                        const clickedIndex = index + 1; 
                        if(!paper.classList.contains('flipped') && currentLocation === clickedIndex) {
                            goNextPage();
                        } else if(paper.classList.contains('flipped') && currentLocation - 1 === clickedIndex) {
                            goPrevPage();
                        }
                    };
                });
            }
        }

        function updateCartItem(id, delta, hasSize = false) {
            let cartKey = id;
            if (hasSize) {
                const sizeSelect = document.getElementById(`size-select-${id}`);
                if (!sizeSelect) {
                    console.error("Size select not found for item " + id);
                    return;
                }
                const selectedSize = sizeSelect.value;
                cartKey = `${id}_${selectedSize}`;
            }

            if (!orderCart[cartKey]) orderCart[cartKey] = 0;
            orderCart[cartKey] += delta;
            if (orderCart[cartKey] < 0) orderCart[cartKey] = 0;

            recalcCartTotals();
        }

        function recalcCartTotals() {
            let foodTotal = 0;
            let itemCount = 0;
            const finalOrder = [];
            const cartContainer = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('cart-empty-msg');
            cartContainer.innerHTML = '';

            // Reset all quantity displays on the page first
            document.querySelectorAll('[id^="qty-"]').forEach(el => el.innerText = '0');

            for (let [cartKey, qty] of Object.entries(orderCart)) {
                if (qty > 0) {
                    const keyParts = cartKey.split('_');
                    const id = keyParts[0];
                    const size = keyParts.length > 1 ? keyParts[1] : null;

                    const item = dbMenuItems.find(i => i.id == id);
                    if (item) {
                        const itemTotal = parseFloat(item.price_solo) * qty;
                        foodTotal += itemTotal;
                        itemCount += qty;
                        finalOrder.push({ id: id, qty: qty, name: item.name, size: size });

                        // Update quantity display in the book
                        const qtyEl = document.getElementById(`qty-${id}`);
                        if (qtyEl) {
                             // For items with sizes, we sum up all sizes to show a total quantity for the item.
                             let currentQty = parseInt(qtyEl.innerText) || 0;
                             qtyEl.innerText = currentQty + qty;
                        }

                        const cartItemEl = document.createElement('div');
                        cartItemEl.className = 'flex justify-between items-center text-sm border-b border-coffee-border/50 pb-2';
                        
                        const displayName = size ? `${item.name} (${size})` : item.name;

                        cartItemEl.innerHTML = `
                            <div>
                                <span class="font-bold text-coffee-text">${qty}x ${displayName}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-coffee-accent font-semibold">₱${itemTotal.toFixed(2)}</span>
                                <button type="button" onclick="updateCartItem('${cartKey}', -qty)" class="text-red-500 hover:text-red-400 text-xs"><i class="ph ph-trash"></i></button>
                            </div>
                        `;
                        cartContainer.appendChild(cartItemEl);
                    }
                }
            }

            if (itemCount > 0) {
                emptyMsg.style.display = 'none';
            } else {
                cartContainer.appendChild(emptyMsg);
                emptyMsg.style.display = 'block';
            }
            document.getElementById('cart-total').innerText = `₱${foodTotal.toFixed(2)}`;
            document.getElementById('order_details_input').value = JSON.stringify(finalOrder);
        }

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
        };

        const toggleTheme = () => {
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'bsu' : 'dark');
            applyTheme(!isDark);
        };
        
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

        window.onload = function () {
            const savedTheme = localStorage.getItem('theme') || 'bsu';
            applyTheme(savedTheme === 'dark');
            document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
            loadMenuData();

            if (new URLSearchParams(window.location.search).get('status') === 'success') {
                showSuccessModal();
            }
        };
    </script>
</body>
</html>