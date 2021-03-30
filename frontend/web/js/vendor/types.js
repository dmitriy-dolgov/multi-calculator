/**
 * Типы объектов и функционал для объектов.
 * Может использоваться при сворачивании/разворачивании, например.
 */

gl.objectTypeList = (function () {

    var typesList = {
        'ui.collapsible': [],
    };

    return {
        /**
         * Добавить в список
         *
         * @param type тип из `typesList`
         * @param funct функция для выполнения
         * @param key уникальный ключ функции. Если на задан, то генерируется и возвращается.
         * @returns {*}
         */
        'add': function (type, funct, key) {
            var list = this.verify(type, key);
            if (list !== false) {
                if (!key) {
                    // Придумаем свой
                    //TODO: cделать обязательно !!!!!!!!!!!!!!!
                    /*for (typesList[list]) {

                    }*/
                    key = gl.getObject('helpers').randomString(4);
                }
                /*typesList[type].push({
                    funct: funct,
                    key: key,
                });*/
            }

            return key;
        },

        'remove': function (type, key) {
            var result = false;

            try {
                if (this.verify(type, key)) {
                    delete typesList[type][key];
                    result = true;
                }
            } catch (e) {
                // думаю не обязательно здесь делать исключение
            }

            return result;
        },

        'evoke': function (type) {
            var list = this.verify(type);
            if (list !== false) {
                for (var i in typesList[list]) {
                    typesList[list][i]();
                }
            }
        },

        'verify': function (type, key) {
            if (!(type in typesList)) {
                //alert('oiruewoi');
                throw new Error('No type "' + type + '" in objectTypeList');
            }

            if (key) {
                var functionExists = false;
                for (var i in typesList[type]) {
                    if (typesList[type][i] === key) {
                        functionExists = true;
                        break;
                    }
                }

                if (functionExists) {
                    throw new Error('No function "' + type + '" in objectTypeList');
                }
            }

            return true;
        }
    }
})();

gl.objectTypeList.add('ui.collapsible', function () {
    alert('collapsible');
});
