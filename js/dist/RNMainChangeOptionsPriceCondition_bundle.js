rndefine("#RNMainChangeOptionsPriceCondition",["#RNMainFormBuilderCore/ConditionBase.Options","#RNMainCore/EventManager","#RNMainFormBuilderCore/FieldConditionProcessorBase","#RNMainFormBuilderCore/ConditionProcessorBase"],(function(e,i,n,t){"use strict";class o extends e.ConditionBaseOptions{constructor(...e){super(...e),this.PriceName=""}LoadDefaultValues(){super.LoadDefaultValues(),this.Type="ChangeOptionsPrice",this.PriceName="Price"}}class r extends n.FieldConditionProcessorBase{GetExecutionOrder(){return t.ExecutionOrder.FalseFirst}ExecuteFalseAction(e){this.Field.SetPriceAttribute(null)}ExecuteTruAction(e){for(let e of this.Field.Options.AdditionalOptionColumn){var i;(null===(i=e.Options)||void 0===i?void 0:i.Id)==this.Condition.Id&&this.Field.SetPriceAttribute(e.Id)}}}exports.ChangeOptionsPriceConditionOptions=o,i.EventManager.Subscribe("GetCondition",(e=>{if("ChangeOptionsPrice"==e.Type)return new o})),i.EventManager.Subscribe("GetConditionProcessor",(e=>{if("ChangeOptionsPrice"==e.Type)return new r}))}));
