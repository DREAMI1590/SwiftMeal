document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".tab");
  const cards = document.querySelectorAll(".promo-card");

  // ✅ Aktifkan filter berdasarkan localStorage saat halaman dimuat
  const presetFilter = localStorage.getItem("promoFilter") || "all";
  localStorage.removeItem("promoFilter"); // hanya dipakai sekali

  activateFilter(presetFilter);

  // ✅ Event listener untuk klik tab
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const filter = tab.dataset.filter;
      activateFilter(filter);
    });
  });

  // ✅ Fungsi untuk mengaktifkan tab dan filter kartu promo
  function activateFilter(filter) {
    // Update tab active
    tabs.forEach((tab) => {
      tab.classList.remove("active");
      if (tab.dataset.filter === filter) {
        tab.classList.add("active");
      }
    });

    // Tampilkan/sembunyikan kartu sesuai kategori
    cards.forEach((card) => {
      const category = card.dataset.category;
      if (filter === "all" || category === filter) {
        card.classList.remove("hide");
      } else {
        card.classList.add("hide");
      }
    });
  }
});
