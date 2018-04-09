
function Xbulle(position, pushType, width){
    position = position || {"top": "50px", "right": "20px"};;
    pushType = pushType || 'append';
    width = width || '600px';    
    this.initContainer(position, pushType, width);
}

Xbulle.prototype.initContainer = function(position, pushType, width) {
	if($(".Xbulle-container").length <= 0){
	
		// creation de l'élement dans le HTML
		$("body").append('<div class="Xbulle-container"></div>');
		this.XbulleContainer = $(".Xbulle-container");
		
		if(!this.setPositionContainer(position)){
			return;
		}
		
		this.setWidthContainer(width);
			
		if(!this.setPushType(pushType)){
			return;
		}
		
		this.durationAnimate = '250';
		
		// si il y a une bulle en sessionStorage on la recupère et on l'affiche
		var bulleExist = sessionStorage.getItem("Xbulle");
		if(bulleExist !== null){
			this.addBulle(JSON.parse(bulleExist));
			sessionStorage.removeItem("Xbulle");
		}
    }
    return;
   
};

Xbulle.prototype.setPositionContainer = function(position){
    if(!_isJson(position)){
        alert("Position du container invalide ! ");
        return false;
    }else{
		this.XbulleContainer.css({"top": "", "bottom" :"", "left":"", "right":""})
        this.XbulleContainer.css(position);
    }
	return true;
    
};

Xbulle.prototype.setWidthContainer = function(width){
	this.XbulleContainer.css({"width":width});
}

Xbulle.prototype.setPushType = function(pushType){
	var pushTypesAllowed = ['append', 'prepend', 'replace'];
    if($.inArray(pushType, pushTypesAllowed) === -1){
        alert("Type d'insertion invalide ! ");
        return false;
    }else{
        this.XbullePushType = pushType;
    }
	return true;
}


Xbulle.prototype.redirectAndAddBulle = function(params){
    var redirect = params.redirect || document.referrer ;    
    sessionStorage.setItem("Xbulle", JSON.stringify(params));    
    window.location = redirect ;
    
};


Xbulle.prototype.addBulle = function(params) {
    var timeout = params.timeout || '5000';
    
    var uniqId = uniqid();
    
    var contentBulle = this.content(uniqId, params);
    
    if(this.XbullePushType == 'replace'){ // replace
        this.XbulleContainer.html(contentBulle).children(':last').hide().fadeIn(this.durationAnimate);
    }else if(this.XbullePushType == 'prepend'){ //prepend
        this.XbulleContainer.prepend(contentBulle).children(':first').hide().fadeIn(this.durationAnimate);
    }else{ // append
        this.XbulleContainer.append(contentBulle).children(':last').hide().fadeIn(this.durationAnimate);
    }
    
    if(timeout != ''){
        setTimeout(function(){
            $('#Xbulle-'+uniqId).fadeTo(this.durationAnimate, 0.01, function(){ //fade
                $(this).slideUp(this.durationAnimate, function() { //slide up
                    $(this).remove(); //then remove from the DOM
                });
            });
        },timeout);
    }
    
    this.XbulleContainer.on('click','.Xbulle-close',function(){
        $(this).closest('.Xbulle-content').fadeTo(this.durationAnimate, 0.01, function(){ //fade
             $(this).slideUp(this.durationAnimate, function() { //slide up
                 $(this).remove(); //then remove from the DOM
             });
         });
    });
   
};

Xbulle.prototype.content = function(uniqId, params){
    var title = params.title || "";
    var message = params.message || "";
    var icon = params.icon || "";
    var type = params.type || "theme";
    
    // si l'icon n'est pas spécifié, on met celui par défaut
    if(type != 'theme' && icon == ''){
        if(type == 'err'){
            icon = 'times-circle';
        }else if(type == 'conf'){
            icon = 'check-circle';
        }else if(type == 'warn'){
            icon = 'exclamation-triangle';
        }else if(type == 'info'){
            icon = 'info-circle';
        }
    }

    // si le titre n'est pas spécifié, on l'ajoute par défaut
    if(type != 'theme' && title == ''){
        if(type == 'err'){
            title = 'Erreur';
        }else if(type == 'conf'){
            title = 'Confirmation';
        }else if(type == 'warn'){
            title = 'Attention';
        }else if(type == 'info'){
            title = 'Information';
        }
    }
    
    var spanIcon = paddTxt = '';
    if(icon != ''){
        paddTxt = 'padding-left:40px;'
        spanIcon = '<div class="on-left padding-right"><span style="font-size:2em;" class="icon '+icon+' color-'+type+'"></span></div>';
    }
    
    return '<div id="Xbulle-'+uniqId+'" class="Xbulle-content Xbulle-type--'+type+' margin-bottom">'+spanIcon+'<div style="'+paddTxt+'" class="text justify relative"><strong class="block color-'+type+'">'+title+'</strong>'+message+'<span class="icon times absolute Xbulle-close"></span></div></div>' ;
    
}







