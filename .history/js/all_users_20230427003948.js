var LoadMore = function (userOptions) {
this.options = {
    "pageSize": 6,
    "dataUrl": "./userdata.php",
    "container": "#c",
    "triggerText": "Load More",
    "triggerLoadingText": "...loading",
    "trigger": "#loadMoreTrigger",
    "callback": null
};
$.extend(this.options, userOptions);
this._index = 0;
this._itemsCurrentlyDisplayed = 0;
};
LoadMore.prototype.scrollToElement = function (selector, time, verticalOffset) {
time = typeof (time) != 'undefined' ? time : 1000;
verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
var element = $(selector);
var offset = element.offset();
var offsetTop = offset.top + verticalOffset;
$('html,body').animate({
    scrollTop: offsetTop
}, 800, function () {
    $('html,body').clearQueue();
});
};
LoadMore.prototype.loadData = function () {
var self = this;
self.triggerFeedback(true);
$.getJSON(self.options.dataUrl,
    function (data) {
    self.triggerFeedback(false);
    var totalResults = data.results.length;
    var items = [];
    var dataArr = data.results.splice(self._index, self.options.pageSize);
    if (dataArr.length > 0) {
        $.each(dataArr, function (key, val) {
            
        items.push("<div class=\"media text-muted pt-3 border-bottom border-gray\" style=\"width: 400px; height: 120px; margin: 0 0 0 30px;\">" + 
                        // "<a style = \"text-decoration:none;\" class = \"box\"  href=\"" + val.link + "\">" +
                            "<img data-src=\"holder.js/32x32?theme=thumb&amp;bg=007bff&amp;fg=007bff&amp;size=1\" alt=\"32x32\" class\"mr-2 rounded\" src=\"data:image;base64," + val.img + "\" data-holder-rendered=\"true\" style=\"width: 100px; height: 100px;\"/>" +
                            "<div class=\"media-body pb-3 mb-0 small lh-125 \" style=\"height: 100px;\">"+
                                "<div class=\"d-flex justify-content-between align-items-center w-100\" style=\height: 40px;\">" + 
                                "<strong class=\"text-gray-dark\">" + val.name + "</strong>" +                            
                                "</div>"+
                                "<span class=\"d-block\" style=\"height: 24px;\">@" + val.uid + "</span>" +
                            "</div>"+
                            "<button id=\"singlebutton\" name=\"singlebutton\" class=\"btn btn-sm btn-primary\" onclick=\"location.href='"+ val.link +"'\">View</button>"+
                        // "</a>" +
                    "</div>");
        });

        $(items.join("")).appendTo(self.options.container);
        var scrollToEl = $(".result").get(self._index);
        self._index += self.options.pageSize;
        if (scrollToEl) {
        // occurs only when not the initial
        // load of data
        self.scrollToElement(scrollToEl);
        }
        self._itemsCurrentlyDisplayed += dataArr.length;
        if (self._itemsCurrentlyDisplayed >= totalResults) {
        self._trigger.hide();
        }
        if (self.options.callback != null) {
        self.options.callback();
        }
    }
    });
};
LoadMore.prototype.triggerFeedback = function (isLoading) {
if (isLoading) {
    this._trigger.text(this.options.triggerLoadingText);
} else {
    this._trigger.text(this.options.triggerText);
}
};
LoadMore.prototype.init = function () {
var self = this;
$(document).ready(
    function () {
    self._trigger = $(self.options.trigger);
    self.loadData();
    self._trigger.on("click", function () {
        self.loadData();
    });
    });
};
// example usage
var loadMore = new LoadMore({
"dataUrl": "./userdata.php",
"pageSize": 4
});
loadMore.init();
