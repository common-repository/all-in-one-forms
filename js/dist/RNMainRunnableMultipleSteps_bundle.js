rndefine("#RNMainRunnableMultipleSteps",["#RNMainCore/LitElementBase","lit/decorators","lit","#RNMainLit/Lit","#RNMainCore/SingleEvent","#RNMainFormBuilderCore/FormBuilder.Model","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/ConditionProcessorBase","#RNMainCore/EventManager","#RNMainFormBuilderCore/ConditionBase.Options"],(function(e,t,i,s,n,r,l,o,d,p){"use strict";var a;let h=t.customElement("rn-multiple-steps")(a=class extends e.LitElementBase{render(){return i.html` <div class="multipleStepsContainer" style="border:1px solid #dfdfdf;padding:5px;"> ${this.model.HeaderModel.Render()} ${this.model.GetEmptySectionContainer()} <div class="fieldAndImageContainer ${this.model.StepOptions.ImagePosition}"> ${s.rnIf(this.model.StepOptions.IncludeStepImage&&i.html` <rness-step-image .model="${this.model}"></rness-step-image> `)} <div style="width: 100%"> ${this.model.FormBuilderModel.RenderRows(this.model.GetCurrentStepRows())} <rn-multiple-step-footer .model="${this.model}"></rn-multiple-step-footer> </div> </div> </div> `}ShowTotal(){return 0}})||a;class S{GenerateItem(e,t){return t}GenerateContainer(e){return e}}class u extends e.LitElementBase{connectedCallback(){super.connectedCallback(),this.model.OnRefresh.Subscribe(this,(()=>this.forceUpdate()))}disconnectedCallback(){super.disconnectedCallback(),this.model.OnRefresh.Unsubscribe(this)}render(){return null}}t.customElement("rn-label-renderer")(class extends u{render(){return this.behavior.GenerateContainer(i.html` <ul class="steps" style="width: 100%;display: flex;flex-wrap: wrap;align-items: center;justify-content: center;list-style: none;margin: 0;padding: 0;"> ${this.model.AvailableSteps.map(((e,t)=>this.behavior.GenerateItem(e,i.html` <li @click=${()=>this.stepSelected(e)} style="display: flex;align-items: center;" class=${"rnStepLabels "+(t<this.model.StepModel.CurrentStepIndex?"rnValidated ":"")+this.model.GetStepClasses(e)}> ${s.rnIf("none"!=e.Icon.ImageType&&i.html`<rn-icon-displayer .formBuilder="${this.model.StepModel.FormBuilderModel}" style="width:auto;margin-right:3px;color:inherit;" .icon=${e.Icon}></rn-icon-displayer>`)} <span>${e.Title}</span></li>`)))} </ul> `)}}),t.customElement("rn-progress-renderer")(class extends u{render(){let e=100,t=this.model.StepModel.CurrentStep;return this.model.StepOptions.Steps.length>0&&(e=Math.ceil(100*(this.model.StepModel.CurrentStepIndex+1)/this.model.StepModel.StepOptions.Steps.length)),this.behavior.GenerateContainer(i.html` <div style="width: 100%;margin-bottom: 5px;"> ${this.behavior.GenerateItem(t,i.html` <div> ${s.rnIf("none"!=t.Icon.ImageType&&i.html`<rn-icon-displayer .formBuilder="${this.model.StepModel.FormBuilderModel}" style="width:auto;margin-right:3px;color:inherit;" .icon=${t.Icon}></rn-icon-displayer>`)} <span class="ProgressLabel">${t.Title}</span> </div> `)} <div style="width: 100%;display: flex;flex-wrap: wrap;align-items: center;justify-content: center;list-style: none;margin: 0;padding: 0;position:relative;" class="msProgressBar"> <div class="msProgressCompleted" style="position: absolute;top:0;left: 0;width: ${e}%"> </div> </div> </div> `)}});class m{constructor(){this.OnRefresh=new n.SingleEvent}async Initialize(e){this.StepModel=e,this.InitializeBehavior(),await this.GetHeaderRenderer()}get AvailableSteps(){return this.StepModel.AvailableSteps}InitializeBehavior(){this.Behavior=new S}get StepOptions(){return this.StepModel.StepOptions}GetStepClasses(e){let t=[],i=this.StepModel.CurrentStep,s=this.StepModel.CurrentStepIndex,n=this.StepModel.StepOptions.Steps.indexOf(e);return t.push("rnStep"),i==e&&t.push("rnActive"),s>n&&t.push("rnVisited"),t.join(" ")}Refresh(){this.OnRefresh.Publish()}Render(){return i.html` <div> ${"Tabs"==this.StepModel.HeaderModel.StepOptions.Style?i.html` <rn-label-renderer .model="${this}" .behavior="${this.Behavior}" .stepSelected="${e=>{this.StepModel.GoToStep(e)}}"></rn-label-renderer> `:i.html` <rn-progress-renderer .model="${this}" .behavior="${this.Behavior}" .stepSelected="${e=>{this.StepModel.GoToStep(e)}}"></rn-progress-renderer> `} </div> `}async GetHeaderRenderer(){}}class c extends o.ConditionProcessorBase{ExecuteTruAction(e){this.Condition.ShowWhenTrue?this.Model.ShowStep(this.Step.Id):this.Model.HideStep(this.Step.Id)}ExecuteFalseAction(e){this.Condition.ShowWhenTrue?this.Model.HideStep(this.Step.Id):this.Model.ShowStep(this.Step.Id)}async InitializeWithStep(e,t,i,s){return this.Model=e,this.Step=t,super.Initialize(i,s)}}class f{constructor(e){this.FormBuilderModel=e,this.Initialized=!1,this.HiddenSteps=[],this.Refresh=new n.SingleEvent}IsStepHidden(e){return-1!=this.HiddenSteps.indexOf(e)}get AvailableSteps(){return this.FormBuilderModel.IsDesign?this.StepOptions.Steps:this.StepOptions.Steps.filter((e=>-1==this.HiddenSteps.indexOf(e.Id)))}get StepOptions(){return this.FormBuilderModel.Options.ClientOptions.MultipleSteps}GetCurrentStepId(){return this.CurrentStep.Id}async Initialize(){await this.MaybeInitializeConditions(),this.CurrentStep=this.StepOptions.Steps[0],this.InitializeHeader(),await this.HeaderModel.Initialize(this),this.Initialized=!0,this.FormBuilderModel.Refresh()}get CurrentStepIndex(){return this.StepOptions.Steps.indexOf(this.CurrentStep)}RenderFormContainer(){return this.Initialized?i.html` <rn-multiple-steps .model=${this} ></rn-multiple-steps> `:null}GetCurrentStepRows(){return this.GetRowsOfField(this.CurrentStep)}GetRowsOfField(e){return this.FormBuilderModel.Rows.filter((t=>t.Options.StepId==e.Id&&t.Columns[0].Field.Options.Type!=l.FieldTypeEnum.SubmitButton))}GetFieldsOfStep(e){let t=this.GetRowsOfField(e),i=[];return t.forEach((e=>e.Columns.forEach((e=>i.push(e.Field))))),i}InitializeHeader(){this.HeaderModel=new m}async GoToStep(e,t=!0,i=null){let s=this.StepOptions.Steps.indexOf(e);for(let e=0;e<s;e++)if(-1==this.HiddenSteps.indexOf(this.StepOptions.Steps[e].Id)&&!await this.ValidateStep(this.StepOptions.Steps[e]))return;this.CurrentStep=e,this.FormBuilderModel.Refresh((()=>{t&&this.ScrollToFirstFieldOfStep(),null!=i&&i()}))}GetEmptySectionContainer(){return null}GetFieldOffset(e){let t=0;for(let i of this.FormBuilderModel.Rows){if(i.Options.StepId==e)break;t++}return t}RemoveStep(e){let t=this.CurrentStepIndex;this.StepOptions.Steps.splice(this.StepOptions.Steps.indexOf(e),1);for(let t=0;t<this.FormBuilderModel.Rows.length;t++)this.FormBuilderModel.Rows[t].Options.StepId==e.Id&&(this.FormBuilderModel.Rows.splice(t,1),t--);this.CurrentStep==e&&(t--,this.CurrentStep=this.StepOptions.Steps[Math.max(0,t)]),this.FormBuilderModel.Refresh()}async ValidateStep(e){let t=this.GetFieldsOfStep(e),i=null;for(let e of t)await e.Validate()||null==i&&(i=e);if(null!=i)if(i.Parent.Parent.Options.StepId!=this.CurrentStep.Id){let e=this.StepOptions.Steps.find((e=>e.Id==i.Parent.Parent.Options.StepId));this.GoToStep(e,!1,(()=>i.ScrollToField()))}else i.ScrollToField();return null==i}GetUsedIcons(){return[]}async Submit(){await this.FormBuilderModel.Save()}ScrollToFirstFieldOfStep(){let e=this.GetFieldsOfStep(this.CurrentStep);e.length>0&&e[0].ScrollToField()}async MaybeInitializeConditions(){for(let e of this.StepOptions.Steps){let t=e.Conditions.find((e=>"ShowHideStep"==e.Type));null!=t&&(this.FormBuilderModel.IsDesign||await(new c).InitializeWithStep(this,e,this.FormBuilderModel,t))}}ShowStep(e){-1!=this.HiddenSteps.indexOf(e)&&(this.HiddenSteps.splice(this.HiddenSteps.indexOf(e),1),this.FormBuilderModel.Refresh())}HideStep(e){-1==this.HiddenSteps.indexOf(e)&&(this.HiddenSteps.push(e),this.FormBuilderModel.Refresh())}}var v={};!function(e){Object.defineProperty(e,"__esModule",{value:!0});var t="arrow-left",i=[],s="f060",n="M257.5 445.1l-22.2 22.2c-9.4 9.4-24.6 9.4-33.9 0L7 273c-9.4-9.4-9.4-24.6 0-33.9L201.4 44.7c9.4-9.4 24.6-9.4 33.9 0l22.2 22.2c9.5 9.5 9.3 25-.4 34.3L136.6 216H424c13.3 0 24 10.7 24 24v32c0 13.3-10.7 24-24 24H136.6l120.5 114.8c9.8 9.3 10 24.8.4 34.3z";e.definition={prefix:"fas",iconName:t,icon:[448,512,i,s,n]},e.faArrowLeft=e.definition,e.prefix="fas",e.iconName=t,e.width=448,e.height=512,e.ligatures=i,e.unicode=s,e.svgPathData=n}(v);var M={};!function(e){Object.defineProperty(e,"__esModule",{value:!0});var t="arrow-right",i=[],s="f061",n="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z";e.definition={prefix:"fas",iconName:t,icon:[448,512,i,s,n]},e.faArrowRight=e.definition,e.prefix="fas",e.iconName=t,e.width=448,e.height=512,e.ligatures=i,e.unicode=s,e.svgPathData=n}(M);var b,O={};!function(e){Object.defineProperty(e,"__esModule",{value:!0});var t="paper-plane",i=[],s="f1d8",n="M476 3.2L12.5 270.6c-18.1 10.4-15.8 35.6 2.2 43.2L121 358.4l287.3-253.2c5.5-4.9 13.3 2.6 8.6 8.3L176 407v80.5c0 23.6 28.5 32.9 42.5 15.8L282 426l124.6 52.2c14.2 6 30.4-2.9 33-18.2l72-432C515 7.8 493.3-6.8 476 3.2z";e.definition={prefix:"fas",iconName:t,icon:[512,512,i,s,n]},e.faPaperPlane=e.definition,e.prefix="fas",e.iconName=t,e.width=512,e.height=512,e.ligatures=i,e.unicode=s,e.svgPathData=n}(O);let g=t.customElement("rn-multiple-step-footer")(b=class extends e.LitElementBase{constructor(...e){super(...e),this.IsBusy=!1}static get properties(){return{IsBusy:{type:Boolean}}}render(){let e=this.model.AvailableSteps.length>0&&this.model.AvailableSteps[this.model.AvailableSteps.length-1]==this.model.CurrentStep;return i.html` <div class='rnFooterContainer' style="display: flex;align-items: center;justify-content: center"> <button ?disabled=${this.model.AvailableSteps[0]==this.model.CurrentStep} @click=${e=>{e.preventDefault(),this.Previous()}} class='rnbtn rnbtn-default' style="margin-right: 5px;display: flex;align-items: center;"> <rn-fontawesome .icon=${v.faArrowLeft} style="margin-right: 5px;"></rn-fontawesome> ${this.model.StepOptions.PreviousButtonText}</button> <rn-spinner-button .label="${e?this.model.StepOptions.SubmitButtonText:this.model.StepOptions.NextButtonText}" .icon=${e?O.faPaperPlane:M.faArrowRight} .isBusy=${this.IsBusy} @click=${()=>{this.Next()}} class='rnbtn rnbtn-default' style="margin-left: 5px;color: black;display: flex;align-items: center;"></rn-spinner-button> </div> `}async Next(){if(this.model.AvailableSteps.length>0&&this.model.AvailableSteps[this.model.AvailableSteps.length-1]==this.model.CurrentStep){let e=this.model.StepOptions.Steps.indexOf(this.model.CurrentStep);for(let t=0;t<e;t++)if(-1==this.model.HiddenSteps.indexOf(this.model.StepOptions.Steps[t].Id)&&!await this.model.ValidateStep(this.model.StepOptions.Steps[t]))return;this.IsBusy=!0,await this.model.Submit(),this.IsBusy=!1}else{let e=this.model.AvailableSteps.indexOf(this.model.CurrentStep);this.model.GoToStep(this.model.AvailableSteps[e+1])}}Previous(){if(this.model.AvailableSteps[0]==this.model.CurrentStep)return;let e=this.model.AvailableSteps.indexOf(this.model.CurrentStep);this.model.GoToStep(this.model.AvailableSteps[e-1])}})||b;class C extends p.ConditionBaseOptions{LoadDefaultValues(){super.LoadDefaultValues(),this.Type="ShowHideStep",this.ShowWhenTrue=!0}}d.EventManager.Subscribe("GetMultipleStepsModel",(e=>new f(e.Model))),exports.MultipleSteps=h,exports.MultipleStepsModel=f,exports.MultipleStepsHeaderModel=class{constructor(){this.OnRefresh=new n.SingleEvent}async Initialize(e){this.StepModel=e,this.InitializeBehavior(),await this.GetHeaderRenderer()}get AvailableSteps(){return this.StepModel.AvailableSteps}InitializeBehavior(){this.Behavior=new S}get StepOptions(){return this.StepModel.StepOptions}GetStepClasses(e){let t=[],i=this.StepModel.CurrentStep,s=this.StepModel.CurrentStepIndex,n=this.StepModel.StepOptions.Steps.indexOf(e);return t.push("rnStep"),i==e&&t.push("rnActive"),s>n&&t.push("rnVisited"),t.join(" ")}Refresh(){this.OnRefresh.Publish()}Render(){return i.html` <div> ${"Tabs"==this.StepModel.HeaderModel.StepOptions.Style?i.html` <rn-label-renderer .model="${this}" .behavior="${this.Behavior}" .stepSelected="${e=>{this.StepModel.GoToStep(e)}}"></rn-label-renderer> `:i.html` <rn-progress-renderer .model="${this}" .behavior="${this.Behavior}" .stepSelected="${e=>{this.StepModel.GoToStep(e)}}"></rn-progress-renderer> `} </div> `}async GetHeaderRenderer(){}},exports.RendererBehaviorBase=S,exports.MultipleStepFooter=g,exports.ShowHideStepConditionOptions=C,d.EventManager.Subscribe("GetCondition",(e=>{if("ShowHideStep"==e.Type)return new C})),d.EventManager.Subscribe("GetConditionProcessor",(e=>{if("ShowHideStep"==e.Type)return new c}))}));
