var _taboldgif = new Array(10)
var _tabnewgif = new Array(10)
_taboldgif[0] = "images/tab_1.gif";
_taboldgif[1] = "images/tab_b1.gif";
_taboldgif[2] = "images/tab_2.gif";
_taboldgif[3] = "images/tab_3.gif";

_tabnewgif[0] = "images/tab_7.gif";
_tabnewgif[1] = "images/tab_b2.gif";
_tabnewgif[2] = "images/tab_5.gif";
_tabnewgif[3] = "images/tab_4.gif";
_tabnewgif[4] = "images/tab_8.gif";


function ClickTab( nTol, nPos, nStatus, bExtern )
{
try
{
	var i;var j = 1;
	var imgname = "";
	if( bExtern )
	{
		imgname = "window.parent.main.itab_";
	}
	else
		imgname = "itab_";
		
	for( i = 0; i <= nTol; i++ )
	{
		if( i == 0 )
			eval(imgname+i).src = _taboldgif[0];
		else if( i == 2*j - 1 )
		{
			eval(imgname+i).background = _taboldgif[1];
			j ++;
		}
		else if( i == nTol )
		{
			eval(imgname+i).src = _taboldgif[3];
		}
		else
			eval(imgname+i).src = _taboldgif[2];
	}

	if( nStatus == 0 )
	{
		var pos = eval( nPos - 1 );
		eval(imgname + pos).src = _tabnewgif[0];
		pos = eval( nPos + 1 );
		eval(imgname + pos).src = _tabnewgif[2];
	}
	if( nStatus == 1 )
	{
		var pos = eval( nPos - 1 );
		eval(imgname + pos).src = _tabnewgif[3];
		pos = eval( nPos + 1 );
		eval(imgname + pos).src = _tabnewgif[2];
	}
	if( nStatus == 2 )
	{
		var pos = eval( nPos - 1 );
		eval(imgname + pos).src = _tabnewgif[3];
		pos = eval( nPos + 1 );
		eval(imgname + pos).src = _tabnewgif[4];
	}
	eval(imgname + nPos).background = _tabnewgif[1];
	
	if( !bExtern )
	{
		pos = eval( (nPos +1)/2 );
		window.parent.left.ClickLink( nTol/2, pos, '',true);
	}
}
catch( err ){}
	
}


function ClickLink( nTol, nPos, urlfile, bExtern )
{
try
{
	var i;	
	var name1 ="", name2= "";
/*	if( bExtern )
	{
		name1 = "window.parent.left.li_";
		name2 = "window.parent.left.lf_"; 
	}
	else
	{
		name1 = "li_";
		name2 = "lf_"; 
	}
*/
	name1 = "parent.parent.left.li_";
	name2 = "parent.parent.left.lf_";
	
	for( i = 1; i <= nTol; i ++ )
	{
		eval( name1 + i ).src = 'images/hdl_1.gif';
		eval( name2 + i ).color = 'black';
	}
	eval( name1 + nPos ).src = 'images/hdl_2.gif';
	eval( name2 + nPos ).color = '#CC3300';
	if( !bExtern )
	{
		var pos = eval( 2* nPos - 1);
		var pos1= 0;
		if( nPos == 1 )
			pos1 = 0;
		else if( nPos == nTol )
			pos1 = 2;
		else
		 	pos1 = 1;
//		window.parent.main.location = urlfile;
		if( typeof(parent.parent.main ) == "object" )
			parent.parent.main.ClickTab(nTol*2,pos,pos1,1);
			
		if( typeof(parent.parent.main.right ) == "object" )
		{
			var tempurl = parent.parent.main.right.location.href;
			if( tempurl.indexOf( urlfile ) < 0 && tempurl.indexOf( "install" ) < 0 )
				parent.parent.main.right.location = urlfile;
		}
	}
}
catch( err ){}
}



var g_arr_argName = new Array(100)
var g_arr_argValue = new Array(100)
var g_lng_argLen = 0
var g_href = "";

function setArg(str_arg)
{
	var arr_arg

	arr_arg=str_arg.split("=")
	g_arr_argName[g_lng_argLen]=arr_arg[0]//放入全局参数数组
	g_arr_argValue[g_lng_argLen]=arr_arg[1]
	g_lng_argLen = g_lng_argLen + 1

	return true
}
function setURLArgument(str_url)
{
	var str_temp
	var arr_temp
	var str_arg
	var arr_arg
	var i
	if(str_url==""||str_url==null) 
	{
		if( typeof(parent.location ) == "object" )
			str_url=parent.location.href
		else
			str_url = location;
	}

	if(str_url.indexOf("?")>=0)
	{
		arr_temp=str_url.split("?")
		g_href = arr_temp[0]
		str_arg=arr_temp[1]//参数串
		if(str_arg.indexOf("&")>=0)
		{
			arr_temp=str_arg.split("&")//切割参数数组
			for(i=0;i<arr_temp.length;i++)
			{
				str_temp=arr_temp[i]
				if(str_temp.indexOf("=")>0)//有效的参数
				{
					setArg(str_temp)
				}
			}
		}
		else
		{
			setArg(str_arg)
		}
	}
	return true
}

function getURL(str_argName)
{
	for(var i=0;i<g_lng_argLen;i++)
	{
		if(g_arr_argName[i]==str_argName)
		{
			return g_arr_argValue[i]
		}
	}
	return ""//参数值为空字符串
}

var _rurl = new Array(10)
var _lpos = new Array(10)
_rurl[0] = "safe0";
_rurl[1] = "prvc0";
_rurl[2] = "acel0";
_rurl[3] = "noad0";
_rurl[4] = "srch0";

_lpos[0] = 4;
_lpos[1] = 4;
_lpos[2] = 5;
_lpos[3] = 2;
_lpos[4] = 2;

function RedirUrl( temp, tempfb )
{
	strurl = "safe01.htm";
    	lefttol = 4;
    	leftpos = 1;
	if( temp ==  "clean")
	{
		strurl = "prvc03.htm";
	    	lefttol = 4;
	    	leftpos = 3;
	}
	else if( temp ==  "cleanie")
	{
		strurl = "prvc02.htm";
	    	lefttol = 4;
	    	leftpos = 2;
	}
	else if( temp ==  "autoclean")
	{
		strurl = "prvc04.htm";
	    	lefttol = 4;
	    	leftpos = 4;
	}
	else if( temp == "security")
	{
		strurl = "safe02.htm";
	    	lefttol = 4;
	    	leftpos = 2;
	}
	else if( temp == "keepie" )
	{

		strurl = "safe03.htm";
	    	lefttol = 4;
	    	leftpos = 3;
	}
	else if( temp == "catch")
	{
		strurl = "noad02.htm";
	    	lefttol = 2;
	    	leftpos = 2;
	}
	else if(temp == "garbage")
	{
		strurl = "acel03.htm";
	    	lefttol = 4;
	    	leftpos = 3;
	}
	else if(temp == "run")
	{
		strurl = "acel02.htm";
	    	lefttol = 5;
	    	leftpos = 2;
	}
	else if(temp == "browser")
	{
		strurl = "acel05.htm";
	    	lefttol = 5;
	    	leftpos = 5;
	}
	else 
	{
		for( ix = 0; ix < 5; ix ++ )
		{
			if( temp.indexOf(_rurl[ix]) >= 0 )
			{	
				url_temp=temp.split(_rurl[ix]);
				if( url_temp[1].indexOf(".") >= 0 )
				{
					num_temp=url_temp[1].split(".");
				    	lefttol = _lpos[ix];
				    	leftpos = num_temp[0];
					strurl = temp;
					break;
				}
			}	
		}
	}
	strurl = strurl +'?fb='+tempfb;
		
	ClickLink( lefttol, leftpos, strurl, false );
}


function MainRedirUrl( temp, tempfb )
{
	strurl = "safe01.htm";
	nstatus = 0;
    	ntol = 8;
    	npos = 1;
	if( temp ==  "clean")
	{
		strurl = "prvc03.htm";
		nstatus = 1;
	    	ntol = 8;
	    	npos = 5;
	}
	else if( temp ==  "cleanie")
	{
		strurl = "prvc02.htm";
		nstatus = 1;
	    	ntol = 8;
	    	npos = 3;
	}
	else if( temp ==  "autoclean")
	{
		strurl = "prvc04.htm";
		nstatus = 2;
	    	ntol = 8;
	    	npos = 7;
	}
	else if( temp == "security")
	{
		strurl = "safe02.htm";
		nstatus = 1;
	    	ntol = 8;
	    	npos = 3;
	}
	else if( temp == "keepie" )
	{
		strurl = "safe03.htm";
		nstatus = 1;
	    	ntol = 8;
	    	npos = 5;
	}
	else if( temp == "catch")
	{
		strurl = "noad02.htm";
		nstatus = 2;
	    	ntol = 4;
	    	npos = 3;
	}
	else if(temp == "garbage")
	{
		strurl = "acel03.htm";
		nstatus = 1;
	    	ntol = 8;
	    	npos = 5;
	}
	else if(temp == "run")
	{
		strurl = "acel02.htm";
		nstatus = 1;
	    	ntol = 10;
	    	npos = 3;
	}
	else if(temp == "browser")
	{
		strurl = "acel05.htm";
		nstatus = 2;
	    	ntol = 10;
	    	npos = 9;
	}
	else 
	{
		for( ix = 0; ix < 5; ix ++ )
		{
			if( temp.indexOf(_rurl[ix]) >= 0 )
			{	
				url_temp=temp.split(_rurl[ix]);
				if( url_temp[1].indexOf(".") >= 0 )
				{
					num_temp=url_temp[1].split(".");
				    	ntol = _lpos[ix] * 2;
				    	npos = num_temp[0] * 2 - 1;
				    	nstatus = 1;
				    	if( npos == 1 )
				    		nstatus = 0;
				    	if( npos == ntol - 1 )
				    		nstatus = 2;
					strurl = temp;
					break;
				}
			}	
		}
	}	
		
	ClickTab( ntol, npos, nstatus, false );
	var tempurl = parent.parent.main.right.location.href;
	if( tempurl.indexOf( strurl ) < 0 && tempurl.indexOf( "install" ) < 0 )
	{
		strurl = strurl + '?fb=' + tempfb;
		parent.parent.main.right.location = strurl; 
	}
}