console.log('kanban activé')

const grid = document.querySelector('.kanban-grid')

if (!grid) {
    console.error("Impossible de trouver .kanban-grid")
}

const btnAddColumn = document.createElement('button')
btnAddColumn.className = "btnAddColumn"
btnAddColumn.textContent = "+ Ajouter colonne"
grid?.insertAdjacentElement('afterend', btnAddColumn)
btnAddColumn.addEventListener('click', () => { createColumn() })

let colNumber = 1
let cardNumber = 1

const createColumn = () => {

    const newColumn = document.createElement('div')
    newColumn.className = "grid-column"
    newColumn.draggable=true

    const titleInput = document.createElement('input')
    titleInput.className = "column-title-input"
    titleInput.value = `Colonne ${colNumber}`
    titleInput.placeholder="Titre de la colonne"

    const cardsContainer = document.createElement('div')
    cardsContainer.className = "cards-container"

    const btnAddCard = document.createElement('button')
    btnAddCard.className = "btnAddCard"
    btnAddCard.textContent = "+ Ajouter carte"
    btnAddCard.addEventListener('click', () => { createCard(cardsContainer) })

    newColumn.appendChild(titleInput)
    newColumn.appendChild(cardsContainer)
    newColumn.appendChild(btnAddCard)

    grid?.appendChild(newColumn)
    colNumber++
    cardNumber=1
}

const createCard = (container: HTMLElement) => {
    const card = document.createElement('div')
    card.className = "column-card"
    card.draggable=true

    const titleInput = document.createElement('input')
    titleInput.className = "card-title-input"
    titleInput.value = `Carte ${cardNumber}`
    titleInput.placeholder="Titre de la carte"

    card.appendChild(titleInput)
    container.appendChild(card)

    cardNumber++
}
