document.addEventListener("DOMContentLoaded", function () {
    filterSelection("all");

    // Highlight active button
    const btnContainer = document.getElementById("sidebarFilterBtnContainer");
    const btns = btnContainer.getElementsByClassName("btn");
    for (let btn of btns) {
        btn.addEventListener("click", function () {
            let current = btnContainer.getElementsByClassName("active");
            if (current.length > 0) current[0].classList.remove("active");
            this.classList.add("active");
        });
    }
});

function filterSelection(category) {
    const cards = document.querySelectorAll(".filterDiv");
    category = category === "all" ? "" : category;
    cards.forEach(card => {
        card.classList.remove("show");
        if (card.classList.contains(category)) {
            card.classList.add("show");
        } else if (category === "") {
            card.classList.add("show");
        }
    });
    showLimitedProducts(document.getElementById("showSelect").value);
    updateNoResultsMessage(); // Tambahkan ini
}

function filterBySearch() {
    const input = document.getElementById("searchInput").value.toUpperCase();
    const cards = document.querySelectorAll(".filterDiv");
    cards.forEach(card => {
        const title = card.querySelector("h4")?.innerText?.toUpperCase() || "";
        if (title.includes(input)) {
            card.classList.add("show");
        } else {
            card.classList.remove("show");
        }
    });
    showLimitedProducts(document.getElementById("showSelect").value);
    updateNoResultsMessage(); // Tambahkan ini
}

function sortCards(ascending = true) {
    const container = document.getElementById("cardContainer");
    const items = Array.from(container.getElementsByClassName("shop-card"));

    items.sort((a, b) => {
        const nameA = a.querySelector("h4").innerText.toUpperCase();
        const nameB = b.querySelector("h4").innerText.toUpperCase();
        return ascending ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
    });

    items.forEach(item => container.appendChild(item));
}

function sortCardsAZ() {
    sortCards(true);
}

function sortCardsZA() {
    sortCards(false);
}

function toggleDropdown() {
    document.getElementById("dropdownContent").classList.toggle("show");
}

function filterDropdownItems() {
    const input = document.getElementById("dropdownSearchInput");
    const filter = input.value.toUpperCase();
    const div = document.getElementById("dropdownContent");
    const a = div.getElementsByTagName("a");

    for (let i = 0; i < a.length; i++) {
        const txt = a[i].textContent || a[i].innerText;
        a[i].style.display = txt.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    }
}
function sortProducts(order) {
    if (order === "az") sortCardsAZ();
    else if (order === "za") sortCardsZA();
}

function showLimitedProducts(limit) {
    const cards = document.querySelectorAll("#cardContainer .shop-card");
    let count = 0;

    cards.forEach(card => {
        if (!card.classList.contains("show")) {
            card.style.display = "none";
            return;
        }

        if (limit === "all" || count < parseInt(limit)) {
            card.style.display = "block";
            count++;
        } else {
            card.style.display = "none";
        }
    });
}

function updateNoResultsMessage() {
    const visible = document.querySelectorAll(".shop-card.show");
    const msg = document.getElementById("noResultsMsg");
    if (msg) msg.style.display = visible.length === 0 ? "block" : "none";
}

// Panggil di akhir filter dan search:
filterBySearch();
updateNoResultsMessage();

document.querySelectorAll('.btn-buy').forEach(button => {
  button.addEventListener('click', function (e) {
    e.stopPropagation(); // Agar klik tombol tidak ikut klik gambar
    const id = this.dataset.id;
    window.location.href = `checkout.html?id=${id}`;
  });
});

document.querySelectorAll('.btn-buy').forEach(button => {
    button.addEventListener('click', function (e) {
        e.stopPropagation(); // mencegah klik ke gambar
        const id = this.dataset.id;
        window.location.href = `checkout.html?id=${id}`;
    });
});
