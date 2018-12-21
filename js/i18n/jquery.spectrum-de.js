// Spectrum Colorpicker
// German (de) localization
// https://github.com/bgrins/spectrum
// this file is modified to avoid Umlauts

(function ( $ ) {

    var localization = $.spectrum.localization["de"] = {
        cancelText: "Abbrechen",
        chooseText: "Selektieren",
        clearText: "Anfangswert setzen",
        noColorSelectedText: "Keine Farbe selektiert",
        togglePaletteMoreText: "Mehr",
        togglePaletteLessText: "Weniger"
    };

    $.extend($.fn.spectrum.defaults, localization);

})( jQuery );
