$(function(){

$('#js-remove').click(function(){
  if(!confirm('本当に削除しますか？')){
      /* キャンセルの時の処理 */
      return false;
  }else{
      /*　OKの時の処理 */
      var remove = $("#js-remove").val();
      location.href = "admin_products_complete.php?remove=" + remove;
}
});

$('#mem-remove').click(function(){
  if(!confirm('本当に削除しますか？')){
      /* キャンセルの時の処理 */
      return false;
  }else{
      /*　OKの時の処理 */
      var remove = $("#mem-remove").val();
      location.href = "admin_regist_complete.php?remove=" + remove;
}
});


  $('#input-file').change(function(){
    $('img').remove();
    var file = $(this).prop('files')[0];
    var fileReader = new FileReader();
    fileReader.onloadend = function() {
        $('#preview').html('<img src="' + fileReader.result + '"/>');
        $('img').addClass('resize-image');
    }
    fileReader.readAsDataURL(file);
});

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

  $('#myImage').on('change', function (e) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]); 
});




});