export interface TicketProductCreateContent {
  message: string
}

export interface TicketProductReportContent {
  productId: number,
  message: string
}

export interface TicketUserUpdateContent {
  newName: string | null,
  newEmail: string | null,
  newPasswordHash: string | null
}
