(function ( $ ) {

    $.fn.maplink = function() {
        this.css( "color", "green" );
        return this;
    };
    $.ltrim = function( str ) {
        return str.replace( /^\s+/, "" );
    };
    $.rtrim = function( str ) {
        return str.replace( /\s+$/, "" );
    };

}( jQuery ));