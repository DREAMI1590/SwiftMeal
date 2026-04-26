// Fungsi untuk menghapus item menu (jika interaktif)
document.addEventListener("DOMContentLoaded", function () {
  const removeButtons = document.querySelectorAll(".item-remove");

  removeButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const item = btn.closest("li");
      if (item) {
        item.remove(); // atau tampilkan modal konfirmasi
      }
    });
  });
});
