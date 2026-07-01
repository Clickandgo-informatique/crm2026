let colNumber = 1;
let cardNumber = 1;
let boardNumber = 1;

export function getColNumber(): number {
    return colNumber;
}

export function getCardNumber(): number {
    return cardNumber;
}

export function getBoardNumber(): number {
    return boardNumber;
}

export function incrementColNumber(): number {
    return ++colNumber;
}

export function incrementCardNumber(): number {
    return ++cardNumber;
}

export function incrementBoardNumber(): number {
    return ++boardNumber;
}

export function resetCardNumber(): void {
    cardNumber = 1;
}