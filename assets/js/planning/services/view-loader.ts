export async function loadPlanningView(
    container: HTMLElement,
    view: string,
): Promise<void> {
    const response = await fetch(`/planning/view/${view}`);

    if (!response.ok) {
        throw new Error(`Unable to load planning view ${view}`);
    }

    const html = await response.text();

    const template = document.createElement("template");

    template.innerHTML = html;

    container.replaceChildren(template.content.cloneNode(true));
}
