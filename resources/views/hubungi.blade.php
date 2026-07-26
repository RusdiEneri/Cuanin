@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-16">
    <!-- Hero Section -->
    <div class="bg-primary text-white py-16 relative overflow-hidden shadow-xl shadow-blue-900/10">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-primary"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Hubungi Kami</h1>
            <p class="text-blue-100 text-lg md:text-xl max-w-3xl mx-auto">
                Ada pertanyaan, saran, atau kendala? Kami senang mendengar dari Anda.
            </p>
        </div>
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12 max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            
            <!-- Info Kontak (Kiri) -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Informasi Kontak</h2>
                    <p class="text-gray-500 text-sm">Hubungi kami melalui salah satu channel di bawah ini.</p>
                </div>

                <!-- Email -->
                <a href="mailto:support@cuanin.id" class="flex items-start gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-primary transition duration-300 group">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-primary group-hover:text-white transition duration-300">
                        <i data-lucide="mail" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-0.5">Email</h4>
                        <p class="text-gray-500 text-sm">support@cuanin.id</p>
                    </div>
                </a>

                <!-- Lokasi -->
                <div class="flex items-start gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-0.5">Lokasi</h4>
                        <p class="text-gray-500 text-sm">Gresik, Jawa Timur, Indonesia</p>
                    </div>
                </div>

                <!-- Sosial Media -->
                <div class="flex items-start gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="share-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-0.5">Sosial Media</h4>
                        <p class="text-gray-500 text-sm">TikTok & Instagram: @Cuanin.id</p>
                    </div>
                </div>

                <!-- Jam Operasional -->
                <div class="flex items-start gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center flex-shrink-0">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-0.5">Jam Operasional</h4>
                        <p class="text-gray-500 text-sm">Senin - Jumat: 08.00 - 17.00 WIB</p>
                        <p class="text-gray-500 text-sm">Sabtu: 08.00 - 12.00 WIB</p>
                    </div>
                </div>
            </div>

            <!-- Form Kontak (Kanan) -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Kirim Pesan</h3>
                    <p class="text-gray-500 text-sm mb-6">Isi formulir di bawah ini dan kami akan membalas secepatnya.</p>

                    <form id="contactForm" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                <input type="text" id="contact_name" name="name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                    placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input type="email" id="contact_email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                    placeholder="nama@email.com">
                            </div>
                        </div>
                        <div>
                            <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subjek</label>
                            <input type="text" id="contact_subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                placeholder="Topik pesan Anda">
                        </div>
                        <div>
                            <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1.5">Pesan</label>
                            <textarea id="contact_message" name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none"
                                placeholder="Tuliskan pesan Anda di sini..."></textarea>
                        </div>
                        <button type="submit" id="contactSubmitBtn"
                            class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-5 h-5"></i>
                            Kirim Pesan
                        </button>
                    </form>

                    <!-- Success Message (hidden by default) -->
                    <div id="contactSuccess" class="hidden mt-6 bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-bold text-green-800 mb-1">Pesan Terkirim!</h4>
                        <p class="text-green-600 text-sm">Terima kasih sudah menghubungi kami. Kami akan segera membalas pesan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const form = document.getElementById('contactForm');
        const successMsg = document.getElementById('contactSuccess');
        const submitBtn = document.getElementById('contactSubmitBtn');

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                // Simulate sending
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Mengirim...';

                setTimeout(function () {
                    form.classList.add('hidden');
                    successMsg.classList.remove('hidden');
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 1500);
            });
        }
    });
</script>
@endpush
@endsection
