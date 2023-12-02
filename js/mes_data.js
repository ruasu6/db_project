var LoadMore = function (userOptions) {
    this.options = {
        "pageSize": 4,
        "dataUrl": "./mes_data.php",
        "container": "#mes",
        "triggerText": "Read More Messages",
        "triggerLoadingText": "...loading",
        "trigger": "#loadMoreTrigger1",
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
                
                items.push('<a class=\"dropdown-item d-flex align-items-center\" href=\"'+ val.link +'\">'+
                '<img class=" mes_img1" src="data:image;base64,' + val.img + '" style = "height: 40px;width: 40px; border-radius: 50%; object-fit: cover;">'+
                '<div>'+
                    '<span class="text-truncate" style="padding-left: 15px; font-weight: bold;">' + val.name +'</span>'+
                    '<span class="text-truncate" style="padding-left: 5px;">Followed you！'+'</span>'+
                    '<div class="small text-gray-500" style="padding-left: 15px;">' + val.time + '</div>'+
                '</div>'+
            '</a>');
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
        $(document).ready(function () {
            self._trigger = $(self.options.trigger);
            self.loadData();
            self._trigger.on("click", function (event) {
                event.preventDefault();
                self.loadData();
                // 阻止下拉菜单关闭
                event.stopPropagation();
                event.target.closest(".dropdown").classList.add("show");
            });
        });
    };    
    // example usage
    var loadMore = new LoadMore({
    "dataUrl": "./mes_data.php",
    "pageSize": 4
    });
    loadMore.init();
    