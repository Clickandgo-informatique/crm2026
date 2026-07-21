export class SidebarToggle {
    public initialize(): void {
        const button = document.querySelector("#sidebar-toggle");
        const wrapper = document.querySelector(".page-wrapper");

        if (!button || !wrapper) {
            return;
        }

        button.addEventListener("click", () => {
            const collapsed = wrapper.classList.toggle("collapsed");

            const icon = button.querySelector("i");

            icon?.classList.toggle("fa-chevron-right", !collapsed);
            icon?.classList.toggle("fa-chevron-left", collapsed);
        });
    }
}
