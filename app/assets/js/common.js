function addClass(ele,className){
	try
	{
		if(ele.className.indexOf(className) < 0){
			ele.className += " "+className;
		}
	}
	catch(ex){errlog(ex);}
}

function removeClass(ele,className){
	try
	{
		var indexStart = ele.className.indexOf(className)
		if(indexStart >= 0){
			ele.className = ele.className.substring(0,indexStart)+ele.className.substring(indexStart+className.length);
		}
	}
	catch(ex){errlog(ex);}
}