<div x-data="globalToastData()" class="fixed bottom-6 right-6 z-[100] pointer-events-none font-sans">
    
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-10 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-10 opacity-0"
         class="bg-[#800000] text-white px-6 py-4 rounded-xl shadow-2xl border border-red-900/50 max-w-sm"
         style="display: none;">
        <p class="font-bold text-sm md:text-base leading-snug tracking-wide">
            <span x-text="downloadData.user"></span> sedang mengunduh <span x-text="downloadData.script"></span>
        </p>
    </div>
</div>

<script>
    // 1. Fungsi untuk MENEMBAK laporan ke server saat tombol diklik
    function logGlobalDownload(scriptTitle) {
        fetch('{{ route('log.download') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ script: scriptTitle })
        }).catch(err => console.error('Gagal lapor:', err));
    }

    // 2. Mesin Alpine.js untuk MENANGKAP sinyal dari server
    document.addEventListener('alpine:init', () => {
        Alpine.data('globalToastData', () => ({
            showToast: false,
            lastId: null,
            downloadData: { user: '', script: '' },
            
            init() {
                // Cek server setiap 3 detik
                setInterval(() => {
                    fetch('{{ route('check.download') }}')
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.id && data.id !== this.lastId) {
                                // Trik pintar: Abaikan data saat halaman baru dimuat
                                // Biar popup tidak tiba-tiba muncul saat pengunjung baru datang
                                if (this.lastId === null) {
                                    this.lastId = data.id;
                                    return; 
                                }
                                
                                // Kalau ada yang baru klik, munculkan!
                                this.lastId = data.id;
                                this.downloadData = data;
                                this.showToast = true;
                                
                                // Hilangkan otomatis setelah 4 detik
                                setTimeout(() => {
                                    this.showToast = false;
                                }, 4000);
                            }
                        })
                        .catch(err => {}); 
                }, 3000);
            }
        }));
    });
</script>