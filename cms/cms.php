<?header("Access-Control-Allow-Origin: *")?>
<pf-package>
    <script data-fn="load" data-args="relPath, nextLoad">
        window.CMS = this;
        window.__body__ = compile($('body'));
        loadModules([
            relPath + "../BGTmpls/BGTmpls.php",
            relPath + "../BGTmpls/RPC.php"
        ], false).onEnd(() => {
            compile($('<pf-connections id="CMSCONN"></pf-connections>'));
            rpc.onRemoteSignal = (obj, sig, args) => {
                if (obj === "CMS") {
                    console.log("CMS.....")
                    CMSCONN.emit(sig, args);
                }
            };
            nextLoad();
        });
        $("head").append('<link type="text/css" rel="stylesheet" href="' + relPath + 'cms.css">');
    </script>

    <script data-fn="collections">
        return rpc.asyncCall("CMS", "collections")
            .then((ret) => {
                if (Array.isArray(ret))
                    return Promise.resolve(ret);
                else if (ret !== undefined)
                    return Promise.resolve([ret]);
                else
                    return Promise.resolve([]);
            });
    </script>

    <script data-fn="collection" data-args="cID">
        return rpc.asyncCall("CMS", "collection", cID);
    </script>

    <script data-fn="categories" data-args="cID">
        return rpc.asyncCall("CMS", "categories", cID, false)
            .then((ret) => {
                if (Array.isArray(ret))
                    return Promise.resolve(ret);
                else if (ret !== undefined)
                    return Promise.resolve([ret]);
                else
                    return Promise.resolve([]);
            });
    </script>

    <script data-fn="category" data-args="cateID">
        return rpc.asyncCall("CMS", "category", cateID);
    </script>

    <script data-fn="resources" data-args="cID, cateID">
        return rpc.asyncCall("CMS", "resources", cID, cateID, false)
            .then((ret) => {
                if (Array.isArray(ret))
                    return Promise.resolve(ret);
                else if (ret !== undefined)
                    return Promise.resolve([ret]);
                else
                    return Promise.resolve([]);
            });
    </script>

    <script data-fn="resource" data-args="rID">
        return rpc.asyncCall("CMS", "resource", rID);
    </script>

    <script data-fn="resourceContent" data-args="rID">
        return rpc.asyncCall("CMS", "resourceContent", rID)
            .then((ret) => {
                return Promise.resolve(ret);
            });
    </script>

    <script data-fn="loadComponent" data-args="path">
        return this.resourceContent(path)
            .then((ret) => {
                var package = compile($(ret.content));
                return package.load();
            });
    </script>

    <script data-fn="renderStyle" data-args="path">
        console.log("in renderStyle", path);
        return this.resourceContent(path)
            .then((ret) => {
                return less.render(ret.content).then((output) => {
                    return Promise.resolve({id: ret.id, css: output.css});
                })
            });
    </script>

    <script data-fn="addStyle" data-args="path, target">
        console.log("in addStyle", path);
        return this.renderStyle(path)
            .then((ret) => {
                console.log("------>", ret);
                var style = '<style>' + ret.css + '</style>';
                if (target !== undefined)
                    target.$.append(style);
                else
                    $('head').append(style);

                return Promise.resolve(ret.id);
            });
        /*return this.resourceContent(path)
            .then((ret) => {
                return less.render(ret.content).then((output) => {
                    var style = '<style>' + output.css + '</style>';
                    if (target !== undefined)
                        target.$.append(style);
                    else
                        $("head").append(style);

                    return Promise.resolve(ret.id);
                });
            });*/
    </script>

    <script data-fn="replaceBody" data-args="tmpl">
        __body__ = this.create(tmpl);
        $('body').replaceWith(__body__.$);
    </script>

    <script data-fn="connect" data-args="ip, port, path">
        rpc.connect(ip, port);
        rpc.onConnected = () => {
            console.log("connected");
            rpc.asyncCall("CMS", "join")
            .then((ret) => {
                console.log("---joined---");
                return this.loadComponent(path);
            })
            .then(() => {
                console.log("CMS end");
            });
        }
    </script>

</pf-package>
