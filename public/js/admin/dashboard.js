function categorySearch(allCategories, selectedItems = []) {
    return {
        all: allCategories,
        search: "",
        show: false,
        selected: selectedItems,

        get filtered() {
            if (!this.search.trim()) {
                return this.all.slice(0, 10);
            }
            return this.all.filter((cat) =>
                cat.name.toLowerCase().startsWith(this.search.toLowerCase())
            );
        },

        select(cat) {
            if (!this.selected.find((c) => c.id === cat.id)) {
                this.selected.push(cat);
            }
            this.search = "";
            this.show = false;
        },

        selectFirst() {
            if (this.filtered.length) {
                this.select(this.filtered[0]);
            }
        },

        remove(id) {
            this.selected = this.selected.filter((c) => c.id !== id);
            setTimeout(() => lucide.createIcons(), 0); // fallback
        },

        resetSearch() {
            this.search = "";
            this.show = true;
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
