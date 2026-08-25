export class ModalManager {
    public static open(content: string): void {
        let modal = document.querySelector("#app-modal");

        if (!modal) {
            modal = document.createElement("div");
            modal.id = "app-modal";
            modal.className = "app-modal";

            document.body.appendChild(modal);
        }

        modal.innerHTML = `
            <div class="modal-overlay">
                <div class="modal-content">
                    <button class="modal-close" type="button">
                        ×
                    </button>
                    ${content}
                </div>
            </div>
        `;

        modal.classList.add("is-open");

        modal.querySelector(".modal-close")?.addEventListener("click", () => {
            this.close();
        });

        modal
            .querySelector(".modal-overlay")
            ?.addEventListener("click", (event) => {
                if (event.target === event.currentTarget) {
                    this.close();
                }
            });
    }

    public static close(): void {
        const modal = document.querySelector("#app-modal");

        if (modal) {
            modal.classList.remove("is-open");
        }
    }
}
