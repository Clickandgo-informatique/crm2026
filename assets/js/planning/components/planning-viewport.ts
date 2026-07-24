import { ViewLoader } from "../services/view-loader";
import { CalendarRenderer } from "../renderers/calendar-renderer";
import { PLANNING_VIEW_CONFIG } from "../core/planning-view";
import type { PlanningView } from "../core/planning-view";
import { getSelectedDate } from "../core/planning-state";
import { CalendarEventManager } from "../services/calendar-event-manager";

export class PlanningViewport {
    // Service chargé de récupérer les fragments HTML des différentes vues
    private readonly viewLoader = new ViewLoader();

    // Renderer générique de la grille calendrier
    private readonly renderer = new CalendarRenderer();

    //Gestionnaire d'évènements
    private readonly eventManager = new CalendarEventManager();

    // Conteneur principal du planning
    private container: Element | null = null;

    // Vue actuellement chargée dans le viewport
    private currentView: PlanningView = "week";

    /**
     * Initialise le conteneur qui recevra les fragments HTML.
     */
    public initialize(container: Element | null): void {
        this.container = container;
    }
    /**
     * Charge et affiche une vue du planning.
     */
    public async loadView(view: PlanningView): Promise<void> {
        if (!this.container) {
            return;
        }
        this.currentView = view;
        const html = await this.viewLoader.load(view);
        this.container.innerHTML = html;

        // Informe le moteur que le nouveau fragment est disponible
        document.dispatchEvent(
            new CustomEvent("planningViewLoaded", {
                detail: {
                    view,
                },
            }),
        );
        const grid = this.container.querySelector(".calendar-grid");
        if (!grid) {
            return;
        }
        const configuration = PLANNING_VIEW_CONFIG[view];
        // Les vues sans grille (exemple : liste) ne passent pas par le renderer
        if (configuration.numberOfDays === 0) {
            return;
        }
        const events = await this.eventManager.load(view, getSelectedDate());
        console.log("VIEW", view);
        console.log("DATE", getSelectedDate());
        console.log("EVENTS", events);
        await this.renderer.render(grid as HTMLElement, {
            referenceDate: getSelectedDate(),
            numberOfDays: configuration.numberOfDays,
            events,
            showTasks: true,
        });
    }
    /**
     * Retourne la vue actuellement affichée.
     */
    public getCurrentView(): PlanningView {
        return this.currentView;
    }
}
