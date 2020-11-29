$(function(){
  $(".cart-number").change(function() {

    var num = $('option:selected').val();
    $("select").append('<input type="hidden"name="item_id2" id="item_id2" value={{value.item_id}} class="item_id2">');
    var item_id =$(".item_id").val();

    console.log(item_id);
    location.href = entry_url + "cart.php?item_id=" + item_id + "&num=" + num;

});

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
});