<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Portal | Next Gen Careers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, #3b82f633, transparent),
                        radial-gradient(circle at bottom left, #8b5cf633, transparent);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans hero-gradient">

    <header class="fixed w-full top-0 z-50 px-4 py-3">
        <div class="max-w-7xl mx-auto glass rounded-2xl px-6 py-3 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <i class="fas fa-briefcase text-white"></i>
                </div>
                <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-indigo-600">
                    InternPortal
                </h1>
            </div>
            
            <nav class="hidden md:flex items-center space-x-8 font-medium">
                <a href="#" class="hover:text-blue-600 transition">Home</a>
                <a href="#about" class="hover:text-blue-600 transition">About</a>
                <a href="#services" class="hover:text-blue-600 transition">Services</a>
                <div class="h-6 w-[1px] bg-slate-200"></div>
                <a href="login.php" class="text-slate-600 hover:text-blue-600 transition">Login</a>
                <a href="registration.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    Register Now
                </a>
            </nav>
        </div>
    </header>

    <main class="pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8 text-center lg:text-left">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold tracking-wide border border-blue-100 uppercase">
                    🚀 Launch Your Career
                </span>
                <h2 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                    The Smart Way to <br/>
                    <span class="text-blue-600 italic">Get Hired.</span>
                </h2>
                <p class="text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Bridging the gap between ambitious students and industry leaders. One platform to manage applications, track progress, and grow professionally.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="registration.php" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold text-lg hover:bg-blue-700 transition-all transform hover:-translate-y-1 shadow-xl shadow-blue-200">
                        Get Started Today
                    </a>
                    <a href="#about" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold text-lg hover:bg-slate-50 transition shadow-sm">
                        Learn More
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="absolute -top-10 -left-10 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
                <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" 
                     alt="Team Working" 
                     class="rounded-[2.5rem] shadow-2xl border-8 border-white">
            </div>
        </div>
    </main>

    <section id="about" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 space-y-4">
                <h3 class="text-3xl md:text-4xl font-bold text-slate-900">Why choose our portal?</h3>
                <p class="text-slate-500 max-w-2xl mx-auto">We've built a professional ecosystem designed specifically for the modern recruitment landscape.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Verified Listings</h4>
                    <p class="text-slate-600">Every internship is manually verified by our team to ensure quality and legitimacy for students.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Real-time Tracking</h4>
                    <p class="text-slate-600">No more ghosting. Track your application status from "Reviewing" to "Hired" in real-time.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Direct Chat</h4>
                    <p class="text-slate-600">Bridge the communication gap with built-in messaging between candidates and HR teams.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-24 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h3 class="text-4xl font-bold mb-6">Built for everyone in the ecosystem.</h3>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="text-blue-400 text-xl pt-1"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <h5 class="text-xl font-semibold mb-1">For Students</h5>
                            <p class="text-slate-400">Easy CV uploads and a clean interface to discover dream roles.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="text-green-400 text-xl pt-1"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <h5 class="text-xl font-semibold mb-1">For Companies</h5>
                            <p class="text-slate-400">Efficiently manage hundreds of applicants with advanced filtering tools.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-800 p-8 rounded-3xl text-center">
                    <div class="text-4xl font-bold text-blue-500 mb-2">10k+</div>
                    <div class="text-slate-400 uppercase text-xs tracking-widest font-bold">Students</div>
                </div>
                <div class="bg-slate-800 p-8 rounded-3xl text-center">
                    <div class="text-4xl font-bold text-green-500 mb-2">500+</div>
                    <div class="text-slate-400 uppercase text-xs tracking-widest font-bold">Companies</div>
                </div>
                <div class="bg-slate-800 p-8 rounded-3xl text-center col-span-2">
                    <div class="text-4xl font-bold text-blue-500 mb-2">95%</div>
                    <div class="text-slate-400 uppercase text-xs tracking-widest font-bold">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-8">Ready to start?</h2>
            <a href="registration.php" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition inline-block mb-16">
                Create Account
            </a>
            <div class="flex justify-center space-x-8 mb-12">
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-xl"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-slate-400 hover:text-pink-600 transition text-xl"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-slate-400 hover:text-blue-700 transition text-xl"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="text-slate-400 hover:text-green-600 transition text-xl"><i class="fab fa-whatsapp"></i></a>
            </div>
            <p class="text-slate-400 text-sm">&copy; 2025 Internship Portal. Designed for the future of work.</p>
        </div>
    </footer>

</body>
</html>