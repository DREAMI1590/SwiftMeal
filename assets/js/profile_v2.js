console.log("✅ profile.js version 1.1 loaded");

document.addEventListener("DOMContentLoaded", () => {
  const sidebarButtons = document.querySelectorAll(".sidebar-btn");
  const sections = document.querySelectorAll(".profile-section");
  const addNewBtn = document.querySelector(".address-card.add-new");

  // Navigasi sidebar
  sidebarButtons.forEach((btn) => {
    btn.addEventListener("click", () => {

      sidebarButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      const targetId = btn.getAttribute("data-target");
      sections.forEach((section) => {
        section.classList.remove("active");
        if (section.id === targetId) {
          section.classList.add("active");
        }
      });
    });
  });

  // Tambah alamat baru
  if (addNewBtn) {
    addNewBtn.addEventListener("click", () => {
      alert("Form tambah alamat akan ditampilkan di sini.");
    });
  }
});

function showAddressForm() {
  document.getElementById('address-form').style.display = 'block';
}

function hideAddressForm() {
  document.getElementById('address-form').style.display = 'none';
}
