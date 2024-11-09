function logspan(t){
	if(t) t.className = t.className == 'missionhead' ? 'missionhead logspan' : 'missionhead';
	for(i=0;i<document.getElementsByTagName('span').length;i++){
		log = document.getElementsByTagName('span')[i];
		if(log.className == 'log'){
			if(log.style.display == 'block') log.style.display = 'none';
			else log.style.display = 'block';
		}		
	}
}