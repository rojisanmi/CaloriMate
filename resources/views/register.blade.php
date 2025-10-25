<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CaloriMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f5f1ed] min-h-screen relative overflow-hidden flex items-center justify-center">
    <!-- Decorative circles using actual images -->
    <img src="/images/ellipse-register.png" alt="" class="absolute top-8 right-24 w-28 h-28 z-0">
    <img src="/images/ellipse-register1.png" alt="" class="absolute bottom-0 right-0 w-56 h-56 z-0">
    <img src="/images/ellipse-register1.png" alt="" class="absolute top-1/2 -left-20 w-32 h-32 -translate-y-1/2 z-0">

    <!-- Back button -->
    <div class="absolute top-8 left-8 z-50">
        <a href="{{ route('home') }}"
            class="flex items-center justify-center w-12 h-12 bg-[#2d5016] rounded-lg hover:bg-[#3d6020] transition">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 flex items-center justify-center gap-0 max-w-7xl mx-auto px-8">
        <!-- Left Section - Mascot (Green Box) -->
        <div
            class="bg-[#2d5016] rounded-l-[40px] w-[480px] h-[680px] relative overflow-hidden flex items-end justify-center pb-0">
            <!-- Background decorative circles inside card -->
            <img src="/images/ellipse-register1.png" alt="" class="absolute top-16 left-8 w-24 h-24">
            <img src="/images/ellipse-register1.png" alt="" class="absolute bottom-20 right-12 w-20 h-20">

            <!-- Large background shape -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80">
                <img src="/images/group-54.png" alt="" class="w-full h-full object-contain">
            </div>

            <!-- Mascot -->
            <div class="relative z-10 flex justify-center items-end">
                <img src="/images/mascot-register.png" alt="CaloriMate Mascot" class="w-full h-auto max-w-[420px]">
            </div>
        </div>

        <!-- Right Section - Form (White Box) -->
        <div class="bg-white rounded-r-[40px] w-[580px] h-[680px] p-16 shadow-xl flex flex-col justify-center">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="/images/logo.png" alt="CaloriMate" class="h-12">
            </div>

            <h2 class="text-4xl font-bold text-[#2d5016] text-center mb-8">Register</h2>

            <form method="POST" action="{{ route('register.do') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label class="block">
                    <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
                </label>

                <label class="block">
                    <input name="username" placeholder="Username" value="{{ old('username') }}" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
                </label>

                <label class="block">
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
                </label>

                <label class="block">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 text-base focus:border-[#2d5016] focus:outline-none transition">
                </label>

                <div class="flex justify-center pt-8">
                    <button type="submit"
                        class="bg-[#2d5016] text-white px-14 py-3 rounded-full font-bold text-lg hover:bg-[#3d6020] transition shadow-lg">
                        Selanjutnya
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="absolute bottom-0 w-full bg-white py-3 text-center text-gray-600 text-sm border-t z-10">
        <p>©CaloriMate</p>
    </footer>
</body>

</html>