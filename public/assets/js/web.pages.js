function WebHotelManagerInterface() {
    this.hotel_container = null;
    this.current_page_url = null;
    /*
     * Manager initialization
     * */
    this.init = function() {
        this.current_page_url = window.location.pathname.substr(1) + window.location.search;
        this.hotel_container = $("#hotel-container");

        this.hotel_container.find(".client-buttons .client-close").click(this.close_hotel);
        this.hotel_container.find(".client-buttons .client-fullscreen").click(this.toggle_fullscreen.bind(this));
        this.hotel_container.find(".client-buttons .client-count").click(this.update_online);
    };

    this.update_online = function () {
        Web.ajax_manager.get("/user/online", function (result) {
            $("body").find(".client-buttons .client-count #count").text(result.data);
        });
    };

    /*
     * Hotel toggle
     * */
    this.close_hotel = function() {
        Web.pages_manager.load(Web.pages_manager.last_page_url, null, true, null, true);
    };

    this.open_hotel = function(arguments) {
        var actions = {};
        var container = this.hotel_container;

        if (arguments !== undefined) {
            parse_str(arguments, actions);
        }

        var argument = arguments;
        var body = $("body");

        this.current_page_url = argument;
        this.hotel_url = argument;


        if (!body.hasClass("hotel-visible")) {
            if (container.find(".client-frame").length === 0) {

                let argumentAction = '';
                if (argument !== "") {
                    let argumentAction = argument.replace("hotel?room=", "&room=");
                }

                Web.ajax_manager.get("/auth/ticket", function (result) {

                    container.prepend('<iframe class="client-frame nitro" src="' + ConfigLoading.data.nitro_url + '/?sso=' + result.data.ticket + argumentAction + '"></iframe>');

                    let frame = document.getElementById('nitro');

                    window.FlashExternalInterface = {};
                    window.FlashExternalInterface.disconnect = () => {
                        Web.notifications_manager.create("error", "Client disconnected!");
                        Web.pages_manager.load('/home');
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
            }
            body.addClass("hotel-visible");
        }
    };


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

function WebPageArticleInterface(main_page) {
    this.init = function() {}
}

function WebPageSettingsNamechangeInterface(main_page) {
    this.init = function() {}
}

function WebPageSettingsInterface(main_page) {
    this.init = function() {}
}

function WebPageSettingsVerificationInterface(main_page) {
    this.init = function() {}
}

function WebPageCommunityPhotosInterface(main_page) {
    this.main_page = main_page;

    this.init = function() {
        var self = this;

        let page_container = this.main_page.get_page_container();

        page_container.find(".photo").click(function(e) {
            let backgroundImage = $(this).find(".inner-photo").css("background-image");
            $(".modal-img").attr("src", backgroundImage.split(/"/)[1]);
            $(".modal-title").text("By " + $(this).find(".photo-title").text());

            const myModal = new bootstrap.Modal(document.getElementById('photoModal'));
            myModal.show();
        });

        page_container.find(".photo .photo-actions .vote").click(function() {
            self.vote($(this).data('id'), $(this).data("vote"))
        });
    }

    this.vote = function (id, voted) {
        Web.ajax_manager.post("//vote/create", {
            entity_id: id,
            vote_entity: 6,
            vote_type: (voted === "like") ? 1 : 0
        });
    }
}


function WebPageHomeInterface(main_page) {
    this.main_page = main_page;

    this.current_page = 1;

    /*
     * Generic function
     * */
    this.init = function() {
        let page_container = this.main_page.get_page_container();

        const photoInterface = new WebPageCommunityPhotosInterface(main_page);

        page_container.find(".photo-content .vote").click(function() {
            photoInterface.vote($(this).data("id"), $(this).data("vote"));
        });
    }
}

function WebPageRegistrationInterface(main_page) {
    this.main_page = main_page;
    this.gender = "male";
    this.clouds_interval = null;
    this.clouds_frame = 0;

    /*
     * Generic function
     * */
    this.init = function() {
        var self = this;
        var page_container = this.main_page.get_page_container();

        page_container.find(".username").keyup(function() {
            self.username_availability($(this).val());
        });

        var gender_selection = page_container.find(".gender-selector .gender");
        gender_selection.click(function() {
            if($(this).attr("data-gender") === "M") {
                page_container.find(".gender-selector .male").removeClass('not-active').addClass('active');
                page_container.find(".gender-selector .female").removeClass('active').addClass('not-active');
                page_container.find(".avatar-selector .girl").attr('style','display:none !important');
                page_container.find(".avatar-selector .boy").show();
            }

            if($(this).attr("data-gender") === "F") {
                page_container.find(".gender-selector .female").removeClass('not-active').addClass('active');
                page_container.find(".gender-selector .male").removeClass('active').addClass('not-active');
                page_container.find(".avatar-selector .boy").attr('style','display:none !important');
                page_container.find(".avatar-selector .girl").show();
            }

            $(".avatar-selector [name=gender]").attr("value", $(this).attr("data-gender"));
        });

        var gender_selection = page_container.find(".avatar-selector .wrapper-item");
        gender_selection.click(function() {
            $('.wrapper-item').not(this).removeClass('active').addClass('not-active');
            $(this).removeClass('not-active').addClass('active');

            var look_preview = page_container.find(".look-preview");
            var look_url = look_preview.attr("src").replace(/figure=.*&direction/, 'figure=&direction');
            var look_figure = $(this).find('.look').attr("data-look");
            var figure = look_url.replace(/figure=.*&direction/, 'figure=' + look_figure + '&direction');

            page_container.find('.look-preview').attr("src", figure);
            page_container.find('.avatar-selector [name=look]').attr("value", figure);
        });
    }


    this.username_availability = function(username) {
        var page_container = this.main_page.get_page_container();

        if (username.length > 2) {
            Web.ajax_manager.post("/settings/namechange/availability", {
                username: username
            }, function(result) {
                if (result.status !== "available") {
                    page_container.find(".username").css('border', '1px solid red');
                } else {
                    page_container.find(".username").css('border', '1px solid green');
                }
            });
        } else {
            page_container.find(".username").css('border', '1px solid red');
        }
    };

}

