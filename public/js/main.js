$(document).ready(function(){
    // $('.dropdown-menu .dropdown').each(function(index){
    //   console.log($(this));
    //     var $self = $(this);
    //     var handle = $self.children('[data-toggle="dropdown"]');
    //     $(handle).click(function(){
    //         var submenu = $self.children('.dropdown-menu');
    //         $(submenu).toggle();
    //         return false;
    //     });
    // });
    
    $('#fix-iphone').click(function() {
      if (!$(this).hasClass('open')) {
        $('#fix-iphone > a').attr('aria-expanded', true);
       $('#fix-iphone > div.dropdown-menu').show();
     } else $('#fix-iphone > div.dropdown-menu').hide();
    });
    $(document).click(function(event) {
    if (!$(event.target).closest("#fix-iphone .dropdown-menu").length) {
            $("#fix-iphone .dropdown-menu").hide();
        }
    });
    $('.dropdown-menu .dropdown').click(function(event)
    {
      event.preventDefault();



      var $self = $(this).parent('li').find('.dropdown-menu');
      if($self.attr('style'))
      {
        $self.removeAttr('style');
        $self.parent('li').removeClass('active');
      }else
      {
        $('.dropdown-menu .dropdown-menu').each(function(index)
        {
          $(this).removeAttr('style');
          $(this).parent('li').removeClass('active');
        });
        $self.parent('li').addClass('active');
        $self.attr({'style' : 'display: block;'});
      }



      return false;
    });
});
