rndefine("#RNMainRunnableForm",["#RNMainCore/WpAjaxPost","#RNMainFormBuilderCore/FormBuilder.Options","#RNMainFormBuilderCore/FormBuilder.Model","lit","#RNMainFormBuilderCore/CurrencyManager","#RNMainCore/EventManager"],(function(e,n,t,r,l,i){"use strict";async function o(){let o=document.querySelectorAll(".RNEasyFormContainer");for(let u=0;u<o.length;u++){let d=o[u],s=d.getAttribute("data-varid");if(!(a.indexOf(s)>=0)&&null!=window["FormOptions_"+s]){if(null==window["FormOptions_"+s])continue;a.push(s);let o=window["FormOptions_"+s];e.WpAjaxPost.SetGlobalVar(o),l.CurrencyManager.WasInitialized||l.CurrencyManager.Initialize(o.Currency);let u=(new n.FormBuilderOptions).Merge(o.Options);if(null!=u.ClientOptions.MultipleSteps){let e=d.parentNode.querySelector(".quantity");null!=e&&(e.style.display="none");let n=d.parentNode.querySelector(".single_add_to_cart_button");null!=n&&(n.style.display="none")}null!=o.Attributes&&null!=o.Attributes.CurrentDate&&(o.Attributes.CurrentDate=new Date(1e3*o.Attributes.CurrentDate));let m=i.EventManager.Publish("LoadFormBuilderModel",{FormOptions:o,Options:u},null);null==m&&(m=new t.FormBuilderModel(o.FormId,u,o)),window.LoadedForms.push(m);let p=[];i.EventManager.Publish("RegisterRunnableAddOn",{FormBuilder:m,AddOns:p}),m.SetAddOns(p),await m.Initialize(),m.ExecuteFirstCalculation(),r.render(m.render(),d)}}}let a=[];window.LoadedForms=[],o();let u=setInterval(o,100);window.addEventListener("DOMContentLoaded",(()=>{o(),clearInterval(u)}))}));
