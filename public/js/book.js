const form = document.querySelector("form");
const formMethod = document.getElementById("formMethod"); // Tambahkan di form

const id = document.getElementById("id-edit"); // opsional
const title = document.getElementById("title-edit");
const author = document.getElementById("author-edit");
const publisher = document.getElementById("publisher-edit");
const year = document.getElementById("year-edit");
const isbn = document.getElementById("isbn-edit");
const stock = document.getElementById("stock-edit");
const description = document.getElementById("description-edit");
const cover = document.getElementById("cover-edit");

const editBookBtns = document.querySelectorAll(".editBookBtn");
const openAddBookModalBtn = document.getElementById("openAddBookModalBtn");
const modalCloseBtn = document.getElementById("modalCloseBtn");
const modalAdd = document.getElementById("modalAdd");
const closeBtnModal = document.getElementById("closeBtnModal");
const resetBtn = document.getElementById("resetBtn");

// Buka modal tambah buku
openAddBookModalBtn.onclick = () => {
    // Reset form
    form.reset();
    form.action = `/books`; // kembali ke store
    if (formMethod) formMethod.value = "POST";

    // Kosongkan kategori (kalau pakai Alpine window binding)
    if (window.categorySearchInstance) {
        window.categorySearchInstance.selected = [];
    }

    modalAdd.classList.remove("hidden");
};

// Tutup modal
modalCloseBtn.addEventListener("click", () => {
    modalAdd.classList.add("hidden");
});

closeBtnModal.addEventListener("click", () => {
    modalAdd.classList.add("hidden");
});

// Tombol edit buku
editBookBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        resetBtn.onclick = () => {
            form.reset();
            if (window.categorySearchInstance) {
                window.categorySearchInstance.selected = [];
            }
        };
        const dataset = btn.dataset;

        // Ubah method dan action form
        form.action = `/books/${dataset.id}`;
        if (formMethod) formMethod.value = "PUT";

        // Isi nilai input
        if (id) id.value = dataset.id;
        title.value = dataset.title;
        author.value = dataset.author;
        publisher.value = dataset.publisher;
        year.value = dataset.year;
        isbn.value = dataset.isbn;
        stock.value = dataset.stock;
        description.value = dataset.description;
        cover.setAttribute("src", `storage/cover/${dataset.cover}`);

        if (window.categorySearchInstance && dataset.categories) {
            try {
                const categoryList = JSON.parse(dataset.categories);
                window.categorySearchInstance.selected = categoryList;
            } catch (err) {
                console.error("Format kategori tidak valid:", err);
            }
        }

        // Tampilkan modal
        modalAdd.classList.remove("hidden");
    });
});
