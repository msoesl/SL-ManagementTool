/**
 * jQuery snow effects.
 *
 * This is a heavily modified, jQuery-adapted, browser-agnostic version of 
 * "Snow Effect Script" by Altan d.o.o. (http://www.altan.hr/snow/index.html).
 *
 * Dustin Oprea (2011)
 */

 var flakes;
 var animateSnow;
 var hideSnow;

function __ShowSnow(settings)
{

    var snowsrc = settings.SnowImage;
    var no = 75;

    var dx, xp, yp;    // coordinate and position variables
    var am, stx, sty;  // amplitude and step variables
    var i; 

    var doc_width = $(window).width() - 10;
    var doc_height = $(window).height();

    dx = [];
    xp = [];
    yp = [];
    am = [];
    stx = [];
    sty = [];
    flakes = [];
    for (i = 0; i < no; ++i) 
    {
        dx[i] = 0;                        // set coordinate variables
        xp[i] = Math.random()*(doc_width-50);  // set position variables
        yp[i] = Math.random()*doc_height;
        am[i] = Math.random()*20;         // set amplitude variables
        stx[i] = 0.02 + Math.random()/10; // set step variables
        sty[i] = 0.7 + Math.random();     // set step variables

        var flake = $("<div />");

        var id = ("dot" + i);
        flake.attr("id", id);
        flake.css({
                    position: "absolute",
                    zIndex: i,
                    top: "15px",
                    left: "15px"
                });

        var possibleSnow = ["res/images/confetti.png", "res/images/confetti2.png", "res/images/confetti3.png"];
        var snowIndex = Math.floor(Math.random()*possibleSnow.length);
        flake.append("<img src='" + possibleSnow[snowIndex] + "'>");
        flake.appendTo("body");

        flakes[i] = $("#" + id);
    }

    animateSnow = function() 
    {  
        for (i = 0; i < no; ++ i) 
        {
            // iterate for every dot
            yp[i] += sty[i]*5;
            if (yp[i] > doc_height - 50) 
            {
                xp[i] = Math.random() * (doc_width - am[i] - 30);
                yp[i] = 0;
                stx[i] = 0.02 + Math.random() / 10;
                sty[i] = 0.7 + Math.random();
            }
      
            dx[i] += stx[i];
            flakes[i].css("top", yp[i] + "px");
            flakes[i].css("left", (xp[i] + am[i] * Math.sin(dx[i])) + "px");
        }

        snowtimer = setTimeout(animateSnow, 10);
    };

	hidesnow = function()
    {
		if(window.snowtimer)
            clearTimeout(snowtimer)

        for (i = 0; i < no; i++)
            flakes[i].hide();
	}
		
    animateSnow();

    setTimeout(hidesnow, 5 * 1000)
}

(function($) {
    $.fn.snow = function(options) {
  
    var settings = $.extend({
            SnowImage:      null,
            Quantity:       7,
            HideSnowTime:   0
        });

    if(flakes && flakes.length > 50){
        for (i = 0; i < flakes.length; i++)
            flakes[i].show();

        animateSnow();
        setTimeout(hidesnow, 5 * 1000)
    }else{
        __ShowSnow(settings);
    }

    return this;
  }

})(jQuery);

