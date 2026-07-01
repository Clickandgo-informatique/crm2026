import { createCard } from "./createCard";
import { getColNumber, incrementColNumber, resetCardNumber } from "../shared/state";
import { focusWithAnimation } from "../shared/dom";

export function createColumn(columnsContainer: HTMLElement): void {

    const newColumn = document.createElement("div");
    newColumn.className = "grid-column";
    newColumn.draggable = true;

    const titleInput = document.createElement("input");
    titleInput.className = "column-title-input";
    titleInput.value = `Colonne ${getColNumber()}`;
    titleInput.placeholder = "Titre de la colonne";

    const cardsContainer = document.createElement("div");
    cardsContainer.className = "cards-container";

    const btnAddCard = document.createElement("button");
    btnAddCard.className = "btnAddCard";
    btnAddCard.textContent = "+ Ajouter carte";

    btnAddCard.addEventListener("click", () => {
        createCard(cardsContainer);
    });

    newColumn.appendChild(titleInput);
    newColumn.appendChild(cardsContainer);
    newColumn.appendChild(btnAddCard);

    columnsContainer.appendChild(newColumn);

    incrementColNumber();
    resetCardNumber();

    requestAnimationFrame(() => {

        newColumn.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });

        focusWithAnimation(titleInput);
    });
}