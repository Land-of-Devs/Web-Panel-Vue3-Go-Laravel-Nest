export const date = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    })
}

export const hash = (hash) => {
    const digits = hash.toString().length;
    return digits === 1 ? '000' + hash :
        digits === 2 ? '00' + hash :
        digits === 3 ? '0' + hash :
        hash
}