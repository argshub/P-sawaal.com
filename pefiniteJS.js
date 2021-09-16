(function (){
    pefinite = function () {
        let allElements = [];
        for(let x= 0; x < arguments.length; x++) {
            let elements = document.querySelectorAll(arguments[x]);
            for (let y = 0; y < elements.length; y++) {
                allElements.push(elements[y]);
            }
        }
        function OperationOnElements(elements) {
            this.click = function (callback) {
                setEvent("click", callback);
                return this;
            };
            this.value = function (val) {
                for (let x = 0;x  < elements.length; x++) {
                    elements[x].value = val;
                }
                return this;
            };
            this.keyup = function (callback) {
                setEvent("keyup", callback);
                return this;
            };
            this.input = function (callback) {
                setEvent("input", callback);
                return this;
            };
            this.focusout = function (callback) {
                setEvent("focusout", callback);
                return this;
            };
            this.html = function (value) {
                for(let x = 0; x < elements.length; x++) {
                    elements[x].innerHTML = value;
                }
                return this;
            };
            this.childElement = function () {
                let allElements = [];
                for(let x = 0; x < elements.length; x++) {
                    for(let y = 0; y < arguments.length; y++) {
                        let searchElement = elements[x].querySelectorAll(arguments[y]);
                        for (let z = 0; z < searchElement.length; z++) {
                            allElements.push(searchElement[z]);
                        }
                    }
                }
                elements = allElements;
                return this;
            };
            this.child = function () {
                for(let x = 0; x < elements.length; x++) {
                    for (let y = 0; y < arguments.length; y++) {
                        elements[x].appendChild(arguments[y]._parse());
                    }
                }
            };
            this.css = function (key, value) {
                for(let x = 0; x < elements.length; x++) {
                    elements[x].style = "" + key + ":" + value + ";";
                }
                return this;
            };
            this.length = function (){
                return elements.length;
            };
            function setEvent(type, callback) {
                for(let x = 0; x < elements.length; x++) {
                    elements[x].addEventListener(type, callback);
                }
            }
        }
        return new OperationOnElements(allElements);
    };
    pefinite.allExecutableQueue = [];
    pefinite.ready = function (eachExecutable) {
        pefinite.allExecutableQueue.push(eachExecutable);
    };
    pefinite.ajax = function (url, postData = null) {
        return new function () {
            this.get = function (successCallback, errorCallback) {
                let http = new XMLHttpRequest();
                http.onload = function () {
                    if(typeof successCallback === 'function') successCallback(http.responseText);
                };
                http.onerror = function () {
                    if(typeof errorCallback === 'function') errorCallback("error");
                }
                http.open("GET", url);
                http.send();
            };
            this.syncGet = function (successCallback, errorCallback) {
                let http = new XMLHttpRequest();
                http.onload = function () {
                    if(typeof successCallback === 'function') successCallback(http.responseText);
                };
                http.onerror = function () {
                    if(typeof errorCallback === 'function') errorCallback("error");
                }
                http.open("GET", url, false);
                http.send();
            };
            this.post = function (successCallback, errorCallback) {
                let http = new XMLHttpRequest();
                http.onload = function () {
                    if(typeof successCallback === 'function') successCallback(http.responseText);
                };
                http.onerror = function () {
                    if(typeof errorCallback === 'function') errorCallback("error");
                }
                http.open("POST", url);
                http.send(postData);
            };
            this.syncPost = function (successCallback, errorCallback) {
                let http = new XMLHttpRequest();
                http.onload = function () {
                    if(typeof successCallback === 'function') successCallback(http.responseText);
                };
                http.onerror = function () {
                    if(typeof errorCallback === 'function') errorCallback("error");
                }
                http.open("POST", url, false);
                http.send(postData);
            };
        };
    };
    window.addEventListener("load", function () {
        for(let x= 0; x < pefinite.allExecutableQueue.length; x++) {
            pefinite.allExecutableQueue[x]();
        }
        pefinite.allExecutableQueue = [];
    });
    this.dom = function (tagName) {
        function domObject(element) {
            this.class = function (className) {
                element.setAttribute("class", className);
                return this;
            };
            this.id = function (idName) {
                element.setAttribute("id", idName);
                return this;
            };
            this.href = function (value) {
                element.href = value;
                return this;
            };
            this.css = function (key, value) {
                element.style = "" + key + ":" + value + ";";
                return this;
            };
            this.html = function (innerHTML) {
                element.innerHTML =  innerHTML;
                return this;
            };
            this.click = function (callback) {
                element.addEventListener("click", callback);
            };
            this.source = function (data) {
                element.src = data;
            };
            this.child = function () {
                for (let x = 0; x < arguments.length; x++) {
                    element.appendChild(arguments[x]._parse());
                }
                return this;
            };
            this._parse = function () {
                return element;
            }
        }
        return new domObject(document.createElement(tagName));
    };
})();