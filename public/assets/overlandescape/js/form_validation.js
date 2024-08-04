jQuery(document).ready(function () {
  /////////// Phone No Validation   
  jQuery(".phone").keypress(function (e) {
     var length = jQuery(this).val().length;
     if(length > 11) {
        return false;
    } else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) ) {
        return false;
    } else if((length == 0) && (e.which == 48)) {
        return false;
    }
});

  jQuery(".number").keypress(function (e) {
      var length = jQuery(this).val().length;
      if((e.which < 48 || e.which > 57) && e.which != 46) {
        return false;
    }
});  

  jQuery(".digits").keypress(function (e) {
     var length = jQuery(this).val().length;
     if(e.which < 48 || e.which > 57) {
        return false;
    }
}); 


  jQuery(".email").keypress(function (e) {
   var length = jQuery(this).val().length;
     //alert(e.which); 
     if(e.which != 8 && e.which != 0 && e.which != 46 && ((e.which < 48 || e.which > 57) && (e.which < 95 || e.which > 122) && (e.which < 63 || e.which > 90))) {
        return false;
    } else if((length == 0) && (e.which == 95 || e.which == 63)) {
        return false;
    }
});  



});

// $('[datatypeinput=datepicker]').datetimepicker({
//     format: 'DD/MM/YYYY',
//     minDate: new Date(),
// });

// $('[datatypeinput=timepicker]').datetimepicker({
//     format: 'LT',
//     minDate: new Date(),
// });

// $('[datatypeinput=datetimepiker]').datetimepicker({
//     format: 'YYYY-MM-DD LT',
//     minDate: new Date(),
// });

// $('[datatypeinput=monthpicker]').datetimepicker({
//     format: 'YYYY-MM',
//      viewMode: 'months',
//      minDate: new Date(),
// });

// $('[datatypeinput=yearpicker]').datetimepicker({
//     format: 'YYYY',
//      viewMode: 'years',
//      minDate: new Date(),
// });

