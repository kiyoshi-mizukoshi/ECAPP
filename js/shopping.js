$(function(){

  var entry_url = $("#entry_url").val();

  $("#cart_in").click(function(){
      var item_id = $("#item_id").val();
      location.href = entry_url + "cart.php?item_id=" + item_id;
  });
  $('.drawer').drawer();


  var slider1=new Swiper( '.slider1', {
    speed: 400,
    spaceBetween: 0,
    effect: 'fade',
    width: 1200,
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false
    },
    loopedSlides: 3,
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true,
    },
    on: {
      slideChange: function () {
        jQuery('.swiper-slide-content').css('opacity', '0');
        realIndex = this.realIndex + 1;
        jQuery('.swiper-slide-content-' + realIndex).css('opacity', '1');
      }},
    breakpoints: {
      768: {
				spaceBetween: 24,
        width: 768,
        height:600,
      }
    }
  });

//formの入力確認
  var submit = $(' #js-submit')
});