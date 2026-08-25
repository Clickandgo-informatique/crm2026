import { ModalManager } from "../../services/modal-manager";

export class CalendarInteractionManager {
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

        const response = await fetch("/calendar/event/new");

        if (!response.ok) {
            throw new Error("Impossible de charger le formulaire événement");
        }

        const html = await response.text();

        ModalManager.open(html);

        console.log("Slot", date, time);
    }

    private onEventClick(event: HTMLElement): void {
        console.log("Event", event.dataset.id);
    }
}
