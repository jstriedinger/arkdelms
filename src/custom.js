import "./custom.scss";
import "jquery";
import "jquery-countdown";
import { gsap, ScrollTrigger, ScrollToPlugin } from "gsap/all";

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin); 

const imgs = require.context('./img', true);

var firstTry = true;


export var fadeSpeed = 0.5
export function toggleModal(id,open = true)
{
    if(open)
    {
        $(".arkde-modal#"+id).addClass("is-active");
        gsap.to($(".arkde-modal#"+id), fadeSpeed, {opacity: 1});
    }
    else
        gsap.to($(".arkde-modal#"+id), fadeSpeed, {opacity: 0, onComplete: function(){
            gsap.delayedCall(0.5, function(){$(".arkde-modal#"+id).removeClass("is-active")});
        }})
}

/**
 * Async load of Vimeo background video if there is one
 */
document.addEventListener('DOMContentLoaded', function (event) {
    let $iframe = $('iframe#videobg');
    if($iframe)
    {
        if (window.matchMedia("(min-width: 769px)").matches) {
            let url = $iframe.data("src")
            $iframe.attr('src',url)
        }
        else
        {
            $('section#home, .bb-vw-container.bb-learndash-banner').css("background-image", "url("+$iframe.data("img")+")");  
        }

    }
})


jQuery(document).ready(function(){

    //Reference to the GTM datalayer
    window.dataLayer = window.dataLayer || [];

    const site_currency = gtm4wp_currency;


    //1. If on course page then add view_item event to datalayer
    if(document.body.classList.contains("single-sfwd-courses") || document.body.classList.contains("single-career"))
    {
        let data_item = document.getElementById("datalayer_course");
        let product_name = data_item.dataset.name;
        let product_id = data_item.dataset.id;
        let product_price = data_item.dataset.price;
        let product_category = data_item.dataset.category;
        //let cats = data_item.data("categories").split(',');

        /*let item = {};
        item["item_name"] = product_name;
        item["item_id"] = product_id;
        item["item_price"] = product_price;
        item["item_category"] = 'Curso';
        cats.forEach(function(val,index,array){
            item["item_category_"+(index+2)] = val;
        })
        item["quantity"] = 1;

        //GA4 event
       /* window.dataLayer.push({
          'event': 'view_item',
          'ecommerce': {
            'currencyCode': data_item.data("currency"),
            'items': [item]
          }
        });*/

        //GUA Product detail
        dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
        dataLayer.push({
        'ecommerce': {
            'currencyCode': site_currency,
            'detail': {
                'actionField': {'list': product_category},
                'products': [{
                    'name': product_name,         // Name or ID is required.
                    'id': product_id,
                    'price': product_price,
                    'category': product_category
                }]
            }
        }
        });

    }

    //Product (course) item click
    $("a.has-card.item-click").click(function(e){

        if (window['google_tag_manager'])
        {
            e.preventDefault();
            let href = this.href;
            let item = {};
            item["item_name"] = this.getAttribute("data-name");
            item["item_id"] = this.getAttribute("data-id");
            item["item_price"] = this.getAttribute("data-price");
            item["item_category"] = this.getAttribute("data-category");
            /*cats.forEach(function(val,index,array){
                item["item_category_"+(index+2)] = val;
            })
            item["quantity"] = 1;*/
    
            //GA4 Event
            /*
            window.dataLayer.push({
              'event': 'select_item',
              'ecommerce': {
                'currencyCode': "USD",
                'items': [item]
              },
              'eventCallback' : function() {
                    console.log("tags fired");
                    window.location = href;
                }
            });
            */
    
            //GUA Analytics Product Click
            dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'currencyCode': site_currency,
                    'click': {
                        'actionField': {'list': item["item_category"]},
                        'products': [{
                            'name': item["item_name"],                      // Name or ID is required.
                            'id': item["item_id"],
                            'price': item["item_price"],
                            'category': item["item_category"]
                        }]
                    }
                },
                'eventCallback': function() {
                    document.location = href
                }
            });

        }
        
     })

     //Woocommerce add to cart Tag Manager
     $("a.add_to_cart_button").click(function(e)
     {

        //UX loading feedback
        $(this).addClass("is-loading");
        if (window['google_tag_manager'])
        {
            e.preventDefault();
            let href = this.href;
            let data_item = document.getElementById("datalayer_course");
            let product_name = data_item.dataset.name;
            let product_id = data_item.dataset.id;
            let product_price = data_item.dataset.price;
            let cat = data_item.dataset.category;

            //GUA Analytics Add to Cart
            dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
            dataLayer.push({
                'event': 'addToCart',
                'ecommerce': {
                    'currencyCode': site_currency,
                    'add': {                                // 'add' actionFieldObject measures.
                    'products': [{                        //  adding a product to a shopping cart.
                        'name': product_name,
                        'id': product_id,
                        'price': product_price,
                        'category': cat,
                        'quantity': 1
                        }]
                    }
                },
                'eventCallback': function() {
                    document.location = href
                },
                'eventTimeout' : 2000
            });
        }

        
        
     })
    
	jQuery( 'body' ).on( 'added_to_cart', function( e, fragments, cart_hash, this_button ) {
	    //1. Find if button in learndash course banner
	    if($(this_button).parent().hasClass("from-learndash-banner")){
	    	$(this_button).before('<i class="bb-icon-check-circle"></i> Ya está en tu carrito <br>');
	    	$(this_button).parent().after('<a href="./cart/" class="added_to_cart wc-forward button teal large" title="Ver carrito">Ver carrito</a>');
	    	$(this_button).remove();
	    }
	    //2. Add added to cart text
	    //3. Change button
	});

    $("#sticky_sidebar").stick_in_parent({offset_top: 130});

    /**
     * PayU Latam hack. 
     * The payu gateway plugin is OLD so here's a hack to save the user another clic after 
     * clicking purchase in the checkout
     */
    if(document.getElementById("submit_payu_latam"))
    {
        console.log("here payu payment");
        $('input#submit_payu_latam').click();
        $("body").block({
            message: "Te estamos dirigiendo a la pagina de PayU Latam para procesar tu pago :) ",
            baseZ: 99999,
            overlayCSS:
            {
                background: "#33016a",
                opacity: 0.7
            },
            css: {
                padding:        "20px",
                zindex:         "9999999",
                textAlign:      "center",
                color:          "#555",
                border:         "0px",
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px',
                backgroundColor:"#fff",
                cursor:         "wait",
                lineHeight:     "24px",
            }
        });
    }

    //asyn load of backkground videos if needed
	var sources  = document.querySelectorAll('video.banner-video source');
    if(sources)
    {

        // Define the video object this source is contained inside
        var videos = document.querySelectorAll('video.banner-video');
        for(var i = 0; i<sources.length;i++) {
          sources[i].setAttribute('src', sources[i].getAttribute('data-src'));
        }
        // If for some reason we do want to load the video after, for desktop as opposed to mobile (I'd imagine), use videojs API to load
        for(var i = 0; i<videos.length;i++) {
            videos[i].load();
        }
    }

   

    //Notification bar
    let le_date = $("#clock").data("date");
    $('#clock').countdown(le_date, function(event) {
      $(this).html(event.strftime('%D días %Hh : %Mm : %Ss'));
    });

    //If there's the promo bar, then show it after 3 segs
     if(document.getElementById("promo-bar"))
     {
        setTimeout(function () {
            $('#promo-bar').addClass("active");
        }, 3000);
     }
     $("#promo-bar .close-button").on("click",function(event ){
        event.preventDefault();
        
        $("#promo-bar").removeClass("active");
        setTimeout(function () {
            console.log("here");
            $("#promo-bar").remove();
        }, 1000);
    })


    //Close function to all modals
    $(".arkde-modal .close-button").click(function(event ){
        event.preventDefault();
        let modal = $(this).data("modal");
        gsap.to($("#"+modal), fadeSpeed, {opacity: 0, onComplete: function(){
            gsap.delayedCall(0.5, function(){$("#"+modal).removeClass("is-active")});
            
        }})
    })

    //Modal opener
    $("a.modal-opener").click(function(e){
        e.preventDefault()
        let m =  $(this).data("modal")
        toggleModal(m);
        return false;
    })

    
     


     //If there's the next event modal, then show it after 3 segs
     if(document.getElementById("next-event-modal") && sessionStorage.getItem("next_event_modal") != "true")
     {
        setTimeout(function () {
            toggleModal("next-event-modal");
            sessionStorage.setItem("next_event_modal", "true");
        }, 3000);
     }
   
    /**
     * Get the rates frm Open Exchange Rate API.
     * NOT USED ANYMORE
     * @return {[type]} [description]
     */
     /*
    function getRates(){
        const key = "rates"; // Key to identify our data in sessionStorage

        // Checking the cache's data in SessionStorage
        let rates = sessionStorage.getItem(key);
        if(rates){
            // If there's somethin in the sessionStorage with our key, return that data:
            console.log("Return cached data");
            fx.rates = JSON.parse(rates);
            fx.base = "USD";
            return;
        }

        //If there's nothing in the storage, make the AJAX request:
        $.ajax({
          url: 'https://openexchangerates.org/api/latest.json?app_id=dc4d804f35df42a5b1589b3cd34bc909',
          dataType: 'json',
          async: false,
          success: function(data) {
            rates = data.rates;
                sessionStorage.setItem(key, JSON.stringify(data.rates));
                fx.rates = rates;
                fx.base = "USD";
                return;
          }
        });
        
    }*/

    //Learndash file input handle
    $( '.ld-file-input' ).off("change");


    $( "form#uploadfile_form" ).submit(function( event ) {
        if( $("#assignment_answer").val()){
            //assignment has an answer
            $(this).find("button.button").addClass("is-loading");
            $(this).unbind('submit').submit();
        }
        else
        {
            event.preventDefault();
        }
    });


    //Course shore more button
    $(".show-more a").on("click", function() {
        var $this = $(this); 
        
        $(".course-content").toggleClass("showing");
        $(this).children( 'i.bb-icons' ).remove();
        if($(".course-content").hasClass("showing"))
        {
            $(this).append( "<i class='bb-icon-chevron-up bb-icons is-large' style='vertical-align:bottom'></i>" );
            //$this.html("Mostrar menos <i class='bb-icon-chevron-up bb-icons is-large' style='vertical-align:bottom'></i>");
        }
        else
            $(this).append( "<i class='bb-icon-chevron-down bb-icons is-large' style='vertical-align:bottom'></i>" );
           // $this.html("Mostrar más <i class='bb-icon-chevron-down bb-icons is-large' style='vertical-align:bottom'></i>");

        return false;
        
    });

    //Leanrdash review pluign hack - remove login to review
    if(document.getElementsByClassName('login-to-enroll button'))
    {
        $(".login-to-enroll.button").parent().remove();
    }
   
});


