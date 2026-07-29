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

    private onSlotClick(slot: HTMLElement): void {
        console.log("Slot", slot.dataset.date, slot.dataset.time);
    }

    private onEventClick(event: HTMLElement): void {
        console.log("Event", event.dataset.id);
    }
}
