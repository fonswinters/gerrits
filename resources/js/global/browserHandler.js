/* ==========================================================================
   Browser Handler
 ========================================================================== */

const BrowserHandler = {

    userAgent: '',
    browserInfo: '',


    init: function () {
        BrowserHandler.userAgent = window.navigator.userAgent;
        BrowserHandler.browserInfo = BrowserHandler.getBrowserInfo();
        BrowserHandler.handleIE();
        BrowserHandler.handleSafari();
    },

    handleIE: function () {

        // Detect versions below ie11
        let msie = BrowserHandler.userAgent.indexOf('MSIE ');
        const ielt11 = msie > 0;

        // Detect ie11
        const ie11 = !!navigator.userAgent.match(/Trident.*rv\:11\./);

        // If Internet Explorer
        if (ielt11 || ie11) {
            // Default version
            let version = '11';

            // Way to detect version < 11
            if (ielt11) version = parseInt(BrowserHandler.userAgent.substring(
                msie + 5,
                BrowserHandler.userAgent.indexOf(".", msie)
            ));

            // Append classes to HTML (we have to do this separately because else ie will fail)
            document.body.classList.add('ie');
            document.body.classList.add('v'+version);
        }

    },

    // Fallback for older safari version
    handleSafari: function () {

        if(BrowserHandler.browserInfo.name === 'Safari' && BrowserHandler.browserInfo.version <= 10){
            document.getElementsByTagName('html')[0].classList.add('ie');
        }

    },

    getBrowserInfo: function () {
        let ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1])){
            tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
            return {name:'IE ',version:(tem[1]||'')};
        }
        if(M[1]==='Chrome'){
            tem=ua.match(/\bOPR\/(\d+)/)
            if(tem!=null)   {return {name:'Opera', version:tem[1]};}
        }
        M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
        if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
        return {
            name: M[0],
            version: M[1]
        };
    }
};

BrowserHandler.init();