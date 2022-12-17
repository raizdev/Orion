var Web;

function htmlDecode(input) {
  var doc = new DOMParser().parseFromString(input, "text/html");
  return doc.documentElement.textContent;
}

function WebInterface() {
    /*
     * Main elements
     * */
    this.web_document = null;

    /*
     * Managers
     * */
    this.pages_manager = null;
    this.ajax_manager = null;
    this.notifications_manager = null;
    this.hotel_manager = null;
    this.customforms_manager = null;

    /*
     * Main initiation
     * */
    this.init = function () {
        // Assign main elements
        this.web_document = $("body");

        // Initialize managers
        this.hotel_manager = new WebHotelManagerInterface();
        this.hotel_manager.init();
        this.pages_manager = new WebPagesManagerInterface();
        this.ajax_manager = new WebAjaxManagerInterface();
        this.pages_manager.init();
        this.notifications_manager = new WebNotificationsManagerInterface();

        // Handlers
        this.forms_handler();
        this.links_handler();

        $( ".player-wave-home").mouseover(function() {
            $(this).attr("src", Configuration.settings.site.fpath + '?figure=' + $(this).data("look") + '&head_direction=3&action=wav');
        })
      
        $( ".player-wave-home").mouseout(function() {
            $(this).attr("src", Configuration.settings.site.fpath + '?figure=' + $(this).data("look"));
        })
    };

    /*
     * Forms
     * */
    this.forms_handler = function () {
        var self = this;
        this.web_document.on("submit", "form:not(.default-prevent)", function (event) {
            event.preventDefault();

            if ($(this).attr("method") !== "get")
                self.ajax_manager.post('/' + $(this).attr("action"), new FormData(this), null, $(this));
            else {
                var href = $(this).attr("action").replace(Configuration.settings.site.domain + "/", "").replace(Configuration.settings.site.domain, "");
                self.pages_manager.load(href + "?" + $(this).serialize());
            }
        });
    };

    /*
     * Links
     * */
    this.links_handler = function () {
        var self = this;
        this.web_document.on("click", "a", function (event) {
            if ($(this).attr("href") === "#" || $(this).hasClass("disabled"))
                event.preventDefault();

        }).on("mouseover", "a:not([target])", function () {
            if ($(this).attr("href"))
                if (!$(this).attr("href") && !$(this).attr("href").match(/^#/))
                    $(this).attr("target", "_blank");

        }).on("click", "a:not([target])", function (event) {
            event.preventDefault();
            if ($(this).attr("href") !== "#" && $(this).attr("href") !== "javascript:;" && $(this).attr("href") !== "javascript:void(0)" && $(this).attr("href") !== undefined) {
                var href = $(this).attr("href");
                if (!href)
                    href = "home";
                if (href.match(/^\#([A-z0-9-_]+)$/i))
                    window.location.hash = href;
                else if (window.location.pathname + window.location.search !== "/" + href || window.location.hash)
                    self.pages_manager.load(href);
            }
        }).on("click", "#login-request", function (event) {
            event.preventDefault();

            $(".spinner-border").show();

            setTimeout(function () {
                var verification_data = {
                    username: $(".login-form [name=username]").val(),
                    password: $(".login-form [name=password]").val()
                };

                Web.ajax_manager.post("/auth/sign-in", verification_data, function (result) {
                    $(".spinner-border").hide();
                });
            }, 1000);
        }).on('keypress',function(event) {
            if(event.which == 13) {
                event.preventDefault();

                $(".spinner-border").show();
    
                setTimeout(function () {
                    var verification_data = {
                        username: $(".login-form [name=username]").val(),
                        password: $(".login-form [name=password]").val()
                    };
    
                    Web.ajax_manager.post("/auth/sign-in", verification_data, function (result) {
                        $(".spinner-border").hide();
                    });
                }, 1000);
            }
        });
    };

    /*
     * Cookies
     * */
    this.check_cookies = function () {
        if (Cookies.get("allow_cookies") === undefined) {
            this.web_document.find(".cookies-accept-container").show();
            this.web_document.find(".cookies-accept-container .close-container").click(function () {
                Cookies.set("allow_cookies", true, {
                    expires: 365
                });
                $(this).parent().hide();
            });
        }
    }
}

$(function () {
    Web = new WebInterface();
    Web.init();
});


function WebPagesManagerInterface() {
    this.current_page_url = null;
    this.current_page_interface = null;
    this.last_page_url = "home";
    this.page_container = null;

    /*
     * Manager initialization
     * */
    this.init = function () {
        var self = this;

        this.page_container = $(".page-container");
        this.current_page_url = window.location.pathname.substr(1) + window.location.search;
        this.current_page_interface = new WebPageInterface(this, this.page_container.attr("data-page"));
        this.current_page_interface.assign_interface();
  
        if (this.current_page_url === "") {
            this.current_page_url = "home";
        }

        if (this.current_page_url.match(/^hotel/) && Configuration.settings.user) {
            Web.hotel_manager.open_hotel(this.current_page_url);
        }

        History.Adapter.bind(window, "statechange", function () {
            var state = History.getState();
            var url = state.url.replace(document.location.origin, "").substring(1);

            if (self.current_page_url !== url) {
                if (url === "/") {
                    self.load("home", null, false, null, false);
                } else {
                    self.load("/" + url, null, false, null, false);
                }
            }
            self.current_page_url = url;
        });
    };

    /*
     * History push
     * */
    this.push = function (url, title, history_replace) {
        url = url.replace(/^\/|\/$/g, "");
        this.current_page_url = url;
  
        if (this.current_page_url.indexOf('profile') > -1) {
        } else {
             $(".page-container").removeAttr('style')
        }
      
        if (!history_replace) {
            History.pushState(null, title ? title : Configuration.settings.name, "/" + url);
        } else {
            History.replaceState(null, title ? title : Configuration.settings.name, "/" + url);
        }
    };

    /*
     * Load page
     * */
    this.load = function (url, data, scroll, callback, history_push, history_replace) {

        if (scroll === undefined) {
            scroll = true
        }

        if (history_push === undefined) {
            history_push = true
        }

        if (history_replace === undefined) {
            history_replace = false
        }

        var self = this;
        var body = $("body");

        if (url === "")
            url = "home";

        if (url.charAt(0) !== "/") {
            url = "/" + url;
        }
        
        this.last_page_url = this.current_page_url;

        if (!url.match(/^\/hotel/)) {

            $.ajax({
                type: "get",
                url: url,
                dataType: null,
                error: function (request, status, error) {
                    if(request.status === 404) {
                        window.location = '/404';
                    } else {
                        Web.notifications_manager.create("error", error, request.responseText);
                    }
                }
            }).done(function (result) {
                var decode = htmlDecode(result);
                var result = JSON.parse(decode)[0];

              // Change full page
                if (result.location) {
                    window.location = result.location;
                    return null;
                }

                if(result.errors) {
                    var errorTitle = result.errors[0].field;
                    var errorMessage = result.errors[0].message;
    
                    Web.notifications_manager.create('error', errorMessage, errorTitle, (Number.isInteger(result.timer) ? result.timer : undefined), (result.link ? result.link : null));
                }

                // Create notification
                if (!isEmpty(result.status) && !isEmpty(result.message))
                    Web.notifications_manager.create(result.status, result.message, (result.title ? result.title : null), (Number.isInteger(result.timer) ? result.timer : undefined), (result.link ? result.link : null));


                // Create dialog
                if (result.dialog) {
                    Web.dialog_manager.create("default", result.dialog, result.title, null, null);
                    return;
                }


                // Change page
                else if (result.loadpage)
                    self.load(result.loadpage);

                // Replace page
                else if (result.replacepage)
                    self.load(result.replacepage, null, true, null, true, true);

                // Build new page
                else {
                  
                    self.current_page_interface = new WebPageInterface(self, result.page, scroll, result.data);
                    self.current_page_interface.build();

                    if (typeof callback === "function")
                        callback(result);
                }

                // Hide hotel
                if (body.hasClass("hotel-visible"))
                    body.removeClass("hotel-visible");

                // Push history & change document title
                if (window.location.pathname + window.location.search === "/" + url)
                    return;
                
                document.title = result.title + ' - ' + Configuration.settings.name;
                self.push(url, result.title + ' - ' + Configuration.settings.name, false);
            });
        } else if (Configuration.settings.user) {
            Web.hotel_manager.open_hotel(url.replace("hotel?", "").replace("hotel", ""));
            self.push(url, Locale.web_hotel_title, false);
        }
    };
}

function WebPageInterface(manager, type, scroll, page_data) {
    if (scroll === undefined) {
        scroll = true;
    }

    /*
     * Page configuration
     * */
    this.manager = manager;
    this.type = type;
    this.scroll = scroll;
    this.page_data = page_data;
    this.page_interface = null;

    /*
     * Build page
     * */
    this.build = function () {
        if (this.page_data === null)
            return;

        var self = this;
        // Assign page

        self.manager.page_container.attr("data-page", this.type).html(this.page_data);

        const element = document.querySelector('.bg-lush-content-bg .row');
        element.classList.add('animate__animated', 'animate__fadeIn');

        // Update navigation
        var navigation_container = $(".navigation-container");

        // Set category
        var category = this.type.substr(0, this.type.lastIndexOf("_"));
        if (isEmpty(category))
            category = this.type;

        navigation_container.find(".navigation-item.selected:not([data-category='" + category + "'])").removeClass("selected");
        navigation_container.find(".navigation-item[data-category='" + category + "']").addClass("selected");

        if (this.manager.current_page_url.indexOf("forum") >= 0) {} else {
            if (this.scroll)
                $("html, body").animate({
                    scrollTop: navigation_container.offset().top
                }, 300);
        }

        // Custom page interface
        this.assign_interface();
    };

    /*
     * Custom interface
     * */
    this.assign_interface = function () {
        if (this.type === "home")
            this.page_interface = new WebPageHomeInterface(this);
        else if (this.type === "article")
            this.page_interface = new WebPageArticleInterface(this);
        else if (this.type === "community")
            this.page_interface = new WebPageCommunityInterface(this);
        else if (this.type === "shop")
            this.page_interface = new WebPageShopInterface(this);
        else if (this.type === "shop_offers")
            this.page_interface = new WebPageShopOffersInterface(this);
        else if (this.type === "profile")
            this.page_interface = new WebPageProfileInterface(this);
        else if (this.type === "jobs")
            this.page_interface = new WebPageJobsInterface(this);
        else if (this.type === "settings_personalisation")
            this.page_interface = new WebPageSettingsInterface(this);
      
      
        if (this.page_interface !== null)
            this.page_interface.init();
    };

    /*
     * Get page container
     * */
    this.get_page_container = function () {
        return this.manager.page_container;
    };

    /*
     * Events
     * */
    this.update = function () {};
}

function WebAjaxManagerInterface() {

    this.get = function (url, callback) {

        // Requests
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            processData: false,
            contentType: false,
            error: function (request, status, error) {
                Web.notifications_manager.create("error", error, request.responseText);
            }
        }).done(function (result) {

            if (typeof callback === "function")
                callback(result);
        });
    }

    /*
     * Post method
     * */
    this.post = function (url, data, callback, form) {
        // Prepare data
        if (!(data instanceof FormData)) {
            if (!(data instanceof Object))
                return;

            var data_source = data;
            data = new FormData();
            for (var key in data_source) {
                if (!data_source.hasOwnProperty(key))
                    continue;

                data.append(key, data_source[key]);
            }
        }

        // Check form name
        if (form !== undefined) {
            if (form.attr("action") === "login")
                data.append("return_url", window.location.href);
        }

        if (url.charAt(0) === "/") {
            url = url.slice(1);
        }

        // Requests
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            processData: false,
            contentType: false
        }).done(function (result) {
            // Change page
            if (result.data && result.data.pagetime)
                setTimeout(function () {
                    window.location = result.data.pagetime
                }, 2500);


            // Change page
            if (result.data && result.data.loadpage)
                Web.pages_manager.load(result.data.loadpage);

            // Replace page
            if (result.data && result.data.replacepage)
                Web.pages_manager.load(result.data.replacepage, null, true, null, true, true);

            // Check if is form
            if (form !== undefined) {
                if (!result.captcha_error)
                    form.find(".registration-recaptcha").removeClass("registration-recaptcha").removeAttr("data-sitekey").removeAttr("data-callback");
            }

            if(result.errors && result.code !== 404) {
                var errorTitle = result.errors[0].field;
                var errorMessage = result.errors[0].message;

                Web.notifications_manager.create('error', errorMessage, errorTitle, (Number.isInteger(result.timer) ? result.timer : undefined), (result.link ? result.link : null));
            }

            // Create notification
            if (result.data && !isEmpty(result.data.status) && !isEmpty(result.data.message) && result.code !== 404) {
                Web.notifications_manager.create(result.data.status,  result.data.message, result.data.title, (Number.isInteger(result.timer) ? result.timer : undefined), (result.data.link ? result.data.link : null));
            }

            // Callback if exists
            if (typeof callback === "function")
                callback(result);
        }).fail(function (xhr, error) {
            Web.notifications_manager.create(error, JSON.parse(xhr.responseText).errors[0].message, error);
            PageLoading.hide();
        });
    };
}

function WebNotificationsManagerInterface() {
    this.titles_configutation = {
        success: 'success',
        error: 'error',
        info: 'info'
    };
    this.notifications = {};

    this.create = function (type, message, title, timer, link) {
        var notification_id = (new Date().getTime() + Math.floor((Math.random() * 10000) + 1)).toString(16);

        if (timer === undefined)
            timer = 5;

        this.notifications[notification_id] = new WebNotificationInterface(this, notification_id, type, message, title, timer, link);
        this.notifications[notification_id].init();
    };

    this.destroy = function (id) {
        if (!this.notifications.hasOwnProperty(id))
            return null;

        this.notifications[id].notification.remove();
        delete this.notifications[id];
    };
}

function WebNotificationInterface(manager, id, type, message, title, timer, link) {
    this.manager = manager;
    this.id = id;
    this.type = type;
    this.message = message;
    this.title = title;
    this.timer = timer;
    this.link = link;
    this.notification = null;
    this.timeout = null;


    this.init = function () {
        var self = this

        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
            duration: 2000,
            dismissible: true
        });

        notyf.open({
            type: (this.manager.titles_configutation[this.type] == "error" ? "error" : "success"),
            message: (this.title) ? this.title + ': ' + this.message : this.message
        });
    };
}