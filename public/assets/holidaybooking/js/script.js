let headerheight = 0;
function setHeaderHeight() {
    if(headerheight != $('header').outerHeight()){
        headerheight = $('header').outerHeight();
        document.documentElement.style.setProperty('--header-height',  `${headerheight}px`);
    }
    requestAnimationFrame(setHeaderHeight);
}
requestAnimationFrame(setHeaderHeight);


$(window).on('resize', function(){
    if(window.innerWidth < 1200){
        if($('header').find('.sidemenu-box').length == 0){
            $('header .theme-nav').css('display', 'flex');
            $('.social-icons').css('display', 'flex');
            $('.social-icons, header .theme-nav').wrapAll( "<div class='sidemenu-box'></div>" );
            $(`
            <div class="header-backdroap"></div>
            <div class="phone-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            `).insertBefore('.sidemenu-box');
            $('body').addClass('menu-closed');
            $('body').removeClass('menu-opened');
            $('.phone-menu').click(function(){
            if($('body').hasClass('menu-closed')){
                $('body').addClass('menu-opened');
                $('body').removeClass('menu-closed');
            }else{
                $('body').addClass('menu-closed');
                $('body').removeClass('menu-opened');
            }
            });
            $('.header-backdroap').click(function(){
            $('body').addClass('menu-closed');
                $('body').removeClass('menu-opened');
            });
            $('.sub-menu').each(function(){
                $(this).hide();
                $('<span class="dd-click"></span>').insertBefore($(this));
            });
        }
    }else{
        if($('header').find('.sidemenu-box').length > 0){
            $('.social-icons, header .theme-nav').unwrap();
            $('.header-backdroap, .phone-menu, .dd-click').remove();
        }
        $('.sub-menu').show();
    }
    $('body').css("--header-height", `${$('.theme_header').outerHeight()}px`)
}).resize();


$(document).click(function(e){
    if(e.target.classList.contains('dd-click')){
        if($(e.target).parents('.sub-menu').length == 0){
            $('.sub-menu').slideUp()
            $('.dd-click').removeClass('slideOpened')
        }
        if($(e.target).siblings('.sub-menu').css('display') == "none"){
            $(e.target).siblings('.sub-menu').slideDown()
            $(e.target).addClass('slideOpened')
        }else{
            $(e.target).siblings('.sub-menu').slideUp()
            $(e.target).removeClass('slideOpened')
        }
        // 
        console.log($(e.target).siblings('.sub-menu').css('display'));
    }
})



// blog sidebar
if($('.blog_sidebar').length > 0){
    var sidebarHeight = $('.blog_sidebar').height();
    $('.blog_list>li:nth-child(1)').css('min-height', sidebarHeight + "px");
}

// event item
function equalheight(wrapper, element){
    $(wrapper).each(function (index) {
        var maxHeight = 0;
        $(this).find(element).height('auto');
        $(this).find(element).each(function (index) {
            if ($(this).height() > maxHeight)
                maxHeight = $(this).height();
        });
        $(this).find(element).height(maxHeight);
    });

}

equalheight('.event-list', '.para-md');
equalheight('.exhibition-list', '.para-md');