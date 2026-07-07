import { initBoardPage } from "./board/init";

console.log("kanban activé");

document.addEventListener("DOMContentLoaded", () => {

    const boardsGrid = document.querySelector(".boards-grid");

    if (boardsGrid instanceof HTMLElement) {
        initBoardPage(boardsGrid);
    }

    const kanbanGrid = document.querySelector(".kanban-grid");

    if (kanbanGrid instanceof HTMLElement) {
        initBoardPage(kanbanGrid);
    }

});