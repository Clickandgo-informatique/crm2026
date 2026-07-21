import { getMondayOfWeek } from "../core/date-utils";
import { getSelectedDate, setSelectedDate } from "../core/planning-state";
import type { PlanningView } from "../core/planning-view";

export class PlanningHeader {
    // Élément affichant la période actuelle
    private periodLabel: HTMLElement | null = null;
    // Bouton précédent
    private previousButton: HTMLElement | null = null;
    // Bouton suivant
    private nextButton: HTMLElement | null = null;
    // Date actuellement utilisée par le header
    private currentDate: Date = getSelectedDate();
    // Vue actuellement affichée
    private currentView: PlanningView = "week";
    /**
     * Initialise la navigation de la vue.
     */
    public initialize(container: Element | null, view: PlanningView): void {
        if (!container) {
            return;
        }
        this.currentView = view;
        this.periodLabel = container.querySelector("#planning-period-label");
        this.previousButton = container.querySelector(".btn-previous");
        this.nextButton = container.querySelector(".btn-next");
        this.previousButton?.addEventListener("click", () => {
            this.changePeriod(-1);
        });
        this.nextButton?.addEventListener("click", () => {
            this.changePeriod(1);
        });
        this.refresh();
    }
    /**
     * Recharge le header avec la date globale actuelle.
     */
    public refresh(): void {
        this.currentDate = getSelectedDate();
        this.updatePeriodLabel();
    }
    /**
     * Change la période sélectionnée selon la vue active.
     */
    private changePeriod(direction: number): void {
        const date = new Date(this.currentDate);
        switch (this.currentView) {
            case "day":
                date.setDate(date.getDate() + direction);
                break;
            case "three-days":
                date.setDate(date.getDate() + direction * 3);
                break;
            case "week":
            default:
                date.setDate(date.getDate() + direction * 7);
                break;
        }
        setSelectedDate(date);
        document.dispatchEvent(
            new CustomEvent("dateSelected", {
                detail: {
                    date,
                },
            }),
        );
    }
    /**
     * Met à jour le texte affiché dans le header selon la vue.
     */
    private updatePeriodLabel(): void {
        if (!this.periodLabel) {
            return;
        }
        const options: Intl.DateTimeFormatOptions = {
            day: "numeric",
            month: "long",
            year: "numeric",
        };
        switch (this.currentView) {
            case "day":
                this.periodLabel.textContent =
                    this.currentDate.toLocaleDateString("fr-FR", {
                        weekday: "long",
                        ...options,
                    });
                break;
            case "three-days":
                const endDate = new Date(this.currentDate);
                endDate.setDate(endDate.getDate() + 2);
                this.periodLabel.textContent = `${this.currentDate.toLocaleDateString("fr-FR", options)} au ${endDate.toLocaleDateString("fr-FR", options)}`;
                break;
            case "week":
            default:
                const monday = getMondayOfWeek(this.currentDate);
                const sunday = new Date(monday);
                sunday.setDate(monday.getDate() + 6);
                const start = monday.toLocaleDateString("fr-FR", options);
                const end = sunday.toLocaleDateString("fr-FR", options);
                this.periodLabel.textContent = `Semaine du ${start} au ${end}`;
                break;
        }
    }
}
