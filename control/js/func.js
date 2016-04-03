function checksystem()
{
    a = navigator.appVersion.indexOf("Windows ");
    strtemp = navigator.appVersion.substring(a+8,a+10);
    if("9"==navigator.appVersion.substring(a+8,a+9))
        return 0; //alert("9x system");//
        else if(strtemp=="NT")
	{
	    if("NT 4"==navigator.appVersion.substring(a+8,a+12))
	        return 1; //alert("NT system");//
	    else if("NT 5"==navigator.appVersion.substring(a+8,a+12))
	        return 2; //alert("2000 system");//
	    else
	        return 3;//alert("NTServer system");//
	}
    else return -1; //alert("Unknow system");//
}



function ValidIever()
{
	Ver = navigator.appVersion;
	str = "MSIE "; end = ";"; 
	//alert(Ver.charAt(Ver.indexOf(str)+5)+Ver.charAt(Ver.indexOf(str)+6)+Ver..charAt(Ver.indexOf(str)+7));

	if(Ver.charAt(Ver.indexOf(str)+5)>="5") return true;
	else return false;
}

function IsEmpty( str )
{
  var i,j;
  if(str == "") return true;

  for(i=0;i<str.length;i++)
    if(str.charAt(i) != ' ') return false;
  if(i >= str.length) return true;
}

function trimString( str )
{
  var i,j;
  if(str == "") return "";

  for(i=0;i<str.length;i++)
    if(str.charAt(i) != ' ') break;
  if(i >= str.length) return "";

  for(j=str.length-1;j>=0;j--)
    if(str.charAt(j) != ' ') break;

  return str.substring(i,j+1);
}

function OpenWindowAtCenter(  szUrl, wWidth, wHeight, scroolbars )
{
	pleft = ( window.screen.availWidth - wWidth ) / 2;
	ptop = ( window.screen.availHeight - wHeight ) / 2;
	flags = "width=" + wWidth + ", height=" + wHeight + ", top=" + ptop + ", eft=" + pleft + ", resizable=0, location=0, menubar=0, scrollbars=" + scroolbars + ", status=0, toolbar=0";
	Window.open( szUrl,"",flags);
}

//------------------------- Set Cookie Start -------------------------//
	var days = 180;
	var path = "/";
	var expdate = new Date();
	expdate.setTime (expdate.getTime() + (86400 * 1000 * days));
//	expdate.setTime (expdate.getTime() + (1000 * 60 * 5));
	
	function setCookie(name,value,expires,path)
	{
		document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString() +  "; path=" + path;
	}
	
	function getCookie(name)
	{
		var search;
		
		search = name + "="
		offset = document.cookie.indexOf(search) ;
		if (offset != -1)
		{
			offset += search.length ;
			end = document.cookie.indexOf(";", offset) ;
			if (end == -1)
			  end = document.cookie.length;
			return unescape(document.cookie.substring(offset, end));
		}
		else
			return "";
	}
	
	function deleteCookie(name)
	{
		var expdate = new Date();
		expdate.setTime(expdate.getTime() - (86400 * 1000 * 1));
		setCookie(name, "", expdate);
	}
	
	function setCookieVal(name,val)
	{
		setCookie(name,val,expdate,path);
	}
	
	function getUserAgent()
	{
		var temp;
		temp = navigator.userAgent;
		var search = "Assistant ";
		var offset = temp.indexOf(search); 
		if (offset != -1)
		{
			offset += search.length ;
			var end = temp.indexOf(";", offset);
			if (end == -1)
			  	end = temp.length - 1;
			return temp.substring(offset, end);
		}
		else
			return "";		
	}
	
	function readCookie( Obj, page )
	{
		//if install baidu...
		var str;
		str = getCookie( "eservice" );
		if( str == "1.0.2.3" )
		{
			return true;
		}
		else
		{
			var str2;
			str2 = getUserAgent();
			if( str2 == "1.0.2.3" )
			{
				return true;
			}
			Obj.location = "http://assistant.3721.com/hint.htm?page=" + page;
		}
	}
	
	
function SaveCookie()
{
	try
	{
		var str1="";
		var temp;
		str1 = getCookie( "assistant_fw" );
//		if( str1 == "" )
//			str1 = "3721Home";
		assistant.EasyFunction(0x10005, 0, str1, temp);
	}
	catch( err ){}	
}	
//-------------------------- Set Cookie End --------------------------//
//---------------------select all or none checkbox----------------------//
function CheckStatus(OBJ,OBJ2)
{
var checkedStatus;
checkedStatus=false;

    for ( i = 0; i < OBJ.length; i++ )
	if (OBJ( i ).checked)
	checkedStatus=true
	;
	OBJ2.checked=checkedStatus;

}
function SelectAll(OBJ,OBJ2)
{

try {
    for ( i = 0; i < OBJ.length; i++ )
	OBJ( i ).checked = true;
    OBJ2.checked=true;
 }
  catch(e){
   }
finally {
  }

}
function SelectNone(OBJ,OBJ2)
{
try {

	for ( i = 0; i < OBJ.length; i++ )
	OBJ( i ).checked = false;
    OBJ2.checked=false;
 }
  catch(e){
   }
finally {
  }

}
//---------------------select all or none checkbox End----------------------//
//-----------------------open an FAQ Window----------------------------------//
function OpenFAQWin(FAQUrl)
{
window.open(FAQUrl,"","width=500,height=400,left="+(screen.width-500)/2+"top="+(screen.width-400)/2+"scrollbars=yes, resizable=yes");

}
//--------------------show the hide layer for explaining sth---------//

function ShowPic(flag,Layername)
{
	Layer = eval(Layername);
	if(flag==true)
	{
		Layer.style.visibility = "visible";	
      // alert(event.clientY+Layer.style.pixelHeight);
		//alert(document.body.clientHeight);
		//判断是显示在鼠标的上面还是下面
	//	alert(event.clientY);
    //  alert(document.body.scrollHeight);
	 //  alert(document.body.clientHeight);
	//     alert(Layer.style.height);
	//	 alert(event.clientY+100);
	//	if (event.clientY+Layer.style.pixelHeight>document.body.clientHeight)
	//	{
		//alert("me");
	//	Layer.style.left=document.body.scrollLeft+event.clientX-Layer.style.pixelWidth/2;
    //    Layer.style.top=document.body.scrollTop+event.clientY-Layer.style.pixelHeight;
	//	}
	
    //	else
	//	{
		Layer.style.left=document.body.scrollLeft+event.clientX-Layer.style.pixelWidth/3;
        Layer.style.top=document.body.scrollTop+event.clientY+20;
	//	}
	}else{
		Layer.style.visibility = "hidden";	
	}
}
function ShowPic2(flag,Layername)
{
	Layer = eval(Layername);
	if(flag==true)
	{
		Layer.style.visibility = "visible";	
		Layer.style.left=document.body.scrollLeft+event.clientX-50;
        Layer.style.top=document.body.scrollTop+event.clientY-Layer.style.pixelHeight-10;

	}else{
		Layer.style.visibility = "hidden";	
	}
}




