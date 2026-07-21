import type { PlanningView } from "../core/planning-view";

export class ViewSwitcher {
    public initialize(root: Element | null): void {
        if (!root) {
            return;
        }

        console.log("ViewSwitcher initialisé");

        const buttons =
            root.querySelectorAll<HTMLButtonElement>(".view-switch-btn");

        const currentView =
            (root.getAttribute("data-current-view") as PlanningView) ?? "week";

        buttons.forEach((button) => {
            if (button.dataset.view === currentView) {
                button.classList.add("view-switch-btn-active");
            } else {
                button.classList.remove("view-switch-btn-active");
            }

            button.addEventListener("click", () => {
                const view = button.dataset.view as PlanningView;

                if (!view) {
                    return;
                }

                buttons.forEach((btn) =>
                    btn.classList.remove("view-switch-btn-active"),
                );

                button.classList.add("view-switch-btn-active");

                document.dispatchEvent(
                    new CustomEvent("planningViewChanged", {
                        detail: {
                            view,
                        },
                    }),
                );
            });
        });
    }
}
