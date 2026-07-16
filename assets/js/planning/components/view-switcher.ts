export type PlanningView = "week" | "three-days" | "day" | "list";

export function initViewSwitcher(root: HTMLElement): void {
    console.log("view-switcher initialisé");

    const buttons =
        root.querySelectorAll<HTMLButtonElement>(".view-switch-btn");
    const currentView = (root.dataset.currentView as PlanningView) ?? "week";

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
