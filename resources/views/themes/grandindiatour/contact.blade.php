@component(config('custom.theme').'.layouts.main')

@slot('title')
{{ $meta_title ?? ''}}
@endslot    

@slot('meta_description')
{{ $meta_description ?? '' }}
@endslot

@slot('meta_keyword')
{{ $meta_keyword ?? '' }}
@endslot

@slot('headerBlock')
<style type="text/css">
/* .ui-datepicker-calendar {
    display: none;
    } */
/* .ui-widget {
    font-size:.7em;
} */
.custom_date { position: relative; }
.monthly-wrp { padding: 1em;       top: 100% !important;
    left: 0 !important;
    width: 100%;  background-color: #f1f1f1; top: 6px; z-index: 1000; border-radius: 3px;  }
.monthly-wrp:before { content: ""; border-bottom: 6px solid #f1f1f1; border-left: 6px solid transparent; border-right: 6px solid transparent; position: absolute; top: -6px; left: 6px; z-index: 1002; }
.monthly-wrp .years { margin-bottom: 0.8em; text-align: center; }
.monthly-wrp .years select { border: 0; border-radius: 3px; width: 100%;background: #fff !important;
    appearance: auto;
 }
.monthly-wrp .years select:focus { outline: none; }
.monthly-wrp table { border-collapse: collapse; table-layout: fixed; width:100%; }
.monthly-wrp td { padding: 1px; }
 .monthly-wrp table button { width: 100%; border: none; background-color: var(--theme-color);color: #FFFFFF; font-size: 12px; padding: 0.6em; cursor: pointer; border-radius: 3px; }
.monthly-wrp table button:hover { background-color: #3e1412; }
 .monthly-wrp table button:focus { outline: none; }
</style>

@endslot

@slot('bodyClass')
  contact-us
@endslot
<?php
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']);
$websiteSetting = CustomHelper::getSettings(['FACEBOOK','TWITTER','INSTAGRAM','LINKEDIN','CONTACT_RESERVATION_OFFICE','CONTACT_MAIN_OFFICE','CONTACT_DELHI_OFFICE','CONTACT_RESERVATION_OFFICE_IFRAME','CONTACT_MAIN_OFFICE_IFRAME','CONTACT_DELHI_OFFICE_IFRAME']);

$contact_reservation_office = $websiteSetting['CONTACT_RESERVATION_OFFICE']??'';
$CONTACT_RESERVATION_OFFICE_IFRAME = $websiteSetting['CONTACT_RESERVATION_OFFICE_IFRAME']??'';
$contact_main_office = $websiteSetting['CONTACT_MAIN_OFFICE']??''; 
$CONTACT_MAIN_OFFICE_IFRAME = $websiteSetting['CONTACT_MAIN_OFFICE_IFRAME']??'';
$contact_delhi_office = $websiteSetting['CONTACT_DELHI_OFFICE']??'';
$CONTACT_DELHI_OFFICE_IFRAME = $websiteSetting['CONTACT_DELHI_OFFICE_IFRAME']??'';
$facebook = $websiteSetting['FACEBOOK']??'';
$twitter = $websiteSetting['TWITTER']??''; 
$instagram = $websiteSetting['INSTAGRAM']??''; 
$linkedin = $websiteSetting['LINKEDIN']??''; 
?>
<?php 
$storage = Storage::disk('public');
$path = 'banners/';
$b_image =asset(config('custom.assets').'/images/noimage.jpg');
// dd($banners);
foreach($banners as $banner){
  $images = (isset($banner->Images))?$banner->Images:'';
  // prd($images->toArray());
  if(!empty($images) && count($images) > 0){
    foreach($images as $image){
      if(!empty($image->image_name) && $storage->exists($path.$image->image_name)){

        $b_image = url('/storage/banners/'.$image->image_name);
      }
    }
  }
}
?>

      <?php /*<section>
        <div class="xl:container xl:mx-auto">
          <div class="gray_add_box">
            <?php if(!empty($contact_reservation_office)){ ?>
            <div class="flex flex-wrap">
              {!! $contact_reservation_office !!}
              <div class="lg:w-3/5 md:w-full">
              {!! $CONTACT_RESERVATION_OFFICE_IFRAME !!}
              </div>
            </div>
            <?php } ?>
            
            <?php if(!empty($contact_main_office)){ ?>
            <hr class="my-5">
            <div class="flex flex-wrap">
              {!! $contact_main_office !!}
              <div class="lg:w-3/5 md:w-full">
              {!! $CONTACT_MAIN_OFFICE_IFRAME !!}
              </div>
            </div>
            <?php } ?>

            <?php if(!empty($contact_delhi_office)){ ?>
            <hr class="my-5">
            <div class="flex flex-wrap">
              {!! $contact_delhi_office !!}
              <div class="lg:w-3/5 md:w-full">
              {!! $CONTACT_DELHI_OFFICE_IFRAME !!}
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </section>
      */?>


      <section class="pt-3">
         <div class="xl:container xl:mx-auto">
            <div class="contact_wrap">
               <div class="contact_inner_form shadow p-5 my-3 rounded flex flex-wrap">
                <div class="form_box w-1/2">
                    <div class="form_box_inner pr-7">
                      <div class="text-2xl pb-5">Contact Us</div>
                      @include('snippets.front.flash')
                      {!!formShortCode(['slug' =>'[contact_us]','class'=>'left_form'])!!}
                    </div>
                </div>
                <div class="form_box w-1/2">
                <div class="text-2xl pb-5">Contact Info</div>
                <?php if(!empty($contact_main_office)){ ?>
                    <div class="flex flex-wrap">
                      {!! $contact_main_office !!}
                    </div>
                    <hr class="my-5">
                    <?php } ?>
                {!! $CONTACT_MAIN_OFFICE_IFRAME !!}
                </div>
               </div>
               
            </div>
         </div>
      </section>

@slot('bottomBlock')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/css/intlTelInput.css" rel="stylesheet" />
<script type="text/javascript">
// Make sure to place this snippet in the footer or at least after
// the HTML input we're targeting.


// CUSTOM DATE PICKER

setTimeout(() => {
   $(".years select").addClass("form_control");
   $('[data-value]').click(function(e){
  e.preventDefault()

})
}, 1000);

function padToTwo(number) {
  if (number <= 9999) {
    number = ("0" + number).slice(-2);
  }
  return number;
}

(function($) {
  $.fn.monthly = function(options) {
    var months = options.months || [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ],
      Monthly = function(el) {
        this._el = $(el);
        this._init();
        this._render();
        this._renderYears();
        this._renderMonths();
        this._bind();
      };

    Monthly.prototype = {
      _init: function() {
        this._el.html(months[0] + " " + options.years[0]);
      },

      _render: function() {
        var linkPosition = this._el.offset(),
          cssOptions = {
            display: "none",
            position: "absolute",
            top:
              linkPosition.top + this._el.height() + (options.topOffset || 0),
            left: linkPosition.left
          };
        this._container = $('<div class="monthly-wrp">')
          .css(cssOptions)
          .appendTo($(".custom_date"));
      },

      _bind: function() {
        var self = this;
        this._el.on("click", $.proxy(this._show, this));
        $(document).on("click", $.proxy(this._hide, this));
        this._yearsSelect.on("click", function(e) {
          e.stopPropagation();
        });
        this._container.on("click", "button", $.proxy(this._selectMonth, this));
      },

      _show: function(e) {
        e.preventDefault();
        e.stopPropagation();
        this._container.css("display", "inline-block");
      },

      _hide: function() {
        this._container.css("display", "none");
      },

      _selectMonth: function(e) {
        var monthIndex = $(e.target).data("value"),
          month = months[monthIndex],
          year = this._yearsSelect.val();
        this._el.html(month + " " + year);
        if (options.onMonthSelect) {
          options.onMonthSelect(monthIndex, month, year);
        }
      },

      _renderYears: function() {
        var markup = $.map(options.years, function(year) {
          return "<option>" + year + "</option>";
        });
        var yearsWrap = $('<div class="years">').appendTo(this._container);
        this._yearsSelect = $("<select>")
          .html(markup.join(""))
          .appendTo(yearsWrap);
      },

      _renderMonths: function() {
        var markup = ["<table>", "<tr>"];
        $.each(months, function(i, month) {
          if (i > 0 && i % 4 === 0) {
            markup.push("</tr>");
            markup.push("<tr>");
          }
          markup.push(
            '<td><button data-value="' + i + '">' + month + "</button></td>"
          );
        });
        markup.push("</tr>");
        markup.push("</table>");
        this._container.append(markup.join(""));
      }
    };

    return this.each(function() {
      return new Monthly(this);
    });
  };
})(jQuery);

$(function() {
  $("#selection").monthly({
    years: [new Date().getFullYear(), new Date().getFullYear() +1],
    topOffset: 28,
    onMonthSelect: function(mi, m, y) {
      mi = padToTwo(mi);
      $("#selection").val(m + " " + y);
      $("#monthly").val(y + "-" + mi);
    }
  });
});




</script>
@endslot

@endcomponent
