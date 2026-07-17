import { ViewLoader } from "../services/view-loader";

export class PlanningViewport {
    private readonly viewLoader = new ViewLoader();

    public async initialize(container: Element | null): Promise<void> {
        if (!container) {
            return;
        }

        const html = await this.viewLoader.load("week");

        container.innerHTML = html;
    }
}
