/**
 * checks if mailadress is already
 */
function checkMailAdress(): void {
    var mailfield = document.getElementById('emailAddUser') as HTMLInputElement;
    var mail = mailfield.value;
    var json = {
        type: "cma",
        mail: mail
    }
    var result = sendApiRequest(json, false);
    if (result.payload) {
        var current = mailfield.getAttribute('class');
        current += " border-danger bg-danger";
        mailfield.setAttribute('class', current);
    } else {
        mailfield.setAttribute('class', 'form-control textinput');
        var buttonSubmit = document.getElementById('submitRegistration') as HTMLButtonElement;
        buttonSubmit.click();
    }
}