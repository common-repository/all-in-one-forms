rndefine("#RNMainConditionDesigner",["lit","#RNMainCore/LitElementBase","lit/decorators","#RNMainFormBuilderCore/ConditionBase.Options","lit/directives/repeat.js","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/FormBuilder.Options","#RNMainParser/FormulaBuilder.Model","#RNMainDialog/Dialog","#RNMainCore/EventManager","#RNMainLit/Lit","lit-html/directives/live.js","#RNMainCore/Sanitizer","#RNMainCore/WpAjaxPost"],(function(i,n,e,t,o,s,a,r,l,d,p,u,m,C){"use strict";var h={};!function(i){Object.defineProperty(i,"__esModule",{value:!0});var n="list",e=[],t="f03a",o="M80 368H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm0-320H16A16 16 0 0 0 0 64v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16zm0 160H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm416 176H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z";i.definition={prefix:"fas",iconName:n,icon:[512,512,e,t,o]},i.faList=i.definition,i.prefix="fas",i.iconName=n,i.width=512,i.height=512,i.ligatures=e,i.unicode=t,i.svgPathData=o}(h);var c,y={};!function(i){Object.defineProperty(i,"__esModule",{value:!0});var n="calculator",e=[],t="f1ec",o="M400 0H48C22.4 0 0 22.4 0 48v416c0 25.6 22.4 48 48 48h352c25.6 0 48-22.4 48-48V48c0-25.6-22.4-48-48-48zM128 435.2c0 6.4-6.4 12.8-12.8 12.8H76.8c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4zm0-128c0 6.4-6.4 12.8-12.8 12.8H76.8c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4zm128 128c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4zm0-128c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4zm128 128c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8V268.8c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v166.4zm0-256c0 6.4-6.4 12.8-12.8 12.8H76.8c-6.4 0-12.8-6.4-12.8-12.8V76.8C64 70.4 70.4 64 76.8 64h294.4c6.4 0 12.8 6.4 12.8 12.8v102.4z";i.definition={prefix:"fas",iconName:n,icon:[448,512,e,t,o]},i.faCalculator=i.definition,i.prefix="fas",i.iconName=n,i.width=448,i.height=512,i.ligatures=e,i.unicode=t,i.svgPathData=o}(y),e.customElement("rn-condition-builder")(class extends n.LitElementBase{static get properties(){return{}}render(){var n;return i.html` <div style="position:relative;"> ${p.rnIf((null===(n=this.model)||void 0===n?void 0:n.SupportFormulaPanel)&&this.ShowFormulaSwitch(this.Condition))} ${this.GetConditionContent(this.Condition)} </div> `}RemoveGroup(i){let n=this.Condition.ConditionGroups.indexOf(i);n<0||(this.Condition.ConditionGroups.splice(n,1),0==this.Condition.ConditionGroups.length&&this.FireEvent("AllGroupsRemoved",!0),this.RecalculateElementsUsed(),this.forceUpdate())}CreateGroup(){this.Condition.ConditionGroups.push((new t.ConditionGroupOptions).Merge({ConditionLines:[{}]})),this.forceUpdate()}RecalculateElementsUsed(){if(this.Condition.ElementsUsed=[],null!=this.Condition.Formula)for(let i of this.Condition.Formula.Fields)this.Condition.ElementsUsed.push((new t.ElementUsedOptions).Merge({Type:t.ElementUsedTypeEnum.Field,Id:i}));for(let i of this.Condition.ConditionGroups)for(let n of i.ConditionLines)if(n.Type==t.ConditionLineTypeEnum.Field&&this.Condition.ElementsUsed.push((new t.ElementUsedOptions).Merge({Type:t.ElementUsedTypeEnum.Field,Id:n.FieldId})),null!=n.Formula)for(let i of n.Formula.Fields)this.Condition.ElementsUsed.push((new t.ElementUsedOptions).Merge({Type:t.ElementUsedTypeEnum.Field,Id:i}))}CreateFormulaBuilder(){var i;this.FormulaBuilder=new r.FormulaBuilderModel(this.model.Form,d.EventManager.Publish("GetAutocompleteDictionary",null===(i=this.model.Form)||void 0===i?void 0:i.RootFormBuilder)),this.forceUpdate()}IsUsingFormulaMode(i){return null!=this.FormulaBuilder||null!=i.Formula}ShowFormulaSwitch(n){return i.html` <div style="text-align: left;padding: 2px" class="conditionToolBar"> <rn-fontawesome title="Standard condition mode" class="${this.IsUsingFormulaMode(n)?"":"active"}" @click="${()=>{this.FormulaBuilder=null,n.Formula=null,this.forceUpdate()}}" .icon="${h.faList}"></rn-fontawesome> <span style="margin:0 5px;opacity: .5">|</span> <rn-fontawesome title="Formula condition mode" class="${this.IsUsingFormulaMode(n)?"active":""}" @click="${()=>{this.CreateFormulaBuilder()}}" .icon="${y.faCalculator}"></rn-fontawesome> </div> `}ShowFormulaEditor(i){var n;return this.FormulaBuilder.Render(null,null===(n=i.Formula)||void 0===n?void 0:n.Code,(n=>{null==n&&(i.Formula=null),i.Formula=(new s.FormulaOptions).Merge({Code:n.Code,Compiled:n.Compiled,PreferredReturnType:a.PreferredReturnTypeEnum.Any,Fields:n.FieldsUsed,Dependencies:n.Dependencies}),this.RecalculateElementsUsed()}))}GetConditionContent(n){var e;return this.IsUsingFormulaMode(n)?(null==this.FormulaBuilder&&this.CreateFormulaBuilder(),i.html`<div> <div style="margin: 3px;text-align: right"> <a href="#" @click="${()=>l.Dialog.Show(i.html`<rn-formula-condition-dialog></rn-formula-condition-dialog>`)}">What is formula condition mode?</a> </div> ${"1"==(null===(e=rednaoFormDesigner)||void 0===e?void 0:e.IsPr)?this.ShowFormulaEditor(n):i.html` <div style="border: 1px solid #ccc; border-radius: 10px;padding: 20px"> <p>Sorry formula conditions require the <strong>essentials add on</strong>, please use the standard conditions instead</p> <div style="text-align: center"> <a @click="${()=>{this.FormulaBuilder=null,n.Formula=null,this.forceUpdate()}}" href="#">Click here to swith to the standard conditions</a> </div> </div> `} </div>`):i.html` <div style="margin-top: 15px;"> ${o.repeat(this.Condition.ConditionGroups,(i=>i.Id),((n,e)=>i.html` <div style="margin-bottom: 10px"> <rn-condition-group .model="${this.model}" @formulachanged="${()=>this.RecalculateElementsUsed()}" @fieldchanged=${()=>this.RecalculateElementsUsed()} @removegroup=${i=>this.RemoveGroup(i.detail)} .ConditionBuilder=${this} .ConditionGroup=${n} .AdditionalConditions=${this.AdditionalConditions}></rn-condition-group> ${e<this.Condition.ConditionGroups.length-1?i.html`<span style="font-weight: bold;">${RNTranslate("or")}</span>`:""} </div> `))} <div style="margin-top: 10px;"> <div style="margin-top: 10px;"> <button style="margin-left: 2px" @click=${i=>{i.preventDefault(),this.CreateGroup()}} class='rnbtn rnbtn-primary'>${RNTranslate("Add new group")}</button> </div> </div> </div> `}});let E=e.customElement("rn-condition-group")(c=class extends n.LitElementBase{constructor(...i){super(...i),this.AdditionalConditions=[]}static get properties(){return{}}render(){return i.html` <table class='rnConditionGroup' style="margin-bottom: 10px;table-layout: fixed;width: 100%;"> <tbody> ${o.repeat(this.ConditionGroup.ConditionLines,(i=>i.Id),((n,e)=>i.html` <rn-condition-line .model=${this.model} .AdditionalConditions=${this.AdditionalConditions} @removeline=${i=>this.RemoveLine(i.detail)} @addline=${i=>this.AddLineBellow(i.detail)} .ConditionGroup=${this} .ConditionLineOptions=${n}></rn-condition-line> `))} </tbody> </table> `}RemoveLine(i){let n=this.ConditionGroup.ConditionLines.indexOf(i);n<0||(this.ConditionGroup.ConditionLines.splice(n,1),0!=this.ConditionGroup.ConditionLines.length?(this.FireEvent("fieldchanged"),this.forceUpdate()):this.FireEvent("removegroup",this.ConditionGroup))}AddLineBellow(i){let n=this.ConditionGroup.ConditionLines.indexOf(i);n<0||(this.ConditionGroup.ConditionLines.splice(n+1,0,(new t.ConditionLineOptions).Merge()),this.FireEvent("fieldchanged"),this.forceUpdate())}})||c;var T,g={};!function(i){Object.defineProperty(i,"__esModule",{value:!0});var n="times",e=[],t="f00d",o="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z";i.definition={prefix:"fas",iconName:n,icon:[352,512,e,t,o]},i.faTimes=i.definition,i.prefix="fas",i.iconName=n,i.width=352,i.height=512,i.ligatures=e,i.unicode=t,i.svgPathData=o}(g);class ${Initialize(i,n){return this.Condition=i,this.Model=n,this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.None&&this.InitializeValues(),this}GetFormulaPlaceHolder(){return null==this.Condition.ConditionGroup.ConditionBuilder.model?null:this.Condition.ConditionGroup.ConditionBuilder.model.SupportFormulas?i.html` <rn-fontawesome @click="${()=>this.OpenFormula()}" class="formulaIcon" .icon="${y.faCalculator}" style="z-index: 1;font-size: 18px;cursor: pointer;color:#dfdfdf;right: 3px;top:50%;position:absolute;transform: translateY(-50%)" ></rn-fontawesome> `:null}GetPreferredReturnType(){return a.PreferredReturnTypeEnum.String}GetFormulaOrValueInput(){return null!=this.Condition&&null!=this.Condition.ConditionLineOptions.Formula?i.html` <div style="position: relative;width: 100%;"> <input readonly type="text" style="width: 100%;height: 30px;padding: 0 5px;cursor: pointer;" .value="${d.EventManager.Publish("GetFriendlyFormula",{Form:this.Model.GetFormulaFormBuilder(),Code:this.Condition.ConditionLineOptions.Formula.Code})}" @click="${()=>this.OpenFormula()}"/> <rn-fontawesome title="Remove Formula" .icon=${g.faTimes} class='formulaRemove' style="font-size: 18px;margin-right: 5px;cursor: pointer;position: absolute;top:50%;right: 1px;transform: translateY(-50%)" @click=${()=>this.RemoveFormula()}></rn-fontawesome> </div>`:this.GetValueInput()}async OpenFormula(){null==this.Condition.ConditionLineOptions.Formula&&(this.Condition.ConditionLineOptions.Formula=(new s.FormulaOptions).Merge());let i=this.Condition.ConditionLineOptions.Formula;new r.FormulaBuilderModel(this.Model.GetFormulaFormBuilder(),d.EventManager.Publish("GetAutocompleteDictionary",this.Model.Form.RootFormBuilder)).RenderDialog(null,null==i?void 0:i.Code,(n=>{i.Code=n.Code,i.Compiled=n.Compiled,i.Fields=n.FieldsUsed,i.Dependencies=n.Dependencies,i.PreferredReturnType=this.GetPreferredReturnType(),this.Condition.forceUpdate(),this.Condition.FireEvent("formulachanged",null,!0)}))}RemoveFormula(){null!=this.Condition&&(this.Condition.ConditionLineOptions.Formula=null,this.Condition.forceUpdate(),this.Condition.FireEvent("formulachanged",null,!0))}}class v extends ${GetComparator(){return i.html`<select style="width:100%;height: auto;padding: 0;" disabled/>`}GetValueInput(){return i.html`<input value="" style="width: 100%;height: 30px;" type='text' disabled/>`}InitializeValues(){}}class L extends ${GetComparator(){return i.html` <select value=${this.Condition.ConditionLineOptions.Comparison} @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: 28px;padding: 0;width: 100%"> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.Contains}" value=${t.ComparisonTypeEnum.Contains}>${RNTranslate("Contains")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.NotContains}" value=${t.ComparisonTypeEnum.NotContains}>${RNTranslate("Not Contains")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsEmpty}" value=${t.ComparisonTypeEnum.IsEmpty}>${RNTranslate("Is Empty")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotEmpty}" value=${t.ComparisonTypeEnum.IsNotEmpty}>${RNTranslate("Is Not Empty")}</option> </select> `}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.Contains}GetValueInput(){if(this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsEmpty||this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotEmpty)return null;let n=this.GetAvailableOptions(),e=n.filter((i=>this.Condition.ConditionLineOptions.Value.indexOf(i.value)>=0)).map((i=>i.value)),o=this.Condition.ConditionLineOptions.Comparison,s=o==t.ComparisonTypeEnum.IsEmpty||o==t.ComparisonTypeEnum.IsNotEmpty;return i.html` <div style="width: 100%"> <rn-inputs-select style="min-height: 30px;margin-bottom: 0" multiple .value="${e}" @change="${i=>this.OnChange(i.detail)}" .propertyName="${"Type"}" .options="${n.map((i=>({Label:i.label,Value:i.value})))}"></rn-inputs-select> ${p.rnIf(!s&&this.GetFormulaPlaceHolder())} </div> `}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.forceUpdate()}GetAvailableOptions(){let i=[];if(this.Condition.ConditionLineOptions.Type==t.ConditionLineTypeEnum.Field){let n=this.Condition.ConditionGroup.ConditionBuilder.FormBuilder.GetFields(!0,!0,!0).find((i=>i.Options.Id.toString()==this.Condition.ConditionLineOptions.FieldId));if(null==n)return i;for(let e of n.Options.Options)i.push({label:e.Label,value:e.Id})}return i}OnChange(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}}class f extends ${GetComparator(){return i.html` <select value=${this.Condition.ConditionLineOptions.Comparison} @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: 28px;padding: 0;width: 100%"> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.Equal}" .value=${t.ComparisonTypeEnum.Equal}>${RNTranslate("Equal To")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.NotEqual}" .value=${t.ComparisonTypeEnum.NotEqual}>${RNTranslate("Not equal to")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.GreaterThan}" .value=${t.ComparisonTypeEnum.GreaterThan}>${RNTranslate("Greater than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.GreaterOrEqualThan}" .value=${t.ComparisonTypeEnum.GreaterOrEqualThan}>${RNTranslate("Greater or equal than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.LessThan}" .value=${t.ComparisonTypeEnum.LessThan}>${RNTranslate("Less than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.LessOrEqualThan}" .value=${t.ComparisonTypeEnum.LessOrEqualThan}>${RNTranslate("Less or equal than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsEmpty}" .value=${t.ComparisonTypeEnum.IsEmpty}>${RNTranslate("Is Empty")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotEmpty}" .value=${t.ComparisonTypeEnum.IsNotEmpty}>${RNTranslate("Is Not Empty")}</option> </select> `}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.Equal,this.Condition.ConditionLineOptions.Value=m.Sanitizer.SanitizeNumber(this.Condition.ConditionLineOptions.Value,0)}GetValueInput(){if(this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsEmpty||this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotEmpty)return null;let n=this.Condition.ConditionLineOptions.Comparison,e=n==t.ComparisonTypeEnum.IsEmpty||n==t.ComparisonTypeEnum.IsNotEmpty;return i.html` <div style="width: 100%"> ${p.rnIf(!e&&this.GetFormulaPlaceHolder())} <rn-inputs-datepicker style="height: 30px;min-height: 30px;margin-bottom: 0;" .value="${this.Condition.ConditionLineOptions.Value}" @change="${i=>this.Condition.ConditionLineOptions.Value=i.detail}"></rn-inputs-datepicker> </div> `}GetPreferredReturnType(){return a.PreferredReturnTypeEnum.Number}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.forceUpdate()}ValueChanged(i){}GetContainer(i){document.getElementsByTagName("body")}}class O extends ${GetComparator(){return i.html` <select value=${this.Condition.ConditionLineOptions.Comparison} @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: 28px;padding: 0;width: 100%"> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.Equal}" value=${t.ComparisonTypeEnum.Equal}>${RNTranslate("Equal to")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.NotEqual}" value=${t.ComparisonTypeEnum.NotEqual}>${RNTranslate("Not Equal to")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.GreaterThan}" value=${t.ComparisonTypeEnum.GreaterThan}>${RNTranslate("Greater than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.GreaterOrEqualThan}" value=${t.ComparisonTypeEnum.GreaterOrEqualThan}>${RNTranslate("Greater or equal than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.LessThan}" value=${t.ComparisonTypeEnum.LessThan}>${RNTranslate("Less than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.LessOrEqualThan}" value=${t.ComparisonTypeEnum.LessOrEqualThan}>${RNTranslate("Less or equal than")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsEmpty}" value=${t.ComparisonTypeEnum.IsEmpty}>${RNTranslate("Is Empty")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotEmpty}" value=${t.ComparisonTypeEnum.IsNotEmpty}>${RNTranslate("Is Not Empty")}</option> </select> `}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.Equal,this.Condition.ConditionLineOptions.Value=m.Sanitizer.SanitizeNumber(this.Condition.ConditionLineOptions.Value,"")}GetValueInput(){let n=this.Condition.ConditionLineOptions.Comparison;return i.html`<input type="number" value=${u.live(this.Condition.ConditionLineOptions.Value)} @change=${i=>this.ValueChanged(i.target.value)} style="min-width: 100%;max-width: 200px;height: 30px;width: 100%;" type='text' ?disabled=${n==t.ComparisonTypeEnum.IsEmpty||n==t.ComparisonTypeEnum.IsNotEmpty}/>`}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.forceUpdate()}ValueChanged(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}}class I extends ${GetComparator(){return i.html` <select value=${this.Condition.ConditionLineOptions.Comparison} @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: auto;padding: 0;margin-left: 5px;width:100%;"> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsChecked}" .value=${t.ComparisonTypeEnum.IsChecked}>${RNTranslate("Is Checked")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotChecked}" .value=${t.ComparisonTypeEnum.IsNotChecked}>${RNTranslate("Is Not Checked")}</option> </select> `}GetValueInput(){return null}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.forceUpdate()}ValueChanged(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.IsChecked}}class F extends ${GetComparator(){return i.html` <select value=${this.Condition.ConditionLineOptions.Comparison} @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: auto;padding: 0;margin-left: 5px;width: 100%"> <option value=${t.ComparisonTypeEnum.WasClicked}>${RNTranslate("Was Clicked")}</option> </select>`}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.WasClicked}GetValueInput(){return null}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.forceUpdate()}ValueChanged(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}}class G{constructor(){this._loadingUsers=null,this._loadingUsersQueue=[],this._cachedUsers=new Map}static GetInstance(){return null==G._Instance&&(G._Instance=new G),G._Instance}async GetUsersById(i){return this.MaybeLoadUsers(i)}GetUserList(){return Array.from(this._cachedUsers.values())}async MaybeLoadUsers(i){let n=[];for(let e of i)this._cachedUsers.has(e)||n.push(e);if(0==n.length)return!0;let e=await C.WpAjaxPost.Post("load_users_by_id",{Ids:n},"",null,{Prefix:rnConditionDesignerVar._prefix,Nonce:rnConditionDesignerVar._nonce});if(null==e)return!1;for(let i of e)this.AddUser(i.Value,i.Label);return i.forEach((i=>{this._cachedUsers.has(i)||this.AddUser(i,"Unknown")})),!0}HasAllUsers(i){for(let n of i)if(!this._cachedUsers.has(n))return!1;return!0}AddUser(i,n){this._cachedUsers.has(i)||this._cachedUsers.set(i,{Value:i,Label:n})}}class N extends ${constructor(){super(),this.LoadUsers=this.LoadUsers.bind(this)}GetComparator(){return i.html` <select .value="${this.Condition.ConditionLineOptions.Comparison}" @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: 28px;padding: 0;width: 100%;"> <!-- <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.Is}" .value="${t.ComparisonTypeEnum.Is}">${RNTranslate("Is")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNot}" .value="${t.ComparisonTypeEnum.IsNot}">${RNTranslate("Is Not")}</option>--> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsPartOfRole}" .value="${t.ComparisonTypeEnum.IsPartOfRole}">${RNTranslate("Is part of role")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotPartOfRole}" .value="${t.ComparisonTypeEnum.IsNotPartOfRole}">${RNTranslate("Is not part of role")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsGuest}" .value="${t.ComparisonTypeEnum.IsGuest}">${RNTranslate("Is guest")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotGuest}" .value="${t.ComparisonTypeEnum.IsNotGuest}">${RNTranslate("Is not guest")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.ViewingPage}" .value="${t.ComparisonTypeEnum.ViewingPage}">${RNTranslate("User viewing the page")}</option> </select> `}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.Is,this.Condition.ConditionLineOptions.Value=[]}GetValueInput(){return[t.ComparisonTypeEnum.ViewingPage,t.ComparisonTypeEnum.IsGuest,t.ComparisonTypeEnum.IsNotGuest].indexOf(this.Condition.ConditionLineOptions.Comparison)>=0?null:i.html` <rn-inputs-select style="min-height: 30px" multiple .value="${this.Condition.ConditionLineOptions.Value}" @change="${i=>this.OnChange(i.detail)}" .propertyName="${"Type"}" .options="${rnConditionDesignerVar.Roles.map((i=>({Label:i.Label,Value:i.Id})))}"></rn-inputs-select> `}async LoadUsers(i,n){let e=await C.WpAjaxPost.Post("list_users",{query:i},"",null,{Nonce:rnConditionDesignerVar._nonce,Prefix:rnConditionDesignerVar._prefix});if(null!=e)for(let i of e)G.GetInstance().AddUser(i.Value,i.Label);n(e)}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.ConditionLineOptions.Value=[],this.Condition.forceUpdate()}ValueChanged(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}GetAvailableOptions(){let i=[];for(let n of rednaoFormDesigner.Roles)i.push({label:n.Label,value:n.Id});return i}OnChange(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}GetUserControl(){let n=m.Sanitizer.SanitizeStringArray(this.Condition.ConditionLineOptions.Value);return G.GetInstance().HasAllUsers(n)?i.html` <rn-inputs-select .load="${this.LoadUsers}" style="min-height: 30px" multiple .value="${this.Condition.ConditionLineOptions.Value}" @change="${i=>this.OnChange(i.detail)}" .options="${G.GetInstance().GetUserList()}" .propertyName="${"Type"}"></rn-inputs-select> `:(G.GetInstance().GetUsersById(n).then((i=>this.Condition.forceUpdate())),i.html`<span>Loading users</span>`)}}class b extends ${GetComparator(){return i.html` <select value=${this.Condition.ConditionLineOptions.Comparison} @change=${i=>this.ComparisonChanged(i.target.value.toString())} style="height: 28px;padding: 0;width: 100%"> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.Equal}" .value=${t.ComparisonTypeEnum.Equal}>${RNTranslate("Equal to")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.NotEqual}" .value=${t.ComparisonTypeEnum.NotEqual}>${RNTranslate("Not Equal to")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsEmpty}" .value=${t.ComparisonTypeEnum.IsEmpty}>${RNTranslate("Is Empty")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.IsNotEmpty}" .value=${t.ComparisonTypeEnum.IsNotEmpty}>${RNTranslate("Is Not Empty")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.Contains}" .value=${t.ComparisonTypeEnum.Contains}>${RNTranslate("Contains")}</option> <option ?selected="${this.Condition.ConditionLineOptions.Comparison==t.ComparisonTypeEnum.NotContains}" .value=${t.ComparisonTypeEnum.NotContains}>${RNTranslate("Not Contains")}</option> </select> `}InitializeValues(){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.Equal}GetValueInput(){let n=this.Condition.ConditionLineOptions.Comparison,e=n==t.ComparisonTypeEnum.IsEmpty||n==t.ComparisonTypeEnum.IsNotEmpty;return i.html` <div style="width: 100%"> ${p.rnIf(!e&&this.GetFormulaPlaceHolder())} <input value=${u.live(this.Condition.ConditionLineOptions.Value)} @change=${i=>this.ValueChanged(i.target.value)} style="min-width: 100%;max-width: 200px;height: 30px;width: 100%;padding-right: 20px;" type='text' ?disabled=${e}/> </div> `}ComparisonChanged(i){this.Condition.ConditionLineOptions.Comparison=t.ComparisonTypeEnum[i],this.Condition.forceUpdate()}ValueChanged(i){this.Condition.ConditionLineOptions.Value=i,this.Condition.forceUpdate()}}class x{static GetComparator(i,n){switch(i.ConditionLineOptions.Type){case t.ConditionLineTypeEnum.None:return new v;case t.ConditionLineTypeEnum.Entry:case t.ConditionLineTypeEnum.Field:case t.ConditionLineTypeEnum.User:return i.ConditionLineOptions.SubType==t.SubTypeEnum.MultipleValues?(new L).Initialize(i,n):i.ConditionLineOptions.SubType==t.SubTypeEnum.Date?(new f).Initialize(i,n):i.ConditionLineOptions.SubType==t.SubTypeEnum.Numeric?(new O).Initialize(i,n):i.ConditionLineOptions.SubType==t.SubTypeEnum.Checkbox?(new I).Initialize(i,n):i.ConditionLineOptions.SubType==t.SubTypeEnum.Button?(new F).Initialize(i,n):(i.ConditionLineOptions.SubType,t.SubTypeEnum.Status,i.ConditionLineOptions.SubType==t.SubTypeEnum.Role?(new N).Initialize(i,n):(new b).Initialize(i,n));case t.ConditionLineTypeEnum.Variation:return(new L).Initialize(i,n)}return new v}}let R=e.customElement("rn-condition-line")(T=class extends n.LitElementBase{constructor(...i){super(...i),this.FieldsToIgnore=[s.FieldTypeEnum.Signature,s.FieldTypeEnum.TermOfService,s.FieldTypeEnum.TextualImage,s.FieldTypeEnum.FileUpload],this.EntryGroup=[{Id:"_sequence",Label:"Entry Number",Type:t.SubTypeEnum.Numeric},{Id:"_creation_date",Label:"Creation Date",Type:t.SubTypeEnum.Date},{Id:"_submitted_by",Label:"Submitted By",Type:t.SubTypeEnum.Role}]}static get properties(){return{}}render(){var n,e,o,s;let a=[...this.GetParentFields(this.ConditionGroup.ConditionBuilder.FormBuilder),...this.GetFields(this.ConditionGroup.ConditionBuilder.FormBuilder)],r=x.GetComparator(this,this.model),l=r.GetValueInput();return i.html` <tr style="display: flex;align-items: flex-start;margin-bottom: 5px;"> <td style="width: 30%"> <select .value="${u.live(this.ConditionLineOptions.FieldId+(""==this.ConditionLineOptions.PathId?"":"_"+this.ConditionLineOptions.PathId))}" style="height: 28px;padding: 0;width: 100%" @change=${i=>this.FieldChanged(i.target[i.target.selectedIndex].getAttribute("data-type").toString(),i.target[i.target.selectedIndex].getAttribute("data-field-id").toString(),i.target[i.target.selectedIndex].getAttribute("data-path-id").toString(),i.target[i.target.selectedIndex])}> <option data-linetype=${t.ConditionLineTypeEnum.None.toString()} id=''>${RNTranslate("Select a field")}</option> ${p.rnIf((null===(n=this.ConditionGroup.ConditionBuilder)||void 0===n||null===(e=n.model)||void 0===e?void 0:e.GetIncludeUserInformationFields())&&i.html` <optgroup label="User Information"> <option data-type="${t.ConditionLineTypeEnum.User}" ?selected="${"_role"==this.ConditionLineOptions.FieldId&&this.ConditionLineOptions.Type==t.ConditionLineTypeEnum.User}" .value="${"_role"}" data-path-id="" data-field-id="${"_role"}" data-subtype="${t.SubTypeEnum.Role}" >User</option> </optgroup> `)} ${p.rnIf((null===(o=this.ConditionGroup.ConditionBuilder)||void 0===o||null===(s=o.model)||void 0===s?void 0:s.GetIncludeEntryInformation())&&i.html` <optgroup label="Entry"> ${this.EntryGroup.map((n=>i.html` <option data-type="${t.ConditionLineTypeEnum.Entry}" ?selected="${n.Id==this.ConditionLineOptions.FieldId&&this.ConditionLineOptions.Type==t.ConditionLineTypeEnum.Entry}" data-field-id="${n.Id}" data-path-id="" data-subtype="${n.Type}" .value="${n.Id}">${n.Label}</option> `))} </optgroup> `)} <optgroup label=${RNTranslate("Fields")}> ${this.GetSelectOptions(a,0)} </optgroup> </select> </td> <td style="width:${null==l?"calc(70% - 80px)":"20%"}"> ${r.GetComparator()} </td> ${p.rnIf(null!=l&&i.html` <td style="width: calc(50% - 80px);position: relative"> ${r.GetFormulaOrValueInput()} </td> `)} <td style="width: 80px"> <button @click=${i=>{i.preventDefault(),this.FireEvent("addline",this.ConditionLineOptions)}} class='rnbtn rnbtn-light' style="margin-left: 5px;padding: 1px 5px;">${RNTranslate("AND")}</button> <span style="line-height: 25px;vertical-align: middle;cursor: pointer;" @click=${i=>{i.preventDefault(),this.FireEvent("removeline",this.ConditionLineOptions)}} class="rnline"><rn-fontawesome .icon=${g.faTimes} style="margin-left: 5px"/></span> </td> </tr> `}GetSelectOptions(n,e){let o=this.ConditionLineOptions.FieldId+(""==this.ConditionLineOptions.PathId?"":"_"+this.ConditionLineOptions.PathId);return(n=n.filter((i=>i.GetStoresInformation()&&i.UsedInConditions)).filter((i=>i.Options.Type!=s.FieldTypeEnum.None)).sort(((i,n)=>i.Options.Label.localeCompare(n.Options.Label)))).filter((i=>{var n;return i.Options.Id.toString()!=(null===(n=this.ConditionGroup.ConditionBuilder.FieldToHide)||void 0===n?void 0:n.Options.Id.toString())})).map((n=>i.html` <option data-type="${t.ConditionLineTypeEnum.Field}" ?selected="${n.Options.Id.toString()==o}" ?disabled="${this.ShouldIncludeSubFields(n)}" data-field-id="${n.Options.Id}" data-path-id="" .value="${n.Options.Id.toString()}">${this.GetSpaces(e)}${n.Options.Label+(""!=n.Options.Label.trim()?" ":"")+"(Id: "+n.Options.Id+")"}</option> ${p.rnIf(this.GetSubSections(n).length>0&&this.GetSubSections(n).map((s=>i.html` <option data-type="${t.ConditionLineTypeEnum.Field}" ?selected="${n.Options.Id+"_"+s.PathId==o}" data-field-id="${n.Options.Id}" data-path-id="${s.PathId}" .value="${n.Options.Id+"_"+s.PathId}">${this.GetSpaces(e+1)}${s.Label}</option> `)))} ${p.rnIf(this.ShouldIncludeSubFields(n)&&this.GetSelectOptions(this.GetFields(n),e+1))} `))}ShouldIncludeSubFields(i){var n;return!!i.IsFieldContainer&&(i.Options.Type!=s.FieldTypeEnum.Repeater||!1!==(null===(n=this.model)||void 0===n?void 0:n.GetIncludeRepeaterFields()))}GetSubSections(i){var n;return i.Options.Type==s.FieldTypeEnum.Repeater&&!1===(null===(n=this.model)||void 0===n?void 0:n.GetIncludeRepeaterFields())?[]:i.GetSubSections()}GetSpaces(n){let e=[];for(let t=0;t<n;t++)e.push(i.html`&nbsp;&nbsp;&nbsp;`);return e}FieldChanged(i,n,e,o){let a=t.SubTypeEnum.Standard;if(this.ConditionLineOptions.Value="",this.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.None,i==t.ConditionLineTypeEnum.Field){var r;let i=null===(r=this.ConditionGroup.ConditionBuilder.FormBuilder.GetFieldById(n,!0,!0))||void 0===r?void 0:r.Options;if(null==i)return;null!=i&&[s.FieldTypeEnum.Checkbox,s.FieldTypeEnum.Radio,s.FieldTypeEnum.DropDown,s.FieldTypeEnum.ButtonSelection].indexOf(i.Type)>=0&&(a=t.SubTypeEnum.MultipleValues,this.ConditionLineOptions.Value=[]),null!=i&&[s.FieldTypeEnum.GoogleMaps].indexOf(i.Type)>=0&&(a="GoogleMaps",this.ConditionLineOptions.Value=[]),null!=i&&[s.FieldTypeEnum.Slider,s.FieldTypeEnum.Numeric].indexOf(i.Type)>=0&&(a=t.SubTypeEnum.Numeric,this.ConditionLineOptions.Value=[]),null!=i&&[s.FieldTypeEnum.Datepicker,s.FieldTypeEnum.DateRange].indexOf(i.Type)>=0&&(a=t.SubTypeEnum.Date,this.ConditionLineOptions.Value=[]),null!=i&&[s.FieldTypeEnum.List].indexOf(i.Type)>=0&&(a=t.SubTypeEnum.List,this.ConditionLineOptions.Value=""),null!=i&&[s.FieldTypeEnum.Switch].indexOf(i.Type)>=0&&(a=t.SubTypeEnum.Checkbox,this.ConditionLineOptions.Value=[]),null!=i&&[s.FieldTypeEnum.ActionButton].indexOf(i.Type)>=0&&(a=t.SubTypeEnum.Button,this.ConditionLineOptions.Value=[])}i!=t.ConditionLineTypeEnum.Entry&&i!=t.ConditionLineTypeEnum.User||(a=o.getAttribute("data-subtype")),this.ConditionLineOptions.Formula=null,this.ConditionLineOptions.FieldId=n,this.ConditionLineOptions.PathId=e,this.ConditionLineOptions.Comparison=t.ComparisonTypeEnum.None,this.ConditionLineOptions.Type=i,this.ConditionLineOptions.SubType=a,this.FireEvent("fieldchanged",null,!0),this.forceUpdate()}GetFields(i){var n,e;i.IsFieldContainer&&i.Options.Type==s.FieldTypeEnum.Repeater&&(i=null===(n=i.Rows[0])||void 0===n||null===(e=n.Columns[0])||void 0===e?void 0:e.Field);return null==i?[]:i.GetFields(!1,!1,!1).filter((i=>i.GetStoresInformation()&&this.FieldsToIgnore.indexOf(i.Options.Type)<0))}GetParentFields(i){if(null==i||null==i.FormBuilder)return[];let n=[...this.FieldsToIgnore,s.FieldTypeEnum.RepeaterItem,s.FieldTypeEnum.Repeater],e=i.FormBuilder,t=e.GetFields(!1,!1,!1).filter((e=>e.GetStoresInformation()&&n.indexOf(e.Options.Type)<0&&e!=i&&(null==e.IsFieldContainer||0==e.IsFieldContainer)));return[...this.GetParentFields(e),...t]}})||T;exports.ConditionBuilderModel=class{constructor(){this._roles=null,this._userNonce="",this._includeRepeaterFields=!0,this._includeUserInformationFields=!1,this._includeEntryInformation=!0,this._supportFormulas=!0,this._supportFormulaPanel=!1}render(n,e,t=null,o=null){return this.Form=n,i.html` <rn-condition-builder @AllGroupsRemoved=${i=>null!=t&&t()} .model="${this}" .FormBuilder="${n}" .Condition="${e}" .FieldToHide=${o} ></rn-condition-builder> `}get SupportFormulas(){return this._supportFormulas}get SupportFormulaPanel(){return this._supportFormulaPanel}SetSupportFormulas(i=!0){return this._supportFormulas=i,this}SetSupportFormulaPanel(i){this._supportFormulaPanel=i}GetFormulaFormBuilder(){return null!=this.FormulaFormBuilder?this.FormulaFormBuilder:this.Form}GetIncludeRepeaterFields(){return this._includeRepeaterFields}SetIncludeRepeaterFields(i){this._includeRepeaterFields=i}SetIncludeUserInformationFields(i){this._includeUserInformationFields=i}SetIncludeEntryInformation(i=!0){return this._includeEntryInformation=i}GetIncludeEntryInformation(){return this._includeEntryInformation}GetIncludeUserInformationFields(){return this._includeUserInformationFields}},exports.ConditionGroup=E,exports.ConditionLine=R,exports.ConditionGroupBase=class{GetOptionById(i){return this.GetOptions().find((n=>n.Id==i))}},exports.UserCache=G}));
