/**
 * resets Password if user exists.
 */
function forgottenLoginPasswordReset():void {
    let usernameField = document.getElementById('usernameForgottenLogin') as HTMLInputElement;
    let username = usernameField.value;
    let json = {
        type: 'cue',
        username: username
    };
    let result = sendApiRequest(json, false);
    if (result.payload){
        let cssUsername = "form-control textinput border-success";
        usernameField.setAttribute('class', cssUsername);
        let form = document.getElementById('forgottenLoginUsernameForm') as HTMLFormElement;
        form.submit();
    } else {
        let cssUsername = "form-control textinput border-danger";
        usernameField.setAttribute('class', cssUsername);
    }
}

/**
 * sends user username if mail-address exists.
 */
function forgottenLoginUsernameReset():void {
    let mailfield = document.getElementById('usernameForgottenMailAdresse') as HTMLInputElement;
    let mailadress = mailfield.value;
    let json = {
        type: 'cma',
        mail: mailadress,
        debug_enable: false
    };
    let result = sendApiRequest(json, false);
    if (result.payload){
        let cssUsername = "form-control textinput border-success";
        mailfield.setAttribute('class', cssUsername);
        let form2 = document.getElementById('forgottenLoginMailadressForm') as HTMLFormElement;
        form2.submit();
    } else {
        let cssUsername = "form-control textinput border-danger";
        mailfield.setAttribute('class', cssUsername);
    }
}