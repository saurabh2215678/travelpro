import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
InertiaProgress.init();

createInertiaApp({
    resolve: (name) => require(`./${name}`),
    setup({ el, App, props, plugin }) {
        module_name = props.initialPage.props.data;
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});

let module_name='test';
function getCookie(name) {
    var cookieName = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    
    if(cookieArray.length) {
        for (var i = 0; i < parseInt(cookieArray.length); i++) {
            var cookie = cookieArray[i];
            while (cookie.charAt(0) == ' ') {
                cookie = cookie.substring(1);
            }
            if (cookie.indexOf(cookieName) == 0) {
                var name = cookie.substring(cookieName.length, cookie.length);
                return name;
            }
        }
    }
    return null;
}

function initApp() {
    var md5 = require('md5');
    var exampleCookieValue = getCookie('_mid');
    var exampleCookieValue_arr = JSON.parse(exampleCookieValue);
    if(module_name=='test') {
        throw '';
    }
    if(module_name) {
        let hostname = window.location.hostname;
        if(exampleCookieValue_arr && exampleCookieValue_arr.length > 0 && exampleCookieValue_arr.indexOf(String(md5(module_name+'_'+hostname))) !== -1) {
            // OK
        } else {
            // Error
            if(hostname == '127.0.0.1' || hostname == 'localhost') {

            } else {
                // throw '';
            }
        }
    }
}
document.addEventListener("DOMContentLoaded", function() {
    initApp();
});