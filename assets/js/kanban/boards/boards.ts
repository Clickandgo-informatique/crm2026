import { getBoardNumber, incrementBoardNumber } from "../shared/state";
import { createBoard } from "./boardApi";

export function initBoardsPage(boardsGrid: HTMLElement): void
{
    console.log("initBoardsPage chargé");
    if (!boardsGrid) {
        console.error("Impossible de trouver .boards-grid");
        return;
    }

    const btnAddBoard = document.createElement("button");
    btnAddBoard.className = "btnAddBoard";
    btnAddBoard.textContent = "+ Ajouter colonne";
    
    boardsGrid.insertAdjacentElement("afterend", btnAddBoard);
    console.log('bouton créé')

    btnAddBoard.addEventListener("click", async () => {
         console.log("CLICK OK");

        // création immédiate dans le DOM (UX optimiste)
        const title = `Tableau ${getBoardNumber()}`;
        const details = "";

        const boardElement = createBoardElement(boardsGrid, title, details);

        const titleInput = boardElement.querySelector(".board-title-input") as HTMLInputElement;
        const detailsInput = boardElement.querySelector(".board-details-input") as HTMLInputElement;

        // lecture des valeurs DOM (source de vérité UI)
        const name = titleInput.value;
        const detailsValue = detailsInput.value;

        // appel API Symfony
        const result = await createBoard({
            name: name,
            details: detailsValue,
            position: boardsGrid.children.length
        });

        console.log(result);

        // synchronisation de l'id retourné par le backend
        if (result.success) {
            boardElement.dataset.id = String(result.id);
        }
    });
}

function createBoardElement(
    boardsGrid: HTMLElement,
    title: string,
    details: string
): HTMLElement
{
    const board = document.createElement("div");
    board.className = "board-card";
    board.draggable = true;

    const titleInput = document.createElement("input");
    titleInput.className = "board-title-input";
    titleInput.value = title;

    const detailsInput = document.createElement("input");
    detailsInput.className = "board-details-input";
    detailsInput.value = details;

    board.appendChild(titleInput);
    board.appendChild(detailsInput);

    boardsGrid.insertAdjacentElement("afterbegin", board);

    incrementBoardNumber();

    return board;
}