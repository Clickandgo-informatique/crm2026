export class CalendarList {
    private container: HTMLElement | null;

    constructor() {
        this.container = document.querySelector("#event-list-container");
    }

    public async render(date?: Date): Promise<void> {
        if (!this.container) {
            return;
        }

        let url = "/planning/events/list";

        if (date) {
            url += `?date=${date.toISOString().split("T")[0]}`;
        }

        const response = await fetch(url);

        if (!response.ok) {
            throw new Error("Impossible de charger la liste des événements");
        }

        this.container.innerHTML = await response.text();
    }
}
