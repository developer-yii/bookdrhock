domain_url = "https://dev.amcodr.co/bookdrhock/public";
//domain_url = "https://www.bookdrhock.com";
//domain_url = "https://bookdrhock.amcodr.co";
var d = new Date();
var rand = d.getMilliseconds();
if(typeof jQuery == 'undefined'){
    var oScriptElem = document.createElement("script");
    oScriptElem.type = "text/javascript";
    oScriptElem.nonce="r@nd0m";
    oScriptElem.src = domain_url+"/widget/jquery.js?"+rand;
    document.head.insertBefore(oScriptElem, document.head.getElementsByTagName("script")[0]);
}
if(typeof jQuery == 'undefined'){
    document.write('<script src="'+domain_url+'/widget/jquery.js"></script>');
}
if(typeof fandomz_widget_load=="undefined")
{
    var oScriptElem = document.createElement("script");
    oScriptElem.type = "text/javascript";
    oScriptElem.src = domain_url+"/widget/widget.js?"+rand;
    document.head.appendChild(oScriptElem, document.head.getElementsByTagName("script")[0]);
}
