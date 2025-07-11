const form = document.querySelector("form");
const modalAdd = document.getElementById("modalAdd");
const openBtn = document.getElementById("openAddBookModalBtn");
const closeBtn = document.getElementById("closeBtnModal");
const resetBtn = document.getElementById("resetBtn");
const formMethod = document.querySelector("input[name='_method']");

// Fungsi bantu reset form
function resetFormToCreate() {
    form.reset();
    form.action = `/books`;
    if (formMethod) formMethod.value = "POST";
    if (window.categorySearchInstance) {
        window.categorySearchInstance.selected = [];
    }
}

// Buka modal tambah buku
openBtn?.addEventListener("click", () => {
    resetFormToCreate();
    modalAdd.classList.remove("hidden");
});

// Tutup modal
closeBtn?.addEventListener("click", () => {
    modalAdd.classList.add("hidden");
});

// Reset form manual
resetBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    resetFormToCreate();
});

// Drag modal
document.querySelectorAll(".modal-add-header").forEach((header, i) => {
    const modal = document.querySelectorAll(".modal-add").item(i);
    let isDragging = false,
        offsetX = 0,
        offsetY = 0;

    header.addEventListener("mousedown", (e) => {
        isDragging = true;
        const rect = modal.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;
    });

    document.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        modal.style.left = `${e.clientX - offsetX}px`;
        modal.style.top = `${e.clientY - offsetY}px`;
        modal.style.transform = "none";
    });

    document.addEventListener("mouseup", () => {
        isDragging = false;
    });
});

// Dropdown Filter Search
function filterSearch() {
    return {
        open: false,
        showDropdown: false,
        filter: "title",
        filters: ["Judul", "Penulis", "Penerbit", "ISBN", "Stok", "Tahun"],
        search: "",
        books: [],
        fetchBooks() {
            if (this.search.length < 1) {
                this.books = [];
                this.showDropdown = false;
                return;
            }
            fetch(`/books/search?query=${this.search.charAt(0)}`).then((res) =>
                res.json().then((data) => {
                    this.books = data;
                    this.showDropdown = true;
                })
            );
        },
        selectBook(book) {
            this.search = book.title;
            this.showDropdown = false;
        },
        toggle() {
            if (this.open) {
                return this.close();
            }
            this.$refs.button.focus();
            this.open = true;
        },
        close(focusAfter) {
            if (focusAfter) return;
            this.open = false;
            focusAfter && focusAfter.focus();
        },
    };
}
