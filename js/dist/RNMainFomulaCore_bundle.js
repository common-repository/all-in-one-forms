rndefine('#RNMainFomulaCore', ['#RNMainCore/EventManager', '#RNMainFormBuilderCore/FieldBase.Model', '#RNMainFormBuilderCore/MultipleOptionsBase.Model', '#RNMainFormBuilderCore/PreferredReturnType'], function (EventManager, FieldBase_Model, MultipleOptionsBase_Model, PreferredReturnType) { 'use strict';

    class ParserElementBase {
      constructor(Parent, Data) {
        this.Parent = Parent;
        this.Data = Data;
      }

      GetMain() {
        if (this.Parent == null) return this;
        return this.Parent.GetMain();
      }

    }

    class ParserNumber extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
      }

      Parse() {
        return parseFloat(this.Data.d);
      }

    }

    class ParserBoolean extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
      }

      Parse() {
        return this.Data.Value;
      }

    }

    class ParserString extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
      }

      Parse() {
        return this.Data.Text;
      }

    }

    class ParserElementThatUsesFieldsBase$1 extends ParserElementBase {
      GetPriceFromField(field) {
        if (this.GetMain().Owner == field) return field.GetPriceWithoutFormula();
        return field.GetPrice();
      }

    }

    class ParseArithmetical extends ParserElementThatUsesFieldsBase$1 {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Left = ParseFactory.GetParseElement(this, this.Data.Left);
        this.Right = ParseFactory.GetParseElement(this, this.Data.Right);
      }

      Parse() {
        switch (this.Data.type) {
          case 'MUL':
            return this.GetScalarOrPrice(this.Left.Parse()) * this.GetScalarOrPrice(this.Right.Parse());

          case 'ADD':
            let left = this.ToScalar(this.Left.Parse());
            let right = this.ToScalar(this.Right.Parse());

            if (left instanceof FieldBase_Model.FieldBaseModel) {
              if (typeof right == 'string') left = left.ToText();else left = this.GetScalarOrPrice(left);
            }

            if (right instanceof FieldBase_Model.FieldBaseModel) {
              if (typeof left == 'string') right = right.ToText();else right = this.GetScalarOrPrice(right);
            }

            return left + right;

          case 'SUB':
            return this.GetScalarOrPrice(this.ToScalar(this.Left.Parse())) - this.GetScalarOrPrice(this.ToScalar(this.Right.Parse()));

          case 'DIV':
            if (this.GetScalarOrPrice(this.ToScalar(this.Right.Parse())) == 0) return 0;
            return this.GetScalarOrPrice(this.ToScalar(this.Left.Parse())) / this.GetScalarOrPrice(this.ToScalar(this.Right.Parse()));
        }
      }

      GetScalarOrPrice(data) {
        if (data instanceof FieldBase_Model.FieldBaseModel) return this.GetPriceFromField(data);
        return data;
      }

      ToScalar(parse) {
        if (Array.isArray(parse)) {
          return parse.reduce((previousValue, currentValue) => previousValue + currentValue, 0);
        }

        return parse;
      }

    }

    class ParseMathFunction extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        if (this.Data.d != null) this.Child = ParseFactory.GetParseElement(this, this.Data.d);
      }

      Parse() {
        switch (this.Data.op) {
          case 'SIN':
            return Math.sin(this.Child.Parse());

          case 'COS':
            return Math.cos(this.Child.Parse());

          case 'TAN':
            return Math.tan(this.Child.Parse());

          case 'ASIN':
            return Math.asin(this.Child.Parse());

          case 'ATAN':
            return Math.atan(this.Child.Parse());

          case 'ACOS':
            return Math.acos(this.Child.Parse());

          case 'SQRT':
            return Math.sqrt(this.Child.Parse());

          case 'LN':
            return Math.log(this.Child.Parse());

          case 'PI':
            return 3.14159265359;

          case 'E':
            return 2.718281828459045;
        }
      }

    }

    class ParseSentence extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Sentence = ParseFactory.GetParseElement(this, this.Data.Sentence);
        if (this.Data.Next != null) this.Next = ParseFactory.GetParseElement(this, this.Data.Next);
      }

      Parse() {
        return this.Sentence.Parse();
      }

    }

    class ParseParenthesis extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Args = [];

        for (let current of this.Data.Args) {
          this.Args.push(ParseFactory.GetParseElement(this, current));
        }
      }

      Parse() {
        if (this.Args.length == 0) return null;
        return this.Args[0].Parse();
      }

    }

    class ParseConditionSentence extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Condition = ParseFactory.GetParseElement(this, Data.Condition);
        this.Result = ParseFactory.GetParseElement(this, Data.Result);
      }

      Parse() {
        if (this.Condition.Parse() === true) return this.Result.Parse();
        return null;
      }

    }

    class ParserElementThatUsesFieldsBase extends ParserElementBase {
      GetPriceFromField(field) {
        if (this.GetMain().Owner == field) return field.GetPriceWithoutFormula();
        return field.GetPrice();
      }

    }

    class ParseComparator extends ParserElementThatUsesFieldsBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Left = ParseFactory.GetParseElement(this, Data.Left);
        this.Right = ParseFactory.GetParseElement(this, Data.Right);
      }

      Parse() {
        let operator = this.Data.operator;
        if (this.Right == null) return this.Left.Parse() == true;
        let originalLeft = this.Left.Parse();
        let originalRight = this.Right.Parse();
        let leftValue = this.Left.Parse();
        let rightValue = this.Right.Parse();

        if (leftValue instanceof FieldBase_Model.FieldBaseModel) {
          if (typeof rightValue == 'string') leftValue = leftValue.ToText();else leftValue = this.GetPriceFromField(leftValue);
        }

        if (rightValue instanceof FieldBase_Model.FieldBaseModel) {
          if (typeof leftValue == 'string') rightValue = rightValue.ToText();else rightValue = this.GetPriceFromField(rightValue);
        }

        switch (operator) {
          case '==':
            return leftValue == rightValue;

          case '!=':
            return leftValue != rightValue;

          case '>':
            return leftValue > rightValue;

          case '>=':
            return leftValue >= rightValue;

          case '<':
            return leftValue <= rightValue;

          case '<=':
            return leftValue <= rightValue;

          case 'contains':
          case 'not contains':
            let haystack = leftValue;
            let needle = rightValue;
            if (originalLeft instanceof MultipleOptionsBase_Model.MultipleOptionsBaseModel) haystack = originalLeft.GetSelectedOptions().map(x => x.Label);
            if (originalRight instanceof MultipleOptionsBase_Model.MultipleOptionsBaseModel) haystack = originalRight.GetSelectedOptions().map(x => x.Label);
            if (!Array.isArray(needle)) needle = [needle];
            if (!Array.isArray(haystack)) haystack = [haystack];

            for (let i = 0; i < haystack.length; i++) {
              if (haystack[i] instanceof FieldBase_Model.FieldBaseModel) haystack[i] = this.GetPriceFromField(haystack[i]);
            }

            for (let i = 0; i < needle.length; i++) {
              if (needle[i] instanceof FieldBase_Model.FieldBaseModel) needle[i] = this.GetPriceFromField(needle[i]);
            }

            if (operator == 'contains') {
              for (let currentNeedle of needle) {
                if (haystack.some(x => x == currentNeedle)) return true;
              }

              return false;
            }

            if (operator == 'not contains') {
              for (let currentNeedle of needle) {
                if (haystack.some(x => x == currentNeedle)) return false;
              }

              return true;
            }

        }
      }

    }

    class ParseCondition extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Operation = Data.Operation;
        this.Comparator = ParseFactory.GetParseElement(this, Data.Comparator);
        this.Next = ParseFactory.GetParseElement(this, Data.Next);
      }

      Parse() {
        let isTrue = this.Comparator.Parse() == true;
        if (this.Next == null) return isTrue;
        let nextIsTrue = this.Next.Parse() == true;
        if (this.Operation == "&&") return isTrue && nextIsTrue;else return isTrue || nextIsTrue;
      }

    }

    class ParseField extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.FieldId = this.Data.Id;
        this.Field = this.GetMain().FieldList.find(x => x.Options.Id == this.FieldId);
      }

      Parse() {
        if (this.Field == null) return 0;
        return this.Field;
      }

    }

    class ParseArray extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Elements = [];

        for (let current of this.Data.Elements) {
          this.Elements.push(ParseFactory.GetParseElement(this, current).Parse());
        }
      }

      Parse() {
        return this.Elements;
      }

    }

    class ParseNegation extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Child = ParseFactory.GetParseElement(this, Data.Child);
      }

      Parse() {
        return !this.Child.Parse();
      }

    }

    class ParseReturn extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Sentence = ParseFactory.GetParseElement(this, Data.Sentence);
      }

      Parse() {
        return this.Sentence.Parse();
      }

    }

    class ParseBlock extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Sentences = [];

        for (let sentence of Data.Sentences) this.Sentences.push(ParseFactory.GetParseElement(this, sentence));
      }

      Parse() {
        let defaultReturn = null;

        for (let sentence of this.Sentences) {
          if (sentence instanceof ParseReturn) return sentence;
          let result = sentence.Parse();
          if (result instanceof ParseReturn) return result;
          if (result != null) defaultReturn = result;
        }

        return defaultReturn;
      }

    }

    class ParseDeclaration extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.VariableName = this.Data.Name;
        this.Assignment = ParseFactory.GetParseElement(this, this.Data.Assignment);
      }

      Parse() {
        let value = this.Assignment.Parse();
        this.GetMain().SetVariable(this.VariableName, value);
        return value;
      }

    }

    class ParseVariable extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.VariableName = Data.d;
      }

      Parse() {
        return this.GetMain().GetVariable(this.VariableName);
      }

    }

    class ParseFixed extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Config = null;
        let d = ParseFactory.GetParseElement(this, Data.d);
        let json = d.Parse();
        this.Config = JSON.parse(json);
      }

      Parse() {
        return this.GetMain().Owner.GetFixedValue(this.Config);
      }

    }

    class MethodDictionary {
      static GetNumber(value) {
        if (value == null) return 0;
        if (value instanceof FieldBase_Model.FieldBaseModel) return value.GetPrice();
        let number = Number(value);
        if (isNaN(number)) return 0;
        return number;
      }

      static GetText(value) {
        if (value == null) return '';
        if (value instanceof FieldBase_Model.FieldBaseModel) return value.ToText();
        return value.toString();
      }

      static Round(value, numberOfDecimals) {
        return MethodDictionary.GetNumber(value).toFixed(MethodDictionary.GetNumber(numberOfDecimals));
      }

      static Ceil(value) {
        return Math.ceil(MethodDictionary.GetNumber(value));
      }

    }

    class ParseFunc extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Args = [];
        this.Method = this.Data.Method;

        for (let current of Data.Args) this.Args.push(ParseFactory.GetParseElement(this, current));
      }

      Parse() {
        if (MethodDictionary[this.Method] != null) return MethodDictionary[this.Method].apply(this, this.Args.map(x => x.Parse()));
        throw new Error('Invalid function used ' + this.Method);
      }

    }

    class ParseMethod extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Args = [];
        this.Name = Data.Name;
        this.Object = ParseFactory.GetParseElement(this, Data.Object).Parse();

        if (Data.Args != null) {
          for (let current of Data.Args) this.Args.push(ParseFactory.GetParseElement(this, current));
        }

        if (this.Object == null) throw new Error('Invalid method call ' + this.Name);
        this.GetNameToUse();
      }

      GetNameToUse() {
        let nameToUse = this.Name;
        if (typeof this.Object[nameToUse] == 'undefined') nameToUse = 'Get' + nameToUse;
        if (typeof this.Object[nameToUse] == 'undefined') throw new Error('Invalid method ' + this.Name);
        return nameToUse;
      }

      Parse() {
        if (this.Object == null) throw new Error('Invalid method call ' + this.Name);
        return this.Object[this.GetNameToUse()].apply(this.Object, this.Args.map(x => x.Parse()));
      }

    }

    class ParseArrayItem extends ParserElementBase {
      constructor(Parent, Data) {
        super(Parent, Data);
        this.Array = ParseFactory.GetParseElement(this, Data.Array);
        this.Index = Number(Data.Index);
        if (isNaN(this.Index)) throw new Error('Invalid Index');
      }

      Parse() {
        let array = this.Array.Parse();
        if (!Array.isArray(array)) return null;
        if (array[this.Index] == undefined) return null;
        return array[this.Index];
      }

    }

    class ParseFactory {
      static GetParseElement(parent, element) {
        if (element == null) return null;

        switch (element.type) {
          case 'NUMBER':
            return new ParserNumber(parent, element);

          case 'BOOLEAN':
            return new ParserBoolean(parent, element);

          case 'STRING':
            return new ParserString(parent, element);

          case 'MATH':
            return new ParseMathFunction(parent, element);

          case 'MUL':
          case 'ADD':
          case 'SUB':
          case 'DIV':
            return new ParseArithmetical(parent, element);

          case 'SENTENCE':
            return new ParseSentence(parent, element);

          case 'P':
            return new ParseParenthesis(parent, element);

          case 'CONDSENTENCE':
            return new ParseConditionSentence(parent, element);

          case 'COMPARATOR':
            return new ParseComparator(parent, element);

          case 'CONDITION':
            return new ParseCondition(parent, element);

          case 'FIELD':
            return new ParseField(parent, element);

          case 'ARR':
            return new ParseArray(parent, element);

          case 'NEGATION':
            return new ParseNegation(parent, element);

          case 'BLOCK':
            return new ParseBlock(parent, element);

          case 'DECLARATION':
            return new ParseDeclaration(parent, element);

          case 'RETURN':
            return new ParseReturn(parent, element);

          case 'VARIABLE':
            return new ParseVariable(parent, element);

          case 'FIXED':
            return new ParseFixed(parent, element);

          case 'FUNC':
            return new ParseFunc(parent, element);

          case 'METHOD':
            return new ParseMethod(parent, element);

          case 'ARRITEM':
            return new ParseArrayItem(parent, element);

          default:
            throw Error('Invalid token ' + element.type);
        }
      }

    }

    class ParseMain extends ParserElementThatUsesFieldsBase$1 {
      constructor(FieldList, Data, Owner = null, ExecutionChain = null) {
        super(null, Data);
        this.FieldList = FieldList;
        this.Owner = Owner;
        this.ExecutionChain = ExecutionChain;
        this.Sentences = [];
        this.Variables = [];
        if (this.Data.length > 0) for (let sentence of this.Data[0].Sentences) this.Sentences.push(ParseFactory.GetParseElement(this, sentence));
      }

      InternalParse() {
        let defaultReturn = null;

        for (let sentence of this.Sentences) {
          if (sentence instanceof ParseReturn) return sentence.Parse();
          let result = sentence.Parse();
          if (result instanceof ParseReturn) return result.Parse();

          if (result !== null) {
            defaultReturn = result;
          }
        }

        return defaultReturn;
      }

      Parse() {
        let result = this.InternalParse();

        if (Array.isArray(result)) {
          return result.reduce((acc, currentValue) => {
            return acc + this.ParseSingleNumber(currentValue);
          }, 0);
        }

        return this.ParseSingleNumber(result);
      }

      ParseSingleNumber(element) {
        if (element == null) return 0;

        if (element instanceof FieldBase_Model.FieldBaseModel) {
          return this.GetPriceFromField(element);
        }

        let float = parseFloat(element);
        if (isNaN(float)) return 0;
        return float;
      }

      ParseText() {
        let result = this.InternalParse();

        if (Array.isArray(result)) {
          return result.map(x => this.ParseSingleText(x)).join(', ');
        }

        return this.ParseSingleText(result);
      }

      ParseSingleText(element) {
        if (element == null) return '';

        if (element instanceof FieldBase_Model.FieldBaseModel) {
          return element.ToText();
        }

        return element.toString();
      }

      SetVariable(variableName, value) {
        let variable = this.Variables.find(x => x.Name == variableName);

        if (variable == null) {
          variable = {
            Name: variableName,
            Value: null
          };
          this.Variables.push(variable);
        }

        variable.Value = value;
      }

      GetVariable(variableName) {
        var _this$Variables$find;

        return (_this$Variables$find = this.Variables.find(x => x.Name == variableName)) === null || _this$Variables$find === void 0 ? void 0 : _this$Variables$find.Value;
      }

    }

    EventManager.EventManager.Subscribe("CalculateFormula", e => {
      if (e.Formula.Compiled == null) {
        console.log('Formula is not compiled', e);
        return;
      }

      let parse = new ParseMain(e.FieldList, e.Formula.Compiled, e.Owner, e.Chain);

      try {
        if (e.Formula.PreferredReturnType == PreferredReturnType.PreferredReturnType.Price) return parse.Parse();else return parse.ParseText();
      } catch (e) {
        console.log(e);
        return 0;
      }
    });

});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiUk5NYWluRm9tdWxhQ29yZV9idW5kbGUuanMiLCJzb3VyY2VzIjpbIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvQ29yZS9QYXJzZXJFbGVtZW50QmFzZS50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvU2NhbGFycy9QYXJzZXJOdW1iZXIudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL1NjYWxhcnMvUGFyc2VyQm9vbGVhbi50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvU2NhbGFycy9QYXJzZXJTdHJpbmcudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0NvcmUvUGFyc2VyRWxlbWVudFRoYXRVc2VzRmllbGRzQmFzZS50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvT3BlcmF0aW9ucy9Bcml0aG1ldGljYWwvUGFyc2VBcml0aG1ldGljYWwudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL01hdGgvUGFyc2VNYXRoRnVuY3Rpb24udHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL1BhcnNlU2VudGVuY2UudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL1BhcnNlUGFyZW50aGVzaXMudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL09wZXJhdGlvbnMvTG9naWNhL1BhcnNlQ29uZGl0aW9uU2VudGVuY2UudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0NvcmUvUGFyc2VyRWxlbWVudFRoYXRVc2VzRmllbGRzQmFzZS50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvT3BlcmF0aW9ucy9Mb2dpY2EvUGFyc2VDb21wYXJhdG9yLnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9PcGVyYXRpb25zL0xvZ2ljYS9QYXJzZUNvbmRpdGlvbi50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvU2NhbGFycy9QYXJzZUZpZWxkLnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9QYXJzZUFycmF5LnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9PcGVyYXRpb25zL0xvZ2ljYS9QYXJzZU5lZ2F0aW9uLnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9QYXJzZVJldHVybi50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvUGFyc2VCbG9jay50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvUGFyc2VEZWNsYXJhdGlvbi50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvRWxlbWVudHMvU2NhbGFycy9QYXJzZVZhcmlhYmxlLnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9TY2FsYXJzL1BhcnNlRml4ZWQudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0NvcmUvTWV0aG9kRGljdGlvbmFyeS50c3giLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL1NjYWxhcnMvUGFyc2VGdW5jLnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9TY2FsYXJzL1BhcnNlTWV0aG9kLnRzIiwiLi4vc3JjL0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9FbGVtZW50cy9QYXJzZUFycmF5SXRlbS50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvQ29yZS9QYXJzZUZhY3RvcnkudHMiLCIuLi9zcmMvRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL1BhcnNlTWFpbi50cyIsIi4uL3NyYy9EeW5hbWljcy9QUi9Gb3JtdWxhL0ZvbXVsYUNvcmUvSW5kZXgudHN4Il0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7UGFyc2VNYWlufSBmcm9tIFwiLi4vRWxlbWVudHMvUGFyc2VNYWluXCI7XHJcblxyXG5leHBvcnQgYWJzdHJhY3QgY2xhc3MgUGFyc2VyRWxlbWVudEJhc2Uge1xyXG4gICAgY29uc3RydWN0b3IocHVibGljIFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxwdWJsaWMgRGF0YTphbnkpIHtcclxuICAgIH1cclxuXHJcbiAgICBwdWJsaWMgR2V0TWFpbigpOlBhcnNlTWFpblxyXG4gICAge1xyXG4gICAgICAgIGlmKHRoaXMuUGFyZW50PT1udWxsKVxyXG4gICAgICAgICAgICByZXR1cm4gdGhpcyBhcyBhbnk7XHJcblxyXG4gICAgICAgIHJldHVybiB0aGlzLlBhcmVudC5HZXRNYWluKCk7XHJcbiAgICB9XHJcbiAgICBwdWJsaWMgYWJzdHJhY3QgUGFyc2UoKTtcclxuXHJcbn0iLCJpbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi4vLi4vQ29yZS9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlck51bWJlciBleHRlbmRzIFBhcnNlckVsZW1lbnRCYXNle1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIHB1YmxpYyBQYXJzZSgpe1xyXG4gICAgICAgIHJldHVybiBwYXJzZUZsb2F0KHRoaXMuRGF0YS5kKTtcclxuICAgIH1cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi8uLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcblxyXG5leHBvcnQgY2xhc3MgUGFyc2VyQm9vbGVhbiBleHRlbmRzIFBhcnNlckVsZW1lbnRCYXNle1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIHB1YmxpYyBQYXJzZSgpe1xyXG4gICAgICAgIHJldHVybiB0aGlzLkRhdGEuVmFsdWU7XHJcbiAgICB9XHJcbn0iLCJpbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi4vLi4vQ29yZS9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlclN0cmluZyBleHRlbmRzIFBhcnNlckVsZW1lbnRCYXNle1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIHB1YmxpYyBQYXJzZSgpe1xyXG4gICAgICAgIHJldHVybiB0aGlzLkRhdGEuVGV4dDtcclxuICAgIH1cclxufSIsImltcG9ydCB7UGFyc2VNYWlufSBmcm9tIFwiLi4vRWxlbWVudHMvUGFyc2VNYWluXCI7XHJcbmltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7RmllbGRCYXNlTW9kZWx9IGZyb20gXCIjRHluYW1pY3MvRm9ybUJ1aWxkZXIvRm9ybUJ1aWxkZXJDb3JlL0ZpZWxkQmFzZS5Nb2RlbFwiO1xyXG5cclxuZXhwb3J0IGFic3RyYWN0IGNsYXNzIFBhcnNlckVsZW1lbnRUaGF0VXNlc0ZpZWxkc0Jhc2UgZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuICAgIHB1YmxpYyBHZXRQcmljZUZyb21GaWVsZChmaWVsZDpGaWVsZEJhc2VNb2RlbClcclxuICAgIHtcclxuICAgICAgICBpZih0aGlzLkdldE1haW4oKS5Pd25lcj09ZmllbGQpXHJcbiAgICAgICAgICAgIHJldHVybiBmaWVsZC5HZXRQcmljZVdpdGhvdXRGb3JtdWxhKCk7XHJcbiAgICAgICAgcmV0dXJuIGZpZWxkLkdldFByaWNlKCk7XHJcbiAgICB9XHJcblxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uLy4uLy4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuaW1wb3J0IHtQYXJzZUZhY3Rvcnl9IGZyb20gXCIuLi8uLi8uLi9Db3JlL1BhcnNlRmFjdG9yeVwiO1xyXG5pbXBvcnQge1BhcnNlckVsZW1lbnRUaGF0VXNlc0ZpZWxkc0Jhc2V9IGZyb20gXCIuLi8uLi8uLi9Db3JlL1BhcnNlckVsZW1lbnRUaGF0VXNlc0ZpZWxkc0Jhc2VcIjtcclxuaW1wb3J0IHtGaWVsZEJhc2VNb2RlbH0gZnJvbSBcIiNEeW5hbWljcy9Gb3JtQnVpbGRlci9Gb3JtQnVpbGRlckNvcmUvRmllbGRCYXNlLk1vZGVsXCI7XHJcblxyXG5leHBvcnQgY2xhc3MgUGFyc2VBcml0aG1ldGljYWwgZXh0ZW5kcyBQYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNle1xyXG4gICAgcHVibGljIExlZnQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcbiAgICBwdWJsaWMgUmlnaHQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcblxyXG5cclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcbiAgICAgICAgdGhpcy5MZWZ0PVBhcnNlRmFjdG9yeS5HZXRQYXJzZUVsZW1lbnQodGhpcyx0aGlzLkRhdGEuTGVmdCk7XHJcbiAgICAgICAgdGhpcy5SaWdodD1QYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsdGhpcy5EYXRhLlJpZ2h0KTtcclxuICAgIH1cclxuXHJcbiAgICBQYXJzZSgpIHtcclxuXHJcblxyXG4gICAgICAgIHN3aXRjaCAodGhpcy5EYXRhLnR5cGUpIHtcclxuICAgICAgICAgICAgY2FzZSAnTVVMJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLkdldFNjYWxhck9yUHJpY2UodGhpcy5MZWZ0LlBhcnNlKCkpKnRoaXMuR2V0U2NhbGFyT3JQcmljZSh0aGlzLlJpZ2h0LlBhcnNlKCkpO1xyXG4gICAgICAgICAgICBjYXNlICdBREQnOlxyXG5cclxuICAgICAgICAgICAgICAgIGxldCBsZWZ0PXRoaXMuVG9TY2FsYXIodGhpcy5MZWZ0LlBhcnNlKCkpO1xyXG4gICAgICAgICAgICAgICAgbGV0IHJpZ2h0PXRoaXMuVG9TY2FsYXIodGhpcy5SaWdodC5QYXJzZSgpKTtcclxuXHJcblxyXG4gICAgICAgICAgICAgICAgaWYobGVmdCBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKVxyXG4gICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmKHR5cGVvZiByaWdodD09J3N0cmluZycpXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxlZnQ9KGxlZnQgYXMgRmllbGRCYXNlTW9kZWwpLlRvVGV4dCgpO1xyXG4gICAgICAgICAgICAgICAgICAgIGVsc2VcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGVmdD10aGlzLkdldFNjYWxhck9yUHJpY2UobGVmdCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgaWYocmlnaHQgaW5zdGFuY2VvZiBGaWVsZEJhc2VNb2RlbClcclxuICAgICAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgICAgICBpZih0eXBlb2YgbGVmdD09J3N0cmluZycpXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJpZ2h0PShyaWdodCBhcyBGaWVsZEJhc2VNb2RlbCkuVG9UZXh0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgZWxzZVxyXG4gICAgICAgICAgICAgICAgICAgICAgICByaWdodD10aGlzLkdldFNjYWxhck9yUHJpY2UocmlnaHQpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIHJldHVybiBsZWZ0K3JpZ2h0O1xyXG4gICAgICAgICAgICBjYXNlICdTVUInOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHRoaXMuR2V0U2NhbGFyT3JQcmljZSh0aGlzLlRvU2NhbGFyKHRoaXMuTGVmdC5QYXJzZSgpKSktdGhpcy5HZXRTY2FsYXJPclByaWNlKHRoaXMuVG9TY2FsYXIodGhpcy5SaWdodC5QYXJzZSgpKSk7XHJcbiAgICAgICAgICAgIGNhc2UgJ0RJVic6XHJcbiAgICAgICAgICAgICAgICBpZih0aGlzLkdldFNjYWxhck9yUHJpY2UodGhpcy5Ub1NjYWxhcih0aGlzLlJpZ2h0LlBhcnNlKCkpKT09MClcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gMDtcclxuICAgICAgICAgICAgICAgIHJldHVybiB0aGlzLkdldFNjYWxhck9yUHJpY2UodGhpcy5Ub1NjYWxhcih0aGlzLkxlZnQuUGFyc2UoKSkpL3RoaXMuR2V0U2NhbGFyT3JQcmljZSh0aGlzLlRvU2NhbGFyKHRoaXMuUmlnaHQuUGFyc2UoKSkpO1xyXG5cclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgR2V0U2NhbGFyT3JQcmljZShkYXRhKVxyXG4gICAge1xyXG4gICAgICAgIGlmKGRhdGEgaW5zdGFuY2VvZiBGaWVsZEJhc2VNb2RlbClcclxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuR2V0UHJpY2VGcm9tRmllbGQoZGF0YSk7XHJcblxyXG4gICAgICAgIHJldHVybiBkYXRhO1xyXG5cclxuICAgIH1cclxuXHJcbiAgICBwcml2YXRlIFRvU2NhbGFyKHBhcnNlOiBhbnkpIHtcclxuICAgICAgICBpZihBcnJheS5pc0FycmF5KHBhcnNlKSlcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIHJldHVybiAocGFyc2UgYXMgYW55W10pLnJlZHVjZSgocHJldmlvdXNWYWx1ZSwgY3VycmVudFZhbHVlKSA9PiBwcmV2aW91c1ZhbHVlK2N1cnJlbnRWYWx1ZSwwKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHJldHVybiBwYXJzZTtcclxuXHJcblxyXG4gICAgfVxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uLy4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuaW1wb3J0IHtQYXJzZUZhY3Rvcnl9IGZyb20gXCIuLi8uLi9Db3JlL1BhcnNlRmFjdG9yeVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlTWF0aEZ1bmN0aW9uIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcblxyXG5cclxuICAgIHB1YmxpYyBDaGlsZDpQYXJzZXJFbGVtZW50QmFzZTtcclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcbiAgICAgICAgaWYodGhpcy5EYXRhLmQhPW51bGwpXHJcbiAgICAgICAgICAgIHRoaXMuQ2hpbGQ9UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLHRoaXMuRGF0YS5kKVxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG5cclxuICAgICAgICBzd2l0Y2ggKHRoaXMuRGF0YS5vcCkge1xyXG4gICAgICAgICAgICBjYXNlICdTSU4nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIE1hdGguc2luKHRoaXMuQ2hpbGQuUGFyc2UoKSk7XHJcbiAgICAgICAgICAgIGNhc2UgJ0NPUyc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gTWF0aC5jb3ModGhpcy5DaGlsZC5QYXJzZSgpKTtcclxuICAgICAgICAgICAgY2FzZSAnVEFOJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBNYXRoLnRhbih0aGlzLkNoaWxkLlBhcnNlKCkpO1xyXG4gICAgICAgICAgICBjYXNlICdBU0lOJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBNYXRoLmFzaW4odGhpcy5DaGlsZC5QYXJzZSgpKTtcclxuICAgICAgICAgICAgY2FzZSAnQVRBTic6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gTWF0aC5hdGFuKHRoaXMuQ2hpbGQuUGFyc2UoKSk7XHJcbiAgICAgICAgICAgIGNhc2UgJ0FDT1MnOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIE1hdGguYWNvcyh0aGlzLkNoaWxkLlBhcnNlKCkpO1xyXG4gICAgICAgICAgICBjYXNlICdTUVJUJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBNYXRoLnNxcnQodGhpcy5DaGlsZC5QYXJzZSgpKTtcclxuICAgICAgICAgICAgY2FzZSAnTE4nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIE1hdGgubG9nKHRoaXMuQ2hpbGQuUGFyc2UoKSk7XHJcbiAgICAgICAgICAgIGNhc2UgJ1BJJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiAzLjE0MTU5MjY1MzU5O1xyXG4gICAgICAgICAgICBjYXNlICdFJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiAyLjcxODI4MTgyODQ1OTA0NTtcclxuXHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZVNlbnRlbmNlIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcblxyXG4gICAgcHVibGljIFNlbnRlbmNlOlBhcnNlckVsZW1lbnRCYXNlO1xyXG4gICAgcHVibGljIE5leHQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcblxyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OlBhcnNlckVsZW1lbnRCYXNlLERhdGE6IGFueSkge1xyXG4gICAgICAgIHN1cGVyKFBhcmVudCxEYXRhKTtcclxuICAgICAgICB0aGlzLlNlbnRlbmNlPVBhcnNlRmFjdG9yeS5HZXRQYXJzZUVsZW1lbnQodGhpcyx0aGlzLkRhdGEuU2VudGVuY2UpO1xyXG4gICAgICAgIGlmKHRoaXMuRGF0YS5OZXh0IT1udWxsKVxyXG4gICAgICAgICAgICB0aGlzLk5leHQ9UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLHRoaXMuRGF0YS5OZXh0KTtcclxuICAgIH1cclxuXHJcbiAgICBQYXJzZSgpIHtcclxuICAgICAgICByZXR1cm4gdGhpcy5TZW50ZW5jZS5QYXJzZSgpO1xyXG4gICAgfVxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuaW1wb3J0IHtQYXJzZUZhY3Rvcnl9IGZyb20gXCIuLi9Db3JlL1BhcnNlRmFjdG9yeVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlUGFyZW50aGVzaXMgZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgQXJnczpQYXJzZXJFbGVtZW50QmFzZVtdO1xyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OlBhcnNlckVsZW1lbnRCYXNlLERhdGE6IGFueSkge1xyXG4gICAgICAgIHN1cGVyKFBhcmVudCxEYXRhKTtcclxuXHJcbiAgICAgICAgdGhpcy5BcmdzPVtdO1xyXG4gICAgICAgIGZvcihsZXQgY3VycmVudCBvZiB0aGlzLkRhdGEuQXJncylcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIHRoaXMuQXJncy5wdXNoKFBhcnNlRmFjdG9yeS5HZXRQYXJzZUVsZW1lbnQodGhpcyxjdXJyZW50KSk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIGlmKHRoaXMuQXJncy5sZW5ndGg9PTApXHJcbiAgICAgICAgICAgIHJldHVybiBudWxsO1xyXG5cclxuICAgICAgICByZXR1cm4gdGhpcy5BcmdzWzBdLlBhcnNlKCk7XHJcbiAgICB9XHJcblxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uLy4uLy4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuaW1wb3J0IHtQYXJzZUZhY3Rvcnl9IGZyb20gXCIuLi8uLi8uLi9Db3JlL1BhcnNlRmFjdG9yeVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlQ29uZGl0aW9uU2VudGVuY2UgZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgQ29uZGl0aW9uOlBhcnNlckVsZW1lbnRCYXNlO1xyXG4gICAgcHVibGljIFJlc3VsdDpQYXJzZXJFbGVtZW50QmFzZTtcclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcbiAgICAgICAgdGhpcy5Db25kaXRpb249UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLERhdGEuQ29uZGl0aW9uKTtcclxuICAgICAgICB0aGlzLlJlc3VsdD1QYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsRGF0YS5SZXN1bHQpO1xyXG5cclxuICAgIH1cclxuXHJcbiAgICBQYXJzZSgpIHtcclxuXHJcblxyXG4gICAgICAgIGlmKHRoaXMuQ29uZGl0aW9uLlBhcnNlKCk9PT10cnVlKVxyXG4gICAgICAgICAgICByZXR1cm4gdGhpcy5SZXN1bHQuUGFyc2UoKTtcclxuICAgICAgICByZXR1cm4gbnVsbDtcclxuICAgIH1cclxuXHJcbn0iLCJpbXBvcnQge1BhcnNlTWFpbn0gZnJvbSBcIi4uL0VsZW1lbnRzL1BhcnNlTWFpblwiO1xyXG5pbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5pbXBvcnQge0ZpZWxkQmFzZU1vZGVsfSBmcm9tIFwiI0R5bmFtaWNzL0Zvcm1CdWlsZGVyL0Zvcm1CdWlsZGVyQ29yZS9GaWVsZEJhc2UuTW9kZWxcIjtcclxuXHJcbmV4cG9ydCBhYnN0cmFjdCBjbGFzcyBQYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNlIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcbiAgICBwdWJsaWMgR2V0UHJpY2VGcm9tRmllbGQoZmllbGQ6RmllbGRCYXNlTW9kZWwpXHJcbiAgICB7XHJcbiAgICAgICAgaWYodGhpcy5HZXRNYWluKCkuT3duZXI9PWZpZWxkKVxyXG4gICAgICAgICAgICByZXR1cm4gZmllbGQuR2V0UHJpY2VXaXRob3V0Rm9ybXVsYSgpO1xyXG4gICAgICAgIHJldHVybiBmaWVsZC5HZXRQcmljZSgpO1xyXG4gICAgfVxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi8uLi8uLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vLi4vLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuaW1wb3J0IHtQYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNlfSBmcm9tIFwiI0R5bmFtaWNzL1BSL0Zvcm11bGEvRm9tdWxhQ29yZS9Db3JlL1BhcnNlckVsZW1lbnRUaGF0VXNlc0ZpZWxkc0Jhc2VcIjtcclxuaW1wb3J0IHtGaWVsZEJhc2VNb2RlbH0gZnJvbSBcIiNEeW5hbWljcy9Gb3JtQnVpbGRlci9Gb3JtQnVpbGRlckNvcmUvRmllbGRCYXNlLk1vZGVsXCI7XHJcbmltcG9ydCB7TXVsdGlwbGVPcHRpb25zQmFzZU1vZGVsfSBmcm9tIFwiI0R5bmFtaWNzL0Zvcm1CdWlsZGVyL0Zvcm1CdWlsZGVyQ29yZS9NdWx0aXBsZU9wdGlvbnNCYXNlLk1vZGVsXCI7XHJcblxyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlQ29tcGFyYXRvciBleHRlbmRzIFBhcnNlckVsZW1lbnRUaGF0VXNlc0ZpZWxkc0Jhc2V7XHJcblxyXG4gICAgcHVibGljIExlZnQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcbiAgICBwdWJsaWMgUmlnaHQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcblxyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OlBhcnNlckVsZW1lbnRCYXNlLERhdGE6IGFueSkge1xyXG4gICAgICAgIHN1cGVyKFBhcmVudCxEYXRhKTtcclxuICAgICAgICB0aGlzLkxlZnQ9UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLERhdGEuTGVmdCk7XHJcbiAgICAgICAgdGhpcy5SaWdodD1QYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsRGF0YS5SaWdodCk7XHJcbiAgICB9XHJcblxyXG4gICAgUGFyc2UoKSB7XHJcbiAgICAgICAgbGV0IG9wZXJhdG9yPXRoaXMuRGF0YS5vcGVyYXRvcjtcclxuXHJcbiAgICAgICAgaWYodGhpcy5SaWdodD09bnVsbClcclxuICAgICAgICAgICAgcmV0dXJuIHRoaXMuTGVmdC5QYXJzZSgpPT10cnVlO1xyXG5cclxuICAgICAgICBsZXQgb3JpZ2luYWxMZWZ0PXRoaXMuTGVmdC5QYXJzZSgpO1xyXG4gICAgICAgIGxldCBvcmlnaW5hbFJpZ2h0PXRoaXMuUmlnaHQuUGFyc2UoKTtcclxuXHJcbiAgICAgICAgbGV0IGxlZnRWYWx1ZT10aGlzLkxlZnQuUGFyc2UoKTtcclxuICAgICAgICBsZXQgcmlnaHRWYWx1ZT10aGlzLlJpZ2h0LlBhcnNlKCk7XHJcblxyXG4gICAgICAgIGlmKGxlZnRWYWx1ZSBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKVxyXG4gICAgICAgIHtcclxuICAgICAgICAgICAgaWYodHlwZW9mIHJpZ2h0VmFsdWU9PSdzdHJpbmcnKVxyXG4gICAgICAgICAgICAgICAgbGVmdFZhbHVlPShsZWZ0VmFsdWUgYXMgRmllbGRCYXNlTW9kZWwpLlRvVGV4dCgpO1xyXG4gICAgICAgICAgICBlbHNlXHJcbiAgICAgICAgICAgICAgICBsZWZ0VmFsdWU9dGhpcy5HZXRQcmljZUZyb21GaWVsZChsZWZ0VmFsdWUpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYocmlnaHRWYWx1ZSBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKVxyXG4gICAgICAgIHtcclxuICAgICAgICAgICAgaWYodHlwZW9mIGxlZnRWYWx1ZT09J3N0cmluZycpXHJcbiAgICAgICAgICAgICAgICByaWdodFZhbHVlPShyaWdodFZhbHVlIGFzIEZpZWxkQmFzZU1vZGVsKS5Ub1RleHQoKTtcclxuICAgICAgICAgICAgZWxzZVxyXG4gICAgICAgICAgICAgICAgcmlnaHRWYWx1ZT10aGlzLkdldFByaWNlRnJvbUZpZWxkKHJpZ2h0VmFsdWUpO1xyXG4gICAgICAgIH1cclxuXHJcblxyXG4gICAgICAgIHN3aXRjaCAob3BlcmF0b3IpIHtcclxuICAgICAgICAgICAgY2FzZSAnPT0nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGxlZnRWYWx1ZT09cmlnaHRWYWx1ZTtcclxuICAgICAgICAgICAgY2FzZSAnIT0nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGxlZnRWYWx1ZSE9cmlnaHRWYWx1ZTtcclxuICAgICAgICAgICAgY2FzZSAnPic6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbGVmdFZhbHVlPnJpZ2h0VmFsdWU7XHJcbiAgICAgICAgICAgIGNhc2UgJz49JzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBsZWZ0VmFsdWU+PXJpZ2h0VmFsdWU7XHJcbiAgICAgICAgICAgIGNhc2UgJzwnOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGxlZnRWYWx1ZTw9cmlnaHRWYWx1ZTtcclxuICAgICAgICAgICAgY2FzZSAnPD0nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGxlZnRWYWx1ZTw9cmlnaHRWYWx1ZTtcclxuICAgICAgICAgICAgY2FzZSAnY29udGFpbnMnOlxyXG4gICAgICAgICAgICBjYXNlICdub3QgY29udGFpbnMnOlxyXG4gICAgICAgICAgICAgICAgbGV0IGhheXN0YWNrPWxlZnRWYWx1ZTtcclxuICAgICAgICAgICAgICAgIGxldCBuZWVkbGU9cmlnaHRWYWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBpZihvcmlnaW5hbExlZnQgaW5zdGFuY2VvZiBNdWx0aXBsZU9wdGlvbnNCYXNlTW9kZWwpXHJcbiAgICAgICAgICAgICAgICAgICAgaGF5c3RhY2s9b3JpZ2luYWxMZWZ0LkdldFNlbGVjdGVkT3B0aW9ucygpLm1hcCh4PT54LkxhYmVsKTtcclxuXHJcbiAgICAgICAgICAgICAgICBpZihvcmlnaW5hbFJpZ2h0IGluc3RhbmNlb2YgTXVsdGlwbGVPcHRpb25zQmFzZU1vZGVsKVxyXG4gICAgICAgICAgICAgICAgICAgIGhheXN0YWNrPW9yaWdpbmFsUmlnaHQuR2V0U2VsZWN0ZWRPcHRpb25zKCkubWFwKHg9PnguTGFiZWwpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmKCFBcnJheS5pc0FycmF5KG5lZWRsZSkpXHJcbiAgICAgICAgICAgICAgICAgICAgbmVlZGxlPVtuZWVkbGVdO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmKCFBcnJheS5pc0FycmF5KGhheXN0YWNrKSlcclxuICAgICAgICAgICAgICAgICAgICBoYXlzdGFjaz1baGF5c3RhY2tdO1xyXG5cclxuICAgICAgICAgICAgICAgIGZvcihsZXQgaT0wO2k8aGF5c3RhY2subGVuZ3RoO2krKylcclxuICAgICAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgICAgICBpZihoYXlzdGFja1tpXSBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICBoYXlzdGFja1tpXT10aGlzLkdldFByaWNlRnJvbUZpZWxkKGhheXN0YWNrW2ldKTtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBmb3IobGV0IGk9MDtpPG5lZWRsZS5sZW5ndGg7aSsrKVxyXG4gICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmKG5lZWRsZVtpXSBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICBuZWVkbGVbaV09dGhpcy5HZXRQcmljZUZyb21GaWVsZChuZWVkbGVbaV0gYXMgRmllbGRCYXNlTW9kZWwpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuXHJcblxyXG4gICAgICAgICAgICAgICAgaWYob3BlcmF0b3I9PSdjb250YWlucycpIHtcclxuICAgICAgICAgICAgICAgICAgICBmb3IgKGxldCBjdXJyZW50TmVlZGxlIG9mIG5lZWRsZSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoaGF5c3RhY2suc29tZSh4PT54PT1jdXJyZW50TmVlZGxlKSlcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiB0cnVlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcblxyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIGlmKG9wZXJhdG9yPT0nbm90IGNvbnRhaW5zJykge1xyXG4gICAgICAgICAgICAgICAgICAgIGZvciAobGV0IGN1cnJlbnROZWVkbGUgb2YgbmVlZGxlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChoYXlzdGFjay5zb21lKHg9Png9PWN1cnJlbnROZWVkbGUpKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG5cclxuICAgICAgICB9XHJcblxyXG4gICAgfVxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi8uLi8uLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vLi4vLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZUNvbmRpdGlvbiBleHRlbmRzIFBhcnNlckVsZW1lbnRCYXNle1xyXG5cclxuICAgIHB1YmxpYyBDb21wYXJhdG9yOlBhcnNlckVsZW1lbnRCYXNlO1xyXG4gICAgcHVibGljIE5leHQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcbiAgICBwdWJsaWMgT3BlcmF0aW9uOnN0cmluZztcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihQYXJlbnQ6UGFyc2VyRWxlbWVudEJhc2UsRGF0YTogYW55KSB7XHJcbiAgICAgICAgc3VwZXIoUGFyZW50LERhdGEpO1xyXG4gICAgICAgIHRoaXMuT3BlcmF0aW9uPURhdGEuT3BlcmF0aW9uO1xyXG4gICAgICAgIHRoaXMuQ29tcGFyYXRvcj1QYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsRGF0YS5Db21wYXJhdG9yKTtcclxuICAgICAgICB0aGlzLk5leHQ9UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLERhdGEuTmV4dCk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIGxldCBpc1RydWU9dGhpcy5Db21wYXJhdG9yLlBhcnNlKCk9PXRydWU7XHJcbiAgICAgICAgaWYodGhpcy5OZXh0PT1udWxsKVxyXG4gICAgICAgICAgICByZXR1cm4gaXNUcnVlO1xyXG5cclxuICAgICAgICBsZXQgbmV4dElzVHJ1ZT10aGlzLk5leHQuUGFyc2UoKT09dHJ1ZTtcclxuICAgICAgICBpZih0aGlzLk9wZXJhdGlvbj09XCImJlwiKVxyXG4gICAgICAgICAgICByZXR1cm4gaXNUcnVlJiZuZXh0SXNUcnVlO1xyXG4gICAgICAgIGVsc2VcclxuICAgICAgICAgICAgcmV0dXJuIGlzVHJ1ZXx8bmV4dElzVHJ1ZTtcclxuXHJcblxyXG4gICAgfVxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi8uLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuaW1wb3J0IHtGaWVsZEJhc2VNb2RlbH0gZnJvbSBcIiNEeW5hbWljcy9Gb3JtQnVpbGRlci9Gb3JtQnVpbGRlckNvcmUvRmllbGRCYXNlLk1vZGVsXCI7XHJcblxyXG5leHBvcnQgY2xhc3MgUGFyc2VGaWVsZCBleHRlbmRzIFBhcnNlckVsZW1lbnRCYXNle1xyXG5cclxuICAgIHB1YmxpYyBGaWVsZElkOm51bWJlcjtcclxuICAgIHB1YmxpYyBGaWVsZDpGaWVsZEJhc2VNb2RlbDtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihQYXJlbnQ6IFBhcnNlckVsZW1lbnRCYXNlLCBEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsIERhdGEpO1xyXG4gICAgICAgIHRoaXMuRmllbGRJZD10aGlzLkRhdGEuSWQ7XHJcblxyXG4gICAgICAgIHRoaXMuRmllbGQ9dGhpcy5HZXRNYWluKCkuRmllbGRMaXN0LmZpbmQoeD0+eC5PcHRpb25zLklkPT10aGlzLkZpZWxkSWQpO1xyXG5cclxuICAgIH1cclxuXHJcblxyXG4gICAgUGFyc2UoKSB7XHJcbiAgICAgICAgaWYodGhpcy5GaWVsZD09bnVsbClcclxuICAgICAgICAgICAgcmV0dXJuIDA7XHJcblxyXG5cclxuICAgICAgICByZXR1cm4gdGhpcy5GaWVsZDtcclxuICAgIH1cclxuXHJcblxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuaW1wb3J0IHtQYXJzZUZhY3Rvcnl9IGZyb20gXCIuLi9Db3JlL1BhcnNlRmFjdG9yeVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlQXJyYXkgZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgRWxlbWVudHM6UGFyc2VyRWxlbWVudEJhc2VbXTtcclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcblxyXG4gICAgICAgIHRoaXMuRWxlbWVudHM9W107XHJcbiAgICAgICAgZm9yKGxldCBjdXJyZW50IG9mIHRoaXMuRGF0YS5FbGVtZW50cylcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIHRoaXMuRWxlbWVudHMucHVzaChQYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsY3VycmVudCkuUGFyc2UoKSk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIHJldHVybiB0aGlzLkVsZW1lbnRzO1xyXG4gICAgfVxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi8uLi8uLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vLi4vLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZU5lZ2F0aW9uIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcbiAgICBwdWJsaWMgQ2hpbGQ6UGFyc2VyRWxlbWVudEJhc2U7XHJcblxyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OiBQYXJzZXJFbGVtZW50QmFzZSwgRGF0YTogYW55KSB7XHJcbiAgICAgICAgc3VwZXIoUGFyZW50LCBEYXRhKTtcclxuICAgICAgICB0aGlzLkNoaWxkPVBhcnNlRmFjdG9yeS5HZXRQYXJzZUVsZW1lbnQodGhpcyxEYXRhLkNoaWxkKTtcclxuICAgIH1cclxuXHJcbiAgICBQYXJzZSgpIHtcclxuICAgICAgICByZXR1cm4gIXRoaXMuQ2hpbGQuUGFyc2UoKTtcclxuICAgIH1cclxuXHJcbn0iLCJpbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi4vQ29yZS9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5pbXBvcnQge1BhcnNlRmFjdG9yeX0gZnJvbSBcIi4uL0NvcmUvUGFyc2VGYWN0b3J5XCI7XHJcblxyXG5leHBvcnQgY2xhc3MgUGFyc2VSZXR1cm4gZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgU2VudGVuY2U6UGFyc2VyRWxlbWVudEJhc2U7XHJcbiAgICBjb25zdHJ1Y3RvcihQYXJlbnQ6UGFyc2VyRWxlbWVudEJhc2UsRGF0YTogYW55KSB7XHJcbiAgICAgICAgc3VwZXIoUGFyZW50LERhdGEpO1xyXG4gICAgICAgIHRoaXMuU2VudGVuY2U9UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLERhdGEuU2VudGVuY2UpO1xyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIHJldHVybiB0aGlzLlNlbnRlbmNlLlBhcnNlKCk7XHJcbiAgICB9XHJcblxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuaW1wb3J0IHtQYXJzZVJldHVybn0gZnJvbSBcIi4vUGFyc2VSZXR1cm5cIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZUJsb2NrIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcblxyXG4gICAgcHVibGljIFNlbnRlbmNlczpQYXJzZXJFbGVtZW50QmFzZVtdO1xyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OlBhcnNlckVsZW1lbnRCYXNlLERhdGE6IGFueSkge1xyXG4gICAgICAgIHN1cGVyKFBhcmVudCxEYXRhKTtcclxuICAgICAgICB0aGlzLlNlbnRlbmNlcz1bXTtcclxuICAgICAgICBmb3IobGV0IHNlbnRlbmNlIG9mIERhdGEuU2VudGVuY2VzKVxyXG4gICAgICAgICAgICB0aGlzLlNlbnRlbmNlcy5wdXNoKFBhcnNlRmFjdG9yeS5HZXRQYXJzZUVsZW1lbnQodGhpcyxzZW50ZW5jZSkpO1xyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIGxldCBkZWZhdWx0UmV0dXJuPW51bGw7XHJcbiAgICAgICAgZm9yKGxldCBzZW50ZW5jZSBvZiB0aGlzLlNlbnRlbmNlcylcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIGlmKHNlbnRlbmNlIGluc3RhbmNlb2YgUGFyc2VSZXR1cm4pXHJcbiAgICAgICAgICAgICAgICByZXR1cm4gc2VudGVuY2U7XHJcblxyXG4gICAgICAgICAgICBsZXQgcmVzdWx0PXNlbnRlbmNlLlBhcnNlKCk7XHJcbiAgICAgICAgICAgIGlmKHJlc3VsdCBpbnN0YW5jZW9mIFBhcnNlUmV0dXJuKVxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlc3VsdDtcclxuXHJcbiAgICAgICAgICAgIGlmKHJlc3VsdCE9bnVsbClcclxuICAgICAgICAgICAgICAgIGRlZmF1bHRSZXR1cm49cmVzdWx0O1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgcmV0dXJuIGRlZmF1bHRSZXR1cm47XHJcbiAgICB9XHJcblxyXG5cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZURlY2xhcmF0aW9uIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcblxyXG4gICAgcHVibGljIFZhcmlhYmxlTmFtZTpzdHJpbmc7XHJcbiAgICBwdWJsaWMgQXNzaWdubWVudDpQYXJzZXJFbGVtZW50QmFzZTtcclxuICAgIGNvbnN0cnVjdG9yKFBhcmVudDpQYXJzZXJFbGVtZW50QmFzZSxEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsRGF0YSk7XHJcbiAgICAgICAgdGhpcy5WYXJpYWJsZU5hbWU9dGhpcy5EYXRhLk5hbWU7XHJcbiAgICAgICAgdGhpcy5Bc3NpZ25tZW50PVBhcnNlRmFjdG9yeS5HZXRQYXJzZUVsZW1lbnQodGhpcyx0aGlzLkRhdGEuQXNzaWdubWVudCk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIGxldCB2YWx1ZT10aGlzLkFzc2lnbm1lbnQuUGFyc2UoKTtcclxuICAgICAgICB0aGlzLkdldE1haW4oKS5TZXRWYXJpYWJsZSh0aGlzLlZhcmlhYmxlTmFtZSx2YWx1ZSk7XHJcbiAgICAgICAgcmV0dXJuIHZhbHVlO1xyXG4gICAgfVxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uLy4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZVZhcmlhYmxlIGV4dGVuZHMgUGFyc2VyRWxlbWVudEJhc2V7XHJcblxyXG4gICAgcHVibGljIFZhcmlhYmxlTmFtZTpzdHJpbmc7XHJcbiAgICBjb25zdHJ1Y3RvcihQYXJlbnQ6IFBhcnNlckVsZW1lbnRCYXNlLCBEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsIERhdGEpO1xyXG5cclxuICAgICAgICB0aGlzLlZhcmlhYmxlTmFtZT1EYXRhLmQ7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIHJldHVybiB0aGlzLkdldE1haW4oKS5HZXRWYXJpYWJsZSh0aGlzLlZhcmlhYmxlTmFtZSk7XHJcbiAgICB9XHJcblxyXG59IiwiaW1wb3J0IHtQYXJzZXJFbGVtZW50QmFzZX0gZnJvbSBcIi4uLy4uL0NvcmUvUGFyc2VyRWxlbWVudEJhc2VcIjtcclxuaW1wb3J0IHtQYXJzZUZhY3Rvcnl9IGZyb20gXCIuLi8uLi9Db3JlL1BhcnNlRmFjdG9yeVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlRml4ZWQgZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgQ29uZmlnOntUeXBlOnN0cmluZyxMYWJlbDpTdHJpbmd9PW51bGw7XHJcbiAgICBjb25zdHJ1Y3RvcihQYXJlbnQ6IFBhcnNlckVsZW1lbnRCYXNlLCBEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsIERhdGEpO1xyXG5cclxuICAgICAgICBsZXQgZD1QYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsRGF0YS5kKTtcclxuICAgICAgICBsZXQganNvbj1kLlBhcnNlKCk7XHJcblxyXG4gICAgICAgIHRoaXMuQ29uZmlnPUpTT04ucGFyc2UoanNvbik7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIHJldHVybiB0aGlzLkdldE1haW4oKS5Pd25lci5HZXRGaXhlZFZhbHVlKHRoaXMuQ29uZmlnKTtcclxuICAgIH1cclxuXHJcbn0iLCJpbXBvcnQge0ZpZWxkQmFzZU1vZGVsfSBmcm9tIFwiI0R5bmFtaWNzL0Zvcm1CdWlsZGVyL0Zvcm1CdWlsZGVyQ29yZS9GaWVsZEJhc2UuTW9kZWxcIjtcclxuXHJcbmV4cG9ydCBjbGFzcyBNZXRob2REaWN0aW9uYXJ5e1xyXG5cclxuICAgIHN0YXRpYyBHZXROdW1iZXIodmFsdWU6YW55KTpudW1iZXJ7XHJcbiAgICAgICAgaWYodmFsdWU9PW51bGwpXHJcbiAgICAgICAgICAgIHJldHVybiAwO1xyXG5cclxuICAgICAgICBpZih2YWx1ZSBpbnN0YW5jZW9mICBGaWVsZEJhc2VNb2RlbClcclxuICAgICAgICAgICAgcmV0dXJuIHZhbHVlLkdldFByaWNlKCk7XHJcblxyXG4gICAgICAgIGxldCBudW1iZXI9TnVtYmVyKHZhbHVlKTtcclxuICAgICAgICBpZihpc05hTihudW1iZXIpKVxyXG4gICAgICAgICAgICByZXR1cm4gMDtcclxuICAgICAgICByZXR1cm4gbnVtYmVyO1xyXG4gICAgfVxyXG5cclxuICAgIHN0YXRpYyBHZXRUZXh0KHZhbHVlOmFueSlcclxuICAgIHtcclxuICAgICAgICBpZih2YWx1ZT09bnVsbClcclxuICAgICAgICAgICAgcmV0dXJuICcnO1xyXG5cclxuICAgICAgICBpZih2YWx1ZSBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKVxyXG4gICAgICAgICAgICByZXR1cm4gdmFsdWUuVG9UZXh0KCk7XHJcblxyXG4gICAgICAgIHJldHVybiB2YWx1ZS50b1N0cmluZygpO1xyXG5cclxuICAgIH1cclxuXHJcbiAgICBzdGF0aWMgUm91bmQodmFsdWUsbnVtYmVyT2ZEZWNpbWFscylcclxuICAgIHtcclxuICAgICAgICByZXR1cm4gTWV0aG9kRGljdGlvbmFyeS5HZXROdW1iZXIodmFsdWUpLnRvRml4ZWQoTWV0aG9kRGljdGlvbmFyeS5HZXROdW1iZXIobnVtYmVyT2ZEZWNpbWFscykpO1xyXG5cclxuICAgIH1cclxuXHJcbiAgICBzdGF0aWMgQ2VpbCh2YWx1ZSlcclxuICAgIHtcclxuICAgICAgICByZXR1cm4gTWF0aC5jZWlsKE1ldGhvZERpY3Rpb25hcnkuR2V0TnVtYmVyKHZhbHVlKSk7XHJcbiAgICB9XHJcbn0iLCJpbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi4vLi4vQ29yZS9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5pbXBvcnQge1BhcnNlRmFjdG9yeX0gZnJvbSBcIi4uLy4uL0NvcmUvUGFyc2VGYWN0b3J5XCI7XHJcbmltcG9ydCB7TWV0aG9kRGljdGlvbmFyeX0gZnJvbSBcIi4uLy4uL0NvcmUvTWV0aG9kRGljdGlvbmFyeVwiO1xyXG5cclxuZXhwb3J0IGNsYXNzIFBhcnNlRnVuYyBleHRlbmRzIFBhcnNlckVsZW1lbnRCYXNle1xyXG5cclxuICAgIHB1YmxpYyBNZXRob2Q6c3RyaW5nO1xyXG4gICAgcHVibGljIEFyZ3M6UGFyc2VyRWxlbWVudEJhc2VbXTtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihQYXJlbnQ6IFBhcnNlckVsZW1lbnRCYXNlLCBEYXRhOiBhbnkpIHtcclxuICAgICAgICBzdXBlcihQYXJlbnQsIERhdGEpO1xyXG5cclxuICAgICAgICB0aGlzLkFyZ3M9W107XHJcbiAgICAgICAgdGhpcy5NZXRob2Q9dGhpcy5EYXRhLk1ldGhvZDtcclxuICAgICAgICBmb3IobGV0IGN1cnJlbnQgb2YgRGF0YS5BcmdzKVxyXG4gICAgICAgICAgICB0aGlzLkFyZ3MucHVzaChQYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsY3VycmVudCkpO1xyXG5cclxuICAgIH1cclxuXHJcbiAgICBQYXJzZSgpIHtcclxuICAgICAgICBpZihNZXRob2REaWN0aW9uYXJ5W3RoaXMuTWV0aG9kXSE9bnVsbClcclxuICAgICAgICAgICAgcmV0dXJuIChNZXRob2REaWN0aW9uYXJ5W3RoaXMuTWV0aG9kXSBhcyBGdW5jdGlvbikuYXBwbHkodGhpcyx0aGlzLkFyZ3MubWFwKHg9PnguUGFyc2UoKSkpO1xyXG5cclxuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0ludmFsaWQgZnVuY3Rpb24gdXNlZCAnK3RoaXMuTWV0aG9kKTtcclxuICAgIH1cclxuXHJcbn0iLCJpbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi4vLi4vQ29yZS9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5pbXBvcnQge1BhcnNlRmFjdG9yeX0gZnJvbSBcIi4uLy4uL0NvcmUvUGFyc2VGYWN0b3J5XCI7XHJcblxyXG5leHBvcnQgY2xhc3MgUGFyc2VNZXRob2QgZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgTmFtZTpzdHJpbmc7XHJcbiAgICBwdWJsaWMgQXJnczpQYXJzZXJFbGVtZW50QmFzZVtdO1xyXG4gICAgcHVibGljIE9iamVjdDphbnk7XHJcbiAgICBwdWJsaWMgSW5zdGFuY2VUb1VzZTphbnk7XHJcblxyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OiBQYXJzZXJFbGVtZW50QmFzZSwgRGF0YTogYW55KSB7XHJcbiAgICAgICAgc3VwZXIoUGFyZW50LCBEYXRhKTtcclxuXHJcbiAgICAgICAgdGhpcy5BcmdzPVtdO1xyXG4gICAgICAgIHRoaXMuTmFtZT1EYXRhLk5hbWU7XHJcbiAgICAgICAgdGhpcy5PYmplY3Q9UGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLERhdGEuT2JqZWN0KS5QYXJzZSgpO1xyXG4gICAgICAgIGlmKERhdGEuQXJncyE9bnVsbClcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIGZvcihsZXQgY3VycmVudCBvZiBEYXRhLkFyZ3MpXHJcbiAgICAgICAgICAgICAgICB0aGlzLkFyZ3MucHVzaChQYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsY3VycmVudCkpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYodGhpcy5PYmplY3Q9PW51bGwpXHJcbiAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcignSW52YWxpZCBtZXRob2QgY2FsbCAnK3RoaXMuTmFtZSk7XHJcblxyXG4gICAgICAgIHRoaXMuR2V0TmFtZVRvVXNlKCk7XHJcbiAgICB9XHJcblxyXG4gICAgcHVibGljIEdldE5hbWVUb1VzZSgpe1xyXG4gICAgICAgIGxldCBuYW1lVG9Vc2U9dGhpcy5OYW1lO1xyXG4gICAgICAgIGlmKHR5cGVvZiB0aGlzLk9iamVjdFtuYW1lVG9Vc2VdPT0ndW5kZWZpbmVkJylcclxuICAgICAgICAgICAgbmFtZVRvVXNlPSdHZXQnK25hbWVUb1VzZTtcclxuXHJcbiAgICAgICAgaWYodHlwZW9mIHRoaXMuT2JqZWN0W25hbWVUb1VzZV09PSd1bmRlZmluZWQnKVxyXG4gICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0ludmFsaWQgbWV0aG9kICcrdGhpcy5OYW1lKTtcclxuXHJcbiAgICAgICAgcmV0dXJuIG5hbWVUb1VzZTtcclxuICAgIH1cclxuXHJcblxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIGlmKHRoaXMuT2JqZWN0PT1udWxsKVxyXG4gICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0ludmFsaWQgbWV0aG9kIGNhbGwgJyt0aGlzLk5hbWUpO1xyXG5cclxuXHJcblxyXG4gICAgICAgIHJldHVybiAodGhpcy5PYmplY3RbdGhpcy5HZXROYW1lVG9Vc2UoKV0gYXMgRnVuY3Rpb24pLmFwcGx5KHRoaXMuT2JqZWN0LHRoaXMuQXJncy5tYXAoeD0+eC5QYXJzZSgpKSlcclxuXHJcblxyXG4gICAgfVxyXG5cclxuXHJcbn0iLCJpbXBvcnQge1BhcnNlckVsZW1lbnRCYXNlfSBmcm9tIFwiLi4vQ29yZS9QYXJzZXJFbGVtZW50QmFzZVwiO1xyXG5pbXBvcnQge1BhcnNlRmFjdG9yeX0gZnJvbSBcIi4uL0NvcmUvUGFyc2VGYWN0b3J5XCI7XHJcblxyXG5leHBvcnQgY2xhc3MgUGFyc2VBcnJheUl0ZW0gZXh0ZW5kcyBQYXJzZXJFbGVtZW50QmFzZXtcclxuXHJcbiAgICBwdWJsaWMgQXJyYXk6UGFyc2VyRWxlbWVudEJhc2U7XHJcbiAgICBwdWJsaWMgSW5kZXg6bnVtYmVyO1xyXG4gICAgY29uc3RydWN0b3IoUGFyZW50OlBhcnNlckVsZW1lbnRCYXNlLERhdGE6IGFueSkge1xyXG4gICAgICAgIHN1cGVyKFBhcmVudCxEYXRhKTtcclxuXHJcbiAgICAgICAgdGhpcy5BcnJheT1QYXJzZUZhY3RvcnkuR2V0UGFyc2VFbGVtZW50KHRoaXMsRGF0YS5BcnJheSk7XHJcbiAgICAgICAgdGhpcy5JbmRleD1OdW1iZXIoRGF0YS5JbmRleCk7XHJcbiAgICAgICAgaWYoaXNOYU4odGhpcy5JbmRleCkpXHJcbiAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcignSW52YWxpZCBJbmRleCcpO1xyXG5cclxuXHJcblxyXG4gICAgfVxyXG5cclxuICAgIFBhcnNlKCkge1xyXG4gICAgICAgIGxldCBhcnJheT10aGlzLkFycmF5LlBhcnNlKCk7XHJcbiAgICAgICAgaWYoIUFycmF5LmlzQXJyYXkoYXJyYXkpKVxyXG4gICAgICAgICAgICByZXR1cm4gbnVsbDtcclxuXHJcbiAgICAgICAgaWYoYXJyYXlbdGhpcy5JbmRleF09PXVuZGVmaW5lZClcclxuICAgICAgICAgICAgcmV0dXJuIG51bGxcclxuXHJcbiAgICAgICAgcmV0dXJuIGFycmF5W3RoaXMuSW5kZXhdO1xyXG5cclxuICAgIH1cclxuXHJcbn0iLCJpbXBvcnQge1BhcnNlck51bWJlcn0gZnJvbSBcIi4uL0VsZW1lbnRzL1NjYWxhcnMvUGFyc2VyTnVtYmVyXCI7XHJcbmltcG9ydCB7UGFyc2VyQm9vbGVhbn0gZnJvbSBcIi4uL0VsZW1lbnRzL1NjYWxhcnMvUGFyc2VyQm9vbGVhblwiO1xyXG5pbXBvcnQge1BhcnNlclN0cmluZ30gZnJvbSBcIi4uL0VsZW1lbnRzL1NjYWxhcnMvUGFyc2VyU3RyaW5nXCI7XHJcblxyXG5pbXBvcnQge1BhcnNlQXJpdGhtZXRpY2FsfSBmcm9tIFwiLi4vRWxlbWVudHMvT3BlcmF0aW9ucy9Bcml0aG1ldGljYWwvUGFyc2VBcml0aG1ldGljYWxcIjtcclxuaW1wb3J0IHtQYXJzZU1hdGhGdW5jdGlvbn0gZnJvbSBcIi4uL0VsZW1lbnRzL01hdGgvUGFyc2VNYXRoRnVuY3Rpb25cIjtcclxuaW1wb3J0IHtQYXJzZVNlbnRlbmNlfSBmcm9tIFwiLi4vRWxlbWVudHMvUGFyc2VTZW50ZW5jZVwiO1xyXG5pbXBvcnQge1BhcnNlUGFyZW50aGVzaXN9IGZyb20gXCIuLi9FbGVtZW50cy9QYXJzZVBhcmVudGhlc2lzXCI7XHJcbmltcG9ydCB7UGFyc2VDb25kaXRpb25TZW50ZW5jZX0gZnJvbSBcIi4uL0VsZW1lbnRzL09wZXJhdGlvbnMvTG9naWNhL1BhcnNlQ29uZGl0aW9uU2VudGVuY2VcIjtcclxuaW1wb3J0IHtQYXJzZUNvbXBhcmF0b3J9IGZyb20gXCIuLi9FbGVtZW50cy9PcGVyYXRpb25zL0xvZ2ljYS9QYXJzZUNvbXBhcmF0b3JcIjtcclxuaW1wb3J0IHtQYXJzZUNvbmRpdGlvbn0gZnJvbSBcIi4uL0VsZW1lbnRzL09wZXJhdGlvbnMvTG9naWNhL1BhcnNlQ29uZGl0aW9uXCI7XHJcbmltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGaWVsZH0gZnJvbSBcIi4uL0VsZW1lbnRzL1NjYWxhcnMvUGFyc2VGaWVsZFwiO1xyXG5pbXBvcnQge1BhcnNlQXJyYXl9IGZyb20gXCIuLi9FbGVtZW50cy9QYXJzZUFycmF5XCI7XHJcbmltcG9ydCB7UGFyc2VOZWdhdGlvbn0gZnJvbSBcIi4uL0VsZW1lbnRzL09wZXJhdGlvbnMvTG9naWNhL1BhcnNlTmVnYXRpb25cIjtcclxuaW1wb3J0IHtQYXJzZUJsb2NrfSBmcm9tIFwiLi4vRWxlbWVudHMvUGFyc2VCbG9ja1wiO1xyXG5pbXBvcnQge1BhcnNlRGVjbGFyYXRpb259IGZyb20gXCIuLi9FbGVtZW50cy9QYXJzZURlY2xhcmF0aW9uXCI7XHJcbmltcG9ydCB7UGFyc2VSZXR1cm59IGZyb20gXCIuLi9FbGVtZW50cy9QYXJzZVJldHVyblwiO1xyXG5pbXBvcnQge1BhcnNlVmFyaWFibGV9IGZyb20gXCIuLi9FbGVtZW50cy9TY2FsYXJzL1BhcnNlVmFyaWFibGVcIjtcclxuaW1wb3J0IHtQYXJzZUZpeGVkfSBmcm9tIFwiLi4vRWxlbWVudHMvU2NhbGFycy9QYXJzZUZpeGVkXCI7XHJcbmltcG9ydCB7UGFyc2VGdW5jfSBmcm9tICcuLi9FbGVtZW50cy9TY2FsYXJzL1BhcnNlRnVuYyc7XHJcbmltcG9ydCB7UGFyc2VNZXRob2R9IGZyb20gXCIuLi9FbGVtZW50cy9TY2FsYXJzL1BhcnNlTWV0aG9kXCI7XHJcbmltcG9ydCB7UGFyc2VBcnJheUl0ZW19IGZyb20gXCIuLi9FbGVtZW50cy9QYXJzZUFycmF5SXRlbVwiO1xyXG5cclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZUZhY3Rvcnkge1xyXG4gICAgcHVibGljIHN0YXRpYyBHZXRQYXJzZUVsZW1lbnQocGFyZW50OlBhcnNlckVsZW1lbnRCYXNlLGVsZW1lbnQ6YW55KVxyXG4gICAge1xyXG4gICAgICAgIGlmKGVsZW1lbnQ9PW51bGwpXHJcbiAgICAgICAgICAgIHJldHVybiBudWxsO1xyXG4gICAgICAgIHN3aXRjaCAoZWxlbWVudC50eXBlKSB7XHJcbiAgICAgICAgICAgIGNhc2UgJ05VTUJFUic6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlck51bWJlcihwYXJlbnQsZWxlbWVudCk7XHJcbiAgICAgICAgICAgIGNhc2UgJ0JPT0xFQU4nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIG5ldyBQYXJzZXJCb29sZWFuKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnU1RSSU5HJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VyU3RyaW5nKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnTUFUSCc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlTWF0aEZ1bmN0aW9uKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnTVVMJzpcclxuICAgICAgICAgICAgY2FzZSAnQUREJzpcclxuICAgICAgICAgICAgY2FzZSAnU1VCJzpcclxuICAgICAgICAgICAgY2FzZSAnRElWJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VBcml0aG1ldGljYWwocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdTRU5URU5DRSc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlU2VudGVuY2UocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdQJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VQYXJlbnRoZXNpcyhwYXJlbnQsZWxlbWVudCk7XHJcbiAgICAgICAgICAgIGNhc2UgJ0NPTkRTRU5URU5DRSc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlQ29uZGl0aW9uU2VudGVuY2UocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdDT01QQVJBVE9SJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VDb21wYXJhdG9yKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnQ09ORElUSU9OJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VDb25kaXRpb24ocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdGSUVMRCc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlRmllbGQocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdBUlInOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIG5ldyBQYXJzZUFycmF5KHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnTkVHQVRJT04nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIG5ldyBQYXJzZU5lZ2F0aW9uKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnQkxPQ0snOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIG5ldyBQYXJzZUJsb2NrKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnREVDTEFSQVRJT04nOlxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIG5ldyBQYXJzZURlY2xhcmF0aW9uKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnUkVUVVJOJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VSZXR1cm4ocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdWQVJJQUJMRSc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlVmFyaWFibGUocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdGSVhFRCc6XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlRml4ZWQocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdGVU5DJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VGdW5jKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgY2FzZSAnTUVUSE9EJzpcclxuICAgICAgICAgICAgICAgIHJldHVybiBuZXcgUGFyc2VNZXRob2QocGFyZW50LGVsZW1lbnQpO1xyXG4gICAgICAgICAgICBjYXNlICdBUlJJVEVNJzpcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbmV3IFBhcnNlQXJyYXlJdGVtKHBhcmVudCxlbGVtZW50KTtcclxuICAgICAgICAgICAgZGVmYXVsdDpcclxuICAgICAgICAgICAgICAgIHRocm93IEVycm9yKCdJbnZhbGlkIHRva2VuICcrZWxlbWVudC50eXBlKTtcclxuXHJcblxyXG4gICAgICAgIH1cclxuICAgIH1cclxufSIsImltcG9ydCB7UGFyc2VyRWxlbWVudEJhc2V9IGZyb20gXCIuLi9Db3JlL1BhcnNlckVsZW1lbnRCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VGYWN0b3J5fSBmcm9tIFwiLi4vQ29yZS9QYXJzZUZhY3RvcnlcIjtcclxuaW1wb3J0IHtQYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNlfSBmcm9tIFwiLi4vQ29yZS9QYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNlXCI7XHJcbmltcG9ydCB7UGFyc2VSZXR1cm59IGZyb20gXCIuL1BhcnNlUmV0dXJuXCI7XHJcbmltcG9ydCB7IEV4ZWN1dGlvbkNoYWluIH0gZnJvbSBcIiNEeW5hbWljcy9Gb3JtQnVpbGRlci9Gb3JtQnVpbGRlckNvcmUvVXRpbGl0aWVzL0V4ZWN1dGlvbkNoYWluXCI7XHJcbmltcG9ydCB7RmllbGRCYXNlTW9kZWx9IGZyb20gXCIjRHluYW1pY3MvRm9ybUJ1aWxkZXIvRm9ybUJ1aWxkZXJDb3JlL0ZpZWxkQmFzZS5Nb2RlbFwiO1xyXG5cclxuXHJcbmV4cG9ydCBjbGFzcyBQYXJzZU1haW4gZXh0ZW5kcyBQYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNle1xyXG5cclxuICAgIFNlbnRlbmNlczpQYXJzZXJFbGVtZW50QmFzZVtdO1xyXG4gICAgRXhlY3V0aW9uQ2hhaW46RXhlY3V0aW9uQ2hhaW47XHJcbiAgICBPd25lcjpGaWVsZEJhc2VNb2RlbDtcclxuICAgIHByaXZhdGUgVmFyaWFibGVzOntOYW1lOnN0cmluZyxWYWx1ZTphbnl9W107XHJcbiAgICBjb25zdHJ1Y3RvcihwdWJsaWMgRmllbGRMaXN0OkZpZWxkQmFzZU1vZGVsW10sRGF0YTogYW55LE93bmVyOkZpZWxkQmFzZU1vZGVsPW51bGwsIEV4ZWN1dGlvbkNoYWluOkV4ZWN1dGlvbkNoYWluPW51bGwpIHtcclxuICAgICAgICBzdXBlcihudWxsLERhdGEpO1xyXG4gICAgICAgIHRoaXMuT3duZXI9T3duZXI7XHJcbiAgICAgICAgdGhpcy5FeGVjdXRpb25DaGFpbj1FeGVjdXRpb25DaGFpbjtcclxuICAgICAgICB0aGlzLlNlbnRlbmNlcz1bXTtcclxuICAgICAgICB0aGlzLlZhcmlhYmxlcz1bXTtcclxuICAgICAgICBpZih0aGlzLkRhdGEubGVuZ3RoPjApXHJcbiAgICAgICAgICAgIGZvcihsZXQgc2VudGVuY2Ugb2YgdGhpcy5EYXRhWzBdLlNlbnRlbmNlcylcclxuICAgICAgICAgICAgICAgIHRoaXMuU2VudGVuY2VzLnB1c2goUGFyc2VGYWN0b3J5LkdldFBhcnNlRWxlbWVudCh0aGlzLHNlbnRlbmNlKSk7XHJcbiAgICB9XHJcblxyXG4gICAgcHJpdmF0ZSBJbnRlcm5hbFBhcnNlKCl7XHJcbiAgICAgICAgbGV0IGRlZmF1bHRSZXR1cm49bnVsbDtcclxuICAgICAgICBmb3IobGV0IHNlbnRlbmNlIG9mIHRoaXMuU2VudGVuY2VzKVxyXG4gICAgICAgIHtcclxuICAgICAgICAgICAgaWYoc2VudGVuY2UgaW5zdGFuY2VvZiBQYXJzZVJldHVybilcclxuICAgICAgICAgICAgICAgIHJldHVybiBzZW50ZW5jZS5QYXJzZSgpO1xyXG5cclxuICAgICAgICAgICAgbGV0IHJlc3VsdD1zZW50ZW5jZS5QYXJzZSgpO1xyXG4gICAgICAgICAgICBpZihyZXN1bHQgaW5zdGFuY2VvZiBQYXJzZVJldHVybilcclxuICAgICAgICAgICAgICAgIHJldHVybiByZXN1bHQuUGFyc2UoKTtcclxuICAgICAgICAgICAgaWYocmVzdWx0IT09bnVsbCkge1xyXG5cclxuICAgICAgICAgICAgICAgIGRlZmF1bHRSZXR1cm49IHJlc3VsdDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gZGVmYXVsdFJldHVybjtcclxuICAgIH1cclxuXHJcbiAgICBQYXJzZSgpIHtcclxuICAgICAgICBsZXQgcmVzdWx0PXRoaXMuSW50ZXJuYWxQYXJzZSgpO1xyXG4gICAgICAgIGlmKEFycmF5LmlzQXJyYXkocmVzdWx0KSlcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIHJldHVybiAocmVzdWx0IGFzIGFueVtdKS5yZWR1Y2UoKChhY2MsY3VycmVudFZhbHVlKT0+e3JldHVybiBhY2MrdGhpcy5QYXJzZVNpbmdsZU51bWJlcihjdXJyZW50VmFsdWUpfSksMCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLlBhcnNlU2luZ2xlTnVtYmVyKHJlc3VsdCk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIHByaXZhdGUgUGFyc2VTaW5nbGVOdW1iZXIoZWxlbWVudCl7XHJcbiAgICAgICAgaWYoZWxlbWVudD09bnVsbClcclxuICAgICAgICAgICAgcmV0dXJuIDA7XHJcbiAgICAgICAgaWYgKGVsZW1lbnQgaW5zdGFuY2VvZiBGaWVsZEJhc2VNb2RlbCkge1xyXG4gICAgICAgICAgICByZXR1cm4gdGhpcy5HZXRQcmljZUZyb21GaWVsZChlbGVtZW50KTtcclxuICAgICAgICB9XHJcbiAgICAgICAgbGV0IGZsb2F0PXBhcnNlRmxvYXQoZWxlbWVudCk7XHJcbiAgICAgICAgaWYoaXNOYU4oZmxvYXQpKVxyXG4gICAgICAgICAgICByZXR1cm4gMDtcclxuICAgICAgICByZXR1cm4gZmxvYXQ7XHJcbiAgICB9XHJcblxyXG4gICAgUGFyc2VUZXh0KCl7XHJcbiAgICAgICAgbGV0IHJlc3VsdD10aGlzLkludGVybmFsUGFyc2UoKTtcclxuICAgICAgICBpZihBcnJheS5pc0FycmF5KHJlc3VsdCkpXHJcbiAgICAgICAge1xyXG4gICAgICAgICAgICByZXR1cm4gcmVzdWx0Lm1hcCh4PT50aGlzLlBhcnNlU2luZ2xlVGV4dCh4KSkuam9pbignLCAnKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuUGFyc2VTaW5nbGVUZXh0KHJlc3VsdCk7XHJcbiAgICB9XHJcblxyXG4gICAgcHJpdmF0ZSBQYXJzZVNpbmdsZVRleHQoZWxlbWVudCl7XHJcblxyXG4gICAgICAgIGlmKGVsZW1lbnQ9PW51bGwpXHJcbiAgICAgICAgICAgIHJldHVybiAnJztcclxuICAgICAgICBpZiAoZWxlbWVudCBpbnN0YW5jZW9mIEZpZWxkQmFzZU1vZGVsKSB7XHJcbiAgICAgICAgICAgIHJldHVybiAoZWxlbWVudCBhcyBGaWVsZEJhc2VNb2RlbCkuVG9UZXh0KCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBlbGVtZW50LnRvU3RyaW5nKCk7XHJcbiAgICB9XHJcblxyXG4gICAgcHVibGljIFNldFZhcmlhYmxlKHZhcmlhYmxlTmFtZTpzdHJpbmcsdmFsdWU6YW55KVxyXG4gICAge1xyXG4gICAgICAgIGxldCB2YXJpYWJsZT10aGlzLlZhcmlhYmxlcy5maW5kKHg9PnguTmFtZT09dmFyaWFibGVOYW1lKTtcclxuICAgICAgICBpZih2YXJpYWJsZT09bnVsbClcclxuICAgICAgICB7XHJcbiAgICAgICAgICAgIHZhcmlhYmxlPXtOYW1lOnZhcmlhYmxlTmFtZSxWYWx1ZTpudWxsfTtcclxuICAgICAgICAgICAgdGhpcy5WYXJpYWJsZXMucHVzaCh2YXJpYWJsZSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICB2YXJpYWJsZS5WYWx1ZT12YWx1ZTtcclxuXHJcbiAgICB9XHJcblxyXG4gICAgcHVibGljIEdldFZhcmlhYmxlKHZhcmlhYmxlTmFtZTpzdHJpbmcpOmFueVxyXG4gICAge1xyXG4gICAgICAgIHJldHVybiB0aGlzLlZhcmlhYmxlcy5maW5kKHg9PnguTmFtZT09dmFyaWFibGVOYW1lKT8uVmFsdWU7XHJcblxyXG4gICAgfVxyXG5cclxufSIsImltcG9ydCB7RXZlbnRNYW5hZ2VyfSBmcm9tIFwiI0R5bmFtaWNzL1NoYXJlZC9Db3JlL0V2ZW50cy9FdmVudE1hbmFnZXJcIjtcclxuaW1wb3J0IHtQYXJzZU1haW59IGZyb20gXCIjRHluYW1pY3MvUFIvRm9ybXVsYS9Gb211bGFDb3JlL0VsZW1lbnRzL1BhcnNlTWFpblwiO1xyXG5pbXBvcnQge1ByZWZlcnJlZFJldHVyblR5cGV9IGZyb20gXCIjRHluYW1pY3MvRm9ybUJ1aWxkZXIvRm9ybUJ1aWxkZXJDb3JlL1V0aWxpdGllcy9QcmVmZXJyZWRSZXR1cm5UeXBlXCI7XHJcblxyXG5cclxuRXZlbnRNYW5hZ2VyLlN1YnNjcmliZShcIkNhbGN1bGF0ZUZvcm11bGFcIixlPT57XHJcblxyXG4gICAgaWYoZS5Gb3JtdWxhLkNvbXBpbGVkPT1udWxsKVxyXG4gICAge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKCdGb3JtdWxhIGlzIG5vdCBjb21waWxlZCcsZSk7XHJcbiAgICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG4gICAgbGV0IHBhcnNlPW5ldyBQYXJzZU1haW4oZS5GaWVsZExpc3QsZS5Gb3JtdWxhLkNvbXBpbGVkLGUuT3duZXIsZS5DaGFpbik7XHJcbiAgICB0cnl7XHJcblxyXG4gICAgICAgIGlmKGUuRm9ybXVsYS5QcmVmZXJyZWRSZXR1cm5UeXBlPT1QcmVmZXJyZWRSZXR1cm5UeXBlLlByaWNlKVxyXG4gICAgICAgICAgICByZXR1cm4gcGFyc2UuUGFyc2UoKTtcclxuICAgICAgICBlbHNlXHJcbiAgICAgICAgICAgIHJldHVybiBwYXJzZS5QYXJzZVRleHQoKTtcclxuICAgIH1jYXRjaCAoZSkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKGUpO1xyXG4gICAgICAgIHJldHVybiAwO1xyXG4gICAgfVxyXG59KTsiXSwibmFtZXMiOlsiUGFyc2VyRWxlbWVudEJhc2UiLCJjb25zdHJ1Y3RvciIsIlBhcmVudCIsIkRhdGEiLCJHZXRNYWluIiwiUGFyc2VyTnVtYmVyIiwiUGFyc2UiLCJwYXJzZUZsb2F0IiwiZCIsIlBhcnNlckJvb2xlYW4iLCJWYWx1ZSIsIlBhcnNlclN0cmluZyIsIlRleHQiLCJQYXJzZXJFbGVtZW50VGhhdFVzZXNGaWVsZHNCYXNlIiwiR2V0UHJpY2VGcm9tRmllbGQiLCJmaWVsZCIsIk93bmVyIiwiR2V0UHJpY2VXaXRob3V0Rm9ybXVsYSIsIkdldFByaWNlIiwiUGFyc2VBcml0aG1ldGljYWwiLCJMZWZ0IiwiUGFyc2VGYWN0b3J5IiwiR2V0UGFyc2VFbGVtZW50IiwiUmlnaHQiLCJ0eXBlIiwiR2V0U2NhbGFyT3JQcmljZSIsImxlZnQiLCJUb1NjYWxhciIsInJpZ2h0IiwiRmllbGRCYXNlTW9kZWwiLCJUb1RleHQiLCJkYXRhIiwicGFyc2UiLCJBcnJheSIsImlzQXJyYXkiLCJyZWR1Y2UiLCJwcmV2aW91c1ZhbHVlIiwiY3VycmVudFZhbHVlIiwiUGFyc2VNYXRoRnVuY3Rpb24iLCJDaGlsZCIsIm9wIiwiTWF0aCIsInNpbiIsImNvcyIsInRhbiIsImFzaW4iLCJhdGFuIiwiYWNvcyIsInNxcnQiLCJsb2ciLCJQYXJzZVNlbnRlbmNlIiwiU2VudGVuY2UiLCJOZXh0IiwiUGFyc2VQYXJlbnRoZXNpcyIsIkFyZ3MiLCJjdXJyZW50IiwicHVzaCIsImxlbmd0aCIsIlBhcnNlQ29uZGl0aW9uU2VudGVuY2UiLCJDb25kaXRpb24iLCJSZXN1bHQiLCJQYXJzZUNvbXBhcmF0b3IiLCJvcGVyYXRvciIsIm9yaWdpbmFsTGVmdCIsIm9yaWdpbmFsUmlnaHQiLCJsZWZ0VmFsdWUiLCJyaWdodFZhbHVlIiwiaGF5c3RhY2siLCJuZWVkbGUiLCJNdWx0aXBsZU9wdGlvbnNCYXNlTW9kZWwiLCJHZXRTZWxlY3RlZE9wdGlvbnMiLCJtYXAiLCJ4IiwiTGFiZWwiLCJpIiwiY3VycmVudE5lZWRsZSIsInNvbWUiLCJQYXJzZUNvbmRpdGlvbiIsIk9wZXJhdGlvbiIsIkNvbXBhcmF0b3IiLCJpc1RydWUiLCJuZXh0SXNUcnVlIiwiUGFyc2VGaWVsZCIsIkZpZWxkSWQiLCJJZCIsIkZpZWxkIiwiRmllbGRMaXN0IiwiZmluZCIsIk9wdGlvbnMiLCJQYXJzZUFycmF5IiwiRWxlbWVudHMiLCJQYXJzZU5lZ2F0aW9uIiwiUGFyc2VSZXR1cm4iLCJQYXJzZUJsb2NrIiwiU2VudGVuY2VzIiwic2VudGVuY2UiLCJkZWZhdWx0UmV0dXJuIiwicmVzdWx0IiwiUGFyc2VEZWNsYXJhdGlvbiIsIlZhcmlhYmxlTmFtZSIsIk5hbWUiLCJBc3NpZ25tZW50IiwidmFsdWUiLCJTZXRWYXJpYWJsZSIsIlBhcnNlVmFyaWFibGUiLCJHZXRWYXJpYWJsZSIsIlBhcnNlRml4ZWQiLCJDb25maWciLCJqc29uIiwiSlNPTiIsIkdldEZpeGVkVmFsdWUiLCJNZXRob2REaWN0aW9uYXJ5IiwiR2V0TnVtYmVyIiwibnVtYmVyIiwiTnVtYmVyIiwiaXNOYU4iLCJHZXRUZXh0IiwidG9TdHJpbmciLCJSb3VuZCIsIm51bWJlck9mRGVjaW1hbHMiLCJ0b0ZpeGVkIiwiQ2VpbCIsImNlaWwiLCJQYXJzZUZ1bmMiLCJNZXRob2QiLCJhcHBseSIsIkVycm9yIiwiUGFyc2VNZXRob2QiLCJPYmplY3QiLCJHZXROYW1lVG9Vc2UiLCJuYW1lVG9Vc2UiLCJQYXJzZUFycmF5SXRlbSIsIkluZGV4IiwiYXJyYXkiLCJ1bmRlZmluZWQiLCJwYXJlbnQiLCJlbGVtZW50IiwiUGFyc2VNYWluIiwiRXhlY3V0aW9uQ2hhaW4iLCJWYXJpYWJsZXMiLCJJbnRlcm5hbFBhcnNlIiwiYWNjIiwiUGFyc2VTaW5nbGVOdW1iZXIiLCJmbG9hdCIsIlBhcnNlVGV4dCIsIlBhcnNlU2luZ2xlVGV4dCIsImpvaW4iLCJ2YXJpYWJsZU5hbWUiLCJ2YXJpYWJsZSIsIkV2ZW50TWFuYWdlciIsIlN1YnNjcmliZSIsImUiLCJGb3JtdWxhIiwiQ29tcGlsZWQiLCJjb25zb2xlIiwiQ2hhaW4iLCJQcmVmZXJyZWRSZXR1cm5UeXBlIiwiUHJpY2UiXSwibWFwcGluZ3MiOiI7O0lBRU8sTUFBZUEsaUJBQWYsQ0FBaUM7SUFDcENDLEVBQUFBLFdBQVcsQ0FBUUMsTUFBUixFQUF3Q0MsSUFBeEMsRUFBa0Q7SUFBQSxTQUExQ0QsTUFBMEMsR0FBMUNBLE1BQTBDO0lBQUEsU0FBVkMsSUFBVSxHQUFWQSxJQUFVO0lBQzVEOztJQUVNQyxFQUFBQSxPQUFQLEdBQ0E7SUFDSSxRQUFHLEtBQUtGLE1BQUwsSUFBYSxJQUFoQixFQUNJLE9BQU8sSUFBUDtJQUVKLFdBQU8sS0FBS0EsTUFBTCxDQUFZRSxPQUFaLEVBQVA7SUFDSDs7SUFWbUM7O0lDQWpDLE1BQU1DLFlBQU4sU0FBMkJMLGlCQUEzQixDQUE0QztJQUUvQ0MsRUFBQUEsV0FBVyxDQUFDQyxNQUFELEVBQTBCQyxJQUExQixFQUFxQztJQUM1QyxVQUFNRCxNQUFOLEVBQWFDLElBQWI7SUFFSDs7SUFFTUcsRUFBQUEsS0FBUCxHQUFjO0lBQ1YsV0FBT0MsVUFBVSxDQUFDLEtBQUtKLElBQUwsQ0FBVUssQ0FBWCxDQUFqQjtJQUNIOztJQVQ4Qzs7SUNBNUMsTUFBTUMsYUFBTixTQUE0QlQsaUJBQTVCLENBQTZDO0lBRWhEQyxFQUFBQSxXQUFXLENBQUNDLE1B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
