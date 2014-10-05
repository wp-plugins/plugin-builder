(function( $ ) {
	'use strict';

	$(init);
	
	function init() {
	    $('#hide-if-js').hide();
	    $('.toggler').on('click', function(e){
                var el = $(this),
                    href = el.attr('href'),
                    target = $(href);
		target.slideToggle('fast');
		e.preventDefault();
		return false;
	    });
	    
	    $('.plugin-register-tab').hide().first().show();
	    $('.plugin-register-tabs a').on('click', function(e){
		var el = $(this);
		$('.plugin-register-tabs a').removeClass('button-primary');
		el.addClass('button-primary');
		$('.plugin-register-tab').hide();
		$(el.attr('href')).show();
                $('#hide-if-js').hide();
		e.preventDefault();
		return false;
	    });
	    
	    $('.autoslug').on('keyup', function(){
		$(this).removeClass('autoslug');
	    });
	    
	    $('#title').on('keyup', function(){
		var el = $(this);
		$('#general-settings .autoslug').val(slugify(el.val()));
		$('#general-settings .autocamel').val(camelify(el.val()));
	    });
            
            $('#cpt_name').on('keyup', function(){
		var el = $(this);
		$('#cpt_slug').val(slugify(el.val()));
	    });
	}
	
	function slugify(text) {
	    return text
		    .toLowerCase()
		    .replace(/[^\w ]+/g,'')
                    .trim()
		    .replace(/ +/g,'-');
	}
	
	function camelify(text) {
	    return text
		    .replace(/[^\w ]+/g,'')
                    .replace(/(\w)(\w*)/g,
                        function(g0,g1,g2){return g1.toUpperCase() + g2.toLowerCase();})
                    .trim()
		    .replace(/ +/g,'_');
	}

})( jQuery );
