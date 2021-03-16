//TODO: see https://2ality.com/2012/01/objects-as-maps.html

gl.getObject('events').base = (function () {
    var events = {
        ui: {
            native: {
                'restore_to_initial': []
            },
            registered: {}
        }
    };

    /*function findInAllEvents(scopeKey, scopeType, eventName) {
        if (Array.isArray(events[scopeKey])) {
            if (Array.isArray(events[scopeKey][scopeType])) {
                for (var id in events[scopeKey][scopeType]) {

                }
            } else {
                throw new Error(`No such event scope type: ${scopeType}. Scope key is ${scopeKey}`);
            }
        } else {
            throw new Error(`No such event scope key: "${scopeType}"`);
        }
    }*/

    function ifEventRepositoryExists(eventScope, eventName, repoType) {
        var key = `${eventScope}.${repoType}.${eventName}`;
        if (gl.object.has(events, key)) {
            return true;
        }

        gl.error('Native event does not exist: ' + key);
        return false;
    }


    return {
        addNativeEventHandler: function (eventScope, eventName, eventHandler) {

            gl.assert(gl.object.isFunction(eventHandler), '`eventHandler` is not a function!');
            if (!ifEventRepositoryExists(eventScope, eventName, 'native')) {
                return false;
            }

            /*var key = `${eventScope}.native.${eventName}`;
            if (!gl.object.has(events, key)) {
                gl.error('Native event does not exist: ' + key);
                return false;
            }*/

            events[eventScope]['native'][eventName].push(eventHandler);

            return true;
        },
        addCustomEventHandler: function (eventScope, eventName, eventHandler) {
            alert('addCustomEventHandler(): not implemented yet');
            return false;
        },
        evokeNativeEventHandlers: function (eventScope, eventName) {
            if (!ifEventRepositoryExists(eventScope, eventName, 'native')) {
                return false;
            }

            for (var key in events[eventScope]['native'][eventName]) {
                gl.info.debugMsg('Native event is to raise: ' + key);
                events[eventScope]['native'][eventName][key]();
            }
        }
    };
})();

//gl.getObject

$('.close-all-global-btn').click(function () {
    //gl.events.
});

gl.events.base.addNativeEventHandler('ui', 'restore_to_initial', function () {
    alert('restore_to_initial IS HERE');
});
gl.events.base.evokeNativeEventHandlers('ui', 'restore_to_initial');
