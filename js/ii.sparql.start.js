if ( window.sparqlResultFormatsElements ) {
	for ( var i = 0; i < window.sparqlResultFormatsElements.length; i++ ) {
		var el = window.sparqlResultFormatsElements[ i ],
		 conf = el.config;
		el.start( conf );
	}
}
