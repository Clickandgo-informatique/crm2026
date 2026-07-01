import { createBoard } from "./createBoard";

export function initBoardsPage(boardsGrid: HTMLElement): void {

    const btnAddBoard = document.createElement("button");
    btnAddBoard.className = "btnAddBoard";
    btnAddBoard.textContent = "+ Ajouter tableau";

    boardsGrid.insertAdjacentElement("afterend", btnAddBoard);

    btnAddBoard.addEventListener("click", () => {
        createBoard(boardsGrid);
    });

}