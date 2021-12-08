<?header("Access-Control-Allow-Origin: *")?>
<pf-RPC>
    <script data-fn="load" data-args="relPath, nextLoad">
        window.rpc = this;
        this.initRPC();
        nextLoad();
    </script>

    <script data-fn="initRPC">
        this.__rpc__ = new RPC();

        this.__rpc__.onConnected = (function () {
            if (this.onConnected)
                this.onConnected();
        }).bind(this);

        this.__rpc__.onCalling = (function (obj, method, args, mID) {
            if (this.onCalling)
                this.onCalling(obj, method, args, mID);
        }).bind(this);

        this.__rpc__.onReturn = (function (mID) {
            if (this.onReturn)
                this.onReturn(mID);
        }).bind(this);

        this.__rpc__.onRemoteSignal = (function (obj, sig, args) {
            if (this.onRemoteSignal)
                this.onRemoteSignal(obj, sig, args);
        }).bind(this);

        this.call = this.__rpc__.call.bind(this.__rpc__);
        this.asyncCall = this.__rpc__.asyncCall.bind(this.__rpc__);
    </script>
    <script data-fn="connect" data-args="host, port">
        this.__rpc__.host = host;
        this.__rpc__.port = port;
        this.__rpc__.connectToHost();
    </script>
</pf-RPC>