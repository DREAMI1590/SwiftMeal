// ==== Fungsi Ganti Gambar Utama ====
document.addEventListener('DOMContentLoaded', function () {
    const thumbnails = document.querySelectorAll('.product-thumbnails img');
    const mainImage = document.getElementById('mainImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            // Ganti src gambar utama
            const newSrc = this.getAttribute('src');
            mainImage.setAttribute('src', newSrc);

            // Hapus kelas 'active' dari semua thumbnail
            thumbnails.forEach(img => img.classList.remove('active'));

            // Tambahkan kelas 'active' ke thumbnail yang diklik
            this.classList.add('active');
        });
    });

    // Tandai thumbnail pertama sebagai aktif saat awal
    if (thumbnails.length > 0) {
        thumbnails[0].classList.add('active');
    }

    // ==== Fungsi Tab Deskripsi dan Review ====
    const tabButtons = document.querySelectorAll('.product-tabs .tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const target = this.getAttribute('data-tab');

            // Nonaktifkan semua tab
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Aktifkan tab yang diklik
            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
    // ==== Fungsi Tombol Scroll Produk Serupa ====
    const leftBtn = document.getElementById('scrollLeft');
    const rightBtn = document.getElementById('scrollRight');
    const scrollContainer = document.getElementById('similarScroll');

    function scrollSimilar(direction) {
        const scrollAmount = 300; // Sesuaikan jika diperlukan
        scrollContainer.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }

    if (leftBtn && rightBtn && scrollContainer) {
        leftBtn.addEventListener('click', () => scrollSimilar(-1));
        rightBtn.addEventListener('click', () => scrollSimilar(1));
    }
    // Flash message lokal: sembunyikan lalu hapus
    setTimeout(() => {
        const localFlash = document.querySelector('.flash-message-local');
        if (localFlash) {
            localFlash.style.transition = 'opacity 0.3s ease';
            localFlash.style.opacity = '0';
            setTimeout(() => {
                localFlash.remove(); // Hapus elemen dari DOM
            }, 300); // Tunggu animasi selesai
        }
    }, 4000);
    document.querySelectorAll('.tab').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

            button.classList.add('active');
            document.getElementById(button.dataset.tab).classList.add('active');
        });
    });
});
