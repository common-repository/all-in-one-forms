rndefine("#RNMainDateRangeField",["#RNMainCore/EventManager","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/FieldWithPrice.Model","#RNMainFormBuilderCore/FieldWithPrice.Options","#RNMainFormBuilderCore/CalculatorBase","#RNMainCore/Sanitizer","lit","flatpickr","lit/decorators","#RNMainFormBuilderCore/FieldBase","#RNMainFormBuilderCore/IconDirective","#RNMainFormBuilderCore/FieldWithPrice","lit-html/directives/live.js","#RNMainCore/StoreBase","#RNMainFormBuilderCore/FormBuilder.Options"],(function(e,t,r,n,a,i,o,s,u,l,d,h,c,m,f){"use strict";function g(e){return e&&"object"==typeof e&&"default"in e?e:{default:e}}var p=g(s);class w extends a.CalculatorBase{constructor(){super(),this.CustomSalePrice=null,this.CustomRegularPrice=null}SetRegularPrice(e){return this.CustomRegularPrice=e,this}SetSalePrice(e){return this.CustomSalePrice=e,this}ExecuteCalculation(e){if(null==e&&(e=this.Field.GetValue()),null==e)e=0;else{let t=e.EndDate-e.StartDate;e=0==t?1:t/86400}let t=null!=this.CustomRegularPrice?this.CustomRegularPrice:this.Field.Options.Price;return t=i.Sanitizer.SanitizeNumber(t)*e,e>0?{Quantity:this.GetQuantity(),RegularPrice:t}:{RegularPrice:"",Quantity:0}}ParseNumber(e){let t=parseFloat(e);return isNaN(t)?0:t}}class b{static UnixToDate(e,t=0){if(null!=e){let r=new Date(1e3*(e+t));return r=new Date(r.setMinutes(r.getMinutes()+r.getTimezoneOffset())),r}return null}static DateToUnix(e,t=0){return(e.getTime()-t)/1e3+-1*e.getTimezoneOffset()*60}}function D(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=Object.prototype.toString.call(e);return e instanceof Date||"object"==typeof e&&"[object Date]"===t?new Date(e.getTime()):"number"==typeof e||"[object Number]"===t?new Date(e):("string"!=typeof e&&"[object String]"!==t||"undefined"==typeof console||(console.warn("Starting with v2.0.0-beta.1 date-fns doesn't accept strings as arguments. Please use `parseISO` to parse strings. See: https://git.io/fjule"),console.warn((new Error).stack)),new Date(NaN))}var y={lessThanXSeconds:{one:"less than a second",other:"less than {{count}} seconds"},xSeconds:{one:"1 second",other:"{{count}} seconds"},halfAMinute:"half a minute",lessThanXMinutes:{one:"less than a minute",other:"less than {{count}} minutes"},xMinutes:{one:"1 minute",other:"{{count}} minutes"},aboutXHours:{one:"about 1 hour",other:"about {{count}} hours"},xHours:{one:"1 hour",other:"{{count}} hours"},xDays:{one:"1 day",other:"{{count}} days"},aboutXMonths:{one:"about 1 month",other:"about {{count}} months"},xMonths:{one:"1 month",other:"{{count}} months"},aboutXYears:{one:"about 1 year",other:"about {{count}} years"},xYears:{one:"1 year",other:"{{count}} years"},overXYears:{one:"over 1 year",other:"over {{count}} years"},almostXYears:{one:"almost 1 year",other:"almost {{count}} years"}};function v(e){return function(t){var r=t||{},n=r.width?String(r.width):e.defaultWidth;return e.formats[n]||e.formats[e.defaultWidth]}}var T={date:v({formats:{full:"EEEE, MMMM do, y",long:"MMMM do, y",medium:"MMM d, y",short:"MM/dd/yyyy"},defaultWidth:"full"}),time:v({formats:{full:"h:mm:ss a zzzz",long:"h:mm:ss a z",medium:"h:mm:ss a",short:"h:mm a"},defaultWidth:"full"}),dateTime:v({formats:{full:"{{date}} 'at' {{time}}",long:"{{date}} 'at' {{time}}",medium:"{{date}}, {{time}}",short:"{{date}}, {{time}}"},defaultWidth:"full"})},S={lastWeek:"'last' eeee 'at' p",yesterday:"'yesterday at' p",today:"'today at' p",tomorrow:"'tomorrow at' p",nextWeek:"eeee 'at' p",other:"P"};function C(e){return function(t,r){var n,a=r||{};if("formatting"===(a.context?String(a.context):"standalone")&&e.formattingValues){var i=e.defaultFormattingWidth||e.defaultWidth,o=a.width?String(a.width):i;n=e.formattingValues[o]||e.formattingValues[i]}else{var s=e.defaultWidth,u=a.width?String(a.width):e.defaultWidth;n=e.values[u]||e.values[s]}return n[e.argumentCallback?e.argumentCallback(t):t]}}function M(e){return function(t,r){var n=String(t),a=r||{},i=a.width,o=i&&e.matchPatterns[i]||e.matchPatterns[e.defaultMatchWidth],s=n.match(o);if(!s)return null;var u,l=s[0],d=i&&e.parsePatterns[i]||e.parsePatterns[e.defaultParseWidth];return u="[object Array]"===Object.prototype.toString.call(d)?d.findIndex((function(e){return e.test(n)})):function(e,t){for(var r in e)if(e.hasOwnProperty(r)&&t(e[r]))return r}(d,(function(e){return e.test(n)})),u=e.valueCallback?e.valueCallback(u):u,{value:u=a.valueCallback?a.valueCallback(u):u,rest:n.slice(l.length)}}}var P,O={formatDistance:function(e,t,r){var n;return r=r||{},n="string"==typeof y[e]?y[e]:1===t?y[e].one:y[e].other.replace("{{count}}",t),r.addSuffix?r.comparison>0?"in "+n:n+" ago":n},formatLong:T,formatRelative:function(e,t,r,n){return S[e]},localize:{ordinalNumber:function(e,t){var r=Number(e),n=r%100;if(n>20||n<10)switch(n%10){case 1:return r+"st";case 2:return r+"nd";case 3:return r+"rd"}return r+"th"},era:C({values:{narrow:["B","A"],abbreviated:["BC","AD"],wide:["Before Christ","Anno Domini"]},defaultWidth:"wide"}),quarter:C({values:{narrow:["1","2","3","4"],abbreviated:["Q1","Q2","Q3","Q4"],wide:["1st quarter","2nd quarter","3rd quarter","4th quarter"]},defaultWidth:"wide",argumentCallback:function(e){return Number(e)-1}}),month:C({values:{narrow:["J","F","M","A","M","J","J","A","S","O","N","D"],abbreviated:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],wide:["January","February","March","April","May","June","July","August","September","October","November","December"]},defaultWidth:"wide"}),day:C({values:{narrow:["S","M","T","W","T","F","S"],short:["Su","Mo","Tu","We","Th","Fr","Sa"],abbreviated:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],wide:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},defaultWidth:"wide"}),dayPeriod:C({values:{narrow:{am:"a",pm:"p",midnight:"mi",noon:"n",morning:"morning",afternoon:"afternoon",evening:"evening",night:"night"},abbreviated:{am:"AM",pm:"PM",midnight:"midnight",noon:"noon",morning:"morning",afternoon:"afternoon",evening:"evening",night:"night"},wide:{am:"a.m.",pm:"p.m.",midnight:"midnight",noon:"noon",morning:"morning",afternoon:"afternoon",evening:"evening",night:"night"}},defaultWidth:"wide",formattingValues:{narrow:{am:"a",pm:"p",midnight:"mi",noon:"n",morning:"in the morning",afternoon:"in the afternoon",evening:"in the evening",night:"at night"},abbreviated:{am:"AM",pm:"PM",midnight:"midnight",noon:"noon",morning:"in the morning",afternoon:"in the afternoon",evening:"in the evening",night:"at night"},wide:{am:"a.m.",pm:"p.m.",midnight:"midnight",noon:"noon",morning:"in the morning",afternoon:"in the afternoon",evening:"in the evening",night:"at night"}},defaultFormattingWidth:"wide"})},match:{ordinalNumber:(P={matchPattern:/^(\d+)(th|st|nd|rd)?/i,parsePattern:/\d+/i,valueCallback:function(e){return parseInt(e,10)}},function(e,t){var r=String(e),n=t||{},a=r.match(P.matchPattern);if(!a)return null;var i=a[0],o=r.match(P.parsePattern);if(!o)return null;var s=P.valueCallback?P.valueCallback(o[0]):o[0];return{value:s=n.valueCallback?n.valueCallback(s):s,rest:r.slice(i.length)}}),era:M({matchPatterns:{narrow:/^(b|a)/i,abbreviated:/^(b\.?\s?c\.?|b\.?\s?c\.?\s?e\.?|a\.?\s?d\.?|c\.?\s?e\.?)/i,wide:/^(before christ|before common era|anno domini|common era)/i},defaultMatchWidth:"wide",parsePatterns:{any:[/^b/i,/^(a|c)/i]},defaultParseWidth:"any"}),quarter:M({matchPatterns:{narrow:/^[1234]/i,abbreviated:/^q[1234]/i,wide:/^[1234](th|st|nd|rd)? quarter/i},defaultMatchWidth:"wide",parsePatterns:{any:[/1/i,/2/i,/3/i,/4/i]},defaultParseWidth:"any",valueCallback:function(e){return e+1}}),month:M({matchPatterns:{narrow:/^[jfmasond]/i,abbreviated:/^(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)/i,wide:/^(january|february|march|april|may|june|july|august|september|october|november|december)/i},defaultMatchWidth:"wide",parsePatterns:{narrow:[/^j/i,/^f/i,/^m/i,/^a/i,/^m/i,/^j/i,/^j/i,/^a/i,/^s/i,/^o/i,/^n/i,/^d/i],any:[/^ja/i,/^f/i,/^mar/i,/^ap/i,/^may/i,/^jun/i,/^jul/i,/^au/i,/^s/i,/^o/i,/^n/i,/^d/i]},defaultParseWidth:"any"}),day:M({matchPatterns:{narrow:/^[smtwf]/i,short:/^(su|mo|tu|we|th|fr|sa)/i,abbreviated:/^(sun|mon|tue|wed|thu|fri|sat)/i,wide:/^(sunday|monday|tuesday|wednesday|thursday|friday|saturday)/i},defaultMatchWidth:"wide",parsePatterns:{narrow:[/^s/i,/^m/i,/^t/i,/^w/i,/^t/i,/^f/i,/^s/i],any:[/^su/i,/^m/i,/^tu/i,/^w/i,/^th/i,/^f/i,/^sa/i]},defaultParseWidth:"any"}),dayPeriod:M({matchPatterns:{narrow:/^(a|p|mi|n|(in the|at) (morning|afternoon|evening|night))/i,any:/^([ap]\.?\s?m\.?|midnight|noon|(in the|at) (morning|afternoon|evening|night))/i},defaultMatchWidth:"any",parsePatterns:{any:{am:/^a/i,pm:/^p/i,midnight:/^mi/i,noon:/^no/i,morning:/morning/i,afternoon:/afternoon/i,evening:/evening/i,night:/night/i}},defaultParseWidth:"any"})},options:{weekStartsOn:0,firstWeekContainsDate:1}};function E(e){if(null===e||!0===e||!1===e)return NaN;var t=Number(e);return isNaN(t)?t:t<0?Math.ceil(t):Math.floor(t)}function x(e,t){if(arguments.length<2)throw new TypeError("2 arguments required, but only "+arguments.length+" present");return function(e,t){if(arguments.length<2)throw new TypeError("2 arguments required, but only "+arguments.length+" present");var r=D(e).getTime(),n=E(t);return new Date(r+n)}(e,-E(t))}function F(e,t){for(var r=e<0?"-":"",n=Math.abs(e).toString();n.length<t;)n="0"+n;return r+n}var k={y:function(e,t){var r=e.getUTCFullYear(),n=r>0?r:1-r;return F("yy"===t?n%100:n,t.length)},M:function(e,t){var r=e.getUTCMonth();return"M"===t?String(r+1):F(r+1,2)},d:function(e,t){return F(e.getUTCDate(),t.length)},a:function(e,t){var r=e.getUTCHours()/12>=1?"pm":"am";switch(t){case"a":case"aa":case"aaa":return r.toUpperCase();case"aaaaa":return r[0];default:return"am"===r?"a.m.":"p.m."}},h:function(e,t){return F(e.getUTCHours()%12||12,t.length)},H:function(e,t){return F(e.getUTCHours(),t.length)},m:function(e,t){return F(e.getUTCMinutes(),t.length)},s:function(e,t){return F(e.getUTCSeconds(),t.length)},S:function(e,t){var r=t.length,n=e.getUTCMilliseconds();return F(Math.floor(n*Math.pow(10,r-3)),t.length)}};function U(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=D(e),r=t.getUTCDay(),n=(r<1?7:0)+r-1;return t.setUTCDate(t.getUTCDate()-n),t.setUTCHours(0,0,0,0),t}function N(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=D(e),r=t.getUTCFullYear(),n=new Date(0);n.setUTCFullYear(r+1,0,4),n.setUTCHours(0,0,0,0);var a=U(n),i=new Date(0);i.setUTCFullYear(r,0,4),i.setUTCHours(0,0,0,0);var o=U(i);return t.getTime()>=a.getTime()?r+1:t.getTime()>=o.getTime()?r:r-1}function W(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=D(e),r=U(t).getTime()-function(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=N(e),r=new Date(0);return r.setUTCFullYear(t,0,4),r.setUTCHours(0,0,0,0),U(r)}(t).getTime();return Math.round(r/6048e5)+1}function R(e,t){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var r=t||{},n=r.locale,a=n&&n.options&&n.options.weekStartsOn,i=null==a?0:E(a),o=null==r.weekStartsOn?i:E(r.weekStartsOn);if(!(o>=0&&o<=6))throw new RangeError("weekStartsOn must be between 0 and 6 inclusively");var s=D(e),u=s.getUTCDay(),l=(u<o?7:0)+u-o;return s.setUTCDate(s.getUTCDate()-l),s.setUTCHours(0,0,0,0),s}function z(e,t){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var r=D(e,t),n=r.getUTCFullYear(),a=t||{},i=a.locale,o=i&&i.options&&i.options.firstWeekContainsDate,s=null==o?1:E(o),u=null==a.firstWeekContainsDate?s:E(a.firstWeekContainsDate);if(!(u>=1&&u<=7))throw new RangeError("firstWeekContainsDate must be between 1 and 7 inclusively");var l=new Date(0);l.setUTCFullYear(n+1,0,u),l.setUTCHours(0,0,0,0);var d=R(l,t),h=new Date(0);h.setUTCFullYear(n,0,u),h.setUTCHours(0,0,0,0);var c=R(h,t);return r.getTime()>=d.getTime()?n+1:r.getTime()>=c.getTime()?n:n-1}function q(e,t){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var r=D(e),n=R(r,t).getTime()-function(e,t){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var r=t||{},n=r.locale,a=n&&n.options&&n.options.firstWeekContainsDate,i=null==a?1:E(a),o=null==r.firstWeekContainsDate?i:E(r.firstWeekContainsDate),s=z(e,t),u=new Date(0);return u.setUTCFullYear(s,0,o),u.setUTCHours(0,0,0,0),R(u,t)}(r,t).getTime();return Math.round(n/6048e5)+1}var Y="midnight",I="noon",G="morning",j="afternoon",H="evening",B="night",L={G:function(e,t,r){var n=e.getUTCFullYear()>0?1:0;switch(t){case"G":case"GG":case"GGG":return r.era(n,{width:"abbreviated"});case"GGGGG":return r.era(n,{width:"narrow"});default:return r.era(n,{width:"wide"})}},y:function(e,t,r){if("yo"===t){var n=e.getUTCFullYear(),a=n>0?n:1-n;return r.ordinalNumber(a,{unit:"year"})}return k.y(e,t)},Y:function(e,t,r,n){var a=z(e,n),i=a>0?a:1-a;return"YY"===t?F(i%100,2):"Yo"===t?r.ordinalNumber(i,{unit:"year"}):F(i,t.length)},R:function(e,t){return F(N(e),t.length)},u:function(e,t){return F(e.getUTCFullYear(),t.length)},Q:function(e,t,r){var n=Math.ceil((e.getUTCMonth()+1)/3);switch(t){case"Q":return String(n);case"QQ":return F(n,2);case"Qo":return r.ordinalNumber(n,{unit:"quarter"});case"QQQ":return r.quarter(n,{width:"abbreviated",context:"formatting"});case"QQQQQ":return r.quarter(n,{width:"narrow",context:"formatting"});default:return r.quarter(n,{width:"wide",context:"formatting"})}},q:function(e,t,r){var n=Math.ceil((e.getUTCMonth()+1)/3);switch(t){case"q":return String(n);case"qq":return F(n,2);case"qo":return r.ordinalNumber(n,{unit:"quarter"});case"qqq":return r.quarter(n,{width:"abbreviated",context:"standalone"});case"qqqqq":return r.quarter(n,{width:"narrow",context:"standalone"});default:return r.quarter(n,{width:"wide",context:"standalone"})}},M:function(e,t,r){var n=e.getUTCMonth();switch(t){case"M":case"MM":return k.M(e,t);case"Mo":return r.ordinalNumber(n+1,{unit:"month"});case"MMM":return r.month(n,{width:"abbreviated",context:"formatting"});case"MMMMM":return r.month(n,{width:"narrow",context:"formatting"});default:return r.month(n,{width:"wide",context:"formatting"})}},L:function(e,t,r){var n=e.getUTCMonth();switch(t){case"L":return String(n+1);case"LL":return F(n+1,2);case"Lo":return r.ordinalNumber(n+1,{unit:"month"});case"LLL":return r.month(n,{width:"abbreviated",context:"standalone"});case"LLLLL":return r.month(n,{width:"narrow",context:"standalone"});default:return r.month(n,{width:"wide",context:"standalone"})}},w:function(e,t,r,n){var a=q(e,n);return"wo"===t?r.ordinalNumber(a,{unit:"week"}):F(a,t.length)},I:function(e,t,r){var n=W(e);return"Io"===t?r.ordinalNumber(n,{unit:"week"}):F(n,t.length)},d:function(e,t,r){return"do"===t?r.ordinalNumber(e.getUTCDate(),{unit:"date"}):k.d(e,t)},D:function(e,t,r){var n=function(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=D(e),r=t.getTime();t.setUTCMonth(0,1),t.setUTCHours(0,0,0,0);var n=r-t.getTime();return Math.floor(n/864e5)+1}(e);return"Do"===t?r.ordinalNumber(n,{unit:"dayOfYear"}):F(n,t.length)},E:function(e,t,r){var n=e.getUTCDay();switch(t){case"E":case"EE":case"EEE":return r.day(n,{width:"abbreviated",context:"formatting"});case"EEEEE":return r.day(n,{width:"narrow",context:"formatting"});case"EEEEEE":return r.day(n,{width:"short",context:"formatting"});default:return r.day(n,{width:"wide",context:"formatting"})}},e:function(e,t,r,n){var a=e.getUTCDay(),i=(a-n.weekStartsOn+8)%7||7;switch(t){case"e":return String(i);case"ee":return F(i,2);case"eo":return r.ordinalNumber(i,{unit:"day"});case"eee":return r.day(a,{width:"abbreviated",context:"formatting"});case"eeeee":return r.day(a,{width:"narrow",context:"formatting"});case"eeeeee":return r.day(a,{width:"short",context:"formatting"});default:return r.day(a,{width:"wide",context:"formatting"})}},c:function(e,t,r,n){var a=e.getUTCDay(),i=(a-n.weekStartsOn+8)%7||7;switch(t){case"c":return String(i);case"cc":return F(i,t.length);case"co":return r.ordinalNumber(i,{unit:"day"});case"ccc":return r.day(a,{width:"abbreviated",context:"standalone"});case"ccccc":return r.day(a,{width:"narrow",context:"standalone"});case"cccccc":return r.day(a,{width:"short",context:"standalone"});default:return r.day(a,{width:"wide",context:"standalone"})}},i:function(e,t,r){var n=e.getUTCDay(),a=0===n?7:n;switch(t){case"i":return String(a);case"ii":return F(a,t.length);case"io":return r.ordinalNumber(a,{unit:"day"});case"iii":return r.day(n,{width:"abbreviated",context:"formatting"});case"iiiii":return r.day(n,{width:"narrow",context:"formatting"});case"iiiiii":return r.day(n,{width:"short",context:"formatting"});default:return r.day(n,{width:"wide",context:"formatting"})}},a:function(e,t,r){var n=e.getUTCHours()/12>=1?"pm":"am";switch(t){case"a":case"aa":case"aaa":return r.dayPeriod(n,{width:"abbreviated",context:"formatting"});case"aaaaa":return r.dayPeriod(n,{width:"narrow",context:"formatting"});default:return r.dayPeriod(n,{width:"wide",context:"formatting"})}},b:function(e,t,r){var n,a=e.getUTCHours();switch(n=12===a?I:0===a?Y:a/12>=1?"pm":"am",t){case"b":case"bb":case"bbb":return r.dayPeriod(n,{width:"abbreviated",context:"formatting"});case"bbbbb":return r.dayPeriod(n,{width:"narrow",context:"formatting"});default:return r.dayPeriod(n,{width:"wide",context:"formatting"})}},B:function(e,t,r){var n,a=e.getUTCHours();switch(n=a>=17?H:a>=12?j:a>=4?G:B,t){case"B":case"BB":case"BBB":return r.dayPeriod(n,{width:"abbreviated",context:"formatting"});case"BBBBB":return r.dayPeriod(n,{width:"narrow",context:"formatting"});default:return r.dayPeriod(n,{width:"wide",context:"formatting"})}},h:function(e,t,r){if("ho"===t){var n=e.getUTCHours()%12;return 0===n&&(n=12),r.ordinalNumber(n,{unit:"hour"})}return k.h(e,t)},H:function(e,t,r){return"Ho"===t?r.ordinalNumber(e.getUTCHours(),{unit:"hour"}):k.H(e,t)},K:function(e,t,r){var n=e.getUTCHours()%12;return"Ko"===t?r.ordinalNumber(n,{unit:"hour"}):F(n,t.length)},k:function(e,t,r){var n=e.getUTCHours();return 0===n&&(n=24),"ko"===t?r.ordinalNumber(n,{unit:"hour"}):F(n,t.length)},m:function(e,t,r){return"mo"===t?r.ordinalNumber(e.getUTCMinutes(),{unit:"minute"}):k.m(e,t)},s:function(e,t,r){return"so"===t?r.ordinalNumber(e.getUTCSeconds(),{unit:"second"}):k.s(e,t)},S:function(e,t){return k.S(e,t)},X:function(e,t,r,n){var a=(n._originalDate||e).getTimezoneOffset();if(0===a)return"Z";switch(t){case"X":return Q(a);case"XXXX":case"XX":return $(a);default:return $(a,":")}},x:function(e,t,r,n){var a=(n._originalDate||e).getTimezoneOffset();switch(t){case"x":return Q(a);case"xxxx":case"xx":return $(a);default:return $(a,":")}},O:function(e,t,r,n){var a=(n._originalDate||e).getTimezoneOffset();switch(t){case"O":case"OO":case"OOO":return"GMT"+V(a,":");default:return"GMT"+$(a,":")}},z:function(e,t,r,n){var a=(n._originalDate||e).getTimezoneOffset();switch(t){case"z":case"zz":case"zzz":return"GMT"+V(a,":");default:return"GMT"+$(a,":")}},t:function(e,t,r,n){var a=n._originalDate||e;return F(Math.floor(a.getTime()/1e3),t.length)},T:function(e,t,r,n){return F((n._originalDate||e).getTime(),t.length)}};function V(e,t){var r=e>0?"-":"+",n=Math.abs(e),a=Math.floor(n/60),i=n%60;if(0===i)return r+String(a);var o=t||"";return r+String(a)+o+F(i,2)}function Q(e,t){return e%60==0?(e>0?"-":"+")+F(Math.abs(e)/60,2):$(e,t)}function $(e,t){var r=t||"",n=e>0?"-":"+",a=Math.abs(e);return n+F(Math.floor(a/60),2)+r+F(a%60,2)}var A=L;function X(e,t){switch(e){case"P":return t.date({width:"short"});case"PP":return t.date({width:"medium"});case"PPP":return t.date({width:"long"});default:return t.date({width:"full"})}}function _(e,t){switch(e){case"p":return t.time({width:"short"});case"pp":return t.time({width:"medium"});case"ppp":return t.time({width:"long"});default:return t.time({width:"full"})}}var J={p:_,P:function(e,t){var r,n=e.match(/(P+)(p+)?/),a=n[1],i=n[2];if(!i)return X(e,t);switch(a){case"P":r=t.dateTime({width:"short"});break;case"PP":r=t.dateTime({width:"medium"});break;case"PPP":r=t.dateTime({width:"long"});break;default:r=t.dateTime({width:"full"})}return r.replace("{{date}}",X(a,t)).replace("{{time}}",_(i,t))}},K=J,Z=6e4;var ee=["D","DD"],te=["YY","YYYY"];function re(e){if("YYYY"===e)throw new RangeError("Use `yyyy` instead of `YYYY` for formatting years; see: https://git.io/fxCyr");if("YY"===e)throw new RangeError("Use `yy` instead of `YY` for formatting years; see: https://git.io/fxCyr");if("D"===e)throw new RangeError("Use `d` instead of `D` for formatting days of the month; see: https://git.io/fxCyr");if("DD"===e)throw new RangeError("Use `dd` instead of `DD` for formatting days of the month; see: https://git.io/fxCyr")}var ne,ae,ie,oe,se,ue,le,de=/[yYQqMLwIdDecihHKkms]o|(\w)\1*|''|'(''|[^'])+('|$)|./g,he=/P+p+|P+|p+|''|'(''|[^'])+('|$)|./g,ce=/^'(.*?)'?$/,me=/''/g,fe=/[a-zA-Z]/;function ge(e,t,r){if(arguments.length<2)throw new TypeError("2 arguments required, but only "+arguments.length+" present");var n=String(t),a=r||{},i=a.locale||O,o=i.options&&i.options.firstWeekContainsDate,s=null==o?1:E(o),u=null==a.firstWeekContainsDate?s:E(a.firstWeekContainsDate);if(!(u>=1&&u<=7))throw new RangeError("firstWeekContainsDate must be between 1 and 7 inclusively");var l=i.options&&i.options.weekStartsOn,d=null==l?0:E(l),h=null==a.weekStartsOn?d:E(a.weekStartsOn);if(!(h>=0&&h<=6))throw new RangeError("weekStartsOn must be between 0 and 6 inclusively");if(!i.localize)throw new RangeError("locale must contain localize property");if(!i.formatLong)throw new RangeError("locale must contain formatLong property");var c=D(e);if(!function(e){if(arguments.length<1)throw new TypeError("1 argument required, but only "+arguments.length+" present");var t=D(e);return!isNaN(t)}(c))throw new RangeError("Invalid time value");var m=function(e){var t=new Date(e.getTime()),r=t.getTimezoneOffset();t.setSeconds(0,0);var n=t.getTime()%Z;return r*Z+n}(c),f=x(c,m),g={firstWeekContainsDate:u,weekStartsOn:h,locale:i,_originalDate:c};return n.match(he).map((function(e){var t=e[0];return"p"===t||"P"===t?(0,K[t])(e,i.formatLong,g):e})).join("").match(de).map((function(e){if("''"===e)return"'";var t=e[0];if("'"===t)return e.match(ce)[1].replace(me,"'");var r,n=A[t];if(n)return a.useAdditionalWeekYearTokens||(r=e,-1===te.indexOf(r))||re(e),!a.useAdditionalDayOfYearTokens&&function(e){return-1!==ee.indexOf(e)}(e)&&re(e),n(f,e,i.localize,g);if(t.match(fe))throw new RangeError("Format string contains an unescaped latin alphabet character `"+t+"`");return e})).join("")}class pe extends r.FieldWithPriceModel{constructor(e,t){super(e,t)}InitializePriceCalculator(){this.Options.PriceType==n.PriceTypeEnum.price_per_day?this.Calculator=(new w).Initialize(this):super.InitializePriceCalculator()}InternalSerialize(e){super.InternalSerialize(e);let t=this.GetValue();if(null==t)return;e.Value={},e.Value.StartDateLabel=this.Options.StartDate.Label,e.Value.EndDateLabel=this.Options.EndDate.Label,e.Value.StartUnix=t.StartDate,e.Value.EndUnix=t.EndDate;let r=new Date(1e3*e.Value.StartUnix);r=new Date(r.setMinutes(r.getMinutes()+r.getTimezoneOffset())),e.Value.StartValue=p.default.formatDate(r,this.DateFormat),r=new Date(1e3*e.Value.EndUnix),r=new Date(r.setMinutes(r.getMinutes()+r.getTimezoneOffset())),e.Value.EndValue=p.default.formatDate(r,this.DateFormat)}GetStoresInformation(){return!0}GetIsUsed(){return!!super.GetIsUsed()&&null!=this.GetValue()}GetValue(){return this.GetIsVisible()&&null!==this.StartDate&&null!==this.EndDate?{StartDate:this.StartDate,EndDate:this.EndDate}:null}CalculateDefaultDate(e,t){this[e]=null;let r=null;if(t.indexOf("/")>=0){let e=t.split("/");if(3==e.length){let t=parseInt(e[0]),n=parseInt(e[1]),a=parseInt(e[2]);isNaN(t)||isNaN(n)||isNaN(a)||(n--,r=new Date(t,n,a))}}else if(""!=t.trim()){let e=parseFloat(t.trim());r=new Date,r.setHours(0),r.setMinutes(0),r.setMilliseconds(0),r.setDate(r.getDate()+e)}this[e]=null!=r?b.DateToUnix(r):null}GetDynamicFieldNames(){return["FBDateRange"]}CalculateDateFormat(){this.DateFormat="MM/dd/yyyy";try{ge(new Date,this.Options.Format)}catch(e){return}this.DateFormat=this.Options.Format}InitializeStartingValues(){this.CalculateDefaultDate("StartDate",this.Options.StartDate.DefaultDate),this.CalculateDefaultDate("EndDate",this.Options.EndDate.DefaultDate);let e=this.GetPreviousDataProperty("Value",null);null!=e&&null!=e.StartUnix&&(this.StartDate=e.StartUnix),null!=e&&null!=e.EndUnix&&(this.EndDate=e.EndUnix),this.DateFormat="",this.CalculateDateFormat()}SetStartDate(e){this.StartDate=e,this.FireValueChanged()}SetEndDate(e){this.EndDate=e,this.FireValueChanged()}GetStartDate(){return null!==this.StartDate?b.UnixToDate(this.StartDate):null}GetEndDate(){return null!==this.EndDate?b.UnixToDate(this.EndDate):null}render(){return o.html`<rn-date-range-field .model="${this}"></rn-date-range-field>`}}function we(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function be(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?we(Object(r),!0).forEach((function(t){babelHelpers.defineProperty(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):we(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}let De=(ne=u.customElement("rn-date-range-field"),ae=u.query(".startdate"),ie=u.query(".enddate"),ne((se=class extends h.FieldWithPrice{constructor(...e){super(...e),babelHelpers.initializerDefineProperty(this,"StartDateInput",ue,this),babelHelpers.initializerDefineProperty(this,"EndDateInput",le,this)}static get properties(){return l.FieldBase.properties}SubRender(){return o.html` <div style="position: relative;"> <div style="white-space: nowrap"> <div style="position: relative" class="${"horizontal"==this.model.Options.Orientation?"rncolsm2":""}"> <div style="position: relative"> <input ${d.IconDirective(this.model.Options.StartDate.Icon)} ?readOnly=${this.model.IsReadonly} @focus=${()=>{this.model.IsFocused=!0,this.model.Refresh()}} @blur=${()=>{this.model.IsFocused=!1,this.model.Refresh()}} class='startdate' placeholder=${this.model.Options.StartDate.Placeholder} style="width: 100%;background-color: white;" type='text' value=${c.live(this.model.GetText())}/> </div> <span>${this.GetText(this.model.Options.StartDate,"Label")}</span> </div> <div style="position: relative" class="${"horizontal"==this.model.Options.Orientation?"rncolsm2":""}"> <div style="position: relative"> <input ${d.IconDirective(this.model.Options.EndDate.Icon)} ?readOnly=${this.model.IsReadonly} @focus=${()=>{this.model.IsFocused=!0,this.model.Refresh()}} @blur=${()=>{this.model.IsFocused=!1,this.model.Refresh()}} class='enddate' placeholder=${this.model.Options.EndDate.Placeholder} style="width: 100%;background-color: white;" type='text' value=${c.live(this.model.GetText())}/> </div> <span>${this.GetText(this.model.Options.EndDate,"Label")}</span> </div> </div> </div> `}firstUpdated(e){super.firstUpdated(e),this.GenerateDatePicker(),this.model.Instance=this}GenerateDatePicker(){null!=this.StartDateFlatPickr&&(this.StartDateFlatPickr.destroy(),this.StartDateFlatPickr=null),null!=this.EndDateFlatPickr&&(this.EndDateFlatPickr.destroy(),this.EndDateFlatPickr=null);let e={dateFormat:this.model.Options.Format,enableTime:this.model.Options.EnableTime,time_24hr:!0,onChange:(e,t,r)=>{0==e.length?this.model.SetStartDate(null):this.model.SetStartDate(b.DateToUnix(e[0]))}},t={dateFormat:this.model.Options.Format,enableTime:this.model.Options.EnableTime,time_24hr:!0,onChange:(e,t,r)=>{0==e.length?this.model.SetEndDate(null):this.model.SetEndDate(b.DateToUnix(e[0]))}};this.StartDateInput.value="",this.EndDateInput.value="",e.defaultDate=this.GenerateDefaultDate(this.model.StartDate,this.model.Options.StartDate),e.disable=[e=>this.model.Options.SDisableWeek.indexOf(e.getDay().toString())>=0],t.defaultDate=this.GenerateDefaultDate(this.model.EndDate,this.model.Options.EndDate),t.disable=[e=>this.model.Options.EDisableWeek.indexOf(e.getDay().toString())>=0],e.locale=be({},this.model.RootFormBuilder.AdditionalOptions.TCal,{firstDayOfWeek:i.Sanitizer.SanitizeNumber(this.model.Options.FirstDayOfWeek)}),t.locale=be({},this.model.RootFormBuilder.AdditionalOptions.TCal,{firstDayOfWeek:i.Sanitizer.SanitizeNumber(this.model.Options.FirstDayOfWeek)}),this.StartDateFlatPickr=p.default(this.StartDateInput,e),this.EndDateFlatPickr=p.default(this.EndDateInput,t)}GenerateDefaultDate(e,t){if(null!=e)return new Date(b.UnixToDate(e));let r=null;if(""!=t.DefaultDate.trim())if(isNaN(Number(t.DefaultDate)))r=new Date(t.DefaultDate),isNaN(r.getTime())&&(r=null);else{let e=Number(t.DefaultDate);r=new Date,r.setDate(r.getDate()+e)}return r}},ue=babelHelpers.applyDecoratedDescriptor(se.prototype,"StartDateInput",[ae],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),le=babelHelpers.applyDecoratedDescriptor(se.prototype,"EndDateInput",[ie],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),oe=se))||oe);var ye,ve,Te,Se,Ce;class Me extends m.StoreBase{LoadDefaultValues(){this.DefaultDate="",this.Placeholder="",this.Label="",this.Icon=(new f.IconOptions).Merge()}}let Pe=(ye=m.StoreDataType(String),ve=m.StoreDataType(String),Te=class extends n.FieldWithPriceOptions{constructor(...e){super(...e),babelHelpers.initializerDefineProperty(this,"SDisableWeek",Se,this),babelHelpers.initializerDefineProperty(this,"EDisableWeek",Ce,this)}LoadDefaultValues(){super.LoadDefaultValues(),this.SDisableWeek=[],this.EDisableWeek=[],this.FirstDayOfWeek=0,this.EnableTime=!1,this.Type=t.FieldTypeEnum.DateRange,this.Format="d/m/Y",this.Orientation="horizontal",this.Label="Dater Range",this.StartDate=(new Me).Merge({Label:"Start Date"}),this.EndDate=(new Me).Merge({Label:"End Date"})}},Se=babelHelpers.applyDecoratedDescriptor(Te.prototype,"SDisableWeek",[ye],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),Ce=babelHelpers.applyDecoratedDescriptor(Te.prototype,"EDisableWeek",[ve],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),Te);exports.DateRangeFieldModel=pe,exports.DateRangeField=De,exports.DateRangeFieldOptions=Pe,e.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==t.FieldTypeEnum.DateRange)return new Pe})),e.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==t.FieldTypeEnum.DateRange)return new pe(e.Options,e.Parent)})),e.EventManager.Subscribe("GetCalculator",(e=>{if("price_per_day"==e)return new w}))}));
