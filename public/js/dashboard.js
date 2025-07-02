function categorySearch(data) {
    return {
        all: data,
        search: "",
        show: false,
        selected: [],

        get filtered() {
            if (!this.search.trim()) {
                return this.all.slice(0, 10); // Tampilkan 10 kategori teratas saat awal
            }
            return this.all.filter((cat) =>
                cat.name.toLowerCase().startsWith(this.search.toLowerCase())
            );
        },

        select(cat) {
            if (!this.selected.some((c) => c.id === cat.id)) {
                this.selected.push(cat);
            }
            this.resetSearch();
        },

        selectFirst() {
            if (this.filtered.length) {
                this.select(this.filtered[0]);
            }
        },

        remove(id) {
            this.selected = this.selected.filter((c) => c.id !== id);
        },

        resetSearch() {
            this.search = "";
            this.show = true; // untuk memicu ulang pencarian
        },
    };
}

const modals = document.querySelectorAll(".modal-add");
const headers = document.querySelectorAll(".modal-add-header");

let isDragging = false;
let offsetX = 0;
let offsetY = 0;

headers.forEach((header, i) => {
    let modal = modals.item(i);
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

const books = document.querySelectorAll(".book");

books.forEach((book, i) => {
    book.get;
});
