
$.fn.mdtabs = function(options){
    var defaults = {
        height: 3,
        color: "#f1b1a4",
        duration: 200,
        onClick: null,
        onIndicaterMoved: null,
        leeway: 0
    }
    $.extend(defaults, options);

    var tabBox = $(this);
    var tabItems = tabBox.children();

    tabBox.find(".indicater").remove();
    var indicater = $("<span class='indicater'></span>").css({
        "position": "absolute",
        "bottom": "0px",
        "height": defaults.height + "px",
        "background-color": defaults.color,
        "left": 0,
        "right": "100%"
    }).appendTo(tabBox);

    function moveIndicater(item, click, moved){
        var left = item ? item.position().left : 0;
        var right = item ? (tabBox.innerWidth() - item.outerWidth() - left) : tabBox.innerWidth();

        left += defaults.leeway - 5;
        right-= defaults.leeway - 5;

        if(click){
            indicater.stop().animate({
                left: left,
                right: right
            }, defaults.duration, function(){
                if(moved)
                    moved.call(item);
            });
        }
        else{
            indicater.css({
                left: left,
                right: right
            });
        }
    }

    var selectedItem = tabBox.find(">a.active").length ? tabBox.find(">a.active") : null;
    moveIndicater(selectedItem, false, defaults.onIndicaterMoved);

    var timer = 0;

    tabItems.on("mouseenter", function(){
        if(defaults.onClick)
            if(defaults.onClick.call(this) == false)
                return false;
        moveIndicater($(this), true, defaults.onIndicaterMoved);
        //return false;
    }).on('mouseleave', function(){
        if (!$(this).is('.active')){
            tabBox.find('a.active').trigger('mouseenter');
        }
    });

}