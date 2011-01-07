// Render json into markup
if (typeof Render == 'undefined' && !Render) {

    var Render = function() {

	// have to distinguish between different types of json objects
	// otherwise how do we know which renderer to send to?

	// should i have definitions for each json type?
	var content_types = ['writing','contact','header','footer','nav','left'];

	var json_type = function(json) {
	    for (var i in content_types) {
		if(json.type==i) {
		    return i;
		}
	    }
	    return false;
	};

	var renderers = [];
	var create_renderer = function() {
	    var r;
	    renderers[renderers.length] = r;
	    return r;
	};

	return {'json_type':json_type,'renderers':renderers,'create_renderer':create_renderer};

    }();
};

(function() {
    var r = Render.renderers;
    var cr = Render.create_renderer;
    
    r.writing = function() {
	var r = cr();
	return r;
    }();
    
    r.header = function() {
	var r = cr();
	return r;
    }();
    
    r.footer = function() {
	var r = cr();
	return r;
    }();
    
})();

// how would i render one thing, a blog entry?
var render_writing = function(json) {
    var markup = '<div class="entry">';
    markup += '<div class="main">';

    markup += '<h2><a href="' + json.id + '">' + json.title + '</a></h2>';
    markup += json.text;
    markup += '</div><!-- /end .main -->';

    markup += '<div class="info">';
    markup += '<p>Posted on ' + json.date + ' at ' + json.time + '</p>';
    markup += '<p>Tagged with ' + json.tags + '</p>';

    markup += '</div><!-- /end .info -->';
    markup += '</div><!-- /end .entry -->';

    return markup;
};
