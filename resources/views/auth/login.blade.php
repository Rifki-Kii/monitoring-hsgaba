<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Homeschooling Group ABA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom gradient and animation */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-bg-alt {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .gradient-card {
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.95));
            backdrop-filter: blur(10px);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .btn-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full gradient-bg-alt opacity-10"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full gradient-bg-alt opacity-10"></div>
        <div class="absolute top-1/4 left-1/4 w-20 h-20 rounded-full bg-white opacity-5"></div>
        <div class="absolute bottom-1/4 right-1/4 w-20 h-20 rounded-full bg-white opacity-5"></div>
    </div>

    <!-- Main login container -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo and brand section -->
        <div class="text-center mb-8 floating">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white shadow-lg mb-4">
                <div class="relative">
                    <i class="fas fa-graduation-cap text-3xl text-indigo-600"></i>
                    <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-yellow-400"></div>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Homeschooling Group ABA</h1>
            <p class="text-indigo-100">Sistem Monitoring Akademik & Kedisiplinan</p>
        </div>

        <!-- Login card -->
        <div class="gradient-card rounded-2xl shadow-2xl overflow-hidden">
            <!-- Card header with decorative bar -->
            <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Masuk ke Akun Anda</h2>
                    <p class="text-gray-600 mt-1">Gunakan kredensial Anda untuk mengakses sistem</p>
                </div>

                <!-- Error message -->
                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Login form -->
                <form action="/login" method="POST">
                    @csrf
                    
                    <!-- Username field -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">
                            <i class="fas fa-user mr-2 text-indigo-500"></i>
                            Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-circle text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="username"
                                name="username" 
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 input-focus transition-colors"
                                placeholder="Masukkan username Anda"
                                required
                                autofocus>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Gunakan username yang diberikan administrator</p>
                    </div>

                    <!-- Password field -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                            <i class="fas fa-lock mr-2 text-indigo-500"></i>
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 input-focus transition-colors"
                                placeholder="Masukkan password Anda"
                                required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="text-gray-400 hover:text-gray-600" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500">Minimal 6 karakter</p>
                            <a href="#" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Lupa password?</a>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center mb-6">
                        <input 
                            type="checkbox" 
                            id="remember"
                            name="remember" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Submit button -->
                    <button 
                        type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md btn-transition flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk ke Sistem
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Atau</span>
                    </div>
                </div>

                <!-- Help & Support -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Butuh bantuan? 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Hubungi Administrator</a>
                    </p>
                    <div class="mt-4 flex justify-center space-x-4">
                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fas fa-envelope text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            <i class="fas fa-phone text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Card footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        &copy; {{ date('Y') }} Homeschooling Group ABA
                    </p>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Secure
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-indigo-100">
                <i class="fas fa-info-circle mr-1"></i>
                Sistem ini khusus untuk guru, staf, dan administrator Homeschooling Group ABA
            </p>
        </div>
    </div>

    <!-- JavaScript for interactive features -->
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Add focus effect to inputs
        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-indigo-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-indigo-200');
            });
        });

        // Form submission loading state
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
        });
        
        // Auto-focus username field on page load
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                usernameInput.focus();
            }
        });
    </script>
</body>
</html>