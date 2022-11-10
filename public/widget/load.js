//domain_url = "http://127.0.0.1:8001";
domain_url = "https://dev.amcodr.co/bookdrhock/public";
var d = new Date();
var rand = d.getMilliseconds();
if(typeof jQuery == 'undefined'){
    var oScriptElem = document.createElement("script");
    oScriptElem.type = "text/javascript";
    oScriptElem.nonce="r@nd0m";
    oScriptElem.src = domain_url+"/widget/jquery.js?"+rand;
    document.head.insertBefore(oScriptElem, document.head.getElementsByTagName("script")[0]);

    oScriptElem.addEventListener("load", () => {
        console.log("import js loaded");
        if(typeof fandomz_widget_load=="undefined")
        {
            var oScriptElem = document.createElement("script");
            oScriptElem.type = "text/javascript";
            oScriptElem.src = domain_url+"/widget/widget.js?"+rand;
            document.head.appendChild(oScriptElem, document.head.getElementsByTagName("script")[0]);
        }
    });
    // error event
    oScriptElem.addEventListener("error", (ev) => {
        console.log("Error on loading import js", ev);
    });
}else{
    console.log("site js loaded");
    setTimeout(function () {
        if(typeof fandomz_widget_load=="undefined")
        {
            var oScriptElem = document.createElement("script");
            oScriptElem.type = "text/javascript";
            oScriptElem.src = domain_url+"/widget/widget.js?"+rand;
            document.head.appendChild(oScriptElem, document.head.getElementsByTagName("script")[0]);
        }   
    },500);

}

