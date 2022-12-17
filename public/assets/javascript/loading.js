var SiteLoading;
var Configuration;

$(function ()
{
    Configuration = new ConfigLoadingInterface();
    Configuration.setConfig();
    Configuration.getConfig();
});

function ConfigLoadingInterface ()
{
    var self = this;
    this.settings = null;
    this.configLoaded = false

    this.getConfig = function ()
    {
        setInterval(() => {
            this.setConfig();
        }, 30000);
    }

    this.setConfig = function ()
    {
        fetch('/api/config')
        .then((response) => response.json())
        .then((data) => {

            if(this.configLoaded == false) {
                SiteLoading = new SiteLoadingInterface();
                SiteLoading.init();
                SiteLoading.load_file(0);
            }

            self.settings = data.data
            $(".online-users").text(data.data.online_users);
            this.configLoaded = true
        });
    }
}

function SiteLoadingInterface()
{
    this.files = [
        "web.pages",
        "web.core"
    ];
    this.loaded_files = 0;
    this.total_files = 0;
    this.cache_id = null;

    this.init = function ()
    {
        console.log(
            "Cosmic - All rights reserved\n\n" +
            "Everything you do here falls under your own responsibility. Never give your code if someone asks for it. If you paste a code here, you will never get free credits or other items.\n\n" +
            "All credits to Liam & Laynester for the layout!\n\n" +
            "- Cosmic Dev");
        this.total_files = this.files.length;
    }
  
    this.load_file = function (file_id)
    {
        var self = this;
        var file_name = this.files[file_id];
        var script = document.createElement("script");
        $("body").append(script);
        script.onload = function ()
        {
            self.loaded_files++;

            if (file_id + 1 < self.total_files)
            {
                file_id++;
                self.load_file(file_id);
            }
        };
        script.onerror = function ()
        {
            console.log("Oops, file \"" + file_name + "\" not found.");
            self.write_bodytext("Oops, something went wrong. <a href=\"javascript:window.location.reload();\">Reload the page</a>.");
        };
        script.src = "/assets/javascript/" + file_name + ".js?=5";
    };
}