import { getCardNumber, incrementCardNumber } from "../shared/state";

export function createCard(container: HTMLElement): void {

    const card = document.createElement("div");
    card.className = "column-card";
    card.draggable = true;

    const titleInput = document.createElement("input");
    titleInput.className = "card-title-input";
    titleInput.value = `Carte ${getCardNumber()}`;
    titleInput.placeholder = "Titre de la carte";

    card.appendChild(titleInput);
    container.appendChild(card);

    incrementCardNumber();
}