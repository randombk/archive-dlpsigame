(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['hoverItem.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, options, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<th colspan=\"2\">";
  if (stack1 = helpers.itemName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</th>\r\n					";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<th colspan=\"1\">";
  if (stack1 = helpers.itemName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</th>\r\n					";
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Type: ";
  if (stack1 = helpers.itemType) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemType; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n						<td class=\"dataHolder\">Quantity: ";
  if (stack1 = helpers.quantity) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.quantity; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Type: ";
  if (stack1 = helpers.itemType) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemType; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Weight: ";
  if (stack1 = helpers.itemWeight) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemWeight; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n						<td class=\"dataHolder\">Total Weight: ";
  if (stack1 = helpers.itemTotalWeight) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemTotalWeight; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program11(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Weight: ";
  if (stack1 = helpers.itemWeight) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemWeight; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program13(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Value: ";
  if (stack1 = helpers.itemValue) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemValue; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n						<td class=\"dataHolder\">Total Value: ";
  if (stack1 = helpers.itemTotalValue) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemTotalValue; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Value: ";
  if (stack1 = helpers.itemValue) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemValue; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program17(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += "\r\n					<tr>\r\n						<td class=\"itemDescHolder\" style=\"color: #BB0000\" colspan=\"3\">\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(18, program18, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoTrade), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoTrade), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(20, program20, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoTransfer), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoTransfer), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(22, program22, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoDestroy), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoDestroy), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(24, program24, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.Unique), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.Unique), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(26, program26, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.Usable), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.Usable), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(28, program28, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoTransport), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.NoTransport), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n							";
  options = {hash:{},inverse:self.noop,fn:self.program(30, program30, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.Fuel), options) : helperMissing.call(depth0, "ifdef", ((stack1 = depth0.itemFlags),stack1 == null || stack1 === false ? stack1 : stack1.Fuel), options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n						</td>\r\n					</tr>\r\n				";
  return buffer;
  }
function program18(depth0,data) {
  
  
  return "\r\n								This item may not be traded<br>\r\n							";
  }

function program20(depth0,data) {
  
  
  return "\r\n								This item may not be transferred to another player<br>\r\n							";
  }

function program22(depth0,data) {
  
  
  return "\r\n								This item may not be destroyed or discarded<br>\r\n							";
  }

function program24(depth0,data) {
  
  
  return "\r\n								This item is unique<br>\r\n							";
  }

function program26(depth0,data) {
  
  
  return "\r\n								This item is usable<br>\r\n							";
  }

function program28(depth0,data) {
  
  
  return "\r\n								This item may not be transported on ships<br>\r\n							";
  }

function program30(depth0,data) {
  
  
  return "\r\n								This item may be used a fuel for ships<br>\r\n							";
  }

  buffer += "<div class=\"tooltipContent\">\r\n	<span class=\"tableHolder\">\r\n		<table class=\"hoverTable\">\r\n			<tbody>\r\n				<tr>\r\n					<th rowspan=\"4\" class=\"itemImgHolder\"><img src=\"http://placehold.it/100x80/BBB&text=";
  if (stack1 = helpers.itemImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></th>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(7, program7, data),fn:self.program(5, program5, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(11, program11, data),fn:self.program(9, program9, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(15, program15, data),fn:self.program(13, program13, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					<td class=\"itemDescHolder\" colspan=\"3\">";
  if (stack1 = helpers.itemDesc) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemDesc; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</td>\r\n				</tr>\r\n				";
  options = {hash:{},inverse:self.noop,fn:self.program(17, program17, data),data:data};
  stack2 = ((stack1 = helpers.ifnotempty || depth0.ifnotempty),stack1 ? stack1.call(depth0, depth0.itemFlags, options) : helperMissing.call(depth0, "ifnotempty", depth0.itemFlags, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n			</tbody>\r\n		</table>\r\n	</span>\r\n</div>";
  return buffer;
  });
})();