import { PlanningHeader } from "../components/planning-header";
import { PlanningViewport } from "../components/planning-viewport";
import { CalendarTasks } from "../components/calendar-tasks";
import { Toolbar } from "../components/toolbar";
import type { PlanningView } from "../core/planning-view";

export class PlanningEngine {
    // Gestionnaire du header spécifique aux vues possédant une navigation temporelle
    private readonly header = new PlanningHeader();

    // Gestionnaire de la zone principale contenant les vues du planning
    private readonly viewport = new PlanningViewport();

    // Gestionnaire des tâches affichées dans le planning
    private readonly tasks = new CalendarTasks();

    // Gestionnaire de la barre d'outils
    private readonly toolbar = new Toolbar();

    // Vue actuellement affichée, conservée lors des changements de date
    private currentView: PlanningView = "week";

    /**
     * Initialise le moteur du planning.
     */
    public async initialize(): Promise<void> {
        //
        this.toolbar.initialize(document.querySelector("#planning-toolbar"));
        this.viewport.initialize(document.querySelector("#planning-viewport"));
        this.bindEvents();
        await this.loadCurrentView();
        this.tasks.initialize();
        console.log("PlanningEngine initialized");
    }
    /**
     * Connecte les événements provenant des composants du planning.
     */
    private bindEvents(): void {
        // Déclenché après le chargement d'un fragment de vue
        // Initialise les composants présents uniquement dans cette vue
        document.addEventListener("planningViewLoaded", (event) => {
            const customEvent = event as CustomEvent;
            const view = customEvent.detail.view as PlanningView;
            const navigation = document.querySelector(".calendar-navigation");
            if (!navigation) {
                return;
            }
            this.header.initialize(navigation, view);
        });
        // Déclenché par le ViewSwitcher
        // Change la vue active et la mémorise
        document.addEventListener("planningViewChanged", async (event) => {
            const customEvent = event as CustomEvent;
            const view = customEvent.detail.view as PlanningView;
            this.currentView = view;
            await this.viewport.loadView(view);
        });
        // Déclenché par le mini-calendar ou le header
        // Recharge la vue active avec la nouvelle date sélectionnée
        document.addEventListener("dateSelected", async () => {
            await this.viewport.loadView(this.currentView);
        });
    }
    /**
     * Recharge la vue actuellement active.
     */
    private async loadCurrentView(): Promise<void> {
        await this.viewport.loadView(this.currentView);
    }
}
