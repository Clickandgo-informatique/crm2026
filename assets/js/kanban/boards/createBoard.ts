import { getBoardNumber, incrementBoardNumber } from "../shared/state";

export function createBoard(boardsGrid: HTMLElement): void {

    const board = document.createElement("div");
    board.className = "board-card";
    board.draggable = true;

    const titleInput = document.createElement("input");
    titleInput.className = "board-title-input";
    titleInput.value = `Tableau ${getBoardNumber()}`;

    const detailsInput = document.createElement("input");
    detailsInput.className = "board-details-input";

    board.appendChild(titleInput);
    board.appendChild(detailsInput);

    boardsGrid.insertAdjacentElement("afterbegin", board);

    incrementBoardNumber();
}