@extends('layouts.app')
@section('title', 'CaloriMate - About')
@section('content')
    <div class="bg-[#2d5016] min-h-screen flex items-center relative overflow-hidden">
        <div class="max-w-7xl mx-auto w-full px-8 py-20">
            <div class="grid grid-cols-2 gap-16 items-center">
                <!-- Left Section -->
                <div class="relative z-10">
                    <!-- Decorative circles -->
                    <div class="absolute -top-16 -left-16 w-24 h-24 bg-[#4a6b2a] rounded-full opacity-40"></div>
                    <div class="absolute -bottom-8 -left-8 w-16 h-16 bg-[#4a6b2a] rounded-full opacity-30"></div>
                    
                    <div class="relative">
                        <h2 class="text-white text-sm italic mb-2">about</h2>
                        <h1 class="text-white text-6xl font-bold mb-4">CaloriMate</h1>
                        <div class="border-b-4 border-white w-24 mb-6"></div>
                        <p class="text-white text-lg leading-relaxed mb-8 max-w-lg">
                            Aplikasi pemantau kalori dan gaya hidup sehat yang membantu remaja mengelola kebutuhan kalori,
                            memilih menu makanan, serta mengikuti program latihan sesuai kondisi tubuh. Dengan fitur ini,
                            CaloriMate mendukung pengguna dalam membangun kesadaran dan disiplin menuju hidup sehat.
                        </p>
                        <a href="{{ route('login.show') }}"
                            class="inline-block bg-white text-[#2d5016] px-10 py-3 rounded-full font-bold text-lg hover:bg-gray-100 transition shadow-lg">
                            Login
                        </a>
                    </div>
                    
                    <!-- Testimonial section -->
                    <div class="mt-20 flex items-start gap-4">
                        <img src="/images/mask-group.png" alt="Luhung Usluk Sikil"
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg flex-shrink-0">
                        <div>
                            <h3 class="text-white font-bold text-xl mb-2">Luhung Usluk Sikil</h3>
                            <p class="text-white text-sm leading-relaxed opacity-90">
                                sosok maskot CaloriMate yang dikenal suka "halu" tentang berbagai kisah imajinasinya. Kadang
                                ia tenggelam dalam dunia khayalannya sendiri, lalu tak henti-hentinya yapping soal "when yah
                                gweh" dan "bukankah ini my"
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Section - Mascot -->
                <div class="relative flex justify-center items-center h-[600px]">
                    <!-- Background shape - positioned behind mascot -->
                    <div class="absolute w-[420px] h-[420px] left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                        <img src="/images/group-54.png" alt="" class="w-full h-full object-contain">
                    </div>
                    
                    <!-- Small decorative circles -->
                    <div class="absolute top-10 left-8 w-20 h-20 bg-[#8ca66f] rounded-full opacity-50"></div>
                    <div class="absolute top-32 right-16 w-24 h-24 bg-[#6b8a4a] rounded-full opacity-40"></div>
                    
                    <!-- Mascot image - centered and larger -->
                    <div class="relative z-10">
                        <img src="/images/Avatar.png" alt="CaloriMate Mascot"
                            class="w-[480px] h-auto drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection