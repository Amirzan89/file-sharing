export function popUpWarning(text){
    const divElement = document.createElement('div');
    document.body.appendChild(divElement);
    divElement.innerHTML = `<h3>Do you want to replace File ${text}?</h3>`;
    divElement.style.cssText = 'position:relative; border-radius:30px;';
}