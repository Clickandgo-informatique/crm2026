export class ModalManager {
    public static open(content: string, title: string = "Fenêtre"): void {
        let modal = document.querySelector("#app-modal");

        if (!modal) {
            modal = document.createElement("div");
            modal.id = "app-modal";
            modal.className = "app-modal";

            document.body.appendChild(modal);
        }

        modal.innerHTML = `
    <div class="modal-overlay">
        <div
            class="modal-content"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
        >
            <header class="modal-header">
                <h2 class="modal-title" id="modal-title">
                    ${title}
                </h2>

                <button
                    class="modal-close"
                    type="button"
                    aria-label="Fermer"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </header>

            <div class="modal-body">
                ${content}
            </div>
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
