import { ViewLoader } from "../services/view-loader";
import { CalendarRenderer } from "../renderers/calendar-renderer";
import { ResourceRenderer } from "../renderers/resource-renderer";
import { CalendarEventManager } from "../services/calendar-event-manager";
import { PLANNING_VIEW_CONFIG } from "../core/planning-view";
import type { PlanningView } from "../core/planning-view";
import { getSelectedDate } from "../core/planning-state";

export class PlanningViewport {
    // Service chargé de récupérer les fragments HTML des différentes vues
    private readonly viewLoader = new ViewLoader();

    // Renderer des vues calendrier (jour, 3 jours, semaine)
    private readonly calendarRenderer = new CalendarRenderer();

    // Renderer de la vue ressources
    private readonly resourceRenderer = new ResourceRenderer();

    // Service de chargement des événements
    private readonly eventManager = new CalendarEventManager();

    // Conteneur principal du viewport
    private container: Element | null = null;

    // Vue actuellement affichée
    private currentView: PlanningView = "week";

    /**
     * Initialise le conteneur recevant les vues.
     */
    public initialize(container: Element | null): void {
        this.container = container;
    }

    /**
     * Charge une vue du planning.
     */
    public async loadView(view: PlanningView): Promise<void> {
        if (!this.container) {
            return;
        }

        this.currentView = view;

        // Charge le fragment Twig correspondant à la vue
        const html = await this.viewLoader.load(view);
        this.container.innerHTML = html;

        // Informe les autres composants que la vue est disponible
        document.dispatchEvent(
            new CustomEvent("planningViewLoaded", {
                detail: {
                    view,
                },
            }),
        );

        const configuration = PLANNING_VIEW_CONFIG[view];

        // Les vues liste et ressources possèdent leur propre moteur de rendu
        switch (configuration.type) {
            case "list":
                await this.loadListView();
                return;

            case "resource":
                await this.loadResourceView();
                return;
        }

        // Toutes les autres vues utilisent la grille calendrier
        const grid = this.container.querySelector(".calendar-grid");

        if (!grid) {
            return;
        }

        const events = await this.eventManager.load(view, getSelectedDate());

        await this.calendarRenderer.render(grid as HTMLElement, {
            referenceDate: getSelectedDate(),
            numberOfDays: configuration.numberOfDays,
            events,
            showTasks: true,
        });
    }

    /**
     * Charge la vue liste.
     */
    private async loadListView(): Promise<void> {
        const container = this.container?.querySelector(
            "#event-list-container",
        );

        if (!container) {
            return;
        }

        const response = await fetch(
            `/planning/events/list?date=${getSelectedDate().toISOString().split("T")[0]}`,
        );

        if (!response.ok) {
            throw new Error("Impossible de charger la liste des événements");
        }

        container.innerHTML = await response.text();
    }

    /**
     * Charge la vue planning par ressources.
     */
    private async loadResourceView(): Promise<void> {
        const container = this.container?.querySelector("#resources-grid");

        if (!container) {
            return;
        }

        // Charge les intervenants
        const resourcesResponse = await fetch("/planning/resources");

        if (!resourcesResponse.ok) {
            throw new Error("Impossible de charger les ressources");
        }

        const resources = await resourcesResponse.json();

        // Charge les événements de la semaine affichée
        const events = await this.eventManager.load("week", getSelectedDate());

        console.log("RESOURCES", resources);
        console.log("RESOURCE EVENTS", events);

        // Génère la grille ressources
        this.resourceRenderer.render(
            container as HTMLElement,
            getSelectedDate(),
            resources,
            events,
        );
    }

    /**
     * Retourne la vue actuellement affichée.
     */
    public getCurrentView(): PlanningView {
        return this.currentView;
    }
}
