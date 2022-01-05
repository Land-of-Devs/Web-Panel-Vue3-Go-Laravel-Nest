export const update = (oldObj, newObj) => {
    const updatedObj = {};
    Object.entries(newObj).forEach(([key, value]) => {
        if (oldObj[key] !== value) {
            if (key === 'image' && value.length > 0) {
                updatedObj[key] = value;
            } else if (key !== 'image') {
                updatedObj[key] = value;
            }
        }
    });
    return Object.keys(updatedObj).length > 0 ? updatedObj : null;
}

export const create = (obj) => {
    let newForm = new FormData();
    Object.entries(obj).forEach(([key, value]) => {
        if (key === 'image' && value.length > 0) {
            newForm.set(key, value[0]);
        } else if (key !== 'image') {
            newForm.set(key, value);
        }
    });
    return JSON.stringify(Object.fromEntries(newForm)) !== "{}" ? newForm : null;
}

export const rules = {
    required: (value) => (value && value.length > 0) || 'Field is required',
    email: (value) => /\S+@\S+\.\S+/.test(value) || 'Must be an Email',
    file: (image) => (image && image.length > 0),
    number: (value) => (!isNaN(parseInt(value)) && parseInt(+value) >= 0) || 'It needs to be a positive number, including 0',
    string: (value) => (/^[a-z0-9.\-:;()\\!? \n]+$/i.test(value)) || 'Needs to be a string and can contain [,-:;()]',
    name: (value) => (/^((?:\w+)\s{0,1}(?:\w*)){1,4}$/gm.test(value)) || 'Only 4 spaces are allowed',
    password: (value) => (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/gm.test(value)) || 'Needs 8 chars, min 1 upper, 1 lower and 1 number and can contain special chars'
}