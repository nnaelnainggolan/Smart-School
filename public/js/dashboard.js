/* Shared shell; Alpine owns state and keyboard interaction. */
window.schoolDashboard = function () {
    return {
        sidebarOpen: window.innerWidth >= 1024,
        desktop: window.innerWidth >= 1024,
        init() {
            const media = window.matchMedia("(min-width: 1024px)");
            media.addEventListener("change", (event) => {
                this.desktop = event.matches;
                this.sidebarOpen = event.matches;
            });
            this.$nextTick(() => {
                this.$refs.sidebar
                    .querySelectorAll(".sidebar-link")
                    .forEach((link) => {
                        const label = link.textContent.trim();
                        link.setAttribute("aria-label", label);
                        link.setAttribute("title", label);
                        if (link.classList.contains("active"))
                            link.setAttribute("aria-current", "page");
                    });
            });
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            if (this.sidebarOpen && !this.desktop) {
                this.$nextTick(() =>
                    this.$refs.sidebar.querySelector("button, a").focus(),
                );
            }
        },
        closeSidebar() {
            if (!this.desktop && this.sidebarOpen) {
                this.sidebarOpen = false;
                this.$nextTick(() => this.$refs.sidebarToggle.focus());
            }
        },
        trapSidebar(event) {
            if (this.desktop || !this.sidebarOpen || event.key !== "Tab")
                return;
            const focusable = [
                ...this.$refs.sidebar.querySelectorAll(
                    "a[href], button:not([disabled])",
                ),
            ].filter((element) => element.getClientRects().length);
            const first = focusable[0],
                last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    };
};
