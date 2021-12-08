<?header("Access-Control-Allow-Origin: *")?>
<pf-package data-fn="Components">
    <script data-fn="load" data-args="relPath,nextLoad">
        nextLoad();
    </script>

    <pf-package data-fn="list">
        <pf-list-item-tmpl data-ft="BGTmpls::listItem" data-tagName="pf-list-item">
            <script data-fn="load" data-args="itemData">
                this.text(itemData);
            </script>
        </pf-list-item-tmpl>
        <pf-list-tmpl data-ft="BGTmpls::list" data-item-tmpl="BGTmpls::listItem" data-fns="listRoot" data-fra="pf-list" data-tagName="pf-list" data-overloaded-prefix="list">
            <pf-viewport id="viewport"></pf-viewport>
            <script data-fn="append" data-args="itemData">
                this.putInto(itemData, true);
            </script>
            <script data-fn="prepend" data-args="itemData">
                this.putInto(itemData, false);
            </script>
            <script data-fn="putInto" data-args="itemData, append">
                var li = this.create(this.$.attr("data-item-tmpl"));
                li.load(itemData);
                if (append)
                    li.putInto(this.viewport);
                else
                    li.prepend(this.viewport);

                if (!this.items)
                    this.items = [];

                this.items.push(li);
                this.itemCount = this.items.length;

                this.$.attr("data-item-count", this.itemCount);
            </script>
            <script data-fn="removeItem" data-args="index">
                this.items[index].removeSelf();
                this.items.splice(index, 1);
                this.itemCount = this.items.length;

                this.$.attr("data-item-count", this.count);
            </script>
            <script data-fn="clear">
                this.items = [];
                this.itemCount = 0;
                this.viewport.empty();
            </script>
            <script data-fn="load" data-args="listData">
                this.clear();
                this.$.attr("data-item-count", 0);

                for (var x in listData) {
                    this.append(listData[x]);
                }
            </script>
            <script data-fn="constructor">
                this.itemID = 0;
                this.$.attr("data-item-count", 0);
            </script>
        </pf-list-tmpl>
    </pf-package>

    <pf-package data-fn="article">
        <article data-ft="BGTmpls::articleTmpl" data-fra="pf-article" data-tagName="article">
            <header data-fn="header"></header>
            <pf-content id="content"></pf-content>

            <script data-fn="setHeader" data-args="header">
                this.header.text(header);
            </script>
            <script data-fn="setContent" data-args="content">
                this.content.html(content);
            </script>
        </article>
    </pf-package>


    <pf-package name="swipe">
        <pf-tab-tmpl data-ft="BGTmpls::tabTmpl" data-fra="pf-tab" data-tagName="pf-tab">
            <span></span>
            <script data-fn="setText" data-args="text">
                this.span.text(text);
            </script>
            <script data-fn="constructor">
                this.span.text(this.__text__);
                this.span.$.click((function() {
                    this.toNS("swipeRoot").swipe(Number(this.$.attr("data-tab-index")));
                }).bind(this));
            </script>
        </pf-tab-tmpl>
        <pf-swipe-tmpl data-ft="BGTmpls::tabviewTmpl" data-fra="pf-swipe" data-fns="swipeRoot" data-tagName="pf-swipe" data-overloaded-prefix="swipe">
            <nav id="tabbar"></nav>
            <pf-viewport id="viewport"></pf-viewport>


            <script data-fn="activateWidget" data-args="obj, index">
                obj.$.children().removeClass("activated");
                obj.$.find("> [data-tab-index=" + index + "]").addClass("activated");
            </script>

            <script data-fn="swipe" data-args="index">
                this.activateWidget(this.tabbar, index);
                this.activateWidget(this.viewport, index);

                if (this.onActivated)
                    this.onActivated(index);
            </script>
            <script data-fn="constructor">
                this.tabbar.$.find("> pf-tab").each(function(i, e) {
                    $(e).attr("data-tab-index", i);
                });
                this.viewport.$.children().each(function(i, e) {
                    $(e).attr("data-tab-index", i);
                });
                this.swipe(0);
            </script>
        </pf-swipe-tmpl>
    </pf-package>

    <pf-package name="controlers">
        <pf-text-input-tmpl data-ft="BGTmpls::textInputTmpl" type="text" data-fra="pf-text-input" data-tagName="input">
            <script data-fn="value">
                return this.$.val();
            </script>
            <script data-fn="setValue" data-args="value">
                this.$.val(value);
            </script>
            <script data-fn="constructor">
                this.$.keypress((function(event) {
                    if (event.which === 13 && this.onInputFinished) {
                        this.onInputFinished(this.$.val());
                        this.$.val("");
                    }
                }).bind(this));
            </script>
        </pf-text-input-tmpl>

        <pf-textarea-tmpl data-ft="BGTmpls::textareaTmpl" data-fra="pf-textarea" data-tagName="textarea">
            <script data-fn="value">
                return this.$.val();
            </script>
            <script data-fn="setValue" data-args="value">
                this.$.val(value);
            </script>
            <script data-fn="constructor">
                this.$.val("");
                this.$.keydown((function(event) {
                    if (event.which === 13 && event.ctrlKey && this.onInputFinished) {
                        this.onInputFinished(this.$.val());
                        this.$.val("");
                    }
                }).bind(this));
            </script>
        </pf-textarea-tmpl>

        <button data-ft="BGTmpls::buttonTmpl" data-fra="pf-button" data-overloaded-prefix="button" data-tagName="button">
            <script data-fn="constructor">
                if (this.__text__) {
                    var t = this.__text__.trim();
                    if (t.length > 0)
                        this.text(t);
                }
            </script>
        </button>
    </pf-package>

    <pf-package name="dialog">
        <button data-ft="BGTmpls::dlgBtnTmpl" data-fr="BGTmpls::buttonTmpl" data-fra="pf-dlg-button" data-tagName="button">
            <script data-fn="constructor">
                this.button_constructor();
                this.toNS("dlgRoot").addButton(this);
            </script>
        </button>

        <pf-extend-tmpl data-ft="BGTmpls::dialogDefine" data-overloaded-prefix="dialog">
            <script data-fn="result" data-args="v">
                return v;
            </script>
            <script data-fn="open">
                this.$.addClass("opened");
                return new Promise((function(resolve) {
                    this.$.on("close", (function(e, v) {
                        this.$.removeClass("opened");
                        resolve(this.result(v));
                    }).bind(this));
                }).bind(this));
            </script>
            <script data-fn="close" data-args="v">
                if (v === undefined)
                    v = -1;
                this.$.trigger("close", [v]);
            </script>
            <script data-fn="addButton" data-args="btn">
                this.dlgButtons.push(btn);
            </script>
            <script data-fn="preconstructor">
                this.dlgButtons = [];
            </script>
            <script data-fn="constructor">
                for (var x in this.dlgButtons) {
                    this.dlgButtons[x].$.click((function(btn) {
                        var v = btn.$.attr("value");
                        v = isNaN(v) ? -1 : Number(v);
                        this.close(v);
                    }).bind(this, this.dlgButtons[x]));
                }
                this.$.click(() => {
                    if (this.$.attr("data-modal") == "false")
                        this.close();
                });
            </script>
        </pf-extend-tmpl>

        <pf-container class="overlayer dialog" data-ft="BGTmpls::dialogTmpl" data-fns="dlgRoot"
            data-modal="true" data-fra="pf-dialog" data-tagName="pf-container">
            <pf-extend data-fr="BGTmpls::dialogDefine"></pf-extend>
            <pf-card data-fw="true">
                <header id="header" class="x-narrow-padding"></header>
                <pf-content id="dlgContent"></pf-content>
                <footer id="dlgBtnsBox" class="normal-padding"></footer>
            </pf-card>
            <script data-fn="constructor">
                this.$.find("> pf-card").click(function(event) {
                    event.stopPropagation();
                })
                if (this.__text__) {
                    var t = this.__text__ ? this.__text__.trim() : "";
                    if (t.length > 0)
                        this.header.text(t);
                }
                this.dialog_constructor();
            </script>
        </pf-container>

        <pf-container class="message" data-fr="BGTmpls::dialogTmpl" data-ft="BGTmpls::messageBoxTmpl" data-fra="pf-message">
            <pf-text id="message" data-putInto="dlgContent"></pf-text>
            <pf-dlg-button value="0" data-putInto="dlgBtnsBox">Close</pf-dlg-button>
            <script data-fn="open" data-args="title, message">
                this.header.text(title);
                this.message.text(message);
                return this.dialog_open();
            </script>
        </pf-container>

        <pf-container class="question" data-fr="BGTmpls::dialogTmpl" data-ft="BGTmpls::questionTmpl" data-fra="pf-question">
            <pf-text id="message" data-putInto="dlgContent"></pf-text>
            <pf-dlg-button value="1" data-putInto="dlgBtnsBox">Yes</pf-dlg-button>
            <pf-dlg-button value="0" data-putInto="dlgBtnsBox">No</pf-dlg-button>
            <pf-dlg-button value="-1" data-putInto="dlgBtnsBox">Cancel</pf-dlg-button>
            <script data-fn="open" data-args="title, message">
                this.header.text(title);
                this.message.text(message);
                return this.dialog_open();
            </script>
        </pf-container>

        <pf-container class="prompt" data-fr="BGTmpls::dialogTmpl" data-ft="BGTmpls::promptTmpl" data-fra="pf-prompt">
            <pf-text-input id="prompt" data-putInto="dlgContent"></pf-text-input>
            <pf-dlg-button value="1" data-putInto="dlgBtnsBox">Ok</pf-dlg-button>
            <pf-dlg-button value="-1" data-putInto="dlgBtnsBox">Cancel</pf-dlg-button>
            <script data-fn="result" data-args="v">
                if (v === 1)
                    return this.prompt.value();
                else
                    return v;
            </script>
            <script data-fn="open" data-args="title, placeholder, value">
                this.header.text(title);
                this.prompt.$.attr("placeholder", placeholder);
                this.prompt.setValue(value);
                return this.dialog_open();
            </script>
        </pf-container>

        <pf-container class="prompt textarea" data-fr="BGTmpls::dialogTmpl" data-ft="BGTmpls::promptTextareaTmpl" data-fra="pf-prompt-text-area">
            <pf-textarea id="prompt" data-putInto="dlgContent"></pf-textarea>
            <pf-dlg-button value="1" data-putInto="dlgBtnsBox">Ok</pf-dlg-button>
            <pf-dlg-button value="-1" data-putInto="dlgBtnsBox">Cancel</pf-dlg-button>
            <script data-fn="result" data-args="v">
                if (v === 1)
                    return this.prompt.value();
                else
                    return v;
            </script>
            <script data-fn="open" data-args="title, placeholder, value">
                this.header.text(title);
                this.prompt.$.attr("placeholder", placeholder);
                this.prompt.setValue(value);
                return this.dialog_open();
            </script>
        </pf-container>

        <script data-fn="message" data-args="title, message">
            var dlg = this.create("BGTmpls::messageBoxTmpl");
            $("body").append(dlg.$);
            return dlg.open(title, message).then((function(ret) {
                this.removeSelf();
                return Promise.resolve(ret);
            }).bind(dlg));
        </script>

        <script data-fn="prompt" data-args="title, placeholder, value">
            var dlg = this.create("BGTmpls::promptTmpl");
            $("body").append(dlg.$);
            return dlg.open(title, placeholder, value).then((function(ret) {
                this.removeSelf();
                return Promise.resolve(ret);
            }).bind(dlg));
        </script>

        <script data-fn="promptTextarea" data-args="title, placeholder, value">
            var dlg = this.create("BGTmpls::promptTextareaTmpl");
            $("body").append(dlg.$);
            return dlg.open(title, placeholder, value).then((function(ret) {
                this.removeSelf();
                return Promise.resolve(ret);
            }).bind(dlg));
        </script>

        <script data-fn="question" data-args="title, message">
            var dlg = this.create("BGTmpls::questionTmpl");
            $("body").append(dlg.$);
            return dlg.open(title, message).then((function(ret) {
                this.removeSelf();
                return Promise.resolve(ret);
            }).bind(dlg));
        </script>

        <script data-fn="open" data-args="tmpl">
            var dlg = this.create(tmpl);
            var args = Array.prototype.slice.call(arguments, 1);
            $("body").append(dlg.$);
            return dlg.open.apply(dlg, args).then((function(ret) {
                this.removeSelf();
                return Promise.resolve(ret);
            }).bind(dlg));
        </script>

        <script data-fn="constructor">
            window.PFDialog = this;
        </script>
    </pf-package>
</pf-package>
