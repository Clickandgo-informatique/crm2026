export function initBoardsPage(boardsGrid: HTMLElement):void
{

    const btnAddBoard = document.createElement('button')
    btnAddBoard.className = "btnAddBoard"
    btnAddBoard.textContent = "+ Ajouter colonne"

    boardsGrid?.insertAdjacentElement('afterend', btnAddBoard)

    btnAddBoard.addEventListener('click', () => {
         createBoard(boardsGrid) 
        })

if (!boardsGrid) {
    console.error("Impossible de trouver .boards-grid")
}

let boardNumber=1

const createBoard=(boardsGrid: HTMLElement)=>{

    const board=document.createElement('div')
    board.className="board-card"
    board.draggable=true

    const titleInput=document.createElement('input')
    titleInput.className="board-title-input"
    titleInput.value=`Tableau ${boardNumber}`
    
    const detailsInput=document.createElement('input')
    detailsInput.className="board-details-input"

boardsGrid?.insertAdjacentElement('afterbegin',board)
board.appendChild(titleInput)
board.appendChild(detailsInput)

boardNumber++
}
}