<?header("Access-Control-Allow-Origin: *")?>
<pf-package>
    <script data-fn="load" data-args="relPath, nextLoad">
        console.log("--->relPath", relPath);
        loadTemplates([{
            file: relPath + "Components.php"
        }/*, {
            file: relPath + "CMEditor.html"
        }*/], nextLoad);

        $("head").append('<link type="text/css" rel="stylesheet" href="' + relPath + 'css/bgpf.css">');

        window.loadPage = function (pageWrap, pageTmpl, args, childrenIn) {
            if (pageWrap.currentPage) {
                if (pageWrap.currentPage.unload)
                    pageWrap.currentPage.unload();
                pageWrap.currentPage.removeSelf();
            }
            // pageWrap.$.empty();
            pageWrap.currentPage = pageWrap.create(pageTmpl);
            //pageWrap.currentPage.putInto (pageWrap);
            pageWrap.currentPage.load(args);
            if (childrenIn) {
                //pageWrap.currentPage.$.find ("> *").append (pageWrap.$);
                pageWrap.$.empty();
                pageWrap.$.append(pageWrap.currentPage.$.find("> *"));
            } else
                pageWrap.currentPage.putInto(pageWrap);

            return pageWrap.currentPage;
        }
        window.BGTmpls = this;
        // window.__body__ = compile($('body'));
        window.__queryString__ = function (name, url) {
            var q = /[^\?]*\?(.*)/.exec(url);

            if (q === null)
                return null;

            q = RegExp("(^|&)" + name + "=([^&]*)")
                .exec(q[1]);

            return q ? q[2] : null;
        }
        window.__queryStringArray__ = function (url) {
            url = url.replace(/^\?/, '');
            var arr = url.split('&');
            var v = {};
            for (var x in arr) {
                var _v_ = /([^=]*)=(.*)/.exec(arr[x]);
                if (_v_)
                    v[_v_[1]] = _v_[2];
                else
                    v[arr[x]] = "";
            }
            return v;
        }
    </script>
    <!-- <script data-fn="initial">
        window.__body__ = compile($('body'));
    </script> -->
    <pf-package name="connections">
        <pf-connections-tmpl data-ft="BGTmpls::connections" type="hidden" data-fra="pf-connections" data-tagName="input">
            <script data-fn="emit" data-args="sig, args">
                this.$.trigger(sig, [args]);
            </script>

            <script data-fn="on" data-args="event, cb">
                this.$.on(event, (e, args)=>{
                    cb(args);
                });
            </script>

            <script data-fn="constructor">
                window[this.__NAME__()] = this;
            </script>
        </pf-connections-tmpl>
    </pf-package>
</pf-package>
