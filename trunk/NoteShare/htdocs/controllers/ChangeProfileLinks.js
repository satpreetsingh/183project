function changeProfileLinksNS()
{
	var links = document.getElementsByTagName("a");

	for( var link in links)
	{
		if(links[link].getAttribute("href").indexOf("www.facebook.com/profile.php") < 0)
			links[link].setAttribute("target","_top");
	}
}
//alert("change is coming");
setTimeout("changeProfileLinksNS()",1000);

//var NSbody = document.getElementsByTagName("body")[0];
//var NSbodyLoad = NSbody.getAttribute("onload");
//NSbody.setAttribute("onload",NSbodyLoad + ";changeProfileLinksNS();");

