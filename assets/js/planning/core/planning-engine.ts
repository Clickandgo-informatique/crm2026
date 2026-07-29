import { PlanningHeader } from "../components/planning-header";
import { PlanningViewport } from "../components/planning-viewport";
import { CalendarTasks } from "../components/calendar-tasks";
import { Toolbar } from "../components/toolbar";
import type { PlanningView } from "../core/planning-view";
import { CalendarInteractionManager } from "../interactions/calendar-interaction-manager";

export class PlanningEngine {
    // Gestionnaire de l'en-tête du planning (navigation temporelle)
    private readonly header = new PlanningHeader();
    // Gestionnaire de la zone principale contenant les vues du planning
    private readonly viewport = new PlanningViewport();
    // Gestionnaire des tâches affichées dans le planning
    private readonly tasks = new CalendarTasks();
    // Gestionnaire de la barre d'outils
    private readonly toolbar = new Toolbar();
    // Gestionnaire des interactions utilisateur (clics, drag & drop, etc.)
    private readonly interactionManager = new CalendarInteractionManager();
    // Vue actuellement affichée
    private currentView: PlanningView = "week";

    /**
     * Initialise le moteur du planning.
     */
    public async initialize(): Promise<void> {
        this.toolbar.initialize(document.querySelector("#planning-toolbar"));
        this.viewport.initialize(document.querySelector("#planning-viewport"));

        const viewport =
            document.querySelector<HTMLElement>("#planning-viewport");
        if (viewport) {
            this.interactionManager.initialize(viewport);
        }
        
        this.bindEvents();
        await this.loadCurrentView();
        this.tasks.initialize();
        console.log("PlanningEngine initialized");
    }

    /**
     * Connecte les événements provenant des composants du planning.
     */
    private bindEvents(): void {
        // Déclenché après le chargement d'une vue.
        // Initialise les composants dépendant du contenu chargé.
        document.addEventListener("planningViewLoaded", (event) => {
            console.log("planningViewLoaded !");
            const customEvent = event as CustomEvent;
            const view = customEvent.detail.view as PlanningView;

            const navigation = document.querySelector(".calendar-navigation");
            if (navigation) {
                this.header.initialize(navigation, view);
            }
        });

        // Déclenché lors d'un changement de vue (jour, semaine, etc.).
        document.addEventListener("planningViewChanged", async (event) => {
            const customEvent = event as CustomEvent;
            const view = customEvent.detail.view as PlanningView;
            this.currentView = view;
            await this.viewport.loadView(view);
        });

        // Déclenché lorsqu'une date est sélectionnée.
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
