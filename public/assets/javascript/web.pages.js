function WebHotelManagerInterface() {
    this.hotel_container = null;
    this.current_page_url = null;
    this.hotel_url = null;
    /*
     * Manager initialization
     * */
    this.init = function() {
        this.current_page_url = window.location.pathname.substr(1) + window.location.search;

        this.hotel_container = $("#hotel-container");

        this.hotel_container.find(".client-buttons .client-close").click(this.close_hotel);
        this.hotel_container.find(".client-buttons .client-fullscreen").click(this.toggle_fullscreen.bind(this));
        this.hotel_container.find(".client-disconnected").hide();
    };
    
    const scrollContainer = () => {
      return document.documentElement || document.body;
    };
    
    const goToTop = () => {
      document.body.scrollIntoView({
        behavior: "smooth"
      });
    };
    
    document.addEventListener("scroll", () => {
      if (scrollContainer().scrollTop > 100) {
        document.querySelector(".scroll-top-btn").classList.add("active");
      } else {
        document.querySelector(".scroll-top-btn").classList.remove("active");
      }
    });
    
    document.querySelector(".scroll-top-btn").addEventListener("click", goToTop);

    /*
     * Hotel toggle
     * */
    this.close_hotel = function() {
        Web.pages_manager.load(Web.pages_manager.last_page_url, null, true, null, true);
    };

    this.open_hotel = function(arguments) {
        var actions = {};
        var container = this.hotel_container;
        var container_actions = this.hotel_actions;

        if (arguments !== undefined) {
            parse_str(arguments, actions);
        }
        var argument = arguments;
        var body = $("body");

        this.current_page_url = argument;
        this.hotel_url = argument;

        if (!body.hasClass("hotel-visible")) {

                    if (container.find(".client-frame").length === 0)

                        Web.ajax_manager.get("/auth/ticket", function(result) {

                            let argumentAction = '';
                            if (argument != "") {
                                let argumentAction = argument.replace("hotel?room=", "&room=");
                            }
                            console.log(Configuration)
                            container.prepend('<iframe id="nitro" class="client-frame" src="' + Configuration.settings.hotel.nitro + '/?sso=' + result.data.ticket + argumentAction + '"></iframe>');

                            let frame = document.getElementById('nitro');

                            window.FlashExternalInterface = {};
                            window.FlashExternalInterface.disconnect = () => {
                                container.find(".client-disconnected").show();
                            };

                            if (frame && frame.contentWindow) {
                                window.addEventListener("message", ev => {
                                    if (!frame || ev.source !== frame.contentWindow) return;
                                    const legacyInterface = "Nitro_LegacyExternalInterface";
                                    if (typeof ev.data !== "string") return;
                                    if (ev.data.startsWith(legacyInterface)) {
                                        const {
                                            method,
                                            params
                                        } = JSON.parse(
                                            ev.data.substr(legacyInterface.length)
                                        );
                                        if (!("FlashExternalInterface" in window)) return;
                                        const fn = window.FlashExternalInterface[method];
                                        if (!fn) return;
                                        fn(...params);
                                        return;
                                    }
                                });
                            }

                        });

                    body.addClass("hotel-visible");
                }
        }

    /*
     * Fullscreen toggle
     * */
    this.toggle_fullscreen = function() {
        if ((document.fullScreenElement && document.fullScreenElement) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            }

            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon").addClass("hidden");
            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon-back").removeClass("hidden");
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }

            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon").removeClass("hidden");
            this.hotel_container.find(".client-buttons .client-fullscreen .client-fullscreen-icon-back").addClass("hidden");
        }
    };
}

function WebPageSettingsInterface(main_page) {
    this.main_page = main_page;
    /*
     * Generic function
     * */
    this.init = function() {
        var self = this;
        var page_container = this.main_page.get_page_container();

        $(".backgroundSwitcher").click(function() {
            Web.ajax_manager.post("/change-personalisation", {
                avatar_bg: $(this).attr("value")
            });
        });
    };
}

function WebPageProfileInterface(main_page) { 
    this.main_page = main_page;

    this.init = function () {
        var self = this;
        var page_container = this.main_page.get_page_container();
        var myStore = {};

        function saveWidgets() {
            var arr = [];
            $('.selectedActive').each(function (i, obj) {

                var ids = $(this).attr('data-ids');
                var id = $(this).attr('data-id');
                var top = $(this).attr('data-top');
                var left = $(this).attr('data-left');
                var skin = ($(this).attr('data-skin') !== undefined) ? $(this).attr('data-skin') : null;
                var type = $(this).attr('data-type');
                var zIndex = ($(this).attr('data-zIndex') !== "") ? $(this).attr('data-zIndex') : null;
                var data = ($(this).attr('data-id') === "2081") ? $('textarea#data').val() : null;
                `       `
                $(".selectedActive").removeClass('selectedItem');
                arr.push([ids, id, top, left, skin, type, data, zIndex]);
            });

            if (arr.length !== 0) {
                Web.ajax_manager.post("/home/profile/save", {
                    draggable: JSON.stringify(arr),
                    background: $(".page-content").attr('data-background')
                });
            }

            arr = []
        }

        page_container.find(".addNote").click(function () {
            var arr = []
            arr.push([undefined, 2081, 29, 100, "note_skin", "w", "Text here..", "138"])

            if (arr.length !== 0) {
                Web.ajax_manager.post("/home/profile/save", {
                    draggable: JSON.stringify(arr)
                });
            }

            arr = []
        });


        page_container.find(".saveProfile").click(function() {

            page_container.find(".editActive").hide();
            page_container.find(".editProfile").show();

            saveWidgets();
        });

        page_container.find(".icon-edit").click(function(e) {

            var id = $(this).attr('data-id');

            page_container.find(".deleteElement[data-id=" + id + "]").unbind("click").click(function (e) {
                Web.ajax_manager.post("/home/profile/remove", {
                    id: $(this).attr('data-id'),
                    type: $(this).attr('data-type'),
                }, function (result) {
                    if (result.status == "success") {
                        $(".widget[data-ids=" + id + "]").remove();
                    }
                });
            });

            page_container.find("#" + id + '-menu').show();

            page_container.find(".selectSkin[data-id=" + id + "]").unbind("click").click(function(e) {
                if ($(this).val() !== null) {

                    $(".widget[data-ids=" + id + "]").removeClass('widget_' + $(".widget[data-ids=" + id + "]").attr('data-skin'));
                    $(".widget[data-ids=" + id + "]").addClass('widget_' + $(this).val());
                    $(".widget[data-ids=" + id + "]").attr('data-skin', $(this).val());

                }
            });

            $(document).mouseup(function(e) {
                var container = $("#" + id + "-menu");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });

        });

        page_container.find(".close-inventory").click(function() {
            page_container.find(".modal-inventory").hide();
        })
      
        page_container.find(".sticker-cat-open").click(function() {
            page_container.find("#stickers-cat").toggle();
        });

        page_container.find(".openInventory").click(function() {
            
            page_container.find(".modal-inventory").show();
            if (!page_container.find(".openInventory").hasClass("open"))
              
            Web.ajax_manager.post("/home/profile/store", {
                data: 'w',
                type: null
            }, function(data) {

                page_container.find("#widgets-cat").click(function () {
                    page_container.find(".stickers-data").hide();
                    page_container.find(".widgets-data").show();

                    page_container.find(".widgetButton").click(function () {
  
                        let status = page_container.find(`.modal-inventory .widgets [data-id=${$(this).data("id")}]`).attr("data-status")

                        Web.ajax_manager.post("/home/profile/update", {
                            id: $(this).data("id"),
                            status: status
                        }, function (data) {
                            if (data.statusWidget == "0") {
                                page_container.find(`.widgets [data-ids=${data.widgetId}]`).show();
                                page_container.find(`.widget [data-ids=${data.widgetId}]`).show();
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).attr("data-status", 1);
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).removeClass("llbtn-success");
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).addClass("llbtn-danger");
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).html("Hide");
                            } else {
                                page_container.find(`.widgets [data-ids=${data.widgetId}]`).hide();
                                page_container.find(`.widget [data-ids=${data.widgetId}]`).hide();
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).attr("data-status", 0);
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).removeClass("llbtn-danger");
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).addClass("llbtn-success");
                                page_container.find(`.modal-inventory .widgets [data-id=${data.widgetId}]`).html("Show");
                            }
                            page_container.find(".modal-inventory").hide();
                        })
                    })
                });

                if (data.categorys.length > 0) {
                    for (var i = 0; i < data.categorys.length; i++) {
                        var category = data.categorys[i];

                        page_container.find("#stickers-cat .list-group")
                            .append('<button type="button" data-category="' +  category.id + '" class="list-group-item small list-group-item-action">' +  category.name + '</button>')
                    }
                }
                
                function addStickerBackground(image, value, id, type) {
                        page_container.find(".overview").show();
                        page_container.find(".overview-preview").attr("src", image);

                        myStore = {
                            image: image,
                            type: type,
                            value: value,
                            id: id
                        }
                } 

                page_container.find("#stickers-cat .list-group-item-action").click(function() {

                      Web.ajax_manager.post("/home/profile/items", {
                          data: 's',
                          type: null,
                          id: $(this).data("category")
                      }, function (data) {

                          page_container.find(".stickers-data").show();
                          page_container.find(".widgets-data").hide();
                          page_container.find(".store-item-grid").empty();

                          $.each(data.items, function(index, value) {
                              page_container.find(`.store-list-overflow [data-type=s]`)
                                .append('<div class="store-item-grid-item store-item-grid-item_s" data-name="' + value.data + '" data-id="' + value.id + '" data-value="' + value.data + '"   style="background-image: url(/assets/images/homestickers/' + value.data + '.gif);"></div>');
                          });

                          page_container.find(".store-item-grid-item_s").click(function () {
                              addStickerBackground('/assets/images/homestickers/' + $(this).data('value') + '.gif', $(this).data('value') + '.gif', $(this).data('id'), 's');
                          });
                      });
                });
                   
                page_container.find("#background-cat").click(function () {
                        page_container.find(".stickers-data").show();
                            page_container.find(".widgets-data").hide();

                      Web.ajax_manager.post("/home/profile/items", {
                          data: 'b',
                          type: null
                      }, function (data) {
                            
                          page_container.find(".store-item-grid").empty();

                          $.each(data.items, function (index, value) {
                              page_container.find(`.store-list-overflow [data-type=s]`)
                                  .append('<div class="store-item-grid-item store-item-grid-item_s" data-name="' + value.data + '" data-id="' + value.id + '" data-value="' + value.data + '"   style="background-image: url(/assets/images/profile_backgrounds/' + value.data + ');"></div>');

                          });

                          page_container.find(".store-item-grid-item_s").click(function () {
                              addStickerBackground('/assets/images/profile_backgrounds/' + $(this).data('value'), $(this).data('value'), $(this).data('id'), 'b');
                          });
                      });
                  });
                
            });
            page_container.find(".openInventory").addClass("open");
        });

        page_container.find(".placeButton").unbind().click(function() {
 
            if (myStore.type == "s") {
                $('<div class="widget selectedActive" data-id="' + myStore.id + '" data-zIndex="0" data-type="s" style="position: relative; width: 0px; top: -173px; z-index: 0;"><img src="' + myStore.image + '"></div>').appendTo('.page-content').draggable({
                    containment: $('.page-content'),
                    stop: function() {
                        $(this).attr('data-top', $(this).css("top").replace('px', ''))
                        $(this).attr('data-left', $(this).css("left").replace('px', ''))
                    },
                    start: function() {
                        var maximum = null;

                        $('.widget').each(function() {
                            var value = parseFloat($(this).css("z-index"));
                            maximum = (value > maximum) ? value : maximum + 1;
                        });

                        $(this).css('zIndex', maximum);
                        $(this).attr('data-zIndex', maximum)
                    }
                });
            }

            if(myStore.type == "b") {
                page_container.find('.page-content').css('background-image', 'url(' + myStore.image + ')');
                page_container.find('.page-content').attr('data-background', myStore.id);
            }
          
            page_container.find(".modal-inventory").hide();
        });

        page_container.find(".editProfile").click(function() {

            page_container.find(".editActive").show();
            page_container.find(".editProfile").hide();

            $('.widget').mousedown(function(e) {
                var maximum = null;

                $('.widget').each(function() {
                    var value = parseFloat($(this).css("z-index"));
                    maximum = (value > maximum) ? value : maximum + 1;
                });

                $(this).css('zIndex', maximum);
                $(this).attr('data-zIndex', maximum)
            });

            $('.widget').mouseenter(function() {
                $('*[data-ids="' + $(this).attr('data-ids') + '"]').addClass('selectedActive')
                $('*[data-ids="' + $(this).attr('data-ids') + '"]').draggable({
                    containment: $('.page-content'),
                    stop: function() {
                        $(this).attr('data-top', $(this).css("top").replace('px', ''))
                        $(this).attr('data-left', $(this).css("left").replace('px', ''))
                    }
                });
            });

            page_container.find(".changeBg").click(function() {
                Web.ajax_manager.post("/home/profile/store", {
                    data: 'b'
                }, function(data) {

                    var dialog = $(template);

                    $.magnificPopup.open({
                        items: {
                            src: dialog,
                            type: 'inline'
                        }
                    });

                    if (data.items.length > 0) {
                        for (var i = 0; i < data.items.length; i++) {

                            var background = data.items[i];
                            var backgroundInsert = $(self.backgrounds_template.replace(/{{image.url}}/g, background.data).replace(/{{id}}/g, background.id));
                            dialog.find(".notification-content").append(backgroundInsert);

                            dialog.find(".bgImage[data-id=" + background.id + "]").click(function() {
                                page_container.css('background', 'url(' + $(this).attr("src") + ')');
                                page_container.find('.page-content').attr('data-background', $(this).attr("src").replace('/assets/images/profile_backgrounds/', ''));
                            });
                        }
                    }
                });
            });
        })
    }
}

function WebPageCommunityInterface(main_page) {
    this.init = function() {
        this.main_page = main_page;
        var page_container = this.main_page.get_page_container();
      
        page_container.find(".remove-tag").click(function() {
            Web.ajax_manager.post("/community/delete-tag", {id: $(this).data("tag")});
        });

        page_container.find(".btn-link").click(function() {
          $('.first-five-rooms').each(function( index , element  ){
              if(element.hasAttribute("style")) {
                  $(element).removeAttr("style");
                  $(".enter-room-button").attr("src","/assets/images/icons/arrow_up.png")
              } else {
                  $(element).attr("style", "display: none !important")
                  $(".enter-room-button").attr("src","/assets/images/icons/arrow_right.png")
              }
          });
        });

        page_container.find(".my-likes-button").click(function() {
            $(this).addClass("disabled");
            $(this).prev().removeClass("disabled");
            $(".my-likes").fadeIn("slow");
            $(".my-tags").fadeOut("fast");
        });
      
        page_container.find(".my-tags-button").click(function() {
            $(this).next().removeClass("disabled");
            $(this).addClass("disabled");
            $(".my-likes").fadeOut("slow");
            $(".my-tags").fadeIn("fast");
        });
      
        page_container.find(".top-rated-button").click(function() {
              $(this).removeClass("disabled");
              $(this).prev().addClass("disabled");
              $(".top-rated").fadeIn("slow");
              $(".recommended").fadeOut("fast");
        });
      
        page_container.find(".recommended-button").click(function() {
              $(this).removeClass("disabled");
              $(this).next().addClass("disabled");
              $(".top-rated").fadeOut("fast");
              $(".recommended").fadeIn("slow");
        });

        $(function () {
            // An array of possible questions
            var questions = Locale.tags_questions;

            var currentQuestion = 0;

            function nextQuestion(showImmediately) {
                var hidden = $.Deferred();

                if (!showImmediately)
                    $('#tagQuestions').fadeOut('slow', function() { hidden.resolve(); });
                else
                    hidden.resolve();

                hidden.promise().done(function() {
                    var randomIndex = currentQuestion;
                    while (randomIndex === currentQuestion) {
                        randomIndex = (Math.floor(Math.random() * questions.length));
                    }

                    $('#tagQuestions').html(questions[randomIndex]);
                    $('#tagQuestions').fadeIn('slow');
                    currentQuestion = randomIndex;
                });
            }

            nextQuestion(true);
            setInterval(function() { nextQuestion(false); }, 3500);
        });
    }
}

function WebPageHomeInterface(main_page) {
    this.main_page = main_page;
 
    /*
     * Generic function
     * */
    this.init = function() {
        var page_container = this.main_page.get_page_container();
        if (Configuration.settings.recaptcha.publickey && !Configuration.settings.user && Configuration.settings.recaptcha.publickey !== null) {
            grecaptcha.render('registration-recaptcha', {
                'sitekey': Configuration.settings.recaptcha.publickey
            });
        }

        setInterval(function() {
            $(".content-o").remove();
            $(".content-w-o").removeClass('d-none');
         }, 200);
      
        page_container.find("[name=username]").keyup(function() {
            Web.ajax_manager.post("/user/look", { username: $(this).val() }, function(result) {
                if(result.data) {
                    page_container.find(".empty-avatar").remove();
                    page_container.find(".ghost-look").append('<img src="' + Configuration.settings.site.fpath + '?figure=' + result.data + '&direction=4" class="empty-avatar user">');
                } else {
                    page_container.find(".empty-avatar").remove();
                    page_container.find(".ghost-look").append('<div class="empty-avatar"></div>')
                }
            });
        });
      
        

        page_container.find("#hablush-login-form .open-registration-form").click(function() {
            page_container.find(".registration-form").show();
        });

        page_container.find("#hablush-login-form .registration-form-close").click(function() {
            page_container.find(".registration-form").hide();
        });
    };
}

function WebPageShopInterface(main_page) {
    this.main_page = main_page;

    /*
     * Generic function
     * */
    this.init = function() {
        var self = this;
        var page_container = this.main_page.get_page_container();

        page_container.find(".purchase-item").click(function() {

            var orderId = $(this).data("id");

            if (page_container.find(".paypal-buttons")[0]) {
                return;
            }

            $(this).hide();
            $(".payment-loader").show();
            $(".order-description").hide();

            paypal.Buttons({
                createOrder: function(data, actions) {
                    return fetch('/shop/offers/createorder', {
                        method: 'post',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            orderId: orderId
                        })
                    }).then(function(res) {
                        return res.json();
                    }).then(function(orderData) {
                        return orderData.id;
                    });
                },
                onError: function(err) {
       
                    $(".payment-loader").hide();
                    $(".order-description").show();

                    Web.notifications_manager.create("error", err, 'Error..');            
                },
                onCancel: function(data) {
                    $(".payment-decline").show();
                    $(".payment-loader").hide();
   
                    Web.ajax_manager.post("/shop/offers/status", {
                        status: 'CANCELD',
                        orderId: data.orderID
                    });
                },
                onApprove: function(data, actions) {
                    return fetch('/shop/offers/captureorder', {
                        method: 'post',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            orderId: data.orderID,
                            offerId: orderId
                        })
                    }).then(function(res) {
                        return res.json();
                    }).then(function(orderData) {

                        var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

                        if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                            return actions.restart();
                        }

                        if (errorDetail) {
                            Web.notifications_manager.create("error", 'Sorry, your transaction could not be processed.', 'Error..');
                        }

                        Web.ajax_manager.post("/shop/offers/validate", {
                            orderId: orderData.id
                        });

                        $(".payment-accept").show();
                        $(".payment-loader").hide();

                        var myAudio = new Audio('/assets/images/shop/cash.mp3');
                        myAudio.play();
                    });
                }
            }).render('#paypal-container');
        });
    };
}

function WebPageArticleInterface(main_page) {
    this.main_page = main_page;

    /*
     * Generic function
     * */
    this.init = function() {
        var self = this;
        var page_container = this.main_page.get_page_container();


        page_container.find(".article-reply").click(function() {
            var id = $(this).attr("data-id");
            var reply = $('#reply-message').val();
            
            Web.ajax_manager.post("/community/articles/add", {
                articleid: id,
                message: reply
            });
        });

    };
}

function WebPageJobsInterface(main_page) {
    this.main_page = main_page;

    /*
     * Generic function
     * */
    this.init = function() {
        var self = this;
        var page_container = this.main_page.get_page_container();

        page_container.find(".experiences-container .add-experience").click(function() {
            var experience_container = $(this).closest(".experiences-container").find(".experience-container:first-child").clone();
            experience_container.find("[name]").val("");

            experience_container.insertBefore($(this));

        });

        page_container.find(".no-experience").change(function() {
            var experience_field = page_container.find(".experiences-container[data-experience-field = '" + $(this).attr("data-experience-field") + "']");

            if (experience_field.length === 0)
                return null;

            if ($(this).is(":checked"))
                experience_field.hide();
            else
                experience_field.show();

        });

        page_container.on("click", ".experiences-container .experience-container .remove button", function() {
            if ($(this).closest(".experiences-container").find(".experience-container").length === 1)
                return null;

            $(this).closest(".experience-container").remove();

        });
    };
}

