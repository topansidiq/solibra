function bookSearch(data) {
    return {
        all: data,
        search: "",
        show: false,
        selected: null,
        book_id: null, // untuk input hidden

        get filtered() {
            if (!this.search.trim()) {
                return this.all.slice(0, 10);
            }
            return this.all.filter((book) =>
                book.title.toLowerCase().startsWith(this.search.toLowerCase())
            );
        },

        select(book) {
            this.selected = book;
            this.search = book.title; // tampilkan judul di input
            this.book_id = book.id; // simpan ID untuk input hidden
            this.show = false; // tutup dropdown
            setTimeout(() => lucide.createIcons(), 0);
        },

        selectFirst() {
            if (this.filtered.length) {
                this.select(this.filtered[0]);
            }
        },
    };
}

function userSearch(data) {
    return {
        all: data,
        search: "",
        show: false,
        selected: null,
        user_id: null, // untuk input hidden

        get filtered() {
            if (!this.search.trim()) {
                return this.all.slice(0, 10);
            }
            return this.all.filter((user) =>
                user.name.toLowerCase().startsWith(this.search.toLowerCase())
            );
        },

        select(user) {
            this.selected = user;
            this.search = user.name; // tampilkan nama di input
            this.user_id = user.id; // simpan ID untuk input hidden
            this.show = false; // tutup dropdown
        },

        selectFirst() {
            if (this.filtered.length) {
                this.select(this.filtered[0]);
            }
        },
    };
}
