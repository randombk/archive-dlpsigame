(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['buildingInfo.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n						<th colspan=\"4\">";
  if (stack1 = helpers.buildName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</th>\n					";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n						<th colspan=\"3\">";
  if (stack1 = helpers.buildName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</th>\n					";
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n						<td class=\"dataHolder\"><b>Next Level: ";
  if (stack1 = helpers.nextLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.nextLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</b></td>\n					";
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Hourly Consumption</b></td>\n						<td class=\"dataHolder\">\n							";
  stack1 = helpers['if'].call(depth0, depth0.curConsumption, {hash:{},inverse:self.program(11, program11, data),fn:self.program(8, program8, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n						</td>\n						";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(13, program13, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program8(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n								";
  options = {hash:{},inverse:self.noop,fn:self.program(9, program9, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.curConsumption, options) : helperMissing.call(depth0, "key_value", depth0.curConsumption, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							";
  return buffer;
  }
function program9(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									<span class=\"itemLink\" style=\"display: block;\" data-type=\"diff\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-quantity=\"-";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\n								";
  return buffer;
  }

function program11(depth0,data) {
  
  
  return "\n								(none)\n							";
  }

function program13(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack1 = helpers['if'].call(depth0, depth0.nextResConsumption, {hash:{},inverse:self.program(17, program17, data),fn:self.program(14, program14, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program14(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n									";
  options = {hash:{},inverse:self.noop,fn:self.program(15, program15, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.nextResConsumption, options) : helperMissing.call(depth0, "key_value", depth0.nextResConsumption, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n								";
  return buffer;
  }
function program15(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n										<span class=\"itemLink\" style=\"display: block;\" data-type=\"diff\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-quantity=\"-";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\n									";
  return buffer;
  }

function program17(depth0,data) {
  
  
  return "\n									(none)\n								";
  }

function program19(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Hourly Production</b></td>\n						<td class=\"dataHolder\">\n							";
  stack1 = helpers['if'].call(depth0, depth0.curProduction, {hash:{},inverse:self.program(11, program11, data),fn:self.program(20, program20, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n						</td>\n						";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(23, program23, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program20(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n								";
  options = {hash:{},inverse:self.noop,fn:self.program(21, program21, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.curProduction, options) : helperMissing.call(depth0, "key_value", depth0.curProduction, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							";
  return buffer;
  }
function program21(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									<span class=\"itemLink\" style=\"display: block;\" data-type=\"diff\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-quantity=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\n								";
  return buffer;
  }

function program23(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack1 = helpers['if'].call(depth0, depth0.nextResProduction, {hash:{},inverse:self.program(17, program17, data),fn:self.program(24, program24, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program24(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n									";
  options = {hash:{},inverse:self.noop,fn:self.program(25, program25, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.nextResProduction, options) : helperMissing.call(depth0, "key_value", depth0.nextResProduction, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n								";
  return buffer;
  }
function program25(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n										<span class=\"itemLink\" style=\"display: block;\" data-type=\"diff\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-quantity=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\n									";
  return buffer;
  }

function program27(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Net Resource Changes</b></td>\n						<td class=\"dataHolder\">\n							";
  stack1 = helpers['if'].call(depth0, depth0.curNetChange, {hash:{},inverse:self.program(11, program11, data),fn:self.program(28, program28, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n						</td>\n						";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(30, program30, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program28(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n								";
  options = {hash:{},inverse:self.noop,fn:self.program(21, program21, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.curNetChange, options) : helperMissing.call(depth0, "key_value", depth0.curNetChange, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							";
  return buffer;
  }

function program30(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack1 = helpers['if'].call(depth0, depth0.nextResChange, {hash:{},inverse:self.program(17, program17, data),fn:self.program(31, program31, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program31(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n									";
  options = {hash:{},inverse:self.noop,fn:self.program(25, program25, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.nextResChange, options) : helperMissing.call(depth0, "key_value", depth0.nextResChange, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n								";
  return buffer;
  }

function program33(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Modifiers</b></td>\n						<td class=\"dataHolder\">\n							";
  stack1 = helpers['if'].call(depth0, depth0.curModifiers, {hash:{},inverse:self.program(11, program11, data),fn:self.program(34, program34, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n						</td>\n						";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(37, program37, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program34(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n								";
  options = {hash:{},inverse:self.noop,fn:self.program(35, program35, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.curModifiers, options) : helperMissing.call(depth0, "key_value", depth0.curModifiers, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							";
  return buffer;
  }
function program35(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									<span class=\"modLink\" data-modID=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-amount=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " -- ";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\n								";
  return buffer;
  }

function program37(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack1 = helpers['if'].call(depth0, depth0.nextModifiers, {hash:{},inverse:self.program(17, program17, data),fn:self.program(38, program38, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program38(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\n									";
  options = {hash:{},inverse:self.noop,fn:self.program(39, program39, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.nextModifiers, options) : helperMissing.call(depth0, "key_value", depth0.nextModifiers, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n								";
  return buffer;
  }
function program39(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n										<span class=\"modLink\" data-modID=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-amount=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " -- ";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span>\n									";
  return buffer;
  }

function program41(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Weapons Research</b></td>\n						<td class=\"dataHolder\">\n							";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Weapons), {hash:{},inverse:self.program(44, program44, data),fn:self.program(42, program42, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n						</td>\n						";
  stack2 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(46, program46, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program42(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n								"
    + escapeExpression(((stack1 = ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Weapons)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n							";
  return buffer;
  }

function program44(depth0,data) {
  
  
  return "\n								0\n							";
  }

function program46(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Weapons), {hash:{},inverse:self.program(49, program49, data),fn:self.program(47, program47, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program47(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									"
    + escapeExpression(((stack1 = ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Weapons)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n								";
  return buffer;
  }

function program49(depth0,data) {
  
  
  return "\n									0\n								";
  }

function program51(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Defense Research</b></td>\n						<td class=\"dataHolder\">\n							";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Defense), {hash:{},inverse:self.program(44, program44, data),fn:self.program(52, program52, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n						</td>\n						";
  stack2 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(54, program54, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program52(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n								"
    + escapeExpression(((stack1 = ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Defense)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n							";
  return buffer;
  }

function program54(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Defense), {hash:{},inverse:self.program(49, program49, data),fn:self.program(55, program55, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program55(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									"
    + escapeExpression(((stack1 = ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Defense)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n								";
  return buffer;
  }

function program57(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Diplomatic Research</b></td>\n						<td class=\"dataHolder\">\n							";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Diplomatic), {hash:{},inverse:self.program(44, program44, data),fn:self.program(58, program58, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n						</td>\n						";
  stack2 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(60, program60, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program58(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n								"
    + escapeExpression(((stack1 = ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Diplomatic)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n							";
  return buffer;
  }

function program60(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Diplomatic), {hash:{},inverse:self.program(49, program49, data),fn:self.program(61, program61, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program61(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									"
    + escapeExpression(((stack1 = ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Diplomatic)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n								";
  return buffer;
  }

function program63(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Economic Research</b></td>\n						<td class=\"dataHolder\">\n							";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Economic), {hash:{},inverse:self.program(44, program44, data),fn:self.program(64, program64, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n						</td>\n						";
  stack2 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(66, program66, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program64(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n								"
    + escapeExpression(((stack1 = ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Economic)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n							";
  return buffer;
  }

function program66(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Economic), {hash:{},inverse:self.program(49, program49, data),fn:self.program(67, program67, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program67(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									"
    + escapeExpression(((stack1 = ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Economic)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n								";
  return buffer;
  }

function program69(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n					<tr>\n						<td class=\"dataHolder\"><b>Fleet Research</b></td>\n						<td class=\"dataHolder\">\n							";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Fleet), {hash:{},inverse:self.program(44, program44, data),fn:self.program(70, program70, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n						</td>\n						";
  stack2 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(72, program72, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n					</tr>\n				";
  return buffer;
  }
function program70(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n								"
    + escapeExpression(((stack1 = ((stack1 = depth0.curResearch),stack1 == null || stack1 === false ? stack1 : stack1.Fleet)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n							";
  return buffer;
  }

function program72(depth0,data) {
  
  var buffer = "", stack1, stack2;
  buffer += "\n							<td class=\"dataHolder\">\n								";
  stack2 = helpers['if'].call(depth0, ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Fleet), {hash:{},inverse:self.program(49, program49, data),fn:self.program(73, program73, data),data:data});
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\n							</td>\n						";
  return buffer;
  }
function program73(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n									"
    + escapeExpression(((stack1 = ((stack1 = depth0.nextResearch),stack1 == null || stack1 === false ? stack1 : stack1.Fleet)),typeof stack1 === functionType ? stack1.apply(depth0) : stack1))
    + "\n								";
  return buffer;
  }

  buffer += "<div class=\"tooltipContent\">\n	<span class=\"tableHolder\">\n		<table class=\"hoverTable\">\n			<tbody>\n				<tr>\n					";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				</tr>\n				<tr>\n					<td rowspan=\"60\" id=\"buildingImgHolder\">\n						<img src=\"http://placehold.it/125x125&text=";
  if (stack1 = helpers.buildImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n					</td>\n				</tr>\n				<tr>\n					<td class=\"dataHolder\"></td>\n					<td class=\"dataHolder\"><b>Level: ";
  if (stack1 = helpers.curLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.curLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " (Current)</b></td>\n					";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				</tr>\n					\n				";
  stack1 = helpers['if'].call(depth0, depth0.showConsumption, {hash:{},inverse:self.noop,fn:self.program(7, program7, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showProduction, {hash:{},inverse:self.noop,fn:self.program(19, program19, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showNetChange, {hash:{},inverse:self.noop,fn:self.program(27, program27, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showModifiers, {hash:{},inverse:self.noop,fn:self.program(33, program33, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showWeaponsResearch, {hash:{},inverse:self.noop,fn:self.program(41, program41, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showDefenseResearch, {hash:{},inverse:self.noop,fn:self.program(51, program51, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showDiplomaticResearch, {hash:{},inverse:self.noop,fn:self.program(57, program57, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showEconomicResearch, {hash:{},inverse:self.noop,fn:self.program(63, program63, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n				";
  stack1 = helpers['if'].call(depth0, depth0.showFleetResearch, {hash:{},inverse:self.noop,fn:self.program(69, program69, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n			</tbody>\n		</table>\n	</span>\n</div>\n";
  return buffer;
  });
})();