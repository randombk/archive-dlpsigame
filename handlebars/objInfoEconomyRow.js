(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['objInfoEconomyRow.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, stack2, options, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<span class=\"itemLink\" data-type=\"diff\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-parameters=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\r\n		";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<span class=\"itemLink\" data-type=\"diff\" data-item=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-quantitysign=\"-\" data-parameters=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\r\n		";
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n			<input class=\"activity_";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " activityInput\" data-id=\"";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" type=\"number\" value=\"";
  if (stack1 = helpers.activity) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.activity; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" min=\"0\" max=\"100\">%\r\n		";
  return buffer;
  }

  buffer += "<tr class=\"";
  if (stack1 = helpers['class']) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0['class']; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n	<td>";
  if (stack1 = helpers.itemName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n	<td>\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data};
  stack2 = ((stack1 = helpers.key_value_object || depth0.key_value_object),stack1 ? stack1.call(depth0, depth0.production, options) : helperMissing.call(depth0, "key_value_object", depth0.production, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n\r\n	</td>\r\n	<td>\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data};
  stack2 = ((stack1 = helpers.key_value_object || depth0.key_value_object),stack1 ? stack1.call(depth0, depth0.consumption, options) : helperMissing.call(depth0, "key_value_object", depth0.consumption, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n	<td>\r\n		";
  options = {hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data};
  stack2 = ((stack1 = helpers.ifdef || depth0.ifdef),stack1 ? stack1.call(depth0, depth0.activity, options) : helperMissing.call(depth0, "ifdef", depth0.activity, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "\r\n	</td>\r\n</tr>\r\n";
  return buffer;
  });
})();