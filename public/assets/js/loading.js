var SiteLoading;
var PageLoading;
var Config;

$(function ()
{
    SiteLoading = new SiteLoadingInterface();
    SiteLoading.init();
    SiteLoading.load_file(0);
    PageLoading = new PageLoadingInterface();
    Config = new Config();
    Config.getConfig();
});

function Config ()
{
    var self = this;

    this.data = null;

    this.getConfig = function ()
    {
        $.getJSON('/config', function(result) {
            self.data = result.data;
        });

        this.setConfig();
    }

    this.setConfig = function () {
        setTimeout(() => {
            this.getConfig();

            $(".online-user .count").text(self.data.online_users);

            $(".user-bar[data-type='credits'] .item-column .amount").text(self.data.credits);
            $.each(self.data.user.currencies, function(i, item) {
                $(".user-bar[data-type='" + item.type + "'] .item-column .amount").text(item.amount);
            });
        }, 30000);
    }
}

function SiteLoadingInterface()
{
    this.loaded_files = 0;
    this.total_files = 2;
    this.loading_container = null;
    this.cache_id = null;

    this.init = function ()
    {
        console.log("Cosmic Forward - All rights reserved");
        this.total_files = 1;
        this.loading_container = $(".loading-container");
        this.cache_id = null;
    }

    this.load_file = function (file_id)
    {
        var self = this;
        var percent = 100;

        self.loading_container.find(".c100").attr("class", "c100 p" + percent + " center");

        setTimeout(function ()
        {
            self.close_loading();
        }, 100);
    };

    this.write_bodytext = function (text)
    {
        this.loading_container.find(".loading-bodytext").html(text);
    };

    this.close_loading = function ()
    {
        this.loading_container.fadeOut(1000, function ()
        {
            $(this).remove();
        });
    };
}

function PageLoadingInterface()
{
    this.show = function ()
    {
        $(".page-loading").stop().fadeIn(500);
    };

    this.hide = function ()
    {
        $(".page-loading").stop().fadeOut(500);
    };
}
