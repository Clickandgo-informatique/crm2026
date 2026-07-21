import { CalendarZoom } from "./calendar-zoom";
import { ViewSwitcher } from "./view-switcher";
import { MiniCalendar } from "./mini-calendar";

export class Toolbar {
    private readonly zoom = new CalendarZoom();
    private readonly viewSwitcher = new ViewSwitcher();
    private readonly miniCalendar = new MiniCalendar();
    private planningContainer: HTMLElement | null = null;
    private planningToolsToggleBtn: HTMLElement | null = null;

    public initialize(container: Element | null): void {
        if (!container) {
            return;
        }

        this.miniCalendar.initialize(document.querySelector("#mini-calendar"));

        //Gestion du toggle de la barre d'outils du planning
        this.planningContainer = document.querySelector(
            ".planning-container",
        );
        this.planningToolsToggleBtn = document.querySelector(
            ".planning-tools-toggle",
        );

        this.viewSwitcher.initialize(container);
        this.zoom.initialize(container);
       
        this.planningToolsToggleBtn?.addEventListener("click", () => {
            this.planningContainer?.classList.toggle("collapsed");
        });

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
