import { createColumn } from "./createColumn";

export function initBoardPage(root: HTMLElement): void {

    root.innerHTML = `
        <div class="kanban-columns"></div>
    `;

    const columnsContainer = root.querySelector(".kanban-columns") as HTMLElement;

    const btnAddColumn = document.createElement("button");
    btnAddColumn.className = "btnAddColumn";
    btnAddColumn.textContent = "+ Ajouter colonne";

    btnAddColumn.addEventListener("click", () => {
        createColumn(columnsContainer);
    });
    createColumn(columnsContainer);
    columnsContainer.appendChild(btnAddColumn);
}