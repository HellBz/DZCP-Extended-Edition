function Pool(e){var t=this;this.taskQueue=[];this.workerQueue=[];this.poolSize=e;this.addWorkerTask=function(e){if(t.workerQueue.length>0){var n=t.workerQueue.shift();n.run(e)}else{t.taskQueue.push(e)}};this.init=function(){for(var n=0;n<e;n++){t.workerQueue.push(new WorkerThread(t))}};this.freeWorkerThread=function(e){if(t.taskQueue.length>0){var n=t.taskQueue.shift();e.run(n)}else{t.taskQueue.push(e)}}}function WorkerThread(e){function n(e){t.workerTask.callback(e);t.parentPool.freeWorkerThread(t)}var t=this;this.parentPool=e;this.workerTask={};this.run=function(e){this.workerTask=e;var t=new Worker(json.tmpdir+"/_js/worker/XMLHttpRequest.js");t.addEventListener("message",n,false);t.postMessage(e.startMessage)}}function WorkerTask(e,t){this.callback=e;this.startMessage=t}var doc=document,ie4=document.all,opera=window.opera;var innerLayer,layer,x,y,doWheel=false,offsetX=15,offsetY=5;var tickerc=0,mTimer=new Array,tickerTo=new Array,tickerSpeed=new Array;var isIE=navigator.appVersion.indexOf("MSIE")!=-1?true:false;var isWin=navigator.appVersion.toLowerCase().indexOf("win")!=-1?true:false;var isOpera=navigator.userAgent.indexOf("Opera")!=-1?true:false;var shoutInterval=2e4;var index=new Array;var i=0;var pool=new Pool(6);var DZCP={init:function(){doc.body.id="dzcp-ee-engine-1.6";$("body").append('<div id="infoDiv"></div>');$("body").append('<div id="dialog"></div>');layer=$("#infoDiv")[0];doc.body.onmousemove=DZCP.trackMouse;if($("#navShout")[0])window.setInterval("$('#navShout').load('../inc/ajax.php?i=shoutbox');",shoutInterval);DZCP.initJQueryUI();DZCP.resizeImages();DZCP.initWorker();images=new Array;images[0]="../inc/images/lvl0.jpg";images[1]="../inc/images/lvl1.jpg";images[2]="../inc/images/lvl2.jpg";images[3]="../inc/images/lvl3.jpg";images[4]="../inc/images/lvl4.jpg";images[5]="../inc/images/lvl5.jpg";images[6]="../inc/_templates_/version1.6/_css/smoothness/images/animated-overlay.gif";images[7]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_diagonals-medium_40_d72119_40x40.png";images[8]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_flat_0_000000_40x100.png";images[9]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_flat_0_fafafa_40x100.png";images[10]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_flat_30_d01515_40x100.png";images[11]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_glass_60_ba1512_1x400.png";images[12]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_glass_65_b2120c_1x400.png";images[13]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_highlight-hard_30_ffffff_1x100.png";images[14]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_highlight-soft_60_de1111_1x100.png";images[15]="../inc/_templates_/version1.6/_css/smoothness/images/ui-bg_highlight-soft_65_e32522_1x100.png";images[16]="../inc/_templates_/version1.6/_css/smoothness/images/ui-icons_2e83ff_256x240.png";images[17]="../inc/_templates_/version1.6/_css/smoothness/images/ui-icons_222fbe_256x240.png";images[18]="../inc/_templates_/version1.6/_css/smoothness/images/ui-icons_222222_256x240.png";images[19]="../inc/_templates_/version1.6/_css/smoothness/images/ui-icons_ffffff_256x240.png";imageObj=new Image;var e;for(e=0;e<=3;e++){imageObj.src=images[e]}if(json.extern=="1")$("a:not([href*="+json.domain+"])").attr("target","_blank")},loadNow:function(){},resizeNow:function(){},checkPassword:function(e){var t=new Array;var n=0;t[0]='<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl1.jpg" width="200" height="18" /></td></tr></table>';t[1]='<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl2.jpg" width="200" height="18" /></td></tr></table>';t[2]='<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl3.jpg" width="200" height="18" /></td></tr></table>';t[3]='<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl4.jpg" width="200" height="18" /></td></tr></table>';t[4]='<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl5.jpg" width="200" height="18" /></td></tr></table>';t[5]='<table><tr><td><table><tr><td height=4 width=150 ><img src="../inc/images/lvl0.jpg" width="200" height="18" /></td></tr></table>';var r=0;if(e.match(/[a-z]/)){r=r+26}if(e.match(/[A-Z]/)){r=r+26}if(e.match(/\d+/)){r=r+7}if(e.match(/(\d.*\d.*\d)/)){r=r+5}if(e.match(/[!",@#$%^&*?_~�$%&/\()=?`���\][}��;:������]/)){r=r+40}if(e.match(/([!,@#$%^&*?_~].*[!,@#$%^&*?_~])/)){r=r+23}if(e.match(/[a-z]/)&&e.match(/[A-Z]/)){r=r+26}if(e.match(/\d/)&&e.match(/\D/)){r=r+5}if(e.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/)){r=r+5}if(e.match(/[a-z]/)&&e.match(/[A-Z]/)&&e.match(/\d/)&&e.match(/[!,@#$%^&*?_~]/)){r=r+5}var i=Math.pow(r,e.length);if(i==1){$("#Words").html(t[5])}else if(i>1&&i<1e6){$("#Words").html(t[0])}else if(i>=1e6&&i<1e12){$("#Words").html(t[1])}else if(i>=1e12&&i<1e18){$("#Words").html(t[2])}else if(i>=1e18&&i<1e24){$("#Words").html(t[3])}else{$("#Words").html(t[4])}},initJQueryUI:function(){$(".slidetabs").tabs(".images > div",{effect:"fade",rotate:true}).slideshow({autoplay:true,interval:6e3});$(".tabs").tabs("> .switchs",{effect:"fade"});$(".nav").button({text:true});$("#rerun").button().click(function(){return false}).next().button({text:false,icons:{primary:"ui-icon-triangle-1-s"}}).click(function(){var e=$(this).parent().next().show().position({my:"left top",at:"left bottom",of:this});$(document).one("click",function(){e.hide()});return false}).parent().buttonset().next().hide().menu();$("[title]").tooltip({track:true,delay:2,fade:250});$("#dialog").dialog({modal:true,bgiframe:true,width:"auto",height:"auto",autoOpen:false,title:"Info"});$("a.confirm").click(function(e){e.preventDefault();var t="";var n=$(this).attr("href");var r=$(this).attr("rel");var i=r==undefined||r==""?t:r;var s='<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>';var o={};o[decodeURIComponent(escape(json.dialog_button_00))]=function(){window.location.href=n};o[decodeURIComponent(escape(json.dialog_button_01))]=function(){$(this).dialog("close")};$("#dialog").html("<P>"+s+i+"</P>");$("#dialog").dialog("option","buttons",o);$("#dialog").dialog("option","position",["center",document.body.clientHeight/3]);$("#dialog").dialog("open")});DZCP.UpdateJQueryUI()},UpdateJQueryUI:function(){$("input[type=submit]").button().click(function(){$(this).find("form").submit()});$("input[type=button]").button().click(function(){$(this).find("form").submit()})},addEvent:function(e,t,n){if(e.addEventListener){e.addEventListener(t,n,false);return true}else if(e.attachEvent){return e.attachEvent("on"+t,n)}else return false},trackMouse:function(e){innerLayer=$("#infoInnerLayer")[0];if(typeof layer=="object"){var t=doc.all;var n=doc.getElementById&&!doc.all;var r=5;var i=-15;x=n?e.pageX-r:window.event.clientX+doc.documentElement.scrollLeft-r;y=n?e.pageY-i:window.event.clientY+doc.documentElement.scrollTop-i;if(innerLayer){var s=(t?innerLayer.offsetWidth:innerLayer.clientWidth)-3;var o=t?innerLayer.offsetHeight:innerLayer.clientHeight}else{var s=(t?layer.clientWidth:layer.offsetWidth)-3;var o=t?layer.clientHeight:layer.offsetHeight}var u=n?window.innerWidth+window.pageXOffset-12:doc.documentElement.clientWidth+doc.documentElement.scrollLeft;var a=n?window.innerHeight+window.pageYOffset:doc.documentElement.clientHeight+doc.documentElement.scrollTop;layer.style.left=(x+offsetX+s>=u-offsetX?x-(s+offsetX):x+offsetX)+"px";layer.style.top=y+offsetY+"px"}return true},popup:function(e,t,n){t=parseInt(t);n=parseInt(n)+50;popup=window.open(e,"Popup","width=1,height=1,location=0,scrollbars=0,resizable=1,status=0");popup.resizeTo(t,n);popup.moveTo((screen.width-t)/2,(screen.height-n)/2);popup.focus()},initPageDynLoader:function(e,t){if(typeof Worker!=="undefined"&&json.worker=="1"){index[i]=new Array(e,"../../../../"+t);i++}else{var n=$.ajax({url:t,type:"GET",data:{},cache:true,dataType:"html",contentType:"application/x-www-form-urlencoded; charset=iso-8859-1"});n.done(function(t){$("#"+e).html(t).hide().fadeIn("normal")});DZCP.UpdateJQueryUI()}},initWorker:function(){if(typeof Worker!=="undefined"&&json.worker=="1"){pool.init();for(var e in index){data=index[e];var t=new WorkerTask(DZCP.callback_replacement,{ajax:data[1],tag:data[0],post:false,postdata:""});pool.addWorkerTask(t)}}},callback_replacement:function(e){worker=e.data;$("#"+worker.tag).html(worker.data).hide().fadeIn("normal");DZCP.UpdateJQueryUI()},initDynLoader:function(e,t,n){if(typeof Worker!=="undefined"&&json.worker=="1"){index[i]=new Array(e,"../../../../ajax.php?loader=menu&mod="+t+n);i++}else{var r=$.ajax({url:"../inc/ajax.php?loader=menu&mod="+t+n,type:"GET",data:{},cache:true,dataType:"html",contentType:"application/x-www-form-urlencoded; charset=iso-8859-1"});r.done(function(t){$("#"+e).html(t).hide().fadeIn("normal")});DZCP.UpdateJQueryUI()}},shoutSubmit:function(){$.post("../shout/index.php?ajax",$("#shoutForm").serialize(),function(e){if(e)alert(e.replace(/  /g," "));$("#navShout").load("../inc/ajax.php?i=shoutbox");if(!e)$("#shouteintrag").prop("value","");DZCP.UpdateJQueryUI()});return false},switchuser:function(){var e=doc.formChange.changeme.options[doc.formChange.changeme.selectedIndex].value;window.location.href=e},tempswitch:function(){var e=doc.form.tempswitch.options[doc.form.tempswitch.selectedIndex].value;if(e!="lazy")DZCP.goTo(e)},goTo:function(e,t){if(t==1)window.open(e);else window.location.href=e},maxlength:function(e,t,n){if(e.value.length>n)e.value=e.value.substring(0,n);else t.value=n-e.value.length},showInfo:function(e,t,n,r,i,s){if(typeof layer=="object"){var o="";if(t&&n){var u=t.split(";");var a=n.split(";");var f="";for(var l=0;l<u.length;++l){f=f+"<tr><td>"+u[l]+"</td><td>"+a[l]+"</td></tr>"}o='<tr><td class="infoTop" colspan="2">'+e+"</td></tr>"+f+""}else if(t&&typeof n=="undefined"){o='<tr><td class="infoTop" colspan="2">'+e+"</td></tr><tr><td>"+t+"</td></tr>"}else{o="<tr><td>"+e+"</td></tr>"}var c="";if(r){c='<tr><td colspan=2 align=center><img src="'+r+'" width="'+i+'" height="'+s+'" alt="" /></td></tr>'}else{c=""}layer.innerHTML='<div id="hDiv">'+'  <table class="hperc" cellspacing="0" style="height:100%">'+"    <tr>"+'      <td style="vertical-align:middle">'+'        <div id="infoInnerLayer">'+'          <table class="hperc" cellspacing="0">'+"              "+o+""+"              "+c+""+"          </table>"+"        </div>"+"      </td>"+"    </tr>"+"  </table>"+"</div>";if(ie4&&!opera){layer.innerHTML+='<iframe id="ieFix" frameborder="0" width="'+$("#hDiv")[0].offsetWidth+'" height="'+$("#hDiv")[0].offsetHeight+'"></iframe>';layer.style.display="block"}else layer.style.display="block"}},showSteamBox:function(e,t,n,r,i){var s;switch(i){case 1:s="online";break;case 2:s="in-game";break;default:s="offline";break}if(typeof layer=="object"){layer.innerHTML='<div id="hDiv">'+'  <table class="hperc" cellspacing="0" style="height:100%">'+"    <tr>"+'      <td style="vertical-align:middle">'+'        <div id="infoInnerLayer">'+'        	 <table class="steam_box_bg" border="0" cellspacing="0" cellpadding="0">'+"	          <tr>"+"	            <td>"+'	               <div class="steam_box steam_box_user '+s+'">'+'	                 <div class="steam_box_avatar '+s+'"> <img src="'+t+'" /></div>'+'	                 <div class="steam_box_content">'+e+"<br />"+'	                 <span class="friendSmallText">'+n+"<br>"+r+"</span></div>"+"	               </div>"+"	            </td>"+"	          </tr>"+"	        </table>"+"        </div>"+"      </td>"+"    </tr>"+"  </table>"+"</div>";if(ie4&&!opera){layer.innerHTML+='<iframe id="ieFix" frameborder="0" width="'+$("#hDiv")[0].offsetWidth+'" height="'+$("#hDiv")[0].offsetHeight+'"></iframe>';layer.style.display="block"}else layer.style.display="block"}},hideInfo:function(){if(typeof layer=="object"){layer.innerHTML="";layer.style.display="none"}},toggle:function(e){if(e==0)return;if($("#more"+e).css("display")=="none"){$("#more"+e).fadeIn("normal");$("#img"+e).prop("src","../inc/images/collapse.gif")}else{$("#more"+e).fadeOut("normal");$("#img"+e).prop("src","../inc/images/expand.gif")}},fadetoggle:function(e){if(e==0)return;$("#more_"+e).fadeToggle("slow","swing");if($("#img_"+e).prop("alt")=="hidden")$("#img_"+e).prop({alt:"normal",src:"../inc/images/toggle_normal.png"});else $("#img_"+e).prop({alt:"hidden",src:"../inc/images/toggle_hidden.png"})},calSwitch:function(e,t){$("#navKalender").load("../inc/ajax.php?i=kalender&month="+e+"&year="+t)},teamSwitch:function(e){clearTimeout(mTimer[1]);$("#navTeam").load("../inc/ajax.php?i=teams&tID="+e,DZCP.initTicker("teams","h",60))},ajaxVote:function(e){DZCP.submitButton("contentSubmitVote");$.post("../votes/index.php?action=do&ajax=1&what=vote&id="+e,$("#navAjaxVote").serialize(),function(e){$("#navVote").html(e)})},ajaxFVote:function(e){DZCP.submitButton("contentSubmitFVote");$.post("../votes/index.php?action=do&fajax=1&what=fvote&id="+e,$("#navAjaxFVote").serialize(),function(e){$("#navFVote").html(e)})},ajaxPreview:function(e){var t=doc.getElementsByTagName("textarea");for(var n=0;n<t.length;n++){var r=t[n].className;var i=t[n].id;if(r=="editorStyle"||r=="editorStyleWord"||r=="editorStyleNewsletter")$("#"+i).prop("value",tinyMCE.get(i).getBody().innerHTML)}$("#previewDIV").html('<div style="width:100%;text-align:center"><img src="../inc/images/ajax-loader-bar.gif" alt="" /></div>');var s=prevURL;var o=e=="cwForm"?"&s1="+$("#screen1").prop("value")+"&s2="+$("#screen2").prop("value")+"&s3="+$("#screen3").prop("value")+"&s4="+$("#screen4").prop("value"):"";$.post(s,$("#"+e).serialize()+o,function(e){$("#previewDIV").html(e)})},hideForumFirst:function(){$("#allkat").prop("checked",false)},hideForumAll:function(){for(var e=0;e<doc.forms["search"].elements.length;e++){var t=doc.forms["search"].elements[e];if(t.id.match(/k_/g))t.checked=false}},submitButton:function(e){submitID=e?e:"contentSubmit";$("#"+submitID).prop("disabled",true);$("#"+submitID).css("color","#909090");$("#"+submitID).css("cursor","default");return true},initTicker:function(e,t,n){tickerTo[tickerc]=t=="h"||t=="v"?t:"v";tickerSpeed[tickerc]=parseInt(n)<=10?10:parseInt(n);var r=$("#"+e).html();var i='  <div id="scrollDiv'+tickerc+'" class="scrollDiv" style="position:relative;left:0;z-index:1">';i+='    <table id="scrollTable'+tickerc+'" class="scrolltable"  cellpadding="0" cellspacing="0">';i+="      <tr>";i+='        <td onmouseover="clearTimeout(mTimer['+tickerc+'])" onmouseout="DZCP.startTickerDiv('+tickerc+')">';for(var s=0;s<10;s++)i+=r;i+="        </td>";i+="      </tr>";i+="    </table>";i+="  </div>";$("#"+e).html(i);window.setTimeout("DZCP.startTickerDiv("+tickerc+");",1500);tickerc++},startTickerDiv:function(e){tableObj=$("#scrollTable"+e)[0];obj=tableObj.parentNode;objWidth=tickerTo[e]=="h"?tableObj.offsetWidth:tableObj.offsetHeight;newWidth=Math.floor(objWidth/2)*2+2;obj.style.width=newWidth;mTimer[e]=setInterval("DZCP.moveDiv('"+obj.id+"', "+newWidth+", "+e+");",tickerSpeed[e])},moveDiv:function(e,t,n){var r=$("#"+e)[0];if(tickerTo[n]=="h")r.style.left=parseInt(r.style.left)<=0-t/2+2?0:parseInt(r.style.left)-1+"px";else r.style.top=r.style.top==""||parseInt(r.style.top)<0-t/2+6?0:parseInt(r.style.top)-1+"px"},check_all:function(e,t){if(!t||!t.form)return false;var n=t.form.elements[e];if(!n)return false;if(!n.length)n.checked=t.checked;else for(var r=0;r<n.length;r++)n[r].checked=t.checked},sendFrom:function(e,t,n){$("input[name="+e+"]").val(t);$("#"+n).submit()},resizeImages:function(){$("table.mainContent img").each(function(e){var t=$(this).width();var n=$(this).height();if(t>json.maxW){var r=json.maxW;var i=Math.round(n*(json.maxW/t));var s=$(this).attr("src");var o=$(this).attr("alt");if(!$(this).parent().attr("href"))$(this).replaceWith('<div style="opacity: 1; height: '+i+"px; width: "+r+'px; overflow: hidden;"><a data-lightbox=\'resize_mainContent\' class="field-anchor" href="'+s+'"><img class="resizeImage" alt="'+o+'" src="'+s+'" /></a></div><span class="resized">auto resized to '+t+"x"+n+"px</span>");else $(this).replaceWith('<div style="opacity: 1; height: '+i+"px; width: "+r+'px; overflow: hidden;"><img class="resizeImage" alt="'+o+'" src="'+s+'" /></div><span class="resized">auto resized to '+t+"x"+n+"px</span>");$("img.resizeImage").imgscale({fade:200,WidthPX:r,HeightPX:i})}})}};$(document).ready(function(){DZCP.init()});$(window).load(function(){DZCP.loadNow()});$(window).resize(function(){DZCP.resizeNow()})