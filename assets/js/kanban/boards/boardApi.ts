interface CreateBoardRequest {
    name: string;
    details: string;
    position: number;
}

interface CreateBoardResponse {
    success: boolean;
    id: number;
    message: string;
}

export async function createBoard(data: CreateBoardRequest): Promise<CreateBoardResponse>
{
    const response = await fetch("/api/kanban-boards", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    });

    return await response.json();
}