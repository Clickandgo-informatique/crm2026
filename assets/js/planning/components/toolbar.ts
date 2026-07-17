import { CalendarZoom } from "./calendar-zoom";
import { ViewSwitcher } from "./view-switcher";
import { MiniCalendar } from "./mini-calendar";

export class Toolbar {
    private readonly zoom = new CalendarZoom();
    private readonly viewSwitcher = new ViewSwitcher();
    private readonly miniCalendar = new MiniCalendar();

    public initialize(container: Element | null): void {
        if (!container) {
            return;
        }
        this.miniCalendar.initialize(document.querySelector("#mini-calendar"));

        this.viewSwitcher.initialize(container);
        this.zoom.initialize(container);

        this.bindNavigation();
    }

    private bindNavigation(): void {
        const previous = document.querySelector("#previous-week");
        const next = document.querySelector("#next-week");

        previous?.addEventListener("click", () => {
            console.log("previous");
        });

        next?.addEventListener("click", () => {
            console.log("next");
        });
    }
}
