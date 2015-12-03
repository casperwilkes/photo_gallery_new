/**
 * creates a flyover effect to expand profile/logout mini-menu
 */
$('#nav').on({
    mouseenter: function () {
        $('#nav li').css('display', 'block');
    },
    mouseleave: function () {
        $('#nav li:nth-child(n+2)').css('display', 'none');
    }
});