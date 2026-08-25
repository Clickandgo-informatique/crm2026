import { ModalManager } from "../../services/modal-manager";

export class CalendarInteractionManager {
    // Inputs formulaire
    private startAtInput: HTMLInputElement | null = null;
    private endAtInput: HTMLInputElement | null = null;
    private checkAllDay: HTMLInputElement | null = null;

    // Heures conservées lorsque "Toute la journée" est activé
    private savedStartTime: string | null = null;
    private savedEndTime: string | null = null;

    public initialize(container: HTMLElement): void {
        console.log("Interaction manager initialized");

        container.addEventListener("click", (event) => {
            console.log("Container click", event.target);
        });

        container.addEventListener("click", this.onClick.bind(this));
    }

    private onClick(event: MouseEvent): void {
        const target = event.target as HTMLElement;

        const slot = target.closest<HTMLElement>(".calendar-slot");

        if (slot) {
            this.onSlotClick(slot);
            return;
        }

        const calendarEvent = target.closest<HTMLElement>(".calendar-event");

        if (calendarEvent) {
            this.onEventClick(calendarEvent);
        }
    }

    private async onSlotClick(slot: HTMLElement): Promise<void> {
        const date = slot.dataset.date;
        const time = slot.dataset.time;

        if (!date || !time) {
            console.error("Date ou heure du slot introuvable.", {
                date,
                time,
            });
            return;
        }

        const response = await fetch("/calendar/event/new");

        if (!response.ok) {
            throw new Error("Impossible de charger le formulaire événement");
        }

        const html = await response.text();

        ModalManager.open(html);

        // Récupération des champs du formulaire
        this.startAtInput = this.getInput(
            "#calendar_event_startAt",
            "Date de début",
        );

        this.endAtInput = this.getInput("#calendar_event_endAt", "Date de fin");

        this.checkAllDay = this.getInput(
            "#calendar_event_allDay",
            "Toute la journée",
        );

        // Réinitialisation des heures mémorisées
        this.savedStartTime = null;
        this.savedEndTime = null;

        // Listener sur "Toute la journée"
        if (this.checkAllDay) {
            this.checkAllDay.addEventListener(
                "change",
                this.onAllDayClick.bind(this),
            );
        }

        // Remplissage de la date de début
        const startAt = `${date}T${time}`;

        console.log("Slot sélectionné :", startAt);

        if (this.startAtInput) {
            this.startAtInput.value = startAt;
        }

        // Remplissage de la date de fin
        if (this.endAtInput) {
            const startDate = new Date(`${date}T${time}`);

            startDate.setHours(startDate.getHours() + 1);

            this.endAtInput.value = this.formatDateTimeLocal(startDate);
        }

        // Mémorisation des heures
        this.savedStartTime = time;

        if (this.endAtInput) {
            this.savedEndTime = this.endAtInput.value.substring(11, 16);
        }

        console.log("Date de début :", this.startAtInput?.value);
        console.log("Date de fin :", this.endAtInput?.value);
    }

    private onEventClick(event: HTMLElement): void {
        console.log("Event", event.dataset.id);
    }

    /**
     * Gestion de la checkbox "Toute la journée".
     */
    private onAllDayClick(): void {
        if (!this.checkAllDay) {
            console.error(
                'Champ "Toute la journée" introuvable : #calendar_event_allDay',
            );
            return;
        }

        if (!this.startAtInput) {
            console.error(
                'Champ "Date de début" introuvable : #calendar_event_startAt',
            );
            return;
        }

        if (!this.endAtInput) {
            console.error(
                'Champ "Date de fin" introuvable : #calendar_event_endAt',
            );
            return;
        }

        if (this.checkAllDay.checked) {
            this.enableAllDayMode();
        } else {
            this.disableAllDayMode();
        }
    }

    /**
     * Active le mode "Toute la journée".
     */
    private enableAllDayMode(): void {
        if (!this.startAtInput || !this.endAtInput) {
            return;
        }

        // Sauvegarde des heures actuelles
        this.savedStartTime = this.startAtInput.value.substring(11, 16);
        this.savedEndTime = this.endAtInput.value.substring(11, 16);

        const startDate = this.startAtInput.value.substring(0, 10);

        if (!startDate) {
            console.error("Date de début introuvable.");
            return;
        }

        // Une journée entière commence à 00:00
        this.startAtInput.value = `${startDate}T00:00`;

        // La fin correspond au début du jour suivant
        const endDate = new Date(`${startDate}T00:00`);

        endDate.setDate(endDate.getDate() + 1);

        this.endAtInput.value = this.formatDateTimeLocal(endDate);

        console.log("Mode toute la journée activé");
        console.log("Date de début :", this.startAtInput.value);
        console.log("Date de fin :", this.endAtInput.value);
    }

    /**
     * Désactive le mode "Toute la journée".
     */
    private disableAllDayMode(): void {
        if (!this.startAtInput || !this.endAtInput) {
            return;
        }

        const startDate = this.startAtInput.value.substring(0, 10);

        if (!startDate) {
            console.error("Date de début introuvable.");
            return;
        }

        // Si aucune heure n'a été sauvegardée,
        // on utilise une valeur par défaut.
        const startTime = this.savedStartTime ?? "09:00";
        const endTime = this.savedEndTime ?? "10:00";

        // Restauration de la date et de l'heure de début
        this.startAtInput.value = `${startDate}T${startTime}`;

        // Restauration de la date et de l'heure de fin
        this.endAtInput.value = `${startDate}T${endTime}`;

        console.log("Mode toute la journée désactivé");
        console.log("Date de début :", this.startAtInput.value);
        console.log("Date de fin :", this.endAtInput.value);
    }

    /**
     * Récupère un champ input et affiche une erreur
     * explicite s'il n'existe pas dans le DOM.
     */
    private getInput(selector: string, label: string): HTMLInputElement | null {
        const input = document.querySelector<HTMLInputElement>(selector);

        if (!input) {
            console.error(`Champ "${label}" introuvable : ${selector}`);
        }

        return input;
    }

    /**
     * Convertit une Date en valeur compatible
     * avec un input datetime-local.
     *
     * Exemple :
     * 2026-08-25T14:30
     */
    private formatDateTimeLocal(date: Date): string {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const hours = String(date.getHours()).padStart(2, "0");
        const minutes = String(date.getMinutes()).padStart(2, "0");

        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
}
