export class ViewLoader {
    public async load(view: string): Promise<string> {
        const response = await fetch(`/planning/view/${view}`);

        if (!response.ok) {
            throw new Error(`Unable to load planning view: ${view}`);
        }

        return await response.text();
    }
}
