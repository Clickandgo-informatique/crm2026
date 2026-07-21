import type { PlanningView } from "../core/planning-view";

export class ViewLoader {
    /**
     * Charge un fragment HTML correspondant à une vue du planning.
     */
    public async load(view: PlanningView): Promise<string> {
        const response = await fetch(`/planning/view/${view}`);

        if (!response.ok) {
            throw new Error(`Unable to load planning view: ${view}`);
        }
        return response.text();
    }
}
