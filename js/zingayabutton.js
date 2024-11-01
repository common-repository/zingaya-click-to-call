var zg_proto = location.protocol;
var zg_url = "http://cdn3.zingaya.com/";
if ( zg_proto === "https:" ) zg_url = "https://d32l2k7yon9s3c.cloudfront.net/";

function ZingayaClass() {
	this.buttonLabel = "Call us online";
	this.labelColor = "#13487f";
	this.labelFontSize = 10;
	this.labelTextDecoration = "none";
	this.labelFontWeight = "bold";
	this.labelShadowDirection = "bottom";
	this.labelShadowColor = "#8fd3ec";
	this.labelShadow = true;	
	this.buttonBackgroundColor = "#68c3f0";
	this.buttonGradientColor1 = "#68c3f0";
	this.buttonGradientColor2 = "#5bbaee";
	this.buttonGradientColor3 = "#5fbdef";
	this.buttonGradientColor4 = "#62bfef";	
	this.buttonShadow = true;	
	this.buttonHoverBackgroundColor = "#30b3f1";
	this.buttonHoverGradientColor1 = "#30b3f1";
	this.buttonHoverGradientColor2 = "#2aa8ef";
	this.buttonHoverGradientColor3 = "#2cacf0";
	this.buttonHoverGradientColor4 = "#2daef0";	
	this.buttonActiveShadowColor1 = "#BAE8FF";
	this.buttonActiveShadowColor2 = "#09608C";	
	this.buttonCornerRadius = 2;
	this.buttonPadding = 10;
	this.iconColor = "#fff";
	this.iconOpacity = 1;
	this.iconDropShadow = true;
	this.iconShadowColor = "#13487f";
	this.iconShadowDirection = "bottom";
	this.iconShadowOpacity = 0.5;
	this.type = "button";
	this.widgetPosition = "right";
	this.cssStyleString = "";
	this.widgetStyleString = "";
        this.callme_id = "";
        this.poll_id = null;
        this.analytics_id = null;
	
	if (typeof document.getElementsByClassName!='function') {
	    document.getElementsByClassName = function() {
	        var elms = document.getElementsByTagName('*');
	        var ei = new Array();
	        for (i=0;i<elms.length;i++) {
	            if (elms[i].getAttribute('class')) {
	                ecl = elms[i].getAttribute('class').split(' ');
	                for (j=0;j<ecl.length;j++) {
	                    if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
	                        ei.push(elms[i]);
	                    }
	                }
	            } else if (elms[i].className) {
	                ecl = elms[i].className.split(' ');
	                for (j=0;j<ecl.length;j++) {
	                    if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
	                        ei.push(elms[i]);
	                    }
	                }
	            }
	        }
	        return ei;  
	    }
	}
	
	this.hex2Rgba = function(hex, alpha){
	  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})|([a-f\d]{1})([a-f\d]{1})([a-f\d]{1})$/i.exec(hex);
	  return result ? {        
	    r: parseInt(hex.length <= 4 ? result[4]+result[4] : result[1], 16),
	    g: parseInt(hex.length <= 4 ? result[5]+result[5] : result[2], 16),
	    b: parseInt(hex.length <= 4 ? result[6]+result[6] : result[3], 16),
	    toString: function() {
	      var arr = [];
	      arr.push(this.r);
	      arr.push(this.g);
	      arr.push(this.b);
	      return "rgba(" + arr.join(",") + ", "+alpha+")";
	    }
	  } : null;
	}
	
	this.loadCSS = function(css) {
		
		if (document.getElementById("zingayaButtonCSS") == null) {
			var s = document.createElement('style'),
				e = document.getElementsByTagName('script')[0];
			s.type = 'text/css';
			s.id = 'zingayaButtonCSS';
			e.parentNode.insertBefore(s, e.nextSibling);
			try{
				document.getElementById('zingayaButtonCSS').innerHTML = css; //this.cssStyleString;
			} catch(e) {
				document.getElementById('zingayaButtonCSS').styleSheet.cssText = css; //this.cssStyleString;
			}
		} else {
			try{
				document.getElementById('zingayaButtonCSS').innerHTML += css; //this.cssStyleString;
			} catch(e) {
				document.getElementById('zingayaButtonCSS').styleSheet.cssText += css; //this.cssStyleString;
			}
		}

	}
}

ZingayaClass.prototype.SVG = function() {
	return document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1");
}

ZingayaClass.prototype.loadWidget = function() {
	var e = document.createElement('span');
	e.innerHTML = this.buttonLabel;
	e.style.whiteSpace = "nowrap";
	e.style.fontFamily = "Arial";
	e.style.fontSize = this.labelFontSize+"px";
	e.style.fontWeight = "bold";
	document.body.appendChild(e);
	var len = e.offsetWidth;	
	document.body.removeChild(e);
	
	var iconSize = Math.round((this.labelFontSize+this.buttonPadding*2)*0.7);
	if (iconSize <= 16) iconSize = 18;	
	var h = this.labelFontSize + this.buttonPadding*2 + iconSize + len , w = this.buttonPadding * 2 + this.labelFontSize, radiusCSS = (this.widgetPosition=='left'?'right':'left');
	
	 
	this.widgetStyleString += " .ZingayaWidget {"+
        "z-index: 99999999;"+
	"height:"+h+"px;"+
	"width:"+w+"px;"+
	"background-color:"+this.buttonBackgroundColor+";"+
        "background:linear-gradient(to right, "+this.buttonGradientColor1+" 0%,"+this.buttonGradientColor2+" 100%);"+
	"box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.5);"+
	"position:fixed;"+
	"top:50%;"+
	"margin-top:-"+h/2+"px;"+
	(this.widgetPosition=="left"?"left:0px;":"right:0px;")+
        "border-radius:0;"+
        "-webkit-border-radius:0;"+
        "-moz-border-radius:0;"+
	"-webkit-border-top-"+radiusCSS+"-radius: "+this.buttonCornerRadius+"px;"+
	"-webkit-border-bottom-"+radiusCSS+"-radius: "+this.buttonCornerRadius+"px;"+
	"-moz-border-radius-top"+radiusCSS+": "+this.buttonCornerRadius+"px;"+
	"-moz-border-radius-bottom"+radiusCSS+": "+this.buttonCornerRadius+"px;"+
	"border-top-"+radiusCSS+"-radius: "+this.buttonCornerRadius+"px;"+
	"border-bottom-"+radiusCSS+"-radius: "+this.buttonCornerRadius+"px;"+
	"} "+
	" .ZingayaWidget:hover {"+
	"background: linear-gradient(to right, "+this.buttonHoverGradientColor1+" 0%,"+this.buttonHoverGradientColor2+" 100%);"+
	"} ";
	this.widgetStyleString += " .ZingayaLink {"+
	"position:relative;"+
	"display: block;"+
	"width: 100%;"+
	"height: 100%;"+
	"background-image: url("+zg_url+"label_h-"+h+"_w-"+w+"_t-"+encodeURIComponent(this.buttonLabel).replace(/-/g,"^$^").replace(/_/g,"^$$^")+"_c-"+this.labelColor.replace("#", "")+"_fs-"+this.labelFontSize+"_is-"+iconSize+");"+ 
	"background-repeat: none;"+
	"background-position: center center;"+
	"background-size: "+w+"px "+h+"px;"+
	"} ";
	this.widgetStyleString += " .ZingayaLink:before {"+
	"content:'';left:0;top:0;position:absolute;width:100%;height:"+(this.labelFontSize+this.buttonPadding*2)+"px;"+
	"-moz-box-shadow: 0px 1px 0px 0px rgba(0, 0, 0, 0.13);"+	
	"-webkit-box-shadow: 0px 1px 0px 0px rgba(0, 0, 0, 0.13);"+
	"box-shadow: 0px 1px 0px 0px rgba(0, 0, 0, 0.13);"+
	"background: url("+zg_url+"zingaya_gc1-"+this.iconColor.replace("#","")+"_gc2-"+this.iconColor.replace("#","")+"_go1-"+this.iconOpacity+"_go2-"+this.iconOpacity+"_ds-"+(this.iconDropShadow?(this.iconShadowDirection=="bottom"?1:-1):0)+"_sc-"+this.iconShadowColor.replace("#", "")+"_so-"+this.iconShadowOpacity+(this.rotation===true?"_r-1":"")+") center center no-repeat;"+
	"background-size: "+iconSize+"px;"+
	"} ";
	this.widgetStyleString += " *html, * html body {"+
	"background-image:url(about:blank);"+
	"background-attachment:fixed;}"+
	" *html .ZingayaWidget {"+  
	"margin-top: 0px;"+
	"position:absolute;"+  
	"right: auto;"+
	(this.widgetPosition=="left"?"left:0px;":"left: expression(eval(document.compatMode && document.compatMode=='CSS1Compat')?documentElement.scrollLeft+(documentElement.clientWidth-this.clientWidth) - 1:document.body.scrollLeft+(document.body.clientWidth-this.clientWidth) - 1);")+
	"top:expression(eval(document.compatMode&&document.compatMode=='CSS1Compat')?documentElement.scrollTop+(documentElement.clientHeight-this.clientHeight)/2 - 1:document.body.scrollTop+(document.body.clientHeight-this.clientHeight)/2 - 1);"+
	"} "; 
	this.widgetStyleString += " .ZingayaLink.nosvg, .ZingayaLink.nosvg:hover {"+
	"background-image: url("+zg_url+"label_h-"+h+"_w-"+w+"_t-"+encodeURIComponent(this.buttonLabel).replace(/-/g,"^$^").replace(/_/g,"^$$^")+"_m-nosvg_c-"+this.labelColor.replace("#", "")+"_fs-"+this.labelFontSize+"_is-"+iconSize+"_p-"+this.buttonPadding+");"+
	"background-repeat: none;"+
	"background-position: center center;"+
	"} ";
	
	this.loadCSS(this.widgetStyleString);
	
	var z = document.createElement('div'), a = document.createElement('a');
	z.appendChild(a);
	a.className = 'ZingayaLink';
	z.className = 'ZingayaWidget';
	if (!this.SVG()) a.className += " nosvg";
        a.href = zg_proto + "//zingaya.com/widget/"+this.callme_id;
        var onclick = ""; 
        var polls = "";
        if ( this.poll_id !== null && this.poll_id !== "" ) {
            polls = "+'&extra='+escape('polls:true;poll_id:"+this.poll_id+"')";
        }

        if ( this.analytics_id !== null && this.analytics_id !== "" ) { 
            onclick = "typeof(_gaq)=='undefined'?'':_gaq.push(['_trackEvent', 'Zingaya', 'ButtonClick']);typeof(_gat)=='undefined'?'':_gat._getTrackerByName()._setAllowLinker(true); window.open(typeof(_gat)=='undefined'?this.href+'?referrer='+escape(window.location.href)"+polls+":_gat._getTrackerByName()._getLinkerUrl(this.href+'?referrer='+escape(window.location.href)"+polls+"), '_blank', 'width=236,height=220,resizable=no,toolbar=no,menubar=no,location=no,status=no'); return false;";
        } else {
            onclick = "window.open(this.href+'?referrer='+escape(window.location.href)"+polls+", '_blank', 'width=236,height=220,resizable=no,toolbar=no,menubar=no,location=no,status=no'); return false;";
        }
        
        a.setAttribute("onclick", onclick);
	document.body.appendChild(z);
}

ZingayaClass.prototype.load = function(config, id) {
	if (!this.SVG()) {
		var p = document.createElement('script'),s = document.getElementsByTagName('script')[0];p.src='PIE.js';p.async='true';s.parentNode.insertBefore(p, s);
		p.onload = p.onreadystatechange = function() {
			if (this.readyState && this.readyState != 'complete' && this.readyState != 'loaded') return;
			if (window.PIE) {		
				if (typeof id == 'undefined') {
					var elements = document.getElementsByClassName("zingayaButton");
					for (var i=0; i < elements.length; i++) PIE.attach(elements[i]);
				} else if (typeof id == 'string') {
					elements = document.getElementsByClassName(id);
					for (var k=0; k < elements.length; k++) PIE.attach(elements[k]);
				} else if (Object.prototype.toString.call( id ) === '[object Array]') {
					for (i=0; i < id.length; i++) {
						elements = document.getElementsByClassName(id[i]);
						for (var k=0; k < elements.length; k++) PIE.attach(elements[k]);
					}
				}
			}
	}}
	
	this.config = typeof config !== 'undefined' ? config : {};
	if (config.hasOwnProperty('type')) this.type = config.type;
        if (config.hasOwnProperty('callme_id')) this.callme_id = config.callme_id;
        if (config.hasOwnProperty('poll_id')) this.poll_id = config.poll_id;
        if (config.hasOwnProperty('analytics_id')) this.analytics_id = config.analytics_id;
	if (config.hasOwnProperty('rotation')) this.rotation = config.rotation;
	if (config.hasOwnProperty('buttonLabel')) this.buttonLabel = config.buttonLabel;
	if (config.hasOwnProperty('labelColor')) this.labelColor = config.labelColor;
	if (config.hasOwnProperty('labelFontSize')) this.labelFontSize = parseFloat(config.labelFontSize);
	if (config.hasOwnProperty('labelFontWeight')) this.labelFontWeight = config.labelFontWeight;
	if (config.hasOwnProperty('labelTextDecoration')) this.labelTextDecoration = config.labelTextDecoration;
	if (config.hasOwnProperty('labelFontWeight')) this.labelFontWeight = config.labelFontWeight;
	if (config.hasOwnProperty('labelShadowDirection')) this.labelShadowDirection = config.labelShadowDirection;
	if (config.hasOwnProperty('labelShadowColor')) this.labelShadowColor = config.labelShadowColor;
	if (config.hasOwnProperty('labelShadow')) this.labelShadow = config.labelShadow;
	if (config.hasOwnProperty('buttonBackgroundColor')) this.buttonBackgroundColor = config.buttonBackgroundColor;
	if (config.hasOwnProperty('buttonGradientColor1')) this.buttonGradientColor1 = config.buttonGradientColor1;
	if (config.hasOwnProperty('buttonGradientColor2')) this.buttonGradientColor2 = config.buttonGradientColor2;
	if (config.hasOwnProperty('buttonGradientColor3')) this.buttonGradientColor3 = config.buttonGradientColor3;
	if (config.hasOwnProperty('buttonGradientColor4')) this.buttonGradientColor4 = config.buttonGradientColor4;
	if (config.hasOwnProperty('buttonShadow')) this.buttonShadow = config.buttonShadow;	
	if (config.hasOwnProperty('buttonHoverBackgroundColor')) this.buttonHoverBackgroundColor = config.buttonHoverBackgroundColor;
	if (config.hasOwnProperty('buttonHoverGradientColor1')) this.buttonHoverGradientColor1 = config.buttonHoverGradientColor1;
	if (config.hasOwnProperty('buttonHoverGradientColor2')) this.buttonHoverGradientColor2 = config.buttonHoverGradientColor2;
	if (config.hasOwnProperty('buttonHoverGradientColor3')) this.buttonHoverGradientColor3 = config.buttonHoverGradientColor3;
	if (config.hasOwnProperty('buttonHoverGradientColor4')) this.buttonHoverGradientColor4 = config.buttonHoverGradientColor4;
	if (config.hasOwnProperty('buttonActiveShadowColor1')) this.buttonActiveShadowColor1 = config.buttonActiveShadowColor1;
	if (config.hasOwnProperty('buttonActiveShadowColor2')) this.buttonActiveShadowColor2 = config.buttonActiveShadowColor2;
	if (config.hasOwnProperty('buttonCornerRadius')) this.buttonCornerRadius = parseInt(config.buttonCornerRadius);
	if (config.hasOwnProperty('buttonPadding')) this.buttonPadding = parseInt(config.buttonPadding);
	if (config.hasOwnProperty('iconColor')) this.iconColor = config.iconColor;
	if (config.hasOwnProperty('iconOpacity')) this.iconOpacity = parseFloat(config.iconOpacity);
	if (config.hasOwnProperty('iconDropShadow')) this.iconDropShadow = config.iconDropShadow;
	if (config.hasOwnProperty('iconShadowColor')) this.iconShadowColor = config.iconShadowColor;
	if (config.hasOwnProperty('iconShadowDirection')) this.iconShadowDirection = config.iconShadowDirection;
	if (config.hasOwnProperty('iconShadowOpacity')) this.iconShadowOpacity = parseFloat(config.iconShadowOpacity);

	if (this.type == 'widget') {
		if (config.hasOwnProperty('widgetPosition')) this.widgetPosition = config.widgetPosition;
		this.loadWidget();
		return false;
	}
	
	var iconSize = Math.round((this.labelFontSize+this.buttonPadding*2)*0.7);
	if (iconSize <= 16) iconSize = 18;	
	var classSelector = hoverClassSelector = activeClassSelector = afterClassSelector = nosvgClassSelector = nosvgHoverClassSelector = nosvgActiveClassSelector = "";
	if (Object.prototype.toString.call( id ) === '[object Array]') {
		for (i=0; i < id.length; i++) {
			classSelector += ".zingayaButton."+id[i]+(i==(id.length-1)?" ":", ");
			hoverClassSelector += ".zingayaButton."+id[i]+":hover, .zingayaButton."+id[i]+(i==(id.length-1)?".nosvg:hover ":".nosvg:hover, ");
			activeClassSelector += ".zingayaButton."+id[i]+":active, .zingayaButton."+id[i]+(i==(id.length-1)?".nosvg:active ":".nosvg:active, ");
			afterClassSelector += ".zingayaButton."+id[i]+(i==(id.length-1)?":after ":":after, ");
			nosvgClassSelector += ".zingayaButton."+id[i]+(i==(id.length-1)?".nosvg ":".nosvg, ");
			nosvgHoverClassSelector += ".zingayaButton."+id[i]+(i==(id.length-1)?".nosvg:hover ":".nosvg:hover, ");
			nosvgActiveClassSelector += ".zingayaButton."+id[i]+(i==(id.length-1)?".nosvg:active ":".nosvg:active, ");
		}
	} else {
		if (this.type == 'button') {
			classSelector += ".zingayaButton." + id;
			hoverClassSelector += ".zingayaButton."+id+":hover ";
			activeClassSelector += ".zingayaButton."+id+":active ";
			afterClassSelector += ".zingayaButton."+id+":after ";
			nosvgClassSelector += ".zingayaButton."+id+".nosvg ";
			nosvgHoverClassSelector += ".zingayaButton."+id+".nosvg:hover ";
			nosvgActiveClassSelector += ".zingayaButton."+id+".nosvg:active ";
		}
	}

	this.cssStyleString = classSelector+" {"+
        "z-index: 99999999;"+
	"display: inline-block;position:relative;"+
	"font-size:"+this.labelFontSize+"px;text-decoration:"+this.labelTextDecoration+";"+
	"color:"+this.labelColor+";font-family:'Helvetica Neue', Arial, 'Helvetica CY', 'Nimbus Sans L', sans-serif;"+
	"font-weight:"+this.labelFontWeight+";padding:"+this.buttonPadding+"px;"+
	(this.buttonShadow?"box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.5);":"box-shadow: none;")+
	"border-radius:"+this.buttonCornerRadius+"px;"+
	"background-color:"+this.buttonBackgroundColor+";"+
	(this.labelShadow===true?("text-shadow: 0px "+(this.labelShadowDirection=="bottom"?"1px":"-1px")+" 0px "+this.labelShadowColor+";"):"text-shadow: none;")+
	"padding-left:"+(this.labelFontSize+this.buttonPadding*3+2)+"px;"+
	"padding-right:"+(this.buttonPadding+6)+"px;"+
	"user-select:none;"+
	"background: -moz-linear-gradient(top, "+this.buttonGradientColor1+" 4%, "+this.buttonGradientColor2+" 4%, "+this.buttonGradientColor1+" 79%, "+this.buttonGradientColor3+" 93%, "+this.buttonGradientColor4+" 95%, "+this.buttonGradientColor1+" 100%);"+
	"background: -webkit-linear-gradient(top, "+this.buttonGradientColor1+" 4%,"+this.buttonGradientColor2+" 4%,"+this.buttonGradientColor1+" 79%,"+this.buttonGradientColor3+" 93%,"+this.buttonGradientColor4+" 95%,"+this.buttonGradientColor1+" 100%);"+
	"background: -o-linear-gradient(top, "+this.buttonGradientColor1+" 4%,"+this.buttonGradientColor2+" 4%,"+this.buttonGradientColor1+" 79%,"+this.buttonGradientColor3+" 93%,"+this.buttonGradientColor4+" 95%,"+this.buttonGradientColor1+" 100%);"+
	"background: -ms-linear-gradient(top, "+this.buttonGradientColor1+" 4%,"+this.buttonGradientColor2+" 4%,"+this.buttonGradientColor1+" 79%,"+this.buttonGradientColor3+" 93%,"+this.buttonGradientColor4+" 95%,"+this.buttonGradientColor1+" 100%);"+
	"background: linear-gradient(to bottom, "+this.buttonGradientColor1+" 4%,"+this.buttonGradientColor2+" 4%,"+this.buttonGradientColor1+" 79%,"+this.buttonGradientColor3+" 93%,"+this.buttonGradientColor4+" 95%,"+this.buttonGradientColor1+" 100%);"+
	"} ";
	this.cssStyleString += hoverClassSelector+" {"+
	"background-color:"+this.buttonHoverBackgroundColor+";"+
	"background: -moz-linear-gradient(top, "+this.buttonHoverGradientColor1+" 4%, "+this.buttonHoverGradientColor2+" 4%, "+this.buttonHoverGradientColor1+" 79%, "+this.buttonHoverGradientColor3+" 93%, "+this.buttonHoverGradientColor4+" 95%, "+this.buttonHoverGradientColor1+" 100%);"+
	"background: -webkit-linear-gradient(top, "+this.buttonHoverGradientColor1+" 4%,"+this.buttonHoverGradientColor2+" 4%,"+this.buttonHoverGradientColor1+" 79%,"+this.buttonHoverGradientColor3+" 93%,"+this.buttonHoverGradientColor4+" 95%,"+this.buttonHoverGradientColor1+" 100%);"+
	"background: -o-linear-gradient(top, "+this.buttonHoverGradientColor1+" 4%,"+this.buttonHoverGradientColor2+" 4%,"+this.buttonHoverGradientColor1+" 79%,"+this.buttonHoverGradientColor3+" 93%,"+this.buttonHoverGradientColor4+" 95%,"+this.buttonHoverGradientColor1+" 100%);"+
	"background: -ms-linear-gradient(top, "+this.buttonHoverGradientColor1+" 4%,"+this.buttonHoverGradientColor2+" 4%,"+this.buttonHoverGradientColor1+" 79%,"+this.buttonHoverGradientColor3+" 93%,"+this.buttonHoverGradientColor4+" 95%,"+this.buttonHoverGradientColor1+" 100%);"+
	"background: linear-gradient(to bottom, "+this.buttonHoverGradientColor1+" 4%,"+this.buttonHoverGradientColor2+" 4%,"+this.buttonHoverGradientColor1+" 79%,"+this.buttonHoverGradientColor3+" 93%,"+this.buttonHoverGradientColor4+" 95%,"+this.buttonHoverGradientColor1+" 100%);"+
	"} ";
	this.cssStyleString += activeClassSelector+" {"+
	"-moz-box-shadow: 0px 1px 0px 0px "+this.hex2Rgba(this.buttonActiveShadowColor1, 0.4)+",inset 0px 1px 10px 0px "+this.hex2Rgba(this.buttonActiveShadowColor2, 0.8)+";"+
	"-webkit-box-shadow:  0px 1px 0px 0px "+this.hex2Rgba(this.buttonActiveShadowColor1, 0.4)+",inset 0px 1px 10px 0px "+this.hex2Rgba(this.buttonActiveShadowColor2, 0.8)+";"+
	"box-shadow:  0px 1px 0px 0px "+this.hex2Rgba(this.buttonActiveShadowColor1, 0.4)+", inset 0px 1px 10px 0px "+this.hex2Rgba(this.buttonActiveShadowColor2, 0.8)+";"+
	"} ";
	this.cssStyleString += afterClassSelector+" {"+
	"content:'';left:0;top:0;position:absolute;height:100%;width:"+(this.labelFontSize+this.buttonPadding*2)+"px;"+
	"-moz-box-shadow: 1px 0px 0px 0px rgba(0, 0, 0, 0.13);"+	
	"-webkit-box-shadow: 1px 0px 0px 0px rgba(0, 0, 0, 0.13);"+
	"box-shadow: 1px 0px 0px 0px rgba(0, 0, 0, 0.13);"+
	"background: url("+zg_url+"zingaya_gc1-"+this.iconColor.replace("#","")+"_gc2-"+this.iconColor.replace("#","")+"_go1-"+this.iconOpacity+"_go2-"+this.iconOpacity+"_ds-"+(this.iconDropShadow?(this.iconShadowDirection=="bottom"?1:-1):0)+"_sc-"+this.iconShadowColor.replace("#", "")+"_so-"+this.iconShadowOpacity+") center center no-repeat;"+
	"background-size: "+iconSize+"px;"+
	"} ";
	this.cssStyleString += nosvgClassSelector+" {"+
	"background-image: url("+zg_url+"zingaya_gc1-"+this.iconColor.replace("#","")+"_gc2-"+this.iconColor.replace("#","")+"_go1-"+this.iconOpacity+"_go2-"+this.iconOpacity+"_ds-"+(this.iconDropShadow?(this.iconShadowDirection=="bottom"?1:-1):0)+"_sc-"+this.iconShadowColor.replace("#", "")+"_so-"+this.iconShadowOpacity+"_is-"+iconSize+"_m-nosvg);"+
	"background-position: "+parseInt((this.labelFontSize+this.buttonPadding*2-iconSize)/2)+"px center;"+
	"background-repeat:no-repeat;"+ 
	"background-color:"+this.buttonBackgroundColor+";"+
	"-pie-background: url("+zg_url+"zingaya_gc1-"+this.iconColor.replace("#","")+"_gc2-"+this.iconColor.replace("#","")+"_go1-"+this.iconOpacity+"_go2-"+this.iconOpacity+"_ds-"+(this.iconDropShadow?(this.iconShadowDirection=="bottom"?1:-1):0)+"_sc-"+this.iconShadowColor.replace("#", "")+"_so-"+this.iconShadowOpacity+"_is-"+iconSize+"_m-nosvg) "+parseInt((this.labelFontSize+this.buttonPadding*2-iconSize)/2)+"px center no-repeat, linear-gradient(top, "+this.buttonGradientColor1+" 4%,"+this.buttonGradientColor2+" 4%,"+this.buttonGradientColor1+" 79%,"+this.buttonGradientColor3+" 93%,"+this.buttonGradientColor4+" 95%,"+this.buttonGradientColor1+" 100%);"+
	"box-shadow: #666 0px 1px 3px;"+
	"} ";
	this.cssStyleString += nosvgHoverClassSelector+" {"+
	"background-image: url("+zg_url+"zingaya_gc1-"+this.iconColor.replace("#","")+"_gc2-"+this.iconColor.replace("#","")+"_go1-"+this.iconOpacity+"_go2-"+this.iconOpacity+"_ds-"+(this.iconDropShadow?(this.iconShadowDirection=="bottom"?1:-1):0)+"_sc-"+this.iconShadowColor.replace("#", "")+"_so-"+this.iconShadowOpacity+"_is-"+iconSize+"_m-nosvg);"+
	"background-position: "+parseInt((this.labelFontSize+this.buttonPadding*2-iconSize)/2)+"px center;"+
	"background-repeat:no-repeat;"+ 
	"background-color:"+this.buttonHoverBackgroundColor+";"+
	"-pie-background: url("+zg_url+"zingaya_gc1-"+this.iconColor.replace("#","")+"_gc2-"+this.iconColor.replace("#","")+"_go1-"+this.iconOpacity+"_go2-"+this.iconOpacity+"_ds-"+(this.iconDropShadow?(this.iconShadowDirection=="bottom"?1:-1):0)+"_sc-"+this.iconShadowColor.replace("#", "")+"_so-"+this.iconShadowOpacity+"_is-"+iconSize+"_m-nosvg) "+parseInt((this.labelFontSize+this.buttonPadding*2-iconSize)/2)+"px center no-repeat, linear-gradient(top, "+this.buttonHoverGradientColor1+" 4%,"+this.buttonHoverGradientColor2+" 4%,"+this.buttonHoverGradientColor1+" 79%,"+this.buttonHoverGradientColor3+" 93%,"+this.buttonHoverGradientColor4+" 95%,"+this.buttonHoverGradientColor1+" 100%);"+
	"} ";
	this.cssStyleString += nosvgActiveClassSelector+" {"+
	"box-shadow: none;"+
	"} ";
	
	this.loadCSS(this.cssStyleString);
	
	if (typeof id == 'undefined') {
		var elements = document.getElementsByClassName("zingayaButton");
		for (var i=0; i < elements.length; i++) {
			elements[i].innerHTML = this.buttonLabel;
			if (!this.SVG()) elements[i].className += " nosvg";
		}
	} else if (typeof id == 'string') {
		elements = document.getElementsByClassName(id);
		for (var k=0; k < elements.length; k++) {
			elements[k].innerHTML = this.buttonLabel;
			if (!this.SVG()) elements[k].className += " nosvg";
		}
	} else if (Object.prototype.toString.call( id ) === '[object Array]') {
		for (i=0; i < id.length; i++) {
			elements = document.getElementsByClassName(id[i]);
			for (var k=0; k < elements.length; k++) {
				elements[k].innerHTML = this.buttonLabel;
				if (!this.SVG()) elements[k].className += " nosvg";
			}
		}
	}
}

ZingayaClass.prototype.Config = function() {
	return { 
            buttonLabel: this.buttonLabel, 
            labelColor: this.labelColor, 
            labelFontSize:this.labelFontSize, 
            labelTextDecoration: this.labelTextDecoration, 
            labelFontWeight: this.labelFontWeight, 
            labelShadowDirection: this.labelShadowDirection,
            labelShadowColor: this.labelShadowColor, 
            labelShadow: this.labelShadow, 
            buttonBackgroundColor: this.buttonBackgroundColor, 
            buttonGradientColor1: this.buttonGradientColor1, 
            buttonGradientColor2: this.buttonGradientColor2, 
            buttonGradientColor3: this.buttonGradientColor3, 
            buttonGradientColor4: this.buttonGradientColor4, 
            buttonShadow: this.buttonShadow, 
            buttonHoverBackgroundColor: this.buttonHoverBackgroundColor, 
            buttonHoverGradientColor1: this.buttonHoverGradientColor1, 
            buttonHoverGradientColor2: this.buttonHoverGradientColor2, 
            buttonHoverGradientColor3: this.buttonHoverGradientColor3, 
            buttonHoverGradientColor4: this.buttonHoverGradientColor4, 
            buttonActiveShadowColor1: this.buttonActiveShadowColor1, 
            buttonActiveShadowColor2: this.buttonActiveShadowColor2,	 
            buttonCornerRadius: this.buttonCornerRadius, 
            buttonPadding: this.buttonPadding, 
            iconColor: this.iconColor, 
            iconOpacity: this.iconOpacity, 
            iconDropShadow: this.iconDropShadow, 
            iconShadowColor: this.iconShadowColor, 
            iconShadowDirection: this.iconShadowDirection, 
            iconShadowOpacity:this.iconShadowOpacity,
            callme_id:this.callme_id,
            poll_id:this.poll_id,
            analytics_id:this.analytics_id,
        };
}

Zingaya = new ZingayaClass();