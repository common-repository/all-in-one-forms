(function (global) {
    var babelHelpers = global.babelHelpers = {};
    babelHelpers.typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
        return typeof obj;
    } : function (obj) {
        return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };


    babelHelpers.initializerDefineProperty =function (target, property, descriptor, context) {
        if (!descriptor) return;
        Object.defineProperty(target, property, {
            enumerable: descriptor.enumerable,
            configurable: descriptor.configurable,
            writable: descriptor.writable,
            value: descriptor.initializer ? descriptor.initializer.call(context) : void 0
        });
    }

    babelHelpers.applyDecoratedDescriptor=function (target, property, decorators, descriptor, context) {
        var desc = {};
        Object.keys(descriptor).forEach(function (key) {
            desc[key] = descriptor[key];
        });
        desc.enumerable = !!desc.enumerable;
        desc.configurable = !!desc.configurable;

        if ('value' in desc || desc.initializer) {
            desc.writable = true;
        }

        desc = decorators.slice().reverse().reduce(function (desc, decorator) {
            return decorator(target, property, desc) || desc;
        }, desc);

        if (context && desc.initializer !== void 0) {
            desc.value = desc.initializer ? desc.initializer.call(context) : void 0;
            desc.initializer = undefined;
        }

        if (desc.initializer === void 0) {
            Object.defineProperty(target, property, desc);
            desc = null;
        }

        return desc;
    };


    babelHelpers.defineProperty = function (obj, key, value) {
        if (key in obj) {
            Object.defineProperty(obj, key, {
                value: value,
                enumerable: true,
                configurable: true,
                writable: true
            });
        } else {
            obj[key] = value;
        }

        return obj;
    };

    babelHelpers.extends = Object.assign || function (target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];

            for (var key in source) {
                if (Object.prototype.hasOwnProperty.call(source, key)) {
                    target[key] = source[key];
                }
            }
        }

        return target;
    };
})(typeof global === "undefined" ? self : global);



var rndefineDictionary={};
function rndefine(libraryName,dependencies,callback)
{
    var dependencyInstances=[];
    var dictionaryItem={};

    if(arguments.length==2)
    {
        callback = arguments[1];
        dependencies=[];
    }


    for(var i=0;i<dependencies.length;i++)
    {
        var currentDependency=dependencies[i];


        if(currentDependency=='flatpickr')
        {
            dependencyInstances.push(flatpickr);
            continue;
        }

        if(currentDependency=='jquery')
        {
            dependencyInstances.push(jQuery);
            continue;
        }
        if(currentDependency=='lit/directives/ref.js'||currentDependency=='lit-html/src/directive-helpers'||currentDependency=='lit'||currentDependency=='lit/decorators'||currentDependency=='lit/decorators.js'||currentDependency=='lit/directives/repeat.js'||currentDependency=='lit-html/directives/live.js'||currentDependency=='lit-html/directives/live')
        {
            dependencyInstances.push(lit);
            continue;
        }


        if(currentDependency=='exports')
        {
            dependencyInstances.push(dictionaryItem);
            continue;
        }

        var parts=currentDependency.split('/');

        if(parts.length!=2)
        {
            throw Error('Invalid dependency ' + currentDependency);
        }

        if(rndefineDictionary[parts[0]]==null) {
            throw Error('Library not found ' + parts[0] + (parts[1] != null ? '--' + parts[1] : ''));
        }

        let dependency=null;
        if(rndefineDictionary[parts[0]][parts[1]]!=null)
            dependency=rndefineDictionary[parts[0]][parts[1]];
        else if(rndefineDictionary[parts[0]][parts[1].replace(/\./g,'')]!=null)
            dependency=rndefineDictionary[parts[0]][parts[1].replace(/\./g,'')];
        else {
            dependency= rndefineDictionary[parts[0]];
        }

        if(typeof dependency==='object'&& dependency!==null)
        {
            dependencyInstances.push(dependency);
        }else
            dependencyInstances.push(rndefineDictionary[parts[0]]);



    }

    let oldExport=window.exports;
    window.exports=dictionaryItem;
    callback.apply(this,dependencyInstances);
    window.exports=oldExport;
    rndefineDictionary[libraryName]=dictionaryItem;

}


function RNTranslate(key) {
    if(typeof RNTranslatorDictionary=="undefined"||typeof RNTranslatorDictionary[key]=='undefined')
        return key;
    return RNTranslatorDictionary[key];
}

function _rnt(key) {
    return RNTranslate(key);
}


