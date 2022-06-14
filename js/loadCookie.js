window.onload = function (){
    if (testCookie('CookieAccept')){
        if (getCookie('CookieAccept') == 'true')
        {
            setCookie('CookieAccept', true, 3650);
            if (testCookie('BrowserSurvey') == false) {
                BrowserSurvey();
            } else if (getCookie('BrowserSurvey') == 'false'){
                BrowserSurvey();
            }

            return;
        }
    }
    $('#CookieBannerModal').modal({
        keyboard: false,
        backdrop: 'static'
    });
}

function AcceptCookies(){
    setCookie('CookieAccept', true, 3650);
    $('#CookieBannerModal').modal('hide');
    BrowserSurvey();
}

function BrowserSurvey(){
    console.log('browserdata insert');
    sendApiRequest({type: 'ibd'}, false);
    setCookie('BrowserSurvey', true, 3650);
}