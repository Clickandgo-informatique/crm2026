export function focusWithAnimation(element: HTMLElement): void {

    element.focus();

    element.classList.add("kanban-focus");

    window.setTimeout(() => {
        element.classList.remove("kanban-focus");
    }, 800);
}